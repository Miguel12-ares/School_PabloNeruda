<?php

/**
 * Validador de Notas
 * Valida calificaciones y datos relacionados
 */
class NotaValidator implements ValidatorInterface {
    private array $errors = [];
    
    public function validate(array $data): array {
        $this->errors = [];
        
        // Validar estudiante
        if (empty($data['id_estudiante'])) {
            $this->errors['id_estudiante'] = 'Debe seleccionar un estudiante';
        }
        
        // Validar materia
        if (empty($data['id_materia'])) {
            $this->errors['id_materia'] = 'Debe seleccionar una materia';
        }
        
        // Validar periodo
        if (empty($data['id_periodo'])) {
            $this->errors['id_periodo'] = 'Debe seleccionar un periodo';
        }
        
        // Validar notas individuales (todas son opcionales pero deben estar en rango)
        for ($i = 1; $i <= GRADES_PER_PERIOD; $i++) {
            $key = "nota_$i";
            if (isset($data[$key]) && $data[$key] !== '' && $data[$key] !== null) {
                $nota = floatval($data[$key]);
                if ($nota < MIN_GRADE || $nota > MAX_GRADE) {
                    $this->errors[$key] = "La nota $i debe estar entre " . MIN_GRADE . " y " . MAX_GRADE;
                }
            }
        }
        
        return $this->errors;
    }
    
    /**
     * Validar una nota individual
     */
    public function validateSingleGrade($nota): bool {
        if ($nota === null || $nota === '') {
            return true; // Las notas pueden ser nulas
        }
        
        $nota = floatval($nota);
        return $nota >= MIN_GRADE && $nota <= MAX_GRADE;
    }
    
    public function getErrors(): array {
        return $this->errors;
    }
}

