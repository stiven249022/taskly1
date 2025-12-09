# 🔍 Cómo Ver el Client ID y Client Secret en Google Cloud Console

## 📍 Método 1: Ver Credenciales Existentes

### Paso 1: Acceder a Google Cloud Console
1. Ve a [Google Cloud Console](https://console.cloud.google.com/)
2. Inicia sesión con tu cuenta de Google
3. Selecciona tu proyecto (si tienes varios, usa el selector de proyectos en la parte superior)

### Paso 2: Ir a Credenciales
1. En el menú lateral izquierdo, haz clic en **"APIs y servicios"** (o **"APIs & Services"**)
2. Luego haz clic en **"Credenciales"** (o **"Credentials"**)

### Paso 3: Ver el Client ID
1. En la lista de credenciales, busca la sección **"ID de cliente de OAuth 2.0"** (o **"OAuth 2.0 Client IDs"**)
2. Verás una lista con tus credenciales OAuth
3. El **Client ID** está visible directamente en la lista (es un texto largo que termina en `.apps.googleusercontent.com`)
4. Haz clic en el nombre de la credencial para ver más detalles

### Paso 4: Ver el Client Secret
⚠️ **IMPORTANTE**: El Client Secret NO se muestra completo por seguridad.

1. Haz clic en el nombre de tu credencial OAuth 2.0
2. Verás los detalles de la credencial
3. El **Client Secret** aparecerá oculto con asteriscos: `GOCSPX-****...`
4. Para ver el Client Secret completo, tienes dos opciones:

   **Opción A: Si lo acabas de crear**
   - Si acabas de crear las credenciales, el Client Secret se mostró en un modal
   - Si no lo guardaste, tendrás que crear nuevas credenciales

   **Opción B: Resetear el Client Secret**
   - En la página de detalles de la credencial
   - Haz clic en **"Restablecer secreto"** o **"Reset Secret"**
   - Se generará un nuevo Client Secret
   - ⚠️ **IMPORTANTE**: Esto invalidará el Client Secret anterior
   - Copia el nuevo Client Secret inmediatamente

---

## 📋 Información que Verás

Cuando hagas clic en tu credencial OAuth 2.0, verás:

```
Nombre: Taskly Web App
Tipo: Aplicación web
ID de cliente: 123456789-abcdefghijklmnop.apps.googleusercontent.com
Secreto de cliente: GOCSPX-****... (oculto)
Orígenes JavaScript autorizados:
  - https://web-production-3192.up.railway.app
URI de redirección autorizados:
  - https://web-production-3192.up.railway.app/auth/google/callback
```

---

## 🔄 Si No Puedes Ver el Client Secret

Si el Client Secret está oculto y no lo guardaste:

1. **Opción 1: Resetear el Secret** (recomendado si no estás usando las credenciales en producción aún)
   - Haz clic en **"Restablecer secreto"**
   - Copia el nuevo secret inmediatamente
   - Actualiza las variables en Railway con el nuevo secret

2. **Opción 2: Crear Nuevas Credenciales**
   - Elimina las credenciales actuales
   - Crea nuevas credenciales
   - Guarda el Client ID y Client Secret inmediatamente

---

## 📝 Ejemplo de cómo se ven las credenciales

**Client ID:**
```
12955442265-8ndni8ego3f2212q4mgkls39uhabekas.apps.googleusercontent.com
```

**Client Secret:**
```
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
```

---

## ✅ Después de Ver las Credenciales

Una vez que tengas el Client ID y Client Secret:

1. **Cópialos** y guárdalos en un lugar seguro
2. **Agrégalos en Railway** como variables de entorno:
   - `GOOGLE_CLIENT_ID` = [tu client id]
   - `GOOGLE_CLIENT_SECRET` = [tu client secret]
   - `GOOGLE_REDIRECT_URI` = `https://web-production-3192.up.railway.app/auth/google/callback`

---

## 🆘 Si No Encuentras las Credenciales

1. Verifica que estés en el proyecto correcto
2. Verifica que hayas creado credenciales OAuth 2.0 (no API keys)
3. Si no tienes credenciales, créalas siguiendo la guía: `GUIA_CREAR_GOOGLE_OAUTH.md`


