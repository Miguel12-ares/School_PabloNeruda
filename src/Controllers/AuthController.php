<?php

/**
 * Controlador de Autenticación
 * Maneja login, logout y operaciones relacionadas con autenticación
 */
class AuthController {
    
    private AuthService $authService;
    private AuthMiddleware $authMiddleware;
    
    public function __construct() {
        $this->authService = new AuthService();
        $this->authMiddleware = new AuthMiddleware();
    }
    
    /**
     * Mostrar formulario de login
     */
    public function showLogin(): void {
        // Si ya está autenticado, redirigir al dashboard
        $this->authMiddleware->requireGuest();
        
        require_once VIEWS_PATH . '/auth/login.php';
    }
    
    /**
     * Procesar login
     */
    public function processLogin(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login.php');
            exit;
        }
        
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Validar campos vacíos
        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Por favor complete todos los campos';
            header('Location: /login.php');
            exit;
        }
        
        // Intentar login
        $result = $this->authService->login($username, $password);
        
        if ($result['success']) {
            // Login exitoso - redirigir
            $redirect = $this->authMiddleware->getRedirectAfterLogin();
            header("Location: {$redirect}");
            exit;
        } else {
            // Login fallido
            $_SESSION['error'] = $result['message'];
            
            if (isset($result['blocked']) && $result['blocked']) {
                $_SESSION['error_type'] = 'blocked';
            }
            
            header('Location: /login.php');
            exit;
        }
    }
    
    /**
     * Cerrar sesión
     */
    public function logout(): void {
        $this->authService->logout();
        $_SESSION['success'] = 'Sesión cerrada exitosamente';
        header('Location: /login.php');
        exit;
    }
    
    /**
     * Verificar estado de sesión (AJAX)
     */
    public function checkSession(): void {
        header('Content-Type: application/json');
        
        echo json_encode([
            'authenticated' => $this->authService->isAuthenticated(),
            'user' => $this->authService->getCurrentUser()
        ]);
    }
    
    /**
     * Cambiar contraseña
     */
    public function changePassword(): void {
        $this->authMiddleware->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /perfil.php');
            exit;
        }
        
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validaciones
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['error'] = 'Todos los campos son requeridos';
            header('Location: /perfil.php');
            exit;
        }
        
        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'Las contraseñas nuevas no coinciden';
            header('Location: /perfil.php');
            exit;
        }
        
        if (strlen($newPassword) < 8) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 8 caracteres';
            header('Location: /perfil.php');
            exit;
        }
        
        // Verificar contraseña actual
        $userId = $this->authService->getCurrentUserId();
        $authRepo = new AuthRepository();
        $user = $authRepo->findById($userId);
        
        if (!password_verify($currentPassword, $user['password_hash'])) {
            $_SESSION['error'] = 'La contraseña actual es incorrecta';
            header('Location: /perfil.php');
            exit;
        }
        
        // Actualizar contraseña
        $result = $this->authService->actualizarUsuario($userId, [
            'password' => $newPassword
        ]);
        
        if ($result['success']) {
            $_SESSION['success'] = 'Contraseña actualizada exitosamente';
        } else {
            $_SESSION['error'] = $result['message'];
        }
        
        header('Location: /perfil.php');
        exit;
    }
}

