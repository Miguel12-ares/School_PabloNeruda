<?php

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
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

