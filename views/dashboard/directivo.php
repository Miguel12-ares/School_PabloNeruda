<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <!-- Header del Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="bi bi-graph-up-arrow"></i> Panel Directivo
            </h1>
            <p class="text-muted">Accede a las funciones de supervisión y reportes académicos.</p>
        </div>
    </div>
    
    <!-- Accesos Rápidos en Tarjetas -->
    <div class="row justify-content-center g-4 mb-4">
        <!-- Rendimiento Académico por Curso -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-graph-up-arrow text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Rendimiento Académico</h5>
                    <p class="card-text text-muted mb-3">Ver rendimiento académico por curso.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=reporte&action=rendimiento" class="btn btn-primary">
                            <i class="bi bi-bar-chart"></i> Ver Rendimiento
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estudiantes en Riesgo -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-person-exclamation text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Estudiantes en Riesgo</h5>
                    <p class="card-text text-muted mb-3">Ver estudiantes con bajo rendimiento.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=reporte&action=estudiantesRiesgo" class="btn btn-primary">
                            <i class="bi bi-exclamation-triangle"></i> Ver Estudiantes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boletines de Notas -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-journal-text text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Boletines de Notas</h5>
                    <p class="card-text text-muted mb-3">Generar boletines de notas.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=reporte&action=boletines" class="btn btn-primary">
                            <i class="bi bi-file-text"></i> Generar Boletines
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estudiantes por Curso -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-people-fill text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Estudiantes por Curso</h5>
                    <p class="card-text text-muted mb-3">Consultar estudiantes por curso.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=reporte&action=estudiantesPorCurso" class="btn btn-primary">
                            <i class="bi bi-list-ul"></i> Ver Reporte
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estudiantes Reprobados -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-x-circle-fill text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Estudiantes Reprobados</h5>
                    <p class="card-text text-muted mb-3">Ver listado de estudiantes reprobados.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=reporte&action=estudiantesReprobados" class="btn btn-primary">
                            <i class="bi bi-clipboard-x"></i> Ver Reprobados
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alergias y Salud -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-heart-pulse-fill text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Alergias y Salud</h5>
                    <p class="card-text text-muted mb-3">Consultar información de salud.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=reporte&action=estudiantesAlergias" class="btn btn-primary">
                            <i class="bi bi-clipboard-heart"></i> Ver Reporte
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparativa por Jornadas -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-clock-fill text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Comparativa por Jornadas</h5>
                    <p class="card-text text-muted mb-3">Ver estadísticas por jornada.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=reporte&action=jornadasComparativa" class="btn btn-primary">
                            <i class="bi bi-pie-chart"></i> Ver Comparativa
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Todos los Reportes -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-file-earmark-bar-graph-fill text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Todos los Reportes</h5>
                    <p class="card-text text-muted mb-3">Acceder a todos los reportes.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=reporte&action=index" class="btn btn-primary">
                            <i class="bi bi-folder"></i> Ver Todos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
