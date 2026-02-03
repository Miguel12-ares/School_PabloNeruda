-- =============================================
-- DATOS INICIALES PARA SISTEMA DE AUTENTICACIÓN
-- Escuela Pablo Neruda
-- =============================================

USE escuela_pablo_neruda;

-- =============================================
-- 1. ROLES DEL SISTEMA
-- =============================================
INSERT INTO roles (id_rol, nombre_rol, descripcion, nivel_acceso) VALUES
(1, 'Maestro', 'Profesor con acceso limitado a sus cursos y materias asignadas', 1),
(2, 'Directivo', 'Personal directivo con acceso de consulta y reportes generales', 2),
(3, 'Administrativo', 'Personal administrativo con acceso completo al sistema', 3);

-- =============================================
-- 2. PERMISOS DEL SISTEMA
-- =============================================
-- Módulo: Estudiantes
INSERT INTO permisos (modulo, accion, descripcion) VALUES
('estudiantes', 'ver', 'Ver listado y detalles de estudiantes'),
('estudiantes', 'crear', 'Crear nuevos estudiantes'),
('estudiantes', 'editar', 'Editar información de estudiantes'),
('estudiantes', 'eliminar', 'Eliminar estudiantes del sistema'),
('estudiantes', 'ver_todos', 'Ver estudiantes de todos los cursos'),
('estudiantes', 'ver_propios', 'Ver solo estudiantes de cursos asignados');

-- Módulo: Notas
INSERT INTO permisos (modulo, accion, descripcion) VALUES
('notas', 'ver', 'Ver notas de estudiantes'),
('notas', 'registrar', 'Registrar nuevas notas'),
('notas', 'editar', 'Editar notas existentes'),
('notas', 'eliminar', 'Eliminar registros de notas'),
('notas', 'ver_todas', 'Ver notas de todos los cursos'),
('notas', 'editar_propias', 'Editar solo notas de materias asignadas');

-- Módulo: Reportes
INSERT INTO permisos (modulo, accion, descripcion) VALUES
('reportes', 'ver', 'Acceder al módulo de reportes'),
('reportes', 'boletines', 'Generar boletines de notas'),
('reportes', 'estadisticas', 'Ver estadísticas generales'),
('reportes', 'alergias', 'Ver reporte de estudiantes con alergias'),
('reportes', 'reprobados', 'Ver reporte de estudiantes reprobados'),
('reportes', 'exportar', 'Exportar reportes a PDF');

-- Módulo: Usuarios
INSERT INTO permisos (modulo, accion, descripcion) VALUES
('usuarios', 'ver', 'Ver listado de usuarios'),
('usuarios', 'crear', 'Crear nuevos usuarios'),
('usuarios', 'editar', 'Editar información de usuarios'),
('usuarios', 'eliminar', 'Eliminar usuarios'),
('usuarios', 'asignar_roles', 'Asignar roles a usuarios'),
('usuarios', 'gestionar_permisos', 'Gestionar permisos de roles');

-- Módulo: Cursos
INSERT INTO permisos (modulo, accion, descripcion) VALUES
('cursos', 'ver', 'Ver listado de cursos'),
('cursos', 'crear', 'Crear nuevos cursos'),
('cursos', 'editar', 'Editar información de cursos'),
('cursos', 'eliminar', 'Eliminar cursos');

-- Módulo: Materias
INSERT INTO permisos (modulo, accion, descripcion) VALUES
('materias', 'ver', 'Ver listado de materias'),
('materias', 'crear', 'Crear nuevas materias'),
('materias', 'editar', 'Editar información de materias'),
('materias', 'eliminar', 'Eliminar materias');

-- Módulo: Auditoría
INSERT INTO permisos (modulo, accion, descripcion) VALUES
('auditoria', 'ver', 'Ver logs de auditoría del sistema');

-- Módulo: Dashboard
INSERT INTO permisos (modulo, accion, descripcion) VALUES
('dashboard', 'administrativo', 'Acceder al dashboard administrativo'),
('dashboard', 'directivo', 'Acceder al dashboard directivo'),
('dashboard', 'maestro', 'Acceder al dashboard de maestro');

-- =============================================
-- 3. ASIGNACIÓN DE PERMISOS A ROLES
-- =============================================

-- ROL MAESTRO (Nivel 1) - Acceso limitado
INSERT INTO rol_permiso (id_rol, id_permiso) VALUES
-- Estudiantes (solo ver propios)
(1, (SELECT id_permiso FROM permisos WHERE modulo='estudiantes' AND accion='ver')),
(1, (SELECT id_permiso FROM permisos WHERE modulo='estudiantes' AND accion='ver_propios')),
-- Notas (registrar y editar solo las propias)
(1, (SELECT id_permiso FROM permisos WHERE modulo='notas' AND accion='ver')),
(1, (SELECT id_permiso FROM permisos WHERE modulo='notas' AND accion='registrar')),
(1, (SELECT id_permiso FROM permisos WHERE modulo='notas' AND accion='editar_propias')),
-- Reportes limitados
(1, (SELECT id_permiso FROM permisos WHERE modulo='reportes' AND accion='ver')),
(1, (SELECT id_permiso FROM permisos WHERE modulo='reportes' AND accion='boletines')),
(1, (SELECT id_permiso FROM permisos WHERE modulo='reportes' AND accion='alergias')),
-- Dashboard
(1, (SELECT id_permiso FROM permisos WHERE modulo='dashboard' AND accion='maestro'));

