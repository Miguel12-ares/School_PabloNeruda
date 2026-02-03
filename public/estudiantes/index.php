<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/autoload.php';

$authMiddleware = new AuthMiddleware();
$authMiddleware->requireAuth();

// Redirigir al controlador
$queryString = isset($_GET['curso']) ? '&curso=' . $_GET['curso'] : '';
header('Location: /index.php?controller=estudiante&action=index' . $queryString);
exit;
