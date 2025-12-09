# 🔧 Variables de Entorno Corregidas para Railway

## ⚠️ Problemas Encontrados

1. **Comillas en valores booleanos** - Laravel espera valores sin comillas
2. **URL incorrecta** - `APP_URL` y `GOOGLE_REDIRECT_URI` apuntan a URL antigua
3. **SESSION_DOMAIN incorrecto** - Tiene valor "null" como string en lugar de estar vacío

## ✅ Variables Corregidas

Copia y pega estas variables en Railway (sin comillas en los valores):

```env
APP_DEBUG=true
APP_ENV=production
APP_FAKER_LOCALE=en_US
APP_FALLBACK_LOCALE=en
APP_KEY=base64:IGC2UtkMZAZ/cDbLcQtE74boTbdQs/3U48UiW2BtqpM=
APP_LOCALE=en
APP_MAINTENANCE_DRIVER=file
APP_NAME=Taskly
APP_URL=https://tasklysena.up.railway.app

AWS_DEFAULT_REGION=us-east-1
AWS_USE_PATH_STYLE_ENDPOINT=false

BCRYPT_ROUNDS=12

BROADCAST_CONNECTION=log

CACHE_STORE=file

DB_CONNECTION=mysql
DB_DATABASE=railway
DB_HOST=mysql.railway.internal
DB_PASSWORD=ARckPSlsJoonIDFxrSUqofaZazxcTInW
DB_PORT=3306
DB_USERNAME=root

FILESYSTEM_DISK=local

LOG_CHANNEL=stderr
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug
LOG_STACK=single

MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME=Taskly
MAIL_HOST=127.0.0.1
MAIL_MAILER=log
MAIL_PASSWORD=null
MAIL_PORT=2525
MAIL_SCHEME=null
MAIL_USERNAME=null

MEMCACHED_HOST=127.0.0.1

PHP_CLI_SERVER_WORKERS=4

QUEUE_CONNECTION=sync

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

SESSION_DRIVER=cookie
SESSION_ENCRYPT=false
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

VITE_APP_NAME=Taskly

GOOGLE_CLIENT_ID=12955442265-8ndni8ego3f2212q4mgkls39uhabekas.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=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
GOOGLE_REDIRECT_URI=https://tasklysena.up.railway.app/auth/google/callback
```

## 🔴 Variables a ELIMINAR

Elimina estas variables si existen (tienen valores incorrectos):

- `SESSION_DOMAIN` (si tiene valor "null" como string)

## 📝 Cambios Importantes

1. **APP_URL**: Cambiado de `https://web-production-3192.up.railway.app` a `https://tasklysena.up.railway.app`
2. **GOOGLE_REDIRECT_URI**: Cambiado a la nueva URL
3. **SESSION_SECURE_COOKIE**: Cambiado de `"true"` (con comillas) a `true` (sin comillas)
4. **SESSION_SAME_SITE**: Cambiado de `"lax"` (con comillas) a `lax` (sin comillas)
5. **SESSION_DOMAIN**: Eliminado (no debe existir o debe estar vacío)

## ⚠️ IMPORTANTE: Actualizar Google Cloud Console

También debes actualizar la URL de redirección en Google Cloud Console:

1. Ve a [Google Cloud Console](https://console.cloud.google.com/)
2. Ve a **APIs & Services** → **Credentials**
3. Edita tus credenciales OAuth 2.0
4. En **Authorized redirect URIs**, actualiza a:
   ```
   https://tasklysena.up.railway.app/auth/google/callback
   ```
5. Guarda los cambios

---

**Última actualización**: Enero 2025

