<?php
// Cargar configuración
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/autoload.php';

// Verificar autenticación
$authMiddleware = new AuthMiddleware();
$authMiddleware->requireAuth();

// Obtener parámetros de la URL
$controller = $_GET['controller'] ?? null;
$action = $_GET['action'] ?? null;

// Si no hay controlador ni acción, redirigir al dashboard según rol
if (!$controller && !$action) {
    $authService = new AuthService();
    $primaryRole = $authService->getPrimaryRole();
    
    if ($primaryRole) {
        $dashboard = match($primaryRole['nombre_rol']) {
            'Administrativo' => '/dashboard/admin.php',
            'Directivo' => '/dashboard/directivo.php',
            'Maestro' => '/dashboard/maestro.php',
            default => '/login.php'
        };
        header("Location: {$dashboard}");
        exit;
    }
}

// Establecer valores por defecto si faltan
$controller = $controller ?? 'estudiante';
$action = $action ?? 'index';

// Sanitizar parámetros
$controller = htmlspecialchars($controller, ENT_QUOTES, 'UTF-8');
$action = htmlspecialchars($action, ENT_QUOTES, 'UTF-8');

// Mapeo de controladores
$controllers = [
    'estudiante' => 'EstudianteController',
    'nota' => 'NotaController',
    'reporte' => 'ReporteController',
    'usuario' => 'UsuarioController',
    'curso' => 'CursoController',
    'materia' => 'MateriaController',
    'auditoria' => 'AuditoriaController'
];

// Verificar que el controlador existe
if (!isset($controllers[$controller])) {
    die('Controlador no encontrado');
}

$controllerClass = $controllers[$controller];

// Verificar que la clase existe
if (!class_exists($controllerClass)) {
    die('Clase del controlador no encontrada');
}

// Instanciar controlador
$controllerInstance = new $controllerClass();

// Verificar que el método existe
if (!method_exists($controllerInstance, $action)) {
    die('Acción no encontrada');
}

// Ejecutar acción
try {
    $controllerInstance->$action();
} catch (Exception $e) {
    $_SESSION['error'] = 'Error: ' . $e->getMessage();
    header('Location: index.php');
    exit;
}

