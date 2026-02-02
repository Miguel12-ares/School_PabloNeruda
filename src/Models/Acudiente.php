<?php

/**
 * Entidad Acudiente
 * Representa un acudiente (padre/madre/tutor) del sistema
 */
class Acudiente {
    private ?int $id_acudiente;
    private string $nombre;
    private string $telefono;
    private string $parentesco;
    
    public function __construct(array $data = []) {
        $this->id_acudiente = $data['id_acudiente'] ?? null;
        $this->nombre = $data['nombre'] ?? '';
        $this->telefono = $data['telefono'] ?? '';
        $this->parentesco = $data['parentesco'] ?? '';
    }
    
    // Getters
    public function getIdAcudiente(): ?int { return $this->id_acudiente; }
    public function getNombre(): string { return $this->nombre; }
    public function getTelefono(): string { return $this->telefono; }
    public function getParentesco(): string { return $this->parentesco; }
    
    // Setters
    public function setIdAcudiente(int $id): void { $this->id_acudiente = $id; }
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }
    public function setTelefono(string $telefono): void { $this->telefono = $telefono; }
    public function setParentesco(string $parentesco): void { $this->parentesco = $parentesco; }
    
    public function toArray(): array {
        return [
            'id_acudiente' => $this->id_acudiente,
            'nombre' => $this->nombre,
            'telefono' => $this->telefono,
            'parentesco' => $this->parentesco
        ];
    }
}

