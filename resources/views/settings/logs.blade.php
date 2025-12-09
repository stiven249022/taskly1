@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Logs y Auditoría</h1>
            <p class="text-gray-600 mt-2">Monitorea y gestiona los registros del sistema</p>
        </div>

        <!-- Grid de Contenido -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Logs del Sistema -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-lg font-medium text-gray-800">Logs del Sistema</h2>
                                <p class="text-sm text-gray-500">Registros de actividad y eventos del sistema</p>
                            </div>
                            <div class="flex space-x-3">
                                <button onclick="downloadLogs()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                    Descargar Logs
                                </button>
                                <button onclick="clearLogs()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                    Limpiar Logs
                                </button>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nivel</label>
                                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                        <option value="all">Todos</option>
                                        <option value="error">Error</option>
                                        <option value="warning">Advertencia</option>
                                        <option value="info">Info</option>
                                        <option value="debug">Debug</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Fecha Desde</label>
                                    <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Fecha Hasta</label>
                                    <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Buscar</label>
                                    <input type="text" placeholder="Buscar en logs..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de Logs -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nivel
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Mensaje
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Usuario
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($logs as $log)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $log->level === 'error' ? 'bg-red-100 text-red-800' : ($log->level === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                                    {{ ucfirst($log->level) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $log->message }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $log->user->name ?? 'Sistema' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="mt-4">
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>

                <!-- Auditoría -->
                <div class="mt-6 bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-800 mb-4">Registro de Auditoría</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Usuario
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acción
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Detalles
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($auditLogs as $audit)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $audit->created_at->format('d/m/Y H:i:s') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $audit->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $audit->action }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <button onclick="showAuditDetails({{ $audit->id }})" class="text-blue-600 hover:text-blue-900">
                                                    Ver Detalles
                                                </button>
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
                <!-- Estadísticas -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Estadísticas</h2>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Errores (24h)</span>
                                <span class="text-red-600">{{ $stats->errors_24h }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-red-600 h-2 rounded-full" style="width: {{ ($stats->errors_24h / $stats->total_logs) * 100 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Advertencias (24h)</span>
                                <span class="text-yellow-600">{{ $stats->warnings_24h }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ ($stats->warnings_24h / $stats->total_logs) * 100 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Total de Logs</span>
                                <span class="text-gray-800">{{ $stats->total_logs }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Configuración de Logs -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Configuración</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nivel de Log</label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                <option value="debug">Debug</option>
                                <option value="info">Info</option>
                                <option value="warning">Warning</option>
                                <option value="error">Error</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Retención de Logs</label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                <option value="7">7 días</option>
                                <option value="30">30 días</option>
                                <option value="90">90 días</option>
                                <option value="365">1 año</option>
                            </select>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="enableAudit" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="enableAudit" class="ml-2 block text-sm text-gray-700">
                                Habilitar Auditoría
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalles de Auditoría -->
<div id="auditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Detalles de Auditoría</h3>
            <div id="auditDetails" class="mt-4">
                <!-- Los detalles se cargarán dinámicamente -->
            </div>
            <div class="mt-4 flex justify-end">
                <button onclick="closeAuditModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function downloadLogs() {
    // Implementar lógica para descargar logs
}

function clearLogs() {
    if (confirm('¿Estás seguro de que deseas limpiar todos los logs? Esta acción no se puede deshacer.')) {
        // Implementar lógica para limpiar logs
    }
}

function showAuditDetails(auditId) {
    // Implementar lógica para mostrar detalles de auditoría
    document.getElementById('auditModal').classList.remove('hidden');
}

function closeAuditModal() {
    document.getElementById('auditModal').classList.add('hidden');
}

// Manejo de filtros
document.querySelectorAll('select, input[type="date"], input[type="text"]').forEach(element => {
    element.addEventListener('change', function() {
        // Implementar lógica para filtrar logs
    });
});
</script>
@endpush
@endsection 