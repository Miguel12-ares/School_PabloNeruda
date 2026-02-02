<?php

/**
 * Servicio de Acudientes
 * Contiene la lógica de negocio para gestión de acudientes
 */
class AcudienteService {
    private AcudienteRepository $acudienteRepo;
    private AcudienteValidator $validator;
    
    public function __construct() {
        $this->acudienteRepo = new AcudienteRepository();
        $this->validator = new AcudienteValidator();
    }
    
    /**
     * Crear acudiente y asociarlo a un estudiante
     */
    public function createAndAttach(array $data, int $id_estudiante, bool $es_principal = false): array {
        // Validar datos
        $errors = $this->validator->validate($data);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        try {
            $acudienteData = [
                'nombre' => $data['nombre'],
                'telefono' => $data['telefono'],
                'parentesco' => $data['parentesco']
            ];
            
            $id_acudiente = $this->acudienteRepo->create($acudienteData);
            $this->acudienteRepo->attachToEstudiante($id_acudiente, $id_estudiante, $es_principal);
            
            return ['success' => true, 'id' => $id_acudiente];
            
        } catch (Exception $e) {
            return ['success' => false, 'errors' => ['general' => 'Error al crear acudiente: ' . $e->getMessage()]];
        }
    }
    
    /**
     * Obtener acudientes de un estudiante
     */
    public function getByEstudiante(int $id_estudiante): array {
        return $this->acudienteRepo->findByEstudiante($id_estudiante);
    }
    
    /**
     * Actualizar acudiente
     */
    public function update(int $id, array $data): array {
        $errors = $this->validator->validate($data);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        try {
            $acudienteData = [
                'nombre' => $data['nombre'],
                'telefono' => $data['telefono'],
                'parentesco' => $data['parentesco']
            ];
            
            $this->acudienteRepo->update($id, $acudienteData);
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'errors' => ['general' => 'Error al actualizar: ' . $e->getMessage()]];
        }
    }
    
    /**
     * Eliminar acudiente
     */
    public function delete(int $id): array {
        try {
            $this->acudienteRepo->delete($id);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()];
        }
    }
}

