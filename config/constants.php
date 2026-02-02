<?php

// Rutas del sistema
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
define('VIEWS_PATH', ROOT_PATH . '/views');

// Configuración de archivos
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB
define('ALLOWED_MIME_TYPES', ['application/pdf']);
define('ALLOWED_EXTENSIONS', ['pdf']);

// Configuración académica
define('MAX_STUDENTS_PER_COURSE', 35);
define('MIN_GRADE', 0.0);
define('MAX_GRADE', 5.0);
define('PASSING_GRADE', 3.0);
define('PERIODS_PER_YEAR', 4);
define('GRADES_PER_PERIOD', 5);

// Jornadas
define('JORNADAS', ['mañana', 'tarde']);

// Grados
define('GRADOS', [
    0 => 'Preescolar',
    1 => 'Primero',
    2 => 'Segundo',
    3 => 'Tercero',
    4 => 'Cuarto',
    5 => 'Quinto'
]);

// Secciones
define('SECCIONES', ['A', 'B', 'C']);

