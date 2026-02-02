<?php 
$title = 'Boletín de Notas';
$controller = 'nota';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="mb-0">BOLETÍN DE CALIFICACIONES</h3>
                <h5 class="mb-0">Escuela Pablo Neruda</h5>
                <p class="mb-0 small">Barrio Las Malvinas, Sector 4 Berlín</p>
            </div>
            <div class="card-body">
                <?php if (!empty($boletin)): ?>
                    <?php $primer_registro = $boletin[0]; ?>
                    
                    <!-- Información del Estudiante -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Estudiante:</strong> <?= htmlspecialchars($primer_registro['nombre'] . ' ' . $primer_registro['apellido']) ?></p>
                            <p><strong>Registro Civil:</strong> <?= htmlspecialchars($primer_registro['registro_civil']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Curso:</strong> <?= htmlspecialchars($primer_registro['nombre_curso']) ?></p>
                            <p><strong>Periodo:</strong> <?= $primer_registro['numero_periodo'] ?> - Año <?= $primer_registro['anio_lectivo'] ?></p>
                        </div>
                    </div>
                    
                    <!-- Tabla de Notas -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Materia</th>
                                    <th class="text-center">Nota 1</th>
                                    <th class="text-center">Nota 2</th>
                                    <th class="text-center">Nota 3</th>
                                    <th class="text-center">Nota 4</th>
                                    <th class="text-center">Nota 5</th>
                                    <th class="text-center">Promedio</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($boletin as $nota): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($nota['nombre_materia']) ?></td>
                                        <td class="text-center"><?= $nota['nota_1'] ?? '-' ?></td>
                                        <td class="text-center"><?= $nota['nota_2'] ?? '-' ?></td>
                                        <td class="text-center"><?= $nota['nota_3'] ?? '-' ?></td>
                                        <td class="text-center"><?= $nota['nota_4'] ?? '-' ?></td>
                                        <td class="text-center"><?= $nota['nota_5'] ?? '-' ?></td>
                                        <td class="text-center">
                                            <strong><?= number_format($nota['promedio'], 1) ?></strong>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($nota['estado'] === 'aprobado'): ?>
                                                <span class="badge bg-success">Aprobado</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Reprobado</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="6" class="text-end"><strong>Promedio General:</strong></td>
                                    <td class="text-center">
                                        <strong class="fs-5">
                                            <?= $promedio_general ? number_format($promedio_general, 1) : '-' ?>
                                        </strong>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($promedio_general): ?>
                                            <?php if ($promedio_general >= 3.0): ?>
                                                <span class="badge bg-success">Aprobado</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Reprobado</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <!-- Escala de Valoración -->
                    <div class="mt-4">
                        <h6>Escala de Valoración:</h6>
                        <ul class="list-unstyled">
                            <li><strong>4.6 - 5.0:</strong> Desempeño Superior</li>
                            <li><strong>4.0 - 4.5:</strong> Desempeño Alto</li>
                            <li><strong>3.0 - 3.9:</strong> Desempeño Básico</li>
                            <li><strong>0.0 - 2.9:</strong> Desempeño Bajo</li>
                        </ul>
                    </div>
                    
                    <!-- Botones -->
                    <div class="mt-4 text-center d-print-none">
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="bi bi-printer"></i> Imprimir Boletín
                        </button>
                        <a href="index.php?controller=estudiante&action=view&id=<?= $estudiante['id_estudiante'] ?>" 
                           class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                    
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> No hay notas registradas para este periodo.
                    </div>
                    <a href="index.php?controller=estudiante&action=view&id=<?= $estudiante['id_estudiante'] ?>" 
                       class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .d-print-none {
        display: none !important;
    }
    .card {
        border: none !important;
    }
}
</style>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>

