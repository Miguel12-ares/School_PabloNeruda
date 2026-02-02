<?php
session_start();

// Cargar configuración
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/autoload.php';

// Obtener parámetros de la URL
$controller = $_GET['controller'] ?? 'estudiante';
$action = $_GET['action'] ?? 'index';

// Sanitizar parámetros
$controller = htmlspecialchars($controller, ENT_QUOTES, 'UTF-8');
$action = htmlspecialchars($action, ENT_QUOTES, 'UTF-8');

// Mapeo de controladores
$controllers = [
    'estudiante' => 'EstudianteController',
    'nota' => 'NotaController',
    'reporte' => 'ReporteController'
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

