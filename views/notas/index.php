<?php 
$title = 'Gestión de Notas';
$controller = 'nota';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-journal-text"></i> Gestión de Calificaciones</h4>
            </div>
            <div class="card-body">
                <p class="lead">Seleccione un curso y periodo para registrar o consultar calificaciones:</p>
                
                <form action="index.php" method="GET" class="row g-3">
                    <input type="hidden" name="controller" value="nota">
                    <input type="hidden" name="action" value="registrar">
                    
                    <div class="col-md-6">
                        <label for="id_curso" class="form-label">Curso</label>
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
                        <label for="id_periodo" class="form-label">Periodo</label>
                        <select class="form-select" id="id_periodo" name="id_periodo" required>
                            <option value="">Seleccione un periodo</option>
                            <?php foreach ($periodos as $periodo): ?>
                                <option value="<?= $periodo['id_periodo'] ?>">
                                    Periodo <?= $periodo['numero_periodo'] ?> - <?= $periodo['anio_lectivo'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-pencil-square"></i> Registrar Notas
                        </button>
                    </div>
                </form>
                
                <hr class="my-4">
                
                <h5>Acciones Rápidas</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="bi bi-file-earmark-text display-4 text-primary"></i>
                                <h6 class="mt-3">Consultar Boletines</h6>
                                <a href="index.php?controller=reporte&action=boletines" class="btn btn-sm btn-outline-primary">
                                    Ver Boletines
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="bi bi-graph-down display-4 text-danger"></i>
                                <h6 class="mt-3">Estudiantes Reprobados</h6>
                                <a href="index.php?controller=reporte&action=estudiantes_reprobados" class="btn btn-sm btn-outline-danger">
                                    Ver Reporte
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="bi bi-bar-chart display-4 text-success"></i>
                                <h6 class="mt-3">Estadísticas</h6>
                                <a href="index.php?controller=reporte&action=index" class="btn btn-sm btn-outline-success">
                                    Ver Reportes
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

