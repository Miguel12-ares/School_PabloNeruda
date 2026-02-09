<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-2"><i class="fas fa-book-open"></i> <?= htmlspecialchars($curso['nombre_curso']) ?></h2>
            <p class="text-muted">Detalle completo del curso</p>
        </div>
    </div>
    
    <div class="row mb-3">
        <div class="col-12 text-end">
            <?php if ($permissionMiddleware->checkPermission('cursos', 'editar')): ?>
                <a href="/index.php?controller=curso&action=edit&id=<?= $curso['id_curso'] ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Editar
                </a>
            <?php endif; ?>
            <a href="/index.php?controller=curso&action=index" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    
    <div class="row">
        <!-- Información del Curso -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información del Curso</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">ID:</th>
                            <td><?= $curso['id_curso'] ?></td>
                        </tr>
                        <tr>
                            <th>Nombre:</th>
                            <td><strong><?= htmlspecialchars($curso['nombre_curso']) ?></strong></td>
                        </tr>
                        <tr>
                            <th>Grado:</th>
                            <td>
                                <?php if ($curso['grado'] == 0): ?>
                                    <span class="badge bg-warning text-dark">0° (Preescolar)</span>
                                <?php else: ?>
                                    <span class="badge bg-primary"><?= $curso['grado'] ?>° Grado</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Sección:</th>
                            <td><?= $curso['seccion'] ? htmlspecialchars($curso['seccion']) : '-' ?></td>
                        </tr>
                        <tr>
                            <th>Jornada:</th>
                            <td>
                                <span class="badge bg-<?= $curso['jornada'] === 'mañana' ? 'primary' : 'secondary' ?>">
                                    <?= ucfirst($curso['jornada']) ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Estadísticas -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Estadísticas</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Capacidad Máxima</small>
                        <h3 class="mb-0"><?= $curso['capacidad_maxima'] ?></h3>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Estudiantes Matriculados</small>
                        <h3 class="mb-0 text-primary"><?= count($estudiantes) ?></h3>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Cupos Disponibles</small>
                        <h3 class="mb-0 text-success">
                            <?= max(0, $curso['capacidad_maxima'] - count($estudiantes)) ?>
                        </h3>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Ocupación</small>
                        <?php 
                        $totalEstudiantes = count($estudiantes);
                        $capacidad = $curso['capacidad_maxima'];
                        $porcentaje = $capacidad > 0 ? ($totalEstudiantes / $capacidad) * 100 : 0;
                        $colorBarra = $porcentaje >= 90 ? 'danger' : ($porcentaje >= 75 ? 'warning' : 'success');
                        ?>
                        <h4 class="mb-0">
                            <span class="badge bg-<?= $colorBarra ?> fs-5">
                                <?= $totalEstudiantes ?> / <?= $capacidad ?>
                            </span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Estudiantes del Curso -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Estudiantes del Curso (<?= count($estudiantes) ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($estudiantes)): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No hay estudiantes matriculados en este curso
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Registro Civil</th>
                                        <th>Nombre Completo</th>
                                        <th>Edad</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($estudiantes as $estudiante): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($estudiante['registro_civil']) ?></td>
                                            <td>
                                                <strong>
                                                    <?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?>
                                                </strong>
                                            </td>
                                            <td><?= $estudiante['edad'] ?> años</td>
                                            <td>
                                                <a href="/index.php?controller=estudiante&action=view&id=<?= $estudiante['id_estudiante'] ?>" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Maestros Asignados -->
            <?php if (!empty($maestros)): ?>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-chalkboard-teacher"></i> Maestros Asignados (<?= count($maestros) ?>)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Maestro</th>
                                    <th>Email</th>
                                    <th>Materias que Imparte</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($maestros as $maestro): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($maestro['nombre_completo']) ?></strong></td>
                                        <td><small><?= htmlspecialchars($maestro['email']) ?></small></td>
                                        <td>
                                            <?php 
                                            $materias = explode(', ', $maestro['materias_imparte'] ?? '');
                                            foreach ($materias as $materia): 
                                                if (!empty(trim($materia))):
                                            ?>
                                                <span class="badge bg-primary me-1">
                                                    <?= htmlspecialchars($materia) ?>
                                                </span>
                                            <?php 
                                                endif;
                                            endforeach; 
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
