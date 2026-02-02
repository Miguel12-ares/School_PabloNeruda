<?php

/**
 * Entidad Estudiante
 * Representa un estudiante del sistema
 */
class Estudiante {
    private ?int $id_estudiante;
    private string $registro_civil;
    private ?string $tarjeta_identidad;
    private string $nombre;
    private string $apellido;
    private int $edad;
    private ?string $documento_pdf;
    private int $id_curso;
    private bool $tiene_alergias;
    private string $jornada;
    private ?string $fecha_registro;
    
    public function __construct(array $data = []) {
        $this->id_estudiante = $data['id_estudiante'] ?? null;
        $this->registro_civil = $data['registro_civil'] ?? '';
        $this->tarjeta_identidad = $data['tarjeta_identidad'] ?? null;
        $this->nombre = $data['nombre'] ?? '';
        $this->apellido = $data['apellido'] ?? '';
        $this->edad = $data['edad'] ?? 0;
        $this->documento_pdf = $data['documento_pdf'] ?? null;
        $this->id_curso = $data['id_curso'] ?? 0;
        $this->tiene_alergias = $data['tiene_alergias'] ?? false;
        $this->jornada = $data['jornada'] ?? 'mañana';
        $this->fecha_registro = $data['fecha_registro'] ?? null;
    }
    
    // Getters
    public function getIdEstudiante(): ?int { return $this->id_estudiante; }
    public function getRegistroCivil(): string { return $this->registro_civil; }
    public function getTarjetaIdentidad(): ?string { return $this->tarjeta_identidad; }
    public function getNombre(): string { return $this->nombre; }
    public function getApellido(): string { return $this->apellido; }
    public function getNombreCompleto(): string { return $this->nombre . ' ' . $this->apellido; }
    public function getEdad(): int { return $this->edad; }
    public function getDocumentoPdf(): ?string { return $this->documento_pdf; }
    public function getIdCurso(): int { return $this->id_curso; }
    public function getTieneAlergias(): bool { return $this->tiene_alergias; }
    public function getJornada(): string { return $this->jornada; }
    public function getFechaRegistro(): ?string { return $this->fecha_registro; }
    
    // Setters
    public function setIdEstudiante(int $id): void { $this->id_estudiante = $id; }
    public function setRegistroCivil(string $registro): void { $this->registro_civil = $registro; }
    public function setTarjetaIdentidad(?string $tarjeta): void { $this->tarjeta_identidad = $tarjeta; }
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }
    public function setApellido(string $apellido): void { $this->apellido = $apellido; }
    public function setEdad(int $edad): void { $this->edad = $edad; }
    public function setDocumentoPdf(?string $documento): void { $this->documento_pdf = $documento; }
    public function setIdCurso(int $id_curso): void { $this->id_curso = $id_curso; }
    public function setTieneAlergias(bool $tiene): void { $this->tiene_alergias = $tiene; }
    public function setJornada(string $jornada): void { $this->jornada = $jornada; }
    
    public function toArray(): array {
        return [
            'id_estudiante' => $this->id_estudiante,
            'registro_civil' => $this->registro_civil,
            'tarjeta_identidad' => $this->tarjeta_identidad,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'edad' => $this->edad,
            'documento_pdf' => $this->documento_pdf,
            'id_curso' => $this->id_curso,
            'tiene_alergias' => $this->tiene_alergias,
            'jornada' => $this->jornada,
            'fecha_registro' => $this->fecha_registro
        ];
    }
}

