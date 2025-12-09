<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly | Verificar Email</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        .otp-digit {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            background: #ffffff;
            color: #1e293b;
            transition: all 0.3s ease;
        }
        .otp-digit:focus {
            border-color: #4F46E5;
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        .otp-digit.filled {
            border-color: #4F46E5;
            background-color: #f8fafc;
            color: #4F46E5;
        }
        .otp-digit.error {
            border-color: #ef4444;
            background-color: #fef2f2;
            animation: shake 0.5s ease-in-out;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .otp-container {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin: 24px 0;
        }
        .shake {
            animation: shake 0.5s;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-4xl flex flex-col md:flex-row rounded-xl overflow-hidden shadow-2xl">
        <!-- Panel Izquierdo - Branding -->
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
                <p class="opacity-90 mb-6">Verifica tu email para completar el registro</p>
                
                <div class="space-y-4 mb-8">
                    <div class="flex items-start">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3 mt-1">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Verificación Segura</h3>
                            <p class="text-sm opacity-80">Protege tu cuenta con verificación por email.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3 mt-1">
                            <i class="fas fa-envelope text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Código de 6 dígitos</h3>
                            <p class="text-sm opacity-80">Ingresa el código que enviamos a tu email.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3 mt-1">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Expira en 10 minutos</h3>
                            <p class="text-sm opacity-80">El código tiene una duración limitada.</p>
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <p class="text-sm opacity-80 mb-3">¿No recibiste el código?</p>
                    <button onclick="resendCode()" class="inline-block px-6 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full font-medium transition-all">
                        Reenviar código
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Panel Derecho - Formulario -->
        <div class="bg-white p-10 md:w-1/2 flex flex-col justify-center">
            <div class="text-center md:text-left mb-8">
                <h2 class="text-2xl font-bold gradient-text">Verificar Email</h2>
                <p class="text-gray-500 mt-1">Ingresa el código de verificación que enviamos a tu email</p>
            </div>
            
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
            
            <!-- Formulario OTP -->
            <form method="POST" action="{{ route('otp.verify.submit') }}" id="otpForm">
                @csrf
                
                <!-- Contenedor de dígitos OTP -->
                <div class="otp-container">
                    <input type="text" class="otp-digit" maxlength="1" data-index="0" required>
                    <input type="text" class="otp-digit" maxlength="1" data-index="1" required>
                    <input type="text" class="otp-digit" maxlength="1" data-index="2" required>
                    <input type="text" class="otp-digit" maxlength="1" data-index="3" required>
                    <input type="text" class="otp-digit" maxlength="1" data-index="4" required>
                    <input type="text" class="otp-digit" maxlength="1" data-index="5" required>
                </div>
                
                <!-- Campo oculto para enviar el código completo -->
                <input type="hidden" name="otp_code" id="otp_code" required>

                <!-- Botón de verificación -->
                <button type="submit" 
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white py-3 px-4 rounded-lg font-semibold transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Verificar Código <i class="fas fa-check ml-2"></i>
                </button>
            </form>
            
            <!-- Enlaces adicionales -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    ¿Problemas con la verificación? 
                    <a href="{{ route('register') }}" class="text-blue-700 font-medium hover:text-blue-900">Registrarse de nuevo</a>
                </p>
            </div>
            
            <div class="mt-8 border-t border-gray-200 pt-6">
                <p class="text-xs text-gray-500 text-center">Al verificar tu email aceptas nuestros 
                     <a href="#" class="text-blue-700 hover:text-blue-900 hover:underline">Términos de servicio</a> y 
                     <a href="#" class="text-blue-700 hover:text-blue-900 hover:underline">Política de privacidad</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // OTP Input Logic
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-digit');
            const otpForm = document.getElementById('otpForm');
            const hiddenInput = document.getElementById('otp_code');

            // Auto-focus first input
            otpInputs[0].focus();

            // Handle input events
            otpInputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    const value = e.target.value;
                    
                    // Only allow numbers
                    if (!/^\d$/.test(value)) {
                        e.target.value = '';
                        return;
                    }

                    // Add filled class
                    e.target.classList.add('filled');
                    e.target.classList.remove('error');

                    // Move to next input
                    if (value && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }

                    // Update hidden input
                    updateHiddenInput();

                    // Auto-submit when all fields are filled
                    if (index === otpInputs.length - 1 && value) {
                        setTimeout(() => {
                            if (isAllFieldsFilled()) {
                                otpForm.submit();
                            }
                        }, 100);
                    }
                });

                // Handle backspace
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        otpInputs[index - 1].focus();
                        otpInputs[index - 1].classList.remove('filled');
                    }
                });

                // Handle paste
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text');
                    const numbers = pastedData.replace(/\D/g, '').slice(0, 6);
                    
                    numbers.split('').forEach((digit, i) => {
                        if (otpInputs[i]) {
                            otpInputs[i].value = digit;
                            otpInputs[i].classList.add('filled');
                            otpInputs[i].classList.remove('error');
                        }
                    });
                    
                    updateHiddenInput();
                    
                    // Focus last filled input or submit
                    const lastFilledIndex = Math.min(numbers.length - 1, otpInputs.length - 1);
                    if (otpInputs[lastFilledIndex]) {
                        otpInputs[lastFilledIndex].focus();
                    }
                    
                    if (isAllFieldsFilled()) {
                        setTimeout(() => otpForm.submit(), 100);
                    }
                });
            });

            function updateHiddenInput() {
                const otpValue = Array.from(otpInputs).map(input => input.value).join('');
                hiddenInput.value = otpValue;
            }

            function isAllFieldsFilled() {
                return Array.from(otpInputs).every(input => input.value.length === 1);
            }

            // Form submission
            otpForm.addEventListener('submit', function(e) {
                const otpValue = Array.from(otpInputs).map(input => input.value).join('');
                
                if (otpValue.length !== 6) {
                    e.preventDefault();
                    otpInputs.forEach(input => {
                        input.classList.add('error');
                    });
                    return;
                }
                
                hiddenInput.value = otpValue;
            });
        });

        // Resend code function
        function resendCode() {
            fetch('{{ route("otp.resend") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const successDiv = document.createElement('div');
                    successDiv.className = 'mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg';
                    successDiv.innerHTML = '<div class="flex items-center"><i class="fas fa-check-circle mr-2"></i>Código reenviado exitosamente</div>';
                    
                    const form = document.getElementById('otpForm');
                    form.parentNode.insertBefore(successDiv, form);
                    
                    // Remove after 5 seconds
                    setTimeout(() => successDiv.remove(), 5000);
                } else {
                    alert('Error al reenviar el código. Intenta nuevamente.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al reenviar el código. Intenta nuevamente.');
            });
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