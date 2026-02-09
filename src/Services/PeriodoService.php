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
    
    /**
     * Verificar si un periodo está activo y se pueden registrar notas
     */
    public function periodoPermiteNotas(int $id_periodo): array {
        $periodo = $this->getById($id_periodo);
        
        if (!$periodo) {
            return [
                'permite' => false,
                'mensaje' => 'El periodo no existe'
            ];
        }
        
        if (!$this->periodoRepo->isPeriodoIniciado($id_periodo)) {
            return [
                'permite' => false,
                'mensaje' => 'El periodo aún no ha iniciado. Fecha de inicio: ' . date('d/m/Y', strtotime($periodo['fecha_inicio']))
            ];
        }
        
        if (!$this->periodoRepo->isPeriodoActivo($id_periodo)) {
            return [
                'permite' => false,
                'mensaje' => 'El periodo ha finalizado. Solo se pueden registrar notas entre ' . 
                            date('d/m/Y', strtotime($periodo['fecha_inicio'])) . ' y ' . 
                            date('d/m/Y', strtotime($periodo['fecha_fin']))
            ];
        }
        
        return [
            'permite' => true,
            'mensaje' => 'El periodo está activo'
        ];
    }
}

