<?php 
$title = 'Boletín de Notas';
$controller = 'nota';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-2 fw-bold"><i class="fas fa-certificate"></i> BOLETÍN DE CALIFICACIONES</h3>
                    <h5 class="mb-1">Escuela Pablo Neruda</h5>
                    <p class="mb-0 small">Barrio Las Malvinas, Sector 4 Berlín</p>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($boletin)): ?>
                        <?php $primer_registro = $boletin[0]; ?>
                        
                        <!-- Información del Estudiante -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="text-primary mb-3">
                                            <i class="fas fa-user-graduate"></i> Datos del Estudiante
                                        </h6>
                                        <p class="mb-2">
                                            <strong>Nombre:</strong> 
                                            <?= htmlspecialchars($primer_registro['nombre'] . ' ' . $primer_registro['apellido']) ?>
                                        </p>
                                        <p class="mb-0">
                                            <strong>Registro Civil:</strong> 
                                            <?= htmlspecialchars($primer_registro['registro_civil']) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="text-primary mb-3">
                                            <i class="fas fa-school"></i> Información Académica
                                        </h6>
                                        <p class="mb-2">
                                            <strong>Curso:</strong> 
                                            <?= htmlspecialchars($primer_registro['nombre_curso']) ?>
                                        </p>
                                        <p class="mb-0">
                                            <strong>Periodo:</strong> 
                                            <?= $primer_registro['numero_periodo'] ?> - Año <?= $primer_registro['anio_lectivo'] ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tabla de Notas -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th class="text-start">
                                            <i class="fas fa-book"></i> Materia
                                        </th>
                                        <th><i class="fas fa-clipboard-check"></i> Nota 1</th>
                                        <th><i class="fas fa-clipboard-check"></i> Nota 2</th>
                                        <th><i class="fas fa-clipboard-check"></i> Nota 3</th>
                                        <th><i class="fas fa-clipboard-check"></i> Nota 4</th>
                                        <th><i class="fas fa-clipboard-check"></i> Nota 5</th>
                                        <th><i class="fas fa-calculator"></i> Promedio</th>
                                        <th><i class="fas fa-check-circle"></i> Estado</th>
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
                                                <strong class="fs-6">
                                                    <?= number_format($nota['promedio'], 2) ?>
                                                </strong>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($nota['estado'] === 'aprobado'): ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Aprobado
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times"></i> Reprobado
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-primary">
                                    <tr>
                                        <td colspan="6" class="text-end">
                                            <strong class="fs-5">
                                                <i class="fas fa-chart-line"></i> Promedio General:
                                            </strong>
                                        </td>
                                        <td class="text-center">
                                            <strong class="fs-4 text-primary">
                                                <?= $promedio_general ? number_format($promedio_general, 2) : '-' ?>
                                            </strong>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($promedio_general): ?>
                                                <?php if ($promedio_general >= 3.0): ?>
                                                    <span class="badge bg-success fs-6">
                                                        <i class="fas fa-check"></i> Aprobado
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger fs-6">
                                                        <i class="fas fa-times"></i> Reprobado
                                                    </span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <!-- Escala de Valoración -->
                        <div class="card border-0 bg-light mt-4">
                            <div class="card-body">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-ruler"></i> Escala de Valoración Nacional
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2">
                                                <span class="badge bg-success me-2">4.6 - 5.0</span>
                                                <strong>Desempeño Superior</strong>
                                            </li>
                                            <li class="mb-2">
                                                <span class="badge bg-info me-2">4.0 - 4.5</span>
                                                <strong>Desempeño Alto</strong>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2">
                                                <span class="badge bg-warning me-2">3.0 - 3.9</span>
                                                <strong>Desempeño Básico</strong>
                                            </li>
                                            <li class="mb-2">
                                                <span class="badge bg-danger me-2">0.0 - 2.9</span>
                                                <strong>Desempeño Bajo</strong>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botones -->
                        <div class="mt-4 text-center d-print-none">
                            <button onclick="window.print()" class="btn btn-primary btn-lg px-5 me-2">
                                <i class="fas fa-print"></i> Imprimir Boletín
                            </button>
                            <a href="index.php?controller=estudiante&action=view&id=<?= $estudiante['id_estudiante'] ?>" 
                               class="btn btn-secondary btn-lg px-5">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                        
                    <?php else: ?>
                        <div class="alert alert-info border-0 shadow-sm">
                            <i class="fas fa-info-circle"></i> No hay notas registradas para este periodo.
                        </div>
                        <div class="text-center">
                            <a href="index.php?controller=estudiante&action=view&id=<?= $estudiante['id_estudiante'] ?>" 
                               class="btn btn-secondary btn-lg px-5">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
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
        box-shadow: none !important;
    }
    body {
        background: white !important;
    }
    .container-fluid {
        padding: 0 !important;
    }
}
</style>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
