<?php 
$title = 'Registrar Notas';
$controller = 'nota';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-pencil-square"></i> Registrar Notas
                </h4>
                <p class="mb-0 mt-2">
                    <strong>Curso:</strong> <?= htmlspecialchars($curso['nombre_curso']) ?> | 
                    <strong>Periodo:</strong> <?= $periodo['numero_periodo'] ?> - <?= $periodo['anio_lectivo'] ?>
                </p>
            </div>
            <div class="card-body">
                <?php if (empty($estudiantes)): ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> No hay estudiantes registrados en este curso.
                    </div>
                <?php elseif (empty($materias)): ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> No hay materias asignadas para este curso.
                        <?php if (isset($_SESSION['user']['roles']) && in_array('Maestro', array_column($_SESSION['user']['roles'], 'nombre_rol'))): ?>
                            <br><small>Como maestro, solo puedes registrar notas de las materias que impartes en este curso.</small>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Materia</th>
                                    <th width="80">Nota 1</th>
                                    <th width="80">Nota 2</th>
                                    <th width="80">Nota 3</th>
                                    <th width="80">Nota 4</th>
                                    <th width="80">Nota 5</th>
                                    <th width="100">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($estudiantes as $estudiante): ?>
                                    <?php foreach ($materias as $materia): ?>
                                        <tr class="nota-row" 
                                            data-estudiante="<?= $estudiante['id_estudiante'] ?>"
                                            data-materia="<?= $materia['id_materia'] ?>">
                                            <td>
                                                <strong><?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?></strong>
                                            </td>
                                            <td><?= htmlspecialchars($materia['nombre_materia']) ?></td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm nota-input" 
                                                       name="nota_1" min="0" max="5" step="0.1" 
                                                       data-nota="1">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm nota-input" 
                                                       name="nota_2" min="0" max="5" step="0.1"
                                                       data-nota="2">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm nota-input" 
                                                       name="nota_3" min="0" max="5" step="0.1"
                                                       data-nota="3">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm nota-input" 
                                                       name="nota_4" min="0" max="5" step="0.1"
                                                       data-nota="4">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm nota-input" 
                                                       name="nota_5" min="0" max="5" step="0.1"
                                                       data-nota="5">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-success btn-guardar-nota"
                                                        data-estudiante="<?= $estudiante['id_estudiante'] ?>"
                                                        data-materia="<?= $materia['id_materia'] ?>"
                                                        data-periodo="<?= $periodo['id_periodo'] ?>">
                                                    <i class="bi bi-save"></i> Guardar
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                
                <div class="mt-3">
                    <a href="index.php?controller=nota&action=index" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar notas existentes
    document.querySelectorAll('.nota-row').forEach(row => {
        const idEstudiante = row.dataset.estudiante;
        const idMateria = row.dataset.materia;
        const idPeriodo = <?= $periodo['id_periodo'] ?>;
        
        fetch(`index.php?controller=nota&action=getNotas&id_estudiante=${idEstudiante}&id_materia=${idMateria}&id_periodo=${idPeriodo}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    for (let i = 1; i <= 5; i++) {
                        const input = row.querySelector(`input[data-nota="${i}"]`);
                        if (data[`nota_${i}`]) {
                            input.value = data[`nota_${i}`];
                        }
                    }
                }
            });
    });
    
    // Guardar notas
    document.querySelectorAll('.btn-guardar-nota').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('.nota-row');
            const formData = new FormData();
            
            // Obtener datos del botón
            const idEstudiante = this.dataset.estudiante;
            const idMateria = this.dataset.materia;
            const idPeriodo = this.dataset.periodo;
            const idCurso = <?= $curso['id_curso'] ?>;
            
            // Debug: mostrar valores antes de enviar
            console.log('Datos a enviar:', {
                id_estudiante: idEstudiante,
                id_materia: idMateria,
                id_periodo: idPeriodo,
                id_curso: idCurso
            });
            
            formData.append('id_estudiante', idEstudiante);
            formData.append('id_materia', idMateria);
            formData.append('id_periodo', idPeriodo);
            formData.append('id_curso', idCurso);
            
            row.querySelectorAll('.nota-input').forEach(input => {
                if (input.value) {
                    formData.append(input.name, input.value);
                    console.log(`Nota ${input.name}: ${input.value}`);
                }
            });
            
            // Debug: mostrar todo el FormData
            console.log('FormData completo:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            // Deshabilitar botón mientras se guarda
            this.disabled = true;
            const originalHTML = this.innerHTML;
            this.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';
            
            fetch('index.php?controller=nota&action=store', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                // Verificar si la respuesta es JSON válido
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return response.text().then(text => {
                        console.error('Respuesta no JSON:', text);
                        throw new Error('La respuesta del servidor no es JSON. Ver consola para detalles.');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    this.innerHTML = '<i class="bi bi-check"></i> Guardado';
                    this.classList.remove('btn-success');
                    this.classList.add('btn-secondary');
                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                        this.classList.remove('btn-secondary');
                        this.classList.add('btn-success');
                        this.disabled = false;
                    }, 2000);
                } else {
                    alert('Error al guardar: ' + (data.errors ? JSON.stringify(data.errors) : data.message));
                    this.innerHTML = originalHTML;
                    this.disabled = false;
                }
            })
            .catch(error => {
                alert('Error de conexión: ' + error.message);
                console.error('Error completo:', error);
                this.innerHTML = originalHTML;
                this.disabled = false;
            });
        });
    });
});
</script>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>

