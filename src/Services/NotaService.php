<?php

/**
 * Servicio de Notas
 * Contiene la lógica de negocio para gestión de calificaciones
 */
class NotaService {
    private NotaRepository $notaRepo;
    private EstudianteRepository $estudianteRepo;
    private MateriaRepository $materiaRepo;
    private PeriodoRepository $periodoRepo;
    private NotaValidator $validator;
    
    public function __construct() {
        $this->notaRepo = new NotaRepository();
        $this->estudianteRepo = new EstudianteRepository();
        $this->materiaRepo = new MateriaRepository();
        $this->periodoRepo = new PeriodoRepository();
        $this->validator = new NotaValidator();
    }
    
    /**
     * Crear o actualizar notas
     */
    public function saveNotas(array $data): array {
        // Validar datos
        $errors = $this->validator->validate($data);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        try {
            // Verificar si ya existe un registro
            $exists = $this->notaRepo->existsForEstudianteMateriaAndPeriodo(
                $data['id_estudiante'],
                $data['id_materia'],
                $data['id_periodo']
            );
            
            $notaData = [
                'id_estudiante' => $data['id_estudiante'],
                'id_materia' => $data['id_materia'],
                'id_periodo' => $data['id_periodo'],
                'nota_1' => !empty($data['nota_1']) ? $data['nota_1'] : null,
                'nota_2' => !empty($data['nota_2']) ? $data['nota_2'] : null,
                'nota_3' => !empty($data['nota_3']) ? $data['nota_3'] : null,
                'nota_4' => !empty($data['nota_4']) ? $data['nota_4'] : null,
                'nota_5' => !empty($data['nota_5']) ? $data['nota_5'] : null,
            ];
            
            if ($exists) {
                // Obtener el ID de la nota existente
                $notas = $this->notaRepo->findByEstudianteAndPeriodo(
                    $data['id_estudiante'],
                    $data['id_periodo']
                );
                
                foreach ($notas as $nota) {
                    if ($nota['id_materia'] == $data['id_materia']) {
                        $this->notaRepo->update($nota['id_nota'], $notaData);
                        return ['success' => true, 'message' => 'Notas actualizadas correctamente'];
                    }
                }
            }
            
            // Crear nuevo registro
            $id = $this->notaRepo->create($notaData);
            return ['success' => true, 'message' => 'Notas guardadas correctamente', 'id' => $id];
            
        } catch (Exception $e) {
            return ['success' => false, 'errors' => ['general' => 'Error al guardar notas: ' . $e->getMessage()]];
        }
    }
    
    /**
     * Obtener boletín de un estudiante
     */
    public function getBoletin(int $id_estudiante, int $id_periodo): array {
        return $this->notaRepo->getBoletinByEstudianteAndPeriodo($id_estudiante, $id_periodo);
    }
    
    /**
     * Obtener notas de un estudiante en un periodo
     */
    public function getNotasByEstudianteAndPeriodo(int $id_estudiante, int $id_periodo): array {
        return $this->notaRepo->findByEstudianteAndPeriodo($id_estudiante, $id_periodo);
    }
    
    /**
     * Obtener todas las notas de un estudiante
     */
    public function getNotasByEstudiante(int $id_estudiante): array {
        return $this->notaRepo->findByEstudiante($id_estudiante);
    }
    
    /**
     * Obtener estadísticas de una materia en un periodo
     */
    public function getEstadisticas(int $id_materia, int $id_periodo): array {
        return $this->notaRepo->getEstadisticasByMateriaAndPeriodo($id_materia, $id_periodo);
    }
    
    /**
     * Obtener estudiantes reprobados en un periodo
     */
    public function getReprobados(int $id_periodo): array {
        return $this->notaRepo->findReprobadosByPeriodo($id_periodo);
    }
    
    /**
     * Calcular promedio general de un estudiante en un periodo
     */
    public function getPromedioGeneral(int $id_estudiante, int $id_periodo): ?float {
        $notas = $this->notaRepo->findByEstudianteAndPeriodo($id_estudiante, $id_periodo);
        
        if (empty($notas)) {
            return null;
        }
        
        $suma = 0;
        $count = 0;
        
        foreach ($notas as $nota) {
            if ($nota['promedio'] !== null) {
                $suma += $nota['promedio'];
                $count++;
            }
        }
        
        return $count > 0 ? round($suma / $count, 1) : null;
    }
}

