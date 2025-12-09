# Funcionalidad de Archivos en Tareas

## Descripción
Se ha implementado una funcionalidad completa para que los estudiantes puedan subir archivos en sus tareas, y que tanto profesores como administradores puedan ver, descargar y calificar estos archivos.

## Características Implementadas

### Para Estudiantes:
1. **Subida de Archivos**: Los estudiantes pueden subir archivos (PDF, DOC, DOCX, TXT, JPG, PNG, GIF) de hasta 10MB
2. **Vista Previa**: Pueden ver los archivos subidos directamente en el navegador
3. **Descarga**: Pueden descargar sus propios archivos
4. **Estado de Entrega**: Las tareas muestran claramente si han sido entregadas o no
5. **Eliminación**: Pueden eliminar archivos si es necesario (la tarea vuelve a estado pendiente)

### Para Profesores:
1. **Vista de Tareas de Estudiantes**: Acceso a todas las tareas de sus estudiantes asignados
2. **Filtros Avanzados**: Por materia, estado, entrega y calificación
3. **Vista Previa de Archivos**: Pueden ver los archivos entregados sin descargarlos
4. **Descarga de Archivos**: Pueden descargar los archivos para revisión offline
5. **Calificación**: Sistema completo de calificación con comentarios
6. **Estadísticas**: Dashboard con estadísticas de entregas y calificaciones

### Para Administradores:
1. **Acceso Completo**: Pueden ver y calificar todas las tareas del sistema
2. **Mismas Funcionalidades**: Todas las características de los profesores

## Archivos Modificados/Creados

### Base de Datos:
- **Migración**: `2025_10_28_160409_add_file_fields_to_tasks_table.php`
- **Campos agregados**:
  - `file_path`: Ruta del archivo en el sistema
  - `file_name`: Nombre original del archivo
  - `file_type`: Tipo MIME del archivo
  - `file_size`: Tamaño del archivo en bytes
  - `submitted_at`: Fecha y hora de entrega

### Modelos:
- **Task.php**: Agregados métodos para manejo de archivos
  - `hasFile()`: Verifica si la tarea tiene archivo
  - `getFileUrl()`: Obtiene la URL del archivo
  - `getFormattedFileSize()`: Formatea el tamaño del archivo
  - `isSubmitted()`: Verifica si la tarea ha sido entregada

### Controladores:
- **TaskFileController.php**: Nuevo controlador para manejo de archivos
  - `upload()`: Subir archivo a una tarea
  - `download()`: Descargar archivo
  - `view()`: Ver archivo en el navegador
  - `delete()`: Eliminar archivo
  - `grade()`: Calificar tarea

- **TaskController.php**: Actualizado
  - `teacherIndex()`: Vista para profesores de tareas de estudiantes
  - `show()`: Carga relaciones necesarias

### Vistas:
- **tasks/show.blade.php**: Vista detallada de tarea con funcionalidad de archivos
- **tasks/teacher-index.blade.php**: Vista para profesores de tareas de estudiantes
- **tasks/index.blade.php**: Actualizada para mostrar estado de entrega
- **layouts/dashboard.blade.php**: Agregado enlace para profesores

### Rutas:
- **web.php**: Agregadas rutas para manejo de archivos
  - `POST /tasks/{task}/upload-file`: Subir archivo
  - `GET /tasks/{task}/view-file`: Ver archivo
  - `GET /tasks/{task}/download-file`: Descargar archivo
  - `DELETE /tasks/{task}/delete-file`: Eliminar archivo
  - `POST /tasks/{task}/grade`: Calificar tarea
  - `GET /teacher/student-tasks`: Vista de profesores

## Configuración

### Almacenamiento:
- Los archivos se almacenan en `storage/app/public/task-files/`
- Se crea un enlace simbólico con `php artisan storage:link`
- Los archivos son accesibles públicamente a través de `/storage/task-files/`

### Seguridad:
- Validación de tipos de archivo permitidos
- Límite de tamaño de 10MB
- Verificación de permisos para cada acción
- Nombres de archivo únicos para evitar conflictos

## Uso

### Para Estudiantes:
1. Ir a la sección "Tareas"
2. Hacer clic en una tarea para ver detalles
3. Si la tarea no está entregada, arrastrar archivo o hacer clic para seleccionar
4. El archivo se sube automáticamente y la tarea se marca como completada
5. Pueden ver, descargar o eliminar el archivo desde la vista de detalles

### Para Profesores:
1. Ir a "Tareas de Estudiantes" en el menú lateral
2. Ver todas las tareas de sus estudiantes asignados
3. Usar filtros para encontrar tareas específicas
4. Hacer clic en "Ver" para ver detalles de la tarea
5. Ver archivos entregados y calificar con comentarios

### Para Administradores:
1. Acceso completo a todas las funcionalidades
2. Pueden ver y calificar tareas de todos los estudiantes
3. Mismas funcionalidades que los profesores

## Tipos de Archivo Soportados:
- **Documentos**: PDF, DOC, DOCX, TXT
- **Imágenes**: JPG, JPEG, PNG, GIF
- **Tamaño máximo**: 10MB por archivo

## Notas Técnicas:
- Los archivos se almacenan con nombres únicos (UUID) para evitar conflictos
- Se mantiene el nombre original del archivo en la base de datos
- La vista previa funciona para archivos compatibles con el navegador
- El sistema es completamente responsive y compatible con modo oscuro
- Todas las acciones son asíncronas para mejor experiencia de usuario

## Próximas Mejoras Sugeridas:
1. Soporte para más tipos de archivo
2. Compresión automática de imágenes
3. Historial de versiones de archivos
4. Notificaciones automáticas de entrega
5. Sistema de comentarios en archivos
6. Vista previa mejorada para diferentes tipos de archivo
