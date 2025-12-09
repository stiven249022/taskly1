# 🔧 Configuración Paso a Paso para estiven.lopera.9@gmail.com

## 📋 **Resumen de lo que necesitas configurar:**

1. ✅ **APP_KEY** - Ya generado automáticamente
2. 🔄 **Gmail App Password** - Necesitas generarlo
3. 🔄 **Google OAuth Credentials** - Necesitas crearlos
4. 🔄 **Archivo .env** - Necesitas crearlo

---

## 📧 **PASO 1: Configurar Gmail (estiven.lopera.9@gmail.com)**

### **1.1 Habilitar Verificación en 2 Pasos:**
1. Ve a [myaccount.google.com](https://myaccount.google.com)
2. Inicia sesión con `estiven.lopera.9@gmail.com`
3. Ve a **Seguridad** → **Verificación en 2 pasos**
4. **Habilita** la verificación en 2 pasos
5. Sigue los pasos de configuración

### **1.2 Generar Contraseña de Aplicación:**
1. Ve a **Seguridad** → **Contraseñas de aplicación**
2. Selecciona **Aplicación**: "Otra (nombre personalizado)"
3. Escribe: **"Taskly"**
4. Haz clic en **Generar**
5. **Copia la contraseña de 16 caracteres** que aparece
6. **Guárdala** - la necesitarás para el archivo .env

---

## 🌐 **PASO 2: Configurar Google OAuth**

### **2.1 Crear Proyecto en Google Cloud:**
1. Ve a [console.cloud.google.com](https://console.cloud.google.com)
2. Haz clic en **Seleccionar proyecto** → **Nuevo proyecto**
3. **Nombre del proyecto**: "Taskly API"
4. Haz clic en **Crear**
5. **Espera** a que se cree el proyecto

### **2.2 Habilitar APIs:**
1. Ve a **APIs y servicios** → **Biblioteca**
2. Busca y habilita estas APIs:
   - **Google+ API** - Haz clic en "Habilitar"
   - **Google OAuth2 API** - Haz clic en "Habilitar"

### **2.3 Crear Credenciales OAuth2:**
1. Ve a **APIs y servicios** → **Credenciales**
2. Haz clic en **+ Crear credenciales** → **ID de cliente de OAuth 2.0**
3. **Tipo de aplicación**: "Aplicación web"
4. **Nombre**: "Taskly Web App"
5. **URIs autorizados**:
   - `http://localhost:8000`
   - `http://127.0.0.1:8000`
6. **URIs de redirección**:
   - `http://localhost:8000/auth/google/callback`
   - `http://127.0.0.1:8000/auth/google/callback`
7. Haz clic en **Crear**
8. **Copia el Client ID y Client Secret** que aparecen

---

## 📝 **PASO 3: Crear Archivo .env**

### **3.1 Crear el archivo:**
1. En la carpeta `taskly`, crea un archivo llamado `.env`
2. Copia y pega el contenido de abajo
3. **Reemplaza** las partes marcadas con `TU_..._AQUI`

### **3.2 Contenido del archivo .env:**

```bash
APP_NAME="Taskly"
APP_ENV=local
APP_KEY=base64:TU_APP_KEY_YA_ESTA_GENERADO
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskly
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Configuración de Gmail para envío de emails
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=estiven.lopera.9@gmail.com
MAIL_PASSWORD=TU_APP_PASSWORD_AQUI
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=estiven.lopera.9@gmail.com
MAIL_FROM_NAME="Taskly"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="Taskly"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

# Configuración de Google OAuth
GOOGLE_CLIENT_ID=TU_GOOGLE_CLIENT_ID_AQUI
GOOGLE_CLIENT_SECRET=TU_GOOGLE_CLIENT_SECRET_AQUI
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### **3.3 Reemplazar valores:**

- **`TU_APP_PASSWORD_AQUI`** → La contraseña de 16 caracteres de Gmail
- **`TU_GOOGLE_CLIENT_ID_AQUI`** → El Client ID de Google Cloud Console
- **`TU_GOOGLE_CLIENT_SECRET_AQUI`** → El Client Secret de Google Cloud Console

---

## ✅ **PASO 4: Verificar Configuración**

### **4.1 Limpiar caché:**
```bash
cd taskly
php artisan config:clear
```

### **4.2 Verificar configuración:**
```bash
php artisan config:show mail
php artisan config:show services
```

### **4.3 Probar servidor:**
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

---

## 🧪 **PASO 5: Probar la API**

### **5.1 Probar endpoint de Google OAuth:**
```powershell
Invoke-WebRequest -Uri "http://localhost:8000/api/auth/google/url" -Method GET -Headers @{"Accept"="application/json"}
```

### **5.2 Respuesta esperada:**
```json
{
  "auth_url": "https://accounts.google.com/o/oauth2/auth?client_id=TU_CLIENT_ID_REAL&redirect_uri=...",
  "message": "URL de autenticación con Google generada"
}
```

---

## 🚨 **Solución de Problemas Comunes**

### **Error: "Client ID not configured"**
- Verifica que `GOOGLE_CLIENT_ID` esté en tu archivo `.env`
- Ejecuta `php artisan config:clear`

### **Error: "Invalid credentials" en Gmail**
- Verifica que la verificación en 2 pasos esté habilitada
- Usa la contraseña de aplicación, no tu contraseña normal
- Verifica que `MAIL_USERNAME` sea `estiven.lopera.9@gmail.com`

### **Error: "Redirect URI mismatch"**
- Verifica que `GOOGLE_REDIRECT_URI` sea exactamente `http://localhost:8000/auth/google/callback`
- Verifica que esté en las URIs de redirección de Google Cloud Console

---

## 📱 **Próximos Pasos Después de la Configuración**

1. **Probar registro de usuario** con la API
2. **Probar envío de emails** de verificación
3. **Probar autenticación con Google**
4. **Integrar con tu frontend** (React, Vue, etc.)

---

## 🆘 **¿Necesitas Ayuda?**

Si tienes algún problema durante la configuración:

1. **Verifica** que todos los valores estén correctos en `.env`
2. **Ejecuta** `php artisan config:clear` después de cambios
3. **Revisa** los logs en `storage/logs/laravel.log`
4. **Confirma** que las APIs estén habilitadas en Google Cloud Console

¡La API estará funcionando perfectamente una vez que completes estos pasos! 🎉
