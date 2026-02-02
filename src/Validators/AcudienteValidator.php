<?php

/**
 * Validador de Acudientes
 * Valida datos de acudientes (padres/tutores)
 */
class AcudienteValidator implements ValidatorInterface {
    private array $errors = [];
    
    public function validate(array $data): array {
        $this->errors = [];
        
        // Validar nombre
        if (empty($data['nombre'])) {
            $this->errors['nombre'] = 'El nombre del acudiente es obligatorio';
        } elseif (strlen($data['nombre']) > 100) {
            $this->errors['nombre'] = 'El nombre no puede exceder 100 caracteres';
        } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $data['nombre'])) {
            $this->errors['nombre'] = 'El nombre solo puede contener letras y espacios';
        }
        
        // Validar teléfono
        if (empty($data['telefono'])) {
            $this->errors['telefono'] = 'El teléfono es obligatorio';
        } elseif (!preg_match('/^[0-9\s\-\+\(\)]+$/', $data['telefono'])) {
            $this->errors['telefono'] = 'Formato de teléfono inválido';
        } elseif (strlen($data['telefono']) > 20) {
            $this->errors['telefono'] = 'El teléfono no puede exceder 20 caracteres';
        }
        
        // Validar parentesco
        if (empty($data['parentesco'])) {
            $this->errors['parentesco'] = 'El parentesco es obligatorio';
        } elseif (strlen($data['parentesco']) > 50) {
            $this->errors['parentesco'] = 'El parentesco no puede exceder 50 caracteres';
        }
        
        return $this->errors;
    }
    
    public function getErrors(): array {
        return $this->errors;
    }
}

