<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-person-circle"></i> Detalle del Usuario</h2>
            <p class="text-muted">Información completa y actividad del usuario</p>
        </div>
        <div class="col-md-4 text-end">
            <?php if ($permissionMiddleware->checkPermission('usuarios', 'editar')): ?>
                <a href="/index.php?controller=usuario&action=edit&id=<?= $usuario['id_usuario'] ?>" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Editar
                </a>
            <?php endif; ?>
            <a href="/index.php?controller=usuario&action=index" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    
    <div class="row">
        <!-- Información del Usuario -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-person-circle text-primary" style="font-size: 5rem;"></i>
                    </div>
                    <h4><?= htmlspecialchars($usuario['nombre_completo']) ?></h4>
                    <p class="text-muted mb-2">@<?= htmlspecialchars($usuario['username']) ?></p>
                    <p class="mb-3">
                        <i class="bi bi-envelope"></i> <?= htmlspecialchars($usuario['email']) ?>
                    </p>
                    
                    <div class="mb-3">
                        <?php
                        $estadoColor = match($usuario['estado']) {
                            'activo' => 'success',
                            'inactivo' => 'warning',
                            'bloqueado' => 'danger',
                            default => 'secondary'
                        };
                        ?>
                        <span class="badge bg-<?= $estadoColor ?> fs-6">
                            <?= ucfirst($usuario['estado']) ?>
                        </span>
                    </div>
                    
                    <hr>
                    
                    <div class="text-start">
                        <p class="mb-2">
                            <i class="bi bi-calendar-plus"></i> 
                            <strong>Creado:</strong> 
                            <?= date('d/m/Y H:i', strtotime($usuario['fecha_creacion'])) ?>
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-clock-history"></i> 
                            <strong>Último acceso:</strong> 
                            <?= $usuario['ultimo_acceso'] ? date('d/m/Y H:i', strtotime($usuario['ultimo_acceso'])) : 'Nunca' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- Roles -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-shield-check"></i> Roles Asignados</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($usuario['roles'])): ?>
                        <div class="row">
                            <?php foreach ($usuario['roles'] as $rol): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <?= htmlspecialchars($rol['nombre_rol']) ?>
                                                <span class="badge bg-primary">Nivel <?= $rol['nivel_acceso'] ?></span>
                                            </h6>
                                            <p class="card-text small text-muted mb-0">
                                                <?= htmlspecialchars($rol['descripcion']) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No tiene roles asignados</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Permisos -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-key"></i> Permisos</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($usuario['permisos'])): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Módulo</th>
                                        <th>Acción</th>
                                        <th>Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usuario['permisos'] as $permiso): ?>
                                        <tr>
                                            <td><span class="badge bg-info"><?= htmlspecialchars($permiso['modulo']) ?></span></td>
                                            <td><code><?= htmlspecialchars($permiso['accion']) ?></code></td>
                                            <td><small><?= htmlspecialchars($permiso['descripcion']) ?></small></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No tiene permisos asignados</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Actividad Reciente -->
            <?php if (!empty($ultimasAcciones)): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0"><i class="bi bi-activity"></i> Actividad Reciente</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Acción</th>
                                        <th>Módulo</th>
                                        <th>Detalles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ultimasAcciones as $accion): ?>
                                        <tr>
                                            <td><small><?= date('d/m/Y H:i', strtotime($accion['fecha_accion'])) ?></small></td>
                                            <td><span class="badge bg-primary"><?= htmlspecialchars($accion['accion']) ?></span></td>
                                            <td><?= htmlspecialchars($accion['modulo']) ?></td>
                                            <td><small><?= htmlspecialchars($accion['detalles'] ?? '-') ?></small></td>
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
