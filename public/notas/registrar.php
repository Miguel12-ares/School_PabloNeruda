<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/autoload.php';

$authMiddleware = new AuthMiddleware();
$authMiddleware->requireAuth();

// Redirigir al controlador de notas con los parámetros
$params = [];
if (isset($_GET['curso'])) $params[] = 'id_curso=' . $_GET['curso'];
if (isset($_GET['materia'])) $params[] = 'id_materia=' . $_GET['materia'];
if (isset($_GET['periodo'])) $params[] = 'id_periodo=' . $_GET['periodo'];

$queryString = !empty($params) ? '&' . implode('&', $params) : '';

header('Location: /index.php?controller=nota&action=registrar' . $queryString);
exit;
