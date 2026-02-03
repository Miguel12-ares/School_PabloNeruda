<?php
// Inicializar servicio de autenticación para el menú
$authService = new AuthService();
$permissionMiddleware = new PermissionMiddleware();
$currentUser = $authService->getCurrentUser();
$primaryRole = $authService->getPrimaryRole();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistema Escuela Pablo Neruda' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }
        .user-badge {
            background: rgba(255,255,255,0.2);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            color: white;
        }
        .dropdown-menu {
            min-width: 250px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/index.php">
                <i class="bi bi-book-fill"></i> Escuela Pablo Neruda
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <!-- Dashboard -->
                    <?php if ($authService->isAuthenticated()): ?>
                        <li class="nav-item">
                            <?php 
                            $dashboardUrl = '/dashboard/';
                            $dashboardUrl .= $primaryRole['nombre_rol'] === 'Administrativo' ? 'admin' : 
                                           ($primaryRole['nombre_rol'] === 'Directivo' ? 'directivo' : 'maestro');
                            $dashboardUrl .= '.php';
                            ?>
                            <a class="nav-link" href="<?= $dashboardUrl ?>">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Estudiantes -->
                    <?php if ($permissionMiddleware->checkPermission('estudiantes', 'ver')): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-people"></i> Estudiantes
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="/index.php?controller=estudiante&action=index">
                                        <i class="bi bi-list"></i> Ver Listado
                                    </a>
                                </li>
                                <?php if ($permissionMiddleware->checkPermission('estudiantes', 'crear')): ?>
                                    <li>
                                        <a class="dropdown-item" href="/index.php?controller=estudiante&action=create">
                                            <i class="bi bi-person-plus"></i> Nuevo Estudiante
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Notas -->
                    <?php if ($permissionMiddleware->checkPermission('notas', 'ver')): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-journal-text"></i> Notas
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="/index.php?controller=nota&action=index">
                                        <i class="bi bi-list"></i> Ver Notas
                                    </a>
                                </li>
                                <?php if ($permissionMiddleware->checkPermission('notas', 'registrar') || 
                                          $permissionMiddleware->checkPermission('notas', 'editar_propias')): ?>
                                    <li>
                                        <a class="dropdown-item" href="/index.php?controller=nota&action=registrar">
                                            <i class="bi bi-pencil-square"></i> Registrar Notas
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Reportes -->
                    <?php if ($permissionMiddleware->checkPermission('reportes', 'ver')): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-file-earmark-bar-graph"></i> Reportes
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="/index.php?controller=reporte&action=index">
                                        <i class="bi bi-grid"></i> Todos los Reportes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <?php if ($permissionMiddleware->checkPermission('reportes', 'boletines')): ?>
                                    <li>
                                        <a class="dropdown-item" href="/index.php?controller=reporte&action=boletines">
                                            <i class="bi bi-journal-text"></i> Boletines
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($permissionMiddleware->checkPermission('reportes', 'alergias')): ?>
                                    <li>
                                        <a class="dropdown-item" href="/index.php?controller=reporte&action=alergias">
                                            <i class="bi bi-heart-pulse"></i> Estudiantes con Alergias
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($permissionMiddleware->checkPermission('reportes', 'reprobados')): ?>
                                    <li>
                                        <a class="dropdown-item" href="/index.php?controller=reporte&action=reprobados">
                                            <i class="bi bi-exclamation-triangle"></i> Estudiantes Reprobados
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Gestión (solo administrativos) -->
                    <?php if ($permissionMiddleware->checkPermission('usuarios', 'ver')): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-gear"></i> Gestión
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="/index.php?controller=usuario&action=index">
                                        <i class="bi bi-people"></i> Usuarios
                                    </a>
                                </li>
                                <?php if ($permissionMiddleware->checkPermission('cursos', 'ver')): ?>
                                    <li>
                                        <a class="dropdown-item" href="/index.php?controller=curso&action=index">
                                            <i class="bi bi-book"></i> Cursos
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($permissionMiddleware->checkPermission('materias', 'ver')): ?>
                                    <li>
                                        <a class="dropdown-item" href="/index.php?controller=materia&action=index">
                                            <i class="bi bi-journal-bookmark"></i> Materias
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($permissionMiddleware->checkPermission('auditoria', 'ver')): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="/index.php?controller=auditoria&action=index">
                                            <i class="bi bi-clock-history"></i> Auditoría
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <!-- Usuario actual -->
                <?php if ($authService->isAuthenticated()): ?>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle user-badge" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                <?= htmlspecialchars($currentUser['nombre_completo']) ?>
                                <span class="badge bg-light text-dark ms-1">
                                    <?= htmlspecialchars($primaryRole['nombre_rol'] ?? 'Usuario') ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li class="px-3 py-2">
                                    <small class="text-muted">Conectado como:</small><br>
                                    <strong><?= htmlspecialchars($currentUser['username']) ?></strong>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="/perfil.php">
                                        <i class="bi bi-person"></i> Mi Perfil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/cambiar-password.php">
                                        <i class="bi bi-key"></i> Cambiar Contraseña
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="/logout.php">
                                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid mt-4">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

