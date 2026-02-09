<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-2"><i class="fas fa-users-cog"></i> Gestión de Usuarios</h2>
            <p class="text-muted">Administra usuarios del sistema y sus permisos</p>
        </div>
    </div>
    
    <div class="row mb-3">
        <div class="col-12 text-end">
            <?php if ($permissionMiddleware->checkPermission('usuarios', 'crear')): ?>
                <a href="/index.php?controller=usuario&action=create" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Nuevo Usuario
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (empty($usuarios)): ?>
                <div class="alert alert-info border-0">
                    <i class="fas fa-info-circle"></i> No hay usuarios registrados
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Nombre Completo</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Estado</th>
                                <th>Último Acceso</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?= $usuario['id_usuario'] ?></td>
                                    <td>
                                        <strong><i class="fas fa-user text-primary"></i> <?= htmlspecialchars($usuario['username']) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($usuario['nombre_completo']) ?></td>
                                    <td>
                                        <small><i class="fas fa-envelope text-muted"></i> <?= htmlspecialchars($usuario['email']) ?></small>
                                    </td>
                                    <td>
                                        <?php if (!empty($usuario['roles'])): ?>
                                            <?php 
                                            $roles = explode(', ', $usuario['roles']);
                                            foreach ($roles as $rol):
                                                $color = match($rol) {
                                                    'Administrativo' => 'primary',
                                                    'Directivo' => 'info',
                                                    'Maestro' => 'secondary',
                                                    default => 'secondary'
                                                };
                                            ?>
                                                <span class="badge bg-<?= $color ?> me-1">
                                                    <?= htmlspecialchars($rol) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Sin rol</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $estadoColor = match($usuario['estado']) {
                                            'activo' => 'success',
                                            'inactivo' => 'secondary',
                                            'bloqueado' => 'danger',
                                            default => 'secondary'
                                        };
                                        ?>
                                        <span class="badge bg-<?= $estadoColor ?>">
                                            <?= ucfirst($usuario['estado']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($usuario['ultimo_acceso']): ?>
                                            <small>
                                                <i class="fas fa-clock text-muted"></i>
                                                <?= date('d/m/Y H:i', strtotime($usuario['ultimo_acceso'])) ?>
                                            </small>
                                        <?php else: ?>
                                            <small class="text-muted">Nunca</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="/index.php?controller=usuario&action=view&id=<?= $usuario['id_usuario'] ?>" 
                                               class="btn btn-outline-info" title="Ver detalle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($permissionMiddleware->checkPermission('usuarios', 'editar')): ?>
                                                <a href="/index.php?controller=usuario&action=edit&id=<?= $usuario['id_usuario'] ?>" 
                                                   class="btn btn-outline-primary" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($permissionMiddleware->checkPermission('usuarios', 'eliminar') && 
                                                      $usuario['id_usuario'] != $authService->getCurrentUserId()): ?>
                                                <button type="button" 
                                                        class="btn btn-outline-danger" 
                                                        onclick="confirmarEliminacion(<?= $usuario['id_usuario'] ?>, '<?= htmlspecialchars($usuario['username']) ?>')"
                                                        title="Eliminar">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            <?php endif; ?>
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

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar al usuario <strong id="usuarioNombre"></strong>?</p>
                <p class="text-danger mb-0">
                    <i class="fas fa-exclamation-circle"></i> 
                    Esta acción no se puede deshacer.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="/index.php?controller=usuario&action=delete" id="formEliminar">
                    <input type="hidden" name="id_usuario" id="usuarioId">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Eliminar Usuario
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEliminacion(id, nombre) {
    document.getElementById('usuarioId').value = id;
    document.getElementById('usuarioNombre').textContent = nombre;
    new bootstrap.Modal(document.getElementById('modalEliminar')).show();
}
</script>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
