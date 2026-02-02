<?php

/**
 * Servicio de Estudiantes
 * Contiene la lógica de negocio para gestión de estudiantes
 */
class EstudianteService {
    private EstudianteRepository $estudianteRepo;
    private CursoRepository $cursoRepo;
    private AcudienteRepository $acudienteRepo;
    private AlergiaRepository $alergiaRepo;
    private EstudianteValidator $validator;
    
    public function __construct() {
        $this->estudianteRepo = new EstudianteRepository();
        $this->cursoRepo = new CursoRepository();
        $this->acudienteRepo = new AcudienteRepository();
        $this->alergiaRepo = new AlergiaRepository();
        $this->validator = new EstudianteValidator();
    }
    
    /**
     * Crear un nuevo estudiante
     */
    public function create(array $data, ?array $file = null): array {
        // Validar datos básicos
        $errors = $this->validator->validate($data);
        
        // Validar archivo si existe
        if ($file && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            $fileErrors = $this->validator->validateFile($file);
            $errors = array_merge($errors, $fileErrors);
        }
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        // Verificar que el documento no exista
        if ($this->estudianteRepo->documentoExists($data['registro_civil'])) {
            return ['success' => false, 'errors' => ['registro_civil' => 'Este documento ya está registrado']];
        }
        
        // Verificar capacidad del curso
        if (!$this->cursoRepo->hasCapacity($data['id_curso'])) {
            return ['success' => false, 'errors' => ['id_curso' => 'El curso ha alcanzado su capacidad máxima']];
        }
        
        // Procesar archivo PDF si existe
        $documentoPdf = null;
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $documentoPdf = $this->uploadDocument($file);
            if (!$documentoPdf) {
                return ['success' => false, 'errors' => ['documento_pdf' => 'Error al guardar el documento']];
            }
        }
        
        try {
            // Preparar datos para inserción
            $estudianteData = [
                'registro_civil' => $data['registro_civil'],
                'tarjeta_identidad' => $data['tarjeta_identidad'] ?? null,
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'edad' => $data['edad'],
                'documento_pdf' => $documentoPdf,
                'id_curso' => $data['id_curso'],
                'tiene_alergias' => isset($data['tiene_alergias']) ? 1 : 0,
                'jornada' => $data['jornada']
            ];
            
            $id_estudiante = $this->estudianteRepo->create($estudianteData);
            
            // Guardar alergias si existen
            if (!empty($data['alergias']) && $estudianteData['tiene_alergias']) {
                $alergias = is_array($data['alergias']) ? $data['alergias'] : [$data['alergias']];
                foreach ($alergias as $alergia) {
                    if (!empty(trim($alergia))) {
                        $this->alergiaRepo->create($id_estudiante, trim($alergia));
                    }
                }
            }
            
            return ['success' => true, 'id' => $id_estudiante];
            
        } catch (Exception $e) {
            // Si hay error, eliminar el archivo subido
            if ($documentoPdf && file_exists(UPLOAD_PATH . '/' . $documentoPdf)) {
                unlink(UPLOAD_PATH . '/' . $documentoPdf);
            }
            return ['success' => false, 'errors' => ['general' => 'Error al crear el estudiante: ' . $e->getMessage()]];
        }
    }
    
    /**
     * Actualizar un estudiante
     */
    public function update(int $id, array $data, ?array $file = null): array {
        // Validar datos
        $errors = $this->validator->validate($data);
        
        if ($file && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            $fileErrors = $this->validator->validateFile($file);
            $errors = array_merge($errors, $fileErrors);
        }
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        // Verificar que el estudiante existe
        if (!$this->estudianteRepo->exists($id)) {
            return ['success' => false, 'errors' => ['general' => 'Estudiante no encontrado']];
        }
        
        // Verificar que el documento no exista en otro estudiante
        if ($this->estudianteRepo->documentoExists($data['registro_civil'], $id)) {
            return ['success' => false, 'errors' => ['registro_civil' => 'Este documento ya está registrado']];
        }
        
        try {
            $estudiante = $this->estudianteRepo->findById($id);
            $documentoPdf = $estudiante['documento_pdf'];
            
            // Procesar nuevo archivo si existe
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $newDocument = $this->uploadDocument($file);
                if ($newDocument) {
                    // Eliminar archivo anterior
                    if ($documentoPdf && file_exists(UPLOAD_PATH . '/' . $documentoPdf)) {
                        unlink(UPLOAD_PATH . '/' . $documentoPdf);
                    }
                    $documentoPdf = $newDocument;
                }
            }
            
            $estudianteData = [
                'registro_civil' => $data['registro_civil'],
                'tarjeta_identidad' => $data['tarjeta_identidad'] ?? null,
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'edad' => $data['edad'],
                'documento_pdf' => $documentoPdf,
                'id_curso' => $data['id_curso'],
                'tiene_alergias' => isset($data['tiene_alergias']) ? 1 : 0,
                'jornada' => $data['jornada']
            ];
            
            $this->estudianteRepo->update($id, $estudianteData);
            
            // Actualizar alergias
            $this->alergiaRepo->deleteByEstudiante($id);
            if (!empty($data['alergias']) && $estudianteData['tiene_alergias']) {
                $alergias = is_array($data['alergias']) ? $data['alergias'] : [$data['alergias']];
                foreach ($alergias as $alergia) {
                    if (!empty(trim($alergia))) {
                        $this->alergiaRepo->create($id, trim($alergia));
                    }
                }
            }
            
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'errors' => ['general' => 'Error al actualizar: ' . $e->getMessage()]];
        }
    }
    
    /**
     * Eliminar un estudiante
     */
    public function delete(int $id): array {
        try {
            $estudiante = $this->estudianteRepo->findById($id);
            
            if (!$estudiante) {
                return ['success' => false, 'message' => 'Estudiante no encontrado'];
            }
            
            // Eliminar archivo PDF si existe
            if ($estudiante['documento_pdf'] && file_exists(UPLOAD_PATH . '/' . $estudiante['documento_pdf'])) {
                unlink(UPLOAD_PATH . '/' . $estudiante['documento_pdf']);
            }
            
            $this->estudianteRepo->delete($id);
            
            return ['success' => true];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()];
        }
    }
    
    /**
     * Buscar estudiantes
     */
    public function search(string $query): array {
        // Intentar buscar por documento primero
        $estudiante = $this->estudianteRepo->findByDocumento($query);
        if ($estudiante) {
            return [$estudiante];
        }
        
        // Buscar por nombre
        return $this->estudianteRepo->findByNombre($query);
    }
    
    /**
     * Subir documento PDF
     */
    private function uploadDocument(array $file): ?string {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('doc_') . '_' . time() . '.' . $extension;
        $destination = UPLOAD_PATH . '/' . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $filename;
        }
        
        return null;
    }
    
    /**
     * Obtener todos los estudiantes con información de curso
     */
    public function getAllWithCurso(): array {
        return $this->estudianteRepo->findAllWithCurso();
    }
    
    /**
     * Obtener estudiante por ID
     */
    public function getById(int $id): ?array {
        return $this->estudianteRepo->findById($id);
    }
    
    /**
     * Obtener estudiantes por curso
     */
    public function getByCurso(int $id_curso): array {
        return $this->estudianteRepo->findByCurso($id_curso);
    }
    
    /**
     * Obtener estudiantes con alergias
     */
    public function getWithAlergias(): array {
        return $this->estudianteRepo->findWithAlergias();
    }
}

