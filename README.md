# Sistema de Gestión Escolar - Escuela Pablo Neruda

Sistema completo de gestión académica desarrollado en PHP nativo y MySQL para la Escuela Pablo Neruda (Barrio Las Malvinas, Sector 4 Berlín). Permite gestionar estudiantes desde preescolar hasta quinto grado, calificaciones, reportes y boletines.

## Stack Tecnológico

- **Backend:** PHP 8.x nativo
- **Base de Datos:** MySQL 8.x con PDO
- **Frontend:** HTML5, CSS3, JavaScript vanilla, Bootstrap 5
- **Arquitectura:** MVC con principios SOLID

## Instalación

### Requisitos

- PHP 8.0 o superior
- MySQL 8.0 o superior
- Extensiones PHP: PDO, pdo_mysql, mbstring, fileinfo

### Configuración

1. **Base de datos**
   ```bash
   # Crear base de datos ejecutando DB_PabloNeruda.sql
   # Opcional: Cargar datos_prueba.sql para datos de ejemplo
   ```

2. **Configurar conexión**
   
   Editar `config/database.php` si es necesario:
   ```php
   private string $host = 'localhost';
   private string $dbname = 'escuela_pablo_neruda';
   private string $username = 'root';
   private string $password = '';
   ```

3. **Permisos**
   ```bash
   # Windows
   icacls public\uploads /grant Everyone:F
   
   # Linux/Mac
   chmod 755 public/uploads
   ```

## Ejecución

### Con servidor PHP (Desarrollo)

```bash
php -S localhost:8000 -t public public/router.php
```

Acceder a: `http://localhost:8000/home`

### Con Apache (Producción)

1. Configurar VirtualHost apuntando a la carpeta `public`
2. Acceder según la configuración del VirtualHost

## Rutas del Sistema

- `/home` - Página principal pública con información institucional
- `/login` - Inicio de sesión (sin credenciales visibles)
- `/index.php` - Dashboard según rol después del login

## Credenciales de Acceso

Las credenciales no se muestran en el login por seguridad:

- **Administrativo:** admin / escuela2026
- **Directivo:** director / escuela2026
- **Maestro:** profesor / escuela2026

## Funcionalidades

### Gestión de Estudiantes

- Registro completo con documento PDF
- Control de capacidad (35 estudiantes por curso)
- Gestión de alergias
- Asociación con acudientes
- Búsqueda por documento, nombre o curso

### Sistema de Calificaciones

- 5 notas por materia por periodo
- 4 periodos por año escolar
- Escala 0.0 a 5.0
- Promedio y estado (Aprobado/Reprobado) calculados automáticamente
- Boletines de notas imprimibles

### Reportes

- Listado de estudiantes por curso
- Estudiantes con alergias (emergencias)
- Estudiantes reprobados por periodo
- Boletines individuales y por curso

### Sistema de Autenticación

- Login con usuario o email
- Roles: Administrativo, Directivo, Maestro
- Permisos específicos por rol
- Dashboard personalizado según rol
- Auditoría de acciones
- Bloqueo temporal tras intentos fallidos

### Gestión de Usuarios

- CRUD completo de usuarios
- Asignación de roles múltiples
- Cambio de contraseña
- Perfil de usuario

### Gestión de Cursos y Materias

- Administración de cursos por grado y sección
- Asignación de materias a cursos
- Control de jornadas (mañana/tarde)

## Estructura del Proyecto

```
School_PabloNeruda/
├── config/
│   ├── database.php          # Conexión PDO Singleton
│   ├── constants.php          # Constantes del sistema
│   └── autoload.php           # Autoloader de clases
├── src/
│   ├── Controllers/          # Controladores MVC
│   ├── Services/             # Lógica de negocio
│   ├── Repositories/         # Acceso a datos
│   ├── Models/               # Modelos de datos
│   ├── Validators/           # Validación de datos
│   ├── Middleware/           # Auth y permisos
│   └── Libraries/            # Librerías (FPDF)
├── public/
│   ├── router.php            # Router para servidor PHP
│   ├── home.php              # Página principal
│   ├── login.php             # Inicio de sesión
│   ├── index.php             # Front controller
│   ├── css/                  # Estilos
│   ├── js/                   # Scripts
│   └── uploads/              # Documentos PDF
├── views/
│   ├── layout/
│   │   ├── header.php        # Header autenticado
│   │   ├── header_public.php # Header público
│   │   └── footer.php        # Footer
│   ├── home/                 # Vista página principal
│   ├── auth/                 # Vistas de autenticación
│   ├── dashboard/            # Dashboards por rol
│   ├── estudiantes/          # CRUD estudiantes
│   ├── notas/                # Registro y consulta
│   ├── reportes/             # Reportes e impresión
│   ├── usuarios/             # Gestión usuarios
│   ├── cursos/               # Gestión cursos
│   └── materias/             # Gestión materias
├── DB_PabloNeruda.sql       # Estructura de BD
├── datos_prueba.sql          # Datos de ejemplo
└── README.md
```

## Arquitectura

### Principios SOLID

- **Single Responsibility:** Cada clase una responsabilidad
- **Open/Closed:** Extensible mediante herencia
- **Liskov Substitution:** Interfaces correctamente implementadas
- **Interface Segregation:** Interfaces específicas
- **Dependency Inversion:** Dependencias mediante interfaces

### Patrones de Diseño

- **Singleton:** Conexión a base de datos
- **Repository:** Acceso a datos
- **Service Layer:** Lógica de negocio
- **Front Controller:** Enrutamiento centralizado
- **MVC:** Separación de responsabilidades

## Base de Datos

### Tablas Principales

- `usuarios` - Usuarios del sistema
- `roles` - Roles disponibles
- `usuario_rol` - Relación usuarios-roles
- `permisos` - Permisos del sistema
- `rol_permiso` - Relación roles-permisos
- `estudiantes` - Información de estudiantes
- `cursos` - Cursos disponibles
- `materias` - Materias del plan de estudios
- `notas` - Calificaciones
- `periodos` - Periodos académicos
- `acudientes` - Padres/tutores
- `alergias_estudiante` - Alergias por estudiante
- `auditoria` - Registro de acciones
- `login_attempts` - Intentos de login

### Características

- Promedio y estado calculados automáticamente en MySQL
- Relaciones N:N con tablas pivote
- Cascada en eliminaciones
- Índices para optimizar búsquedas

## Seguridad

- Prepared statements (prevención SQL injection)
- Validación cliente y servidor
- Sanitización con `htmlspecialchars()`
- Validación MIME para archivos
- Límite de 2MB para uploads
- Hash de contraseñas con `password_hash()`
- Protección CSRF en formularios
- Bloqueo tras intentos fallidos de login

## Desarrollo

Para agregar funcionalidad:

1. Crear modelo en `src/Models/`
2. Crear repositorio en `src/Repositories/`
3. Crear servicio en `src/Services/`
4. Crear validador en `src/Validators/`
5. Crear controlador en `src/Controllers/`
6. Crear vistas en `views/`
7. Actualizar rutas en `public/index.php`

## Notas Importantes

- El promedio y estado se calculan automáticamente en MySQL
- Validar siempre en servidor, no solo en cliente
- El servidor PHP requiere `router.php` para rutas limpias
- En Apache usar el archivo `.htaccess` incluido

---

**Escuela Pablo Neruda**  
Barrio Las Malvinas, Sector 4 Berlín  
Sistema de Gestión Académica © 2026
