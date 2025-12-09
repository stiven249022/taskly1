<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly | Recuperar Contraseña</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .bg-study {
            background: linear-gradient(135deg, #4F46E5, #06B6D4);
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
        }
        .shake {
            animation: shake 0.5s;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-4xl flex flex-col md:flex-row rounded-xl overflow-hidden shadow-2xl">
        <div class="bg-study text-white p-10 flex flex-col justify-center items-center md:w-1/2">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="relative w-20 h-20">
                        <div class="absolute w-full h-full flex justify-center items-center">
                            <div class="absolute left-0 w-1/2 h-3/4 bg-white bg-opacity-30 rounded-l-lg transform -rotate-6 origin-right shadow-lg"></div>
                            <div class="absolute right-0 w-1/2 h-3/4 bg-white bg-opacity-30 rounded-r-lg transform rotate-6 origin-left shadow-lg"></div>
                            <div class="absolute w-1 h-3/4 bg-white bg-opacity-40"></div>
                        </div>
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white text-2xl">
                            <i class="fas fa-key"></i>
                        </div>
                    </div>
                </div>
                <h1 class="text-3xl font-bold mb-2">Recuperar Contraseña</h1>
                <p class="opacity-90 mb-6">No te preocupes, te ayudaremos a recuperar tu cuenta</p>
                
                <div class="space-y-4 mb-8">
                    <div class="flex items-start">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3 mt-1">
                            <i class="fas fa-envelope text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Envío Seguro</h3>
                            <p class="text-sm opacity-80">Te enviaremos un enlace seguro a tu correo electrónico.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3 mt-1">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Proceso Rápido</h3>
                            <p class="text-sm opacity-80">Recupera tu contraseña en solo unos minutos.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3 mt-1">
                            <i class="fas fa-lock text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">100% Seguro</h3>
                            <p class="text-sm opacity-80">Tu información está protegida y encriptada.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-10 md:w-1/2 flex flex-col justify-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">¿Olvidaste tu contraseña?</h2>
            <p class="text-gray-600 mb-8">Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña</p>
            
            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus
                               class="pl-10 w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 input-focus transition duration-300 @error('email') border-red-500 @enderror"
                               placeholder="tu@email.com">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-500 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg shadow-md transition duration-300 transform hover:scale-105 flex items-center justify-center space-x-2">
                        <i class="fas fa-paper-plane"></i>
                        <span>Enviar Enlace de Recuperación</span>
                    </button>
                </div>
                
                <div class="text-center text-sm">
                    <p class="text-gray-600">¿Recordaste tu contraseña? 
                        <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:text-indigo-800">Iniciar sesión</a>
                    </p>
                </div>
            </form>
            
            <div class="mt-8 border-t border-gray-200 pt-6">
                <p class="text-xs text-gray-500 text-center">¿Necesitas ayuda? 
                    <a href="#" class="text-indigo-600 hover:text-indigo-800 hover:underline">Contáctanos</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
