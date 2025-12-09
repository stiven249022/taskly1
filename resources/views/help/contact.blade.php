@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Contacto y Soporte</h1>
            <p class="text-gray-600 mt-2">Estamos aquí para ayudarte. Elige la mejor manera de contactarnos.</p>
        </div>

        <!-- Opciones de Contacto -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Email -->
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-blue-600 mb-4">
                    <i class="fas fa-envelope text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-800 mb-2">Email</h3>
                <p class="text-gray-600 mb-4">Responde en 24 horas</p>
                <a href="mailto:soporte@taskly.com" class="text-blue-600 hover:text-blue-800">
                    soporte@taskly.com
                </a>
            </div>

            <!-- Chat -->
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-green-600 mb-4">
                    <i class="fas fa-comments text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-800 mb-2">Chat en Vivo</h3>
                <p class="text-gray-600 mb-4">Disponible 24/7</p>
                <button onclick="startChat()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    Iniciar Chat
                </button>
            </div>

            <!-- Teléfono -->
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-purple-600 mb-4">
                    <i class="fas fa-phone text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-800 mb-2">Teléfono</h3>
                <p class="text-gray-600 mb-4">Lunes a Viernes, 9:00 - 18:00</p>
                <a href="tel:+1234567890" class="text-purple-600 hover:text-purple-800">
                    +1 (234) 567-890
                </a>
            </div>
        </div>

        <!-- Formulario de Contacto -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-medium text-gray-800 mb-4">Envíanos un Mensaje</h2>
            <form action="{{ route('help.contact.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700">Asunto</label>
                    <select name="subject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="">Selecciona un asunto</option>
                        <option value="technical">Problema Técnico</option>
                        <option value="billing">Facturación</option>
                        <option value="feature">Sugerencia de Característica</option>
                        <option value="other">Otro</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Prioridad</label>
                    <select name="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="low">Baja</option>
                        <option value="medium">Media</option>
                        <option value="high">Alta</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Mensaje</label>
                    <textarea name="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Archivos Adjuntos</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl"></i>
                            <div class="flex text-sm text-gray-600">
                                <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Subir archivos</span>
                                    <input type="file" name="attachments[]" class="sr-only" multiple>
                                </label>
                                <p class="pl-1">o arrastrar y soltar</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, PDF hasta 10MB</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="subscribe" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label class="ml-2 block text-sm text-gray-700">
                        Suscribirme al boletín de novedades
                    </label>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        Enviar Mensaje
                    </button>
                </div>
            </form>
        </div>

        <!-- Información Adicional -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Horario de Atención -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Horario de Atención</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Lunes - Viernes</span>
                        <span class="text-gray-800">9:00 - 18:00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sábado</span>
                        <span class="text-gray-800">10:00 - 14:00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Domingo</span>
                        <span class="text-gray-800">Cerrado</span>
                    </div>
                </div>
            </div>

            <!-- Redes Sociales -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Síguenos</h3>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-blue-600">
                        <i class="fab fa-facebook text-2xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-400">
                        <i class="fab fa-twitter text-2xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-red-600">
                        <i class="fab fa-youtube text-2xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-700">
                        <i class="fab fa-linkedin text-2xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function startChat() {
    // Implementar lógica de chat
    alert('Iniciando chat...');
}

// Manejo de archivos adjuntos
const fileInput = document.querySelector('input[type="file"]');
const dropZone = document.querySelector('.border-dashed');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    dropZone.classList.add('border-blue-500');
}

function unhighlight(e) {
    dropZone.classList.remove('border-blue-500');
}

dropZone.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    fileInput.files = files;
}
</script>
@endpush
@endsection 