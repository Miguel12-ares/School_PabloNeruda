# Sistema de Gestión Escolar - Escuela Pablo Neruda

Sistema completo de gestión académica desarrollado en PHP nativo y MySQL, siguiendo principios SOLID y arquitectura limpia.

## 📋 Descripción

La Escuela Pablo Neruda (Barrio Las Malvinas, Sector 4 Berlín) requiere digitalizar la gestión académica de estudiantes desde preescolar hasta grado quinto. Este sistema reemplaza el manejo manual en cuadernos físicos, eliminando errores, pérdida de información y demoras en búsquedas críticas.

## 🚀 Características Principales

### Gestión de Estudiantes
- ✅ Registro completo con documento de identidad (PDF)
- ✅ Control de capacidad máxima por curso (35 estudiantes)
- ✅ Gestión de alergias para emergencias
- ✅ Asociación con acudientes (padre/madre)
- ✅ Información de convivencia familiar
- ✅ Búsqueda rápida por documento, nombre o curso

### Sistema de Calificaciones
- ✅ 5 notas por materia por periodo (4 periodos/año)
- ✅ Escala de 0.0 a 5.0
- ✅ Cálculo automático de promedio en base de datos
- ✅ Estado automático: Aprobado (≥3.0) / Reprobado (<3.0)
- ✅ Boletines de notas imprimibles

### Reportes
- ✅ Listado de estudiantes por curso
- ✅ Estudiantes con alergias (reporte de emergencia)
- ✅ Estudiantes reprobados por periodo
- ✅ Boletines individuales y por curso

## 🛠️ Stack Tecnológico

- **Backend:** PHP 8.x nativo (sin frameworks)
- **Base de Datos:** MySQL 8.x con PDO
- **Frontend:** HTML5 + CSS3 + JavaScript vanilla
- **UI Framework:** Bootstrap 5
- **Servidor:** Apache (XAMPP/WAMP)

## 📁 Estructura del Proyecto

```
escuela-pablo-neruda/
├── config/
│   ├── database.php          # Singleton PDO
│   ├── constants.php          # Constantes del sistema
│   └── autoload.php           # Autoloader de clases
├── src/
│   ├── Interfaces/
│   │   ├── RepositoryInterface.php
│   │   └── ValidatorInterface.php
│   ├── Models/
│   │   ├── Estudiante.php
│   │   ├── Curso.php
│   │   ├── Materia.php
│   │   ├── Acudiente.php
│   │   ├── Nota.php
│   │   └── Periodo.php
│   ├── Repositories/
│   │   ├── BaseRepository.php
│   │   ├── EstudianteRepository.php
│   │   ├── CursoRepository.php
│   │   ├── MateriaRepository.php
│   │   ├── AcudienteRepository.php
│   │   ├── NotaRepository.php
│   │   ├── PeriodoRepository.php
│   │   └── AlergiaRepository.php
│   ├── Services/
│   │   ├── EstudianteService.php
│   │   ├── NotaService.php
│   │   ├── CursoService.php
│   │   ├── MateriaService.php
│   │   ├── PeriodoService.php
│   │   └── AcudienteService.php
│   ├── Validators/
│   │   ├── EstudianteValidator.php
│   │   ├── NotaValidator.php
│   │   └── AcudienteValidator.php
│   └── Controllers/
│       ├── EstudianteController.php
│       ├── NotaController.php
│       └── ReporteController.php
├── public/
│   ├── index.php              # Front controller
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── main.js
│   └── uploads/               # PDFs de documentos
├── views/
│   ├── layout/
│   │   ├── header.php
│   │   └── footer.php
│   ├── estudiantes/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── view.php
│   ├── notas/
│   │   ├── index.php
│   │   ├── registrar.php
│   │   └── boletin.php
│   └── reportes/
│       ├── index.php
│       ├── estudiantes_por_curso.php
│       ├── estudiantes_alergias.php
│       ├── estudiantes_reprobados.php
│       └── boletines.php
├── DB_PabloNeruda.sql         # Estructura de base de datos
├── datos_prueba.sql           # Datos de prueba
└── README.md
```

## 📦 Instalación

### Requisitos Previos

- PHP 8.0 o superior
- MySQL 8.0 o superior
- Apache (XAMPP, WAMP, LAMP, o similar)
- Extensiones PHP: PDO, pdo_mysql, mbstring, fileinfo

### Pasos de Instalación

1. **Clonar o descargar el proyecto**
   ```bash
   cd C:\xampp\htdocs\
   # Copiar la carpeta del proyecto aquí
   ```

2. **Crear la base de datos**
   ```bash
   # Abrir phpMyAdmin o MySQL Workbench
   # Ejecutar el archivo DB_PabloNeruda.sql
   ```

3. **Configurar la conexión a la base de datos**
   
   Editar `config/database.php` si es necesario:
   ```php
   private string $host = 'localhost';
   private string $dbname = 'escuela_pablo_neruda';
   private string $username = 'root';
   private string $password = '';
   ```

4. **Configurar permisos de la carpeta uploads**
   ```bash
   # En Windows (desde la carpeta del proyecto)
   icacls public\uploads /grant Everyone:F
   
   # En Linux/Mac
   chmod 755 public/uploads
   ```

