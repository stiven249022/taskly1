@extends('layouts.app')

@section('title', 'Configuración')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Configuración</h1>
            <p class="text-gray-600 mt-2">Gestiona todas las configuraciones de tu cuenta</p>
        </div>

        <!-- Grid de Configuración -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Configuración General -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-user text-blue-600 text-xl"></i>
                    </div>
                    <h2 class="text-lg font-medium text-gray-800 ml-4">General</h2>
                </div>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('settings.general') }}" class="flex items-center justify-between text-gray-600 hover:text-gray-900">
                            <span>Información Personal</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.profile') }}" class="flex items-center justify-between text-gray-600 hover:text-gray-900">
                            <span>Perfil</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-between text-gray-600 hover:text-gray-900">
                            <span>Preferencias</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Configuración de Notificaciones -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-bell text-green-600 text-xl"></i>
                    </div>
                    <h2 class="text-lg font-medium text-gray-800 ml-4">Notificaciones</h2>
                </div>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('settings.notifications') }}" class="flex items-center justify-between text-gray-600 hover:text-gray-900">
                            <span>Configuración de Notificaciones</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('notifications.index') }}" class="flex items-center justify-between text-gray-600 hover:text-gray-900">
                            <span>Historial de Notificaciones</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('notifications.settings') }}" class="flex items-center justify-between text-gray-600 hover:text-gray-900">
                            <span>Preferencias de Notificaciones</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Configuración de Seguridad -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <i class="fas fa-shield-alt text-yellow-600 text-xl"></i>
                    </div>
                    <h2 class="text-lg font-medium text-gray-800 ml-4">Seguridad</h2>
                </div>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('settings.security') }}" class="flex items-center justify-between text-gray-600 hover:text-gray-900">
                            <span>Configuración de Seguridad</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-between text-gray-600 hover:text-gray-900">
                            <span>Autenticación de Dos Factores</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-between text-gray-600 hover:text-gray-900">
                            <span>Sesiones Activas</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Privacidad y Datos -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-lock text-purple-600 text-xl"></i>
                    </div>
                    <h2 class="text-lg font-medium text-gray-800 ml-4">Privacidad</h2>
                </div>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('settings.export-data') }}" class="flex items-center justify-between text-gray-600 hover:text-gray-900">
                            <span>Exportar Mis Datos</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-between text-gray-600 hover:text-gray-900">
                            <span>Política de Privacidad</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-between text-gray-600 hover:text-gray-900">
                            <span>Términos de Servicio</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Integraciones -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-indigo-100 rounded-lg">
                        <i class="fas fa-plug text-indigo-600 text-xl"></i>
                    </div>
                    <h2 class="text-lg font-medium text-gray-800 ml-4">Integraciones</h2>
                </div>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="flex items-center justify-between text-gray-600 hover:text-gray-900">
                            <span>Google Calendar</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-between text-gray-600 hover:text-gray-900">
                            <span>Slack</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-between text-gray-600 hover:text-gray-900">
                            <span>Microsoft Teams</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Ayuda y Soporte -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <i class="fas fa-question-circle text-red-600 text-xl"></i>
                    </div>
                    <h2 class="text-lg font-medium text-gray-800 ml-4">Ayuda</h2>
                </div>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="flex items-center justify-between text-gray-600 hover:text-gray-900">
                            <span>Centro de Ayuda</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-between text-gray-600 hover:text-gray-900">
                            <span>Contactar Soporte</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-between text-gray-600 hover:text-gray-900">
                            <span>Reportar un Problema</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Acciones Importantes -->
        <div class="mt-8">
            <h2 class="text-lg font-medium text-gray-800 mb-4">Acciones Importantes</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-800">Eliminar Cuenta</h3>
                            <p class="text-sm text-gray-600 mt-1">Elimina permanentemente tu cuenta y todos tus datos</p>
                        </div>
                        <button onclick="confirmDeleteAccount()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Eliminar
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-800">Exportar Datos</h3>
                            <p class="text-sm text-gray-600 mt-1">Descarga una copia de todos tus datos</p>
                        </div>
                        <button onclick="exportData()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Exportar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDeleteAccount() {
    if (confirm('¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.')) {
        const password = prompt('Ingresa tu contraseña para confirmar:');
        if (password) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("settings.delete-account") }}';
            
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '{{ csrf_token() }}';
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            const passwordInput = document.createElement('input');
            passwordInput.type = 'hidden';
            passwordInput.name = 'password';
            passwordInput.value = password;
            
            form.appendChild(tokenInput);
            form.appendChild(methodInput);
            form.appendChild(passwordInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
}

function exportData() {
    fetch('{{ route("settings.export-data") }}')
    .then(response => response.json())
    .then(data => {
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'taskly-data.json';
        a.click();
        window.URL.revokeObjectURL(url);
    });
}
</script>
@endsection 