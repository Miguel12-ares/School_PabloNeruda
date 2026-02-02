-- =============================================
-- DATOS DE PRUEBA - Escuela Pablo Neruda
-- =============================================

USE escuela_pablo_neruda;

-- Insertar periodos académicos
INSERT INTO periodos (numero_periodo, anio_lectivo, fecha_inicio, fecha_fin) VALUES
(1, 2026, '2026-01-15', '2026-03-31'),
(2, 2026, '2026-04-01', '2026-06-15'),
(3, 2026, '2026-07-15', '2026-09-30'),
(4, 2026, '2026-10-01', '2026-11-30');

-- Insertar cursos adicionales
INSERT INTO cursos (nombre_curso, grado, seccion, jornada, capacidad_maxima) VALUES
('Segundo A', 2, 'A', 'mañana', 35),
('Tercero A', 3, 'A', 'mañana', 35),
('Cuarto A', 4, 'A', 'tarde', 35);

-- Insertar estudiantes de prueba
INSERT INTO estudiantes (registro_civil, tarjeta_identidad, nombre, apellido, edad, id_curso, tiene_alergias, jornada) VALUES
('RC-2020-001', 'TI-1234567890', 'Juan', 'Pérez García', 7, 2, 0, 'mañana'),
('RC-2019-002', 'TI-0987654321', 'María', 'González López', 8, 2, 1, 'mañana'),
('RC-2018-003', 'TI-1122334455', 'Carlos', 'Rodríguez Martínez', 9, 3, 0, 'mañana'),
('RC-2017-004', 'TI-5544332211', 'Ana', 'Fernández Silva', 10, 4, 1, 'tarde'),
('RC-2020-005', NULL, 'Luis', 'Ramírez Torres', 7, 2, 0, 'mañana'),
('RC-2019-006', 'TI-6677889900', 'Sofia', 'Morales Castro', 8, 2, 0, 'mañana');

-- Insertar alergias para estudiantes que las tienen
INSERT INTO alergias_estudiante (id_estudiante, tipo_alergia) VALUES
(2, 'Alergia al maní'),
(2, 'Alergia a los mariscos'),
(4, 'Alergia al polen'),
(4, 'Asma');

-- Insertar acudientes
INSERT INTO acudientes (nombre, telefono, parentesco) VALUES
('Pedro Pérez', '300-123-4567', 'Padre'),
('Laura García', '301-234-5678', 'Madre'),
('Roberto González', '302-345-6789', 'Padre'),
('Carmen López', '303-456-7890', 'Madre'),
('Miguel Rodríguez', '304-567-8901', 'Padre'),
('Patricia Martínez', '305-678-9012', 'Madre'),
('Jorge Fernández', '306-789-0123', 'Padre'),
('Isabel Silva', '307-890-1234', 'Madre'),
('Antonio Ramírez', '308-901-2345', 'Padre'),
('Rosa Torres', '309-012-3456', 'Madre'),
('Fernando Morales', '310-123-4567', 'Padre'),
('Diana Castro', '311-234-5678', 'Madre');

-- Asociar acudientes con estudiantes
INSERT INTO estudiante_acudiente (id_estudiante, id_acudiente, es_principal) VALUES
(1, 1, 1), -- Juan - Padre (principal)
(1, 2, 0), -- Juan - Madre
(2, 3, 1), -- María - Padre (principal)
(2, 4, 0), -- María - Madre
(3, 5, 1), -- Carlos - Padre (principal)
(3, 6, 0), -- Carlos - Madre
(4, 7, 1), -- Ana - Padre (principal)
(4, 8, 0), -- Ana - Madre
(5, 9, 1), -- Luis - Padre (principal)
(5, 10, 0), -- Luis - Madre
(6, 11, 1), -- Sofia - Padre (principal)
(6, 12, 0); -- Sofia - Madre

-- Insertar información de convivencia familiar
INSERT INTO convivencia_familiar (id_estudiante, cantidad_hermanos, con_quien_vive) VALUES
(1, 2, 'Vive con ambos padres y dos hermanos'),
(2, 1, 'Vive con ambos padres y un hermano'),
(3, 0, 'Vive con ambos padres, hijo único'),
(4, 3, 'Vive con ambos padres y tres hermanos'),
(5, 1, 'Vive con madre y un hermano'),
(6, 2, 'Vive con ambos padres y dos hermanos');

-- Asignar materias a cursos
-- Segundo A (id_curso = 4)
INSERT INTO curso_materia (id_curso, id_materia) VALUES
(4, 1), (4, 2), (4, 3), (4, 4), (4, 5), (4, 6), (4, 7), (4, 8), (4, 9), (4, 10), (4, 11);

