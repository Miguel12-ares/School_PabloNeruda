<?php

/**
 * Controlador de Usuarios
 * Gestión CRUD de usuarios del sistema
 */
class UsuarioController {
    
    private AuthService $authService;
    private AuthRepository $authRepo;
    private PermissionMiddleware $permissionMiddleware;
    private AuditoriaRepository $auditoriaRepo;
    
    public function __construct() {
        $this->authService = new AuthService();
        $this->authRepo = new AuthRepository();
        $this->permissionMiddleware = new PermissionMiddleware();
        $this->auditoriaRepo = new AuditoriaRepository();
    }
    
    /**
     * Listar todos los usuarios
     */
    public function index(): void {
        $this->permissionMiddleware->requirePermission('usuarios', 'ver');
        
        $usuarios = $this->authRepo->findAllWithRoles();
        
        require_once VIEWS_PATH . '/usuarios/index.php';
    }
    
    /**
     * Mostrar formulario de creación
     */
    public function create(): void {
        $this->permissionMiddleware->requirePermission('usuarios', 'crear');
        
        $roles = $this->authRepo->getAllRoles();
        $cursos = (new CursoRepository())->findAll();
        $materias = (new MateriaRepository())->findAll();
        
        require_once VIEWS_PATH . '/usuarios/create.php';
    }
    
    /**
     * Guardar nuevo usuario
     */
    public function store(): void {
        $this->permissionMiddleware->requirePermission('usuarios', 'crear');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?controller=usuario&action=index');
            exit;
        }
        
        $datos = [
            'username' => $_POST['username'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'nombre_completo' => $_POST['nombre_completo'] ?? '',
            'estado' => $_POST['estado'] ?? 'activo'
        ];
        
        $roles = $_POST['roles'] ?? [];
        
        // Crear usuario
        $result = $this->authService->crearUsuario($datos, $roles);
        
        if ($result['success']) {
            $userId = $result['user_id'];
            
            // Si tiene rol de maestro, asignar cursos y materias
            if (in_array(1, $roles)) { // 1 = Rol Maestro
                $asignaciones = $_POST['asignaciones'] ?? [];
                foreach ($asignaciones as $asignacion) {
                    if (!empty($asignacion['curso']) && !empty($asignacion['materia'])) {
                        $this->authRepo->assignCursoToMaestro(
                            $userId,
                            $asignacion['curso'],
                            $asignacion['materia']
                        );
                    }
                }
            }
            
            $_SESSION['success'] = $result['message'];
            header('Location: /index.php?controller=usuario&action=index');
        } else {
            $_SESSION['error'] = $result['message'];
            header('Location: /index.php?controller=usuario&action=create');
        }
        exit;
    }
    
    /**
     * Mostrar formulario de edición
     */
    public function edit(): void {
        $this->permissionMiddleware->requirePermission('usuarios', 'editar');
        
        $userId = $_GET['id'] ?? 0;
        $usuario = $this->authRepo->getUserComplete($userId);
        
        if (!$usuario) {
            $_SESSION['error'] = 'Usuario no encontrado';
            header('Location: /index.php?controller=usuario&action=index');
            exit;
        }
        
        $roles = $this->authRepo->getAllRoles();
        $cursos = (new CursoRepository())->findAll();
        $materias = (new MateriaRepository())->findAll();
        
        // Obtener roles actuales del usuario
        $rolesUsuario = array_column($usuario['roles'], 'id_rol');
        
        // Si es maestro, obtener asignaciones
        $asignaciones = [];
        if (in_array(1, $rolesUsuario)) {
            $asignaciones = $this->getMaestroAsignaciones($userId);
        }
        
        require_once VIEWS_PATH . '/usuarios/edit.php';
    }
    
    /**
     * Actualizar usuario
     */
    public function update(): void {
        $this->permissionMiddleware->requirePermission('usuarios', 'editar');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?controller=usuario&action=index');
            exit;
        }
        
        $userId = $_POST['id_usuario'] ?? 0;
        
        $datos = [
            'username' => $_POST['username'] ?? '',
            'email' => $_POST['email'] ?? '',
            'nombre_completo' => $_POST['nombre_completo'] ?? '',
            'estado' => $_POST['estado'] ?? 'activo'
        ];
        
        // Si hay nueva contraseña
        if (!empty($_POST['password'])) {
            $datos['password'] = $_POST['password'];
        }
        
