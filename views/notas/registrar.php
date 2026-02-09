<?php 
$title = 'Registrar Notas';
$controller = 'nota';
require_once VIEWS_PATH . '/layout/header.php'; 
?>

<div class="container-fluid py-4">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-2"><i class="fas fa-edit"></i> Registrar Calificaciones</h2>
            <p class="text-muted mb-3">
                <strong>Curso:</strong> <?= htmlspecialchars($curso['nombre_curso']) ?> | 
                <strong>Periodo:</strong> <?= $periodo['numero_periodo'] ?> - <?= $periodo['anio_lectivo'] ?>
            </p>
            <div class="alert alert-info border-0 d-inline-block">
                <i class="fas fa-calendar-alt"></i> 
                Periodo activo desde <strong><?= date('d/m/Y', strtotime($periodo['fecha_inicio'])) ?></strong> 
                hasta <strong><?= date('d/m/Y', strtotime($periodo['fecha_fin'])) ?></strong>
            </div>
        </div>
    </div>
    
    <?php if (empty($estudiantes)): ?>
        <div class="alert alert-warning border-0 shadow-sm">
            <i class="fas fa-exclamation-triangle"></i> No hay estudiantes registrados en este curso.
        </div>
        <a href="index.php?controller=nota&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    <?php elseif (empty($materias)): ?>
        <div class="alert alert-warning border-0 shadow-sm">
            <i class="fas fa-exclamation-triangle"></i> No hay materias asignadas para este curso.
            <?php if (isset($_SESSION['user']['roles']) && in_array('Maestro', array_column($_SESSION['user']['roles'], 'nombre_rol'))): ?>
                <br><small>Como maestro, solo puedes registrar notas de las materias que impartes en este curso.</small>
            <?php endif; ?>
        </div>
        <a href="index.php?controller=nota&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    <?php else: ?>
        
        <!-- Filtro por Materia -->
        <div class="row mb-4">
            <div class="col-md-6 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <label for="filtro-materia" class="form-label">
                            <i class="fas fa-filter text-primary"></i> Filtrar por Materia
                        </label>
                        <select class="form-select" id="filtro-materia">
                            <option value="">Todas las materias</option>
                            <?php foreach ($materias as $materia): ?>
                                <option value="<?= $materia['id_materia'] ?>">
                                    <?= htmlspecialchars($materia['nombre_materia']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tablas de Notas por Materia -->
        <?php foreach ($materias as $materia): ?>
            <div class="materia-section mb-4" data-materia="<?= $materia['id_materia'] ?>">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-book"></i> <?= htmlspecialchars($materia['nombre_materia']) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 30%;">
                                            <i class="fas fa-user text-primary"></i> Estudiante
                                        </th>
                                        <th style="width: 10%;" class="text-center">
                                            <i class="fas fa-clipboard-check text-info"></i> Nota 1
                                        </th>
                                        <th style="width: 10%;" class="text-center">
                                            <i class="fas fa-clipboard-check text-info"></i> Nota 2
                                        </th>
                                        <th style="width: 10%;" class="text-center">
                                            <i class="fas fa-clipboard-check text-info"></i> Nota 3
                                        </th>
                                        <th style="width: 10%;" class="text-center">
                                            <i class="fas fa-clipboard-check text-info"></i> Nota 4
                                        </th>
                                        <th style="width: 10%;" class="text-center">
                                            <i class="fas fa-clipboard-check text-info"></i> Nota 5
                                        </th>
                                        <th style="width: 10%;" class="text-center">
                                            <i class="fas fa-calculator text-success"></i> Promedio
                                        </th>
                                        <th style="width: 10%;" class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($estudiantes as $estudiante): ?>
                                        <tr class="nota-row" 
                                            data-estudiante="<?= $estudiante['id_estudiante'] ?>"
                                            data-materia="<?= $materia['id_materia'] ?>">
                                            <td>
                                                <strong class="text-dark">
                                                    <?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       class="form-control form-control-sm nota-input text-center" 
                                                       name="nota_1" 
                                                       min="0" 
                                                       max="5" 
                                                       step="0.1" 
                                                       data-nota="1"
                                                       placeholder="0.0">
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       class="form-control form-control-sm nota-input text-center" 
                                                       name="nota_2" 
                                                       min="0" 
                                                       max="5" 
                                                       step="0.1"
                                                       data-nota="2"
                                                       placeholder="0.0">
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       class="form-control form-control-sm nota-input text-center" 
                                                       name="nota_3" 
                                                       min="0" 
                                                       max="5" 
                                                       step="0.1"
                                                       data-nota="3"
                                                       placeholder="0.0">
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       class="form-control form-control-sm nota-input text-center" 
                                                       name="nota_4" 
                                                       min="0" 
                                                       max="5" 
                                                       step="0.1"
                                                       data-nota="4"
                                                       placeholder="0.0">
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       class="form-control form-control-sm nota-input text-center" 
                                                       name="nota_5" 
                                                       min="0" 
                                                       max="5" 
                                                       step="0.1"
                                                       data-nota="5"
                                                       placeholder="0.0">
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary promedio-badge">-</span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" 
                                                        class="btn btn-sm btn-success btn-guardar-nota"
                                                        data-estudiante="<?= $estudiante['id_estudiante'] ?>"
                                                        data-materia="<?= $materia['id_materia'] ?>"
                                                        data-periodo="<?= $periodo['id_periodo'] ?>">
                                                    <i class="fas fa-save"></i> Guardar
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <div class="text-center mt-4">
            <a href="index.php?controller=nota&action=index" class="btn btn-secondary btn-lg px-5">
                <i class="fas fa-arrow-left"></i> Volver a Gestión de Notas
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtro por materia
    const filtroMateria = document.getElementById('filtro-materia');
    filtroMateria.addEventListener('change', function() {
        const materiaSeleccionada = this.value;
        const secciones = document.querySelectorAll('.materia-section');
        
        secciones.forEach(seccion => {
            if (materiaSeleccionada === '' || seccion.dataset.materia === materiaSeleccionada) {
                seccion.style.display = 'block';
            } else {
                seccion.style.display = 'none';
            }
        });
    });
    
    // Función para calcular promedio
    function calcularPromedio(row) {
        let suma = 0;
        let contador = 0;
        
        row.querySelectorAll('.nota-input').forEach(input => {
            const valor = parseFloat(input.value);
            if (!isNaN(valor) && valor > 0) {
                suma += valor;
                contador++;
            }
        });
        
        if (contador === 0) {
            return '-';
        }
        
        const promedio = (suma / contador).toFixed(2);
        return promedio;
    }
    
    // Actualizar promedio al cambiar notas
    document.querySelectorAll('.nota-input').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('tr');
            const promedioBadge = row.querySelector('.promedio-badge');
            const promedio = calcularPromedio(row);
            
            if (promedio === '-') {
                promedioBadge.textContent = '-';
                promedioBadge.className = 'badge bg-secondary';
            } else {
                promedioBadge.textContent = promedio;
                
                // Colorear según el promedio
                if (promedio >= 4.0) {
                    promedioBadge.className = 'badge bg-success';
                } else if (promedio >= 3.0) {
                    promedioBadge.className = 'badge bg-warning';
                } else {
                    promedioBadge.className = 'badge bg-danger';
                }
            }
        });
    });
    
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
                    // Actualizar promedio después de cargar notas
                    const promedioBadge = row.querySelector('.promedio-badge');
                    const promedio = calcularPromedio(row);
                    if (promedio !== '-') {
                        promedioBadge.textContent = promedio;
                        if (promedio >= 4.0) {
                            promedioBadge.className = 'badge bg-success';
                        } else if (promedio >= 3.0) {
                            promedioBadge.className = 'badge bg-warning';
                        } else {
                            promedioBadge.className = 'badge bg-danger';
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
            
            const idEstudiante = this.dataset.estudiante;
            const idMateria = this.dataset.materia;
            const idPeriodo = this.dataset.periodo;
            const idCurso = <?= $curso['id_curso'] ?>;
            
            formData.append('id_estudiante', idEstudiante);
            formData.append('id_materia', idMateria);
            formData.append('id_periodo', idPeriodo);
            formData.append('id_curso', idCurso);
            
            row.querySelectorAll('.nota-input').forEach(input => {
                if (input.value) {
                    formData.append(input.name, input.value);
                }
            });
            
            // Deshabilitar botón mientras se guarda
            this.disabled = true;
            const originalHTML = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
            
            fetch('index.php?controller=nota&action=store', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return response.text().then(text => {
                        console.error('Respuesta no JSON:', text);
                        throw new Error('La respuesta del servidor no es JSON válida');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    this.innerHTML = '<i class="fas fa-check"></i> Guardado';
                    this.classList.remove('btn-success');
                    this.classList.add('btn-secondary');
                    
                    // Mostrar notificación de éxito
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                    alertDiv.style.zIndex = '9999';
                    alertDiv.innerHTML = `
                        <i class="fas fa-check-circle"></i> Notas guardadas correctamente
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(alertDiv);
                    setTimeout(() => alertDiv.remove(), 3000);
                    
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
