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
}

