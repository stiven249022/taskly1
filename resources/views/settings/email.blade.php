@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Configuración de Correo</h1>
            <p class="text-gray-600 mt-2">Gestiona la configuración del servidor de correo y las plantillas</p>
        </div>

        <!-- Grid de Contenido -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Configuración SMTP -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-800 mb-4">Configuración del Servidor SMTP</h2>
                        
                        <form id="smtpForm" class="space-y-6">
                            <!-- Servidor -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Servidor SMTP</label>
                                    <input type="text" name="smtp_host" value="{{ $smtp->host }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Puerto</label>
                                    <input type="number" name="smtp_port" value="{{ $smtp->port }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                            </div>

                            <!-- Credenciales -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Usuario</label>
                                    <input type="text" name="smtp_username" value="{{ $smtp->username }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Contraseña</label>
                                    <input type="password" name="smtp_password" value="{{ $smtp->password }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                            </div>

                            <!-- Configuración Adicional -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Encriptación</label>
                                    <select name="smtp_encryption" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                        <option value="tls" {{ $smtp->encryption === 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ $smtp->encryption === 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="none" {{ $smtp->encryption === 'none' ? 'selected' : '' }}>Ninguna</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Remitente</label>
                                    <input type="email" name="smtp_from_address" value="{{ $smtp->from_address }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="testConnection()" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
                                    Probar Conexión
                                </button>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Plantillas de Correo -->
                <div class="mt-6 bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-medium text-gray-800">Plantillas de Correo</h2>
                            <button onclick="createTemplate()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                Nueva Plantilla
                            </button>
                        </div>

                        <div class="space-y-4">
                            @foreach($templates as $template)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-800">{{ $template->name }}</h3>
                                            <p class="text-xs text-gray-500">Asunto: {{ $template->subject }}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button onclick="editTemplate({{ $template->id }})" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="previewTemplate({{ $template->id }})" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button onclick="deleteTemplate({{ $template->id }})" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="lg:col-span-1">
                <!-- Estado del Servidor -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Estado del Servidor</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Estado de Conexión</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $smtp->is_connected ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $smtp->is_connected ? 'Conectado' : 'Desconectado' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Última Prueba</span>
                            <span class="text-sm text-gray-800">{{ $smtp->last_test->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Correos Enviados</span>
                            <span class="text-sm text-gray-800">{{ number_format($smtp->emails_sent) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Variables Disponibles -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Variables Disponibles</h2>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <code class="text-sm text-gray-600">{user_name}</code>
                            <span class="text-xs text-gray-500">Nombre del usuario</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <code class="text-sm text-gray-600">{task_name}</code>
                            <span class="text-xs text-gray-500">Nombre de la tarea</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <code class="text-sm text-gray-600">{project_name}</code>
                            <span class="text-xs text-gray-500">Nombre del proyecto</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <code class="text-sm text-gray-600">{due_date}</code>
                            <span class="text-xs text-gray-500">Fecha de vencimiento</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <code class="text-sm text-gray-600">{status}</code>
                            <span class="text-xs text-gray-500">Estado actual</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Plantilla -->
<div id="templateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Nueva Plantilla</h3>
            <form id="templateForm" class="mt-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Asunto</label>
                    <input type="text" name="subject" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Contenido</label>
                    <textarea name="content" rows="6" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeTemplateModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
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
function testConnection() {
    // Implementar lógica para probar conexión SMTP
}

function createTemplate() {
    document.getElementById('modalTitle').textContent = 'Nueva Plantilla';
    document.getElementById('templateForm').reset();
    document.getElementById('templateModal').classList.remove('hidden');
}

function editTemplate(templateId) {
    // Implementar lógica para editar plantilla
    document.getElementById('modalTitle').textContent = 'Editar Plantilla';
    document.getElementById('templateModal').classList.remove('hidden');
}

function previewTemplate(templateId) {
    // Implementar lógica para previsualizar plantilla
}

function deleteTemplate(templateId) {
    if (confirm('¿Estás seguro de que deseas eliminar esta plantilla?')) {
        // Implementar lógica para eliminar plantilla
    }
}

function closeTemplateModal() {
    document.getElementById('templateModal').classList.add('hidden');
}

// Manejo del formulario SMTP
document.getElementById('smtpForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Implementar lógica para guardar configuración SMTP
});

// Manejo del formulario de plantilla
document.getElementById('templateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Implementar lógica para guardar plantilla
    closeTemplateModal();
});
</script>
@endpush
@endsection 