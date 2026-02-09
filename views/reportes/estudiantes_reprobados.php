<?php 
$title = 'Estudiantes Reprobados';
$controller = 'reporte';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="container-fluid py-4">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-2"><i class="fas fa-exclamation-triangle"></i> Reporte de Estudiantes Reprobados</h2>
            <p class="text-muted">Seguimiento académico de estudiantes con materias perdidas por periodo</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-11 mx-auto">
            <!-- Filtros -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros de Búsqueda</h5>
                </div>
                <div class="card-body">
                    <form action="index.php" method="GET" class="row g-3">
                        <input type="hidden" name="controller" value="reporte">
                        <input type="hidden" name="action" value="estudiantes_reprobados">
                        
                        <div class="col-md-4">
                            <label for="id_periodo" class="form-label">
                                <i class="fas fa-calendar-alt text-primary"></i> Periodo Académico <span class="text-danger">*</span>
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
                        
                        <div class="col-md-3">
                            <label for="id_curso" class="form-label">
                                <i class="fas fa-book text-primary"></i> Curso (Opcional)
                            </label>
                            <select class="form-select" id="id_curso" name="id_curso">
                                <option value="">Todos los cursos</option>
                                <?php foreach ($cursos as $curso): ?>
                                    <option value="<?= $curso['id_curso'] ?>" 
                                            <?= (isset($id_curso) && $id_curso == $curso['id_curso']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($curso['nombre_curso']) ?> - <?= ucfirst($curso['jornada']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="id_estudiante" class="form-label">
                                <i class="fas fa-user text-primary"></i> Estudiante (Opcional)
                            </label>
                            <select class="form-select" id="id_estudiante" name="id_estudiante">
                                <option value="">Todos los estudiantes</option>
                                <!-- Se llenará dinámicamente si es necesario -->
                            </select>
                        </div>
                        
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Consultar
                            </button>
                        </div>
                    </form>
                    
                    <?php if (isset($id_periodo) && $id_periodo > 0): ?>
                        <div class="mt-3">
                            <a href="index.php?controller=reporte&action=estudiantes_reprobados" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-times"></i> Limpiar Filtros
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($periodo_seleccionado): ?>
                <!-- Información del Periodo -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-calendar-check display-6 text-primary"></i>
                                    <h6 class="mt-2 mb-0">Periodo Consultado</h6>
                                    <p class="mb-0 fw-bold">
                                        Periodo <?= $periodo_seleccionado['numero_periodo'] ?> - <?= $periodo_seleccionado['anio_lectivo'] ?>
                                    </p>
                                </div>
                            </div>
                            <?php if (isset($curso_seleccionado) && $curso_seleccionado): ?>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded">
                                        <i class="fas fa-book display-6 text-info"></i>
                                        <h6 class="mt-2 mb-0">Curso</h6>
                                        <p class="mb-0 fw-bold"><?= htmlspecialchars($curso_seleccionado['nombre_curso']) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-users display-6 text-danger"></i>
                                    <h6 class="mt-2 mb-0">Estudiantes Afectados</h6>
                                    <p class="mb-0 fw-bold fs-3 text-danger">
                                        <?php 
                                        // Contar estudiantes únicos
                                        $estudiantes_unicos = [];
                                        foreach ($estudiantes as $est) {
                                            $estudiantes_unicos[$est['id_estudiante']] = true;
                                        }
                                        echo count($estudiantes_unicos);
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if (empty($estudiantes)): ?>
                    <div class="alert alert-success border-0 shadow-sm">
                        <i class="fas fa-check-circle"></i> 
                        ¡Excelente! No hay estudiantes reprobados en este periodo
                        <?php if (isset($curso_seleccionado) && $curso_seleccionado): ?>
                            para el curso <?= htmlspecialchars($curso_seleccionado['nombre_curso']) ?>
                        <?php endif; ?>.
                    </div>
                <?php else: ?>
                    <!-- Tabla de Estudiantes Reprobados -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-list"></i> Detalle de Materias Reprobadas
                                <span class="badge bg-danger float-end"><?= count($estudiantes) ?> materias</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover border" id="tabla-reprobados">
                                    <thead class="table-primary">
                                        <tr>
                                            <th style="width: 5%;" class="text-center">#</th>
                                            <th style="width: 20%;">
                                                <i class="fas fa-user"></i> Estudiante
                                            </th>
                                            <th style="width: 15%;">
                                                <i class="fas fa-book"></i> Curso
                                            </th>
                                            <th style="width: 15%;">
                                                <i class="fas fa-book-open"></i> Materia Reprobada
                                            </th>
                                            <th style="width: 10%;" class="text-center">
                                                <i class="fas fa-clipboard-list"></i> Notas
                                            </th>
                                            <th style="width: 8%;" class="text-center">
                                                <i class="fas fa-calculator"></i> Promedio
                                            </th>
                                            <th style="width: 10%;" class="text-center">
                                                <i class="fas fa-chart-line"></i> Estado
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($estudiantes as $index => $est): ?>
                                            <tr>
                                                <td class="text-center fw-bold"><?= $index + 1 ?></td>
                                                <td>
                                                    <strong class="text-dark">
                                                        <?= htmlspecialchars($est['nombre'] . ' ' . $est['apellido']) ?>
                                                    </strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-id-card"></i> 
                                                        <?= htmlspecialchars($est['registro_civil']) ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        <?= htmlspecialchars($est['nombre_curso']) ?>
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?= $est['grado'] == 0 ? 'Preescolar' : $est['grado'] . '°' ?> - 
                                                        Sección <?= htmlspecialchars($est['seccion']) ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <strong><?= htmlspecialchars($est['nombre_materia']) ?></strong>
                                                </td>
                                                <td class="text-center">
                                                    <small>
                                                        <?= $est['nota_1'] ?? '-' ?> | 
                                                        <?= $est['nota_2'] ?? '-' ?> | 
                                                        <?= $est['nota_3'] ?? '-' ?> | 
                                                        <?= $est['nota_4'] ?? '-' ?> | 
                                                        <?= $est['nota_5'] ?? '-' ?>
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-danger fs-6">
                                                        <?= number_format($est['promedio'], 2) ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times-circle"></i> Reprobado
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Información Adicional para Impresión -->
                            <div class="mt-4 d-none d-print-block">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="alert alert-warning">
                                            <h6><i class="fas fa-info-circle"></i> Observaciones:</h6>
                                            <p class="mb-0 small">
                                                Este reporte muestra las materias reprobadas (promedio < 3.0) 
                                                del periodo académico seleccionado. Se recomienda seguimiento 
                                                personalizado y apoyo académico adicional.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-info">
                                            <h6><i class="fas fa-graduation-cap"></i> Escala de Valoración:</h6>
                                            <ul class="mb-0 small">
                                                <li>4.6 - 5.0: Superior</li>
                                                <li>4.0 - 4.5: Alto</li>
                                                <li>3.0 - 3.9: Básico</li>
                                                <li><strong>0.0 - 2.9: Bajo (Reprobado)</strong></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <p class="small text-muted mb-0 mt-3">
                                    <strong>Fecha de generación:</strong> <?= date('d/m/Y H:i:s') ?><br>
                                    <strong>Escuela Pablo Neruda</strong> - Barrio Las Malvinas, Sector 4 Berlín<br>
                                    <strong>Periodo:</strong> <?= $periodo_seleccionado['numero_periodo'] ?> - <?= $periodo_seleccionado['anio_lectivo'] ?>
                                </p>
                            </div>
                            
                            <div class="mt-3 d-print-none">
                                <a href="index.php?controller=reporte&action=estudiantes_reprobados_pdf&id_periodo=<?= $id_periodo ?><?= $id_curso ? '&id_curso=' . $id_curso : '' ?>" 
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
    .d-print-block {
        display: block !important;
    }
    .card {
        border: 2px solid #1e3a5f !important;
        box-shadow: none !important;
    }
    body {
        background: white !important;
    }
    .table {
        border: 2px solid #dee2e6 !important;
        font-size: 0.9rem;
    }
    thead {
        background-color: #1e3a5f !important;
        color: white !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .badge {
        border: 1px solid #dee2e6;
    }
}
</style>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
