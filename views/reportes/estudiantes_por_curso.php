<?php 
$title = 'Estudiantes por Curso';
$controller = 'reporte';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="bi bi-people"></i> Estudiantes por Curso</h4>
    </div>
    <div class="card-body">
        <!-- Selector de Curso -->
        <form action="index.php" method="GET" class="row g-3 mb-4">
            <input type="hidden" name="controller" value="reporte">
            <input type="hidden" name="action" value="estudiantes_por_curso">
            
            <div class="col-md-10">
                <select class="form-select" name="id_curso" required>
                    <option value="">Seleccione un curso</option>
                    <?php foreach ($cursos as $curso): ?>
                        <option value="<?= $curso['id_curso'] ?>" 
                                <?= ($id_curso == $curso['id_curso']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($curso['nombre_curso']) ?> - 
                            <?= ucfirst($curso['jornada']) ?> 
                            (<?= $curso['total_estudiantes'] ?> estudiantes)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </form>
        
        <?php if ($curso_seleccionado): ?>
            <div class="alert alert-info">
                <strong>Curso:</strong> <?= htmlspecialchars($curso_seleccionado['nombre_curso']) ?> | 
                <strong>Jornada:</strong> <?= ucfirst($curso_seleccionado['jornada']) ?> | 
                <strong>Capacidad:</strong> <?= count($estudiantes) ?>/<?= $curso_seleccionado['capacidad_maxima'] ?>
            </div>
            
            <?php if (empty($estudiantes)): ?>
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle"></i> No hay estudiantes registrados en este curso.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nombre Completo</th>
                                <th>Registro Civil</th>
                                <th>Edad</th>
                                <th>Alergias</th>
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
                                    <td><?= $estudiante['edad'] ?> años</td>
                                    <td>
                                        <?php if ($estudiante['tiene_alergias']): ?>
                                            <span class="badge bg-danger">
                                                <i class="bi bi-exclamation-triangle"></i> Sí
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success">No</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3 d-print-none">
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="bi bi-printer"></i> Imprimir Reporte
                    </button>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>

