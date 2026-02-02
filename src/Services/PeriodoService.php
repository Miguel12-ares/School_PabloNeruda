<?php

/**
 * Servicio de Periodos
 * Contiene la lógica de negocio para gestión de periodos académicos
 */
class PeriodoService {
    private PeriodoRepository $periodoRepo;
    
    public function __construct() {
        $this->periodoRepo = new PeriodoRepository();
    }
    
    /**
     * Obtener todos los periodos
     */
    public function getAll(): array {
        return $this->periodoRepo->findAll();
    }
    
    /**
     * Obtener periodo por ID
     */
    public function getById(int $id): ?array {
        return $this->periodoRepo->findById($id);
    }
    
    /**
     * Obtener periodos por año
     */
    public function getByAnio(int $anio): array {
        return $this->periodoRepo->findByAnio($anio);
    }
    
    /**
     * Obtener el periodo actual
     */
    public function getCurrent(): ?array {
        return $this->periodoRepo->getCurrentPeriodo();
    }
    
    /**
     * Obtener años lectivos disponibles
     */
    public function getAniosLectivos(): array {
        return $this->periodoRepo->getAniosLectivos();
    }
}

