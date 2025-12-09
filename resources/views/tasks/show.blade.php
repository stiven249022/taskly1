@extends('layouts.dashboard')

@section('title', 'Detalle de Tarea')
@section('page-title', 'Detalle de Tarea')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $task->title }}</h1>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $task->status === 'completed' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 
                           ($task->status === 'in_progress' ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200') }}">
                        {{ ucfirst($task->status) }}
                    </span>
                    @if($task->isSubmitted())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                            <i class="fas fa-paperclip mr-1"></i> Entregada
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Task Details -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Información de la Tarea</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descripción</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $task->description ?: 'Sin descripción' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Materia</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $task->course->name ?? 'Sin materia' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Prioridad</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $task->priority === 'high' ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' : 
                                       ($task->priority === 'medium' ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' : 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200') }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de vencimiento</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $task->due_date ? $task->due_date->format('d/m/Y H:i') : 'Sin fecha límite' }}
                            </dd>
                        </div>
                        @if($task->grade !== null)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Calificación</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                    <i class="fas fa-star mr-1"></i>
                                    {{ number_format($task->grade, 0) }}/100
                                </span>
                                @if($task->feedback)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $task->feedback }}</p>
                                @endif
                            </dd>
                        </div>
                        @endif
                        @if($task->teacher)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Profesor</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $task->teacher->name }} {{ $task->teacher->last_name }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Estado de Entrega</h3>
                    @if($task->hasSupportFile())
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4 mb-4">
                            <h4 class="text-sm font-medium text-indigo-800 dark:text-indigo-200 mb-2">Material de apoyo</h4>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-file text-indigo-500 mr-2"></i>
                                    <div>
                                        <p class="text-sm text-indigo-800 dark:text-indigo-200">{{ $task->support_file_name }}</p>
                                        <p class="text-xs text-indigo-600 dark:text-indigo-300">{{ $task->getFormattedSupportFileSize() }}</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('task-files.support.view', $task) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200" target="_blank" title="Ver material">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('task-files.support.download', $task) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200" title="Descargar material">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($task->isSubmitted())
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                                <div>
                                    <h4 class="text-sm font-medium text-green-800 dark:text-green-200">Tarea Entregada</h4>
                                    <p class="text-sm text-green-600 dark:text-green-300">
                                        Entregada el {{ $task->submitted_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- File Information -->
                        <div class="mt-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">Archivo Entregado</h4>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-file text-blue-500 mr-2"></i>
                                    <div>
                                        <p class="text-sm text-blue-800 dark:text-blue-200">{{ $task->file_name }}</p>
                                        <p class="text-xs text-blue-600 dark:text-blue-300">{{ $task->getFormattedFileSize() }}</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('task-files.view', $task) }}" 
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200"
                                       target="_blank"
                                       title="Ver archivo">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('task-files.download', $task) }}" 
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200"
                                       title="Descargar archivo">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    @can('submit', $task)
                                    <button onclick="deleteFile({{ $task->id }})" 
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200"
                                            title="Eliminar archivo">
                                            <i class="fas fa-trash"></i>
                                    </button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-yellow-500 text-xl mr-3"></i>
                                <div>
                                    <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Tarea Pendiente</h4>
                                    <p class="text-sm text-yellow-600 dark:text-yellow-300">
                                        Aún no se ha entregado un archivo
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- File Upload Form -->
                        @can('submit', $task)
                        <div class="mt-4">
                            <form id="fileUploadForm" enctype="multipart/form-data">
                                @csrf
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                                    <input type="file" 
                                           id="fileInput" 
                                           name="file" 
                                           accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif"
                                           class="hidden" 
                                           onchange="uploadFile({{ $task->id }})">
                                    <label for="fileInput" class="cursor-pointer">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 dark:text-gray-500 mb-2"></i>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            Haz clic para subir un archivo
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            PDF, DOC, DOCX, TXT, JPG, PNG, GIF (máx. 10MB)
                                        </p>
                                    </label>
                                </div>
                            </form>
                        </div>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Grading Section (for teachers and admins) -->
    @if(auth()->user()->hasAnyRole(['teacher', 'admin']) && $task->isSubmitted())
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Calificación</h3>
        </div>
        <div class="px-6 py-4">
            @if($task->grade !== null)
                <div id="gradeDisplay{{ $task->id }}" class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-green-800 dark:text-green-200">Calificación Asignada</h4>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-300">{{ number_format($task->grade, 0) }}/100</p>
                            @if($task->feedback)
                                <p class="text-sm text-green-600 dark:text-green-300 mt-1">{{ $task->feedback }}</p>
                            @endif
                            <p class="text-xs text-green-500 dark:text-green-400 mt-1">
                                Calificado el {{ $task->graded_at->format('d/m/Y H:i') }}
                                @if($task->grader)
                                    por {{ $task->grader->name }} {{ $task->grader->last_name }}
                                @endif
                            </p>
                        </div>
                        <button onclick="editGrade({{ $task->id }}, {{ $task->grade }}, '{{ addslashes($task->feedback ?? '') }}')" 
                                class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
                <div id="gradeEditForm{{ $task->id }}" class="hidden">
                    <form onsubmit="updateGrade(event, {{ $task->id }})">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Calificación (0-100)</label>
                                <input type="number" 
                                       id="editGrade{{ $task->id }}"
                                       name="grade" 
                                       min="0" 
                                       max="100" 
                                       step="1"
                                       value="{{ $task->grade }}"
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Comentarios</label>
                                <textarea name="feedback" 
                                          id="editFeedback{{ $task->id }}"
                                          rows="3"
                                          class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                          placeholder="Comentarios sobre la tarea...">{{ $task->feedback }}</textarea>
                            </div>
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <button type="submit" 
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-save mr-2"></i> Guardar Cambios
                            </button>
                            <button type="button" 
                                    onclick="cancelEditGrade({{ $task->id }})"
                                    class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-times mr-2"></i> Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <form id="gradeForm" onsubmit="submitGrade(event, {{ $task->id }})">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Calificación (0-100)</label>
                            <input type="number" 
                                   name="grade" 
                                   min="0" 
                                   max="100" 
                                   step="1"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Comentarios</label>
                            <textarea name="feedback" 
                                      rows="3"
                                      class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                      placeholder="Comentarios sobre la tarea..."></textarea>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" 
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-check mr-2"></i> Calificar Tarea
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="flex justify-between items-center">
        <a href="{{ route('tasks.index') }}" 
           class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Volver a Tareas
        </a>
        @can('update', $task)
        <a href="{{ route('tasks.edit', $task) }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-edit mr-2"></i> Editar Tarea
        </a>
        @endcan
    </div>
