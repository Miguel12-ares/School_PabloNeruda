<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-pencil"></i> Editar Materia</h2>
            <p class="text-muted">Modifica la información de la materia</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="/index.php?controller=materia&action=index" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="/index.php?controller=materia&action=update">
                <input type="hidden" name="id_materia" value="<?= $materia['id_materia'] ?>">
                
                <div class="mb-3">
                    <label for="nombre_materia" class="form-label">
                        Nombre de la Materia <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="nombre_materia" 
                           name="nombre_materia" 
                           value="<?= htmlspecialchars($_SESSION['old']['nombre_materia'] ?? $materia['nombre_materia']) ?>"
                           required>
                </div>
                
                <div class="mb-3">
                    <label for="estado" class="form-label">
                        Estado <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="estado" name="estado" required>
                        <option value="1" <?= ($materia['estado'] ?? 1) == 1 ? 'selected' : '' ?>>
                            Activa
                        </option>
                        <option value="0" <?= ($materia['estado'] ?? 1) == 0 ? 'selected' : '' ?>>
                            Inactiva
                        </option>
                    </select>
                </div>
                
                <hr class="my-4">
                
                <div class="d-flex justify-content-between">
                    <a href="/index.php?controller=materia&action=index" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Actualizar Materia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
