# Guía de Instalación Rápida
## Sistema Escuela Pablo Neruda

### Paso 1: Requisitos
- XAMPP 8.0+ (incluye PHP 8.0+ y MySQL 8.0+)
- Navegador web moderno (Chrome, Firefox, Edge)

### Paso 2: Instalar XAMPP
1. Descargar XAMPP desde: https://www.apachefriends.org/
2. Instalar en `C:\xampp\`
3. Iniciar Apache y MySQL desde el Panel de Control de XAMPP

### Paso 3: Copiar Archivos
1. Copiar la carpeta `School_PabloNeruda` a `C:\xampp\htdocs\`
2. La ruta final debe ser: `C:\xampp\htdocs\School_PabloNeruda\`

### Paso 4: Crear Base de Datos
1. Abrir navegador y ir a: `http://localhost/phpmyadmin`
2. Clic en "Nueva" para crear base de datos
3. Nombre: `escuela_pablo_neruda`
4. Cotejamiento: `utf8mb4_general_ci`
5. Clic en "Crear"

### Paso 5: Importar Estructura
1. Seleccionar la base de datos `escuela_pablo_neruda`
2. Ir a la pestaña "Importar"
3. Clic en "Seleccionar archivo"
4. Buscar y seleccionar: `School_PabloNeruda\DB_PabloNeruda.sql`
5. Clic en "Continuar" al final de la página
6. Esperar mensaje de éxito

### Paso 6: Importar Datos de Prueba (Opcional)
1. Ir nuevamente a "Importar"
2. Seleccionar: `School_PabloNeruda\datos_prueba.sql`
3. Clic en "Continuar"
4. Esperar confirmación

### Paso 7: Configurar Permisos
**En Windows:**
1. Ir a la carpeta: `C:\xampp\htdocs\School_PabloNeruda\public\uploads`
2. Clic derecho → Propiedades
3. Pestaña "Seguridad" → Editar
4. Agregar permisos de escritura para "Usuarios"

### Paso 8: Acceder al Sistema
1. Abrir navegador
2. Ir a: `http://localhost/School_PabloNeruda/public/`
3. Debería ver la página principal del sistema

### Verificación de Instalación

✅ **Si todo está bien, verás:**
- Menú de navegación (Estudiantes, Notas, Reportes)
- Sin errores en pantalla
- Puedes navegar entre secciones

❌ **Si hay problemas:**

**Error: "No se puede conectar a la base de datos"**
- Verificar que MySQL esté iniciado en XAMPP
- Revisar archivo `config/database.php`:
  ```php
  private string $username = 'root';
  private string $password = '';  // Dejar vacío por defecto
  ```

**Error: "Cannot modify header information"**
- Verificar que no haya espacios antes de `<?php` en los archivos
- Verificar que los archivos estén guardados en UTF-8 sin BOM

**Error al subir archivos**
- Verificar permisos de la carpeta `uploads`
- En XAMPP, editar `php.ini`:
  - `upload_max_filesize = 2M`
  - `post_max_size = 3M`

### Datos de Prueba Incluidos

Si importaste `datos_prueba.sql`, tendrás:
- **6 estudiantes** de ejemplo
- **12 acudientes** asociados
- **Notas** del primer periodo de 2026
- **4 periodos** académicos configurados
- **Cursos** de Segundo, Tercero y Cuarto

### Credenciales

El sistema no requiere login en esta versión. Acceso directo a todas las funcionalidades.

### Soporte

Si tienes problemas:
1. Revisar que XAMPP esté ejecutándose
2. Verificar que la base de datos existe
3. Revisar permisos de carpetas
4. Consultar el archivo `README.md` para más detalles

---

**¡Listo para usar!** 🎉

Ahora puedes:
- Registrar estudiantes
- Ingresar calificaciones
- Generar reportes
- Consultar boletines

