<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-journal-bookmark-fill"></i> <?= htmlspecialchars($materia['nombre_materia']) ?></h2>
            <p class="text-muted">Detalle completo de la materia</p>
        </div>
        <div class="col-md-4 text-end">
            <?php if ($permissionMiddleware->checkPermission('materias', 'editar')): ?>
                <a href="/index.php?controller=materia&action=edit&id=<?= $materia['id_materia'] ?>" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Editar
                </a>
            <?php endif; ?>
            <a href="/index.php?controller=materia&action=index" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información de la Materia</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="30%">ID:</th>
                            <td><?= $materia['id_materia'] ?></td>
                        </tr>
                        <tr>
                            <th>Nombre:</th>
                            <td><strong><?= htmlspecialchars($materia['nombre_materia']) ?></strong></td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>
                                <?php if ($materia['estado']): ?>
                                    <span class="badge bg-success">Activa</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactiva</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <?php if (!empty($maestros)): ?>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-person-badge"></i> Maestros que Imparten esta Materia</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Maestro</th>
                                    <th>Email</th>
                                    <th>Cursos</th>
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
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No hay maestros asignados a esta materia
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
