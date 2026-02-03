<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <!-- Header del Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="bi bi-bar-chart-line"></i> Dashboard Directivo
            </h1>
            <p class="text-muted">Panel de supervisión y reportes académicos</p>
        </div>
    </div>
    
    <!-- Tarjetas de Estadísticas Generales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Estudiantes</h6>
                            <h2 class="mb-0"><?= $totalEstudiantes ?></h2>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people-fill text-info fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Promedio General</h6>
                            <h2 class="mb-0"><?= number_format($estadisticas['promedio_general'] ?? 0, 1) ?></h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-graph-up text-success fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Tasa Aprobación</h6>
                            <h2 class="mb-0">
                                <?php 
                                $total = ($estadisticas['total_aprobados'] ?? 0) + ($estadisticas['total_reprobados'] ?? 0);
                                $tasaAprobacion = $total > 0 ? round(($estadisticas['total_aprobados'] / $total) * 100, 1) : 0;
                                echo $tasaAprobacion . '%';
                                ?>
                            </h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-check-circle-fill text-success fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">En Riesgo</h6>
                            <h2 class="mb-0 text-danger"><?= count($estudiantesEnRiesgo) ?></h2>
                            <small class="text-muted">Promedio < 3.0</small>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="bi bi-exclamation-triangle-fill text-danger fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Rendimiento por Curso -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-graph-up-arrow"></i> Rendimiento Académico por Curso</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($rendimientoPorCurso)): ?>
                        <p class="text-muted mb-0">No hay datos de rendimiento disponibles</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Curso</th>
                                        <th>Grado</th>
                                        <th>Estudiantes</th>
                                        <th>Promedio</th>
                                        <th>Aprobados</th>
                                        <th>Reprobados</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rendimientoPorCurso as $curso): ?>
                                        <?php 
                                        $promedio = $curso['promedio_curso'] ?? 0;
                                        $colorPromedio = $promedio >= 4.0 ? 'success' : ($promedio >= 3.0 ? 'warning' : 'danger');
                                        ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($curso['nombre_curso']) ?></strong></td>
                                            <td><?= htmlspecialchars(GRADOS[$curso['grado']]) ?></td>
                                            <td><?= $curso['total_estudiantes'] ?></td>
                                            <td>
                                                <span class="badge bg-<?= $colorPromedio ?>">
                                                    <?= number_format($promedio, 1) ?>
                                                </span>
                                            </td>
                                            <td><span class="text-success"><?= $curso['aprobados'] ?></span></td>
                                            <td><span class="text-danger"><?= $curso['reprobados'] ?></span></td>
                                            <td>
                                                <?php
                                                $totalNotas = $curso['aprobados'] + $curso['reprobados'];
                                                $porcentajeAprobacion = $totalNotas > 0 ? 
                                                    round(($curso['aprobados'] / $totalNotas) * 100) : 0;
                                                ?>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success" role="progressbar" 
                                                         style="width: <?= $porcentajeAprobacion ?>%">
                                                        <?= $porcentajeAprobacion ?>%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Estudiantes en Riesgo -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-person-exclamation"></i> Estudiantes en Riesgo</h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    <?php if (empty($estudiantesEnRiesgo)): ?>
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-check-circle-fill"></i> No hay estudiantes en riesgo académico
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($estudiantesEnRiesgo as $estudiante): ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">
                                                <?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?>
                                            </h6>
                                            <small class="text-muted d-block">
                                                <?= htmlspecialchars($estudiante['nombre_curso']) ?>
                                            </small>
                                            <small class="text-danger">
                                                <i class="bi bi-exclamation-circle"></i>
                                                <?= $estudiante['materias_reprobadas'] ?> materia(s) reprobada(s)
                                            </small>
                                        </div>
                                        <span class="badge bg-danger">
                                            <?= number_format($estudiante['promedio_general'], 1) ?>
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
    
    <!-- Estadísticas por Jornada -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-clock"></i> Comparativa por Jornadas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($estadisticasPorJornada as $jornada): ?>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="text-capitalize mb-3">
                                            <i class="bi bi-sun"></i> Jornada <?= htmlspecialchars($jornada['jornada']) ?>
                                        </h5>
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <h3 class="text-primary mb-0"><?= $jornada['total_estudiantes'] ?></h3>
                                                <small class="text-muted">Estudiantes</small>
                                            </div>
                                            <div class="col-4">
                                                <h3 class="text-success mb-0"><?= $jornada['total_cursos'] ?></h3>
                                                <small class="text-muted">Cursos</small>
                                            </div>
                                            <div class="col-4">
                                                <h3 class="text-warning mb-0"><?= $jornada['estudiantes_con_alergias'] ?></h3>
                                                <small class="text-muted">Con Alergias</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Accesos Rápidos a Reportes -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Reportes Disponibles</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="/reportes/boletines.php" class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-journal-text fs-3 d-block mb-2"></i>
                                Boletines de Notas
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="/reportes/estudiantes-por-curso.php" class="btn btn-outline-success w-100 py-3">
                                <i class="bi bi-people fs-3 d-block mb-2"></i>
                                Estudiantes por Curso
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="/reportes/estudiantes-reprobados.php" class="btn btn-outline-danger w-100 py-3">
                                <i class="bi bi-exclamation-triangle fs-3 d-block mb-2"></i>
                                Estudiantes Reprobados
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="/reportes/estudiantes-alergias.php" class="btn btn-outline-warning w-100 py-3">
                                <i class="bi bi-heart-pulse fs-3 d-block mb-2"></i>
                                Alergias y Salud
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
