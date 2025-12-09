# Configuración de Email para Taskly

## Para que funcione la verificación de correo, necesitas configurar el email en el archivo `.env`:

### Opción 1: Gmail (Recomendado para pruebas)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-contraseña-de-aplicación
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
MAIL_FROM_NAME="Taskly"
```

**Nota:** Para Gmail, necesitas usar una "Contraseña de aplicación", no tu contraseña normal.

### Opción 2: Mailtrap (Para desarrollo)

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu-usuario-mailtrap
MAIL_PASSWORD=tu-contraseña-mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=from@example.com
MAIL_FROM_NAME="Taskly"
```

### Opción 3: Log (Para desarrollo local - solo guarda en logs)

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@taskly.com
MAIL_FROM_NAME="Taskly"
```

## Pasos para configurar Gmail:

1. Ve a tu cuenta de Google
2. Activa la verificación en 2 pasos
3. Ve a "Contraseñas de aplicación"
4. Genera una nueva contraseña para "Mail"
5. Usa esa contraseña en MAIL_PASSWORD

## Después de configurar:

1. Ejecuta: `php artisan config:cache`
2. Prueba el registro de un nuevo usuario
3. Verifica que llegue el email de verificación

## Para ver los logs de email (si usas MAIL_MAILER=log):

```bash
tail -f storage/logs/laravel.log
``` 