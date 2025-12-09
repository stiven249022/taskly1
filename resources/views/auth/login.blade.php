<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly | Iniciar Sesión</title>
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
        .gmail-btn:hover {
            box-shadow: 0 8px 25px rgba(234, 67, 53, 0.3);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-4xl flex flex-col md:flex-row rounded-xl overflow-hidden shadow-2xl">
        <div class="bg-study text-white p-10 flex flex-col justify-center items-center md:w-1/2">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div id="floatingBook" class="relative w-20 h-20">
                        <div class="absolute w-full h-full flex justify-center items-center">
                            <div class="absolute left-0 w-1/2 h-3/4 bg-white bg-opacity-30 rounded-l-lg transform -rotate-6 origin-right shadow-lg"></div>
                            <div class="absolute right-0 w-1/2 h-3/4 bg-white bg-opacity-30 rounded-r-lg transform rotate-6 origin-left shadow-lg"></div>
                            <div class="absolute w-1 h-3/4 bg-white bg-opacity-40"></div>
                        </div>
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white text-2xl">
                            <i class="fas fa-book-open"></i>
                        </div>
                    </div>
                </div>
                <h1 class="text-3xl font-bold mb-2">Taskly</h1>
                <p class="opacity-90 mb-6">Perfecto para organizar tus estudios</p>
                
                <div class="space-y-4 mb-8">
                    <div class="flex items-start">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3 mt-1">
                            <i class="fas fa-tasks text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Gestión de Tareas</h3>
                            <p class="text-sm opacity-80">Organiza tus tareas por prioridad y fecha límite.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3 mt-1">
                            <i class="fas fa-project-diagram text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Proyectos Académicos</h3>
                            <p class="text-sm opacity-80">Divide tus proyectos en etapas manejables.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3 mt-1">
                            <i class="fas fa-bell text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Recordatorios Inteligentes</h3>
                            <p class="text-sm opacity-80">Nunca más olvides una fecha importante.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-10 md:w-1/2 flex flex-col justify-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Iniciar Sesión</h2>
            <p class="text-gray-600 mb-8">Ingresa a tu cuenta para administrar tus proyectos</p>
            
            <!-- Mostrar errores de sesión -->
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif
            
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" id="email" name="email" required 
                            class="pl-10 w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 input-focus transition duration-300 @error('email') border-red-500 @enderror"
                            placeholder="tu@email.com" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" id="password" name="password" required 
                            class="pl-10 w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 input-focus transition duration-300 @error('password') border-red-500 @enderror"
                            placeholder="Tu contraseña">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="passwordIcon" class="far fa-eye text-gray-400 hover:text-gray-600 cursor-pointer"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Recuérdame</label>
                    </div>
                    @if (Route::has('password.request'))
                       <a href="{{ route('password.request') }}" class="text-sm text-blue-800 hover:text-blue-900">¿Olvidaste tu contraseña?</a>
                    @endif
                </div>
                
                <div>
                    <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-medium py-3 px-4 rounded-lg shadow-md transition duration-300 transform hover:scale-105">
                         Iniciar Sesión
                    </button>
                </div>
                
                <div class="text-center text-sm">
                    <p class="text-gray-600">¿Nuevo en Taskly? 
                        <a href="{{ route('register') }}" class="text-blue-700 font-medium hover:text-blue-900">Regístrate aquí</a>
                    </p>
                </div>
            </form>
            
            <!-- Separador -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">o</span>
                </div>
            </div>
            
            <!-- Botón de Gmail -->
<div class="mb-6">
    <a href="{{ route('auth.google') }}" class="gmail-btn w-full bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-4 rounded-lg shadow-md transition duration-300 transform hover:scale-105 flex items-center justify-center space-x-2">
        <i class="fab fa-google"></i>
        <span>Continuar con Google</span>
    </a>
</div>
            
            <div class="mt-8 border-t border-gray-200 pt-6">
                <p class="text-xs text-gray-500 text-center">Al iniciar sesión aceptas nuestros 
                     <a href="{{ route('terms') }}" target="_blank" rel="noopener noreferrer" class="text-blue-700 hover:text-blue-900 hover:underline">Términos de servicio</a> y 
                     <a href="{{ route('privacy') }}" target="_blank" rel="noopener noreferrer" class="text-blue-700 hover:text-blue-900 hover:underline">Política de privacidad</a>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Botón Volver -->
    <a href="/" class="fixed bottom-6 left-6 z-50 bg-blue-700 hover:bg-blue-800 text-white font-medium py-3 px-4 rounded-lg shadow-md transition duration-300 transform hover:scale-105 flex items-center justify-center">
        <i class="fas fa-arrow-left mr-2"></i>
        Volver al inicio
    </a>
    
    <script>
        // Toggle para mostrar/ocultar contraseña
        document.getElementById('togglePassword')?.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('passwordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>