<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-journal-bookmark-fill"></i> Gestión de Materias</h2>
            <p class="text-muted">Administra las materias del plan de estudios</p>
        </div>
        <div class="col-md-4 text-end">
            <?php if ($permissionMiddleware->checkPermission('materias', 'crear')): ?>
                <a href="/index.php?controller=materia&action=create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nueva Materia
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (empty($materias)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No hay materias registradas
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre de la Materia</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($materias as $materia): ?>
                                <tr>
                                    <td><?= $materia['id_materia'] ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($materia['nombre_materia']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $materia['estado'] ? 'success' : 'secondary' ?>">
                                            <?= $materia['estado'] ? 'Activa' : 'Inactiva' ?>
                                        </span>
                                    </td>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="/index.php?controller=materia&action=view&id=<?= $materia['id_materia'] ?>" 
                                               class="btn btn-outline-info" 
                                               title="Ver detalle">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <?php if ($permissionMiddleware->checkPermission('materias', 'editar')): ?>
                                                <a href="/index.php?controller=materia&action=edit&id=<?= $materia['id_materia'] ?>" 
                                                   class="btn btn-outline-primary" 
                                                   title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-outline-<?= $materia['estado'] ? 'warning' : 'success' ?>" 
                                                        title="<?= $materia['estado'] ? 'Desactivar' : 'Activar' ?>"
                                                        onclick="toggleEstado(<?= $materia['id_materia'] ?>, '<?= htmlspecialchars($materia['nombre_materia']) ?>', <?= $materia['estado'] ? 1 : 0 ?>)">
                                                    <i class="bi bi-<?= $materia['estado'] ? 'toggle-off' : 'toggle-on' ?>"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if ($permissionMiddleware->checkPermission('materias', 'eliminar')): ?>
                                                <button type="button" 
                                                        class="btn btn-outline-danger" 
                                                        title="Eliminar"
                                                        onclick="confirmarEliminar(<?= $materia['id_materia'] ?>, '<?= htmlspecialchars($materia['nombre_materia']) ?>')">
                                                    <i class="bi bi-trash"></i>
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

<!-- Modal para cambiar estado -->
<?php if ($permissionMiddleware->checkPermission('materias', 'editar')): ?>
<div class="modal fade" id="modalToggleEstado" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Estado de Materia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="mensajeToggle"></p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="/index.php?controller=materia&action=toggleEstado" id="formToggle">
                    <input type="hidden" name="id_materia" id="idMateriaToggle">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEstado(id, nombre, estadoActual) {
    document.getElementById('idMateriaToggle').value = id;
    const nuevoEstado = estadoActual == 1 ? 'inactiva' : 'activa';
    document.getElementById('mensajeToggle').innerHTML = 
        '¿Desea marcar la materia <strong>' + nombre + '</strong> como <strong>' + nuevoEstado + '</strong>?';
    new bootstrap.Modal(document.getElementById('modalToggleEstado')).show();
}
</script>
<?php endif; ?>

<!-- Modal de confirmación para eliminar -->
<?php if ($permissionMiddleware->checkPermission('materias', 'eliminar')): ?>
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="mensajeEliminar"></p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="/index.php?controller=materia&action=delete" id="formEliminar">
                    <input type="hidden" name="id_materia" id="idMateriaEliminar">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEliminar(id, nombre) {
    document.getElementById('idMateriaEliminar').value = id;
    document.getElementById('mensajeEliminar').innerHTML = 
        '¿Está seguro de que desea eliminar la materia <strong>' + nombre + '</strong>?<br><br>' +
        '<small class="text-muted">Esta acción no se puede deshacer.</small>';
    new bootstrap.Modal(document.getElementById('modalEliminar')).show();
}
</script>
<?php endif; ?>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