5. **Cargar datos de prueba (opcional)**
   ```bash
   # Ejecutar el archivo datos_prueba.sql en phpMyAdmin
   ```

6. **Acceder al sistema**
   ```
   http://localhost/School_PabloNeruda/public/
   ```

## 🎯 Uso del Sistema

### Módulo de Estudiantes

1. **Registrar Estudiante:**
   - Ir a "Estudiantes" → "Nuevo Estudiante"
   - Completar formulario con datos personales
   - Subir documento PDF (opcional, máx 2MB)
   - Marcar si tiene alergias y especificarlas
   - Guardar

2. **Buscar Estudiante:**
   - Usar el buscador en la página principal
   - Buscar por documento, nombre o apellido

3. **Editar/Ver Detalles:**
   - Clic en los botones de acción en la tabla
   - Ver información completa incluyendo acudientes

### Módulo de Notas

1. **Registrar Notas:**
   - Ir a "Notas" → Seleccionar curso y periodo
   - Ingresar calificaciones (0.0 a 5.0)
   - Guardar cada fila individualmente
   - El promedio y estado se calculan automáticamente

2. **Consultar Boletín:**
   - Ir a "Reportes" → "Boletines de Notas"
   - Seleccionar curso y periodo
   - Ver boletín individual
   - Opción de imprimir

### Módulo de Reportes

1. **Estudiantes por Curso:**
   - Listado completo con información de contacto
   - Opción de impresión

2. **Estudiantes con Alergias:**
   - Reporte de emergencia
   - Información crítica para personal

3. **Estudiantes Reprobados:**
   - Filtrar por periodo
   - Ver cantidad de materias reprobadas

## 🔒 Seguridad

- ✅ Prepared statements (prevención de SQL injection)
- ✅ Validación de datos en cliente y servidor
- ✅ Sanitización de inputs con `htmlspecialchars()`
- ✅ Validación de tipo MIME para archivos
- ✅ Límite de tamaño de archivos (2MB)
- ✅ Nombres únicos para archivos subidos (hash)

## 🏗️ Arquitectura

### Principios SOLID Aplicados

1. **Single Responsibility:** Cada clase tiene una única responsabilidad
2. **Open/Closed:** Extensible mediante herencia (BaseRepository)
3. **Liskov Substitution:** Interfaces implementadas correctamente
4. **Interface Segregation:** Interfaces específicas y pequeñas
5. **Dependency Inversion:** Dependencias mediante interfaces

### Patrones de Diseño

- **Singleton:** Conexión a base de datos
- **Repository:** Acceso a datos
- **Service Layer:** Lógica de negocio
- **Front Controller:** Enrutamiento centralizado
- **MVC:** Separación de responsabilidades

## 📊 Base de Datos

### Tablas Principales

- `estudiantes` - Información de estudiantes
- `cursos` - Cursos disponibles
- `materias` - Materias del plan de estudios
- `notas` - Calificaciones (con promedio y estado calculados)
- `periodos` - Periodos académicos
- `acudientes` - Padres/tutores
- `alergias_estudiante` - Alergias por estudiante
- `convivencia_familiar` - Información familiar

### Características Especiales

- Promedio y estado calculados automáticamente en MySQL
- Relaciones N:N entre cursos-materias y estudiantes-acudientes
- Cascada en eliminaciones donde corresponde
- Índices para optimizar búsquedas

## 🧪 Datos de Prueba

El sistema incluye 6 estudiantes de prueba con:
- Información completa
- Acudientes asociados
- Alergias (algunos)
- Notas del primer periodo
- Casos de aprobados y reprobados

## 🐛 Solución de Problemas

### Error de conexión a la base de datos
- Verificar que MySQL esté ejecutándose
- Revisar credenciales en `config/database.php`
- Confirmar que la base de datos existe

### No se pueden subir archivos
- Verificar permisos de `public/uploads`
- Revisar `php.ini`: `upload_max_filesize` y `post_max_size`

### Errores de autoload
- Verificar que todas las clases estén en sus carpetas correctas
- Nombres de archivo deben coincidir con nombres de clase

## 📝 Notas Importantes

- El promedio y estado de notas se calculan **automáticamente en MySQL**
- No calcular promedios en PHP, leer directamente de la BD
- Validar siempre en servidor, nunca confiar solo en validación cliente
- Mantener actualizado el reporte de alergias para emergencias

## 👨‍💻 Desarrollo

### Agregar Nueva Funcionalidad

1. Crear modelo en `src/Models/`
2. Crear repositorio en `src/Repositories/`
3. Crear servicio en `src/Services/`
4. Crear validador en `src/Validators/`
5. Crear controlador en `src/Controllers/`
6. Crear vistas en `views/`
7. Actualizar rutas en `public/index.php`

## 📄 Licencia

Este proyecto fue desarrollado para la Escuela Pablo Neruda como sistema interno de gestión académica.

## 📞 Soporte

Para soporte o consultas sobre el sistema, contactar al administrador del sistema.

---

**Escuela Pablo Neruda**  
Barrio Las Malvinas, Sector 4 Berlín  
Sistema de Gestión Académica © 2026

