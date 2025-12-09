@extends('layouts.dashboard')

@section('title', 'Tareas de Estudiantes')
@section('page-title', 'Tareas de Estudiantes')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total tareas</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $stats['total'] ?? $tasks->total() }}</p>
            </div>
            <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400">
                <i class="fas fa-tasks"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Entregadas</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $stats['submitted'] ?? 0 }}</p>
            </div>
            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pendientes</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $stats['pending'] ?? 0 }}</p>
            </div>
            <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Calificadas</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $stats['graded'] ?? 0 }}</p>
            </div>
            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                <i class="fas fa-star"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tasks Section -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tareas de Estudiantes</h3>
    </div>
    
    <!-- Filters -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <form action="{{ route('teacher.student-tasks') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Materia</label>
                <select name="course" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                    <option value="">Todas las materias</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course') == $course->id || request('course') == (string)$course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                <select name="status" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                    <option value="">Todos los estados</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completada</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Entrega</label>
                <select name="with_files" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                    <option value="">Todas</option>
                    <option value="1" {{ request('with_files') == '1' ? 'selected' : '' }}>Con archivos</option>
                    <option value="0" {{ request('with_files') == '0' ? 'selected' : '' }}>Sin archivos</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Calificación</label>
                <select name="graded" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                    <option value="">Todas</option>
                    <option value="1" {{ request('graded') == '1' ? 'selected' : '' }}>Calificadas</option>
                    <option value="0" {{ request('graded') == '0' ? 'selected' : '' }}>Sin calificar</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-filter mr-2"></i> Aplicar Filtros
                </button>
                <a href="{{ route('teacher.student-tasks') }}" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-refresh mr-2"></i> Resetear
                </a>
            </div>
        </form>
    </div>
    
    <!-- Tasks List -->
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($tasks as $task)
        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-4 mb-2">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $task->title }}</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $task->status === 'completed' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' }}">
                            {{ ucfirst($task->status) }}
                        </span>
                        @if($task->isSubmitted())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                <i class="fas fa-paperclip mr-1"></i> Entregada
                            </span>
                        @endif
                        @if($task->grade !== null)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                <i class="fas fa-star mr-1"></i> {{ $task->grade }}/100
                            </span>
                        @endif
                    </div>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">{{ $task->description }}</p>
                    
                    <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                        <span><i class="fas fa-user mr-1"></i> {{ $task->user->name }} {{ $task->user->last_name }}</span>
                        @if($task->course)
                            <span><i class="fas fa-book mr-1"></i> {{ $task->course->name }}</span>
                        @endif
                        <span><i class="fas fa-calendar mr-1"></i> Vence: {{ $task->due_date->format('d/m/Y') }}</span>
                        @if($task->isSubmitted())
                            <span><i class="fas fa-clock mr-1"></i> Entregada: {{ $task->submitted_at->format('d/m/Y H:i') }}</span>
                        @endif
                    </div>

                    @if($task->isSubmitted())
                    <div class="mt-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-file text-blue-500 mr-2"></i>
                                <div>
                                    <p class="text-sm text-blue-800 dark:text-blue-200 font-medium">{{ $task->file_name }}</p>
                                    <p class="text-xs text-blue-600 dark:text-blue-300">{{ $task->getFormattedFileSize() }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                @if($task->file_path)
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
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($task->grade !== null)
                    <div id="gradeDisplay{{ $task->id }}" class="mt-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-green-800 dark:text-green-200 font-medium">Calificación: {{ $task->grade }}/100</p>
                                @if($task->feedback)
                                    <p class="text-xs text-green-600 dark:text-green-300 mt-1">{{ $task->feedback }}</p>
                                @endif
                            </div>
                            <button onclick="editGrade({{ $task->id }}, {{ $task->grade }}, '{{ addslashes($task->feedback ?? '') }}')" 
                                    class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                    <div id="gradeEditForm{{ $task->id }}" class="mt-3 hidden">
                        <form onsubmit="updateGrade(event, {{ $task->id }})">
                            @csrf
                            <div class="flex items-center space-x-4">
                                <div class="flex-1">
                                    <input type="number" 
                                           id="editGrade{{ $task->id }}"
                                           name="grade" 
                                           min="0" 
                                           max="100" 
                                           step="1"
                                           value="{{ $task->grade }}"
                                           placeholder="Calificación (0-100)"
                                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 text-sm"
                                           required>
                                </div>
                                <div class="flex-1">
                                    <input type="text" 
                                           id="editFeedback{{ $task->id }}"
                                           name="feedback" 
                                           value="{{ $task->feedback }}"
                                           placeholder="Comentarios..."
                                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 text-sm">
                                </div>
                                <button type="submit" 
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-save mr-1"></i> Guardar
                                </button>
                                <button type="button" 
                                        onclick="cancelEditGrade({{ $task->id }})"
                                        class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-times mr-1"></i> Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                    @elseif($task->isSubmitted())
                    <div class="mt-3">
                        <form id="gradeForm{{ $task->id }}" onsubmit="submitGrade(event, {{ $task->id }})">
                            @csrf
                            <div class="flex items-center space-x-4">
                                <div class="flex-1">
                                    <input type="number" 
                                           name="grade" 
                                           min="0" 
                                           max="100" 
                                           step="1"
                                           placeholder="Calificación (0-100)"
                                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 text-sm"
                                           required>
                                </div>
                                <div class="flex-1">
                                    <input type="text" 
                                           name="feedback" 
                                           placeholder="Comentarios..."
                                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 text-sm">
                                </div>
                                <button type="submit" 
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-check mr-1"></i> Calificar
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
                
                <div class="flex items-center space-x-2 ml-4">
                    <a href="{{ route('tasks.show', $task) }}" 
                       class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300" 
                       title="Ver detalles">
                        <i class="fas fa-eye"></i>
                    </a>
                    @can('update', $task)
                    <a href="{{ route('tasks.edit', $task) }}" 
                       class="text-indigo-400 dark:text-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-300" 
                       title="Editar tarea">
                        <i class="fas fa-edit"></i>
                    </a>
                    @endcan
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <div class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500">
                <i class="fas fa-tasks text-4xl"></i>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay tareas</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                No se han asignado tareas a tus estudiantes aún.
            </p>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($tasks->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        {{ $tasks->links() }}
    </div>
    @endif
</div>

<script>
function submitGrade(event, taskId) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    fetch(`/teacher/tasks/${taskId}/grade`, {
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
    const gradeDisplay = document.getElementById('gradeDisplay' + taskId);
    const gradeEditForm = document.getElementById('gradeEditForm' + taskId);
    
    if (gradeDisplay && gradeEditForm) {
        gradeDisplay.classList.add('hidden');
        gradeEditForm.classList.remove('hidden');
        
        // Establecer valores actuales
        document.getElementById('editGrade' + taskId).value = currentGrade;
        document.getElementById('editFeedback' + taskId).value = currentFeedback || '';
    }
}

function cancelEditGrade(taskId) {
    // Ocultar el formulario de edición
    const gradeEditForm = document.getElementById('gradeEditForm' + taskId);
    const gradeDisplay = document.getElementById('gradeDisplay' + taskId);
    
    if (gradeEditForm && gradeDisplay) {
        gradeEditForm.classList.add('hidden');
        gradeDisplay.classList.remove('hidden');
    }
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
