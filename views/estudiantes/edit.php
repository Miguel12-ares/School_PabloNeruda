<?php 
$title = 'Editar Estudiante';
$controller = 'estudiante';
require_once VIEWS_PATH . '/layout/header.php'; 

$old = $_SESSION['old'] ?? $estudiante;
$errors = $_SESSION['errors'] ?? [];
?>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header bg-warning">
                <h4 class="mb-0"><i class="bi bi-pencil"></i> Editar Estudiante</h4>
            </div>
            <div class="card-body">
                <form action="index.php?controller=estudiante&action=update" method="POST" 
                      enctype="multipart/form-data">
                    <input type="hidden" name="id_estudiante" value="<?= $estudiante['id_estudiante'] ?>">
                    
                    <!-- Datos Personales -->
                    <h5 class="border-bottom pb-2 mb-3">Datos Personales</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="registro_civil" class="form-label">Registro Civil <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= isset($errors['registro_civil']) ? 'is-invalid' : '' ?>" 
                                   id="registro_civil" name="registro_civil" 
                                   value="<?= htmlspecialchars($old['registro_civil']) ?>" required>
                            <?php if (isset($errors['registro_civil'])): ?>
                                <div class="invalid-feedback"><?= $errors['registro_civil'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tarjeta_identidad" class="form-label">Tarjeta de Identidad</label>
                            <input type="text" class="form-control" 
                                   id="tarjeta_identidad" name="tarjeta_identidad" 
                                   value="<?= htmlspecialchars($old['tarjeta_identidad'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>" 
                                   id="nombre" name="nombre" 
                                   value="<?= htmlspecialchars($old['nombre']) ?>" required>
                            <?php if (isset($errors['nombre'])): ?>
                                <div class="invalid-feedback"><?= $errors['nombre'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= isset($errors['apellido']) ? 'is-invalid' : '' ?>" 
                                   id="apellido" name="apellido" 
                                   value="<?= htmlspecialchars($old['apellido']) ?>" required>
                            <?php if (isset($errors['apellido'])): ?>
                                <div class="invalid-feedback"><?= $errors['apellido'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="edad" class="form-label">Edad <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" 
                                   id="edad" name="edad" min="3" max="18"
                                   value="<?= htmlspecialchars($old['edad']) ?>" required>
                        </div>
                    </div>
                    
                    <!-- Información Académica -->
                    <h5 class="border-bottom pb-2 mb-3 mt-4">Información Académica</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_curso" class="form-label">Curso <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_curso" name="id_curso" required>
                                <?php foreach ($cursos as $curso): ?>
                                    <option value="<?= $curso['id_curso'] ?>" 
                                            <?= $old['id_curso'] == $curso['id_curso'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($curso['nombre_curso']) ?> - <?= ucfirst($curso['jornada']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jornada" class="form-label">Jornada <span class="text-danger">*</span></label>
                            <select class="form-select" id="jornada" name="jornada" required>
                                <option value="mañana" <?= $old['jornada'] === 'mañana' ? 'selected' : '' ?>>Mañana</option>
                                <option value="tarde" <?= $old['jornada'] === 'tarde' ? 'selected' : '' ?>>Tarde</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Documento PDF -->
                    <div class="mb-3">
                        <label for="documento_pdf" class="form-label">Documento de Identidad (PDF - Máx 2MB)</label>
                        <?php if ($estudiante['documento_pdf']): ?>
                            <div class="alert alert-info">
                                <i class="bi bi-file-pdf"></i> Documento actual: 
                                <a href="uploads/<?= htmlspecialchars($estudiante['documento_pdf']) ?>" target="_blank">
                                    Ver documento
                                </a>
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="documento_pdf" name="documento_pdf" accept=".pdf">
                        <small class="text-muted">Deje vacío si no desea cambiar el documento</small>
                    </div>
                    
                    <!-- Información de Salud -->
                    <h5 class="border-bottom pb-2 mb-3 mt-4">Información de Salud</h5>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="tiene_alergias" 
                                   name="tiene_alergias" value="1"
                                   <?= $old['tiene_alergias'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="tiene_alergias">
                                El estudiante tiene alergias
                            </label>
                        </div>
                    </div>
                    
                    <div id="alergias-container" style="display: <?= $old['tiene_alergias'] ? 'block' : 'none' ?>;">
                        <label class="form-label">Especifique las alergias:</label>
                        <div id="alergias-list">
                            <?php if (!empty($alergias)): ?>
                                <?php foreach ($alergias as $alergia): ?>
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="alergias[]" 
                                               value="<?= htmlspecialchars($alergia['tipo_alergia']) ?>">
                                        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="alergias[]" placeholder="Tipo de alergia">
                                </div>
                            <?php endif; ?>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" id="add-alergia">
                            <i class="bi bi-plus"></i> Agregar otra alergia
                        </button>
                    </div>
                    
                    <!-- Botones -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Estudiante
                        </button>
                        <a href="index.php?controller=estudiante&action=index" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('tiene_alergias').addEventListener('change', function() {
    document.getElementById('alergias-container').style.display = this.checked ? 'block' : 'none';
});

document.getElementById('add-alergia').addEventListener('click', function() {
    const container = document.getElementById('alergias-list');
    const newInput = document.createElement('div');
    newInput.className = 'input-group mb-2';
    newInput.innerHTML = `
        <input type="text" class="form-control" name="alergias[]" placeholder="Tipo de alergia">
        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
            <i class="bi bi-trash"></i>
        </button>
    `;
    container.appendChild(newInput);
});
</script>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>

