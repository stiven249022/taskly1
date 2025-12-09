<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly | Registro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-study {
            background: linear-gradient(135deg, #4F46E5, #06B6D4);
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
        }
        .gradient-text {
            background: linear-gradient(135deg, #4F46E5, #06B6D4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-4xl flex flex-col md:flex-row rounded-xl overflow-hidden shadow-2xl">
        <!-- Presentation Section -->
        <div class="bg-study text-white p-10 flex flex-col justify-center md:w-1/2">
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
                <p class="opacity-90 mb-6">Tu asistente personal para organizar tareas, proyectos y recordatorios académicos.</p>
                
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
                
                <div class="text-center">
                    <p class="text-sm opacity-80 mb-3">¿Ya tienes una cuenta?</p>
                    <a href="{{ route('login') }}" class="inline-block px-6 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full font-medium transition-all">
                        Iniciar Sesión
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Registration Form -->
        <div class="bg-white p-10 md:w-1/2 flex flex-col justify-center">
            <div class="text-center md:text-left mb-8">
                <h2 class="text-2xl font-bold gradient-text">Crear Cuenta</h2>
                <p class="text-gray-500 mt-1">Comienza a organizar tu vida académica hoy mismo.</p>
            </div>
            
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" id="name" name="name" required 
                               class="pl-10 w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 input-focus transition duration-300 @error('name') border-red-500 @enderror"
                               placeholder="Tu nombre completo" value="{{ old('name') }}">
                    </div>
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
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
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Usuario</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user-graduate text-gray-400"></i>
                        </div>
                        <input type="text" id="role" name="role" value="student" readonly
                               class="pl-10 w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-100 text-gray-600 cursor-not-allowed @error('role') border-red-500 @enderror">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Solo se permite registro como Estudiante. Los profesores y administradores son creados por el administrador del sistema.</p>
                    @error('role')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" id="password" name="password" required 
                                   class="pl-10 w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 input-focus transition duration-300 @error('password') border-red-500 @enderror"
                                   placeholder="">
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center toggle-password" onclick="togglePassword('password', this)">
                                <i class="far fa-eye text-gray-400 hover:text-gray-600 cursor-pointer"></i>
                            </button>
                        </div>
                        <div class="text-xs text-gray-500 mt-1 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i> La contraseña debe tener al menos 8 caracteres, incluyendo letras mayúsculas, minúsculas, números y caracteres especiales
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" required 
                                   class="pl-10 w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 input-focus transition duration-300"
                                   placeholder="">
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center toggle-password" onclick="togglePassword('password_confirmation', this)">
                                <i class="far fa-eye text-gray-400 hover:text-gray-600 cursor-pointer"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" required 
                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="text-gray-700">
                            Acepto los <a href="{{ route('terms') }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">Términos de Servicio</a> y 
                            <a href="{{ route('privacy') }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">Política de Privacidad</a>
                        </label>
                    </div>
                </div>
                
                <button type="submit" 
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white py-3 px-4 rounded-lg font-semibold transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Crear Cuenta <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </form>
        </div>
    </div>
    
    <!-- Botón Volver -->
    <a href="/" class="fixed bottom-6 left-6 z-50 bg-blue-700 hover:bg-blue-800 text-white font-medium py-3 px-4 rounded-lg shadow-md transition duration-300 transform hover:scale-105 flex items-center justify-center">
        <i class="fas fa-arrow-left mr-2"></i>
        Volver al inicio
    </a>
    
    <script>
        // Toggle password visibility
        function togglePassword(fieldId, icon) {
            const field = document.getElementById(fieldId);
            const iconElement = icon.querySelector('i');
            
            if (field.type === "password") {
                field.type = "text";
                iconElement.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                field.type = "password";
                iconElement.classList.replace("fa-eye-slash", "fa-eye");
            }
        }

        // Floating book animation
        document.addEventListener('DOMContentLoaded', function() {
            const book = document.getElementById('floatingBook');
            
            function floatAnimation() {
                book.style.transition = 'transform 3s ease-in-out';
                book.style.transform = 'translateY(-10px)';
                
                setTimeout(() => {
                    book.style.transform = 'translateY(5px)';
                    
                    setTimeout(() => {
                        book.style.transform = 'translateY(-10px)';
                        setTimeout(floatAnimation, 3000);
                    }, 3000);
                }, 3000);
            }
            
            floatAnimation();
            
            book.addEventListener('mouseenter', () => {
                book.style.transition = 'transform 0.3s ease';
                book.style.transform = 'scale(1.1) rotate(-5deg)';
            });
            
            book.addEventListener('mouseleave', () => {
                book.style.transition = 'transform 0.5s ease';
                book.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>
