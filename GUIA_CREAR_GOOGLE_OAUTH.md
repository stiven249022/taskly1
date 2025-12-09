# 🔐 Guía: Crear Nuevas Credenciales de Google OAuth

## 📋 Requisitos Previos

- Una cuenta de Google (Gmail)
- Acceso a [Google Cloud Console](https://console.cloud.google.com/)

---

## 🚀 Paso 1: Crear un Nuevo Proyecto en Google Cloud

1. Ve a [Google Cloud Console](https://console.cloud.google.com/)
2. Inicia sesión con tu cuenta de Google
3. En la parte superior, haz clic en el selector de proyectos (junto al logo de Google Cloud)
4. Haz clic en **"NUEVO PROYECTO"** o **"New Project"**
5. Completa el formulario:
   - **Nombre del proyecto**: `Taskly Production` (o el nombre que prefieras)
   - **Organización**: Déjalo como está (si no tienes organización)
6. Haz clic en **"Crear"** o **"Create"**
7. Espera unos segundos a que se cree el proyecto
8. Selecciona el proyecto recién creado desde el selector de proyectos

---

## 🔧 Paso 2: Habilitar las APIs Necesarias

1. En el menú lateral izquierdo, ve a **"APIs y servicios"** → **"Biblioteca"** (o **"APIs & Services"** → **"Library"**)
2. Busca y habilita estas APIs (una por una):

   **a) Google+ API:**
   - Busca: `Google+ API`
   - Haz clic en el resultado
   - Haz clic en **"Habilitar"** o **"Enable"**
   - Espera a que se habilite

   **b) Google OAuth2 API:**
   - Busca: `Google OAuth2 API`
   - Haz clic en el resultado
   - Haz clic en **"Habilitar"** o **"Enable"**
   - Espera a que se habilite

   **c) People API (Recomendado):**
   - Busca: `People API`
   - Haz clic en el resultado
   - Haz clic en **"Habilitar"** o **"Enable"**
   - Espera a que se habilite

---

## 🔑 Paso 3: Crear Credenciales OAuth 2.0

1. En el menú lateral izquierdo, ve a **"APIs y servicios"** → **"Credenciales"** (o **"APIs & Services"** → **"Credentials"**)
2. En la parte superior, haz clic en **"+ CREAR CREDENCIALES"** o **"+ CREATE CREDENTIALS"**
3. Selecciona **"ID de cliente de OAuth 2.0"** o **"OAuth client ID"**

### Si es la primera vez, te pedirá configurar la pantalla de consentimiento:

4. **Configurar la pantalla de consentimiento:**
   - **Tipo de usuario**: Selecciona **"Externo"** o **"External"** (a menos que tengas una cuenta de Google Workspace)
   - Haz clic en **"Crear"** o **"Create"**
   - **Nombre de la aplicación**: `Taskly`
   - **Correo electrónico de soporte**: Tu correo de Gmail
   - **Dominio autorizado**: Déjalo vacío por ahora
   - **Correo electrónico del desarrollador**: Tu correo de Gmail
   - Haz clic en **"Guardar y continuar"** o **"Save and Continue"**
   - En **"Ámbitos"** (Scopes): Haz clic en **"Guardar y continuar"** (no necesitas agregar nada)
   - En **"Usuarios de prueba"**: Agrega tu correo de Gmail si quieres probar antes de publicar
   - Haz clic en **"Guardar y continuar"**
   - Revisa y haz clic en **"Volver al panel"** o **"Back to Dashboard"**

5. **Crear el ID de cliente:**
   - Haz clic nuevamente en **"+ CREAR CREDENCIALES"** → **"ID de cliente de OAuth 2.0"**
   - **Tipo de aplicación**: Selecciona **"Aplicación web"** o **"Web application"**
   - **Nombre**: `Taskly Web App`
   
   **Orígenes JavaScript autorizados:**
   - Haz clic en **"+ Agregar URI"** o **"+ Add URI"**
   - Agrega: `https://tasklysena.up.railway.app`
   - (Opcional para desarrollo local): `http://localhost:8000`
   
   **URI de redirección autorizados:**
   - Haz clic en **"+ Agregar URI"** o **"+ Add URI"**
   - Agrega: `https://tasklysena.up.railway.app/auth/google/callback`
   - (Opcional para desarrollo local): `http://localhost:8000/auth/google/callback`
   - (Opcional para desarrollo local): `http://127.0.0.1:8000/auth/google/callback`

6. Haz clic en **"Crear"** o **"Create"**

7. **¡IMPORTANTE!** Se mostrará un modal con tus credenciales:
   - **ID de cliente** (Client ID): Cópialo y guárdalo
   - **Secreto de cliente** (Client Secret): Cópialo y guárdalo
   - ⚠️ **Este es el ÚNICO momento en que verás el Client Secret completo. Guárdalo bien.**

---

## 📝 Paso 4: Guardar las Credenciales

Copia y guarda estas credenciales en un lugar seguro:

```
GOOGLE_CLIENT_ID=tu-client-id-aqui.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=tu-client-secret-aqui
```

---

## ⚙️ Paso 5: Agregar las Credenciales en Railway

1. Ve a [Railway Dashboard](https://railway.app)
2. Selecciona tu proyecto
3. Ve a tu servicio web → **Variables**
4. Haz clic en **"+ New Variable"** y agrega:

   **Variable 1:**
   - Nombre: `GOOGLE_CLIENT_ID`
   - Valor: `[tu-client-id-copiado]`

   **Variable 2:**
   - Nombre: `GOOGLE_CLIENT_SECRET`
   - Valor: `[tu-client-secret-copiado]`

   **Variable 3:**
   - Nombre: `GOOGLE_REDIRECT_URI`
   - Valor: `https://tasklysena.up.railway.app/auth/google/callback`

5. Railway redesplegará automáticamente

---

## ✅ Paso 6: Verificar que Funciona

1. Espera a que Railway termine de redesplegar (puedes ver el progreso en los logs)
2. Ve a `https://tasklysena.up.railway.app/login`
3. Haz clic en "Continuar con Google"
4. Deberías ser redirigido a Google para autenticarte
5. Si funciona, ¡listo! 🎉

---

## 🐛 Solución de Problemas

### Error: "redirect_uri_mismatch"
- Verifica que la URL en Railway sea exactamente: `https://tasklysena.up.railway.app/auth/google/callback`
- Verifica que la misma URL esté en Google Cloud Console → Credenciales → URI de redirección autorizados

### Error: "access_denied"
- Verifica que hayas agregado tu correo como "Usuario de prueba" en la pantalla de consentimiento
- O publica la aplicación (en producción, cualquier usuario podrá usarla)

### No puedo ver el Client Secret
- Si perdiste el Client Secret, debes crear nuevas credenciales
- Ve a Credenciales → Haz clic en tu ID de cliente → Haz clic en "Eliminar" → Crea uno nuevo

---

## 📌 Notas Importantes

- ⚠️ **Nunca compartas tu Client Secret públicamente**
- ⚠️ **Guarda las credenciales en un lugar seguro**
- ✅ Las credenciales funcionan tanto para desarrollo local como para producción
- ✅ Puedes tener múltiples URIs de redirección configuradas

---

**Última actualización**: Enero 2025


