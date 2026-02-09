<?php 
$title = 'Estudiantes con Alergias';
$controller = 'reporte';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="container-fluid py-4">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-2"><i class="fas fa-heartbeat"></i> Reporte de Estudiantes con Alergias</h2>
            <p class="text-muted">Listado de estudiantes con alergias registradas en el sistema</p>
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
                        <input type="hidden" name="action" value="estudiantes_alergias">
                        
                        <div class="col-md-5">
                            <label for="id_curso" class="form-label">
                                <i class="fas fa-book text-primary"></i> Filtrar por Curso
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
                        
                        <div class="col-md-5">
                            <label for="id_estudiante" class="form-label">
                                <i class="fas fa-user text-primary"></i> Filtrar por Estudiante
                            </label>
                            <select class="form-select" id="id_estudiante" name="id_estudiante">
                                <option value="">Todos los estudiantes</option>
                                <?php 
                                $estudiantes_filtro = array_filter($todos_estudiantes, function($est) {
                                    return $est['tiene_alergias'] == 1;
                                });
                                foreach ($estudiantes_filtro as $est): 
                                ?>
                                    <option value="<?= $est['id_estudiante'] ?>" 
                                            <?= (isset($id_estudiante) && $id_estudiante == $est['id_estudiante']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($est['nombre'] . ' ' . $est['apellido']) ?>
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
                    
                    <?php if (isset($id_curso) && $id_curso > 0 || isset($id_estudiante) && $id_estudiante > 0): ?>
                        <div class="mt-3">
                            <a href="index.php?controller=reporte&action=estudiantes_alergias" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-times"></i> Limpiar Filtros
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if (empty($estudiantes)): ?>
                <div class="alert alert-success border-0 shadow-sm">
                    <i class="fas fa-check-circle"></i> No hay estudiantes con alergias registradas 
                    <?php if (isset($id_curso) && $id_curso > 0): ?>
                        en el curso seleccionado
                    <?php elseif (isset($id_estudiante) && $id_estudiante > 0): ?>
                        para el estudiante seleccionado
                    <?php else: ?>
                        en el sistema
                    <?php endif; ?>.
                </div>
            <?php else: ?>
                <!-- Tabla de Estudiantes -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list"></i> Listado de Estudiantes con Alergias
                            <span class="badge bg-white text-primary float-end"><?= count($estudiantes) ?> casos</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover border" id="tabla-alergias">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="width: 5%;" class="text-center">#</th>
                                        <th style="width: 20%;">
                                            <i class="fas fa-user"></i> Nombre Completo
                                        </th>
                                        <th style="width: 15%;">
                                            <i class="fas fa-id-card"></i> Registro Civil
                                        </th>
                                        <th style="width: 15%;">
                                            <i class="fas fa-book"></i> Curso
                                        </th>
                                        <th style="width: 10%;" class="text-center">
                                            <i class="fas fa-clock"></i> Jornada
                                        </th>
                                        <th style="width: 35%;">
                                            <i class="fas fa-allergies"></i> Alergias Registradas
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
                                            <td class="text-center fw-bold"><?= $index + 1 ?></td>
                                            <td>
                                                <strong class="text-dark">
                                                    <?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?>
                                                </strong>
                                                <?php if ($acudiente_principal): ?>
                                                    <br>
                                                    <small class="text-muted d-print-block">
                                                        <i class="fas fa-user-friends text-success"></i> 
                                                        <?= htmlspecialchars($acudiente_principal['nombre']) ?>
                                                    </small>
                                                    <br>
                                                    <small class="text-muted d-print-block">
                                                        <i class="fas fa-phone text-success"></i> 
                                                        <?= htmlspecialchars($acudiente_principal['telefono']) ?>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    <?= htmlspecialchars($estudiante['registro_civil']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    <?= htmlspecialchars($estudiante['nombre_curso']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">
                                                    <?= ucfirst($estudiante['jornada']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="alert alert-warning mb-0 py-2">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    <strong><?= htmlspecialchars($estudiante['alergias']) ?></strong>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Información Adicional para Impresión -->
                        <div class="mt-4 d-none d-print-block">
                            <div class="alert alert-danger">
                                <h6><i class="fas fa-info-circle"></i> Protocolo de Emergencia:</h6>
                                <ol class="mb-0 small">
                                    <li>Identificar inmediatamente la alergia del estudiante</li>
                                    <li>Contactar al acudiente registrado</li>
                                    <li>Activar protocolo de emergencia médica si es necesario</li>
                                    <li>Documentar el incidente</li>
                                </ol>
                            </div>
                            <p class="small text-muted mb-0">
                                <strong>Fecha de generación:</strong> <?= date('d/m/Y H:i:s') ?><br>
                                <strong>Escuela Pablo Neruda</strong> - Barrio Las Malvinas, Sector 4 Berlín
                            </p>
                        </div>
                        
                        <div class="mt-3 d-print-none">
                            <a href="index.php?controller=reporte&action=estudiantes_alergias_pdf<?= $id_curso ? '&id_curso=' . $id_curso : '' ?>" 
                               class="btn btn-primary" target="_blank">
                                <i class="fas fa-file-pdf"></i> Descargar PDF
                            </a>
                            <a href="index.php?controller=reporte&action=index" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver a Reportes
                            </a>
                        </div>
                    </div>
                </div>
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
    }
    thead {
        background-color: #1e3a5f !important;
        color: white !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
