# Configuración de Variables de Entorno para la API de Gmail

## 📧 Configuración de Gmail para Envío de Emails

Agrega estas variables a tu archivo `.env`:

```bash
# Configuración de Gmail para envío de emails
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
MAIL_FROM_NAME="Taskly"
```

### 🔐 Cómo obtener la contraseña de aplicación de Gmail:

1. **Habilitar verificación en 2 pasos** en tu cuenta de Google
2. **Ir a Configuración > Seguridad > Contraseñas de aplicación**
3. **Generar nueva contraseña** para "Taskly"
4. **Usar esa contraseña** en `MAIL_PASSWORD`

## 🌐 Configuración de Google OAuth

Agrega estas variables a tu archivo `.env`:

```bash
# Configuración de Google OAuth
GOOGLE_CLIENT_ID=tu-google-client-id
GOOGLE_CLIENT_SECRET=tu-google-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### 🔧 Cómo configurar Google OAuth:

1. **Crear proyecto** en [Google Cloud Console](https://console.cloud.google.com/)
2. **Habilitar APIs**:
   - Google+ API
   - Google OAuth2 API
3. **Crear credenciales OAuth2**:
   - Tipo: "Aplicación web"
   - URIs autorizados: `http://localhost:8000`
   - URIs de redirección: `http://localhost:8000/auth/google/callback`
4. **Copiar Client ID y Client Secret** a tu archivo `.env`

## 🚀 Configuración de la Aplicación

Asegúrate de tener estas variables básicas:

```bash
APP_NAME="Taskly"
APP_ENV=local
APP_KEY=tu-app-key-generado
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskly
DB_USERNAME=root
DB_PASSWORD=
```

## ✅ Verificación de Configuración

Después de configurar las variables, ejecuta:

```bash
# Limpiar caché de configuración
php artisan config:clear

# Verificar que las configuraciones se carguen
php artisan config:show mail
php artisan config:show services
```

## 🧪 Probar la API

Una vez configurado, puedes probar los endpoints:

```bash
# 1. Registrar usuario
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test",
    "last_name": "User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# 2. Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'

# 3. Enviar verificación de email (con token)
curl -X POST http://localhost:8000/api/email/send-verification \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

## 🚨 Solución de Problemas Comunes

### Error de Gmail:
- Verificar que la verificación en 2 pasos esté habilitada
- Usar contraseña de aplicación, no la contraseña normal
- Verificar que el puerto 587 esté abierto

### Error de Google OAuth:
- Verificar que las APIs estén habilitadas en Google Cloud Console
- Comprobar que los URIs de redirección coincidan exactamente
- Verificar que las credenciales sean del tipo correcto

### Error de API:
- Verificar que Sanctum esté instalado y configurado
- Comprobar que las migraciones se hayan ejecutado
- Verificar que el servidor esté corriendo en el puerto correcto
