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
    <!-- FontAwesome para iconos mejorados -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts - Tipografía educativa -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <style>
        /* Header mejorado */
        .navbar {
            background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%) !important;
            padding: 1rem 2rem;
        }
        .navbar-brand {
            font-family: 'Poppins', sans-serif;
            font-size: 1.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .nav-link {
            font-size: 1.05rem;
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
                Pablo Neruda
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
                
                <!-- Usuario actual - Botones simplificados -->
                <?php if ($authService->isAuthenticated()): ?>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="/perfil.php" title="Mi Perfil">
                                <i class="fa-solid fa-user fs-5"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/logout.php" title="Cerrar Sesión">
                                <i class="fa-solid fa-right-from-bracket fs-5"></i>
                            </a>
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

