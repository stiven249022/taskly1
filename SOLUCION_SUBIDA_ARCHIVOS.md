# 🔧 Solución: Problema con Subida de Archivos en Railway

## 🚨 Problema

No se pueden subir archivos en la aplicación desplegada en Railway.

## 🔍 Causas Posibles

1. **Enlace simbólico no creado**
   - El comando `php artisan storage:link` no se ejecuta durante el despliegue
   - Sin este enlace, los archivos no son accesibles públicamente

2. **Permisos de escritura**
   - El directorio `storage/app/public` no tiene permisos de escritura
   - Railway puede tener restricciones de permisos

3. **Directorio storage no existe**
   - Los directorios necesarios no se crean automáticamente

4. **Límite de tamaño de archivo**
   - PHP tiene límites de `upload_max_filesize` y `post_max_size`
   - El límite configurado en la aplicación es de 10MB

## ✅ Solución

### Paso 1: Verificar que el Enlace Simbólico se Cree

El comando `php artisan storage:link` ahora se ejecuta automáticamente durante el despliegue. Si el problema persiste:

1. Ve a Railway Dashboard → tu servicio web → **Deployments**
2. Haz clic en los **3 puntos** del último deployment
3. Selecciona **"View Logs"** o **"Open Shell"**
4. Ejecuta manualmente:
   ```bash
   php artisan storage:link
   ```

### Paso 2: Verificar Permisos de Directorios

En Railway, los permisos generalmente se manejan automáticamente, pero puedes verificar:

1. Abre una shell en Railway
2. Verifica que el directorio existe:
   ```bash
   ls -la storage/app/public
   ```
3. Si no existe, créalo:
   ```bash
   mkdir -p storage/app/public/task-files
   mkdir -p storage/app/public/project-files
   mkdir -p storage/app/public/project-task-files
   mkdir -p storage/app/public/profile-photos
   ```

### Paso 3: Verificar Límites de PHP

Si los archivos son muy grandes, verifica los límites de PHP. En Railway, puedes agregar estas variables de entorno:

```env
PHP_INI_UPLOAD_MAX_FILESIZE=10M
PHP_INI_POST_MAX_SIZE=10M
```

### Paso 4: Verificar Configuración de Storage

Asegúrate de que estas variables estén configuradas en Railway:

```env
FILESYSTEM_DISK=local
APP_URL=https://tasklysena.up.railway.app
```

## 🧪 Verificar que Funciona

1. Intenta subir un archivo pequeño (menos de 1MB)
2. Verifica los logs de Railway para ver si hay errores
3. Verifica que el archivo se guarde en `storage/app/public/task-files/` (o la carpeta correspondiente)

## 🐛 Errores Comunes

### Error: "The stream or file could not be opened"

**Causa**: El directorio no existe o no tiene permisos de escritura.

**Solución**:
1. Verifica que el directorio `storage/app/public` exista
2. Verifica los permisos del directorio
3. Ejecuta `php artisan storage:link` manualmente

### Error: "File too large"

**Causa**: El archivo excede el límite de 10MB o los límites de PHP.

**Solución**:
1. Reduce el tamaño del archivo
2. O aumenta los límites de PHP en Railway

### Error: "Storage disk [public] not found"

**Causa**: Problema con la configuración de filesystems.

**Solución**:
1. Verifica que `FILESYSTEM_DISK=local` esté configurado
2. Limpia el cache: `php artisan config:clear`

## ⚠️ IMPORTANTE: Almacenamiento Efímero en Railway

**Railway tiene almacenamiento efímero**, lo que significa que:

- Los archivos se pierden cuando se redespliega
- Los archivos se pierden si el servicio se reinicia
- Los archivos NO persisten entre despliegues

### Soluciones para Persistencia:

1. **Usar un servicio de almacenamiento externo** (recomendado):
   - AWS S3
   - Google Cloud Storage
   - DigitalOcean Spaces

2. **Usar un volumen persistente de Railway** (si está disponible en tu plan)

3. **Aceptar que los archivos son temporales** (solo para desarrollo/pruebas)

## 📝 Configuración Recomendada para Producción

Para producción, se recomienda usar S3 o un servicio similar:

1. Configura las variables de AWS en Railway:
   ```env
   AWS_ACCESS_KEY_ID=tu-access-key
   AWS_SECRET_ACCESS_KEY=tu-secret-key
   AWS_DEFAULT_REGION=us-east-1
   AWS_BUCKET=tu-bucket-name
   ```

2. Cambia el disco por defecto:
   ```env
   FILESYSTEM_DISK=s3
   ```

3. Actualiza los controladores para usar el disco S3 (ya deberían funcionar automáticamente)

---

**Última actualización**: Enero 2025

