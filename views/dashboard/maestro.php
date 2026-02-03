<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <!-- Header del Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="bi bi-person-workspace"></i> Dashboard Maestro
            </h1>
            <p class="text-muted">Gestión de tus cursos y estudiantes asignados</p>
        </div>
    </div>
    
    <!-- Resumen de Cursos Asignados -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Cursos Asignados</h6>
                            <h2 class="mb-0"><?= count($cursosAsignados) ?></h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-book-fill text-primary fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Materias que Imparte</h6>
                            <h2 class="mb-0"><?= count($materiasAsignadas) ?></h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-journal-text text-success fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Estudiantes con Alerta</h6>
                            <h2 class="mb-0 text-warning"><?= count($estudiantesConAlerta) ?></h2>
                            <small class="text-muted">Promedio < 3.0</small>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-exclamation-triangle-fill text-warning fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Materias Asignadas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Mis Materias</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($materiasAsignadas)): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No tienes materias asignadas actualmente
                        </div>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php foreach ($materiasAsignadas as $materia): ?>
                                <div class="col-md-4">
                                    <div class="card border-primary">
                                        <div class="card-body text-center">
                                            <i class="bi bi-journal-bookmark-fill text-primary fs-1 mb-2"></i>
                                            <h6><?= htmlspecialchars($materia['nombre_materia']) ?></h6>
                                            <p class="small text-muted mb-2">
                                                Selecciona un curso abajo para registrar notas
                                            </p>
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
    
    <!-- Detalle por Curso -->
    <?php foreach ($estadisticasPorCurso as $data): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="mb-0 text-white">
                            <i class="bi bi-mortarboard-fill"></i> 
                            <?= htmlspecialchars($data['curso']['nombre_curso']) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <h4 class="text-primary mb-0"><?= $data['total_estudiantes'] ?></h4>
                                    <small class="text-muted">Estudiantes</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <h4 class="text-success mb-0">
                                        <?= number_format($data['estadisticas']['promedio_curso'] ?? 0, 1) ?>
                                    </h4>
                                    <small class="text-muted">Promedio</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <h4 class="text-success mb-0"><?= $data['estadisticas']['total_aprobados'] ?? 0 ?></h4>
                                    <small class="text-muted">Aprobados</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <h4 class="text-danger mb-0"><?= $data['estadisticas']['total_reprobados'] ?? 0 ?></h4>
                                    <small class="text-muted">Reprobados</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Materias que imparte en este curso -->
                        <div class="mb-3">
                            <h6 class="mb-2"><i class="bi bi-journal"></i> Materias que impartes en este curso:</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <?php foreach ($data['materias'] as $materia): ?>
                                    <span class="badge bg-primary">
                                        <?= htmlspecialchars($materia['nombre_materia']) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Botones de acción -->
                        <div class="d-flex gap-2">
                            <a href="/index.php?controller=estudiante&action=index&curso=<?= $data['curso']['id_curso'] ?>" 
                               class="btn btn-primary">
                                <i class="bi bi-people"></i> Ver Estudiantes
                            </a>
                            <a href="/index.php?controller=nota&action=index&curso=<?= $data['curso']['id_curso'] ?>" 
                               class="btn btn-success">
                                <i class="bi bi-journal-text"></i> Ver Notas
                            </a>
                            <a href="/index.php?controller=nota&action=registrar&id_curso=<?= $data['curso']['id_curso'] ?>&id_periodo=1" 
                               class="btn btn-info">
                                <i class="bi bi-pencil-square"></i> Registrar Notas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    
    <!-- Estudiantes con Alerta -->
    <?php if (!empty($estudiantesConAlerta)): ?>
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm border-warning">
                    <div class="card-header bg-warning bg-opacity-10 border-0 py-3">
                        <h5 class="mb-0 text-warning">
                            <i class="bi bi-exclamation-triangle-fill"></i> 
                            Estudiantes que Requieren Atención
                        </h5>
                    </div>
                    <div class="card-body">
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
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
