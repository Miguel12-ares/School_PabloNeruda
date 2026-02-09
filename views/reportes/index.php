<?php 
$title = 'Reportes';
$controller = 'reporte';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-3"><i class="fas fa-chart-line"></i> Reportes del Sistema</h2>
            <p class="text-muted">Seleccione el tipo de reporte que desea consultar y gestionar</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-11 mx-auto">
            <!-- Reportes Académicos -->
            <div class="mb-4">
                <h5 class="text-primary mb-3">
                    <i class="fas fa-graduation-cap"></i> Reportes Académicos
                </h5>
                <div class="row g-4">
                    <!-- Boletines de Notas -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 hover-card">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <i class="fas fa-file-alt display-3 text-primary"></i>
                                </div>
                                <h5 class="card-title fw-bold">Boletines de Notas</h5>
                                <p class="card-text text-muted small">
                                    Consulte y genere boletines de calificaciones por estudiante, con cálculos de promedio y proyecciones.
                                </p>
                                <a href="index.php?controller=reporte&action=boletines" 
                                   class="btn btn-primary w-100">
                                    <i class="fas fa-eye"></i> Ver Reporte
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estudiantes Reprobados -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 hover-card">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <i class="fas fa-exclamation-triangle display-3 text-warning"></i>
                                </div>
                                <h5 class="card-title fw-bold">Estudiantes Reprobados</h5>
                                <p class="card-text text-muted small">
                                    Reporte detallado de estudiantes con materias reprobadas, incluyendo notas y análisis por periodo.
                                </p>
                                <a href="index.php?controller=reporte&action=estudiantes_reprobados" 
                                   class="btn btn-warning w-100">
                                    <i class="fas fa-eye"></i> Ver Reporte
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estudiantes por Curso -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 hover-card">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <i class="fas fa-users display-3 text-info"></i>
                                </div>
                                <h5 class="card-title fw-bold">Estudiantes por Curso</h5>
                                <p class="card-text text-muted small">
                                    Listado completo y detallado de estudiantes organizados por curso, con información de contacto.
                                </p>
                                <a href="index.php?controller=reporte&action=estudiantes_por_curso" 
                                   class="btn btn-info w-100">
                                    <i class="fas fa-eye"></i> Ver Reporte
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estudiantes con Alergias -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 hover-card">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <i class="fas fa-heartbeat display-3 text-danger"></i>
                                </div>
                                <h5 class="card-title fw-bold">Alergias de Emergencia</h5>
                                <p class="card-text text-muted small">
                                    Listado crítico de estudiantes con alergias registradas. Información para casos de emergencia.
                                </p>
                                <a href="index.php?controller=reporte&action=estudiantes_alergias" 
                                   class="btn btn-danger w-100">
                                    <i class="fas fa-eye"></i> Ver Reporte
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Información de Ayuda -->
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-info-circle"></i> Información de los Reportes
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success"></i>
                                    <strong>Boletines:</strong> Incluye cálculos de promedio, proyecciones y recomendaciones
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success"></i>
                                    <strong>Reprobados:</strong> Filtros por periodo, curso y materia con detalles completos
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success"></i>
                                    <strong>Por Curso:</strong> Información completa con datos de contacto de acudientes
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success"></i>
                                    <strong>Alergias:</strong> Reportes individuales o por curso, optimizado para impresión
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
