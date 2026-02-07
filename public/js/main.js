/**
 * JavaScript principal para Escuela Pablo Neruda
 * Validaciones del lado del cliente y funcionalidades interactivas
 */

// Validación de formularios
document.addEventListener('DOMContentLoaded', function() {
    
    // Auto-cerrar alertas después de 5 segundos
    const alerts = document.querySelectorAll('.alert:not(.alert-danger)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // Validación de formulario de estudiantes
    const formEstudiante = document.getElementById('formEstudiante');
    if (formEstudiante) {
        formEstudiante.addEventListener('submit', function(e) {
            if (!validateEstudianteForm()) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    }
    
    // Validación de notas en tiempo real
    const notaInputs = document.querySelectorAll('.nota-input');
    notaInputs.forEach(input => {
        input.addEventListener('input', function() {
            validateNota(this);
        });
        
        input.addEventListener('blur', function() {
            formatNota(this);
        });
    });
    
    // Confirmación antes de eliminar
    const deleteLinks = document.querySelectorAll('a[href*="delete"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('¿Está seguro de que desea eliminar este registro?')) {
                e.preventDefault();
            }
        });
    });
});

/**
 * Validar formulario de estudiante
 */
function validateEstudianteForm() {
    let isValid = true;
    
    // Validar nombre (solo letras y espacios)
    const nombre = document.getElementById('nombre');
    if (nombre) {
        const nombreRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
        if (!nombreRegex.test(nombre.value)) {
            showError(nombre, 'El nombre solo puede contener letras y espacios');
            isValid = false;
        } else {
            clearError(nombre);
        }
    }
    
    // Validar apellido
    const apellido = document.getElementById('apellido');
    if (apellido) {
        const apellidoRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
        if (!apellidoRegex.test(apellido.value)) {
            showError(apellido, 'El apellido solo puede contener letras y espacios');
            isValid = false;
        } else {
            clearError(apellido);
        }
    }
    
    // Validar edad
    const edad = document.getElementById('edad');
    if (edad) {
        const edadValue = parseInt(edad.value);
        if (edadValue < 3 || edadValue > 18) {
            showError(edad, 'La edad debe estar entre 3 y 18 años');
            isValid = false;
        } else {
            clearError(edad);
        }
    }
    
    // Validar archivo PDF
    const documentoPdf = document.getElementById('documento_pdf');
    if (documentoPdf && documentoPdf.files.length > 0) {
        const file = documentoPdf.files[0];
        
        // Validar tamaño (2MB)
        if (file.size > 2 * 1024 * 1024) {
            showError(documentoPdf, 'El archivo no puede exceder 2MB');
            isValid = false;
        }
        
        // Validar extensión
        const extension = file.name.split('.').pop().toLowerCase();
        if (extension !== 'pdf') {
            showError(documentoPdf, 'Solo se permiten archivos PDF');
            isValid = false;
        }
        
        if (isValid) {
            clearError(documentoPdf);
        }
    }
    
    return isValid;
}

/**
 * Validar una nota individual
 */
function validateNota(input) {
    const value = parseFloat(input.value);
    
    if (input.value === '') {
        input.classList.remove('is-invalid');
        return true;
    }
    
    if (isNaN(value) || value < 0 || value > 5) {
        input.classList.add('is-invalid');
        return false;
    }
    
    input.classList.remove('is-invalid');
    return true;
}

/**
 * Formatear nota a un decimal
 */
function formatNota(input) {
    if (input.value !== '') {
        const value = parseFloat(input.value);
        if (!isNaN(value)) {
            input.value = value.toFixed(1);
        }
    }
}

/**
 * Mostrar error en un campo
 */
function showError(input, message) {
    input.classList.add('is-invalid');
    
    // Buscar o crear div de feedback
    let feedback = input.nextElementSibling;
    if (!feedback || !feedback.classList.contains('invalid-feedback')) {
        feedback = document.createElement('div');
        feedback.classList.add('invalid-feedback');
        input.parentNode.insertBefore(feedback, input.nextSibling);
    }
    
    feedback.textContent = message;
}

