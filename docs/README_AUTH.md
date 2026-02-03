# Sistema de Autenticación y Control de Acceso - Escuela Pablo Neruda

## 📋 Descripción General

Sistema completo de gestión escolar con autenticación segura y control de acceso basado en roles (RBAC) implementado con PHP nativo y MySQL, siguiendo principios SOLID.

## 🔐 Características de Seguridad

### Autenticación
- ✅ Login con username/email y contraseña
- ✅ Contraseñas hasheadas con `password_hash()` usando bcrypt
- ✅ Sesiones seguras con regeneración de ID
- ✅ Límite de intentos fallidos (5 intentos → bloqueo 15 min)
- ✅ Timeout de sesión (30 minutos de inactividad)
- ✅ Protección CSRF (preparado para implementación)

### Control de Acceso (RBAC)
- ✅ Sistema de roles y permisos granular
- ✅ Verificación de permisos en cada acción
- ✅ Menú dinámico según permisos
- ✅ Dashboards diferenciados por rol
- ✅ Auditoría completa de acciones

## 👥 Roles del Sistema

### 1. Administrativo (Nivel 3)
**Acceso Completo**

#### Capacidades:
- ✅ CRUD completo de estudiantes y acudientes
- ✅ Registrar y modificar cualquier nota
- ✅ Gestionar cursos, materias y periodos
- ✅ **Crear, editar y eliminar usuarios**
- ✅ Asignar roles y permisos
- ✅ Acceso completo a reportes
- ✅ Ver logs de auditoría

### 2. Directivo (Nivel 2)
**Supervisión y Consulta**

#### Capacidades:
- ✅ Consultar todos los estudiantes (solo lectura)
- ✅ Ver todas las notas y promedios
- ✅ Generar y exportar reportes generales
- ✅ Ver estudiantes con alergias
- ✅ Consultar acudientes

#### Restricciones:
- ❌ No puede modificar estudiantes
- ❌ No puede registrar notas
- ❌ No puede gestionar usuarios

### 3. Maestro (Nivel 1)
**Operación Limitada**

#### Capacidades:
- ✅ Ver estudiantes de **SUS cursos** asignados
- ✅ Registrar y editar notas de **SUS materias**
- ✅ Consultar información de sus estudiantes
- ✅ Generar reportes individuales

#### Restricciones:
- ❌ No puede ver estudiantes de otros cursos
- ❌ No puede modificar notas de otras materias
- ❌ No puede eliminar registros
- ❌ No puede gestionar usuarios

## 🔑 Credenciales de Prueba

### Usuario Administrativo
```
Usuario: admin
Contraseña: escuela2026
Rol: Administrativo
Acceso: Completo
```

### Usuario Directivo
```
Usuario: director
Contraseña: escuela2026
Rol: Directivo
Acceso: Consulta y Reportes
```

### Usuario Maestro
```
Usuario: profesor
Contraseña: escuela2026
Rol: Maestro
Cursos Asignados: 
  - Primero A (Matemáticas, Español)
  - Quinto B (Matemáticas)
```

## 🚀 Instalación

### 1. Requisitos Previos
- PHP 8.0 o superior
- MySQL 8.0 o superior
- Apache con mod_rewrite
- Extensión PDO habilitada

### 2. Instalación de la Base de Datos

```bash
# 1. Crear la base de datos y estructura
mysql -u root -p < db/DB_PabloNeruda.sql

# 2. Cargar datos de autenticación (roles, permisos, usuarios)
mysql -u root -p escuela_pablo_neruda < db/datos_iniciales_auth.sql

# 3. (Opcional) Cargar datos de prueba
mysql -u root -p escuela_pablo_neruda < db/datos_prueba.sql
```

### 3. Configuración

1. **Copiar archivo de configuración:**
```bash
cp config/database.example.php config/database.php
```

2. **Editar `config/database.php`:**
```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'escuela_pablo_neruda');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
define('DB_CHARSET', 'utf8mb4');
```

3. **Configurar permisos de carpetas:**
```bash
# En Linux/Mac
chmod -R 755 public/
chmod -R 777 public/uploads/

# En Windows (desde CMD como administrador)
icacls public\uploads /grant Everyone:(OI)(CI)F /T
```

