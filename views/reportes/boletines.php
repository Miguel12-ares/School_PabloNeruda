<?php 
$title = 'Boletines de Notas';
$controller = 'reporte';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="container-fluid py-4">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-2"><i class="fas fa-file-alt"></i> Consultar Boletines de Notas</h2>
            <p class="text-muted">Genere boletines académicos con cálculos de promedio y proyecciones</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-11 mx-auto">
            <!-- Selector de Curso y Periodo -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter"></i> Seleccione Curso y Periodo</h5>
                </div>
                <div class="card-body">
                    <form action="index.php" method="GET" class="row g-3">
                        <input type="hidden" name="controller" value="reporte">
                        <input type="hidden" name="action" value="boletines">
                        
                        <div class="col-md-5">
                            <label for="id_curso" class="form-label">
                                <i class="fas fa-book text-primary"></i> Curso <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="id_curso" name="id_curso" required>
                                <option value="">Seleccione un curso</option>
                                <?php foreach ($cursos as $curso): ?>
                                    <option value="<?= $curso['id_curso'] ?>" 
                                            <?= (isset($id_curso) && $id_curso == $curso['id_curso']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($curso['nombre_curso']) ?> - <?= ucfirst($curso['jornada']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-5">
                            <label for="id_periodo" class="form-label">
                                <i class="fas fa-calendar-alt text-primary"></i> Periodo <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="id_periodo" name="id_periodo" required>
                                <option value="">Seleccione un periodo</option>
                                <?php foreach ($periodos as $periodo): ?>
                                    <option value="<?= $periodo['id_periodo'] ?>" 
                                            <?= (isset($id_periodo) && $id_periodo == $periodo['id_periodo']) ? 'selected' : '' ?>>
                                        Periodo <?= $periodo['numero_periodo'] ?> - <?= $periodo['anio_lectivo'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <?php if (isset($id_curso) && isset($id_periodo) && $id_curso && $id_periodo): ?>
                <!-- Información de Selección -->
                <?php if (isset($curso_seleccionado) && isset($periodo_seleccionado)): ?>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded">
                                        <i class="fas fa-book display-6 text-primary"></i>
                                        <h6 class="mt-2 mb-0">Curso</h6>
                                        <p class="mb-0 fw-bold"><?= htmlspecialchars($curso_seleccionado['nombre_curso']) ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded">
                                        <i class="fas fa-calendar-check display-6 text-primary"></i>
                                        <h6 class="mt-2 mb-0">Periodo</h6>
                                        <p class="mb-0 fw-bold">
                                            Periodo <?= $periodo_seleccionado['numero_periodo'] ?> - <?= $periodo_seleccionado['anio_lectivo'] ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded">
                                        <i class="fas fa-users display-6 text-success"></i>
                                        <h6 class="mt-2 mb-0">Total Estudiantes</h6>
                                        <p class="mb-0 fw-bold fs-3 text-success"><?= count($estudiantes) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (empty($estudiantes)): ?>
                    <div class="alert alert-warning border-0 shadow-sm">
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
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 5%;" class="text-center">#</th>
                                            <th style="width: 35%;">
                                                <i class="fas fa-user text-primary"></i> Nombre Completo
                                            </th>
                                            <th style="width: 20%;">
                                                <i class="fas fa-id-card text-primary"></i> Registro Civil
                                            </th>
                                            <th style="width: 15%;" class="text-center">
                                                <i class="fas fa-calculator text-success"></i> Promedio
                                            </th>
                                            <th style="width: 25%;" class="text-center">
                                                <i class="fas fa-file-alt text-primary"></i> Acciones
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $notaService = new NotaService();
                                        foreach ($estudiantes as $index => $estudiante): 
                                            $promedio = $notaService->getPromedioGeneral($estudiante['id_estudiante'], $id_periodo);
                                        ?>
                                            <tr>
                                                <td class="text-center fw-bold"><?= $index + 1 ?></td>
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
                                                    <?php if ($promedio): ?>
                                                        <?php if ($promedio >= 4.6): ?>
                                                            <span class="badge bg-success fs-6">
                                                                <i class="fas fa-star"></i> <?= number_format($promedio, 2) ?>
                                                            </span>
                                                        <?php elseif ($promedio >= 4.0): ?>
                                                            <span class="badge bg-info fs-6">
                                                                <i class="fas fa-thumbs-up"></i> <?= number_format($promedio, 2) ?>
                                                            </span>
                                                        <?php elseif ($promedio >= 3.0): ?>
                                                            <span class="badge bg-warning fs-6">
                                                                <i class="fas fa-check"></i> <?= number_format($promedio, 2) ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger fs-6">
                                                                <i class="fas fa-exclamation-triangle"></i> <?= number_format($promedio, 2) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Sin notas</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="index.php?controller=reporte&action=boletines&id_estudiante=<?= $estudiante['id_estudiante'] ?>&id_periodo=<?= $id_periodo ?>" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="fas fa-file-alt"></i> Ver Boletín Completo
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-3">
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

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
