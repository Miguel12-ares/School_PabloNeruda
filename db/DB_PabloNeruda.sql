-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS escuela_pablo_neruda;
USE escuela_pablo_neruda;

-- =============================================
-- 1. Tabla CURSOS
-- =============================================
CREATE TABLE cursos (
    id_curso         INT AUTO_INCREMENT PRIMARY KEY,
    nombre_curso     VARCHAR(50) NOT NULL COMMENT 'Ej: Preescolar, Primero A, Quinto B',
    grado            INT NOT NULL COMMENT '0 = Preescolar, 1 a 5 = primaria',
    seccion          VARCHAR(10) NULL COMMENT 'A, B, C, etc. o NULL si no aplica',
    capacidad_maxima INT NOT NULL DEFAULT 35,
    jornada          ENUM('mañana', 'tarde') NOT NULL,
    
    CONSTRAINT chk_grado CHECK (grado BETWEEN 0 AND 5),
    INDEX idx_grado_seccion (grado, seccion)
);

-- =============================================
-- 2. Tabla MATERIAS
-- =============================================
CREATE TABLE materias (
    id_materia    INT AUTO_INCREMENT PRIMARY KEY,
    nombre_materia VARCHAR(100) NOT NULL,
    estado        BOOLEAN NOT NULL DEFAULT TRUE,
    UNIQUE KEY uk_nombre_materia (nombre_materia)
);

-- =============================================
-- 3. Relación N:N entre cursos y materias
-- =============================================
CREATE TABLE curso_materia (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    id_curso    INT NOT NULL,
    id_materia  INT NOT NULL,
    
    CONSTRAINT fk_cm_curso    FOREIGN KEY (id_curso)   REFERENCES cursos(id_curso)   ON DELETE RESTRICT,
    CONSTRAINT fk_cm_materia  FOREIGN KEY (id_materia) REFERENCES materias(id_materia) ON DELETE RESTRICT,
    
    UNIQUE KEY uk_curso_materia (id_curso, id_materia)
);

-- =============================================
-- 4. Tabla ESTUDIANTES
-- =============================================
CREATE TABLE estudiantes (
    id_estudiante     INT AUTO_INCREMENT PRIMARY KEY,
    registro_civil    VARCHAR(50) UNIQUE NOT NULL,
    tarjeta_identidad VARCHAR(50) UNIQUE NULL,
    nombre            VARCHAR(100) NOT NULL,
    apellido          VARCHAR(100) NOT NULL,
    edad              INT NOT NULL,
    documento_pdf     VARCHAR(255) NULL COMMENT 'ruta/nombre del archivo PDF',
    id_curso          INT NOT NULL,
    tiene_alergias    BOOLEAN NOT NULL DEFAULT FALSE,
    jornada           ENUM('mañana', 'tarde') NOT NULL,
    fecha_registro    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_estudiante_curso FOREIGN KEY (id_curso) REFERENCES cursos(id_curso) ON DELETE RESTRICT,
    
    INDEX idx_nombre_completo (nombre, apellido),
    INDEX idx_registro_civil (registro_civil)
);

-- =============================================
-- 5. Tabla ALERGIAS (por estudiante)
-- =============================================
CREATE TABLE alergias_estudiante (
    id_alergia     INT AUTO_INCREMENT PRIMARY KEY,
    id_estudiante  INT NOT NULL,
    tipo_alergia   VARCHAR(200) NOT NULL,
    
    CONSTRAINT fk_alergia_estudiante FOREIGN KEY (id_estudiante) REFERENCES estudiantes(id_estudiante) ON DELETE CASCADE,
    
    UNIQUE KEY uk_alergia_por_estudiante (id_estudiante, tipo_alergia)
);

-- =============================================
-- 6. Tabla ACUDIENTES
-- =============================================
CREATE TABLE acudientes (
    id_acudiente  INT AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(100) NOT NULL,
    telefono      VARCHAR(20) NOT NULL,
    parentesco    VARCHAR(50) NOT NULL
);