-- Tercero A (id_curso = 5)
INSERT INTO curso_materia (id_curso, id_materia) VALUES
(5, 1), (5, 2), (5, 3), (5, 4), (5, 5), (5, 6), (5, 7), (5, 8), (5, 9), (5, 10), (5, 11);

-- Cuarto A (id_curso = 6)
INSERT INTO curso_materia (id_curso, id_materia) VALUES
(6, 1), (6, 2), (6, 3), (6, 4), (6, 5), (6, 6), (6, 7), (6, 8), (6, 9), (6, 10), (6, 11);

-- Insertar notas de prueba para el periodo 1 de 2026
-- Estudiante 1 (Juan) - Segundo A
INSERT INTO notas (id_estudiante, id_materia, id_periodo, nota_1, nota_2, nota_3, nota_4, nota_5) VALUES
(1, 1, 1, 4.5, 4.2, 4.8, 4.6, 4.7), -- Matemáticas
(1, 2, 1, 4.0, 4.3, 4.1, 4.2, 4.4), -- Español
(1, 3, 1, 5.0, 4.8, 4.9, 5.0, 4.9), -- Informática
(1, 4, 1, 3.8, 4.0, 3.9, 4.1, 4.0), -- Inglés
(1, 5, 1, 4.5, 4.5, 4.6, 4.5, 4.5), -- Religión
(1, 6, 1, 4.2, 4.3, 4.4, 4.2, 4.3), -- Ética
(1, 7, 1, 4.0, 4.1, 4.2, 4.0, 4.1), -- Ciencias Naturales
(1, 8, 1, 4.3, 4.4, 4.5, 4.3, 4.4), -- Tecnología
(1, 9, 1, 4.8, 4.7, 4.9, 4.8, 4.8), -- Educación Artística
(1, 10, 1, 5.0, 5.0, 5.0, 5.0, 5.0), -- Educación Física
(1, 11, 1, 4.1, 4.2, 4.0, 4.3, 4.2); -- Ciencias Sociales

-- Estudiante 2 (María) - Segundo A
INSERT INTO notas (id_estudiante, id_materia, id_periodo, nota_1, nota_2, nota_3, nota_4, nota_5) VALUES
(2, 1, 1, 3.5, 3.2, 3.8, 3.6, 3.7), -- Matemáticas
(2, 2, 1, 4.0, 3.8, 4.1, 3.9, 4.0), -- Español
(2, 3, 1, 4.5, 4.3, 4.6, 4.4, 4.5), -- Informática
(2, 4, 1, 2.8, 3.0, 2.9, 3.1, 3.0), -- Inglés (reprobado)
(2, 5, 1, 4.0, 4.0, 4.1, 4.0, 4.0), -- Religión
(2, 6, 1, 3.8, 3.9, 3.7, 3.8, 3.8), -- Ética
(2, 7, 1, 3.2, 3.3, 3.1, 3.4, 3.3), -- Ciencias Naturales
(2, 8, 1, 4.0, 4.1, 4.0, 4.2, 4.1), -- Tecnología
(2, 9, 1, 4.5, 4.6, 4.5, 4.7, 4.6), -- Educación Artística
(2, 10, 1, 4.8, 4.9, 4.8, 4.9, 4.9), -- Educación Física
(2, 11, 1, 3.5, 3.6, 3.5, 3.7, 3.6); -- Ciencias Sociales

-- Estudiante 3 (Carlos) - Tercero A
INSERT INTO notas (id_estudiante, id_materia, id_periodo, nota_1, nota_2, nota_3, nota_4, nota_5) VALUES
(3, 1, 1, 4.8, 4.9, 5.0, 4.8, 4.9), -- Matemáticas
(3, 2, 1, 4.5, 4.6, 4.7, 4.5, 4.6), -- Español
(3, 3, 1, 5.0, 5.0, 5.0, 5.0, 5.0), -- Informática
(3, 4, 1, 4.2, 4.3, 4.4, 4.2, 4.3), -- Inglés
(3, 5, 1, 4.5, 4.5, 4.5, 4.5, 4.5), -- Religión
(3, 6, 1, 4.6, 4.7, 4.6, 4.7, 4.7), -- Ética
(3, 7, 1, 4.3, 4.4, 4.5, 4.3, 4.4), -- Ciencias Naturales
(3, 8, 1, 4.8, 4.9, 4.8, 4.9, 4.9), -- Tecnología
(3, 9, 1, 4.7, 4.8, 4.7, 4.8, 4.8), -- Educación Artística
(3, 10, 1, 5.0, 5.0, 5.0, 5.0, 5.0), -- Educación Física
(3, 11, 1, 4.4, 4.5, 4.6, 4.4, 4.5); -- Ciencias Sociales

