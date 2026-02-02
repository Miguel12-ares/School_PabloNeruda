<?php

/**
 * Validador de Estudiantes
 * Valida todos los datos relacionados con estudiantes
 */
class EstudianteValidator implements ValidatorInterface {
    private array $errors = [];
    
    public function validate(array $data): array {
        $this->errors = [];
        
        // Validar registro civil (requerido)
        if (empty($data['registro_civil'])) {
            $this->errors['registro_civil'] = 'El registro civil es obligatorio';
        } elseif (strlen($data['registro_civil']) > 50) {
            $this->errors['registro_civil'] = 'El registro civil no puede exceder 50 caracteres';
        }
        
        // Validar tarjeta de identidad (opcional pero con límite)
        if (!empty($data['tarjeta_identidad']) && strlen($data['tarjeta_identidad']) > 50) {
            $this->errors['tarjeta_identidad'] = 'La tarjeta de identidad no puede exceder 50 caracteres';
        }
        
        // Validar nombre
        if (empty($data['nombre'])) {
            $this->errors['nombre'] = 'El nombre es obligatorio';
        } elseif (strlen($data['nombre']) > 100) {
            $this->errors['nombre'] = 'El nombre no puede exceder 100 caracteres';
        } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $data['nombre'])) {
            $this->errors['nombre'] = 'El nombre solo puede contener letras y espacios';
        }
        
        // Validar apellido
        if (empty($data['apellido'])) {
            $this->errors['apellido'] = 'El apellido es obligatorio';
        } elseif (strlen($data['apellido']) > 100) {
            $this->errors['apellido'] = 'El apellido no puede exceder 100 caracteres';
        } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $data['apellido'])) {
            $this->errors['apellido'] = 'El apellido solo puede contener letras y espacios';
        }
        
        // Validar edad
        if (empty($data['edad'])) {
            $this->errors['edad'] = 'La edad es obligatoria';
        } elseif (!is_numeric($data['edad']) || $data['edad'] < 3 || $data['edad'] > 18) {
            $this->errors['edad'] = 'La edad debe estar entre 3 y 18 años';
        }
        
        // Validar curso
        if (empty($data['id_curso'])) {
            $this->errors['id_curso'] = 'Debe seleccionar un curso';
        }
        
        // Validar jornada
        if (empty($data['jornada'])) {
            $this->errors['jornada'] = 'Debe seleccionar una jornada';
        } elseif (!in_array($data['jornada'], JORNADAS)) {
            $this->errors['jornada'] = 'Jornada inválida';
        }
        
        return $this->errors;
    }
    
    /**
     * Validar archivo PDF
     */
    public function validateFile(array $file): array {
        $errors = [];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $errors['documento_pdf'] = 'El archivo excede el tamaño máximo permitido (2MB)';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    // No es error si el archivo es opcional
                    break;
                default:
                    $errors['documento_pdf'] = 'Error al subir el archivo';
            }
            return $errors;
        }
        
        // Validar tamaño
        if ($file['size'] > MAX_FILE_SIZE) {
            $errors['documento_pdf'] = 'El archivo no puede exceder 2MB';
            return $errors;
        }
        
        // Validar tipo MIME
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, ALLOWED_MIME_TYPES)) {
            $errors['documento_pdf'] = 'Solo se permiten archivos PDF';
            return $errors;
        }
        
        // Validar extensión
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, ALLOWED_EXTENSIONS)) {
            $errors['documento_pdf'] = 'Solo se permiten archivos con extensión .pdf';
        }
        
        return $errors;
    }
    
    public function getErrors(): array {
        return $this->errors;
    }
}

