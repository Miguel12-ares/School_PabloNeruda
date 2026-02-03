<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-plus-circle"></i> Crear Nuevo Curso</h2>
            <p class="text-muted">Registra un nuevo curso en el sistema</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="/index.php?controller=curso&action=index" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="/index.php?controller=curso&action=store" id="formCurso">
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="nombre_curso" class="form-label">
                                    Nombre del Curso <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control <?= isset($_SESSION['errors']['nombre_curso']) ? 'is-invalid' : '' ?>" 
                                       id="nombre_curso" 
                                       name="nombre_curso" 
                                       value="<?= htmlspecialchars($_SESSION['old']['nombre_curso'] ?? '') ?>"
                                       placeholder="Ej: Primero A, Segundo B, Preescolar"
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
                                <select class="form-select <?= isset($_SESSION['errors']['grado']) ? 'is-invalid' : '' ?>" 
                                        id="grado" 
                                        name="grado" 
                                        required>
                                    <option value="">Seleccione un grado</option>
                                    <option value="0" <?= ($_SESSION['old']['grado'] ?? '') == '0' ? 'selected' : '' ?>>
                                        Preescolar
                                    </option>
                                    <?php for ($i = 1; $i <= 11; $i++): ?>
                                        <option value="<?= $i ?>" <?= ($_SESSION['old']['grado'] ?? '') == $i ? 'selected' : '' ?>>
                                            <?= $i ?>° Grado
                                        </option>
                                    <?php endfor; ?>
                                </select>
                                <?php if (isset($_SESSION['errors']['grado'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $_SESSION['errors']['grado'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="seccion" class="form-label">
                                    Sección
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="seccion" 
                                       name="seccion" 
                                       value="<?= htmlspecialchars($_SESSION['old']['seccion'] ?? '') ?>"
                                       placeholder="Ej: A, B, C"
                                       maxlength="5">
                                <small class="text-muted">Opcional</small>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="jornada" class="form-label">
                                    Jornada <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="jornada" name="jornada" required>
                                    <option value="mañana" <?= ($_SESSION['old']['jornada'] ?? 'mañana') === 'mañana' ? 'selected' : '' ?>>
                                        Mañana
                                    </option>
                                    <option value="tarde" <?= ($_SESSION['old']['jornada'] ?? '') === 'tarde' ? 'selected' : '' ?>>
                                        Tarde
                                    </option>
                                    <option value="noche" <?= ($_SESSION['old']['jornada'] ?? '') === 'noche' ? 'selected' : '' ?>>
                                        Noche
                                    </option>
                                    <option value="completa" <?= ($_SESSION['old']['jornada'] ?? '') === 'completa' ? 'selected' : '' ?>>
                                        Jornada Completa
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
                                       value="<?= htmlspecialchars($_SESSION['old']['capacidad_maxima'] ?? '30') ?>"
                                       min="1"
                                       max="100"
                                       required>
                                <small class="text-muted">Número máximo de estudiantes</small>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-between">
                            <a href="/index.php?controller=curso&action=index" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Curso
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-info-circle"></i> Información</h5>
                    <hr>
                    <p class="small">
                        <strong>Nombre del Curso:</strong> Debe ser descriptivo y único. 
                        Ejemplo: "Primero A", "Quinto B", "Preescolar Mañana".
                    </p>
                    <p class="small">
                        <strong>Grado:</strong> Selecciona el nivel educativo. 
                        Preescolar es para niños de 3-5 años.
                    </p>
                    <p class="small">
                        <strong>Sección:</strong> Útil cuando hay múltiples grupos del mismo grado. 
                        Por ejemplo: A, B, C.
                    </p>
                    <p class="small">
                        <strong>Capacidad Máxima:</strong> Define cuántos estudiantes pueden matricularse. 
                        El sistema alertará cuando se acerque al límite.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
