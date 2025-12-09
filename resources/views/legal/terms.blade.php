<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos de Servicio | Taskly</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .section-card {
            transition: all 0.3s ease;
        }
        .section-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-12 max-w-4xl">
        <!-- Header -->
        <div class="bg-white border-l-4 border-blue-700 p-8 rounded-xl shadow-lg mb-8">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <i class="fas fa-file-contract text-2xl text-blue-700"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold mb-2 text-gray-900">Términos de Servicio</h1>
                        <p class="text-gray-600 flex items-center text-sm">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Última actualización: {{ date('d/m/Y') }}
                        </p>
                    </div>
                </div>
                <a href="{{ url()->previous() ?: route('login') }}" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all">
                    <i class="fas fa-times text-gray-600"></i>
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="space-y-6">
            <!-- Section 1 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">1</span>
                            Aceptación de los Términos
                        </h2>
                        <p class="text-gray-600 leading-relaxed">
                            Al acceder y utilizar Taskly, aceptas cumplir con estos Términos de Servicio y todas las leyes y regulaciones aplicables. Si no estás de acuerdo con alguno de estos términos, no debes usar nuestro servicio.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 2 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">2</span>
                            Uso del Servicio
                        </h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Taskly es una plataforma diseñada para ayudar a estudiantes y profesores a organizar tareas, proyectos y actividades académicas. Al usar nuestro servicio, te comprometes a:
                        </p>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                <span class="text-gray-700">Proporcionar información precisa y actualizada</span>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                <span class="text-gray-700">Mantener la seguridad de tu cuenta y contraseña</span>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                <span class="text-gray-700">No usar el servicio para fines ilegales o no autorizados</span>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                <span class="text-gray-700">No interferir con el funcionamiento del servicio</span>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                <span class="text-gray-700">Respetar los derechos de otros usuarios</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">3</span>
                            Cuentas de Usuario
                        </h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Para acceder a ciertas funcionalidades de Taskly, necesitas crear una cuenta. Eres responsable de:
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="bg-gray-50 rounded-lg p-3 flex items-center space-x-2">
                                <i class="fas fa-key text-blue-600"></i>
                                <span class="text-gray-700 text-sm">Mantener la confidencialidad de tu contraseña</span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 flex items-center space-x-2">
                                <i class="fas fa-user-check text-purple-500"></i>
                                <span class="text-gray-700 text-sm">Todas las actividades bajo tu cuenta</span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 flex items-center space-x-2">
                                <i class="fas fa-bell text-purple-500"></i>
                                <span class="text-gray-700 text-sm">Notificar uso no autorizado</span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 flex items-center space-x-2">
                                <i class="fas fa-address-card text-purple-500"></i>
                                <span class="text-gray-700 text-sm">Información de contacto actualizada</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">4</span>
                            Propiedad Intelectual
                        </h2>
                        <p class="text-gray-600 leading-relaxed">
                            Todo el contenido de Taskly, incluyendo pero no limitado a textos, gráficos, logos, iconos, imágenes y software, es propiedad de Taskly o sus proveedores de contenido y está protegido por las leyes de propiedad intelectual.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 5 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">5</span>
                            Conducta Prohibida
                        </h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            No debes:
                        </p>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-2 border-l-4 border-gray-300">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-times-circle text-gray-500 mt-1"></i>
                                <span class="text-gray-700">Usar el servicio para actividades ilegales</span>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-times-circle text-red-500 mt-1"></i>
                                <span class="text-gray-700">Intentar acceder a cuentas de otros usuarios</span>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-times-circle text-red-500 mt-1"></i>
                                <span class="text-gray-700">Transmitir virus, malware o código malicioso</span>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-times-circle text-red-500 mt-1"></i>
                                <span class="text-gray-700">Realizar ingeniería inversa del software</span>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-times-circle text-red-500 mt-1"></i>
                                <span class="text-gray-700">Usar bots o scripts automatizados sin autorización</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 6 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">6</span>
                            Limitación de Responsabilidad
                        </h2>
                        <p class="text-gray-600 leading-relaxed">
                            Taskly se proporciona "tal cual" y "según disponibilidad". No garantizamos que el servicio esté libre de errores, interrupciones o defectos. No seremos responsables de ningún daño indirecto, incidental o consecuente derivado del uso del servicio.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 7 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">7</span>
                            Modificaciones de los Términos
                        </h2>
                        <p class="text-gray-600 leading-relaxed">
                            Nos reservamos el derecho de modificar estos términos en cualquier momento. Las modificaciones entrarán en vigor inmediatamente después de su publicación. Es tu responsabilidad revisar periódicamente estos términos.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 8 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">8</span>
                            Ley Aplicable
                        </h2>
                        <p class="text-gray-600 leading-relaxed">
                            Estos términos se rigen por las leyes del país donde opera Taskly. Cualquier disputa relacionada con estos términos será resuelta en los tribunales competentes de dicha jurisdicción.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 9 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">9</span>
                            Contacto
                        </h2>
                        <p class="text-gray-600 leading-relaxed">
                            Si tienes preguntas sobre estos Términos de Servicio, puedes contactarnos a través de los canales de soporte disponibles en la plataforma.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer Card -->
            <div class="bg-white border-l-4 border-blue-700 rounded-xl shadow-lg p-6 text-center">
                <i class="fas fa-check-circle text-3xl mb-3 text-blue-700"></i>
                <p class="text-lg font-semibold text-gray-900">
                    Al usar Taskly, reconoces que has leído, entendido y aceptas estos Términos de Servicio.
                </p>
            </div>
        </div>

        <!-- Footer Navigation -->
        <div class="text-center mt-8">
            <a href="{{ url()->previous() ?: route('login') }}" class="inline-flex items-center px-6 py-3 bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded-full shadow-lg transition-all transform hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver
            </a>
        </div>
    </div>
</body>
</html>
