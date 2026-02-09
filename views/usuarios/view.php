<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-2">
                <i class="fas fa-user-circle"></i> 
                <?= htmlspecialchars($usuario['nombre_completo']) ?>
            </h2>
            <p class="text-muted">Información completa y actividad del usuario</p>
        </div>
    </div>
    
    <div class="row mb-3">
        <div class="col-12 text-end">
            <?php if ($permissionMiddleware->checkPermission('usuarios', 'editar')): ?>
                <a href="/index.php?controller=usuario&action=edit&id=<?= $usuario['id_usuario'] ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Editar
                </a>
            <?php endif; ?>
            <a href="/index.php?controller=usuario&action=index" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Columna Izquierda - Información del Usuario -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle text-primary" style="font-size: 5rem;"></i>
                    </div>
                    <h4><?= htmlspecialchars($usuario['nombre_completo']) ?></h4>
                    <p class="text-muted mb-2">
                        <i class="fas fa-user"></i> @<?= htmlspecialchars($usuario['username']) ?>
                    </p>
                    <p class="mb-3">
                        <i class="fas fa-envelope"></i> <?= htmlspecialchars($usuario['email']) ?>
                    </p>
                    
                    <div class="mb-3">
                        <?php
                        $estadoColor = match($usuario['estado']) {
                            'activo' => 'success',
                            'inactivo' => 'secondary',
                            'bloqueado' => 'danger',
                            default => 'secondary'
                        };
                        ?>
                        <span class="badge bg-<?= $estadoColor ?> fs-6">
                            <i class="fas fa-circle"></i> <?= ucfirst($usuario['estado']) ?>
                        </span>
                    </div>
                    
                    <hr>
                    
                    <div class="text-start">
                        <p class="mb-2">
                            <i class="fas fa-calendar-plus text-primary"></i> 
                            <strong>Creado:</strong> 
                            <br><small><?= date('d/m/Y H:i', strtotime($usuario['fecha_creacion'])) ?></small>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-clock text-primary"></i> 
                            <strong>Último acceso:</strong> 
                            <br><small><?= $usuario['ultimo_acceso'] ? date('d/m/Y H:i', strtotime($usuario['ultimo_acceso'])) : 'Nunca' ?></small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Columna Derecha - Roles y Permisos -->
        <div class="col-lg-8">
            <!-- Roles Asignados -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-tag"></i> Roles Asignados</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($usuario['roles'])): ?>
                        <div class="row g-3">
                            <?php foreach ($usuario['roles'] as $rol): ?>
                                <div class="col-md-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fas fa-shield-alt text-primary"></i>
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
                        <p class="text-muted mb-0"><i class="fas fa-info-circle"></i> No tiene roles asignados</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Permisos -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-key"></i> Permisos del Usuario</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($usuario['permisos'])): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="fas fa-folder"></i> Módulo</th>
                                        <th><i class="fas fa-cog"></i> Acción</th>
                                        <th><i class="fas fa-info-circle"></i> Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usuario['permisos'] as $permiso): ?>
                                        <tr>
                                            <td><span class="badge bg-primary"><?= htmlspecialchars($permiso['modulo']) ?></span></td>
                                            <td><code><?= htmlspecialchars($permiso['accion']) ?></code></td>
                                            <td><small><?= htmlspecialchars($permiso['descripcion']) ?></small></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0"><i class="fas fa-info-circle"></i> No tiene permisos asignados</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Actividad Reciente -->
            <?php if (!empty($ultimasAcciones)): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Actividad Reciente</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="fas fa-calendar"></i> Fecha</th>
                                        <th><i class="fas fa-bolt"></i> Acción</th>
                                        <th><i class="fas fa-folder-open"></i> Módulo</th>
                                        <th><i class="fas fa-info"></i> Detalles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ultimasAcciones as $accion): ?>
                                        <tr>
                                            <td><small><?= date('d/m/Y H:i', strtotime($accion['fecha_accion'])) ?></small></td>
                                            <td><span class="badge bg-info"><?= htmlspecialchars($accion['accion']) ?></span></td>
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
