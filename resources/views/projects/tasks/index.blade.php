@extends('layouts.dashboard')

@section('title', 'Subtareas del Proyecto')
@section('page-title', 'Subtareas del Proyecto')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Project Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->name }}</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $project->description }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $project->status === 'completed' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 
                           ($project->status === 'active' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200') }}">
                        {{ ucfirst($project->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Progress Section -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->getTaskStats()['total'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Subtareas</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $project->getTaskStats()['completed'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Completadas</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $project->getTaskStats()['in_progress'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">En Progreso</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $project->getTaskStats()['pending'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Pendientes</div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-4">
                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300 mb-2">
                    <span>Progreso General</span>
                    <span>{{ $project->progress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 dark:bg-gray-700">
                    <div class="h-3 rounded-full {{ $project->getProgressColor() }}" 
                         style="width: {{ $project->progress }}%">
                    </div>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $project->getProgressText() }}</p>
            </div>
        </div>
    </div>

    <!-- Tasks Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Subtareas del Proyecto</h3>
            @can('manageSubtasks', $project)
            @if(auth()->user()->hasAnyRole(['teacher','admin']))
            <button onclick="openCreateTaskModal()" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i> Nueva Subtarea
            </button>
            @endif
            @endcan
        </div>

        <!-- Tasks List -->
        <div id="tasksList" class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($project->projectTasks as $task)
            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 task-item" data-task-id="{{ $task->id }}">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4 mb-2">
                            <div class="flex items-center space-x-2">
                                @can('manageSubtasks', $project)
                                <button onclick="toggleTaskStatus({{ $task->id }})" 
                                        class="w-5 h-5 rounded border-2 flex items-center justify-center
                                        {{ $task->isCompleted() ? 'bg-green-500 border-green-500 text-white' : 
                                           ($task->isInProgress() ? 'bg-blue-500 border-blue-500 text-white' : 'border-gray-300 dark:border-gray-600') }}">
                                    @if($task->isCompleted())
                                        <i class="fas fa-check text-xs"></i>
                                    @elseif($task->isInProgress())
                                        <i class="fas fa-play text-xs"></i>
                                    @endif
                                </button>
                                @endcan
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white {{ $task->isCompleted() ? 'line-through text-gray-500 dark:text-gray-400' : '' }}">
                                    {{ $task->title }}
                                </h4>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->getStatusColor() }}">
                                {{ $task->getStatusText() }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->getPriorityColor() }}">
                                {{ $task->getPriorityText() }}
                            </span>
                            @if($task->isSubmitted())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                    <i class="fas fa-paperclip mr-1"></i> Entregada
                                </span>
                            @endif
                        </div>
                        
                        @if($task->description)
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">{{ $task->description }}</p>
                        @endif

                        <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400 mb-3">
                            @if($task->due_date)
                                <span class="flex items-center {{ $task->isOverdue() ? 'text-red-500 dark:text-red-400' : '' }}">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Vence: {{ $task->due_date->format('d/m/Y') }}
                                </span>
                            @endif
                            @if($task->isSubmitted())
                                <span class="flex items-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    Entregada: {{ $task->submitted_at->format('d/m/Y H:i') }}
                                </span>
                            @endif
                        </div>

                        @if($task->isSubmitted())
                        <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-file text-purple-500 mr-2"></i>
                                    <div>
                                        <p class="text-sm text-purple-800 dark:text-purple-200 font-medium">{{ $task->file_name }}</p>
                                        <p class="text-xs text-purple-600 dark:text-purple-300">{{ $task->getFormattedFileSize() }}</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('project-tasks.view-file', [$project, $task]) }}" 
                                       class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-200"
                                       target="_blank"
                                       title="Ver archivo">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('project-tasks.download-file', [$project, $task]) }}" 
                                       class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-200"
                                       title="Descargar archivo">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    @can('submit', $project)
                                    <button onclick="deleteTaskFile({{ $task->id }})" 
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200"
                                            title="Eliminar archivo">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- File Upload Form -->
                        @can('submit', $project)
                        <div class="mt-3">
                            <form id="fileUploadForm{{ $task->id }}" enctype="multipart/form-data">
                                @csrf
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                                    <input type="file" 
                                           id="fileInput{{ $task->id }}" 
                                           name="file" 
                                           accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar"
                                           class="hidden" 
                                           onchange="uploadTaskFile({{ $task->id }})">
                                    <label for="fileInput{{ $task->id }}" class="cursor-pointer">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 dark:text-gray-500 mb-1"></i>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            Subir archivo para completar esta subtarea
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            PDF, DOC, DOCX, TXT, JPG, PNG, GIF, ZIP, RAR (máx. 10MB)
                                        </p>
                                    </label>
                                </div>
                            </form>
                        </div>
                        @endcan
                        @endif
                    </div>
                    
                    <div class="flex items-center space-x-2 ml-4">
                        @can('manageSubtasks', $project)
                        @if(auth()->user()->hasAnyRole(['teacher','admin']))
                        <button onclick="editTask({{ $task->id }})" 
                                class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300" 
                                title="Editar subtarea">
                            <i class="fas fa-edit"></i>
                        </button>
                        @endif
                        <button onclick="deleteTask({{ $task->id }})" 
                                class="text-red-400 dark:text-red-500 hover:text-red-600 dark:hover:text-red-400" 
                                title="Eliminar subtarea">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endcan
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <div class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500">
                    <i class="fas fa-tasks text-4xl"></i>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay subtareas</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Comienza agregando la primera subtarea a este proyecto.
                </p>
                @can('manageSubtasks', $project)
                @if(auth()->user()->hasAnyRole(['teacher','admin']))
                <div class="mt-6">
                    <button onclick="openCreateTaskModal()" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <i class="fas fa-plus mr-2"></i> Nueva Subtarea
                    </button>
                </div>
                @endif
                @endcan
            </div>
            @endforelse
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-between items-center mt-6">
        <a href="{{ route('projects.show', $project) }}" 
           class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Volver al Proyecto
        </a>
        <a href="{{ route('projects.index') }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-list mr-2"></i> Ver Todos los Proyectos
        </a>
    </div>
