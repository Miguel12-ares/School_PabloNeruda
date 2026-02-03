<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/autoload.php';

$authMiddleware = new AuthMiddleware();
$authMiddleware->requireAuth();

$authService = new AuthService();
$usuario = $authService->getCurrentUser();
$primaryRole = $authService->getPrimaryRole();

require_once VIEWS_PATH . '/perfil/index.php';
