# 👥 Usuarios de Prueba en Railway

## 📋 Usuarios Creados Automáticamente

Cuando se despliega la aplicación en Railway, los seeders se ejecutan automáticamente y crean los siguientes usuarios de prueba:

### 🔑 Credenciales de Acceso

#### Administrador
- **Email**: `admin@taskly.com`
- **Contraseña**: `admin123`
- **Rol**: Administrador
- **Estado**: Activo
- **Email verificado**: Sí

#### Profesor
- **Email**: `profesor@taskly.com`
- **Contraseña**: `profesor123`
- **Rol**: Profesor
- **Estado**: Activo
- **Email verificado**: Sí

#### Estudiante
- **Email**: `estudiante@taskly.com`
- **Contraseña**: `estudiante123`
- **Rol**: Estudiante
- **Estado**: Activo
- **Email verificado**: Sí

#### Usuarios Estudiantes Adicionales
- **Email**: `usuario@taskly.com` | **Contraseña**: `usuario123`
- **Email**: `maria@taskly.com` | **Contraseña**: `maria123`
- **Email**: `juan@taskly.com` | **Contraseña**: `juan123`

---

## 🔄 Cómo se Crean los Usuarios

Los usuarios se crean automáticamente mediante seeders cuando:
1. Se despliega la aplicación por primera vez en Railway
2. Se ejecutan las migraciones (`php artisan migrate --force`)
3. Se ejecutan los seeders (`php artisan db:seed --force`)

### Seeders Ejecutados

1. **AdminUserSeeder**: Crea admin, profesor y estudiante
2. **AdminSeeder**: Crea usuario, maria y juan
3. **CourseSeeder**: Crea cursos de ejemplo
4. **TagSeeder**: Crea etiquetas de ejemplo
5. **TaskSeeder**: Crea tareas de ejemplo
6. **ProjectSeeder**: Crea proyectos de ejemplo
7. **ReminderSeeder**: Crea recordatorios de ejemplo

---

## ⚠️ Importante

- Los seeders usan `firstOrCreate()` y `updateOrCreate()`, por lo que **no crearán duplicados** si se ejecutan múltiples veces
- Los usuarios se crean con **email verificado** y **estado activo**
- Las contraseñas están **hasheadas** usando bcrypt
- Estos usuarios son **solo para pruebas** - considera cambiar las contraseñas en producción

---

## 🧪 Probar el Login

1. Ve a `https://web-production-3192.up.railway.app/login`
2. Usa cualquiera de las credenciales de arriba
3. Deberías poder iniciar sesión sin problemas

---

## 🔧 Ejecutar Seeders Manualmente

Si necesitas ejecutar los seeders manualmente en Railway:

1. Ve a Railway Dashboard → tu servicio web → **Deployments**
2. Haz clic en los **3 puntos** del último deployment
3. Selecciona **"View Logs"** o **"Open Shell"**
4. Ejecuta:
   ```bash
   php artisan db:seed --force
   ```

O desde la terminal local (si tienes acceso a la BD de Railway):
```bash
php artisan db:seed --force
```

---

## 📝 Modificar Usuarios de Prueba

Si quieres cambiar los usuarios de prueba, edita estos archivos:

- `database/seeders/AdminUserSeeder.php` - Admin, Profesor, Estudiante
- `database/seeders/AdminSeeder.php` - Usuario, María, Juan

Después de modificar, haz commit y push. Railway ejecutará los seeders actualizados en el próximo despliegue.

---

**Última actualización**: Enero 2025

