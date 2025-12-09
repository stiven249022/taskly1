@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Respaldo y Restauración</h1>
            <p class="text-gray-600 mt-2">Gestiona las copias de seguridad de tu aplicación</p>
        </div>

        <!-- Grid de Contenido -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Configuración de Respaldo -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-lg font-medium text-gray-800">Configuración de Respaldo</h2>
                                <p class="text-sm text-gray-500">Programa y configura tus copias de seguridad</p>
                            </div>
                            <button onclick="createBackup()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                Crear Respaldo Ahora
                            </button>
                        </div>

                        <!-- Programación -->
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-800 mb-4">Programación</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Frecuencia</label>
                                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                        <option value="daily">Diario</option>
                                        <option value="weekly">Semanal</option>
                                        <option value="monthly">Mensual</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Hora</label>
                                    <input type="time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                            </div>
                        </div>

                        <!-- Almacenamiento -->
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-800 mb-4">Almacenamiento</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 border rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-database text-gray-400"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">Base de Datos</p>
                                            <p class="text-xs text-gray-500">Incluir toda la información de la base de datos</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-4 border rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-file text-gray-400"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">Archivos</p>
                                            <p class="text-xs text-gray-500">Incluir archivos subidos y documentos</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Retención -->
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-800 mb-4">Retención</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Período de Retención</label>
                                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                        <option value="7">7 días</option>
                                        <option value="30">30 días</option>
                                        <option value="90">90 días</option>
                                        <option value="365">1 año</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Máximo de Copias</label>
                                    <input type="number" min="1" max="100" value="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historial de Respaldos -->
                <div class="mt-6 bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-800 mb-4">Historial de Respaldos</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tamaño
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estado
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($backups as $backup)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $backup->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $backup->size }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $backup->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $backup->status === 'completed' ? 'Completado' : 'Fallido' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class="flex space-x-3">
                                                    <button onclick="downloadBackup({{ $backup->id }})" class="text-blue-600 hover:text-blue-900">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                    <button onclick="restoreBackup({{ $backup->id }})" class="text-green-600 hover:text-green-900">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                    <button onclick="deleteBackup({{ $backup->id }})" class="text-red-600 hover:text-red-900">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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

            <!-- Panel Lateral -->
            <div class="lg:col-span-1">
                <!-- Estado del Sistema -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Estado del Sistema</h2>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Espacio en Disco</span>
                                <span class="text-gray-800">{{ $diskSpace->used }} / {{ $diskSpace->total }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($diskSpace->used / $diskSpace->total) * 100 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Último Respaldo</span>
                                <span class="text-gray-800">{{ $lastBackup->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Próximo Respaldo</span>
                                <span class="text-gray-800">{{ $nextBackup->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notas Importantes -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Notas Importantes</h2>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Mantén copias de seguridad regulares</p>
                                <p class="text-xs text-gray-500">Programa respaldos automáticos para proteger tus datos</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-exclamation-triangle text-yellow-500 mt-1"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Verifica tus respaldos</p>
                                <p class="text-xs text-gray-500">Asegúrate de que los respaldos se completen correctamente</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-shield-alt text-green-500 mt-1"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Almacena respaldos externamente</p>
                                <p class="text-xs text-gray-500">Considera usar almacenamiento en la nube para mayor seguridad</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function createBackup() {
    if (confirm('¿Estás seguro de que deseas crear un respaldo ahora?')) {
        // Implementar lógica para crear respaldo
    }
}

function downloadBackup(backupId) {
    // Implementar lógica para descargar respaldo
}

function restoreBackup(backupId) {
    if (confirm('¿Estás seguro de que deseas restaurar este respaldo? Esta acción sobrescribirá los datos actuales.')) {
        // Implementar lógica para restaurar respaldo
    }
}

function deleteBackup(backupId) {
    if (confirm('¿Estás seguro de que deseas eliminar este respaldo? Esta acción no se puede deshacer.')) {
        // Implementar lógica para eliminar respaldo
    }
}
</script>
@endpush
@endsection 