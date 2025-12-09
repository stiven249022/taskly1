@extends('layouts.app')

@section('title', 'Configuración de Seguridad')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Configuración de Seguridad</h1>
            <p class="text-gray-600 mt-2">Gestiona la seguridad de tu cuenta</p>
        </div>

        <!-- Cambiar Contraseña -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Cambiar Contraseña</h2>
                
                <form action="{{ route('settings.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña Actual</label>
                            <input type="password" name="current_password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200" required>
                            @error('current_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña</label>
                            <input type="password" name="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200" required>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Nueva Contraseña</label>
                        <input type="password" name="password_confirmation" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200" required>
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" class="px-6 py-2 bg-blue-600 border border-transparent rounded-md text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cambiar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sesiones Activas -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Sesiones Activas</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Sesión Actual</p>
                                <p class="text-xs text-gray-500">Navegador: Chrome en Windows</p>
                                <p class="text-xs text-gray-500">IP: {{ request()->ip() }}</p>
                                <p class="text-xs text-gray-500">Última actividad: Ahora</p>
                            </div>
                        </div>
                        <span class="text-xs text-green-600 font-medium">Activa</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Otra Sesión</p>
                                <p class="text-xs text-gray-500">Navegador: Firefox en Mac</p>
                                <p class="text-xs text-gray-500">IP: 192.168.1.100</p>
                                <p class="text-xs text-gray-500">Última actividad: Hace 2 horas</p>
                            </div>
                        </div>
                        <button class="text-xs text-red-600 hover:text-red-800 font-medium">Terminar</button>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 text-sm">
                        Terminar Todas las Sesiones
                    </button>
                </div>
            </div>
        </div>

        <!-- Autenticación de Dos Factores -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Autenticación de Dos Factores</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Autenticación de Dos Factores</p>
                            <p class="text-sm text-gray-500">Añade una capa extra de seguridad a tu cuenta</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-800">
                                    La autenticación de dos factores no está habilitada. Te recomendamos activarla para mayor seguridad.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actividad de la Cuenta -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Actividad de la Cuenta</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Inicio de sesión exitoso</p>
                            <p class="text-xs text-gray-500">Hace 2 horas</p>
                        </div>
                        <span class="text-xs text-green-600">Exitoso</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Cambio de contraseña</p>
                            <p class="text-xs text-gray-500">Hace 1 semana</p>
                        </div>
                        <span class="text-xs text-blue-600">Configuración</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Intento de inicio de sesión fallido</p>
                            <p class="text-xs text-gray-500">Hace 2 semanas</p>
                        </div>
                        <span class="text-xs text-red-600">Fallido</span>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Ver toda la actividad</a>
                </div>
            </div>
        </div>

        <!-- Eliminar Cuenta -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Eliminar Cuenta</h2>
                
                <div class="bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Eliminar cuenta permanentemente</h3>
                            <p class="text-sm text-red-700 mt-1">
                                Una vez que elimines tu cuenta, no hay vuelta atrás. Por favor, ten cuidado.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button id="deleteAccountBtn" class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 text-sm">
                        Eliminar Cuenta
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div id="successMessage" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg z-50">
    {{ session('success') }}
</div>

<script>
setTimeout(() => {
    document.getElementById('successMessage').remove();
}, 3000);
</script>
@endif

<script>
document.getElementById('deleteAccountBtn').addEventListener('click', function() {
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
});
</script>
@endsection 