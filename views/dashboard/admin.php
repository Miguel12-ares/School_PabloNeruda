<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <!-- Header del Dashboard con FontAwesome -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="h3 mb-0 dashboard-title">
                <i class="fa-solid fa-gears"></i> Panel Administrativo
            </h1>
            <p class="text-muted">Accede a las funciones administrativas principales del sistema.</p>
        </div>
    </div>
    
    <!-- Accesos Rápidos en Tarjetas - Máximo 3 por fila -->
    <div class="row justify-content-center g-4 mb-4">
        <!-- Gestión de Cursos -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="fa-solid fa-book text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Gestión de Cursos</h5>
                    <p class="card-text text-muted mb-3">Administrar cursos del sistema.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=curso&action=create" class="btn btn-primary">
                            <i class="fa-solid fa-plus-circle"></i> Crear Curso
                        </a>
                        <a href="/index.php?controller=curso&action=index" class="btn btn-outline-primary">
                            <i class="fa-solid fa-list-ul"></i> Administrar Cursos
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestión de Estudiantes -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="fa-solid fa-user-graduate text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Gestión de Estudiantes</h5>
                    <p class="card-text text-muted mb-3">Administrar estudiantes del sistema.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=estudiante&action=create" class="btn btn-primary">
                            <i class="fa-solid fa-plus-circle"></i> Crear Estudiante
                        </a>
                        <a href="/index.php?controller=estudiante&action=index" class="btn btn-outline-primary">
                            <i class="fa-solid fa-list-ul"></i> Administrar Estudiantes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestión de Usuarios -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="fa-solid fa-users text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Gestión de Usuarios</h5>
                    <p class="card-text text-muted mb-3">Administrar usuarios del sistema.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=usuario&action=create" class="btn btn-primary">
                            <i class="fa-solid fa-plus-circle"></i> Crear Usuario
                        </a>
                        <a href="/index.php?controller=usuario&action=index" class="btn btn-outline-primary">
                            <i class="fa-solid fa-list-ul"></i> Administrar Usuarios
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestión de Materias -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="fa-solid fa-book-open text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Gestión de Materias</h5>
                    <p class="card-text text-muted mb-3">Administrar materias del sistema.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=materia&action=create" class="btn btn-primary">
                            <i class="fa-solid fa-plus-circle"></i> Crear Materia
                        </a>
                        <a href="/index.php?controller=materia&action=index" class="btn btn-outline-primary">
                            <i class="fa-solid fa-list-ul"></i> Administrar Materias
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestión de Notas -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="fa-solid fa-pen-to-square text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Gestión de Notas</h5>
                    <p class="card-text text-muted mb-3">Registrar y consultar notas.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=nota&action=index" class="btn btn-primary">
                            <i class="fa-solid fa-list-check"></i> Ver Notas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reportes -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="fa-solid fa-chart-line text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Reportes</h5>
                    <p class="card-text text-muted mb-3">Generar reportes del sistema.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=reporte&action=index" class="btn btn-primary">
                            <i class="fa-solid fa-chart-column"></i> Ver Reportes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Auditoría del Sistema -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="fa-solid fa-clock-rotate-left text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Auditoría del Sistema</h5>
                    <p class="card-text text-muted mb-3">Ver registro de actividades del sistema.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=auditoria&action=index" class="btn btn-primary">
                            <i class="fa-solid fa-magnifying-glass"></i> Ver Auditoría
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