### 4. Acceder al Sistema

Abrir en el navegador:
```
http://localhost/School_PabloNeruda/public/login.php
```

## 📁 Estructura del Proyecto

```
School_PabloNeruda/
├── config/
│   ├── autoload.php          # Carga automática de clases
│   ├── constants.php          # Constantes del sistema
│   └── database.php           # Configuración BD
├── db/
│   ├── DB_PabloNeruda.sql    # Estructura completa
│   ├── datos_iniciales_auth.sql  # Roles, permisos, usuarios
│   └── datos_prueba.sql       # Datos de ejemplo
├── public/
│   ├── login.php              # Página de login
│   ├── logout.php             # Cerrar sesión
│   ├── index.php              # Página principal
│   ├── dashboard/
│   │   ├── admin.php
│   │   ├── directivo.php
│   │   └── maestro.php
│   └── usuarios/              # Gestión de usuarios
├── src/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── UsuarioController.php
│   │   ├── EstudianteController.php
│   │   ├── NotaController.php
│   │   └── ReporteController.php
│   ├── Middleware/
│   │   ├── AuthMiddleware.php
│   │   └── PermissionMiddleware.php
│   ├── Repositories/
│   │   ├── AuthRepository.php
│   │   ├── AuditoriaRepository.php
│   │   ├── LoginAttemptRepository.php
│   │   └── ...
│   ├── Services/
│   │   ├── AuthService.php
│   │   └── ...
│   └── Validators/
└── views/
    ├── auth/
    │   └── login.php
    ├── dashboard/
    │   ├── admin.php
    │   ├── directivo.php
    │   └── maestro.php
    ├── usuarios/
    ├── estudiantes/
    ├── notas/
    ├── reportes/
    ├── errors/
    │   └── 403.php
    └── layout/
        ├── header.php         # Menú dinámico
        └── footer.php
```

## 🔒 Flujo de Autenticación

### 1. Login
```
Usuario ingresa credenciales
      ↓
AuthService valida
      ↓
Verificar intentos fallidos (< 5)
      ↓
Verificar password_verify()
      ↓
Crear sesión + obtener roles y permisos
      ↓
Registrar en auditoría
      ↓
Redireccionar a dashboard según rol
```

### 2. Verificación de Permisos
```
Request a acción
      ↓
AuthMiddleware verifica sesión
      ↓
PermissionMiddleware verifica permisos
      ↓
Consulta: usuario → usuario_rol → rol → rol_permiso → permisos
      ↓
SI tiene permiso → Permitir acceso
      ↓
NO tiene permiso → Error 403 + log auditoría
```

## 🛡️ Seguridad Implementada

### Sesiones Seguras
```php
session.cookie_httponly = true   // No accesible desde JS
session.cookie_secure = true     // Solo HTTPS (producción)
Regeneración de ID tras login
Timeout: 30 minutos
```

### Protección contra Fuerza Bruta
```php
Máximo 5 intentos fallidos
Bloqueo temporal: 15 minutos
Registro en tabla intentos_login
```

### Validaciones
```php
✅ Prepared statements SIEMPRE
✅ Sanitización de inputs
✅ Validación en backend (nunca confiar en frontend)
✅ Verificación de permisos en CADA acción
```

## 📊 Sistema de Auditoría

### Acciones Registradas
- Login exitoso y fallido
- Creación/edición/eliminación de usuarios
- Creación/edición de estudiantes
- Registro y modificación de notas
- Cambios en roles y permisos
- Intentos de acceso no autorizado

### Consultar Logs
```php
// Solo usuarios con permiso 'auditoria' → 'ver'
http://localhost/School_PabloNeruda/public/auditoria
```

## 📚 Manual de Uso por Rol

### Administrativo
1. **Login** → Se redirige a `/dashboard/admin.php`
2. **Gestionar Usuarios:**
   - Ir a "Gestión" → "Usuarios"
   - Crear nuevo usuario con roles
   - Asignar cursos a maestros
3. **Ver Auditoría:**
   - "Gestión" → "Auditoría"
   - Filtrar por usuario, módulo o fecha

