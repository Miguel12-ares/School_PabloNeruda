<?php 
$title = 'Listado de Estudiantes';
$controller = 'estudiante';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people"></i> Estudiantes</h2>
    <a href="index.php?controller=estudiante&action=create" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Estudiante
    </a>
</div>

<!-- Formulario de búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form action="index.php" method="GET" class="row g-3">
            <input type="hidden" name="controller" value="estudiante">
            <input type="hidden" name="action" value="search">
            <div class="col-md-10">
                <input type="text" name="q" class="form-control" 
                       placeholder="Buscar por documento, nombre o apellido..." 
                       value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de estudiantes -->
<div class="card">
    <div class="card-body">
        <?php if (empty($estudiantes)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No se encontraron estudiantes.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Registro Civil</th>
                            <th>Nombre Completo</th>
                            <th>Edad</th>
                            <th>Curso</th>
                            <th>Jornada</th>
                            <th>Alergias</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudiantes as $estudiante): ?>
                            <tr>
                                <td><?= htmlspecialchars($estudiante['registro_civil']) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($estudiante['edad']) ?> años</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?= htmlspecialchars($estudiante['nombre_curso']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $estudiante['jornada'] === 'mañana' ? 'warning' : 'info' ?>">
                                        <?= ucfirst(htmlspecialchars($estudiante['jornada'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($estudiante['tiene_alergias']): ?>
                                        <span class="badge bg-danger">
                                            <i class="bi bi-exclamation-triangle"></i> Sí
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success">No</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="index.php?controller=estudiante&action=view&id=<?= $estudiante['id_estudiante'] ?>" 
                                           class="btn btn-info" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="index.php?controller=estudiante&action=edit&id=<?= $estudiante['id_estudiante'] ?>" 
                                           class="btn btn-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="index.php?controller=estudiante&action=delete&id=<?= $estudiante['id_estudiante'] ?>" 
                                           class="btn btn-danger" 
                                           onclick="return confirm('¿Está seguro de eliminar este estudiante?')"
                                           title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                <p class="text-muted">
                    <i class="bi bi-info-circle"></i> Total de estudiantes: <strong><?= count($estudiantes) ?></strong>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>

