<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-pencil-square"></i> Editar Usuario</h2>
            <p class="text-muted">Actualiza la información del usuario</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="/index.php?controller=usuario&action=index" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver al Listado
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="/index.php?controller=usuario&action=update">
                        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                        
                        <!-- Información Básica -->
                        <h5 class="mb-3"><i class="bi bi-person-badge"></i> Información Básica</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Usuario *</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= htmlspecialchars($usuario['username']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($usuario['email']) ?>" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="nombre_completo" class="form-label">Nombre Completo *</label>
                                <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" 
                                       value="<?= htmlspecialchars($usuario['nombre_completo']) ?>" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" minlength="8">
                                <small class="text-muted">Dejar en blanco para mantener la actual</small>
                            </div>
                            <div class="col-md-6">
                                <label for="estado" class="form-label">Estado *</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="activo" <?= $usuario['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                                    <option value="inactivo" <?= $usuario['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                    <option value="bloqueado" <?= $usuario['estado'] === 'bloqueado' ? 'selected' : '' ?>>Bloqueado</option>
                                </select>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Roles -->
                        <h5 class="mb-3"><i class="bi bi-shield-check"></i> Asignación de Roles</h5>
                        
                        <div class="mb-3">
                            <?php foreach ($roles as $rol): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" 
                                           value="<?= $rol['id_rol'] ?>" id="rol_<?= $rol['id_rol'] ?>"
                                           <?= in_array($rol['id_rol'], $rolesUsuario) ? 'checked' : '' ?>
                                           onchange="toggleMaestroSection()">
                                    <label class="form-check-label" for="rol_<?= $rol['id_rol'] ?>">
                                        <strong><?= htmlspecialchars($rol['nombre_rol']) ?></strong>
                                        <span class="badge bg-secondary">Nivel <?= $rol['nivel_acceso'] ?></span>
                                        <br>
                                        <small class="text-muted"><?= htmlspecialchars($rol['descripcion']) ?></small>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Asignación de Cursos (solo para Maestros) -->
                        <div id="seccionMaestro" style="display: <?= in_array(1, $rolesUsuario) ? 'block' : 'none' ?>;">
                            <hr class="my-4">
                            <h5 class="mb-3"><i class="bi bi-book"></i> Asignación de Cursos y Materias</h5>
                            
                            <div id="asignacionesContainer">
                                <?php if (!empty($asignaciones)): ?>
                                    <?php foreach ($asignaciones as $index => $asig): ?>
                                        <div class="asignacion-item mb-3 p-3 border rounded">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label class="form-label">Curso</label>
                                                    <select class="form-select" name="asignaciones[<?= $index ?>][curso]">
                                                        <option value="">Seleccionar curso...</option>
                                                        <?php foreach ($cursos as $curso): ?>
                                                            <option value="<?= $curso['id_curso'] ?>" 
                                                                    <?= $curso['id_curso'] == $asig['id_curso'] ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($curso['nombre_curso']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="form-label">Materia</label>
                                                    <select class="form-select" name="asignaciones[<?= $index ?>][materia]">
                                                        <option value="">Seleccionar materia...</option>
                                                        <?php foreach ($materias as $materia): ?>
                                                            <option value="<?= $materia['id_materia'] ?>"
                                                                    <?= $materia['id_materia'] == $asig['id_materia'] ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($materia['nombre_materia']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarAsignacion(this)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="asignacion-item mb-3 p-3 border rounded">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label class="form-label">Curso</label>
                                                <select class="form-select" name="asignaciones[0][curso]">
                                                    <option value="">Seleccionar curso...</option>
                                                    <?php foreach ($cursos as $curso): ?>
                                                        <option value="<?= $curso['id_curso'] ?>">
                                                            <?= htmlspecialchars($curso['nombre_curso']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label">Materia</label>
                                                <select class="form-select" name="asignaciones[0][materia]">
                                                    <option value="">Seleccionar materia...</option>
                                                    <?php foreach ($materias as $materia): ?>
                                                        <option value="<?= $materia['id_materia'] ?>">
                                                            <?= htmlspecialchars($materia['nombre_materia']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarAsignacion(this)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="agregarAsignacion()">
                                <i class="bi bi-plus-circle"></i> Agregar Otra Asignación
                            </button>
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Botones -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Actualizar Usuario
                            </button>
                            <a href="/index.php?controller=usuario&action=index" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-light mb-3">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-info-circle"></i> Información</h6>
                    <ul class="small mb-0">
                        <li>Deja la contraseña en blanco para mantener la actual</li>
                        <li>El username y email deben ser únicos</li>
                        <li>Los cambios se aplicarán inmediatamente</li>
                    </ul>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-clock-history"></i> Información del Usuario</h6>
                    <p class="small mb-2">
                        <strong>Creado:</strong><br>
                        <?= date('d/m/Y H:i', strtotime($usuario['fecha_creacion'])) ?>
                    </p>
                    <p class="small mb-0">
                        <strong>Último acceso:</strong><br>
                        <?= $usuario['ultimo_acceso'] ? date('d/m/Y H:i', strtotime($usuario['ultimo_acceso'])) : 'Nunca' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let contadorAsignaciones = <?= !empty($asignaciones) ? count($asignaciones) : 1 ?>;

function toggleMaestroSection() {
    const rolMaestro = document.getElementById('rol_1');
    const seccion = document.getElementById('seccionMaestro');
    
    if (rolMaestro && rolMaestro.checked) {
        seccion.style.display = 'block';
    } else {
        seccion.style.display = 'none';
    }
}

function agregarAsignacion() {
    const container = document.getElementById('asignacionesContainer');
    const nuevaAsignacion = `
        <div class="asignacion-item mb-3 p-3 border rounded">
            <div class="row">
                <div class="col-md-5">
                    <label class="form-label">Curso</label>
                    <select class="form-select" name="asignaciones[${contadorAsignaciones}][curso]">
                        <option value="">Seleccionar curso...</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?= $curso['id_curso'] ?>">
                                <?= htmlspecialchars($curso['nombre_curso']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Materia</label>
                    <select class="form-select" name="asignaciones[${contadorAsignaciones}][materia]">
                        <option value="">Seleccionar materia...</option>
                        <?php foreach ($materias as $materia): ?>
                            <option value="<?= $materia['id_materia'] ?>">
                                <?= htmlspecialchars($materia['nombre_materia']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarAsignacion(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', nuevaAsignacion);
    contadorAsignaciones++;
}

function eliminarAsignacion(btn) {
    const container = document.getElementById('asignacionesContainer');
    if (container.children.length > 1) {
        btn.closest('.asignacion-item').remove();
    } else {
        alert('Debe haber al menos una asignación');
    }
}
</script>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>
