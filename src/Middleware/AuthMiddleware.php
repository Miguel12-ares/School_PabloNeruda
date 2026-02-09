<?php

/**
 * Middleware de Autenticación
 * Verifica que el usuario esté autenticado antes de acceder a rutas protegidas
 */
class AuthMiddleware {
    
    private AuthService $authService;
    
    public function __construct() {
        $this->authService = new AuthService();
    }
    
    /**
     * Verificar si usuario está autenticado
     * Redirige al login si no lo está
     */
    public function requireAuth(): void {
        if (!$this->authService->isAuthenticated()) {
            // Guardar URL a la que intentaba acceder
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '';
            
            // Redirigir al login
            header('Location: /login');
            exit;
        }
    }
    
    /**
     * Verificar que el usuario NO esté autenticado (para páginas como login)
     * Redirige al dashboard si ya está autenticado
     */
    public function requireGuest(): void {
        if ($this->authService->isAuthenticated()) {
            $this->redirectToDashboard();
        }
    }
    
    /**
     * Redirigir al dashboard según el rol
     */
    private function redirectToDashboard(): void {
        $rol = $this->authService->getPrimaryRole();
        
        if (!$rol) {
            header('Location: /index.php');
            exit;
        }
        
        switch ($rol['nombre_rol']) {
            case 'Administrativo':
                header('Location: /dashboard/admin.php');
                break;
            case 'Directivo':
                header('Location: /dashboard/directivo.php');
                break;
            case 'Maestro':
                header('Location: /dashboard/maestro.php');
                break;
            default:
                header('Location: /index.php');
        }
        exit;
    }
    
    /**
     * Obtener URL de redirección después del login
     */
    public function getRedirectAfterLogin(): string {
        $redirect = $_SESSION['redirect_after_login'] ?? '';
        unset($_SESSION['redirect_after_login']);
        
        if (!empty($redirect) && $redirect !== '/login' && $redirect !== '/login.php' && $redirect !== '/logout.php') {
            return $redirect;
        }
        
        // Redirección por defecto según rol
        $rol = $this->authService->getPrimaryRole();
        
        if (!$rol) {
            return '/index.php';
        }
        
        switch ($rol['nombre_rol']) {
            case 'Administrativo':
                return '/dashboard/admin.php';
            case 'Directivo':
                return '/dashboard/directivo.php';
            case 'Maestro':
                return '/dashboard/maestro.php';
            default:
                return '/index.php';
        }
    }
}