### Directivo
1. **Login** → Se redirige a `/dashboard/directivo.php`
2. **Ver Estadísticas:**
   - Dashboard muestra rendimiento por curso
   - Estudiantes en riesgo académico
3. **Generar Reportes:**
   - "Reportes" → Seleccionar tipo
   - Exportar a PDF

### Maestro
1. **Login** → Se redirige a `/dashboard/maestro.php`
2. **Ver Mis Cursos:**
   - Dashboard muestra cursos asignados
3. **Registrar Notas:**
   - "Notas" → "Registrar Notas"
   - Seleccionar curso y materia asignada
   - Solo puede editar sus materias

## 🔧 Configuración Avanzada

### Cambiar Tiempo de Sesión
```php
// src/Services/AuthService.php
private const SESSION_TIMEOUT = 1800; // 30 minutos (en segundos)
```

### Cambiar Límite de Intentos
```php
// src/Repositories/LoginAttemptRepository.php
private const MAX_INTENTOS = 5;
private const TIEMPO_BLOQUEO_MINUTOS = 15;
```

### Agregar Nuevos Permisos
```sql
-- Agregar permiso
INSERT INTO permisos (modulo, accion, descripcion) VALUES
('nuevo_modulo', 'nueva_accion', 'Descripción del permiso');

-- Asignar a rol
INSERT INTO rol_permiso (id_rol, id_permiso) VALUES
(1, LAST_INSERT_ID());
```

## 🐛 Solución de Problemas

### Error: "Sesión expirada"
**Causa:** Timeout de 30 minutos
**Solución:** Volver a iniciar sesión

### Error: "Usuario bloqueado"
**Causa:** 5 intentos fallidos
**Solución:** 
1. Esperar 15 minutos
2. O limpiar manualmente:
```sql
DELETE FROM intentos_login WHERE username = 'usuario';
```

### Error 403: Acceso Denegado
**Causa:** Sin permisos para la acción
**Solución:** Contactar administrador para asignar permisos

### No aparece opción en el menú
**Causa:** Rol no tiene el permiso
**Verificar:**
```sql
-- Ver permisos del rol
SELECT p.modulo, p.accion 
FROM permisos p
INNER JOIN rol_permiso rp ON p.id_permiso = rp.id_permiso
WHERE rp.id_rol = 1; -- Cambiar ID del rol
```

## 📈 Monitoreo y Mantenimiento

### Limpiar Intentos Antiguos
```php
$loginAttemptRepo = new LoginAttemptRepository();
$loginAttemptRepo->limpiarIntentosAntiguos(30); // 30 días
```

### Ver Estadísticas de Auditoría
```php
$auditoriaRepo = new AuditoriaRepository();
$stats = $auditoriaRepo->getEstadisticasPorUsuario($userId, 30);
```

## 🎯 Casos de Uso Principales

### Caso 1: Maestro Registra Notas
1. Login como `profesor` / `escuela2026`
2. Dashboard → "Mis Cursos" → Seleccionar "Primero A"
3. Click en "Registrar Notas"
4. Seleccionar materia: "Matemáticas"
5. Ingresar 5 notas por estudiante
6. Sistema calcula promedio automático
7. Ver estado (aprobado/reprobado)

### Caso 2: Administrativo Crea Usuario Maestro
1. Login como `admin` / `escuela2026`
2. "Gestión" → "Usuarios" → "Nuevo Usuario"
3. Completar: username, email, contraseña, nombre
4. Seleccionar rol "Maestro"
5. Asignar cursos y materias específicas
6. Guardar → Sistema registra en auditoría

### Caso 3: Directivo Genera Reporte
1. Login como `director` / `escuela2026`
2. "Reportes" → "Boletines de Notas"
3. Seleccionar curso y periodo
4. Sistema genera PDF
5. Descargar e imprimir

## 📞 Soporte

Para problemas o preguntas:
- Revisar logs de auditoría
- Verificar permisos en la base de datos
- Contactar al desarrollador del sistema

## 📝 Licencia

Sistema desarrollado para la Escuela Pablo Neruda.
Todos los derechos reservados.

---

**Versión:** 1.0.0  
**Fecha:** Febrero 2026  
**Desarrollador:** Sistema de Gestión Escolar SOLID
