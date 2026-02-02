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
}

