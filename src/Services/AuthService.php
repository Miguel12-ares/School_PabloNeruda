<?php

/**
 * Servicio de Autenticación
 * Gestiona login, logout, sesiones y verificación de permisos
 */
class AuthService {
    
    private AuthRepository $authRepo;
    private LoginAttemptRepository $loginAttemptRepo;
    private AuditoriaRepository $auditoriaRepo;
    
    private const SESSION_TIMEOUT = 1800; // 30 minutos
    
    public function __construct() {
        $this->authRepo = new AuthRepository();
        $this->loginAttemptRepo = new LoginAttemptRepository();
        $this->auditoriaRepo = new AuditoriaRepository();
        
        $this->iniciarSesionSegura();
    }
    
    /**
     * Configurar sesión segura
     */
    private function iniciarSesionSegura(): void {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_only_cookies', '1');
            ini_set('session.cookie_secure', '0'); // Cambiar a 1 en producción con HTTPS
            session_start();
        }
        
        // Verificar timeout de sesión
        if (isset($_SESSION['ultimo_activity'])) {
            if (time() - $_SESSION['ultimo_activity'] > self::SESSION_TIMEOUT) {
                $this->logout();
                return;
            }
        }
        
        $_SESSION['ultimo_activity'] = time();
    }
    
    /**
     * Intentar login
     */
    public function login(string $username, string $password): array {
        // Verificar si está bloqueado
        if ($this->loginAttemptRepo->estaBloqueado($username)) {
            $tiempoRestante = $this->loginAttemptRepo->getTiempoRestanteBloqueo($username);
            return [
                'success' => false,
                'message' => "Usuario bloqueado. Intente nuevamente en {$tiempoRestante} minutos.",
                'blocked' => true
            ];
        }
        
        // Buscar usuario
        $user = $this->authRepo->findByUsername($username);
        
        if (!$user) {
            $this->loginAttemptRepo->registrarIntento($username, false);
            return [
                'success' => false,
                'message' => 'Credenciales inválidas'
            ];
        }
        
        // Verificar estado del usuario
        if ($user['estado'] !== 'activo') {
            $this->loginAttemptRepo->registrarIntento($username, false);
            return [
                'success' => false,
                'message' => 'Usuario inactivo o bloqueado. Contacte al administrador.'
            ];
        }
        
        // Verificar contraseña
        if (!password_verify($password, $user['password_hash'])) {
            $this->loginAttemptRepo->registrarIntento($username, false);
            return [
                'success' => false,
                'message' => 'Credenciales inválidas'
            ];
        }
        
        // Login exitoso
        $this->loginAttemptRepo->registrarIntento($username, true);
        
        // Obtener datos completos del usuario
        $userComplete = $this->authRepo->getUserComplete($user['id_usuario']);
        
        // Regenerar ID de sesión por seguridad
        session_regenerate_id(true);
        
        // Establecer datos de sesión
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nombre_completo'] = $user['nombre_completo'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['roles'] = $userComplete['roles'];
        $_SESSION['permisos'] = $userComplete['permisos'];
        $_SESSION['logged_in'] = true;
        $_SESSION['ultimo_activity'] = time();
        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        
        // Si es maestro, guardar cursos y materias
        if (isset($userComplete['cursos_asignados'])) {
            $_SESSION['cursos_asignados'] = $userComplete['cursos_asignados'];
            $_SESSION['materias_asignadas'] = $userComplete['materias_asignadas'];
        }
        
        // Actualizar último acceso
        $this->authRepo->updateLastAccess($user['id_usuario']);
        
        // Registrar en auditoría
        $this->auditoriaRepo->registrarAccion(
            $user['id_usuario'],
            'LOGIN_EXITOSO',
            'auth',
            'Usuario inició sesión correctamente'
        );
        
        return [
            'success' => true,
            'message' => 'Login exitoso',
            'user' => $userComplete
        ];
    }
    
    /**
     * Cerrar sesión
     */
    public function logout(): void {
        if (isset($_SESSION['user_id'])) {
            $this->auditoriaRepo->registrarAccion(
                $_SESSION['user_id'],
                'LOGOUT',
                'auth',
                'Usuario cerró sesión'
            );
        }
        
        session_unset();
        session_destroy();
        
        // Iniciar nueva sesión limpia
        session_start();
        session_regenerate_id(true);
    }
    
    /**
     * Verificar si usuario está autenticado
     */
    public function isAuthenticated(): bool {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    /**
     * Obtener usuario actual
     */
    public function getCurrentUser(): ?array {
        if (!$this->isAuthenticated()) {
            return null;
        }
        
        return [
            'id_usuario' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'nombre_completo' => $_SESSION['nombre_completo'] ?? null,
            'email' => $_SESSION['email'] ?? null,
            'roles' => $_SESSION['roles'] ?? [],
            'permisos' => $_SESSION['permisos'] ?? [],
            'cursos_asignados' => $_SESSION['cursos_asignados'] ?? [],
            'materias_asignadas' => $_SESSION['materias_asignadas'] ?? []
        ];
    }
    
    /**
     * Obtener ID del usuario actual
     */
    public function getCurrentUserId(): ?int {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Verificar si usuario tiene un permiso específico
     */
    public function hasPermission(string $modulo, string $accion): bool {
        if (!$this->isAuthenticated()) {
            return false;
        }
        
        $permisos = $_SESSION['permisos'] ?? [];
        
        foreach ($permisos as $permiso) {
            if ($permiso['modulo'] === $modulo && $permiso['accion'] === $accion) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Verificar si usuario tiene un rol específico
     */
    public function hasRole(string $nombreRol): bool {
        if (!$this->isAuthenticated()) {
            return false;
        }
        
        $roles = $_SESSION['roles'] ?? [];
        
        foreach ($roles as $rol) {
            if ($rol['nombre_rol'] === $nombreRol) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Obtener rol principal (el de mayor nivel)
     */
    public function getPrimaryRole(): ?array {
        if (!$this->isAuthenticated()) {
            return null;
        }
        
        $roles = $_SESSION['roles'] ?? [];
        return !empty($roles) ? $roles[0] : null;
    }
    
    /**
     * Verificar si maestro puede acceder a un curso
     */
    public function maestroPuedeAccederCurso(int $cursoId): bool {
        if (!$this->hasRole('Maestro')) {
            // Si no es maestro, verificar si tiene permiso general
            return $this->hasPermission('estudiantes', 'ver_todos');
        }
        
        $cursosAsignados = $_SESSION['cursos_asignados'] ?? [];
        
        foreach ($cursosAsignados as $curso) {
            if ($curso['id_curso'] == $cursoId) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Verificar si maestro puede editar nota de una materia
     */
    public function maestroPuedeEditarNota(int $cursoId, int $materiaId): bool {
        if (!$this->hasRole('Maestro')) {
            // Si no es maestro, verificar permiso general
            return $this->hasPermission('notas', 'editar');
        }
        
        $userId = $this->getCurrentUserId();
        return $this->authRepo->maestroPuedeEditarMateria($userId, $cursoId, $materiaId);
    }
    
    /**
     * Obtener cursos del usuario actual
     */
    public function getCursosUsuarioActual(): array {
        if ($this->hasRole('Maestro')) {
            // Si es maestro, devolver solo sus cursos asignados
            return $_SESSION['cursos_asignados'] ?? [];
        }
        
        if ($this->hasPermission('cursos', 'ver')) {
            // Si tiene permiso completo (Directivo/Administrativo), devolver todos los cursos
            $cursoRepo = new CursoRepository();
            return $cursoRepo->findAll();
        }
        
        // Por defecto, retornar array vacío
        return [];
    }
    
    /**
     * Registrar intento de acceso no autorizado
     */
    public function registrarAccesoNoAutorizado(string $modulo, string $accion, ?string $detalles = null): void {
        if (!$this->isAuthenticated()) {
            return;
        }
        
        $this->auditoriaRepo->registrarAccion(
            $this->getCurrentUserId(),
            'ACCESO_DENEGADO',
            $modulo,
            $detalles ?? "Intento de acceso a: {$modulo}.{$accion}"
        );
    }
    
    /**
     * Crear nuevo usuario (solo para administrativos)
     */
    public function crearUsuario(array $datos, array $roles = []): array {
        if (!$this->hasPermission('usuarios', 'crear')) {
            return [
                'success' => false,
                'message' => 'No tiene permisos para crear usuarios'
            ];
        }
        
        // Validar datos requeridos
        $camposRequeridos = ['username', 'email', 'password', 'nombre_completo'];
        foreach ($camposRequeridos as $campo) {
            if (empty($datos[$campo])) {
                return [
                    'success' => false,
                    'message' => "El campo {$campo} es requerido"
                ];
            }
        }
        
        // Verificar si username o email ya existen
        if ($this->authRepo->usernameExists($datos['username'])) {
            return [
                'success' => false,
                'message' => 'El nombre de usuario ya existe'
            ];
        }
        
        if ($this->authRepo->emailExists($datos['email'])) {
            return [
                'success' => false,
                'message' => 'El email ya está registrado'
            ];
        }
        
        // Hashear contraseña
        $passwordHash = password_hash($datos['password'], PASSWORD_BCRYPT);
        
        // Crear usuario
        $userData = [
            'username' => $datos['username'],
            'email' => $datos['email'],
            'password_hash' => $passwordHash,
            'nombre_completo' => $datos['nombre_completo'],
            'estado' => $datos['estado'] ?? 'activo'
        ];
        
        try {
            $userId = $this->authRepo->create($userData);
            
            // Asignar roles
            foreach ($roles as $roleId) {
                $this->authRepo->assignRole($userId, $roleId);
            }
            
            // Registrar en auditoría
            $this->auditoriaRepo->registrarAccion(
                $this->getCurrentUserId(),
                'CREAR_USUARIO',
                'usuarios',
                "Nuevo usuario creado: {$datos['username']}"
            );
            
            return [
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'user_id' => $userId
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al crear usuario: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Actualizar usuario
     */
    public function actualizarUsuario(int $userId, array $datos): array {
        if (!$this->hasPermission('usuarios', 'editar')) {
            return [
                'success' => false,
                'message' => 'No tiene permisos para editar usuarios'
            ];
        }
        
        // Verificar duplicados
        if (isset($datos['username']) && $this->authRepo->usernameExists($datos['username'], $userId)) {
            return [
                'success' => false,
                'message' => 'El nombre de usuario ya existe'
            ];
        }
        
        if (isset($datos['email']) && $this->authRepo->emailExists($datos['email'], $userId)) {
            return [
                'success' => false,
                'message' => 'El email ya está registrado'
            ];
        }
        
        // Si hay nueva contraseña, hashearla
        if (!empty($datos['password'])) {
            $datos['password_hash'] = password_hash($datos['password'], PASSWORD_BCRYPT);
            unset($datos['password']);
        }
        
        try {
            $this->authRepo->update($userId, $datos);
            
            // Registrar en auditoría
            $this->auditoriaRepo->registrarAccion(
                $this->getCurrentUserId(),
                'EDITAR_USUARIO',
                'usuarios',
                "Usuario ID {$userId} actualizado"
            );
            
            return [
                'success' => true,
                'message' => 'Usuario actualizado exitosamente'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al actualizar usuario: ' . $e->getMessage()
            ];
        }
    }
}