</div>

<script>
function uploadFile(taskId) {
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];
    
    if (!file) return;
    
    const formData = new FormData();
    formData.append('file', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Show loading
    const uploadArea = document.querySelector('.border-dashed');
    uploadArea.innerHTML = '<i class="fas fa-spinner fa-spin text-4xl text-indigo-500 mb-2"></i><p class="text-sm text-gray-600 dark:text-gray-300">Subiendo archivo...</p>';
    
    fetch(`/tasks/${taskId}/upload-file`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al subir el archivo');
        location.reload();
    });
}

function deleteFile(taskId) {
    if (confirm('¿Estás seguro de que quieres eliminar este archivo?')) {
        fetch(`/tasks/${taskId}/delete-file`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el archivo');
        });
    }
}

function submitGrade(event, taskId) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    fetch(`/tasks/${taskId}/grade`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al calificar la tarea');
    });
}

function editGrade(taskId, currentGrade, currentFeedback) {
    // Ocultar la vista de calificación
    document.getElementById('gradeDisplay' + taskId).classList.add('hidden');
    // Mostrar el formulario de edición
    document.getElementById('gradeEditForm' + taskId).classList.remove('hidden');
    
    // Establecer valores actuales
    document.getElementById('editGrade' + taskId).value = currentGrade;
    document.getElementById('editFeedback' + taskId).value = currentFeedback || '';
}

function cancelEditGrade(taskId) {
    // Ocultar el formulario de edición
    document.getElementById('gradeEditForm' + taskId).classList.add('hidden');
    // Mostrar la vista de calificación
    document.getElementById('gradeDisplay' + taskId).classList.remove('hidden');
}

function updateGrade(event, taskId) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    fetch(`/tasks/${taskId}/grade`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar la calificación');
    });
}
</script>
@endsection
