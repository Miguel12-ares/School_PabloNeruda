<?php require_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-3"><i class="fas fa-user-edit"></i> Editar Usuario</h2>
            <p class="text-muted">Actualice la información del usuario</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <form method="POST" action="/index.php?controller=usuario&action=update">
                <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                
                <!-- Información Básica -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user-circle"></i> Información Básica</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">
                                    <i class="fas fa-user text-primary"></i> Usuario <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= htmlspecialchars($usuario['username']) ?>" 
                                       placeholder="nombre.usuario" required>
                                <small class="text-muted">Sin espacios, solo letras, números, punto y guion bajo</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope text-primary"></i> Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($usuario['email']) ?>" 
                                       placeholder="usuario@ejemplo.com" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="nombre_completo" class="form-label">
                                    <i class="fas fa-id-card text-primary"></i> Nombre Completo <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" 
                                       value="<?= htmlspecialchars($usuario['nombre_completo']) ?>" 
                                       placeholder="Nombre y apellidos completos" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock text-primary"></i> Nueva Contraseña
                                </label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       minlength="8" placeholder="Dejar en blanco para no cambiar">
                                <small class="text-muted">Solo complete si desea cambiar la contraseña (mínimo 8 caracteres)</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">
                                    <i class="fas fa-toggle-on text-primary"></i> Estado <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="activo" <?= $usuario['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                                    <option value="inactivo" <?= $usuario['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Asignación de Roles -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user-tag"></i> Asignación de Roles</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            <i class="fas fa-info-circle"></i> Seleccione uno o más roles para el usuario
                        </p>
                        <?php 
                        $rolesUsuario = array_column($usuario['roles'], 'id_rol');
                        foreach ($roles as $rol): 
                        ?>
                            <div class="card mb-3 border">
                                <div class="card-body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" 
                                               value="<?= $rol['id_rol'] ?>" id="rol_<?= $rol['id_rol'] ?>"
                                               <?= in_array($rol['id_rol'], $rolesUsuario) ? 'checked' : '' ?>
                                               onchange="toggleMaestroSection()">
                                        <label class="form-check-label w-100" for="rol_<?= $rol['id_rol'] ?>">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong class="fs-5"><?= htmlspecialchars($rol['nombre_rol']) ?></strong>
                                                    <span class="badge bg-primary ms-2">Nivel <?= $rol['nivel_acceso'] ?></span>
                                                </div>
                                            </div>
                                            <p class="text-muted mb-0 mt-1"><?= htmlspecialchars($rol['descripcion']) ?></p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Asignación de Cursos y Materias (solo para Maestros) -->
                <?php 
                $esMaestro = in_array(1, $rolesUsuario); // 1 = Rol Maestro
                ?>
                <div id="seccionMaestro" style="display: <?= $esMaestro ? 'block' : 'none' ?>;">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-chalkboard-teacher"></i> Asignación de Cursos y Materias</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">
                                <i class="fas fa-info-circle"></i> Seleccione los cursos y materias que impartirá este maestro
                            </p>
                            
                            <div id="asignacionesContainer">
                                <?php if (!empty($asignaciones)): ?>
                                    <?php foreach ($asignaciones as $index => $asignacion): ?>
                                        <div class="asignacion-item mb-3 p-3 border rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-5 mb-2">
                                                    <label class="form-label">
                                                        <i class="fas fa-book text-primary"></i> Curso
                                                    </label>
                                                    <select class="form-select" name="asignaciones[<?= $index ?>][curso]">
                                                        <option value="">Seleccionar curso...</option>
                                                        <?php foreach ($cursos as $curso): ?>
                                                            <option value="<?= $curso['id_curso'] ?>" 
                                                                    <?= $asignacion['id_curso'] == $curso['id_curso'] ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($curso['nombre_curso']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-5 mb-2">
                                                    <label class="form-label">
                                                        <i class="fas fa-book-open text-primary"></i> Materia
                                                    </label>
                                                    <select class="form-select" name="asignaciones[<?= $index ?>][materia]">
                                                        <option value="">Seleccionar materia...</option>
                                                        <?php foreach ($materias as $materia): ?>
                                                            <option value="<?= $materia['id_materia'] ?>"
                                                                    <?= $asignacion['id_materia'] == $materia['id_materia'] ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($materia['nombre_materia']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end mb-2">
                                                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="eliminarAsignacion(this)">
                                                        <i class="fas fa-trash-alt"></i> Eliminar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="asignacion-item mb-3 p-3 border rounded bg-light">
                                        <div class="row">
                                            <div class="col-md-5 mb-2">
                                                <label class="form-label">
                                                    <i class="fas fa-book text-primary"></i> Curso
                                                </label>
                                                <select class="form-select" name="asignaciones[0][curso]">
                                                    <option value="">Seleccionar curso...</option>
                                                    <?php foreach ($cursos as $curso): ?>
                                                        <option value="<?= $curso['id_curso'] ?>">
                                                            <?= htmlspecialchars($curso['nombre_curso']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-5 mb-2">
                                                <label class="form-label">
                                                    <i class="fas fa-book-open text-primary"></i> Materia
                                                </label>
                                                <select class="form-select" name="asignaciones[0][materia]">
                                                    <option value="">Seleccionar materia...</option>
                                                    <?php foreach ($materias as $materia): ?>
                                                        <option value="<?= $materia['id_materia'] ?>">
                                                            <?= htmlspecialchars($materia['nombre_materia']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end mb-2">
                                                <button type="button" class="btn btn-danger btn-sm w-100" onclick="eliminarAsignacion(this)">
                                                    <i class="fas fa-trash-alt"></i> Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <button type="button" class="btn btn-outline-primary" onclick="agregarAsignacion()">
                                <i class="fas fa-plus-circle"></i> Agregar Otra Asignación
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Botones -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="/index.php?controller=usuario&action=index" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fas fa-save"></i> Actualizar Usuario
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let contadorAsignaciones = <?= !empty($asignaciones) ? count($asignaciones) : 1 ?>;

function toggleMaestroSection() {
    const rolMaestro = document.getElementById('rol_1'); // ID 1 = Maestro
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
        <div class="asignacion-item mb-3 p-3 border rounded bg-light">
            <div class="row">
                <div class="col-md-5 mb-2">
                    <label class="form-label">
                        <i class="fas fa-book text-primary"></i> Curso
                    </label>
                    <select class="form-select" name="asignaciones[${contadorAsignaciones}][curso]">
                        <option value="">Seleccionar curso...</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?= $curso['id_curso'] ?>">
                                <?= htmlspecialchars($curso['nombre_curso']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-5 mb-2">
                    <label class="form-label">
                        <i class="fas fa-book-open text-primary"></i> Materia
                    </label>
                    <select class="form-select" name="asignaciones[${contadorAsignaciones}][materia]">
                        <option value="">Seleccionar materia...</option>
                        <?php foreach ($materias as $materia): ?>
                            <option value="<?= $materia['id_materia'] ?>">
                                <?= htmlspecialchars($materia['nombre_materia']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end mb-2">
                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="eliminarAsignacion(this)">
                        <i class="fas fa-trash-alt"></i> Eliminar
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
