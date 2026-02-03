<?php

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Autoloader simple para cargar clases automáticamente
 */
spl_autoload_register(function ($class) {
    $directories = [
        ROOT_PATH . '/src/Interfaces/',
        ROOT_PATH . '/src/Models/',
        ROOT_PATH . '/src/Repositories/',
        ROOT_PATH . '/src/Services/',
        ROOT_PATH . '/src/Validators/',
        ROOT_PATH . '/src/Controllers/',
        ROOT_PATH . '/src/Middleware/',
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

