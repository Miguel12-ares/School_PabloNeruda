<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-person-circle"></i> Mi Perfil</h2>
            <p class="text-muted">Información de tu cuenta y configuración</p>
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
                        <span class="badge bg-primary fs-6">
                            <i class="bi bi-shield-check"></i> <?= htmlspecialchars($primaryRole['nombre_rol']) ?>
                        </span>
                    </div>
                    
                    <?php if (!empty($usuario['roles'])): ?>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-2">Todos mis roles:</small>
                            <?php foreach ($usuario['roles'] as $rol): ?>
                                <span class="badge bg-secondary me-1">
                                    <?= htmlspecialchars($rol['nombre_rol']) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Cambiar Contraseña -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-key"></i> Cambiar Contraseña</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/cambiar-password.php">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Contraseña Actual *</label>
                            <input type="password" class="form-control" id="current_password" 
                                   name="current_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nueva Contraseña *</label>
                            <input type="password" class="form-control" id="new_password" 
                                   name="new_password" required minlength="8">
                            <small class="text-muted">Mínimo 8 caracteres</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña *</label>
                            <input type="password" class="form-control" id="confirm_password" 
                                   name="confirm_password" required minlength="8">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Actualizar Contraseña
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Permisos del Usuario -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-shield-lock"></i> Mis Permisos</h5>
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
                        <p class="text-muted mb-0">No tienes permisos asignados</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
