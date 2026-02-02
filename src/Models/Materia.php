<?php

/**
 * Entidad Materia
 * Representa una materia del sistema
 */
class Materia {
    private ?int $id_materia;
    private string $nombre_materia;
    private bool $estado;
    
    public function __construct(array $data = []) {
        $this->id_materia = $data['id_materia'] ?? null;
        $this->nombre_materia = $data['nombre_materia'] ?? '';
        $this->estado = $data['estado'] ?? true;
    }
    
    // Getters
    public function getIdMateria(): ?int { return $this->id_materia; }
    public function getNombreMateria(): string { return $this->nombre_materia; }
    public function getEstado(): bool { return $this->estado; }
    
    // Setters
    public function setIdMateria(int $id): void { $this->id_materia = $id; }
    public function setNombreMateria(string $nombre): void { $this->nombre_materia = $nombre; }
    public function setEstado(bool $estado): void { $this->estado = $estado; }
    
    public function toArray(): array {
        return [
            'id_materia' => $this->id_materia,
            'nombre_materia' => $this->nombre_materia,
            'estado' => $this->estado
        ];
    }
}