</div>

<!-- Create Task Modal -->
<div id="createTaskModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Nueva Subtarea</h3>
            </div>
            <form id="createTaskForm">
                @csrf
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título</label>
                        <input type="text" 
                               name="title" 
                               required
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                        <textarea name="description" 
                                  rows="3"
                                  class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha límite</label>
                        <input type="datetime-local" 
                               name="due_date"
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prioridad</label>
                        <select name="priority" 
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                            <option value="1">Muy Baja</option>
                            <option value="2">Baja</option>
                            <option value="3" selected>Media</option>
                            <option value="4">Alta</option>
                            <option value="5">Crítica</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notas</label>
                        <textarea name="notes" 
                                  rows="2"
                                  class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeCreateTaskModal()"
                            class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md text-sm font-medium">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Crear Subtarea
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCreateTaskModal() {
    document.getElementById('createTaskModal').classList.remove('hidden');
}

function closeCreateTaskModal() {
    document.getElementById('createTaskModal').classList.add('hidden');
    document.getElementById('createTaskForm').reset();
}

function toggleTaskStatus(taskId) {
    fetch(`/projects/{{ $project->id }}/tasks/${taskId}/toggle-status`, {
        method: 'POST',
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
        alert('Error al actualizar el estado de la subtarea');
    });
}

function uploadTaskFile(taskId) {
    const fileInput = document.getElementById(`fileInput${taskId}`);
    const file = fileInput.files[0];
    
    if (!file) return;
    
    const formData = new FormData();
    formData.append('file', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Show loading
    const uploadArea = fileInput.closest('.border-dashed');
    uploadArea.innerHTML = '<i class="fas fa-spinner fa-spin text-2xl text-indigo-500 mb-1"></i><p class="text-sm text-gray-600 dark:text-gray-300">Subiendo archivo...</p>';
    
    fetch(`/projects/{{ $project->id }}/tasks/${taskId}/upload-file`, {
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

function deleteTaskFile(taskId) {
    if (confirm('¿Estás seguro de que quieres eliminar este archivo?')) {
        fetch(`/projects/{{ $project->id }}/tasks/${taskId}/delete-file`, {
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

function editTask(taskId) {
    // Implementar edición de subtarea
    alert('Funcionalidad de edición en desarrollo');
}

function deleteTask(taskId) {
    if (confirm('¿Estás seguro de que quieres eliminar esta subtarea?')) {
        fetch(`/projects/{{ $project->id }}/tasks/${taskId}`, {
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
            alert('Error al eliminar la subtarea');
        });
    }
}

// Create task form
document.getElementById('createTaskForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`/projects/{{ $project->id }}/tasks`, {
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
        alert('Error al crear la subtarea');
    });
});
</script>
@endsection