-- =============================================
-- 7. Relación N:N estudiantes – acudientes
-- =============================================
CREATE TABLE estudiante_acudiente (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    id_estudiante INT NOT NULL,
    id_acudiente  INT NOT NULL,
    es_principal  BOOLEAN NOT NULL DEFAULT FALSE,
    
    CONSTRAINT fk_ea_estudiante FOREIGN KEY (id_estudiante) REFERENCES estudiantes(id_estudiante) ON DELETE CASCADE,
    CONSTRAINT fk_ea_acudiente  FOREIGN KEY (id_acudiente)  REFERENCES acudientes(id_acudiente)   ON DELETE RESTRICT,
    
    UNIQUE KEY uk_estudiante_acudiente (id_estudiante, id_acudiente),
    INDEX idx_principal (id_estudiante, es_principal)
);

-- =============================================
-- 8. Tabla CONVIVENCIA FAMILIAR (1:1 con estudiante)
-- =============================================
CREATE TABLE convivencia_familiar (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    id_estudiante INT NOT NULL UNIQUE,
    cantidad_hermanos INT NOT NULL DEFAULT 0,
    con_quien_vive    VARCHAR(200) NOT NULL,
    
    CONSTRAINT fk_convivencia_estudiante FOREIGN KEY (id_estudiante) REFERENCES estudiantes(id_estudiante) ON DELETE CASCADE
);

-- =============================================
-- 9. Tabla PERIODOS / Bimestres
-- =============================================
CREATE TABLE periodos (
    id_periodo     INT AUTO_INCREMENT PRIMARY KEY,
    numero_periodo INT NOT NULL COMMENT '1,2,3,4',
    anio_lectivo   YEAR NOT NULL,
    fecha_inicio   DATE NOT NULL,
    fecha_fin      DATE NOT NULL,
    
    CONSTRAINT chk_periodo CHECK (numero_periodo BETWEEN 1 AND 4),
    UNIQUE KEY uk_periodo_anio (numero_periodo, anio_lectivo)
);

-- =============================================
-- 10. Tabla NOTAS
-- =============================================
CREATE TABLE notas (
    id_nota       INT AUTO_INCREMENT PRIMARY KEY,
    id_estudiante INT NOT NULL,
    id_materia    INT NOT NULL,
    id_periodo    INT NOT NULL,
    
    nota_1  DECIMAL(3,1) NULL CHECK (nota_1  BETWEEN 0.0 AND 5.0),
    nota_2  DECIMAL(3,1) NULL CHECK (nota_2  BETWEEN 0.0 AND 5.0),
    nota_3  DECIMAL(3,1) NULL CHECK (nota_3  BETWEEN 0.0 AND 5.0),
    nota_4  DECIMAL(3,1) NULL CHECK (nota_4  BETWEEN 0.0 AND 5.0),
    nota_5  DECIMAL(3,1) NULL CHECK (nota_5  BETWEEN 0.0 AND 5.0),
    
    promedio DECIMAL(3,1) GENERATED ALWAYS AS (
        ROUND(
            (COALESCE(nota_1,0) + COALESCE(nota_2,0) + COALESCE(nota_3,0) + 
             COALESCE(nota_4,0) + COALESCE(nota_5,0)) /
            GREATEST(
                (nota_1 IS NOT NULL) + (nota_2 IS NOT NULL) + (nota_3 IS NOT NULL) +
                (nota_4 IS NOT NULL) + (nota_5 IS NOT NULL),
                1
            ),
            1
        )
    ) STORED,
    
    estado ENUM('aprobado', 'reprobado') GENERATED ALWAYS AS (
        CASE 
            WHEN promedio >= 3.0 THEN 'aprobado'
            ELSE 'reprobado'
        END
    ) STORED,
    
    CONSTRAINT fk_notas_estudiante FOREIGN KEY (id_estudiante) REFERENCES estudiantes(id_estudiante) ON DELETE CASCADE,
    CONSTRAINT fk_notas_materia    FOREIGN KEY (id_materia)    REFERENCES materias(id_materia)    ON DELETE RESTRICT,
    CONSTRAINT fk_notas_periodo    FOREIGN KEY (id_periodo)    REFERENCES periodos(id_periodo)    ON DELETE RESTRICT,
    
    UNIQUE KEY uk_nota_unica (id_estudiante, id_materia, id_periodo),
    INDEX idx_estudiante_periodo (id_estudiante, id_periodo)
);

