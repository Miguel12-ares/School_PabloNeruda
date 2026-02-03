<?php

/**
 * Middleware de Permisos
 * Verifica que el usuario tenga los permisos necesarios para realizar una acción
 */
class PermissionMiddleware {
    
    private AuthService $authService;
    
    public function __construct() {
        $this->authService = new AuthService();
    }
    
    /**
     * Verificar permiso
     * Muestra error 403 si no tiene permiso
     */
    public function requirePermission(string $modulo, string $accion): void {
        if (!$this->authService->hasPermission($modulo, $accion)) {
            $this->authService->registrarAccesoNoAutorizado($modulo, $accion);
            $this->mostrarError403($modulo, $accion);
        }
    }
    
    /**
     * Verificar uno de varios permisos (OR)
     */
    public function requireAnyPermission(array $permisos): void {
        foreach ($permisos as $permiso) {
            if ($this->authService->hasPermission($permiso['modulo'], $permiso['accion'])) {
                return; // Tiene al menos uno, permitir acceso
            }
        }
        
        // No tiene ninguno de los permisos
        $this->authService->registrarAccesoNoAutorizado(
            'multiple',
            'Requiere uno de varios permisos'
        );
        $this->mostrarError403('módulo', 'acción');
    }
    
    /**
     * Verificar todos los permisos (AND)
     */
    public function requireAllPermissions(array $permisos): void {
        foreach ($permisos as $permiso) {
            if (!$this->authService->hasPermission($permiso['modulo'], $permiso['accion'])) {
                $this->authService->registrarAccesoNoAutorizado(
                    $permiso['modulo'],
                    $permiso['accion']
                );
                $this->mostrarError403($permiso['modulo'], $permiso['accion']);
            }
        }
    }
    
    /**
     * Verificar rol específico
     */
    public function requireRole(string $nombreRol): void {
        if (!$this->authService->hasRole($nombreRol)) {
            $this->authService->registrarAccesoNoAutorizado(
                'roles',
                "Requiere rol: {$nombreRol}"
            );
            $this->mostrarError403('rol', $nombreRol);
        }
    }
    
    /**
     * Verificar acceso a curso (para maestros)
     */
    public function requireCursoAccess(int $cursoId): void {
        if (!$this->authService->maestroPuedeAccederCurso($cursoId)) {
            $this->authService->registrarAccesoNoAutorizado(
                'cursos',
                "Intento de acceso a curso ID: {$cursoId}"
            );
            $this->mostrarError403('curso', $cursoId);
        }
    }
    
    /**
     * Verificar si puede editar nota
     */
    public function requireNotaEditPermission(int $cursoId, int $materiaId): void {
        if (!$this->authService->maestroPuedeEditarNota($cursoId, $materiaId)) {
            $this->authService->registrarAccesoNoAutorizado(
                'notas',
                "Intento de editar nota - Curso: {$cursoId}, Materia: {$materiaId}"
            );
            $this->mostrarError403('notas', 'editar');
        }
    }
    
    /**
     * Mostrar página de error 403
     */
    private function mostrarError403(string $modulo, string $accion): void {
        http_response_code(403);
        
        $user = $this->authService->getCurrentUser();
        $rol = $this->authService->getPrimaryRole();
        
        include VIEWS_PATH . '/errors/403.php';
        exit;
    }
    
    /**
     * Verificar permiso y retornar booleano (sin bloquear)
     */
    public function checkPermission(string $modulo, string $accion): bool {
        return $this->authService->hasPermission($modulo, $accion);
    }
    
    /**
     * Verificar rol y retornar booleano (sin bloquear)
     */
    public function checkRole(string $nombreRol): bool {
        return $this->authService->hasRole($nombreRol);
    }
}

