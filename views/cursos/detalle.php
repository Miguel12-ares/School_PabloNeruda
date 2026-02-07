<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <!-- Header con información del curso -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header text-white py-4" 
                     style="background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">
                                <i class="bi bi-mortarboard-fill"></i> 
                                <?= htmlspecialchars($curso['nombre_curso']) ?>
                            </h2>
                            <p class="mb-0 opacity-75">
                                Grado <?= $curso['grado'] ?> - Sección <?= strtoupper($curso['seccion']) ?> - 
                                Jornada <?= ucfirst($curso['jornada']) ?>
                            </p>
                        </div>
                        <a href="/dashboard/maestro.php" class="btn btn-light">
                            <i class="bi bi-arrow-left"></i> Volver al Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="mb-0" style="color: #1e3a5f;">
                                    <?= count($estudiantes) ?>
                                </h3>
                                <small class="text-muted">Total Estudiantes</small>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        Capacidad: <?= $curso['capacidad_maxima'] ?>
                                        (<?= round((count($estudiantes) / $curso['capacidad_maxima']) * 100) ?>% ocupado)
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="mb-0 text-success">
                                    <?= number_format($estadisticas['promedio_curso'] ?? 0, 1) ?>
                                </h3>
                                <small class="text-muted">Promedio General</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="mb-0 text-success">
                                    <?= $estadisticas['total_aprobados'] ?? 0 ?>
                                </h3>
                                <small class="text-muted">Aprobados</small>
                                <div class="mt-2">
                                    <?php 
                                    $total = ($estadisticas['total_aprobados'] ?? 0) + ($estadisticas['total_reprobados'] ?? 0);
                                    $porcentaje = $total > 0 ? round(($estadisticas['total_aprobados'] / $total) * 100) : 0;
                                    ?>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: <?= $porcentaje ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="mb-0 text-danger">
                                    <?= $estadisticas['total_reprobados'] ?? 0 ?>
                                </h3>
                                <small class="text-muted">Reprobados</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Materias y Maestros -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-journal-bookmark-fill"></i> Materias del Curso</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($materias)): ?>
                        <p class="text-muted mb-0">No hay materias asignadas a este curso</p>
                    <?php else: ?>
                        <div class="row g-2">
                            <?php foreach ($materias as $materia): ?>
                                <div class="col-6">
                                    <div class="card border" style="border-color: #1e3a5f !important;">
                                        <div class="card-body p-2 text-center">
                                            <i class="bi bi-book text-primary"></i>
                                            <small class="d-block">
                                                <?= htmlspecialchars($materia['nombre_materia']) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-people-fill"></i> Maestros Asignados</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($maestros)): ?>
                        <p class="text-muted mb-0">No hay maestros asignados a este curso</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($maestros as $maestro): ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= htmlspecialchars($maestro['nombre_completo']) ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="bi bi-journal"></i> 
                                                <?= htmlspecialchars($maestro['materias_imparte'] ?? 'Sin materias') ?>
                                            </small>
                                        </div>
                                        <span class="badge" style="background-color: #1e3a5f;">
                                            Maestro
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Acciones Rápidas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-lightning-fill text-warning"></i> Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="/index.php?controller=estudiante&action=index&curso=<?= $curso['id_curso'] ?>" 
                               class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-people fs-3 d-block mb-2"></i>
                                Gestionar Estudiantes
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="/index.php?controller=nota&action=index&curso=<?= $curso['id_curso'] ?>" 
                               class="btn btn-outline-success w-100 py-3">
                                <i class="bi bi-journal-text fs-3 d-block mb-2"></i>
                                Ver Notas del Curso
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="/index.php?controller=nota&action=registrar&id_curso=<?= $curso['id_curso'] ?>&id_periodo=1" 
                               class="btn btn-outline-info w-100 py-3">
                                <i class="bi bi-pencil-square fs-3 d-block mb-2"></i>
                                Registrar Notas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Lista de Estudiantes -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill"></i> Estudiantes del Curso</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($estudiantes)): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No hay estudiantes inscritos en este curso
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Registro Civil</th>
                                        <th>Nombre Completo</th>
                                        <th>Edad</th>
                                        <th>Promedio</th>
                                        <th>Materias Reprobadas</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($estudiantes as $estudiante): ?>
                                        <?php
                                        $promedio = $estudiante['promedio_general'] ?? 0;
                                        $reprobadas = $estudiante['materias_reprobadas'] ?? 0;
                                        $estadoClass = $promedio >= 3.0 ? 'success' : 'danger';
                                        $estadoText = $promedio >= 3.0 ? 'Aprobado' : 'En Riesgo';
                                        ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($estudiante['registro_civil']) ?></strong>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?>
                                                <?php if ($estudiante['tiene_alergias']): ?>
                                                    <i class="bi bi-heart-pulse text-danger ms-1" 
                                                       title="Tiene alergias registradas"></i>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $estudiante['edad'] ?> años</td>
                                            <td>
                                                <span class="badge bg-<?= $estadoClass ?>">
                                                    <?= number_format($promedio, 1) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($reprobadas > 0): ?>
                                                    <span class="badge bg-warning text-dark">
                                                        <?= $reprobadas ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">0</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $estadoClass ?>">
                                                    <?= $estadoText ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="/index.php?controller=estudiante&action=view&id=<?= $estudiante['id_estudiante'] ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Ver Perfil">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="/index.php?controller=nota&action=registrar&id_estudiante=<?= $estudiante['id_estudiante'] ?>&id_curso=<?= $curso['id_curso'] ?>" 
                                                       class="btn btn-sm btn-outline-success" title="Registrar Notas">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Resumen adicional -->
                        <div class="mt-3 p-3 bg-light rounded">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <strong class="text-success">Estudiantes Aprobados:</strong>
                                    <?php 
                                    $aprobados = array_filter($estudiantes, fn($e) => ($e['promedio_general'] ?? 0) >= 3.0);
                                    echo count($aprobados);
                                    ?>
                                </div>
                                <div class="col-md-4">
                                    <strong class="text-danger">Estudiantes en Riesgo:</strong>
                                    <?php 
                                    $enRiesgo = array_filter($estudiantes, fn($e) => ($e['promedio_general'] ?? 0) < 3.0);
                                    echo count($enRiesgo);
                                    ?>
                                </div>
                                <div class="col-md-4">
                                    <strong class="text-warning">Con Alergias:</strong>
                                    <?php 
                                    $conAlergias = array_filter($estudiantes, fn($e) => $e['tiene_alergias']);
                                    echo count($conAlergias);
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
