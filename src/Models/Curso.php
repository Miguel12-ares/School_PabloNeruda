<?php

/**
 * Entidad Curso
 * Representa un curso del sistema
 */
class Curso {
    private ?int $id_curso;
    private string $nombre_curso;
    private int $grado;
    private ?string $seccion;
    private int $capacidad_maxima;
    private string $jornada;
    
    public function __construct(array $data = []) {
        $this->id_curso = $data['id_curso'] ?? null;
        $this->nombre_curso = $data['nombre_curso'] ?? '';
        $this->grado = $data['grado'] ?? 0;
        $this->seccion = $data['seccion'] ?? null;
        $this->capacidad_maxima = $data['capacidad_maxima'] ?? MAX_STUDENTS_PER_COURSE;
        $this->jornada = $data['jornada'] ?? 'mañana';
    }
    
    // Getters
    public function getIdCurso(): ?int { return $this->id_curso; }
    public function getNombreCurso(): string { return $this->nombre_curso; }
    public function getGrado(): int { return $this->grado; }
    public function getSeccion(): ?string { return $this->seccion; }
    public function getCapacidadMaxima(): int { return $this->capacidad_maxima; }
    public function getJornada(): string { return $this->jornada; }
    
    // Setters
    public function setIdCurso(int $id): void { $this->id_curso = $id; }
    public function setNombreCurso(string $nombre): void { $this->nombre_curso = $nombre; }
    public function setGrado(int $grado): void { $this->grado = $grado; }
    public function setSeccion(?string $seccion): void { $this->seccion = $seccion; }
    public function setCapacidadMaxima(int $capacidad): void { $this->capacidad_maxima = $capacidad; }
    public function setJornada(string $jornada): void { $this->jornada = $jornada; }
    
    public function toArray(): array {
        return [
            'id_curso' => $this->id_curso,
            'nombre_curso' => $this->nombre_curso,
            'grado' => $this->grado,
            'seccion' => $this->seccion,
            'capacidad_maxima' => $this->capacidad_maxima,
            'jornada' => $this->jornada
        ];
    }
}

