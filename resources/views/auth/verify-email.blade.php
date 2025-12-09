<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Email - Taskly</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #3a58dfff 0%, #93c9fbff 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
       
        
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4 relative">
    <!-- Floating Shapes Background -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <!-- Main Content -->
    <div class="relative z-10 w-full max-w-lg">
        <!-- Logo/Brand Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-white/20 backdrop-blur-lg rounded-full mb-6 pulse-animation">
                <i class="fas fa-tasks text-4xl text-white"></i>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2 tracking-tight">Taskly</h1>
            <p class="text-white/80 text-lg">Tu organizador personal</p>
        </div>
        
        <!-- Verification Card -->
        <div class="glass-effect rounded-3xl p-8 card-hover">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-envelope-open text-3xl text-white"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-3">Verifica tu Email</h2>
                <p class="text-white/80 leading-relaxed">
                    Para acceder a todas las funcionalidades de Taskly, necesitamos verificar tu dirección de correo electrónico
                </p>
            </div>

            <!-- Email Display -->
            <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-6 mb-8">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-white/60 text-sm font-medium uppercase tracking-wide">Email de Verificación</p>
                        <p class="text-white font-semibold text-lg">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-gradient-to-r from-blue-500/20 to-purple-500/20 border border-white/20 rounded-2xl p-6 mb-8">
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 bg-blue-500/30 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-lightbulb text-blue-300 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold mb-2">¿Qué hacer ahora?</h3>
                        <ul class="text-white/80 space-y-2 text-sm">
                            <li class="flex items-center space-x-2">
                                <i class="fas fa-check-circle text-green-400 text-xs"></i>
                                <span>Revisa tu bandeja de entrada</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i class="fas fa-check-circle text-green-400 text-xs"></i>
                                <span>Haz clic en el enlace de verificación</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <i class="fas fa-check-circle text-green-400 text-xs"></i>
                                <span>¡Listo! Accede a todas las funciones</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" 
                            class="btn-primary w-full text-white font-semibold py-4 px-6 rounded-2xl text-lg flex items-center justify-center space-x-3 shadow-lg">
                        <i class="fas fa-paper-plane"></i>
                        <span>Reenviar Email de Verificación</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="btn-secondary w-full text-white font-semibold py-4 px-6 rounded-2xl text-lg flex items-center justify-center space-x-3">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>

            <!-- Help Section -->
            <div class="mt-8 pt-6 border-t border-white/20">
                <div class="text-center">
                    <p class="text-white/60 text-sm mb-3">
                        ¿No recibiste el email? Revisa tu carpeta de spam
                    </p>
                    <div class="flex items-center justify-center space-x-6 text-sm">
                        <a href="#" class="text-white/80 hover:text-white transition-colors flex items-center space-x-2">
                            <i class="fas fa-question-circle"></i>
                            <span>Ayuda</span>
                        </a>
                        <a href="#" class="text-white/80 hover:text-white transition-colors flex items-center space-x-2">
                            <i class="fas fa-headset"></i>
                            <span>Soporte</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-white/60 text-sm">
                © 2024 Taskly. Todos los derechos reservados.
            </p>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Auto-refresh cada 30 segundos para verificar si el email fue verificado
        setTimeout(function() {
            window.location.reload();
        }, 30000);
        
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.card-hover');
            
            card.addEventListener('mousemove', function(e) {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = (y - centerY) / 10;
                const rotateY = (centerX - x) / 10;
                
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(10px)`;
            });
            
            card.addEventListener('mouseleave', function() {
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateZ(0px)';
            });
        });
    </script>
</body>
</html>
