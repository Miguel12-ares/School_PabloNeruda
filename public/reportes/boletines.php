<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/autoload.php';

$authMiddleware = new AuthMiddleware();
$authMiddleware->requireAuth();

// Redirigir al controlador de reportes
header('Location: /index.php?controller=reporte&action=boletines');
exit;
