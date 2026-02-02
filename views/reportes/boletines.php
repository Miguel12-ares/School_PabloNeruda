<?php 
$title = 'Boletines de Notas';
$controller = 'reporte';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="card">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0"><i class="bi bi-journal-text"></i> Consultar Boletines de Notas</h4>
    </div>
    <div class="card-body">
        <!-- Selector de Curso y Periodo -->
        <form action="index.php" method="GET" class="row g-3 mb-4">
            <input type="hidden" name="controller" value="reporte">
            <input type="hidden" name="action" value="boletines">
            
            <div class="col-md-5">
                <label for="id_curso" class="form-label">Curso</label>
                <select class="form-select" id="id_curso" name="id_curso" required>
                    <option value="">Seleccione un curso</option>
                    <?php foreach ($cursos as $curso): ?>
                        <option value="<?= $curso['id_curso'] ?>" 
                                <?= ($id_curso == $curso['id_curso']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($curso['nombre_curso']) ?> - <?= ucfirst($curso['jornada']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-5">
                <label for="id_periodo" class="form-label">Periodo</label>
                <select class="form-select" id="id_periodo" name="id_periodo" required>
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
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-success w-100">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </form>
        
        <?php if ($id_curso && $id_periodo): ?>
            <?php if (empty($estudiantes)): ?>
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle"></i> No hay estudiantes registrados en este curso.
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <strong>Total de estudiantes:</strong> <?= count($estudiantes) ?>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nombre Completo</th>
                                <th>Registro Civil</th>
                                <th>Acción</th>
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
                                    <td>
                                        <a href="index.php?controller=nota&action=boletin&id_estudiante=<?= $estudiante['id_estudiante'] ?>&id_periodo=<?= $id_periodo ?>" 
                                           class="btn btn-sm btn-success" target="_blank">
                                            <i class="bi bi-file-earmark-text"></i> Ver Boletín
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>

