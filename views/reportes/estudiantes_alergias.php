<?php 
$title = 'Estudiantes con Alergias';
$controller = 'reporte';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="card">
    <div class="card-header bg-danger text-white">
        <h4 class="mb-0">
            <i class="bi bi-exclamation-triangle"></i> Estudiantes con Alergias - REPORTE DE EMERGENCIA
        </h4>
    </div>
    <div class="card-body">
        <div class="alert alert-danger">
            <strong><i class="bi bi-info-circle"></i> Importante:</strong> 
            Este reporte contiene información crítica para casos de emergencia. 
            Mantenga esta información actualizada y accesible.
        </div>
        
        <?php if (empty($estudiantes)): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> No hay estudiantes con alergias registradas en el sistema.
            </div>
        <?php else: ?>
            <div class="mb-3">
                <strong>Total de estudiantes con alergias:</strong> <?= count($estudiantes) ?>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-danger">
                        <tr>
                            <th>#</th>
                            <th>Nombre Completo</th>
                            <th>Curso</th>
                            <th>Jornada</th>
                            <th>Alergias</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudiantes as $index => $estudiante): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?></strong>
                                    <br>
                                    <small class="text-muted">RC: <?= htmlspecialchars($estudiante['registro_civil']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($estudiante['nombre_curso']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $estudiante['jornada'] === 'mañana' ? 'warning' : 'info' ?>">
                                        <?= ucfirst($estudiante['jornada']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-danger">
                                        <strong><?= htmlspecialchars($estudiante['alergias']) ?></strong>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3 d-print-none">
                <button onclick="window.print()" class="btn btn-danger">
                    <i class="bi bi-printer"></i> Imprimir Reporte de Emergencia
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>

