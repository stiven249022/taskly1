<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly | Restablecer Contraseña</title>
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
        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s;
        }
        .strength-weak { background-color: #ef4444; width: 33%; }
        .strength-medium { background-color: #f59e0b; width: 66%; }
        .strength-strong { background-color: #10b981; width: 100%; }
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
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                </div>
                <h1 class="text-3xl font-bold mb-2">Nueva Contraseña</h1>
                <p class="opacity-90 mb-6">Crea una contraseña segura para tu cuenta</p>
                
                <div class="space-y-4 mb-8">
                    <div class="flex items-start">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3 mt-1">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Contraseña Segura</h3>
                            <p class="text-sm opacity-80">Usa al menos 8 caracteres con letras y números.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3 mt-1">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Confirma tu Contraseña</h3>
                            <p class="text-sm opacity-80">Asegúrate de que ambas contraseñas coincidan.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3 mt-1">
                            <i class="fas fa-key text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Acceso Inmediato</h3>
                            <p class="text-sm opacity-80">Podrás iniciar sesión con tu nueva contraseña.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-10 md:w-1/2 flex flex-col justify-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Restablecer Contraseña</h2>
            <p class="text-gray-600 mb-8">Ingresa tu nueva contraseña para completar el proceso</p>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                               value="{{ old('email', $request->email) }}" 
                               required 
                               autofocus
                               autocomplete="username"
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

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               autocomplete="new-password"
                               class="pl-10 pr-10 w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 input-focus transition duration-300 @error('password') border-red-500 @enderror"
                               placeholder="Tu nueva contraseña">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="passwordIcon" class="far fa-eye text-gray-400 hover:text-gray-600 cursor-pointer"></i>
                        </button>
                    </div>
                    <div id="passwordStrength" class="mt-2 hidden">
                        <div class="password-strength"></div>
                        <p class="text-xs mt-1 text-gray-500" id="strengthText"></p>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-500 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               class="pl-10 pr-10 w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 input-focus transition duration-300 @error('password_confirmation') border-red-500 @enderror"
                               placeholder="Confirma tu contraseña">
                        <button type="button" id="togglePasswordConfirmation" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="passwordConfirmationIcon" class="far fa-eye text-gray-400 hover:text-gray-600 cursor-pointer"></i>
                        </button>
                    </div>
                    <div id="passwordMatch" class="mt-2 hidden">
                        <p class="text-xs flex items-center" id="matchText"></p>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-1 text-xs text-red-500 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg shadow-md transition duration-300 transform hover:scale-105 flex items-center justify-center space-x-2">
                        <i class="fas fa-check-circle"></i>
                        <span>Restablecer Contraseña</span>
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

        // Toggle para mostrar/ocultar confirmación de contraseña
        document.getElementById('togglePasswordConfirmation')?.addEventListener('click', function() {
            const passwordInput = document.getElementById('password_confirmation');
            const icon = document.getElementById('passwordConfirmationIcon');
            
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

        // Validación de fuerza de contraseña
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');
        const strengthBar = document.getElementById('passwordStrength');
        const strengthIndicator = strengthBar.querySelector('.password-strength');
        const strengthText = document.getElementById('strengthText');
        const passwordMatch = document.getElementById('passwordMatch');
        const matchText = document.getElementById('matchText');

        passwordInput?.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            
            if (password.length > 0) {
                strengthBar.classList.remove('hidden');
                strengthIndicator.className = 'password-strength ' + strength.class;
                strengthText.textContent = strength.text;
                strengthText.className = 'text-xs mt-1 ' + strength.textColor;
            } else {
                strengthBar.classList.add('hidden');
            }

            // Verificar coincidencia
            checkPasswordMatch();
        });

        passwordConfirmationInput?.addEventListener('input', function() {
            checkPasswordMatch();
        });

        function calculatePasswordStrength(password) {
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z\d]/.test(password)) strength++;

            if (strength <= 2) {
                return { class: 'strength-weak', text: 'Contraseña débil', textColor: 'text-red-500' };
            } else if (strength <= 4) {
                return { class: 'strength-medium', text: 'Contraseña media', textColor: 'text-yellow-500' };
            } else {
                return { class: 'strength-strong', text: 'Contraseña fuerte', textColor: 'text-green-500' };
            }
        }

        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmation = passwordConfirmationInput.value;
            
            if (confirmation.length > 0) {
                passwordMatch.classList.remove('hidden');
                if (password === confirmation) {
                    matchText.innerHTML = '<i class="fas fa-check-circle text-green-500 mr-1"></i> Las contraseñas coinciden';
                    matchText.className = 'text-xs flex items-center text-green-500';
                } else {
                    matchText.innerHTML = '<i class="fas fa-times-circle text-red-500 mr-1"></i> Las contraseñas no coinciden';
                    matchText.className = 'text-xs flex items-center text-red-500';
                }
            } else {
                passwordMatch.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
