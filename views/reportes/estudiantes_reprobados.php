<?php 
$title = 'Estudiantes Reprobados';
$controller = 'reporte';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="card">
    <div class="card-header bg-warning">
        <h4 class="mb-0"><i class="bi bi-graph-down"></i> Estudiantes Reprobados por Periodo</h4>
    </div>
    <div class="card-body">
        <!-- Selector de Periodo -->
        <form action="index.php" method="GET" class="row g-3 mb-4">
            <input type="hidden" name="controller" value="reporte">
            <input type="hidden" name="action" value="estudiantes_reprobados">
            
            <div class="col-md-10">
                <select class="form-select" name="id_periodo" required>
                    <option value="">Seleccione un periodo</option>
                    <?php foreach ($periodos as $periodo): ?>
                        <option value="<?= $periodo['id_periodo'] ?>" 
                                <?= ($id_periodo == $periodo['id_periodo']) ? 'selected' : '' ?>>
                            Periodo <?= $periodo['numero_periodo'] ?> - <?= $periodo['anio_lectivo'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-warning w-100">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </form>
        
        <?php if ($periodo_seleccionado): ?>
            <div class="alert alert-info">
                <strong>Periodo:</strong> <?= $periodo_seleccionado['numero_periodo'] ?> - 
                Año <?= $periodo_seleccionado['anio_lectivo'] ?>
            </div>
            
            <?php if (empty($estudiantes)): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> No hay estudiantes reprobados en este periodo.
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <strong>Total de estudiantes con materias reprobadas:</strong> <?= count($estudiantes) ?>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nombre Completo</th>
                                <th>Registro Civil</th>
                                <th>Curso</th>
                                <th>Materias Reprobadas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($estudiantes as $index => $estudiante): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($estudiante['registro_civil']) ?></td>
                                    <td><?= htmlspecialchars($estudiante['nombre_curso']) ?></td>
                                    <td>
                                        <span class="badge bg-danger fs-6">
                                            <?= $estudiante['materias_reprobadas'] ?> materia(s)
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3 d-print-none">
                    <button onclick="window.print()" class="btn btn-warning">
                        <i class="bi bi-printer"></i> Imprimir Reporte
                    </button>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>

