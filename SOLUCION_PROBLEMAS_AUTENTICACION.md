# 🔧 Solución de Problemas de Autenticación en Railway

## 🚨 Problemas Identificados

### 1. Variables de Google OAuth Faltantes
Las variables de Google OAuth **NO están configuradas** en Railway, por lo que la autenticación con Google no funciona.

### 2. Posible Problema de Conexión a Base de Datos
La conexión a la BD puede fallar si:
- El host `mysql.railway.internal` no es accesible
- Las credenciales están incorrectas
- Las migraciones no se ejecutaron correctamente

### 3. URL de Redirección de Google OAuth Incorrecta
Si las variables están configuradas, probablemente tienen la URL de `localhost` en lugar de la URL de producción.

---

## ✅ Solución Paso a Paso

### PASO 1: Verificar Variables de Entorno en Railway

1. Ve a [Railway Dashboard](https://railway.app)
2. Selecciona tu proyecto
3. Ve a tu servicio web → **Variables**
4. Verifica que tengas estas variables:

```env
# Variables básicas (ya deberían estar)
APP_DEBUG=true
APP_ENV=production
APP_KEY=base64:IGC2UtkMZAZ/cDbLcQtE74boTbdQs/3U48UiW2BtqpM=
APP_NAME=Taskly
APP_URL=https://tasklysena.up.railway.app

# Base de datos (verificar que sean correctas)
DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=ARckPSlsJoonIDFxrSUqofaZazxcTInW

# Variables de Google OAuth (FALTANTES - AGREGAR ESTAS)
GOOGLE_CLIENT_ID=tu-google-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=tu-google-client-secret
GOOGLE_REDIRECT_URI=https://tasklysena.up.railway.app/auth/google/callback

# Sesiones y cache
LOG_CHANNEL=stderr
SESSION_DRIVER=cookie
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

---

### PASO 2: Configurar Google OAuth en Google Cloud Console

1. Ve a [Google Cloud Console](https://console.cloud.google.com/)
2. Selecciona tu proyecto (o crea uno nuevo)
3. Ve a **APIs & Services** → **Credentials**
4. Si ya tienes credenciales OAuth 2.0:
   - Haz clic en ellas para editarlas
   - En **Authorized redirect URIs**, agrega:
     ```
     https://tasklysena.up.railway.app/auth/google/callback
     ```
   - Guarda los cambios
5. Si NO tienes credenciales:
   - Haz clic en **Create Credentials** → **OAuth client ID**
   - Tipo: **Web application**
   - Name: Taskly Production
   - **Authorized JavaScript origins**:
     ```
     https://tasklysena.up.railway.app
     ```
   - **Authorized redirect URIs**:
     ```
     https://tasklysena.up.railway.app/auth/google/callback
     ```
   - Haz clic en **Create**
   - **Copia el Client ID y Client Secret**

---

### PASO 3: Agregar Variables de Google OAuth en Railway

1. En Railway → tu servicio web → **Variables**
2. Haz clic en **+ New Variable**
3. Agrega estas 3 variables:

```
GOOGLE_CLIENT_ID=tu-client-id-copiado.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=tu-client-secret-copiado
GOOGLE_REDIRECT_URI=https://tasklysena.up.railway.app/auth/google/callback
```

⚠️ **IMPORTANTE**: Reemplaza `tu-client-id-copiado` y `tu-client-secret-copiado` con los valores reales de Google Cloud Console.

---

### PASO 4: Verificar Conexión a Base de Datos

#### Opción A: Usar la variable MYSQL_URL de Railway (Recomendado)

Railway a veces proporciona una variable `MYSQL_URL` que es más confiable. Verifica si existe:

1. En Railway → tu servicio MySQL → **Variables**
2. Busca `MYSQL_URL` o `DATABASE_URL`
3. Si existe, agrega esta variable a tu servicio web:
   ```
   DB_URL=el-valor-de-MYSQL_URL
   ```
   
   **Nota**: Si usas `DB_URL`, Laravel automáticamente parseará la URL y usará esos valores en lugar de las variables individuales (`DB_HOST`, `DB_USERNAME`, etc.). Esto puede resolver problemas de conexión.

#### Opción C: Verificar que el servicio MySQL esté en el mismo proyecto

Asegúrate de que el servicio MySQL y el servicio web estén en el **mismo proyecto** de Railway. Si están en proyectos diferentes, `mysql.railway.internal` no funcionará.

#### Opción B: Verificar que las credenciales sean correctas

1. En Railway → tu servicio MySQL → **Variables**
2. Verifica que estas variables coincidan con las de tu servicio web:
   - `MYSQLDATABASE` → debe coincidir con `DB_DATABASE`
   - `MYSQLUSER` → debe coincidir con `DB_USERNAME`
   - `MYSQLPASSWORD` → debe coincidir con `DB_PASSWORD`
   - `MYSQLHOST` → debe coincidir con `DB_HOST`

---

### PASO 5: Verificar que las Migraciones se Ejecutaron

1. En Railway → tu servicio web → **Logs**
2. Busca en los logs la línea que dice:
   ```
   php artisan migrate --force
   ```
3. Verifica que no haya errores de migración
4. Si hay errores, verás algo como:
   ```
   SQLSTATE[HY000] [2002] Connection refused
   ```
   Esto indica un problema de conexión a la BD.

---

### PASO 6: Limpiar Cache de Configuración

Después de agregar las variables, Railway debería redesplegar automáticamente. Si no, puedes forzar un redespliegue:

1. Railway → tu servicio web → **Deployments**
2. Haz clic en los **3 puntos** del último deployment
3. Selecciona **Redeploy**

O puedes agregar un comando de limpieza de cache en el `startCommand` del `railway.json`:

```json
{
  "deploy": {
    "startCommand": "php artisan config:clear && php artisan cache:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT"
  }
}
```

---

## 🔍 Verificar que Todo Funciona

### 1. Verificar Logs de Railway

1. Railway → tu servicio web → **Logs**
2. Busca errores relacionados con:
   - `SQLSTATE` → Problema de base de datos
   - `Google OAuth` → Problema con credenciales de Google
   - `Connection refused` → Problema de conexión a BD

### 2. Probar Inicio de Sesión Normal

1. Ve a `https://tasklysena.up.railway.app/login`
2. Intenta iniciar sesión con un usuario registrado
3. Si falla, revisa los logs de Railway

### 3. Probar Autenticación con Google

1. Ve a `https://tasklysena.up.railway.app/login`
2. Haz clic en "Continuar con Google"
3. Si te redirige a Google, las credenciales están correctas
4. Si ves un error, revisa los logs de Railway

---

## 🐛 Errores Comunes y Soluciones

### Error: "SQLSTATE[HY000] [2002] Connection refused"

**Causa**: No puede conectarse a la base de datos.

**Solución**:
1. Verifica que el servicio MySQL esté corriendo en Railway
2. Verifica que `DB_HOST` sea `mysql.railway.internal` (no `localhost` o `127.0.0.1`)
3. Verifica que las credenciales sean correctas
4. Intenta usar `MYSQL_URL` si está disponible

### Error: "Invalid client_id" o "redirect_uri_mismatch"

**Causa**: Las credenciales de Google OAuth están incorrectas o la URL de redirección no coincide.

**Solución**:
1. Verifica que `GOOGLE_CLIENT_ID` y `GOOGLE_CLIENT_SECRET` sean correctos
2. Verifica que `GOOGLE_REDIRECT_URI` sea exactamente:
   ```
   https://tasklysena.up.railway.app/auth/google/callback
   ```
3. Verifica en Google Cloud Console que la URL de redirección autorizada sea la misma

### Error: "Las credenciales no coinciden" (Login normal)

**Causa**: El usuario no existe en la base de datos o la contraseña es incorrecta.

**Solución**:
1. Verifica que las migraciones se ejecutaron correctamente
2. Verifica que el usuario exista en la base de datos
3. Si es un usuario nuevo, regístralo primero

### Error: "Class 'Laravel\Socialite\Facades\Socialite' not found"

**Causa**: El paquete Laravel Socialite no está instalado.

**Solución**:
1. Verifica que `laravel/socialite` esté en `composer.json`
2. Ejecuta `composer install` localmente y haz push
3. Railway debería instalar las dependencias automáticamente

---

## 📝 Checklist Final

- [ ] Variables de Google OAuth agregadas en Railway
- [ ] URL de redirección de Google configurada en Google Cloud Console
- [ ] Variables de base de datos verificadas y correctas
- [ ] Migraciones ejecutadas sin errores
- [ ] Cache de configuración limpiado
- [ ] Logs de Railway revisados sin errores críticos
- [ ] Inicio de sesión normal funciona
- [ ] Autenticación con Google funciona

---

## 🆘 Si Nada Funciona

1. **Revisa los logs completos** de Railway
2. **Verifica que todas las variables** estén correctamente escritas (sin espacios extra)
3. **Prueba conectarte a la BD** desde tu máquina local usando las credenciales de Railway
4. **Verifica que el servicio MySQL** esté corriendo en Railway
5. **Contacta al soporte** de Railway si el problema persiste

---

**Última actualización**: Enero 2025

