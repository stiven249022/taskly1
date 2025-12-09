@extends('layouts.dashboard')

@section('title', 'Detalle de Proyecto')
@section('page-title', 'Detalle de Proyecto')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->name }}</h1>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $project->status === 'completed' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 
                           ($project->status === 'active' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200') }}">
                        {{ ucfirst($project->status) }}
                    </span>
                    @if($project->isSubmitted())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                            <i class="fas fa-paperclip mr-1"></i> Entregado
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Project Details -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Información del Proyecto</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descripción</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $project->description ?: 'Sin descripción' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Materia</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $project->course->name ?? 'Sin materia' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Prioridad</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $project->priority === 'high' ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' : 
                                       ($project->priority === 'medium' ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' : 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200') }}">
                                    {{ ucfirst($project->priority) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de inicio</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $project->start_date ? $project->start_date->format('d/m/Y') : 'Sin fecha de inicio' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de vencimiento</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $project->end_date ? $project->end_date->format('d/m/Y') : 'Sin fecha límite' }}
                            </dd>
                        </div>
                        @if($project->grade !== null)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Calificación</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                    <i class="fas fa-star mr-1"></i>
                                    {{ number_format($project->grade, 0) }}/100
                                </span>
                                @if($project->feedback)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $project->feedback }}</p>
                                @endif
                            </dd>
                        </div>
                        @endif
                        @if($project->teacher)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Profesor</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $project->teacher->name }} {{ $project->teacher->last_name }}</dd>
                        </div>
                        @endif
                        @if($project->tags->count() > 0)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Etiquetas</dt>
                            <dd class="mt-1 flex flex-wrap gap-1">
                                @foreach($project->tags as $tag)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }}">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Progreso del Proyecto</h3>
                    @if($project->hasSupportFile())
                    <div class="mb-4 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-indigo-800 dark:text-indigo-200 mb-2">Material de apoyo</h4>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-file text-indigo-500 mr-2"></i>
                                <div>
                                    <p class="text-sm text-indigo-800 dark:text-indigo-200">{{ $project->support_file_name }}</p>
                                    <p class="text-xs text-indigo-600 dark:text-indigo-300">{{ $project->getFormattedSupportFileSize() }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('project-files.support.view', $project) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200" target="_blank" title="Ver material">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('project-files.support.download', $project) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200" title="Descargar material">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progreso</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $project->progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 dark:bg-gray-700">
                            <div class="h-3 rounded-full {{ $project->getProgressColor() }}" 
                                 style="width: {{ $project->progress }}%"
                                 data-progress="{{ $project->progress }}">
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $project->getProgressText() }}</p>
                    </div>

                    <!-- Progress Update Form -->
                    @can('update', $project)
                    <div class="mb-4">
                        <form id="progressForm" onsubmit="updateProgress(event, {{ $project->id }})">
                            @csrf
                            <div class="flex items-center space-x-2">
                                <input type="range" 
                                       name="progress" 
                                       min="0" 
                                       max="100" 
                                       value="{{ $project->progress }}"
                                       class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
                                       oninput="this.nextElementSibling.textContent = this.value + '%'">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 w-12">{{ $project->progress }}%</span>
                                <button type="submit" 
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-md text-sm font-medium">
                                    <i class="fas fa-save mr-1"></i> Actualizar
                                </button>
                            </div>
                        </form>
                    </div>
                    @endcan

                    <!-- Se elimina la sección de archivo general del proyecto. Los archivos serán por subtarea -->
                </div>
            </div>
        </div>
    </div>

    <!-- Calificación promedio de subtareas -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Calificación del Proyecto</h3>
        </div>
        <div class="px-6 py-4">
            @php
                $gradedCount = $project->projectTasks->whereNotNull('submitted_at')->whereNotNull('grade')->count();
                $avgGrade = $gradedCount > 0 ? round($project->projectTasks->whereNotNull('submitted_at')->whereNotNull('grade')->avg('grade')) : null;
            @endphp
            @if($avgGrade !== null)
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-green-800 dark:text-green-200">Promedio de Subtareas</h4>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-300">{{ number_format($avgGrade, 0) }}/100</p>
                            <p class="text-xs text-green-500 dark:text-green-400 mt-1">Basado en {{ $gradedCount }} subtarea(s) calificadas</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">Aún no hay subtareas calificadas.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Tasks Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Subtareas del Proyecto</h3>
            @can('manageSubtasks', $project)
            <a href="{{ route('project-tasks.index', $project) }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-tasks mr-2"></i> Gestionar Subtareas
            </a>
            @endcan
        </div>
        <div class="px-6 py-4">
            @php
                $taskStats = $project->getTaskStats();
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $taskStats['total'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $taskStats['completed'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Completadas</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $taskStats['in_progress'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">En Progreso</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $taskStats['pending'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Pendientes</div>
                </div>
            </div>
            
            @if($taskStats['total'] > 0)
                <div class="text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Progreso basado en {{ $taskStats['completed'] }} de {{ $taskStats['total'] }} subtareas completadas
                    </p>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-tasks text-4xl text-gray-300 dark:text-gray-600 mb-2"></i>
                    <p class="text-sm text-gray-500 dark:text-gray-400">No hay subtareas en este proyecto</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Agrega subtareas para organizar mejor tu proyecto</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Listado de subtareas con archivos y calificación -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Detalle de Subtareas</h3>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($project->projectTasks as $t)
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1 pr-4">
                        <div class="flex items-center gap-2 mb-1">
                            <h4 class="text-base font-medium text-gray-900 dark:text-white">{{ $t->title }}</h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $t->getStatusColor() }}">{{ $t->getStatusText() }}</span>
                            @if($t->isSubmitted())
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200"><i class="fas fa-paperclip mr-1"></i> Entregada</span>
                            @endif
                        </div>
                        @if($t->description)
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">{{ $t->description }}</p>
                        @endif

                        @if($t->isSubmitted())
                        <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-file text-purple-500 mr-2"></i>
                                    <div>
                                        <p class="text-sm text-purple-800 dark:text-purple-200 font-medium">{{ $t->file_name }}</p>
                                        <p class="text-xs text-purple-600 dark:text-purple-300">{{ $t->getFormattedFileSize() }}</p>
                                    </div>
                                </div>
                                <div class="flex space-x-3">
                                    <a href="{{ route('project-tasks.view-file', [$project, $t]) }}" target="_blank" class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-200" title="Ver archivo">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('project-tasks.download-file', [$project, $t]) }}" class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-200" title="Descargar archivo">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="w-full md:w-80">
                        @if($t->grade !== null)
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-green-800 dark:text-green-200">Calificación</p>
                                        <p class="text-xl font-bold text-green-600 dark:text-green-300">{{ number_format($t->grade, 0) }}/100</p>
                                        @if($t->feedback)
                                            <p class="text-xs text-green-600 dark:text-green-300 mt-1">{{ $t->feedback }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @elseif(auth()->user()->hasAnyRole(['teacher','admin']) && $t->isSubmitted())
                            <form onsubmit="return gradeSubtask(event, {{ $project->id }}, {{ $t->id }})" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                @csrf
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Calificación (0-100)</label>
                                <input type="number" name="grade" min="0" max="100" step="1" required class="w-full mb-2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Comentarios</label>
                                <textarea name="feedback" rows="2" class="w-full mb-2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-indigo-300 focus:ring focus:ring-indigo-200" placeholder="Comentarios (opcional)"></textarea>
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-md text-sm font-medium">
                                    <i class="fas fa-check mr-1"></i> Calificar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-between items-center">
        <a href="{{ route('projects.index') }}" 
           class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Volver a Proyectos
        </a>
        <div class="flex space-x-3">
            <a href="{{ route('project-tasks.index', $project) }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-tasks mr-2"></i> Ver Subtareas
            </a>
            @can('update', $project)
            <a href="{{ route('projects.edit', $project) }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-edit mr-2"></i> Editar Proyecto
            </a>
            @endcan
        </div>
    </div>
</div>

<script>
function gradeSubtask(event, projectId, subtaskId) {
    event.preventDefault();
    const formData = new FormData(event.target);
    fetch(`/projects/${projectId}/tasks/${subtaskId}/grade`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => r.json())
    .then(d => { if (d.success) { location.reload(); } else { alert('Error: ' + d.message); } })
    .catch(e => { console.error(e); alert('Error al calificar la subtarea'); });
}
function updateProgress(event, projectId) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    fetch(`/projects/${projectId}/update-progress`, {
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
            // Actualizar la barra de progreso visualmente
            const progressBar = document.querySelector('.h-3.rounded-full');
            const progressText = document.querySelector('.text-sm.text-gray-600');
            const progressPercent = document.querySelector('.text-sm.font-medium.text-gray-900');
            
            progressBar.style.width = data.progress + '%';
            progressBar.setAttribute('data-progress', data.progress);
            progressPercent.textContent = data.progress + '%';
            progressText.textContent = data.progress_text;
            
            // Actualizar el color de la barra
            progressBar.className = 'h-3 rounded-full ' + data.progress_color;
            
            // Mostrar notificación
            alert('Progreso actualizado exitosamente');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar el progreso');
    });
}

function uploadFile(projectId) {
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];
    
    if (!file) return;
    
    const formData = new FormData();
    formData.append('file', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Show loading
    const uploadArea = document.querySelector('.border-dashed');
    uploadArea.innerHTML = '<i class="fas fa-spinner fa-spin text-4xl text-indigo-500 mb-2"></i><p class="text-sm text-gray-600 dark:text-gray-300">Subiendo archivo...</p>';
    
    fetch(`/projects/${projectId}/upload-file`, {
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

function deleteFile(projectId) {
    if (confirm('¿Estás seguro de que quieres eliminar este archivo?')) {
        fetch(`/projects/${projectId}/delete-file`, {
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

</script>
@endsection
