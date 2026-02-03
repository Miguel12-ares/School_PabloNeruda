-- =============================================
-- AGREGAR ASIGNACIONES DE MAESTRO A CURSOS ADICIONALES
-- Escuela Pablo Neruda
-- =============================================

USE escuela_pablo_neruda;

-- Eliminar asignaciones existentes del profesor (si existen) para evitar duplicados
DELETE FROM maestro_curso WHERE id_usuario = 3;

-- =============================================
-- ASIGNAR MAESTRO A TODOS LOS CURSOS
-- =============================================
-- Asignar al profesor (id_usuario=3) a los cursos
INSERT INTO maestro_curso (id_usuario, id_curso, id_materia) VALUES
-- Primero A (curso 2)
(3, 2, (SELECT id_materia FROM materias WHERE nombre_materia='Matemáticas')),
(3, 2, (SELECT id_materia FROM materias WHERE nombre_materia='Español')),
-- Quinto B (curso 3)
(3, 3, (SELECT id_materia FROM materias WHERE nombre_materia='Matemáticas')),
-- Segundo A (curso 4)
(3, 4, (SELECT id_materia FROM materias WHERE nombre_materia='Matemáticas')),
(3, 4, (SELECT id_materia FROM materias WHERE nombre_materia='Español')),
-- Tercero A (curso 5)
(3, 5, (SELECT id_materia FROM materias WHERE nombre_materia='Matemáticas')),
(3, 5, (SELECT id_materia FROM materias WHERE nombre_materia='Ciencias Naturales')),
-- Cuarto A (curso 6)
(3, 6, (SELECT id_materia FROM materias WHERE nombre_materia='Matemáticas'));

-- =============================================
-- Verificación
-- =============================================
SELECT 'Asignaciones actualizadas correctamente' AS mensaje;

SELECT 
    u.username,
    u.nombre_completo,
    c.nombre_curso,
    m.nombre_materia
FROM maestro_curso mc
JOIN usuarios u ON mc.id_usuario = u.id_usuario
JOIN cursos c ON mc.id_curso = c.id_curso
JOIN materias m ON mc.id_materia = m.id_materia
WHERE u.id_usuario = 3
ORDER BY c.id_curso, m.nombre_materia;
