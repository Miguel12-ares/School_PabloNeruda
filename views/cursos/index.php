<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-book-fill"></i> Gestión de Cursos</h2>
            <p class="text-muted">Administra los cursos y grados del sistema</p>
        </div>
        <div class="col-md-4 text-end">
            <?php if ($permissionMiddleware->checkPermission('cursos', 'crear')): ?>
                <a href="/index.php?controller=curso&action=create" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nuevo Curso
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (empty($cursos)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No hay cursos registrados
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre del Curso</th>
                                <th>Grado</th>
                                <th>Sección</th>
                                <th>Jornada</th>
                                <th>Capacidad</th>
                                <th>Estudiantes</th>
                                <th>Ocupación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cursos as $curso): ?>
                                <?php 
                                $porcentaje = $curso['capacidad_maxima'] > 0 
                                    ? ($curso['total_estudiantes'] / $curso['capacidad_maxima']) * 100 
                                    : 0;
                                $colorBarra = $porcentaje >= 90 ? 'danger' : ($porcentaje >= 75 ? 'warning' : 'success');
                                ?>
                                <tr>
                                    <td><?= $curso['id_curso'] ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($curso['nombre_curso']) ?></strong>
                                    </td>
                                    <td>
                                        <?php if ($curso['grado'] == 0): ?>
                                            <span class="badge bg-info">Preescolar</span>
                                        <?php else: ?>
                                            <?= $curso['grado'] ?>°
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= $curso['seccion'] ? htmlspecialchars($curso['seccion']) : '-' ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $curso['jornada'] === 'mañana' ? 'primary' : 'secondary' ?>">
                                            <?= ucfirst($curso['jornada']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?= $curso['capacidad_maxima'] ?>
                                    </td>
                                    <td class="text-center">
                                        <strong><?= $curso['total_estudiantes'] ?></strong>
                                    </td>
                                    <td style="width: 150px;">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-<?= $colorBarra ?>" 
                                                 role="progressbar" 
                                                 style="width: <?= min($porcentaje, 100) ?>%"
                                                 aria-valuenow="<?= $porcentaje ?>" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                <?= number_format($porcentaje, 0) ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="/index.php?controller=curso&action=view&id=<?= $curso['id_curso'] ?>" 
                                               class="btn btn-outline-info" 
                                               title="Ver detalle">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <?php if ($permissionMiddleware->checkPermission('cursos', 'editar')): ?>
                                                <a href="/index.php?controller=curso&action=edit&id=<?= $curso['id_curso'] ?>" 
                                                   class="btn btn-outline-primary" 
                                                   title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($permissionMiddleware->checkPermission('cursos', 'eliminar')): ?>
                                                <button type="button" 
                                                        class="btn btn-outline-danger" 
                                                        title="Eliminar"
                                                        onclick="confirmarEliminar(<?= $curso['id_curso'] ?>, '<?= htmlspecialchars($curso['nombre_curso']) ?>', <?= $curso['total_estudiantes'] ?>)">
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

<!-- Modal de confirmación para eliminar -->
<?php if ($permissionMiddleware->checkPermission('cursos', 'eliminar')): ?>
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
                <form method="POST" action="/index.php?controller=curso&action=delete" id="formEliminar">
                    <input type="hidden" name="id_curso" id="idCursoEliminar">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEliminar(id, nombre, totalEstudiantes) {
    document.getElementById('idCursoEliminar').value = id;
    
    if (totalEstudiantes > 0) {
        document.getElementById('mensajeEliminar').innerHTML = 
            '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> ' +
            'El curso <strong>' + nombre + '</strong> tiene ' + totalEstudiantes + ' estudiante(s) asignado(s). ' +
            'No se puede eliminar.</div>';
        document.querySelector('#formEliminar button[type="submit"]').disabled = true;
    } else {
        document.getElementById('mensajeEliminar').innerHTML = 
            '¿Está seguro de que desea eliminar el curso <strong>' + nombre + '</strong>?';
        document.querySelector('#formEliminar button[type="submit"]').disabled = false;
    }
    
    new bootstrap.Modal(document.getElementById('modalEliminar')).show();
}
</script>
<?php endif; ?>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