        // Actualizar usuario
        $result = $this->authService->actualizarUsuario($userId, $datos);
        
        if ($result['success']) {
            // Actualizar roles
            $nuevosRoles = $_POST['roles'] ?? [];
            $this->actualizarRolesUsuario($userId, $nuevosRoles);
            
            // Si tiene rol maestro, actualizar asignaciones
            if (in_array(1, $nuevosRoles)) {
                $this->actualizarAsignacionesMaestro($userId);
            }
            
            $_SESSION['success'] = 'Usuario actualizado correctamente';
        } else {
            $_SESSION['error'] = $result['message'];
        }
        
        header("Location: /index.php?controller=usuario&action=edit&id={$userId}");
        exit;
    }
    
    /**
     * Eliminar usuario
     */
    public function delete(): void {
        $this->permissionMiddleware->requirePermission('usuarios', 'eliminar');
        
        $userId = $_POST['id_usuario'] ?? 0;
        
        if ($userId == $this->authService->getCurrentUserId()) {
            $_SESSION['error'] = 'No puedes eliminar tu propio usuario';
            header('Location: /index.php?controller=usuario&action=index');
            exit;
        }
        
        if ($this->authRepo->delete($userId)) {
            $this->auditoriaRepo->registrarAccion(
                $this->authService->getCurrentUserId(),
                'ELIMINAR_USUARIO',
                'usuarios',
                "Usuario ID {$userId} eliminado"
            );
            
            $_SESSION['success'] = 'Usuario eliminado correctamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar usuario';
        }
        
        header('Location: /index.php?controller=usuario&action=index');
        exit;
    }
    
    /**
     * Ver detalle de usuario
     */
    public function view(): void {
        $this->permissionMiddleware->requirePermission('usuarios', 'ver');
        
        $userId = $_GET['id'] ?? 0;
        $usuario = $this->authRepo->getUserComplete($userId);
        
        if (!$usuario) {
            $_SESSION['error'] = 'Usuario no encontrado';
            header('Location: /index.php?controller=usuario&action=index');
            exit;
        }
        
        // Obtener estadísticas de actividad
        $estadisticas = $this->auditoriaRepo->getEstadisticasPorUsuario($userId);
        $ultimasAcciones = $this->auditoriaRepo->getUltimasAccionesUsuario($userId, 20);
        
        require_once VIEWS_PATH . '/usuarios/view.php';
    }
    
    /**
     * Actualizar roles de usuario
     */
    private function actualizarRolesUsuario(int $userId, array $nuevosRoles): void {
        // Obtener roles actuales
        $rolesActuales = $this->authRepo->getUserRoles($userId);
        $idsRolesActuales = array_column($rolesActuales, 'id_rol');
        
        // Roles a agregar
        $rolesAgregar = array_diff($nuevosRoles, $idsRolesActuales);
        foreach ($rolesAgregar as $rolId) {
            $this->authRepo->assignRole($userId, $rolId);
        }
        
        // Roles a remover
        $rolesRemover = array_diff($idsRolesActuales, $nuevosRoles);
        foreach ($rolesRemover as $rolId) {
            $this->authRepo->removeRole($userId, $rolId);
        }
    }
    
    /**
     * Actualizar asignaciones de curso/materia para maestro
     */
    private function actualizarAsignacionesMaestro(int $userId): void {
        // Por simplicidad, eliminar todas y recrear
        // En producción se podría optimizar comparando diferencias
        
        $asignaciones = $_POST['asignaciones'] ?? [];
        
        foreach ($asignaciones as $asignacion) {
            if (!empty($asignacion['curso']) && !empty($asignacion['materia'])) {
                $this->authRepo->assignCursoToMaestro(
                    $userId,
                    $asignacion['curso'],
                    $asignacion['materia']
                );
            }
        }
    }
    
    /**
     * Obtener asignaciones actuales de un maestro
     */
    private function getMaestroAsignaciones(int $userId): array {
        $stmt = $this->authRepo->db->prepare("
            SELECT mc.id_curso, mc.id_materia, c.nombre_curso, m.nombre_materia
            FROM maestro_curso mc
            INNER JOIN cursos c ON mc.id_curso = c.id_curso
            INNER JOIN materias m ON mc.id_materia = m.id_materia
            WHERE mc.id_usuario = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}

