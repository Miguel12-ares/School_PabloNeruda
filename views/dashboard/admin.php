<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <!-- Header del Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="bi bi-gear-fill"></i> Panel Administrativo
            </h1>
            <p class="text-muted">Accede a las funciones administrativas principales del sistema.</p>
        </div>
    </div>
    
    <!-- Accesos Rápidos en Tarjetas -->
    <div class="row justify-content-center g-4 mb-4">
        <!-- Gestión de Fichas -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-folder-fill text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Gestión de Fichas</h5>
                    <p class="card-text text-muted mb-3">Administrar, crear y deshabilitar fichas.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=estudiante&action=create" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Crear Ficha
                        </a>
                        <a href="/index.php?controller=estudiante&action=index" class="btn btn-outline-primary">
                            <i class="bi bi-list-ul"></i> Administrar Fichas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestionar Aprendices -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-mortarboard-fill text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Gestionar Aprendices</h5>
                    <p class="card-text text-muted mb-3">Centraliza la administración de aprendices.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=estudiante&action=index" class="btn btn-primary">
                            <i class="bi bi-people"></i> Administrar Aprendices
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestión de Instructores -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-person-badge-fill text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Gestión de Instructores</h5>
                    <p class="card-text text-muted mb-3">Administrar instructores del sistema.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=usuario&action=create" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Crear Instructor
                        </a>
                        <a href="/index.php?controller=usuario&action=index" class="btn btn-outline-primary">
                            <i class="bi bi-list-ul"></i> Administrar Instructores
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestión de Cursos -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-book-fill text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Gestión de Cursos</h5>
                    <p class="card-text text-muted mb-3">Administrar cursos del sistema.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=curso&action=create" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Crear Curso
                        </a>
                        <a href="/index.php?controller=curso&action=index" class="btn btn-outline-primary">
                            <i class="bi bi-list-ul"></i> Administrar Cursos
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestión de Materias -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-journal-text text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Gestión de Materias</h5>
                    <p class="card-text text-muted mb-3">Administrar materias del sistema.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=materia&action=create" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Crear Materia
                        </a>
                        <a href="/index.php?controller=materia&action=index" class="btn btn-outline-primary">
                            <i class="bi bi-list-ul"></i> Administrar Materias
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Auditoría del Sistema -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-clock-history text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Auditoría del Sistema</h5>
                    <p class="card-text text-muted mb-3">Ver registro de actividades del sistema.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=auditoria&action=index" class="btn btn-primary">
                            <i class="bi bi-search"></i> Ver Auditoría
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reportes -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-file-earmark-bar-graph-fill text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Reportes</h5>
                    <p class="card-text text-muted mb-3">Generar reportes del sistema.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=reporte&action=index" class="btn btn-primary">
                            <i class="bi bi-graph-up"></i> Ver Reportes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestión de Notas -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-pencil-square text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Gestión de Notas</h5>
                    <p class="card-text text-muted mb-3">Registrar y consultar notas.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=nota&action=index" class="btn btn-primary">
                            <i class="bi bi-list-check"></i> Ver Notas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
