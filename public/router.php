<?php
/**
 * Router para el servidor PHP incorporado
 * Uso: php -S localhost:8000 -t public router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Servir archivos estáticos directamente
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Rutas personalizadas
if ($uri === '/home' || $uri === '/home/') {
    require_once __DIR__ . '/home.php';
    return true;
}

if ($uri === '/login' || $uri === '/login/') {
    require_once __DIR__ . '/login.php';
    return true;
}

// Ruta por defecto
if ($uri === '/') {
    require_once __DIR__ . '/index.php';
    return true;
}

// Si no es una ruta especial, intentar servir el archivo
if (file_exists(__DIR__ . $uri)) {
    return false;
}

// Si no existe, intentar con index.php
require_once __DIR__ . '/index.php';
return true;
