<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-2"><i class="fas fa-edit"></i> Editar Curso</h2>
            <p class="text-muted">Modifica la información del curso</p>
        </div>
    </div>
    
    <div class="row mb-3">
        <div class="col-12 text-end">
            <a href="/index.php?controller=curso&action=index" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="/index.php?controller=curso&action=update">
                        <input type="hidden" name="id_curso" value="<?= $curso['id_curso'] ?>">
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="nombre_curso" class="form-label">
                                    Nombre del Curso <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control <?= isset($_SESSION['errors']['nombre_curso']) ? 'is-invalid' : '' ?>" 
                                       id="nombre_curso" 
                                       name="nombre_curso" 
                                       value="<?= htmlspecialchars($_SESSION['old']['nombre_curso'] ?? $curso['nombre_curso']) ?>"
                                       required>
                                <?php if (isset($_SESSION['errors']['nombre_curso'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $_SESSION['errors']['nombre_curso'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="grado" class="form-label">
                                    Grado <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="grado" name="grado" required>
                                    <option value="">Seleccione un grado</option>
                                    <option value="0" <?= ($curso['grado'] ?? '') == '0' ? 'selected' : '' ?>>
                                        Preescolar
                                    </option>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <option value="<?= $i ?>" <?= ($curso['grado'] ?? '') == $i ? 'selected' : '' ?>>
                                            <?= $i ?>° Grado
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="seccion" class="form-label">
                                    Sección
                                </label>
                                <select class="form-select" id="seccion" name="seccion">
                                    <option value="">Sin sección</option>
                                    <option value="A" <?= ($curso['seccion'] ?? '') === 'A' ? 'selected' : '' ?>>A</option>
                                    <option value="B" <?= ($curso['seccion'] ?? '') === 'B' ? 'selected' : '' ?>>B</option>
                                    <option value="C" <?= ($curso['seccion'] ?? '') === 'C' ? 'selected' : '' ?>>C</option>
                                    <option value="D" <?= ($curso['seccion'] ?? '') === 'D' ? 'selected' : '' ?>>D</option>
                                    <option value="E" <?= ($curso['seccion'] ?? '') === 'E' ? 'selected' : '' ?>>E</option>
                                    <option value="F" <?= ($curso['seccion'] ?? '') === 'F' ? 'selected' : '' ?>>F</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="jornada" class="form-label">
                                    Jornada <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="jornada" name="jornada" required>
                                    <option value="mañana" <?= ($curso['jornada'] ?? '') === 'mañana' ? 'selected' : '' ?>>
                                        Mañana
                                    </option>
                                    <option value="tarde" <?= ($curso['jornada'] ?? '') === 'tarde' ? 'selected' : '' ?>>
                                        Tarde
                                    </option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="capacidad_maxima" class="form-label">
                                    Capacidad Máxima <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control" 
                                       id="capacidad_maxima" 
                                       name="capacidad_maxima" 
                                       value="<?= htmlspecialchars($curso['capacidad_maxima'] ?? '30') ?>"
                                       min="1"
                                       max="100"
                                       required>
                            </div>
                        </div>
                        
                        <?php if (isset($curso['total_estudiantes']) && $curso['total_estudiantes'] > 0): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            Este curso tiene <strong><?= $curso['total_estudiantes'] ?></strong> estudiante(s) asignado(s).
                            Ten cuidado al modificar la capacidad máxima.
                        </div>
                        <?php endif; ?>
                        
                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-between">
                            <a href="/index.php?controller=curso&action=index" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Actualizar Curso
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-bar-chart"></i> Estadísticas</h5>
                    <hr>
                    <div class="mb-3">
                        <small class="text-muted">Estudiantes Actuales</small>
                        <h3 class="mb-0"><?= $curso['total_estudiantes'] ?? 0 ?></h3>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Capacidad</small>
                        <h3 class="mb-0"><?= $curso['capacidad_maxima'] ?></h3>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Disponibilidad</small>
                        <h3 class="mb-0">
                            <?= max(0, $curso['capacidad_maxima'] - ($curso['total_estudiantes'] ?? 0)) ?> cupos
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
