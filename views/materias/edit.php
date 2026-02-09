<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-3"><i class="fas fa-edit"></i> Editar Materia</h2>
            <p class="text-muted">Modifique la información de la materia</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <form method="POST" action="/index.php?controller=materia&action=update">
                <input type="hidden" name="id_materia" value="<?= $materia['id_materia'] ?>">
                
                <!-- Información de la Materia -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-book-open"></i> Información de la Materia</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="nombre_materia" class="form-label">
                                    <i class="fas fa-book text-primary"></i> Nombre de la Materia <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nombre_materia" 
                                       name="nombre_materia" 
                                       value="<?= htmlspecialchars($_SESSION['old']['nombre_materia'] ?? $materia['nombre_materia']) ?>"
                                       placeholder="Ej: Matemáticas, Español, Ciencias Naturales"
                                       required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="estado" class="form-label">
                                    <i class="fas fa-toggle-on text-primary"></i> Estado <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="1" <?= ($materia['estado'] ?? 1) == 1 ? 'selected' : '' ?>>
                                        Activa
                                    </option>
                                    <option value="0" <?= ($materia['estado'] ?? 1) == 0 ? 'selected' : '' ?>>
                                        Inactiva
                                    </option>
                                </select>
                                <small class="text-muted">Las materias activas están disponibles para ser asignadas a maestros</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Botones -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="/index.php?controller=materia&action=index" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fas fa-save"></i> Actualizar Materia
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Panel de Información -->
        <div class="col-lg-4 d-none d-lg-block">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Estadísticas</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <small class="text-muted">Estado Actual</small>
                        <h4 class="mb-0">
                            <span class="badge bg-<?= $materia['estado'] ? 'success' : 'secondary' ?> fs-5">
                                <?= $materia['estado'] ? 'Activa' : 'Inactiva' ?>
                            </span>
                        </h4>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm bg-light mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle text-primary"></i> Información
                    </h6>
                    <hr>
                    <ul class="small mb-0">
                        <li class="mb-2">
                            Si desactiva una materia, no estará disponible para nuevas asignaciones.
                        </li>
                        <li class="mb-2">
                            Los registros históricos de calificaciones se mantendrán intactos.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
unset($_SESSION['errors']);
unset($_SESSION['old']);
require_once VIEWS_PATH . '/layout/footer.php'; 
?>