-- Tabla USUARIOS
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(150) NOT NULL,
    estado ENUM('activo', 'inactivo', 'bloqueado') DEFAULT 'activo',
    ultimo_acceso TIMESTAMP NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
);

-- Tabla ROLES
CREATE TABLE roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(50) UNIQUE NOT NULL,
    descripcion TEXT,
    nivel_acceso INT NOT NULL COMMENT '1=bajo, 2=medio, 3=alto'
);

-- Tabla PERMISOS (módulos del sistema)
CREATE TABLE permisos (
    id_permiso INT AUTO_INCREMENT PRIMARY KEY,
    modulo VARCHAR(50) NOT NULL,
    accion VARCHAR(50) NOT NULL,
    descripcion VARCHAR(200),
    UNIQUE KEY uk_modulo_accion (modulo, accion)
);

-- Tabla ROL_PERMISO (N:N)
CREATE TABLE rol_permiso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_rol INT NOT NULL,
    id_permiso INT NOT NULL,
    CONSTRAINT fk_rp_rol FOREIGN KEY (id_rol) REFERENCES roles(id_rol) ON DELETE CASCADE,
    CONSTRAINT fk_rp_permiso FOREIGN KEY (id_permiso) REFERENCES permisos(id_permiso) ON DELETE CASCADE,
    UNIQUE KEY uk_rol_permiso (id_rol, id_permiso)
);

-- Tabla USUARIO_ROL (N:N - un usuario puede tener múltiples roles)
CREATE TABLE usuario_rol (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_rol INT NOT NULL,
    fecha_asignacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_ur_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    CONSTRAINT fk_ur_rol FOREIGN KEY (id_rol) REFERENCES roles(id_rol) ON DELETE RESTRICT,
    UNIQUE KEY uk_usuario_rol (id_usuario, id_rol)
);

-- Tabla SESIONES (opcional para mayor seguridad)
CREATE TABLE sesiones (
    id_sesion VARCHAR(128) PRIMARY KEY,
    id_usuario INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    fecha_inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_expiracion TIMESTAMP NOT NULL,
    CONSTRAINT fk_sesion_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    INDEX idx_expiracion (fecha_expiracion)
);

-- Tabla AUDITORIA (registro de acciones críticas)
CREATE TABLE auditoria (
    id_auditoria INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    accion VARCHAR(100) NOT NULL,
    modulo VARCHAR(50) NOT NULL,
    detalles TEXT,
    ip_address VARCHAR(45),
    fecha_accion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_auditoria_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT,
    INDEX idx_fecha (fecha_accion),
    INDEX idx_usuario_accion (id_usuario, accion)
);

-- Tabla MAESTRO_CURSO (asignación de cursos a maestros)
CREATE TABLE maestro_curso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_curso INT NOT NULL,
    id_materia INT NOT NULL,
    fecha_asignacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_mc_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    CONSTRAINT fk_mc_curso FOREIGN KEY (id_curso) REFERENCES cursos(id_curso) ON DELETE CASCADE,
    CONSTRAINT fk_mc_materia FOREIGN KEY (id_materia) REFERENCES materias(id_materia) ON DELETE CASCADE,
    UNIQUE KEY uk_maestro_curso_materia (id_usuario, id_curso, id_materia)
);

-- Tabla INTENTOS_LOGIN (control de intentos fallidos)
CREATE TABLE intentos_login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    fecha_intento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    exitoso BOOLEAN NOT NULL DEFAULT FALSE,
    INDEX idx_username_fecha (username, fecha_intento),
    INDEX idx_ip_fecha (ip_address, fecha_intento)
);

-- =============================================
-- Datos iniciales de ejemplo (opcional)
-- =============================================

-- Materias básicas
INSERT INTO materias (nombre_materia) VALUES
('Matemáticas'), ('Español'), ('Informática'), ('Inglés'), ('Religión'),
('Ética'), ('Ciencias Naturales'), ('Tecnología'), ('Educación Artística'),
('Educación Física'), ('Ciencias Sociales');

-- Ejemplo de cursos (puedes agregar más)
INSERT INTO cursos (nombre_curso, grado, seccion, jornada) VALUES
('Preescolar', 0, NULL, 'mañana'),
('Primero A', 1, 'A', 'mañana'),
('Quinto B', 5, 'B', 'tarde');