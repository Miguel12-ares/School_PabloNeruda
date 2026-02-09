<?php
// Cargar configuración sin autenticación (página pública)
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';

// Iniciar sesión solo para mensajes, sin requerir autenticación
session_start();

// Cargar vista de home
require_once VIEWS_PATH . '/home/index.php';
