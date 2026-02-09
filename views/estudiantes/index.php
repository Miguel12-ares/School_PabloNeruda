<?php 
$title = 'Listado de Estudiantes';
$controller = 'estudiante';
require_once VIEWS_PATH . '/layout/header.php'; 

$cursoSeleccionado = $_GET['curso'] ?? '';
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-2"><i class="fas fa-user-graduate"></i> Gestión de Estudiantes</h2>
            <p class="text-muted">Administra los estudiantes del sistema</p>
        </div>
    </div>
    
    <div class="row mb-3">
        <div class="col-12 text-end">
            <a href="index.php?controller=estudiante&action=create" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Nuevo Estudiante
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form action="index.php" method="GET" class="row g-3">
                <input type="hidden" name="controller" value="estudiante">
                <input type="hidden" name="action" value="index">
                
                <div class="col-md-4">
                    <label for="curso" class="form-label"><i class="fas fa-filter"></i> Filtrar por Curso</label>
                    <select class="form-select" id="curso" name="curso" onchange="this.form.submit()">
                        <option value="">Todos los cursos</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?= $curso['id_curso'] ?>" 
                                    <?= $cursoSeleccionado == $curso['id_curso'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($curso['nombre_curso']) ?> - <?= ucfirst($curso['jornada']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label for="q" class="form-label"><i class="fas fa-search"></i> Buscar Estudiante</label>
                    <input type="text" name="q" id="q" class="form-control" 
                           placeholder="Buscar por documento, nombre o apellido..." 
                           value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de estudiantes -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (empty($estudiantes)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No se encontraron estudiantes.
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
                                        <span class="badge bg-primary">
                                            <?= htmlspecialchars($estudiante['nombre_curso']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $estudiante['jornada'] === 'mañana' ? 'info' : 'secondary' ?>">
                                            <?= ucfirst(htmlspecialchars($estudiante['jornada'])) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($estudiante['tiene_alergias']): ?>
                                            <span class="badge bg-danger">
                                                <i class="fas fa-exclamation-triangle"></i> Sí
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success">No</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="index.php?controller=estudiante&action=view&id=<?= $estudiante['id_estudiante'] ?>" 
                                               class="btn btn-outline-info" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="index.php?controller=estudiante&action=edit&id=<?= $estudiante['id_estudiante'] ?>" 
                                               class="btn btn-outline-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="index.php?controller=estudiante&action=delete&id=<?= $estudiante['id_estudiante'] ?>" 
                                               class="btn btn-outline-danger" 
                                               onclick="return confirm('¿Está seguro de eliminar este estudiante?')"
                                               title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
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
                        <i class="fas fa-info-circle"></i> Total de estudiantes: <strong><?= count($estudiantes) ?></strong>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
