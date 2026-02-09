<?php 
$title = 'Detalles del Estudiante';
$controller = 'estudiante';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-2">
                <i class="fas fa-user-graduate"></i> 
                <?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?>
            </h2>
            <p class="text-muted">Información detallada del estudiante</p>
        </div>
    </div>
    
    <div class="row mb-3">
        <div class="col-12 text-end">
            <a href="index.php?controller=estudiante&action=edit&id=<?= $estudiante['id_estudiante'] ?>" 
               class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="index.php?controller=estudiante&action=index" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Columna Izquierda -->
        <div class="col-lg-6">
            <!-- Datos Personales -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-id-card"></i> Datos Personales</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td width="40%" class="text-muted"><i class="fas fa-file-alt text-primary"></i> <strong>Registro Civil:</strong></td>
                            <td><?= htmlspecialchars($estudiante['registro_civil']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="fas fa-id-badge text-primary"></i> <strong>Tarjeta de Identidad:</strong></td>
                            <td>
                                <?= $estudiante['tarjeta_identidad'] ? htmlspecialchars($estudiante['tarjeta_identidad']) : '<em class="text-muted">No registrada</em>' ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="fas fa-birthday-cake text-primary"></i> <strong>Edad:</strong></td>
                            <td><?= htmlspecialchars($estudiante['edad']) ?> años</td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="fas fa-calendar text-primary"></i> <strong>Fecha de Registro:</strong></td>
                            <td><?= date('d/m/Y H:i', strtotime($estudiante['fecha_registro'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Información Académica -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Información Académica</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td width="40%" class="text-muted"><i class="fas fa-book text-primary"></i> <strong>Curso:</strong></td>
                            <td>
                                <span class="badge bg-primary fs-6"><?= htmlspecialchars($curso['nombre_curso']) ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="fas fa-clock text-primary"></i> <strong>Jornada:</strong></td>
                            <td>
                                <span class="badge bg-<?= $estudiante['jornada'] === 'mañana' ? 'info' : 'secondary' ?> fs-6">
                                    <?= ucfirst(htmlspecialchars($estudiante['jornada'])) ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Documento PDF -->
            <?php if ($estudiante['documento_pdf']): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-file-pdf"></i> Documento de Identidad</h5>
                </div>
                <div class="card-body text-center">
                    <a href="uploads/<?= htmlspecialchars($estudiante['documento_pdf']) ?>" 
                       target="_blank" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-download"></i> Ver/Descargar Documento PDF
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Columna Derecha -->
        <div class="col-lg-6">
            <!-- Información de Salud -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-heartbeat"></i> Información de Salud</h5>
                </div>
                <div class="card-body">
                    <?php if ($estudiante['tiene_alergias'] && !empty($alergias)): ?>
                        <div class="alert alert-danger border-0">
                            <h6 class="alert-heading">
                                <i class="fas fa-exclamation-triangle"></i> <strong>ALERGIAS REGISTRADAS</strong>
                            </h6>
                            <ul class="mb-0 mt-2">
                                <?php foreach ($alergias as $alergia): ?>
                                    <li><i class="fas fa-allergies"></i> <?= htmlspecialchars($alergia['tipo_alergia']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <p class="text-success mb-0">
                            <i class="fas fa-check-circle"></i> No presenta alergias registradas
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Acudientes -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Acudientes</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($acudientes)): ?>
                        <div class="row g-3">
                            <?php foreach ($acudientes as $acudiente): ?>
                                <div class="col-12">
                                    <div class="card <?= $acudiente['es_principal'] ? 'border-primary' : 'border' ?>">
                                        <div class="card-body">
                                            <?php if ($acudiente['es_principal']): ?>
                                                <span class="badge bg-primary mb-2">
                                                    <i class="fas fa-star"></i> Acudiente Principal
                                                </span>
                                            <?php endif; ?>
                                            <h6 class="mb-2"><i class="fas fa-user"></i> <?= htmlspecialchars($acudiente['nombre']) ?></h6>
                                            <p class="mb-1 text-muted">
                                                <i class="fas fa-phone text-primary"></i> 
                                                <?= htmlspecialchars($acudiente['telefono']) ?>
                                            </p>
                                            <p class="mb-0 text-muted">
                                                <i class="fas fa-user-friends text-primary"></i> 
                                                <?= htmlspecialchars($acudiente['parentesco']) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0"><em>No hay acudientes registrados</em></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Acciones Rápidas -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt"></i> Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <a href="index.php?controller=nota&action=boletin&id_estudiante=<?= $estudiante['id_estudiante'] ?>&id_periodo=1" 
                       class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-file-alt"></i> Ver Boletín de Notas
                    </a>
                    <a href="index.php?controller=estudiante&action=edit&id=<?= $estudiante['id_estudiante'] ?>" 
                       class="btn btn-outline-primary w-100">
                        <i class="fas fa-edit"></i> Editar Información
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
