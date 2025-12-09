@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">API y Desarrollo</h1>
            <p class="text-gray-600 mt-2">Gestiona tus tokens de API y accede a la documentación</p>
        </div>

        <!-- Grid de Contenido -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Tokens de API -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-lg font-medium text-gray-800">Tokens de API</h2>
                                <p class="text-sm text-gray-500">Gestiona tus tokens de acceso a la API</p>
                            </div>
                            <button onclick="createToken()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                Crear Token
                            </button>
                        </div>

                        <div class="space-y-4">
                            @forelse($tokens as $token)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-800">{{ $token->name }}</h3>
                                            <p class="text-xs text-gray-500">Creado el {{ $token->created_at->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button onclick="showToken({{ $token->id }})" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button onclick="deleteToken({{ $token->id }})" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $token->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $token->is_active ? 'Activo' : 'Inactivo' }}
                                            </span>
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                                {{ $token->last_used_at ? 'Último uso: ' . $token->last_used_at->diffForHumans() : 'Nunca usado' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-gray-500">No hay tokens de API creados</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Documentación de la API -->
                <div class="mt-6 bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-800 mb-4">Documentación de la API</h2>
                        <div class="prose max-w-none">
                            <p class="text-gray-600">
                                La API de Taskly te permite integrar la funcionalidad de Taskly en tus propias aplicaciones.
                                Consulta nuestra documentación completa para más detalles.
                            </p>
                            <div class="mt-4 space-y-4">
                                <div class="flex items-center space-x-4">
                                    <i class="fas fa-book text-blue-600"></i>
                                    <a href="{{ route('api.docs') }}" class="text-blue-600 hover:text-blue-800">
                                        Documentación Completa
                                    </a>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <i class="fas fa-code text-blue-600"></i>
                                    <a href="{{ route('api.examples') }}" class="text-blue-600 hover:text-blue-800">
                                        Ejemplos de Código
                                    </a>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <i class="fas fa-tools text-blue-600"></i>
                                    <a href="{{ route('api.playground') }}" class="text-blue-600 hover:text-blue-800">
                                        Playground de la API
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="lg:col-span-1">
                <!-- Estadísticas de Uso -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Estadísticas de Uso</h2>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Límite de Peticiones</span>
                                <span class="text-gray-800">{{ number_format($usage->limit) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($usage->used / $usage->limit) * 100 }}%"></div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">Usadas</p>
                                <p class="text-lg font-medium text-gray-800">{{ number_format($usage->used) }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">Restantes</p>
                                <p class="text-lg font-medium text-gray-800">{{ number_format($usage->remaining) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Endpoints Populares -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Endpoints Populares</h2>
                    <div class="space-y-3">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-800">GET /api/tasks</span>
                                <span class="text-xs text-gray-500">Tareas</span>
                            </div>
                            <p class="text-xs text-gray-600">Obtener lista de tareas</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-800">POST /api/tasks</span>
                                <span class="text-xs text-gray-500">Tareas</span>
                            </div>
                            <p class="text-xs text-gray-600">Crear nueva tarea</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-800">GET /api/projects</span>
                                <span class="text-xs text-gray-500">Proyectos</span>
                            </div>
                            <p class="text-xs text-gray-600">Obtener lista de proyectos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Token -->
<div id="tokenModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Crear Token de API</h3>
            <form id="tokenForm" class="mt-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nombre del Token</label>
                    <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Permisos</label>
                    <div class="mt-2 space-y-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="permissions[]" value="read" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label class="ml-2 block text-sm text-gray-700">
                                Lectura
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="permissions[]" value="write" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label class="ml-2 block text-sm text-gray-700">
                                Escritura
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="permissions[]" value="delete" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label class="ml-2 block text-sm text-gray-700">
                                Eliminación
                            </label>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeTokenModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Crear Token
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function createToken() {
    document.getElementById('modalTitle').textContent = 'Crear Token de API';
    document.getElementById('tokenForm').reset();
    document.getElementById('tokenModal').classList.remove('hidden');
}

function closeTokenModal() {
    document.getElementById('tokenModal').classList.add('hidden');
}

function showToken(tokenId) {
    // Implementar lógica para mostrar token
}

function deleteToken(tokenId) {
    if (confirm('¿Estás seguro de que deseas eliminar este token? Esta acción no se puede deshacer.')) {
        // Implementar lógica para eliminar token
    }
}

// Manejo del formulario de token
document.getElementById('tokenForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Implementar lógica para crear token
    closeTokenModal();
});
</script>
@endpush
@endsection 