<?php 
$title = 'Detalles del Estudiante';
$controller = 'estudiante';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="bi bi-person-badge"></i> 
                    <?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?>
                </h4>
                <div>
                    <a href="index.php?controller=estudiante&action=edit&id=<?= $estudiante['id_estudiante'] ?>" 
                       class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <a href="index.php?controller=estudiante&action=index" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Datos Personales -->
                <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-person"></i> Datos Personales</h5>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Registro Civil:</strong> <?= htmlspecialchars($estudiante['registro_civil']) ?></p>
                        <p><strong>Tarjeta de Identidad:</strong> 
                            <?= $estudiante['tarjeta_identidad'] ? htmlspecialchars($estudiante['tarjeta_identidad']) : '<em>No registrada</em>' ?>
                        </p>
                        <p><strong>Edad:</strong> <?= htmlspecialchars($estudiante['edad']) ?> años</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Curso:</strong> 
                            <span class="badge bg-secondary"><?= htmlspecialchars($curso['nombre_curso']) ?></span>
                        </p>
                        <p><strong>Jornada:</strong> 
                            <span class="badge bg-<?= $estudiante['jornada'] === 'mañana' ? 'warning' : 'info' ?>">
                                <?= ucfirst(htmlspecialchars($estudiante['jornada'])) ?>
                            </span>
                        </p>
                        <p><strong>Fecha de Registro:</strong> 
                            <?= date('d/m/Y H:i', strtotime($estudiante['fecha_registro'])) ?>
                        </p>
                    </div>
                </div>
                
                <!-- Documento PDF -->
                <?php if ($estudiante['documento_pdf']): ?>
                    <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-file-pdf"></i> Documento de Identidad</h5>
                    <div class="mb-4">
                        <a href="uploads/<?= htmlspecialchars($estudiante['documento_pdf']) ?>" 
                           target="_blank" class="btn btn-outline-primary">
                            <i class="bi bi-download"></i> Ver/Descargar Documento PDF
                        </a>
                    </div>
                <?php endif; ?>
                
                <!-- Información de Salud -->
                <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-heart-pulse"></i> Información de Salud</h5>
                <div class="mb-4">
                    <?php if ($estudiante['tiene_alergias'] && !empty($alergias)): ?>
                        <div class="alert alert-danger">
                            <strong><i class="bi bi-exclamation-triangle"></i> ALERGIAS REGISTRADAS:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach ($alergias as $alergia): ?>
                                    <li><?= htmlspecialchars($alergia['tipo_alergia']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <p class="text-success"><i class="bi bi-check-circle"></i> No presenta alergias registradas</p>
                    <?php endif; ?>
                </div>
                
                <!-- Acudientes -->
                <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-people"></i> Acudientes</h5>
                <div class="mb-4">
                    <?php if (!empty($acudientes)): ?>
                        <div class="row">
                            <?php foreach ($acudientes as $acudiente): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card <?= $acudiente['es_principal'] ? 'border-primary' : '' ?>">
                                        <div class="card-body">
                                            <?php if ($acudiente['es_principal']): ?>
                                                <span class="badge bg-primary mb-2">Acudiente Principal</span>
                                            <?php endif; ?>
                                            <h6><?= htmlspecialchars($acudiente['nombre']) ?></h6>
                                            <p class="mb-1">
                                                <i class="bi bi-telephone"></i> 
                                                <?= htmlspecialchars($acudiente['telefono']) ?>
                                            </p>
                                            <p class="mb-0">
                                                <i class="bi bi-person-heart"></i> 
                                                <?= htmlspecialchars($acudiente['parentesco']) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted"><em>No hay acudientes registrados</em></p>
                    <?php endif; ?>
                </div>
                
                <!-- Acciones -->
                <div class="mt-4">
                    <a href="index.php?controller=nota&action=boletin&id_estudiante=<?= $estudiante['id_estudiante'] ?>&id_periodo=1" 
                       class="btn btn-primary">
                        <i class="bi bi-journal-text"></i> Ver Boletín de Notas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>

