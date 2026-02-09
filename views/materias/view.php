<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-2">
                <i class="fas fa-book-open"></i> 
                <?= htmlspecialchars($materia['nombre_materia']) ?>
            </h2>
            <p class="text-muted">Detalle completo de la materia</p>
        </div>
    </div>
    
    <div class="row mb-3">
        <div class="col-12 text-end">
            <?php if ($permissionMiddleware->checkPermission('materias', 'editar')): ?>
                <a href="/index.php?controller=materia&action=edit&id=<?= $materia['id_materia'] ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Editar
                </a>
            <?php endif; ?>
            <a href="/index.php?controller=materia&action=index" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Columna Izquierda - Información de la Materia -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información de la Materia</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td width="35%" class="text-muted">
                                <i class="fas fa-hashtag text-primary"></i> <strong>ID:</strong>
                            </td>
                            <td><?= $materia['id_materia'] ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">
                                <i class="fas fa-book text-primary"></i> <strong>Nombre:</strong>
                            </td>
                            <td><strong><?= htmlspecialchars($materia['nombre_materia']) ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">
                                <i class="fas fa-toggle-on text-primary"></i> <strong>Estado:</strong>
                            </td>
                            <td>
                                <?php if ($materia['estado']): ?>
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check-circle"></i> Activa
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary fs-6">
                                        <i class="fas fa-times-circle"></i> Inactiva
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Columna Derecha - Maestros Asignados -->
        <div class="col-lg-7">
            <?php if (!empty($maestros)): ?>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chalkboard-teacher"></i> 
                        Maestros que Imparten esta Materia (<?= count(array_unique(array_column($maestros, 'id_usuario'))) ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fas fa-user"></i> Maestro</th>
                                    <th><i class="fas fa-envelope"></i> Email</th>
                                    <th><i class="fas fa-book"></i> Cursos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $maestrosAgrupados = [];
                                foreach ($maestros as $maestro) {
                                    $id = $maestro['id_usuario'];
                                    if (!isset($maestrosAgrupados[$id])) {
                                        $maestrosAgrupados[$id] = [
                                            'nombre' => $maestro['nombre_completo'],
                                            'email' => $maestro['email'],
                                            'cursos' => []
                                        ];
                                    }
                                    $maestrosAgrupados[$id]['cursos'][] = $maestro['nombre_curso'];
                                }
                                ?>
                                <?php foreach ($maestrosAgrupados as $maestro): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($maestro['nombre']) ?></strong></td>
                                        <td><small><?= htmlspecialchars($maestro['email']) ?></small></td>
                                        <td>
                                            <?php foreach ($maestro['cursos'] as $curso): ?>
                                                <span class="badge bg-primary me-1">
                                                    <?= htmlspecialchars($curso) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chalkboard-teacher"></i> 
                        Maestros Asignados
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0 mb-0">
                        <i class="fas fa-info-circle"></i> No hay maestros asignados a esta materia
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
