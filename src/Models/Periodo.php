<?php

/**
 * Entidad Periodo
 * Representa un periodo académico (bimestre)
 */
class Periodo {
    private ?int $id_periodo;
    private int $numero_periodo;
    private int $anio_lectivo;
    private string $fecha_inicio;
    private string $fecha_fin;
    
    public function __construct(array $data = []) {
        $this->id_periodo = $data['id_periodo'] ?? null;
        $this->numero_periodo = $data['numero_periodo'] ?? 1;
        $this->anio_lectivo = $data['anio_lectivo'] ?? date('Y');
        $this->fecha_inicio = $data['fecha_inicio'] ?? '';
        $this->fecha_fin = $data['fecha_fin'] ?? '';
    }
    
    // Getters
    public function getIdPeriodo(): ?int { return $this->id_periodo; }
    public function getNumeroPeriodo(): int { return $this->numero_periodo; }
    public function getAnioLectivo(): int { return $this->anio_lectivo; }
    public function getFechaInicio(): string { return $this->fecha_inicio; }
    public function getFechaFin(): string { return $this->fecha_fin; }
    
    // Setters
    public function setIdPeriodo(int $id): void { $this->id_periodo = $id; }
    public function setNumeroPeriodo(int $numero): void { $this->numero_periodo = $numero; }
    public function setAnioLectivo(int $anio): void { $this->anio_lectivo = $anio; }
    public function setFechaInicio(string $fecha): void { $this->fecha_inicio = $fecha; }
    public function setFechaFin(string $fecha): void { $this->fecha_fin = $fecha; }
    
    public function toArray(): array {
        return [
            'id_periodo' => $this->id_periodo,
            'numero_periodo' => $this->numero_periodo,
            'anio_lectivo' => $this->anio_lectivo,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin
        ];
    }
}