-- Estudiante 4 (Ana) - Cuarto A
INSERT INTO notas (id_estudiante, id_materia, id_periodo, nota_1, nota_2, nota_3, nota_4, nota_5) VALUES
(4, 1, 1, 2.5, 2.8, 2.6, 2.7, 2.6), -- Matemáticas (reprobado)
(4, 2, 1, 3.0, 3.2, 3.1, 3.3, 3.2), -- Español
(4, 3, 1, 3.5, 3.6, 3.7, 3.5, 3.6), -- Informática
(4, 4, 1, 2.8, 2.9, 2.7, 3.0, 2.9), -- Inglés (reprobado)
(4, 5, 1, 3.5, 3.5, 3.6, 3.5, 3.5), -- Religión
(4, 6, 1, 3.2, 3.3, 3.2, 3.4, 3.3), -- Ética
(4, 7, 1, 2.9, 3.0, 2.8, 3.1, 3.0), -- Ciencias Naturales (reprobado)
(4, 8, 1, 3.3, 3.4, 3.5, 3.3, 3.4), -- Tecnología
(4, 9, 1, 4.0, 4.1, 4.0, 4.2, 4.1), -- Educación Artística
(4, 10, 1, 4.5, 4.6, 4.5, 4.6, 4.6), -- Educación Física
(4, 11, 1, 3.1, 3.2, 3.0, 3.3, 3.2); -- Ciencias Sociales

-- Estudiante 5 (Luis) - Segundo A
INSERT INTO notas (id_estudiante, id_materia, id_periodo, nota_1, nota_2, nota_3, nota_4, nota_5) VALUES
(5, 1, 1, 3.8, 4.0, 3.9, 4.1, 4.0), -- Matemáticas
(5, 2, 1, 3.5, 3.6, 3.7, 3.5, 3.6), -- Español
(5, 3, 1, 4.2, 4.3, 4.4, 4.2, 4.3), -- Informática
(5, 4, 1, 3.3, 3.4, 3.5, 3.3, 3.4), -- Inglés
(5, 5, 1, 4.0, 4.0, 4.1, 4.0, 4.0), -- Religión
(5, 6, 1, 3.7, 3.8, 3.7, 3.9, 3.8), -- Ética
(5, 7, 1, 3.5, 3.6, 3.7, 3.5, 3.6), -- Ciencias Naturales
(5, 8, 1, 3.9, 4.0, 4.1, 3.9, 4.0), -- Tecnología
(5, 9, 1, 4.3, 4.4, 4.5, 4.3, 4.4), -- Educación Artística
(5, 10, 1, 4.7, 4.8, 4.7, 4.8, 4.8), -- Educación Física
(5, 11, 1, 3.6, 3.7, 3.8, 3.6, 3.7); -- Ciencias Sociales

-- Estudiante 6 (Sofia) - Segundo A
INSERT INTO notas (id_estudiante, id_materia, id_periodo, nota_1, nota_2, nota_3, nota_4, nota_5) VALUES
(6, 1, 1, 4.2, 4.3, 4.4, 4.2, 4.3), -- Matemáticas
(6, 2, 1, 4.5, 4.6, 4.7, 4.5, 4.6), -- Español
(6, 3, 1, 4.8, 4.9, 5.0, 4.8, 4.9), -- Informática
(6, 4, 1, 4.0, 4.1, 4.2, 4.0, 4.1), -- Inglés
(6, 5, 1, 4.3, 4.3, 4.4, 4.3, 4.3), -- Religión
(6, 6, 1, 4.4, 4.5, 4.4, 4.6, 4.5), -- Ética
(6, 7, 1, 4.1, 4.2, 4.3, 4.1, 4.2), -- Ciencias Naturales
(6, 8, 1, 4.5, 4.6, 4.7, 4.5, 4.6), -- Tecnología
(6, 9, 1, 4.7, 4.8, 4.9, 4.7, 4.8), -- Educación Artística
(6, 10, 1, 5.0, 5.0, 5.0, 5.0, 5.0), -- Educación Física
(6, 11, 1, 4.3, 4.4, 4.5, 4.3, 4.4); -- Ciencias Sociales

-- =============================================
-- Verificación de datos insertados
-- =============================================

SELECT 'Datos de prueba insertados correctamente' AS mensaje;
SELECT COUNT(*) AS total_estudiantes FROM estudiantes;
SELECT COUNT(*) AS total_acudientes FROM acudientes;
SELECT COUNT(*) AS total_notas FROM notas;
SELECT COUNT(*) AS total_periodos FROM periodos;

