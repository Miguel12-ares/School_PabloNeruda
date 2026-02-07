<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <!-- Header del Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="bi bi-person-workspace"></i> Panel Instructor
            </h1>
            <p class="text-muted">Gestión de tus cursos, materias y estudiantes asignados.</p>
        </div>
    </div>
    
    <!-- Accesos Rápidos en Tarjetas -->
    <div class="row justify-content-center g-4 mb-4">
        <!-- Mis Cursos -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-book-fill text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Mis Cursos</h5>
                    <p class="card-text text-muted mb-3">Ver cursos asignados: <strong><?= count($cursosAsignados) ?></strong></p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=curso&action=index" class="btn btn-primary">
                            <i class="bi bi-list-ul"></i> Ver Cursos
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mis Materias -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-journal-text text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Mis Materias</h5>
                    <p class="card-text text-muted mb-3">Materias que imparto: <strong><?= count($materiasAsignadas) ?></strong></p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=materia&action=index" class="btn btn-primary">
                            <i class="bi bi-journal"></i> Ver Materias
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registrar Notas -->
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-pencil-square text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Registrar Notas</h5>
                    <p class="card-text text-muted mb-3">Registrar y gestionar calificaciones.</p>
                    <div class="d-grid gap-2">
                        <a href="/index.php?controller=nota&action=index" class="btn btn-primary">
                            <i class="bi bi-clipboard-check"></i> Registrar Notas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estudiantes con Alerta -->
        <?php if (count($estudiantesConAlerta) > 0): ?>
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm quick-access-card h-100">
                <div class="card-body text-center p-4">
                    <div class="quick-access-icon mb-3">
                        <i class="bi bi-exclamation-triangle-fill text-primary"></i>
                    </div>
                    <h5 class="card-title mb-2">Estudiantes en Alerta</h5>
                    <p class="card-text text-muted mb-3">Con promedio bajo: <strong><?= count($estudiantesConAlerta) ?></strong></p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#alertasModal">
                            <i class="bi bi-eye"></i> Ver Alertas
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Materias que Imparte -->
    <?php if (!empty($materiasAsignadas)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-journal-bookmark-fill"></i> Mis Materias Asignadas</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php foreach ($materiasAsignadas as $materia): ?>
                            <div class="col-md-6 col-lg-3">
                                <div class="card border-primary h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-journal-bookmark-fill text-primary fs-1 mb-2"></i>
                                        <h6 class="mb-0"><?= htmlspecialchars($materia['nombre_materia']) ?></h6>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Cursos Asignados en Grilla -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-grid-3x3-gap-fill"></i> Mis Cursos Asignados</h5>
                    <p class="text-muted small mb-0">Haz clic en un curso para ver los detalles completos y gestionar notas</p>
                </div>
                <div class="card-body">
                    <?php if (empty($estadisticasPorCurso)): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No tienes cursos asignados actualmente
                        </div>
                    <?php else: ?>
                        <div class="row g-4">
                            <?php foreach ($estadisticasPorCurso as $data): ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 border-0 shadow-sm curso-card" 
                                         style="cursor: pointer; transition: all 0.3s ease;"
                                         onclick="window.location.href='/index.php?controller=curso&action=detalle&id=<?= $data['curso']['id_curso'] ?>'">
                                        <div class="card-header text-white" 
                                             style="background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);">
                                            <h5 class="mb-0">
                                                <i class="bi bi-mortarboard-fill"></i> 
                                                <?= htmlspecialchars($data['curso']['nombre_curso']) ?>
                                            </h5>
                                            <small class="opacity-75">
                                                Grado <?= $data['curso']['grado'] ?> - Sección <?= strtoupper($data['curso']['seccion']) ?>
                                            </small>
                                        </div>
                                        <div class="card-body">
                                            <!-- Estadísticas del curso -->
                                            <div class="row text-center mb-3">
                                                <div class="col-6 mb-3">
                                                    <div class="p-2 bg-light rounded">
                                                        <h4 class="mb-0" style="color: #1e3a5f;">
                                                            <?= $data['total_estudiantes'] ?>
                                                        </h4>
                                                        <small class="text-muted">Estudiantes</small>
                                                    </div>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <div class="p-2 bg-light rounded">
                                                        <h4 class="mb-0 text-success">
                                                            <?= number_format($data['estadisticas']['promedio_curso'] ?? 0, 1) ?>
                                                        </h4>
                                                        <small class="text-muted">Promedio</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="p-2 bg-light rounded">
                                                        <h4 class="mb-0 text-success">
                                                            <?= $data['estadisticas']['total_aprobados'] ?? 0 ?>
                                                        </h4>
                                                        <small class="text-muted">Aprobados</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="p-2 bg-light rounded">
                                                        <h4 class="mb-0 text-danger">
                                                            <?= $data['estadisticas']['total_reprobados'] ?? 0 ?>
                                                        </h4>
                                                        <small class="text-muted">Reprobados</small>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Materias que imparte -->
                                            <div class="mb-3">
                                                <h6 class="mb-2 small"><i class="bi bi-journal"></i> Tus Materias:</h6>
                                                <div class="d-flex gap-1 flex-wrap">
                                                    <?php foreach ($data['materias'] as $materia): ?>
                                                        <span class="badge" style="background-color: #1e3a5f; font-size: 0.7rem;">
                                                            <?= htmlspecialchars($materia['nombre_materia']) ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                            
                                            <!-- Botones de acción -->
                                            <div class="d-grid gap-2">
                                                <a href="/index.php?controller=curso&action=detalle&id=<?= $data['curso']['id_curso'] ?>" 
                                                   class="btn btn-sm" style="background-color: #1e3a5f; color: white;"
                                                   onclick="event.stopPropagation();">
                                                    <i class="bi bi-eye"></i> Ver Detalles
                                                </a>
                                                <div class="btn-group" role="group">
                                                    <a href="/index.php?controller=estudiante&action=index&curso=<?= $data['curso']['id_curso'] ?>" 
                                                       class="btn btn-sm btn-outline-primary"
                                                       onclick="event.stopPropagation();">
                                                        <i class="bi bi-people"></i> Estudiantes
                                                    </a>
                                                    <a href="/index.php?controller=nota&action=registrar&id_curso=<?= $data['curso']['id_curso'] ?>&id_periodo=1" 
                                                       class="btn btn-sm btn-outline-success"
                                                       onclick="event.stopPropagation();">
                                                        <i class="bi bi-pencil-square"></i> Notas
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Estudiantes con Alerta -->
<?php if (!empty($estudiantesConAlerta)): ?>
<div class="modal fade" id="alertasModal" tabindex="-1" aria-labelledby="alertasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-warning bg-opacity-10">
                <h5 class="modal-title text-warning" id="alertasModalLabel">
                    <i class="bi bi-exclamation-triangle-fill"></i> Estudiantes que Requieren Atención
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th>Registro Civil</th>
                                <th>Promedio</th>
                                <th>Materias Reprobadas</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($estudiantesConAlerta as $estudiante): ?>
                                <tr>
                                    <td>
                                        <strong>
                                            <?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?>
                                        </strong>
                                    </td>
                                    <td><?= htmlspecialchars($estudiante['registro_civil']) ?></td>
                                    <td>
                                        <span class="badge bg-danger">
                                            <?= number_format($estudiante['promedio_general'], 1) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            <?= $estudiante['materias_reprobadas'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="/index.php?controller=estudiante&action=view&id=<?= $estudiante['id_estudiante'] ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Ver Detalle
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
