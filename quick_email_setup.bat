@echo off
echo Configurando email temporal para pruebas...
echo.

REM Hacer backup del archivo .env
copy .env .env.backup

REM Crear configuración temporal
echo Configurando MAIL_MAILER=log para ver emails en logs...
powershell -Command "(Get-Content .env) -replace 'MAIL_MAILER=log', 'MAIL_MAILER=log' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'MAIL_FROM_ADDRESS=\"hello@example.com\"', 'MAIL_FROM_ADDRESS=\"noreply@taskly.com\"' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'MAIL_FROM_NAME=\"\${APP_NAME}\"', 'MAIL_FROM_NAME=\"Taskly\"' | Set-Content .env"

echo.
echo Configuración temporal aplicada.
echo Los emails se guardarán en: storage/logs/laravel.log
echo.
echo Para ver los emails:
echo tail -f storage/logs/laravel.log
echo.
echo Para configurar Gmail más tarde, edita el archivo .env
pause 