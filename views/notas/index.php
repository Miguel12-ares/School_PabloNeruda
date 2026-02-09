<?php 
$title = 'Gestión de Notas';
$controller = 'nota';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-3"><i class="fas fa-clipboard-list"></i> Gestión de Calificaciones</h2>
            <p class="text-muted">Seleccione un curso y periodo para registrar o consultar calificaciones</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-10 mx-auto">
            <!-- Formulario de Selección -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter"></i> Seleccione Curso y Periodo</h5>
                </div>
                <div class="card-body">
                    <form action="index.php" method="GET" class="row g-3">
                        <input type="hidden" name="controller" value="nota">
                        <input type="hidden" name="action" value="registrar">
                        
                        <div class="col-md-6">
                            <label for="id_curso" class="form-label">
                                <i class="fas fa-book text-primary"></i> Curso <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="id_curso" name="id_curso" required>
                                <option value="">Seleccione un curso</option>
                                <?php foreach ($cursos as $curso): ?>
                                    <option value="<?= $curso['id_curso'] ?>">
                                        <?= htmlspecialchars($curso['nombre_curso']) ?>
                                        <?php if (isset($curso['jornada'])): ?>
                                            - <?= ucfirst($curso['jornada']) ?>
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="id_periodo" class="form-label">
                                <i class="fas fa-calendar-alt text-primary"></i> Periodo <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="id_periodo" name="id_periodo" required>
                                <option value="">Seleccione un periodo</option>
                                <?php foreach ($periodos as $periodo): ?>
                                    <option value="<?= $periodo['id_periodo'] ?>">
                                        Periodo <?= $periodo['numero_periodo'] ?> - <?= $periodo['anio_lectivo'] ?>
                                        (<?= date('d/m/Y', strtotime($periodo['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($periodo['fecha_fin'])) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-edit"></i> Registrar Notas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Acciones Rápidas -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt"></i> Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card border text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-file-alt display-4 text-primary"></i>
                                    <h6 class="mt-3">Consultar Boletines</h6>
                                    <p class="text-muted small">Ver boletines de calificaciones por estudiante</p>
                                    <a href="index.php?controller=reporte&action=boletines" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Ver Boletines
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-chart-line display-4 text-info"></i>
                                    <h6 class="mt-3">Estadísticas</h6>
                                    <p class="text-muted small">Consultar estadísticas y promedios generales</p>
                                    <a href="index.php?controller=reporte&action=index" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-chart-bar"></i> Ver Reportes
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-exclamation-triangle display-4 text-danger"></i>
                                    <h6 class="mt-3">Estudiantes Reprobados</h6>
                                    <p class="text-muted small">Listado de estudiantes con materias perdidas</p>
                                    <a href="index.php?controller=reporte&action=estudiantes_reprobados" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-list"></i> Ver Reporte
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
