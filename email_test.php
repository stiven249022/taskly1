<?php

// Script de prueba para verificar configuración de email
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Mail;
use App\Notifications\VerifyEmailNotification;
use App\Models\User;

// Simular un usuario para la prueba
$user = new User();
$user->name = 'Usuario de Prueba';
$user->email = 'test@example.com';

echo "=== PRUEBA DE CONFIGURACIÓN DE EMAIL ===\n\n";

// Verificar configuración actual
echo "Configuración actual:\n";
echo "MAIL_MAILER: " . env('MAIL_MAILER', 'no configurado') . "\n";
echo "MAIL_HOST: " . env('MAIL_HOST', 'no configurado') . "\n";
echo "MAIL_PORT: " . env('MAIL_PORT', 'no configurado') . "\n";
echo "MAIL_USERNAME: " . env('MAIL_USERNAME', 'no configurado') . "\n";
echo "MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS', 'no configurado') . "\n";
echo "MAIL_FROM_NAME: " . env('MAIL_FROM_NAME', 'no configurado') . "\n\n";

// Si está configurado como 'log', mostrar dónde se guardan los logs
if (env('MAIL_MAILER') === 'log') {
    echo "⚠️  EMAIL CONFIGURADO COMO 'LOG'\n";
    echo "Los emails se guardan en: storage/logs/laravel.log\n";
    echo "Para ver los logs: tail -f storage/logs/laravel.log\n\n";
}

echo "Para configurar email real, edita el archivo .env con una de estas opciones:\n\n";

echo "=== OPCIÓN 1: GMAIL ===\n";
echo "MAIL_MAILER=smtp\n";
echo "MAIL_HOST=smtp.gmail.com\n";
echo "MAIL_PORT=587\n";
echo "MAIL_USERNAME=tu-email@gmail.com\n";
echo "MAIL_PASSWORD=tu-contraseña-de-aplicación\n";
echo "MAIL_ENCRYPTION=tls\n";
echo "MAIL_FROM_ADDRESS=tu-email@gmail.com\n";
echo "MAIL_FROM_NAME=\"Taskly\"\n\n";

echo "=== OPCIÓN 2: MAILTRAP (Para desarrollo) ===\n";
echo "MAIL_MAILER=smtp\n";
echo "MAIL_HOST=sandbox.smtp.mailtrap.io\n";
echo "MAIL_PORT=2525\n";
echo "MAIL_USERNAME=tu-usuario-mailtrap\n";
echo "MAIL_PASSWORD=tu-contraseña-mailtrap\n";
echo "MAIL_ENCRYPTION=tls\n";
echo "MAIL_FROM_ADDRESS=from@example.com\n";
echo "MAIL_FROM_NAME=\"Taskly\"\n\n";

echo "=== OPCIÓN 3: LOG (Para desarrollo local) ===\n";
echo "MAIL_MAILER=log\n";
echo "MAIL_FROM_ADDRESS=noreply@taskly.com\n";
echo "MAIL_FROM_NAME=\"Taskly\"\n\n";

echo "Después de configurar:\n";
echo "1. Ejecuta: php artisan config:cache\n";
echo "2. Prueba el registro de un nuevo usuario\n";
echo "3. Verifica que llegue el email de verificación\n\n";

echo "=== INSTRUCCIONES PARA GMAIL ===\n";
echo "1. Ve a tu cuenta de Google\n";
echo "2. Activa la verificación en 2 pasos\n";
echo "3. Ve a 'Contraseñas de aplicación'\n";
echo "4. Genera una nueva contraseña para 'Mail'\n";
echo "5. Usa esa contraseña en MAIL_PASSWORD\n\n";

echo "=== PARA VER LOGS DE EMAIL ===\n";
echo "Si usas MAIL_MAILER=log:\n";
echo "tail -f storage/logs/laravel.log\n\n"; 