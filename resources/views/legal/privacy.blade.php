<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad | Taskly</title>
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
                        <i class="fas fa-shield-alt text-2xl text-blue-700"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold mb-2 text-gray-900">Política de Privacidad</h1>
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
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">1</span>
                            Introducción
                        </h2>
                        <p class="text-gray-600 leading-relaxed">
                            En Taskly, nos comprometemos a proteger tu privacidad. Esta Política de Privacidad explica cómo recopilamos, usamos, divulgamos y protegemos tu información personal cuando utilizas nuestro servicio.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 2 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">2</span>
                            Información que Recopilamos
                        </h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Recopilamos diferentes tipos de información para proporcionar y mejorar nuestro servicio:
                        </p>
                        
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-user-circle mr-2 text-blue-600"></i>
                                    2.1. Información Personal
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-check text-blue-600"></i>
                                        <span class="text-gray-700 text-sm">Nombre completo</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-check text-blue-600"></i>
                                        <span class="text-gray-700 text-sm">Correo electrónico</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-check text-blue-600"></i>
                                        <span class="text-gray-700 text-sm">Información de perfil</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-check text-blue-600"></i>
                                        <span class="text-gray-700 text-sm">Rol en la plataforma</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-chart-line mr-2 text-blue-600"></i>
                                    2.2. Información de Uso
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-check text-blue-600"></i>
                                        <span class="text-gray-700 text-sm">Tareas y proyectos</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-check text-blue-600"></i>
                                        <span class="text-gray-700 text-sm">Actividad en la plataforma</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-check text-blue-600"></i>
                                        <span class="text-gray-700 text-sm">Preferencias de usuario</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-check text-blue-600"></i>
                                        <span class="text-gray-700 text-sm">Configuraciones</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-server mr-2 text-blue-600"></i>
                                    2.3. Información Técnica
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-check text-blue-600"></i>
                                        <span class="text-gray-700 text-sm">Dirección IP</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-check text-blue-600"></i>
                                        <span class="text-gray-700 text-sm">Tipo de navegador</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-check text-blue-600"></i>
                                        <span class="text-gray-700 text-sm">Dispositivo utilizado</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-check text-blue-600"></i>
                                        <span class="text-gray-700 text-sm">Registros de acceso</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">3</span>
                            Cómo Usamos tu Información
                        </h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Utilizamos la información recopilada para:
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="bg-gray-50 rounded-lg p-3 flex items-start space-x-3">
                                <i class="fas fa-rocket text-blue-600 mt-1"></i>
                                <span class="text-gray-700 text-sm">Proporcionar, mantener y mejorar nuestro servicio</span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 flex items-start space-x-3">
                                <i class="fas fa-user-cog text-blue-600 mt-1"></i>
                                <span class="text-gray-700 text-sm">Personalizar tu experiencia en la plataforma</span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 flex items-start space-x-3">
                                <i class="fas fa-bell text-blue-600 mt-1"></i>
                                <span class="text-gray-700 text-sm">Enviar notificaciones sobre tareas y proyectos</span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 flex items-start space-x-3">
                                <i class="fas fa-credit-card text-blue-600 mt-1"></i>
                                <span class="text-gray-700 text-sm">Procesar transacciones y gestionar cuentas</span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 flex items-start space-x-3">
                                <i class="fas fa-shield-alt text-blue-600 mt-1"></i>
                                <span class="text-gray-700 text-sm">Detectar y prevenir fraudes</span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 flex items-start space-x-3">
                                <i class="fas fa-gavel text-blue-600 mt-1"></i>
                                <span class="text-gray-700 text-sm">Cumplir con obligaciones legales</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">4</span>
                            Compartir Información
                        </h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            No vendemos tu información personal. Podemos compartir tu información solo en las siguientes circunstancias:
                        </p>
                        <div class="space-y-3">
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-200">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-handshake text-blue-600 mt-1"></i>
                                    <div>
                                        <strong class="text-gray-900">Con tu consentimiento:</strong>
                                        <span class="text-gray-700"> Cuando nos autorizas explícitamente</span>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-200">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-building text-blue-600 mt-1"></i>
                                    <div>
                                        <strong class="text-gray-900">Proveedores de servicios:</strong>
                                        <span class="text-gray-700"> Con terceros bajo estrictos acuerdos de confidencialidad</span>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-200">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-balance-scale text-blue-600 mt-1"></i>
                                    <div>
                                        <strong class="text-gray-900">Requisitos legales:</strong>
                                        <span class="text-gray-700"> Cuando es necesario cumplir con la ley</span>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-200">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-lock text-blue-600 mt-1"></i>
                                    <div>
                                        <strong class="text-gray-900">Protección de derechos:</strong>
                                        <span class="text-gray-700"> Para proteger nuestros derechos y seguridad</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 5 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">5</span>
                            Seguridad de los Datos
                        </h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Implementamos medidas de seguridad técnicas y organizativas apropiadas para proteger tu información personal:
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="bg-gray-50 rounded-lg p-3 flex items-center space-x-3">
                                <i class="fas fa-key text-blue-600"></i>
                                <span class="text-gray-700 text-sm">Cifrado de datos</span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 flex items-center space-x-3">
                                <i class="fas fa-user-shield text-blue-600"></i>
                                <span class="text-gray-700 text-sm">Autenticación segura</span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 flex items-center space-x-3">
                                <i class="fas fa-ban text-blue-600"></i>
                                <span class="text-gray-700 text-sm">Acceso restringido</span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 flex items-center space-x-3">
                                <i class="fas fa-eye text-blue-600"></i>
                                <span class="text-gray-700 text-sm">Monitoreo regular</span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 flex items-center space-x-3 md:col-span-2">
                                <i class="fas fa-sync-alt text-blue-600"></i>
                                <span class="text-gray-700 text-sm">Actualizaciones de seguridad regulares</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 6 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">6</span>
                            Tus Derechos
                        </h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Tienes derecho a:
                        </p>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-eye text-blue-600"></i>
                                <span class="text-gray-700">Acceder a tu información personal</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-edit text-blue-600"></i>
                                <span class="text-gray-700">Corregir información inexacta</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-trash text-blue-600"></i>
                                <span class="text-gray-700">Solicitar la eliminación de tus datos</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-ban text-blue-600"></i>
                                <span class="text-gray-700">Oponerte al procesamiento de tus datos</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-download text-blue-600"></i>
                                <span class="text-gray-700">Exportar tus datos</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-undo text-blue-600"></i>
                                <span class="text-gray-700">Retirar tu consentimiento en cualquier momento</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 7 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-cookie"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">7</span>
                            Cookies y Tecnologías Similares
                        </h2>
                        <p class="text-gray-600 leading-relaxed">
                            Utilizamos cookies y tecnologías similares para mejorar tu experiencia, analizar el uso del servicio y personalizar el contenido. Puedes gestionar tus preferencias de cookies a través de la configuración de tu navegador.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 8 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-child"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">8</span>
                            Privacidad de Menores
                        </h2>
                        <p class="text-gray-600 leading-relaxed">
                            Nuestro servicio está dirigido a usuarios mayores de 13 años. No recopilamos intencionalmente información personal de menores de 13 años. Si descubrimos que hemos recopilado información de un menor sin el consentimiento de los padres, tomaremos medidas para eliminar esa información.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 9 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">9</span>
                            Transferencias Internacionales
                        </h2>
                        <p class="text-gray-600 leading-relaxed">
                            Tu información puede ser transferida y procesada en países distintos al tuyo. Al usar nuestro servicio, consientes la transferencia de tu información a estos países, donde las leyes de protección de datos pueden diferir.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 10 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">10</span>
                            Cambios a esta Política
                        </h2>
                        <p class="text-gray-600 leading-relaxed">
                            Podemos actualizar esta Política de Privacidad ocasionalmente. Te notificaremos de cualquier cambio publicando la nueva política en esta página y actualizando la fecha de "Última actualización". Te recomendamos revisar esta política periódicamente.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 11 -->
            <div class="bg-white rounded-xl shadow-lg p-6 section-card">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl flex-shrink-0">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold mr-2">11</span>
                            Contacto
                        </h2>
                        <p class="text-gray-600 leading-relaxed">
                            Si tienes preguntas o inquietudes sobre esta Política de Privacidad o sobre cómo manejamos tu información personal, puedes contactarnos a través de los canales de soporte disponibles en la plataforma.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer Card -->
            <div class="bg-white border-l-4 border-blue-700 rounded-xl shadow-lg p-6 text-center">
                <i class="fas fa-shield-check text-3xl mb-3 text-blue-700"></i>
                <p class="text-lg font-semibold text-gray-900">
                    Al usar Taskly, reconoces que has leído y entendido esta Política de Privacidad.
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
