@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Roles y Permisos</h1>
                <p class="text-gray-600 mt-2">Administra los roles y permisos de la aplicación</p>
            </div>
            <button onclick="openRoleModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Crear Rol
            </button>
        </div>

        <!-- Lista de Roles -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($roles as $role)
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800">{{ $role->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $role->users_count }} usuarios</p>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="editRole({{ $role->id }})" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @unless($role->is_system)
                                    <button onclick="deleteRole
                                    
                                    +({{ $role->id }})" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endunless
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Permisos</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($role->permissions as $permission)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                            {{ $permission->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Descripción</h4>
                                <p class="text-sm text-gray-600">{{ $role->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sección de Permisos -->
        <div class="mt-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Permisos Disponibles</h2>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Permiso
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Descripción
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Roles Asignados
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($permissions as $permission)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $permission->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-500">
                                            {{ $permission->description }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($permission->roles as $role)
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Rol -->
<div id="roleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Crear Rol</h3>
            <form id="roleForm" class="mt-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Permisos</label>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @foreach($permissions as $permission)
                            <div class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label class="ml-2 block text-sm text-gray-700">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRoleModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openRoleModal() {
    document.getElementById('modalTitle').textContent = 'Crear Rol';
    document.getElementById('roleForm').reset();
    document.getElementById('roleModal').classList.remove('hidden');
}

function closeRoleModal() {
    document.getElementById('roleModal').classList.add('hidden');
}

function editRole(roleId) {
    document.getElementById('modalTitle').textContent = 'Editar Rol';
    // Implementar lógica para cargar datos del rol
    document.getElementById('roleModal').classList.remove('hidden');
}

function deleteRole(roleId) {
    if (confirm('¿Estás seguro de que deseas eliminar este rol? Esta acción no se puede deshacer.')) {
        // Implementar lógica para eliminar rol
    }
}

// Manejo del formulario de rol
document.getElementById('roleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Implementar lógica para guardar rol
    closeRoleModal();
});
</script>
@endpush
@endsection 