/**
 * Limpiar error de un campo
 */
function clearError(input) {
    input.classList.remove('is-invalid');
    
    const feedback = input.nextElementSibling;
    if (feedback && feedback.classList.contains('invalid-feedback')) {
        feedback.remove();
    }
}

/**
 * Formatear número de teléfono
 */
function formatTelefono(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length > 0) {
        if (value.length <= 3) {
            value = value;
        } else if (value.length <= 6) {
            value = value.slice(0, 3) + '-' + value.slice(3);
        } else {
            value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 10);
        }
    }
    
    input.value = value;
}

/**
 * Calcular promedio de notas
 */
function calcularPromedio(notas) {
    const notasValidas = notas.filter(n => n !== null && n !== '' && !isNaN(n));
    
    if (notasValidas.length === 0) {
        return null;
    }
    
    const suma = notasValidas.reduce((acc, nota) => acc + parseFloat(nota), 0);
    return (suma / notasValidas.length).toFixed(1);
}

/**
 * Determinar estado según promedio
 */
function determinarEstado(promedio) {
    if (promedio === null) {
        return null;
    }
    
    return parseFloat(promedio) >= 3.0 ? 'aprobado' : 'reprobado';
}

/**
 * Capitalizar primera letra
 */
function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}

/**
 * Validar solo letras
 */
function soloLetras(e) {
    const key = e.key;
    const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]$/;
    
    if (!regex.test(key) && key !== 'Backspace' && key !== 'Delete' && key !== 'Tab') {
        e.preventDefault();
    }
}

/**
 * Validar solo números
 */
function soloNumeros(e) {
    const key = e.key;
    const regex = /^[0-9]$/;
    
    if (!regex.test(key) && key !== 'Backspace' && key !== 'Delete' && key !== 'Tab' && key !== '.') {
        e.preventDefault();
    }
}

/**
 * Debounce function para búsquedas
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Mostrar/ocultar loading spinner
 */
function toggleLoading(show, element) {
    if (show) {
        element.disabled = true;
        element.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Cargando...';
    } else {
        element.disabled = false;
    }
}

/**
 * Copiar al portapapeles
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Copiado al portapapeles');
    }).catch(err => {
        console.error('Error al copiar:', err);
    });
}

// Agregar listeners para campos específicos
document.addEventListener('DOMContentLoaded', function() {
    // Solo letras en nombre y apellido
    const camposLetras = document.querySelectorAll('#nombre, #apellido');
    camposLetras.forEach(campo => {
        campo.addEventListener('keypress', soloLetras);
    });
    
    // Solo números en edad
    const campoEdad = document.getElementById('edad');
    if (campoEdad) {
        campoEdad.addEventListener('keypress', soloNumeros);
    }
    
    // Formatear teléfono
    const camposTelefono = document.querySelectorAll('input[name="telefono"]');
    camposTelefono.forEach(campo => {
        campo.addEventListener('input', function() {
            formatTelefono(this);
        });
    });
    
    // Inicializar tablas responsive
    initResponsiveTables();
});

/**
 * Inicializar tablas responsive con data-labels
 * Convierte las tablas en tarjetas verticales en móviles
 */
function initResponsiveTables() {
    // Buscar todas las tablas dentro de .table-responsive
    const tables = document.querySelectorAll('.table-responsive table');
    
    tables.forEach(table => {
        // Obtener los encabezados de la tabla
        const headers = [];
        const headerCells = table.querySelectorAll('thead th');
        
        headerCells.forEach(th => {
            headers.push(th.textContent.trim());
        });
        
        // Agregar data-label a cada celda del tbody
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            
            cells.forEach((cell, index) => {
                if (headers[index]) {
                    cell.setAttribute('data-label', headers[index]);
                }
            });
        });
    });
}
