@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado del Chat -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="relative">
                        <img src="{{ asset('images/support-agent.jpg') }}" alt="Agente de Soporte" class="w-10 h-10 rounded-full">
                        <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-green-400 ring-2 ring-white"></span>
                    </div>
                    <div class="ml-3">
                        <h2 class="text-lg font-medium text-gray-800">Soporte en Vivo</h2>
                        <p class="text-sm text-gray-600">Agente: {{ $agent->name ?? 'Conectando...' }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="toggleSound()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-volume-up" id="soundIcon"></i>
                    </button>
                    <button onclick="endChat()" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Contenedor Principal -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Panel de Chat -->
            <div class="md:col-span-3">
                <div class="bg-white rounded-lg shadow-md">
                    <!-- Mensajes -->
                    <div class="h-96 overflow-y-auto p-4" id="chatMessages">
                        <!-- Mensaje del Sistema -->
                        <div class="flex justify-center mb-4">
                            <span class="bg-gray-100 text-gray-600 text-sm px-4 py-2 rounded-full">
                                Chat iniciado
                            </span>
                        </div>

                        <!-- Mensaje del Agente -->
                        <div class="flex mb-4">
                            <img src="{{ asset('images/support-agent.jpg') }}" alt="Agente" class="w-8 h-8 rounded-full">
                            <div class="ml-3">
                                <div class="bg-gray-100 rounded-lg px-4 py-2">
                                    <p class="text-gray-800">¡Hola! ¿En qué puedo ayudarte hoy?</p>
                                </div>
                                <span class="text-xs text-gray-500 mt-1">10:00 AM</span>
                            </div>
                        </div>

                        <!-- Mensaje del Usuario -->
                        <div class="flex justify-end mb-4">
                            <div class="mr-3">
                                <div class="bg-blue-600 rounded-lg px-4 py-2">
                                    <p class="text-white">Tengo un problema con mi tarea</p>
                                </div>
                                <span class="text-xs text-gray-500 mt-1">10:01 AM</span>
                            </div>
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="Usuario" class="w-8 h-8 rounded-full">
                        </div>
                    </div>

                    <!-- Input de Mensaje -->
                    <div class="border-t p-4">
                        <form id="messageForm" class="flex items-center space-x-4">
                            <div class="flex-1">
                                <input type="text" id="messageInput" class="w-full rounded-lg border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200" placeholder="Escribe tu mensaje...">
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" onclick="attachFile()" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-paperclip"></i>
                                </button>
                                <button type="button" onclick="sendEmoji()" class="text-gray-400 hover:text-gray-600">
                                    <i class="far fa-smile"></i>
                                </button>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                    Enviar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="md:col-span-1">
                <!-- Información del Chat -->
                <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Información del Chat</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-600">ID del Chat:</span>
                            <p class="text-gray-800">{{ $chatId ?? 'Generando...' }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Iniciado:</span>
                            <p class="text-gray-800">{{ now()->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Estado:</span>
                            <p class="text-green-600">Activo</p>
                        </div>
                    </div>
                </div>

                <!-- Archivos Compartidos -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Archivos Compartidos</h3>
                    <div class="space-y-2" id="sharedFiles">
                        <!-- Archivo -->
                        <div class="flex items-center p-2 hover:bg-gray-50 rounded">
                            <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-800 truncate">documento.pdf</p>
                                <p class="text-xs text-gray-500">2.5 MB</p>
                            </div>
                            <button class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let soundEnabled = true;

function toggleSound() {
    soundEnabled = !soundEnabled;
    const icon = document.getElementById('soundIcon');
    icon.className = soundEnabled ? 'fas fa-volume-up' : 'fas fa-volume-mute';
}

function endChat() {
    if (confirm('¿Estás seguro de que deseas finalizar el chat?')) {
        // Implementar lógica para finalizar chat
        window.location.href = '{{ route("help.contact") }}';
    }
}

function attachFile() {
    const input = document.createElement('input');
    input.type = 'file';
    input.onchange = (e) => {
        const file = e.target.files[0];
        // Implementar lógica para subir archivo
        console.log('Archivo seleccionado:', file.name);
    };
    input.click();
}

function sendEmoji() {
    // Implementar selector de emojis
    console.log('Abrir selector de emojis');
}

// Manejo del formulario de mensajes
document.getElementById('messageForm').addEventListener('submit', (e) => {
    e.preventDefault();
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (message) {
        // Implementar lógica para enviar mensaje
        console.log('Enviando mensaje:', message);
        input.value = '';
    }
});

// Scroll al último mensaje
function scrollToBottom() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Scroll inicial
scrollToBottom();
</script>
@endpush
@endsection 