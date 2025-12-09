@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header del Perfil -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Mi Perfil</h1>
            <p class="text-gray-600 dark:text-gray-300">Gestiona tu información personal y preferencias</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Columna Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información Personal -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="relative">
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" 
                                 class="w-20 h-20 rounded-full object-cover border-4 border-gray-200 dark:border-gray-600">
                            <button onclick="document.getElementById('photo-input').click()" 
                                    class="absolute bottom-0 right-0 bg-blue-600 text-white p-2 rounded-full hover:bg-blue-700 transition-colors">
                                <i class="fas fa-camera"></i>
                            </button>
                            <input type="file" id="photo-input" class="hidden" accept="image/*">
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">{{ auth()->user()->name }}</h2>
                            <p class="text-gray-600 dark:text-gray-300">{{ auth()->user()->email }}</p>
                            <div class="flex items-center mt-2">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if(auth()->user()->role === 'admin') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                    @elseif(auth()->user()->role === 'teacher') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                    @else bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                    @endif">
                                    {{ ucfirst(auth()->user()->role) }}
                                </span>
                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">Miembro desde {{ auth()->user()->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de Información Personal -->
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre Completo</label>
                                <input type="text" name="name" value="{{ auth()->user()->name }}" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('name')
                                    <p class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Correo Electrónico</label>
                                <input type="email" name="email" value="{{ auth()->user()->email }}" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('email')
                                    <p class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors">
                                <i class="fas fa-save mr-2"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Cambiar Contraseña -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">
                        <i class="fas fa-lock mr-2"></i>Cambiar Contraseña
                    </h3>
                    
                    <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contraseña Actual</label>
                            <input type="password" name="current_password" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Ingresa tu contraseña actual">
                            @error('current_password')
                                <p class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nueva Contraseña</label>
                                <input type="password" name="password" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Mínimo 8 caracteres">
                                @error('password')
                                    <p class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirmar Nueva Contraseña</label>
                                <input type="password" name="password_confirmation" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Repite la nueva contraseña">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition-colors">
                                <i class="fas fa-key mr-2"></i>Actualizar Contraseña
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Preferencias -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">
                        <i class="fas fa-cog mr-2"></i>Preferencias
                    </h3>
                    
                    <form action="{{ route('profile.preferences.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tema</label>
                                <select name="theme" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="light" {{ auth()->user()->theme === 'light' ? 'selected' : '' }}>Claro</option>
                                    <option value="dark" {{ auth()->user()->theme === 'dark' ? 'selected' : '' }}>Oscuro</option>
                                    <option value="system" {{ auth()->user()->theme === 'system' ? 'selected' : '' }}>Sistema</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Zona Horaria</label>
                                <select name="timezone" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="America/Mexico_City" {{ auth()->user()->timezone === 'America/Mexico_City' ? 'selected' : '' }}>México (GMT-6)</option>
                                    <option value="America/New_York" {{ auth()->user()->timezone === 'America/New_York' ? 'selected' : '' }}>Nueva York (GMT-5)</option>
                                    <option value="Europe/Madrid" {{ auth()->user()->timezone === 'Europe/Madrid' ? 'selected' : '' }}>Madrid (GMT+1)</option>
                                    <option value="UTC" {{ auth()->user()->timezone === 'UTC' ? 'selected' : '' }}>UTC</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="email_notifications" id="email_notifications" 
                                   {{ auth()->user()->email_notifications ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded">
                            <label for="email_notifications" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                Recibir notificaciones por correo electrónico
                            </label>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md transition-colors">
                                <i class="fas fa-save mr-2"></i>Guardar Preferencias
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Estadísticas -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">
                        <i class="fas fa-chart-bar mr-2"></i>Estadísticas
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tareas Completadas</p>
                                <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ auth()->user()->tasks()->where('status', 'completed')->count() }}</p>
                            </div>
                            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-full">
                                <i class="fas fa-check text-green-600 dark:text-green-400"></i>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Proyectos Activos</p>
                                <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ auth()->user()->projects()->where('status', 'active')->count() }}</p>
                            </div>
                            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-full">
                                <i class="fas fa-project-diagram text-blue-600 dark:text-blue-400"></i>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Cursos</p>
                                <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ auth()->user()->courses()->count() }}</p>
                            </div>
                            <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                                <i class="fas fa-graduation-cap text-yellow-600 dark:text-yellow-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">
                        <i class="fas fa-bolt mr-2"></i>Acciones Rápidas
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('dashboard') }}" class="flex items-center text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            <i class="fas fa-tachometer-alt w-5"></i>
                            <span class="ml-2">Dashboard</span>
                        </a>
                        <a href="{{ route('tasks.create') }}" class="flex items-center text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            <i class="fas fa-plus w-5"></i>
                            <span class="ml-2">Nueva Tarea</span>
                        </a>
                        <a href="{{ route('projects.create') }}" class="flex items-center text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            <i class="fas fa-folder-plus w-5"></i>
                            <span class="ml-2">Nuevo Proyecto</span>
                        </a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                <i class="fas fa-shield-alt w-5"></i>
                                <span class="ml-2">Panel de Administración</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Información de Seguridad -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">
                        <i class="fas fa-shield-alt mr-2"></i>Seguridad
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Verificación de Email</span>
                            @if(auth()->user()->email_verified_at)
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                    <i class="fas fa-check mr-1"></i>Verificado
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                    <i class="fas fa-times mr-1"></i>No verificado
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Último acceso</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Eliminar Cuenta -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-red-200 dark:border-red-800">
                    <h3 class="text-lg font-medium text-red-800 dark:text-red-400 mb-4">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Zona de Peligro
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                        Una vez que elimines tu cuenta, no podrás recuperarla. Por favor, asegúrate de que es lo que realmente quieres hacer.
                    </p>
                    <button onclick="confirmDelete()" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition-colors">
                        <i class="fas fa-trash mr-2"></i>Eliminar Cuenta
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Eliminar Cuenta -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Confirmar Eliminación</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                ¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.
            </p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white">
                    Cancelar
                </button>
                <form method="POST" action="{{ route('profile.destroy') }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Eliminar Cuenta
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Manejo de la foto de perfil
document.getElementById('photo-input').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const formData = new FormData();
        formData.append('photo', e.target.files[0]);

        fetch('{{ route("profile.update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            }
        });
    }
});

// Modal de eliminación
function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Cerrar modal al hacer clic fuera
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endpush
@endsection 