-- ROL DIRECTIVO (Nivel 2) - Solo lectura y reportes
INSERT INTO rol_permiso (id_rol, id_permiso) VALUES
-- Estudiantes (ver todos, sin edición)
(2, (SELECT id_permiso FROM permisos WHERE modulo='estudiantes' AND accion='ver')),
(2, (SELECT id_permiso FROM permisos WHERE modulo='estudiantes' AND accion='ver_todos')),
-- Notas (ver todas, sin edición)
(2, (SELECT id_permiso FROM permisos WHERE modulo='notas' AND accion='ver')),
(2, (SELECT id_permiso FROM permisos WHERE modulo='notas' AND accion='ver_todas')),
-- Reportes completos
(2, (SELECT id_permiso FROM permisos WHERE modulo='reportes' AND accion='ver')),
(2, (SELECT id_permiso FROM permisos WHERE modulo='reportes' AND accion='boletines')),
(2, (SELECT id_permiso FROM permisos WHERE modulo='reportes' AND accion='estadisticas')),
(2, (SELECT id_permiso FROM permisos WHERE modulo='reportes' AND accion='alergias')),
(2, (SELECT id_permiso FROM permisos WHERE modulo='reportes' AND accion='reprobados')),
(2, (SELECT id_permiso FROM permisos WHERE modulo='reportes' AND accion='exportar')),
-- Cursos y materias (solo ver)
(2, (SELECT id_permiso FROM permisos WHERE modulo='cursos' AND accion='ver')),
(2, (SELECT id_permiso FROM permisos WHERE modulo='materias' AND accion='ver')),
-- Dashboard
(2, (SELECT id_permiso FROM permisos WHERE modulo='dashboard' AND accion='directivo'));

-- ROL ADMINISTRATIVO (Nivel 3) - Acceso completo
INSERT INTO rol_permiso (id_rol, id_permiso) 
SELECT 3, id_permiso FROM permisos;

-- =============================================
-- 4. USUARIOS DE PRUEBA
-- =============================================
-- Contraseña para todos: "escuela2026"
-- Hash generado con password_hash('escuela2026', PASSWORD_BCRYPT)
-- Hash verificado y funcional

INSERT INTO usuarios (username, email, password_hash, nombre_completo, estado) VALUES
('admin', 'admin@pabloneruda.edu.co', '$2y$10$vSiuSCx2tBAcqV7BGy.tz.jB7FMpC0t09HGsA8yJuSRMV8HK2rxJ6', 'María Rodríguez García', 'activo'),
('director', 'director@pabloneruda.edu.co', '$2y$10$vSiuSCx2tBAcqV7BGy.tz.jB7FMpC0t09HGsA8yJuSRMV8HK2rxJ6', 'Carlos Alberto Mendoza', 'activo'),
('profesor', 'profesor@pabloneruda.edu.co', '$2y$10$vSiuSCx2tBAcqV7BGy.tz.jB7FMpC0t09HGsA8yJuSRMV8HK2rxJ6', 'Ana Patricia López', 'activo');

-- =============================================
-- 5. ASIGNACIÓN DE ROLES A USUARIOS
-- =============================================
INSERT INTO usuario_rol (id_usuario, id_rol) VALUES
(1, 3), -- admin tiene rol Administrativo
(2, 2), -- director tiene rol Directivo
(3, 1); -- profesor tiene rol Maestro

-- =============================================
-- 6. ASIGNACIÓN DE CURSOS A MAESTRO
-- =============================================
-- Asignar al profesor algunas materias en cursos específicos
INSERT INTO maestro_curso (id_usuario, id_curso, id_materia) VALUES
-- Profesor imparte Matemáticas en Primero A
(3, 2, (SELECT id_materia FROM materias WHERE nombre_materia='Matemáticas')),
-- Profesor imparte Español en Primero A
(3, 2, (SELECT id_materia FROM materias WHERE nombre_materia='Español')),
-- Profesor imparte Matemáticas en Quinto B
(3, 3, (SELECT id_materia FROM materias WHERE nombre_materia='Matemáticas'));

-- =============================================
-- 7. REGISTRO INICIAL DE AUDITORÍA
-- =============================================
INSERT INTO auditoria (id_usuario, accion, modulo, detalles, ip_address) VALUES
(1, 'INSTALACION_SISTEMA', 'sistema', 'Instalación inicial del sistema y creación de usuarios de prueba', '127.0.0.1');

-- =============================================
-- RESUMEN DE USUARIOS DE PRUEBA
-- =============================================
-- Usuario: admin
-- Contraseña: escuela2026
-- Rol: Administrativo (Acceso completo)
-- 
-- Usuario: director
-- Contraseña: escuela2026
-- Rol: Directivo (Consulta y reportes)
-- 
-- Usuario: profesor
-- Contraseña: escuela2026
-- Rol: Maestro (Limitado a sus cursos)
-- Cursos asignados: Primero A (Matemáticas, Español), Quinto B (Matemáticas)
-- =============================================
