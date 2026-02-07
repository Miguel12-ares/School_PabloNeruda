<?php

/**
 * Servicio de Materias
 * Contiene la lógica de negocio para gestión de materias
 */
class MateriaService {
    private MateriaRepository $materiaRepo;
    
    public function __construct() {
        $this->materiaRepo = new MateriaRepository();
    }
    
    /**
     * Obtener todas las materias activas
     */
    public function getActive(): array {
        return $this->materiaRepo->findActive();
    }
    
    /**
     * Obtener todas las materias
     */
    public function getAll(): array {
        return $this->materiaRepo->findAll();
    }
    
    /**
     * Obtener materia por ID
     */
    public function getById(int $id): ?array {
        return $this->materiaRepo->findById($id);
    }
    
    /**
     * Obtener materias de un curso
     */
    public function getByCurso(int $id_curso): array {
        return $this->materiaRepo->findByCurso($id_curso);
    }
    
    /**
     * Obtener materias de un curso (alias para mantener compatibilidad)
     */
    public function getMateriasByCurso(int $id_curso): array {
        return $this->getByCurso($id_curso);
    }
    
    /**
     * Crear nueva materia
     */
    public function create(array $datos): array {
        // Validar que el nombre no esté vacío
        if (empty($datos['nombre_materia'])) {
            return [
                'success' => false,
                'message' => 'El nombre de la materia es requerido'
            ];
        }
        
        // Verificar si ya existe una materia con ese nombre
        if ($this->materiaRepo->existsByName($datos['nombre_materia'])) {
            return [
                'success' => false,
                'message' => 'Ya existe una materia con ese nombre'
            ];
        }
        
        $id = $this->materiaRepo->create($datos);
        
        if ($id) {
            return [
                'success' => true,
                'message' => 'Materia creada correctamente',
                'id' => $id
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Error al crear la materia'
        ];
    }
    
    /**
     * Actualizar materia
     */
    public function update(int $id, array $datos): array {
        // Validar que el nombre no esté vacío si se está actualizando
        if (isset($datos['nombre_materia']) && empty($datos['nombre_materia'])) {
            return [
                'success' => false,
                'message' => 'El nombre de la materia es requerido'
            ];
        }
        
        // Verificar si ya existe otra materia con ese nombre
        if (isset($datos['nombre_materia']) && $this->materiaRepo->existsByName($datos['nombre_materia'], $id)) {
            return [
                'success' => false,
                'message' => 'Ya existe otra materia con ese nombre'
            ];
        }
        
        $success = $this->materiaRepo->update($id, $datos);
        
        if ($success) {
            return [
                'success' => true,
                'message' => 'Materia actualizada correctamente'
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Error al actualizar la materia'
        ];
    }
    
    /**
     * Eliminar materia
     */
    public function delete(int $id): array {
        $success = $this->materiaRepo->delete($id);
        
        if ($success) {
            return [
                'success' => true,
                'message' => 'Materia eliminada correctamente'
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Error al eliminar la materia'
        ];
    }
}

