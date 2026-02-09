<?php

/**
 * Repositorio de Autenticación
 * Maneja operaciones de base de datos relacionadas con usuarios, roles y permisos
 */
class AuthRepository extends BaseRepository {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'usuarios';
        $this->primaryKey = 'id_usuario';
    }
    
    /**
     * Buscar usuario por username o email
     */
    public function findByUsername(string $username): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM usuarios WHERE username = ? OR email = ? LIMIT 1"
        );
        $stmt->execute([$username, $username]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Obtener roles de un usuario
     */
    public function getUserRoles(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT r.id_rol, r.nombre_rol, r.descripcion, r.nivel_acceso
            FROM roles r
            INNER JOIN usuario_rol ur ON r.id_rol = ur.id_rol
            WHERE ur.id_usuario = ?
            ORDER BY r.nivel_acceso DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener permisos de un usuario (a través de sus roles)
     */
    public function getUserPermissions(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT DISTINCT p.id_permiso, p.modulo, p.accion, p.descripcion
            FROM permisos p
            INNER JOIN rol_permiso rp ON p.id_permiso = rp.id_permiso
            INNER JOIN usuario_rol ur ON rp.id_rol = ur.id_rol
            WHERE ur.id_usuario = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Verificar si un usuario tiene un permiso específico
     */
    public function hasPermission(int $userId, string $modulo, string $accion): bool {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM permisos p
            INNER JOIN rol_permiso rp ON p.id_permiso = rp.id_permiso
            INNER JOIN usuario_rol ur ON rp.id_rol = ur.id_rol
            WHERE ur.id_usuario = ?
            AND p.modulo = ?
            AND p.accion = ?
        ");
        $stmt->execute([$userId, $modulo, $accion]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Obtener cursos asignados a un maestro
     */
    public function getMaestroCursos(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT DISTINCT c.id_curso, c.nombre_curso, c.grado, c.seccion
            FROM cursos c
            INNER JOIN maestro_curso mc ON c.id_curso = mc.id_curso
            WHERE mc.id_usuario = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener materias asignadas a un maestro en un curso específico
     */
    public function getMaestroMaterias(int $userId, ?int $cursoId = null): array {
        if ($cursoId) {
            $stmt = $this->db->prepare("
                SELECT m.id_materia, m.nombre_materia, mc.id_curso
                FROM materias m
                INNER JOIN maestro_curso mc ON m.id_materia = mc.id_materia
                WHERE mc.id_usuario = ? AND mc.id_curso = ?
            ");
            $stmt->execute([$userId, $cursoId]);
        } else {
            $stmt = $this->db->prepare("
                SELECT DISTINCT m.id_materia, m.nombre_materia
                FROM materias m
                INNER JOIN maestro_curso mc ON m.id_materia = mc.id_materia
                WHERE mc.id_usuario = ?
            ");
            $stmt->execute([$userId]);
        }
        return $stmt->fetchAll();
    }
    
    /**
     * Verificar si un maestro puede acceder a un curso
     */
    public function maestroTieneCurso(int $userId, int $cursoId): bool {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM maestro_curso 
            WHERE id_usuario = ? AND id_curso = ?
        ");
        $stmt->execute([$userId, $cursoId]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Verificar si un maestro puede editar una materia en un curso
     */
    public function maestroPuedeEditarMateria(int $userId, int $cursoId, int $materiaId): bool {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM maestro_curso 
            WHERE id_usuario = ? AND id_curso = ? AND id_materia = ?
        ");
        $stmt->execute([$userId, $cursoId, $materiaId]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Actualizar último acceso del usuario
     */
    public function updateLastAccess(int $userId): bool {
        $stmt = $this->db->prepare(
            "UPDATE usuarios SET ultimo_acceso = CURRENT_TIMESTAMP WHERE id_usuario = ?"
        );
        return $stmt->execute([$userId]);
    }
    
    /**
     * Obtener usuario con sus roles y permisos completos
     */
    public function getUserComplete(int $userId): ?array {
        $user = $this->findById($userId);
        if (!$user) {
            return null;
        }
        
        $user['roles'] = $this->getUserRoles($userId);
        $user['permisos'] = $this->getUserPermissions($userId);
        
        // Si tiene rol de maestro, obtener cursos y materias asignados
        $esMaestro = false;
        foreach ($user['roles'] as $rol) {
            if ($rol['nombre_rol'] === 'Maestro') {
                $esMaestro = true;
                break;
            }
        }
        
        if ($esMaestro) {
            $user['cursos_asignados'] = $this->getMaestroCursos($userId);
            $user['materias_asignadas'] = $this->getMaestroMaterias($userId);
        }
        
        return $user;
    }
    
    /**
     * Verificar si username ya existe
     */
    public function usernameExists(string $username, ?int $excludeUserId = null): bool {
        if ($excludeUserId) {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM usuarios WHERE username = ? AND id_usuario != ?"
            );
            $stmt->execute([$username, $excludeUserId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE username = ?");
            $stmt->execute([$username]);
        }
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Verificar si email ya existe
     */
    public function emailExists(string $email, ?int $excludeUserId = null): bool {
        if ($excludeUserId) {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM usuarios WHERE email = ? AND id_usuario != ?"
            );
            $stmt->execute([$email, $excludeUserId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
        }
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Obtener todos los usuarios con sus roles
     */
    public function findAllWithRoles(): array {
        $stmt = $this->db->query("
            SELECT u.*, 
                   GROUP_CONCAT(r.nombre_rol SEPARATOR ', ') as roles
            FROM usuarios u
            LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
            LEFT JOIN roles r ON ur.id_rol = r.id_rol
            GROUP BY u.id_usuario
            ORDER BY u.fecha_creacion DESC
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Asignar rol a usuario
     */
    public function assignRole(int $userId, int $roleId): bool {
        try {
            $stmt = $this->db->prepare(
                "INSERT IGNORE INTO usuario_rol (id_usuario, id_rol) VALUES (?, ?)"
            );
            return $stmt->execute([$userId, $roleId]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Remover rol de usuario
     */
    public function removeRole(int $userId, int $roleId): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM usuario_rol WHERE id_usuario = ? AND id_rol = ?"
        );
        return $stmt->execute([$userId, $roleId]);
    }
    
    /**
     * Asignar curso y materia a maestro
     */
    public function assignCursoToMaestro(int $userId, int $cursoId, int $materiaId): bool {
        try {
            $stmt = $this->db->prepare(
                "INSERT IGNORE INTO maestro_curso (id_usuario, id_curso, id_materia) VALUES (?, ?, ?)"
            );
            return $stmt->execute([$userId, $cursoId, $materiaId]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Remover asignación de curso a maestro
     */
    public function removeCursoFromMaestro(int $userId, int $cursoId, int $materiaId): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM maestro_curso WHERE id_usuario = ? AND id_curso = ? AND id_materia = ?"
        );
        return $stmt->execute([$userId, $cursoId, $materiaId]);
    }
    
    /**
     * Obtener todos los roles disponibles
     */
    public function getAllRoles(): array {
        $stmt = $this->db->query("SELECT * FROM roles ORDER BY nivel_acceso DESC");
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener maestros que imparten una materia específica
     */
    public function getMaestrosByMateria(int $materiaId): array {
        $stmt = $this->db->prepare("
            SELECT DISTINCT 
                u.id_usuario,
                u.username,
                u.nombre_completo,
                u.email,
                c.id_curso,
                c.nombre_curso
            FROM usuarios u
            INNER JOIN maestro_curso mc ON u.id_usuario = mc.id_usuario
            INNER JOIN cursos c ON mc.id_curso = c.id_curso
            WHERE mc.id_materia = ?
            ORDER BY u.nombre_completo, c.nombre_curso
        ");
        $stmt->execute([$materiaId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener maestros asignados a un curso específico
     */
    public function getMaestrosByCurso(int $cursoId): array {
        $stmt = $this->db->prepare("
            SELECT 
                u.id_usuario,
                u.username,
                u.nombre_completo,
                u.email,
                GROUP_CONCAT(m.nombre_materia ORDER BY m.nombre_materia SEPARATOR ', ') as materias_imparte
            FROM usuarios u
            INNER JOIN maestro_curso mc ON u.id_usuario = mc.id_usuario
            INNER JOIN materias m ON mc.id_materia = m.id_materia
            WHERE mc.id_curso = ?
            GROUP BY u.id_usuario, u.username, u.nombre_completo, u.email
            ORDER BY u.nombre_completo
        ");
        $stmt->execute([$cursoId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener asignaciones de curso y materia de un maestro
     */
    public function getMaestroAsignaciones(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT mc.id_curso, mc.id_materia, c.nombre_curso, m.nombre_materia
            FROM maestro_curso mc
            INNER JOIN cursos c ON mc.id_curso = c.id_curso
            INNER JOIN materias m ON mc.id_materia = m.id_materia
            WHERE mc.id_usuario = ?
            ORDER BY c.nombre_curso, m.nombre_materia
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}

