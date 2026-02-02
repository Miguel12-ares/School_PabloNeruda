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
            
            formData.append('id_estudiante', this.dataset.estudiante);
            formData.append('id_materia', this.dataset.materia);
            formData.append('id_periodo', this.dataset.periodo);
            
            row.querySelectorAll('.nota-input').forEach(input => {
                if (input.value) {
                    formData.append(input.name, input.value);
                }
            });
            
            fetch('index.php?controller=nota&action=store', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.innerHTML = '<i class="bi bi-check"></i> Guardado';
                    this.classList.remove('btn-success');
                    this.classList.add('btn-secondary');
                    setTimeout(() => {
                        this.innerHTML = '<i class="bi bi-save"></i> Guardar';
                        this.classList.remove('btn-secondary');
                        this.classList.add('btn-success');
                    }, 2000);
                } else {
                    alert('Error al guardar: ' + JSON.stringify(data.errors));
                }
            })
            .catch(error => {
                alert('Error de conexión');
                console.error(error);
            });
        });
    });
});
</script>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>

