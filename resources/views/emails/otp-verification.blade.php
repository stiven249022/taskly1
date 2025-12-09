<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificación - Taskly</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header-banner {
            background: linear-gradient(135deg, #4F46E5, #06B6D4);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        .logo-icon {
            width: 24px;
            height: 24px;
            background-color: #fbbf24;
            border-radius: 4px;
            margin-right: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: white;
        }
        .logo-text {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }
        .tagline {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin: 0 0 20px 0;
        }
        .message {
            font-size: 16px;
            color: #4b5563;
            line-height: 1.6;
            margin: 0 0 30px 0;
        }
        .code-container {
            background-color: #f8fafc;
            border: 2px dashed #3b82f6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .code-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 10px 0;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            color: #1e40af;
            letter-spacing: 4px;
            margin: 0;
            font-family: 'Courier New', monospace;
        }
        .alert-yellow {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
            display: flex;
            align-items: center;
        }
        .alert-yellow-icon {
            font-size: 16px;
            margin-right: 10px;
        }
        .alert-yellow-text {
            font-size: 14px;
            color: #92400e;
            margin: 0;
            font-weight: 500;
        }
        .alert-red {
            background-color: #fef2f2;
            border: 1px solid #fca5a5;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
            display: flex;
            align-items: center;
        }
        .alert-red-icon {
            font-size: 16px;
            margin-right: 10px;
        }
        .alert-red-text {
            font-size: 14px;
            color: #dc2626;
            margin: 0;
            font-weight: 500;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer-text {
            font-size: 12px;
            color: #6b7280;
            margin: 0;
        }
        .footer-link {
            color: #3b82f6;
            text-decoration: none;
        }
        .footer-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header Banner -->
        <div class="header-banner">
            <div class="logo-container">
                <div class="logo-icon">🔒</div>
                <h1 class="logo-text">Taskly</h1>
            </div>
            <p class="tagline">Tu organizador personal</p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2 class="greeting">¡Hola {{ $user->name }}!</h2>
            
            <p class="message">
                Gracias por registrarte en Taskly. Para verificar tu cuenta y comenzar a organizar tus tareas, utiliza el siguiente código de verificación:
            </p>

            <!-- Verification Code Box -->
            <div class="code-container">
                <p class="code-label">Código de Verificación</p>
                <p class="code">{{ $otpCode }}</p>
            </div>

            <!-- Yellow Alert -->
            <div class="alert-yellow">
                <span class="alert-yellow-icon">🕐</span>
                <p class="alert-yellow-text">Este código expirará en 10 minutos</p>
            </div>

            <!-- Red Alert -->
            <div class="alert-red">
                <span class="alert-red-icon">⚠️</span>
                <p class="alert-red-text">Si no solicitaste este código, puedes ignorar este mensaje de forma segura.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="footer-text">
                Este mensaje fue enviado por <strong>Taskly</strong><br>
                Si tienes alguna pregunta, contacta a nuestro 
                <a href="#" class="footer-link">equipo de soporte</a>
            </p>
        </div>
    </div>
</body>
</html>
