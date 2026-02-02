<?php

/**
 * Entidad Nota
 * Representa las calificaciones de un estudiante
 */
class Nota {
    private ?int $id_nota;
    private int $id_estudiante;
    private int $id_materia;
    private int $id_periodo;
    private ?float $nota_1;
    private ?float $nota_2;
    private ?float $nota_3;
    private ?float $nota_4;
    private ?float $nota_5;
    private ?float $promedio;
    private ?string $estado;
    
    public function __construct(array $data = []) {
        $this->id_nota = $data['id_nota'] ?? null;
        $this->id_estudiante = $data['id_estudiante'] ?? 0;
        $this->id_materia = $data['id_materia'] ?? 0;
        $this->id_periodo = $data['id_periodo'] ?? 0;
        $this->nota_1 = $data['nota_1'] ?? null;
        $this->nota_2 = $data['nota_2'] ?? null;
        $this->nota_3 = $data['nota_3'] ?? null;
        $this->nota_4 = $data['nota_4'] ?? null;
        $this->nota_5 = $data['nota_5'] ?? null;
        $this->promedio = $data['promedio'] ?? null;
        $this->estado = $data['estado'] ?? null;
    }
    
    // Getters
    public function getIdNota(): ?int { return $this->id_nota; }
    public function getIdEstudiante(): int { return $this->id_estudiante; }
    public function getIdMateria(): int { return $this->id_materia; }
    public function getIdPeriodo(): int { return $this->id_periodo; }
    public function getNota1(): ?float { return $this->nota_1; }
    public function getNota2(): ?float { return $this->nota_2; }
    public function getNota3(): ?float { return $this->nota_3; }
    public function getNota4(): ?float { return $this->nota_4; }
    public function getNota5(): ?float { return $this->nota_5; }
    public function getPromedio(): ?float { return $this->promedio; }
    public function getEstado(): ?string { return $this->estado; }
    
    // Setters
    public function setIdNota(int $id): void { $this->id_nota = $id; }
    public function setIdEstudiante(int $id): void { $this->id_estudiante = $id; }
    public function setIdMateria(int $id): void { $this->id_materia = $id; }
    public function setIdPeriodo(int $id): void { $this->id_periodo = $id; }
    public function setNota1(?float $nota): void { $this->nota_1 = $nota; }
    public function setNota2(?float $nota): void { $this->nota_2 = $nota; }
    public function setNota3(?float $nota): void { $this->nota_3 = $nota; }
    public function setNota4(?float $nota): void { $this->nota_4 = $nota; }
    public function setNota5(?float $nota): void { $this->nota_5 = $nota; }
    
    public function toArray(): array {
        return [
            'id_nota' => $this->id_nota,
            'id_estudiante' => $this->id_estudiante,
            'id_materia' => $this->id_materia,
            'id_periodo' => $this->id_periodo,
            'nota_1' => $this->nota_1,
            'nota_2' => $this->nota_2,
            'nota_3' => $this->nota_3,
            'nota_4' => $this->nota_4,
            'nota_5' => $this->nota_5,
            'promedio' => $this->promedio,
            'estado' => $this->estado
        ];
    }
}

