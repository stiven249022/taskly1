@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Plantillas de Email</h1>
            <p class="text-gray-600 mt-2">Personaliza las plantillas de email de la aplicación</p>
        </div>

        <!-- Grid de Plantillas -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Lista de Plantillas -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-4 border-b">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-medium text-gray-800">Plantillas</h2>
                            <button onclick="createTemplate()" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="divide-y">
                        @foreach($templates as $template)
                            <div class="p-4 hover:bg-gray-50 cursor-pointer {{ $selectedTemplate && $selectedTemplate->id === $template->id ? 'bg-blue-50' : '' }}" 
                                 onclick="selectTemplate({{ $template->id }})">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-800">{{ $template->name }}</h3>
                                        <p class="text-xs text-gray-500">{{ $template->subject }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button onclick="editTemplate({{ $template->id }})" class="text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteTemplate({{ $template->id }})" class="text-gray-400 hover:text-red-600">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Editor de Plantilla -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md">
                    <form id="templateForm" action="{{ route('settings.email-templates.update', $selectedTemplate->id ?? '') }}" method="POST" class="p-6">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Información Básica -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre de la Plantilla</label>
                                <input type="text" name="name" value="{{ $selectedTemplate->name ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Asunto</label>
                                <input type="text" name="subject" value="{{ $selectedTemplate->subject ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>

                            <!-- Variables Disponibles -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Variables Disponibles</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($variables as $variable)
                                        <button type="button" onclick="insertVariable('{{ $variable }}')" class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full">
                                            {{ $variable }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Editor -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contenido</label>
                                <div class="border rounded-lg">
                                    <div class="border-b p-2 bg-gray-50">
                                        <div class="flex space-x-2">
                                            <button type="button" onclick="formatText('bold')" class="p-1 hover:bg-gray-200 rounded">
                                                <i class="fas fa-bold"></i>
                                            </button>
                                            <button type="button" onclick="formatText('italic')" class="p-1 hover:bg-gray-200 rounded">
                                                <i class="fas fa-italic"></i>
                                            </button>
                                            <button type="button" onclick="formatText('underline')" class="p-1 hover:bg-gray-200 rounded">
                                                <i class="fas fa-underline"></i>
                                            </button>
                                            <div class="w-px h-6 bg-gray-300 mx-2"></div>
                                            <button type="button" onclick="formatText('link')" class="p-1 hover:bg-gray-200 rounded">
                                                <i class="fas fa-link"></i>
                                            </button>
                                            <button type="button" onclick="formatText('image')" class="p-1 hover:bg-gray-200 rounded">
                                                <i class="fas fa-image"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <textarea name="content" id="editor" rows="12" class="w-full p-4 focus:outline-none">{{ $selectedTemplate->content ?? '' }}</textarea>
                                </div>
                            </div>

                            <!-- Vista Previa -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-medium text-gray-700">Vista Previa</label>
                                    <button type="button" onclick="togglePreview()" class="text-sm text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i> Mostrar/Ocultar
                                    </button>
                                </div>
                                <div id="preview" class="border rounded-lg p-4 bg-gray-50 hidden">
                                    <!-- La vista previa se cargará aquí -->
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="flex justify-end space-x-4">
                                <button type="button" onclick="resetForm()" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-6 py-2 rounded-lg">
                                    Restablecer
                                </button>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                                    Guardar Plantilla
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function createTemplate() {
    // Implementar lógica para crear nueva plantilla
}

function selectTemplate(templateId) {
    window.location.href = `/settings/email-templates/${templateId}`;
}

function editTemplate(templateId) {
    // Implementar lógica para editar plantilla
}

function deleteTemplate(templateId) {
    if (confirm('¿Estás seguro de que deseas eliminar esta plantilla?')) {
        // Implementar lógica para eliminar plantilla
    }
}

function insertVariable(variable) {
    const editor = document.getElementById('editor');
    const start = editor.selectionStart;
    const end = editor.selectionEnd;
    const text = editor.value;
    const variableText = `{{ ${variable} }}`;
    
    editor.value = text.substring(0, start) + variableText + text.substring(end);
    editor.focus();
    editor.selectionStart = editor.selectionEnd = start + variableText.length;
}

function formatText(type) {
    const editor = document.getElementById('editor');
    const start = editor.selectionStart;
    const end = editor.selectionEnd;
    const text = editor.value;
    let formattedText = '';

    switch(type) {
        case 'bold':
            formattedText = `**${text.substring(start, end)}**`;
            break;
        case 'italic':
            formattedText = `*${text.substring(start, end)}*`;
            break;
        case 'underline':
            formattedText = `__${text.substring(start, end)}__`;
            break;
        case 'link':
            formattedText = `[${text.substring(start, end)}](url)`;
            break;
        case 'image':
            formattedText = `![alt text](${text.substring(start, end)})`;
            break;
    }

    editor.value = text.substring(0, start) + formattedText + text.substring(end);
    editor.focus();
    editor.selectionStart = editor.selectionEnd = start + formattedText.length;
}

function togglePreview() {
    const preview = document.getElementById('preview');
    const editor = document.getElementById('editor');
    
    if (preview.classList.contains('hidden')) {
        // Convertir markdown a HTML y mostrar vista previa
        preview.innerHTML = marked(editor.value);
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
}

function resetForm() {
    if (confirm('¿Estás seguro de que deseas restablecer el formulario? Los cambios no guardados se perderán.')) {
        document.getElementById('templateForm').reset();
    }
}

// Manejo del formulario
document.getElementById('templateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Implementar lógica para guardar plantilla
    this.submit();
});
</script>
@endpush
@endsection 