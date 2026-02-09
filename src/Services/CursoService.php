<?php

/**
 * Servicio de Cursos
 * Contiene la lógica de negocio para gestión de cursos
 */
class CursoService {
    private CursoRepository $cursoRepo;
    
    public function __construct() {
        $this->cursoRepo = new CursoRepository();
    }
    
    /**
     * Obtener todos los cursos
     */
    public function getAll(): array {
        return $this->cursoRepo->findAll();
    }
    
    /**
     * Obtener todos los cursos con conteo de estudiantes
     */
    public function getAllWithStudentCount(): array {
        return $this->cursoRepo->findAllWithStudentCount();
    }
    
    /**
     * Obtener curso por ID
     */
    public function getById(int $id): ?array {
        return $this->cursoRepo->findById($id);
    }
    
    /**
     * Obtener cursos por jornada
     */
    public function getByJornada(string $jornada): array {
        return $this->cursoRepo->findByJornada($jornada);
    }
    
    /**
     * Verificar capacidad disponible
     */
    public function hasCapacity(int $id_curso): bool {
        return $this->cursoRepo->hasCapacity($id_curso);
    }
    
    /**
     * Crear nuevo curso
     */
    public function create(array $datos): array {
        // Validar datos
        if (empty($datos['nombre_curso'])) {
            return ['success' => false, 'message' => 'El nombre del curso es obligatorio'];
        }
        
        if (!isset($datos['grado']) || $datos['grado'] < 0 || $datos['grado'] > 5) {
            return ['success' => false, 'message' => 'El grado debe estar entre 0 (Preescolar) y 5 (Quinto)'];
        }
        
        $jornadasValidas = ['mañana', 'tarde'];
        if (!in_array($datos['jornada'], $jornadasValidas)) {
            return ['success' => false, 'message' => 'La jornada debe ser mañana o tarde'];
        }
        
        // Verificar si ya existe un curso con el mismo nombre
        $cursoExistente = $this->cursoRepo->findByNombre($datos['nombre_curso']);
        if ($cursoExistente) {
            return ['success' => false, 'message' => 'Ya existe un curso con ese nombre'];
        }
        
        try {
            $id = $this->cursoRepo->create($datos);
            return ['success' => true, 'message' => 'Curso creado exitosamente', 'id' => $id];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al crear el curso: ' . $e->getMessage()];
        }
    }
    
    /**
     * Actualizar curso
     */
    public function update(int $id, array $datos): array {
        // Validar datos
        if (empty($datos['nombre_curso'])) {
            return ['success' => false, 'message' => 'El nombre del curso es obligatorio'];
        }
        
        if (!isset($datos['grado']) || $datos['grado'] < 0 || $datos['grado'] > 5) {
            return ['success' => false, 'message' => 'El grado debe estar entre 0 (Preescolar) y 5 (Quinto)'];
        }
        
        $jornadasValidas = ['mañana', 'tarde'];
        if (!in_array($datos['jornada'], $jornadasValidas)) {
            return ['success' => false, 'message' => 'La jornada debe ser mañana o tarde'];
        }
        
        try {
            $this->cursoRepo->update($id, $datos);
            return ['success' => true, 'message' => 'Curso actualizado exitosamente'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al actualizar el curso: ' . $e->getMessage()];
        }
    }
    
    /**
     * Eliminar curso
     */
    public function delete(int $id): array {
        try {
            $this->cursoRepo->delete($id);
            return ['success' => true, 'message' => 'Curso eliminado exitosamente'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al eliminar el curso: ' . $e->getMessage()];
        }
    }
}

