<?php 
$title = 'Reportes';
$controller = 'reporte';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-file-earmark-bar-graph"></i> Reportes del Sistema</h4>
            </div>
            <div class="card-body">
                <p class="lead">Seleccione el tipo de reporte que desea consultar:</p>
                
                <div class="row g-4 mt-2">
                    <!-- Estudiantes por Curso -->
                    <div class="col-md-6">
                        <div class="card h-100 border-primary">
                            <div class="card-body text-center">
                                <i class="bi bi-people-fill display-1 text-primary"></i>
                                <h5 class="card-title mt-3">Estudiantes por Curso</h5>
                                <p class="card-text">
                                    Consulte el listado completo de estudiantes organizados por curso, 
                                    incluyendo información de contacto de acudientes.
                                </p>
                                <a href="index.php?controller=reporte&action=estudiantes_por_curso" 
                                   class="btn btn-primary">
                                    <i class="bi bi-eye"></i> Ver Reporte
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estudiantes con Alergias -->
                    <div class="col-md-6">
                        <div class="card h-100 border-danger">
                            <div class="card-body text-center">
                                <i class="bi bi-exclamation-triangle-fill display-1 text-danger"></i>
                                <h5 class="card-title mt-3">Estudiantes con Alergias</h5>
                                <p class="card-text">
                                    Listado de emergencia con todos los estudiantes que presentan 
                                    alergias registradas en el sistema.
                                </p>
                                <a href="index.php?controller=reporte&action=estudiantes_alergias" 
                                   class="btn btn-danger">
                                    <i class="bi bi-eye"></i> Ver Reporte
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estudiantes Reprobados -->
                    <div class="col-md-6">
                        <div class="card h-100 border-warning">
                            <div class="card-body text-center">
                                <i class="bi bi-graph-down display-1 text-warning"></i>
                                <h5 class="card-title mt-3">Estudiantes Reprobados</h5>
                                <p class="card-text">
                                    Reporte de estudiantes con materias reprobadas por periodo académico, 
                                    para seguimiento y apoyo.
                                </p>
                                <a href="index.php?controller=reporte&action=estudiantes_reprobados" 
                                   class="btn btn-warning">
                                    <i class="bi bi-eye"></i> Ver Reporte
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Boletines de Notas -->
                    <div class="col-md-6">
                        <div class="card h-100 border-success">
                            <div class="card-body text-center">
                                <i class="bi bi-journal-text display-1 text-success"></i>
                                <h5 class="card-title mt-3">Boletines de Notas</h5>
                                <p class="card-text">
                                    Consulte y genere boletines de calificaciones por estudiante, 
                                    curso y periodo académico.
                                </p>
                                <a href="index.php?controller=reporte&action=boletines" 
                                   class="btn btn-success">
                                    <i class="bi bi-eye"></i> Ver Reporte
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>

