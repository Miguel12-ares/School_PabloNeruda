<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <!-- Header del Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="bi bi-speedometer2"></i> Dashboard Administrativo
            </h1>
            <p class="text-muted">Panel de control con acceso completo al sistema</p>
        </div>
    </div>
    
    <!-- Tarjetas de Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Estudiantes</h6>
                            <h2 class="mb-0"><?= $totalEstudiantes ?></h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people-fill text-primary fs-2"></i>
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
                            <h6 class="text-muted mb-2">Cursos Activos</h6>
                            <h2 class="mb-0"><?= $totalCursos ?></h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-book-fill text-success fs-2"></i>
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
                            <h6 class="text-muted mb-2">Aprobados</h6>
                            <h2 class="mb-0"><?= $estadisticas['total_aprobados'] ?? 0 ?></h2>
                            <small class="text-success">
                                <?php 
                                $total = ($estadisticas['total_aprobados'] ?? 0) + ($estadisticas['total_reprobados'] ?? 0);
                                $porcentaje = $total > 0 ? round(($estadisticas['total_aprobados'] / $total) * 100, 1) : 0;
                                echo $porcentaje . '%';
                                ?>
                            </small>
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
                            <h6 class="text-muted mb-2">Reprobados</h6>
                            <h2 class="mb-0"><?= $estadisticas['total_reprobados'] ?? 0 ?></h2>
                            <small class="text-danger">
                                <?php 
                                $porcentajeRep = $total > 0 ? round(($estadisticas['total_reprobados'] / $total) * 100, 1) : 0;
                                echo $porcentajeRep . '%';
                                ?>
                            </small>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="bi bi-x-circle-fill text-danger fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Accesos Rápidos -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-lightning-fill text-warning"></i> Accesos Rápidos</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="/estudiantes" class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-person-plus-fill fs-3 d-block mb-2"></i>
                                Gestionar Estudiantes
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="/notas" class="btn btn-outline-success w-100 py-3">
                                <i class="bi bi-journal-text fs-3 d-block mb-2"></i>
                                Registrar Notas
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="/usuarios" class="btn btn-outline-info w-100 py-3">
                                <i class="bi bi-people fs-3 d-block mb-2"></i>
                                Gestionar Usuarios
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="/reportes" class="btn btn-outline-warning w-100 py-3">
                                <i class="bi bi-file-earmark-bar-graph fs-3 d-block mb-2"></i>
                                Ver Reportes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Alertas de Capacidad -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle-fill text-warning"></i> Alertas de Capacidad</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($cursosConAlerta)): ?>
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-check-circle-fill"></i> Todos los cursos tienen capacidad disponible
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($cursosConAlerta as $curso): ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($curso['nombre_curso']) ?></h6>
                                            <small class="text-muted">
                                                <?= $curso['total_estudiantes'] ?> / <?= $curso['capacidad_maxima'] ?> estudiantes
                                            </small>
                                        </div>
                                        <span class="badge bg-warning text-dark">
                                            <?= number_format($curso['porcentaje_ocupacion'], 1) ?>% ocupado
                                        </span>
                                    </div>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-warning" role="progressbar" 
                                             style="width: <?= $curso['porcentaje_ocupacion'] ?>%">
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
    
    <!-- Últimas Actividades (Auditoría) -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Últimas Actividades del Sistema</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($ultimasActividades)): ?>
                        <p class="text-muted mb-0">No hay actividades registradas</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Acción</th>
                                        <th>Módulo</th>
                                        <th>Detalles</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ultimasActividades as $actividad): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($actividad['nombre_completo']) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= htmlspecialchars($actividad['username']) ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    <?= htmlspecialchars($actividad['accion']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($actividad['modulo']) ?></td>
                                            <td>
                                                <small><?= htmlspecialchars($actividad['detalles'] ?? '-') ?></small>
                                            </td>
                                            <td>
                                                <small><?= date('d/m/Y H:i', strtotime($actividad['fecha_accion'])) ?></small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="/auditoria" class="btn btn-sm btn-outline-primary">
                                Ver Historial Completo <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
