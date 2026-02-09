<?php 
$title = 'Estudiantes por Curso';
$controller = 'reporte';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="container-fluid py-4">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-2"><i class="fas fa-users"></i> Reporte de Estudiantes por Curso</h2>
            <p class="text-muted">Listado completo con información detallada de estudiantes y acudientes</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-11 mx-auto">
            <!-- Selector de Curso -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter"></i> Seleccione un Curso</h5>
                </div>
                <div class="card-body">
                    <form action="index.php" method="GET" class="row g-3">
                        <input type="hidden" name="controller" value="reporte">
                        <input type="hidden" name="action" value="estudiantes_por_curso">
                        
                        <div class="col-md-10">
                            <label for="id_curso" class="form-label">
                                <i class="fas fa-book text-primary"></i> Curso <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="id_curso" name="id_curso" required>
                                <option value="">Seleccione un curso</option>
                                <?php foreach ($cursos as $curso): ?>
                                    <option value="<?= $curso['id_curso'] ?>" 
                                            <?= ($id_curso == $curso['id_curso']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($curso['nombre_curso']) ?> - 
                                        <?= ucfirst($curso['jornada']) ?> 
                                        (<?= $curso['total_estudiantes'] ?> estudiantes)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Consultar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <?php if ($curso_seleccionado): ?>
                <!-- Información del Curso -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-graduation-cap display-6 text-primary"></i>
                                    <h6 class="mt-2 mb-0">Curso</h6>
                                    <p class="mb-0 fw-bold"><?= htmlspecialchars($curso_seleccionado['nombre_curso']) ?></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-clock display-6 text-primary"></i>
                                    <h6 class="mt-2 mb-0">Jornada</h6>
                                    <p class="mb-0 fw-bold"><?= ucfirst($curso_seleccionado['jornada']) ?></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-users display-6 text-success"></i>
                                    <h6 class="mt-2 mb-0">Matriculados</h6>
                                    <p class="mb-0 fw-bold"><?= count($estudiantes) ?></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-chair display-6 text-primary"></i>
                                    <h6 class="mt-2 mb-0">Capacidad</h6>
                                    <p class="mb-0 fw-bold"><?= count($estudiantes) ?> / <?= $curso_seleccionado['capacidad_maxima'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if (empty($estudiantes)): ?>
                    <div class="alert alert-info border-0 shadow-sm">
                        <i class="fas fa-info-circle"></i> No hay estudiantes registrados en este curso.
                    </div>
                <?php else: ?>
                    <!-- Tabla de Estudiantes -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-list"></i> Listado de Estudiantes 
                                <span class="badge bg-white text-primary float-end"><?= count($estudiantes) ?> estudiantes</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="tabla-estudiantes">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 25%;">
                                                <i class="fas fa-user text-primary"></i> Nombre Completo
                                            </th>
                                            <th style="width: 15%;">
                                                <i class="fas fa-id-card text-primary"></i> Registro Civil
                                            </th>
                                            <th style="width: 10%;" class="text-center">
                                                <i class="fas fa-birthday-cake text-primary"></i> Edad
                                            </th>
                                            <th style="width: 15%;" class="text-center">
                                                <i class="fas fa-heartbeat text-danger"></i> Alergias
                                            </th>
                                            <th style="width: 30%;">
                                                <i class="fas fa-phone text-success"></i> Acudiente Principal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $acudienteRepo = new AcudienteRepository();
                                        foreach ($estudiantes as $index => $estudiante): 
                                            $acudientes = $acudienteRepo->findByEstudiante($estudiante['id_estudiante']);
                                            $acudiente_principal = null;
                                            foreach ($acudientes as $ac) {
                                                if ($ac['es_principal']) {
                                                    $acudiente_principal = $ac;
                                                    break;
                                                }
                                            }
                                        ?>
                                            <tr>
                                                <td class="text-center"><?= $index + 1 ?></td>
                                                <td>
                                                    <strong class="text-dark">
                                                        <?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?>
                                                    </strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        <?= htmlspecialchars($estudiante['registro_civil']) ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary"><?= $estudiante['edad'] ?> años</span>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($estudiante['tiene_alergias']): ?>
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-exclamation-triangle"></i> Sí
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check"></i> No
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($acudiente_principal): ?>
                                                        <div>
                                                            <strong><?= htmlspecialchars($acudiente_principal['nombre']) ?></strong>
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-phone-alt text-success"></i> 
                                                                <?= htmlspecialchars($acudiente_principal['telefono']) ?>
                                                            </small>
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-user-friends text-info"></i> 
                                                                <?= htmlspecialchars($acudiente_principal['parentesco']) ?>
                                                            </small>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted small">Sin acudiente registrado</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-3 d-print-none">
                                <a href="index.php?controller=reporte&action=estudiantes_por_curso_pdf&id_curso=<?= $id_curso ?>" 
                                   class="btn btn-danger" target="_blank">
                                    <i class="fas fa-file-pdf"></i> Descargar PDF
                                </a>
                                <a href="index.php?controller=reporte&action=index" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver a Reportes
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
@media print {
    .d-print-none {
        display: none !important;
    }
    .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: none !important;
    }
    body {
        background: white !important;
    }
}
</style>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
