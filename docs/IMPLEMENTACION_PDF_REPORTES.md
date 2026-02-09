# Implementación de Generación de PDFs para Reportes

## Fecha
8 de Febrero de 2026

## Resumen
Se ha implementado la funcionalidad de generación de PDFs para los tres reportes principales del sistema, reemplazando el botón de "Imprimir" por un botón de "Descargar PDF" que genera archivos PDF profesionales con los colores corporativos de la escuela.

## Cambios Realizados

### 1. Colores Actualizados
Se actualizaron todos los reportes para usar el color azul oscuro primario (#1e3a5f) en lugar de colores personalizados:

- **Estudiantes Reprobados**: Cambió de amarillo (#ffc107) a azul primario
- **Estudiantes por Curso**: Cambió de azul claro (#0dcaf0) a azul primario  
- **Alergias (Emergencias)**: Cambió de rojo (#dc3545) a azul primario

#### Archivos Modificados:
- `views/reportes/estudiantes_reprobados.php`
- `views/reportes/estudiantes_por_curso.php`
- `views/reportes/estudiantes_alergias.php`

### 2. Librería FPDF
Se instaló la librería FPDF v1.86 en el directorio:
- `src/Libraries/fpdf.php` (librería principal)
- `src/Libraries/font/` (fuentes incluidas)
- `src/Libraries/PdfGenerator.php` (clase personalizada)

### 3. Clase PdfGenerator
Se creó una clase personalizada `PdfGenerator` que extiende FPDF con las siguientes características:

#### Funcionalidades:
- **Encabezado personalizado**: Logo y título de la institución con fondo azul
- **Pie de página**: Información de la escuela, fecha y numeración de páginas
- **Títulos de sección**: Con fondo azul primario
- **Tablas**: Con encabezados coloreados y datos alternados
- **Cajas de alerta**: Para información, éxito, advertencia y peligro
- **Cajas de estadísticas**: Para mostrar métricas importantes
- **Manejo automático de páginas**: Repite encabezados en páginas múltiples
- **Codificación UTF-8**: Soporte para caracteres especiales españoles

#### Métodos principales:
```php
- setHeaderText($text): Configura el texto del encabezado
- addSectionTitle($title): Agrega un título de sección
- addSubtitle($text): Agrega un subtítulo
- addText($text): Agrega texto normal con soporte multilinea
- addInfoCard($label, $value): Muestra información en formato etiqueta:valor
- createTable($headers, $data, $widths): Crea tablas con datos
- addAlertBox($text, $type): Agrega cajas de alerta/información
- addStatBox($label, $value, $width, $x): Agrega cajas de estadísticas
```

### 4. Métodos del Controlador
Se agregaron tres nuevos métodos en `ReporteController`:

#### `estudiantes_reprobados_pdf()`
Genera PDF con:
- Información del periodo académico
- Estadísticas (estudiantes afectados, materias reprobadas)
- Tabla detallada con notas individuales
- Orientación horizontal (landscape)

**URL**: `index.php?controller=reporte&action=estudiantes_reprobados_pdf&id_periodo=X&id_curso=Y`

#### `estudiantes_por_curso_pdf()`
Genera PDF con:
- Información del curso
- Capacidad y matriculados
- Tabla con datos de estudiantes y acudientes
- Orientación vertical (portrait)

**URL**: `index.php?controller=reporte&action=estudiantes_por_curso_pdf&id_curso=X`

#### `estudiantes_alergias_pdf()`
Genera PDF con:
- Alerta de emergencia
- Estadísticas de estudiantes con alergias
- Tabla con detalles de alergias
- Protocolo de emergencia
- Orientación horizontal (landscape)

**URL**: `index.php?controller=reporte&action=estudiantes_alergias_pdf&id_curso=X`

### 5. Actualizaciones en las Vistas
Se reemplazaron los botones de impresión por botones de descarga PDF:

#### Antes:
```html
<button onclick="window.print()" class="btn btn-warning text-dark">
    <i class="fas fa-print"></i> Imprimir Reporte
</button>
```

#### Después:
```html
<a href="index.php?controller=reporte&action=estudiantes_reprobados_pdf&id_periodo=<?= $id_periodo ?>" 
   class="btn btn-danger" target="_blank">
    <i class="fas fa-file-pdf"></i> Descargar PDF
</a>
```

## Características del PDF Generado

### Diseño Visual
- **Encabezado**: Fondo azul corporativo (#1e3a5f) con nombre de la escuela
- **Tablas**: Encabezados azules con datos alternados (gris claro/blanco)
- **Tipografía**: Arial para compatibilidad universal
- **Márgenes**: 15mm en todos los lados
- **Tamaño**: Carta (Letter)

### Contenido
- Información completa sin pérdida de datos
- Todos los campos de las tablas web incluidos
- Fechas y hora de generación
- Numeración automática de páginas
- Información de contacto de la escuela

### Seguridad y Permisos
- Todos los métodos PDF verifican permisos del usuario
- Usa el mismo sistema de middleware que las vistas web
- Requiere sesión activa

## Ventajas de la Implementación

1. **Sin Dependencias Externas**: FPDF no requiere extensiones especiales de PHP
2. **Profesional**: PDFs con diseño consistente y corporativo
3. **Portable**: Los PDFs se pueden compartir fácilmente
4. **Completo**: Incluye toda la información de los reportes web
5. **Personalizable**: Fácil de modificar colores, fuentes y diseño
6. **Multilingüe**: Soporte para caracteres españoles (tildes, ñ, etc.)
7. **Automático**: Manejo de páginas múltiples sin intervención manual

## Archivos Creados/Modificados

### Creados:
- `src/Libraries/fpdf.php` (librería FPDF)
- `src/Libraries/PdfGenerator.php` (clase personalizada)
- `src/Libraries/font/` (directorio con fuentes)
- `docs/IMPLEMENTACION_PDF_REPORTES.md` (este documento)

### Modificados:
- `src/Controllers/ReporteController.php` (3 nuevos métodos)
- `views/reportes/estudiantes_reprobados.php` (colores + botón PDF)
- `views/reportes/estudiantes_por_curso.php` (colores + botón PDF)
- `views/reportes/estudiantes_alergias.php` (colores + botón PDF)

## Uso

### Para Descargar un Reporte:
1. Acceder al reporte deseado desde el menú
2. Aplicar los filtros necesarios (periodo, curso, etc.)
3. Hacer clic en el botón "Descargar PDF"
4. El archivo PDF se descargará automáticamente

### Para Desarrolladores:
Para agregar un nuevo reporte PDF:

1. Crear método en `ReporteController` con sufijo `_pdf()`
2. Importar las librerías necesarias:
```php
require_once __DIR__ . '/../Libraries/fpdf.php';
require_once __DIR__ . '/../Libraries/PdfGenerator.php';
```
3. Crear instancia de PdfGenerator y configurar:
```php
$pdf = new PdfGenerator();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->setHeaderText('Título del Reporte');
```
4. Agregar contenido usando los métodos disponibles
5. Generar salida:
```php
$pdf->Output('D', 'nombre_archivo.pdf');
exit;
```

## Notas Técnicas

- **Orientación**: Use 'P' para vertical, 'L' para horizontal
- **Codificación**: Use `utf8_decode()` para textos con tildes
- **Paginación**: El sistema maneja automáticamente múltiples páginas
- **Memoria**: Los PDFs se generan en memoria y luego se descargan
- **Performance**: Optimizado para reportes con cientos de registros

## Soporte

Para problemas o mejoras, contactar al equipo de desarrollo.

---
**Escuela Pablo Neruda**  
*Sistema de Gestión Académica*
