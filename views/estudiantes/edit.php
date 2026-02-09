<?php 
$title = 'Editar Estudiante';
$controller = 'estudiante';
require_once VIEWS_PATH . '/layout/header.php'; 

$old = $_SESSION['old'] ?? $estudiante;
$errors = $_SESSION['errors'] ?? [];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-3"><i class="fas fa-user-edit"></i> Editar Estudiante</h2>
            <p class="text-muted">Actualice la información del estudiante</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <form action="index.php?controller=estudiante&action=update" method="POST" 
                  enctype="multipart/form-data">
                <input type="hidden" name="id_estudiante" value="<?= $estudiante['id_estudiante'] ?>">
                
                <!-- Datos Personales -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-id-card"></i> Datos Personales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="registro_civil" class="form-label">
                                    <i class="fas fa-file-alt text-primary"></i> Registro Civil <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control <?= isset($errors['registro_civil']) ? 'is-invalid' : '' ?>" 
                                       id="registro_civil" name="registro_civil" 
                                       value="<?= htmlspecialchars($old['registro_civil']) ?>" 
                                       placeholder="Ej: 1234567890" required>
                                <?php if (isset($errors['registro_civil'])): ?>
                                    <div class="invalid-feedback"><?= $errors['registro_civil'] ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tarjeta_identidad" class="form-label">
                                    <i class="fas fa-id-badge text-primary"></i> Tarjeta de Identidad
                                </label>
                                <input type="text" class="form-control" 
                                       id="tarjeta_identidad" name="tarjeta_identidad" 
                                       value="<?= htmlspecialchars($old['tarjeta_identidad'] ?? '') ?>"
                                       placeholder="Ej: 1234567890 (Opcional)">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-user text-primary"></i> Nombre <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>" 
                                       id="nombre" name="nombre" 
                                       value="<?= htmlspecialchars($old['nombre']) ?>" 
                                       placeholder="Nombre del estudiante" required>
                                <?php if (isset($errors['nombre'])): ?>
                                    <div class="invalid-feedback"><?= $errors['nombre'] ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="apellido" class="form-label">
                                    <i class="fas fa-user text-primary"></i> Apellido <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control <?= isset($errors['apellido']) ? 'is-invalid' : '' ?>" 
                                       id="apellido" name="apellido" 
                                       value="<?= htmlspecialchars($old['apellido']) ?>" 
                                       placeholder="Apellido del estudiante" required>
                                <?php if (isset($errors['apellido'])): ?>
                                    <div class="invalid-feedback"><?= $errors['apellido'] ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="edad" class="form-label">
                                    <i class="fas fa-birthday-cake text-primary"></i> Edad <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" 
                                       id="edad" name="edad" min="3" max="18"
                                       value="<?= htmlspecialchars($old['edad']) ?>" 
                                       placeholder="Ej: 7" required>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Información Académica -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Información Académica</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_curso" class="form-label">
                                    <i class="fas fa-book text-primary"></i> Curso <span class="text-danger">*</span>
                                </label>
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
                                <label for="jornada" class="form-label">
                                    <i class="fas fa-clock text-primary"></i> Jornada <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="jornada" name="jornada" required>
                                    <option value="mañana" <?= $old['jornada'] === 'mañana' ? 'selected' : '' ?>>Mañana</option>
                                    <option value="tarde" <?= $old['jornada'] === 'tarde' ? 'selected' : '' ?>>Tarde</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="documento_pdf" class="form-label">
                                    <i class="fas fa-file-pdf text-primary"></i> Documento de Identidad (PDF)
                                </label>
                                <?php if ($estudiante['documento_pdf']): ?>
                                    <div class="alert alert-info border-0 mb-2">
                                        <i class="fas fa-file-pdf"></i> Documento actual: 
                                        <a href="uploads/<?= htmlspecialchars($estudiante['documento_pdf']) ?>" target="_blank" class="alert-link">
                                            Ver documento
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" id="documento_pdf" name="documento_pdf" accept=".pdf">
                                <small class="text-muted">Deje vacío si no desea cambiar el documento | Tamaño máximo: 2MB</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Información de Salud -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-heartbeat"></i> Información de Salud</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="tiene_alergias" 
                                       name="tiene_alergias" value="1"
                                       <?= $old['tiene_alergias'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="tiene_alergias">
                                    <i class="fas fa-exclamation-triangle text-danger"></i> El estudiante tiene alergias
                                </label>
                            </div>
                        </div>
                        
                        <div id="alergias-container" style="display: <?= $old['tiene_alergias'] ? 'block' : 'none' ?>;">
                            <label class="form-label fw-bold">
                                <i class="fas fa-list text-primary"></i> Especifique las alergias:
                            </label>
                            <div id="alergias-list">
                                <?php if (!empty($alergias)): ?>
                                    <?php foreach ($alergias as $alergia): ?>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-allergies"></i></span>
                                            <input type="text" class="form-control" name="alergias[]" 
                                                   value="<?= htmlspecialchars($alergia['tipo_alergia']) ?>">
                                            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="fas fa-allergies"></i></span>
                                        <input type="text" class="form-control" name="alergias[]" placeholder="Tipo de alergia">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-alergia">
                                <i class="fas fa-plus"></i> Agregar otra alergia
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Botones -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="index.php?controller=estudiante&action=index" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fas fa-save"></i> Actualizar Estudiante
                            </button>
                        </div>
                    </div>
                </div>
            </form>
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
        <span class="input-group-text"><i class="fas fa-allergies"></i></span>
        <input type="text" class="form-control" name="alergias[]" placeholder="Tipo de alergia">
        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
            <i class="fas fa-trash-alt"></i>
        </button>
    `;
    container.appendChild(newInput);
});
</script>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
