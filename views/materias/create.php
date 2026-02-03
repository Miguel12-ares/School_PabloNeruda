<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-plus-circle"></i> Crear Nueva Materia</h2>
            <p class="text-muted">Registra una nueva materia en el plan de estudios</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="/index.php?controller=materia&action=index" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="/index.php?controller=materia&action=store">
                        
                        <div class="mb-3">
                            <label for="nombre_materia" class="form-label">
                                Nombre de la Materia <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?= isset($_SESSION['errors']['nombre_materia']) ? 'is-invalid' : '' ?>" 
                                   id="nombre_materia" 
                                   name="nombre_materia" 
                                   value="<?= htmlspecialchars($_SESSION['old']['nombre_materia'] ?? '') ?>"
                                   placeholder="Ej: Matemáticas, Español, Ciencias Naturales"
                                   required>
                            <?php if (isset($_SESSION['errors']['nombre_materia'])): ?>
                                <div class="invalid-feedback">
                                    <?= $_SESSION['errors']['nombre_materia'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="estado" class="form-label">
                                Estado <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="1" <?= ($_SESSION['old']['estado'] ?? '1') == '1' ? 'selected' : '' ?>>
                                    Activa
                                </option>
                                <option value="0" <?= ($_SESSION['old']['estado'] ?? '') == '0' ? 'selected' : '' ?>>
                                    Inactiva
                                </option>
                            </select>
                            <small class="text-muted">Las materias inactivas no aparecerán en los formularios</small>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-between">
                            <a href="/index.php?controller=materia&action=index" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Materia
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-lightbulb"></i> Sugerencias</h5>
                    <hr>
                    <p class="small">
                        <strong>Materias comunes:</strong>
                    </p>
                    <ul class="small">
                        <li>Matemáticas</li>
                        <li>Español</li>
                        <li>Ciencias Naturales</li>
                        <li>Ciencias Sociales</li>
                        <li>Inglés</li>
                        <li>Educación Física</li>
                        <li>Artística</li>
                        <li>Ética y Valores</li>
                        <li>Religión</li>
                        <li>Tecnología e Informática</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
