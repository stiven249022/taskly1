@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Integraciones</h1>
            <p class="text-gray-600 mt-2">Conecta Taskly con tus servicios favoritos</p>
        </div>

        <!-- Grid de Contenido -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Lista de Integraciones -->
            <div class="lg:col-span-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Google Calendar -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <img src="{{ asset('images/integrations/google-calendar.png') }}" alt="Google Calendar" class="w-12 h-12">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800">Google Calendar</h3>
                                <p class="text-sm text-gray-500">Sincroniza tus tareas con Google Calendar</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $integrations->google_calendar ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $integrations->google_calendar ? 'Conectado' : 'No Conectado' }}
                            </span>
                            <button onclick="connectGoogleCalendar()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                {{ $integrations->google_calendar ? 'Reconectar' : 'Conectar' }}
                            </button>
                        </div>
                    </div>

                    <!-- Microsoft Teams -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <img src="{{ asset('images/integrations/teams.png') }}" alt="Microsoft Teams" class="w-12 h-12">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800">Microsoft Teams</h3>
                                <p class="text-sm text-gray-500">Integra Taskly con Microsoft Teams</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $integrations->teams ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $integrations->teams ? 'Conectado' : 'No Conectado' }}
                            </span>
                            <button onclick="connectTeams()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                {{ $integrations->teams ? 'Reconectar' : 'Conectar' }}
                            </button>
                        </div>
                    </div>

                    <!-- Slack -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <img src="{{ asset('images/integrations/slack.png') }}" alt="Slack" class="w-12 h-12">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800">Slack</h3>
                                <p class="text-sm text-gray-500">Recibe notificaciones en Slack</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $integrations->slack ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $integrations->slack ? 'Conectado' : 'No Conectado' }}
                            </span>
                            <button onclick="connectSlack()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                {{ $integrations->slack ? 'Reconectar' : 'Conectar' }}
                            </button>
                        </div>
                    </div>

                    <!-- GitHub -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <img src="{{ asset('images/integrations/github.png') }}" alt="GitHub" class="w-12 h-12">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800">GitHub</h3>
                                <p class="text-sm text-gray-500">Sincroniza issues y pull requests</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $integrations->github ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $integrations->github ? 'Conectado' : 'No Conectado' }}
                            </span>
                            <button onclick="connectGithub()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                {{ $integrations->github ? 'Reconectar' : 'Conectar' }}
                            </button>
                        </div>
                    </div>

                    <!-- Trello -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <img src="{{ asset('images/integrations/trello.png') }}" alt="Trello" class="w-12 h-12">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800">Trello</h3>
                                <p class="text-sm text-gray-500">Sincroniza tus tableros de Trello</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $integrations->trello ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $integrations->trello ? 'Conectado' : 'No Conectado' }}
                            </span>
                            <button onclick="connectTrello()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                {{ $integrations->trello ? 'Reconectar' : 'Conectar' }}
                            </button>
                        </div>
                    </div>

                    <!-- Zapier -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <img src="{{ asset('images/integrations/zapier.png') }}" alt="Zapier" class="w-12 h-12">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800">Zapier</h3>
                                <p class="text-sm text-gray-500">Automatiza con más de 3000 apps</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $integrations->zapier ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $integrations->zapier ? 'Conectado' : 'No Conectado' }}
                            </span>
                            <button onclick="connectZapier()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                {{ $integrations->zapier ? 'Reconectar' : 'Conectar' }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Webhooks -->
                <div class="mt-6 bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h2 class="text-lg font-medium text-gray-800">Webhooks</h2>
                                <p class="text-sm text-gray-500">Configura webhooks para integraciones personalizadas</p>
                            </div>
                            <button onclick="createWebhook()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                Crear Webhook
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            URL
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Eventos
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
                                    @foreach($webhooks as $webhook)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $webhook->url }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ implode(', ', $webhook->events) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $webhook->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $webhook->is_active ? 'Activo' : 'Inactivo' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class="flex space-x-3">
                                                    <button onclick="editWebhook({{ $webhook->id }})" class="text-blue-600 hover:text-blue-900">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button onclick="toggleWebhook({{ $webhook->id }})" class="text-green-600 hover:text-green-900">
                                                        <i class="fas fa-power-off"></i>
                                                    </button>
                                                    <button onclick="deleteWebhook({{ $webhook->id }})" class="text-red-600 hover:text-red-900">
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
                <!-- Estado de Integraciones -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Estado de Integraciones</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Integraciones Activas</span>
                            <span class="text-sm font-medium text-gray-800">{{ $stats->active_integrations }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Webhooks Activos</span>
                            <span class="text-sm font-medium text-gray-800">{{ $stats->active_webhooks }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Última Sincronización</span>
                            <span class="text-sm font-medium text-gray-800">{{ $stats->last_sync->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Eventos Disponibles -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Eventos Disponibles</h2>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <code class="text-sm text-gray-600">task.created</code>
                            <span class="text-xs text-gray-500">Nueva tarea</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <code class="text-sm text-gray-600">task.updated</code>
                            <span class="text-xs text-gray-500">Tarea actualizada</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <code class="text-sm text-gray-600">task.completed</code>
                            <span class="text-xs text-gray-500">Tarea completada</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <code class="text-sm text-gray-600">project.created</code>
                            <span class="text-xs text-gray-500">Nuevo proyecto</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <code class="text-sm text-gray-600">project.updated</code>
                            <span class="text-xs text-gray-500">Proyecto actualizado</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Webhook -->
<div id="webhookModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Crear Webhook</h3>
            <form id="webhookForm" class="mt-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">URL</label>
                    <input type="url" name="url" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Eventos</label>
                    <div class="mt-2 space-y-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="events[]" value="task.created" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label class="ml-2 block text-sm text-gray-700">
                                task.created
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="events[]" value="task.updated" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label class="ml-2 block text-sm text-gray-700">
                                task.updated
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="events[]" value="task.completed" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label class="ml-2 block text-sm text-gray-700">
                                task.completed
                            </label>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeWebhookModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Crear Webhook
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function connectGoogleCalendar() {
    // Implementar lógica para conectar Google Calendar
}

function connectTeams() {
    // Implementar lógica para conectar Microsoft Teams
}

function connectSlack() {
    // Implementar lógica para conectar Slack
}

function connectGithub() {
    // Implementar lógica para conectar GitHub
}

function connectTrello() {
    // Implementar lógica para conectar Trello
}

function connectZapier() {
    // Implementar lógica para conectar Zapier
}

function createWebhook() {
    document.getElementById('modalTitle').textContent = 'Crear Webhook';
    document.getElementById('webhookForm').reset();
    document.getElementById('webhookModal').classList.remove('hidden');
}

function editWebhook(webhookId) {
    // Implementar lógica para editar webhook
    document.getElementById('modalTitle').textContent = 'Editar Webhook';
    document.getElementById('webhookModal').classList.remove('hidden');
}

function toggleWebhook(webhookId) {
    // Implementar lógica para activar/desactivar webhook
}

function deleteWebhook(webhookId) {
    if (confirm('¿Estás seguro de que deseas eliminar este webhook?')) {
        // Implementar lógica para eliminar webhook
    }
}

function closeWebhookModal() {
    document.getElementById('webhookModal').classList.add('hidden');
}

// Manejo del formulario de webhook
document.getElementById('webhookForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Implementar lógica para guardar webhook
    closeWebhookModal();
});
</script>
@endpush
@endsection 