<?php

/**
 * Repositorio de Periodos
 * Maneja todas las operaciones de base de datos relacionadas con periodos académicos
 */
class PeriodoRepository extends BaseRepository {
    protected string $table = 'periodos';
    protected string $primaryKey = 'id_periodo';
    
    /**
     * Obtener periodos por año lectivo
     */
    public function findByAnio(int $anio): array {
        $stmt = $this->db->prepare("SELECT * FROM periodos WHERE anio_lectivo = ? ORDER BY numero_periodo");
        $stmt->execute([$anio]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener el periodo actual (basado en fecha)
     */
    public function getCurrentPeriodo(): ?array {
        $sql = "SELECT * FROM periodos 
                WHERE CURDATE() BETWEEN fecha_inicio AND fecha_fin
                LIMIT 1";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Verificar si ya existe un periodo
     */
    public function existsPeriodo(int $numero_periodo, int $anio_lectivo, ?int $exclude_id = null): bool {
        $sql = "SELECT COUNT(*) FROM periodos 
                WHERE numero_periodo = ? AND anio_lectivo = ?";
        $params = [$numero_periodo, $anio_lectivo];
        
        if ($exclude_id !== null) {
            $sql .= " AND id_periodo != ?";
            $params[] = $exclude_id;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Obtener años lectivos disponibles
     */
    public function getAniosLectivos(): array {
        $sql = "SELECT DISTINCT anio_lectivo FROM periodos ORDER BY anio_lectivo DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Verificar si un periodo está activo (dentro de sus fechas)
     */
    public function isPeriodoActivo(int $id_periodo): bool {
        $sql = "SELECT COUNT(*) FROM periodos 
                WHERE id_periodo = ? 
                AND CURDATE() BETWEEN fecha_inicio AND fecha_fin";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_periodo]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Verificar si un periodo ya ha iniciado
     */
    public function isPeriodoIniciado(int $id_periodo): bool {
        $sql = "SELECT COUNT(*) FROM periodos 
                WHERE id_periodo = ? 
                AND CURDATE() >= fecha_inicio";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_periodo]);
        return $stmt->fetchColumn() > 0;
    }
}

