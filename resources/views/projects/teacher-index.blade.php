@extends('layouts.dashboard')

@section('title', 'Proyectos de Estudiantes')
@section('page-title', 'Proyectos de Estudiantes')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total proyectos</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $stats['total'] ?? $projects->total() }}</p>
            </div>
            <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400">
                <i class="fas fa-project-diagram"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Entregados</p>
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
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">En desarrollo</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $stats['active'] ?? 0 }}</p>
            </div>
            <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Calificados</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $stats['graded'] ?? 0 }}</p>
            </div>
            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                <i class="fas fa-star"></i>
            </div>
        </div>
    </div>
</div>

<!-- Projects Section -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Proyectos de Estudiantes</h3>
    </div>
    
    <!-- Filters -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <form action="{{ route('teacher.student-projects') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
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
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Entrega</label>
                <select name="with_files" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                    <option value="">Todos</option>
                    <option value="1" {{ request('with_files') == '1' ? 'selected' : '' }}>Con archivos</option>
                    <option value="0" {{ request('with_files') == '0' ? 'selected' : '' }}>Sin archivos</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Calificación</label>
                <select name="graded" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                    <option value="">Todas</option>
                    <option value="1" {{ request('graded') == '1' ? 'selected' : '' }}>Calificados</option>
                    <option value="0" {{ request('graded') == '0' ? 'selected' : '' }}>Sin calificar</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-filter mr-2"></i> Aplicar Filtros
                </button>
                <a href="{{ route('teacher.student-projects') }}" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-refresh mr-2"></i> Resetear
                </a>
            </div>
        </form>
    </div>
    
    <!-- Projects List -->
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($projects as $project)
        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-4 mb-2">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $project->name }}</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $project->status === 'completed' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200' }}">
                            {{ ucfirst($project->status) }}
                        </span>
                        @if($project->isSubmitted())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                <i class="fas fa-paperclip mr-1"></i> Entregado
                            </span>
                        @endif
                        @if($project->grade !== null)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                <i class="fas fa-star mr-1"></i> {{ $project->grade }}/100
                            </span>
                        @endif
                    </div>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">{{ $project->description }}</p>
                    
                    <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400 mb-3">
                        <span><i class="fas fa-user mr-1"></i> {{ $project->user->name }} {{ $project->user->last_name }}</span>
                        @if($project->course)
                            <span><i class="fas fa-book mr-1"></i> {{ $project->course->name }}</span>
                        @endif
                        <span><i class="fas fa-calendar mr-1"></i> Vence: {{ $project->end_date->format('d/m/Y') }}</span>
                        @if($project->isSubmitted())
                            <span><i class="fas fa-clock mr-1"></i> Entregado: {{ $project->submitted_at->format('d/m/Y H:i') }}</span>
                        @endif
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-3">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progreso</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $project->progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                            <div class="h-2 rounded-full {{ $project->getProgressColor() }}" 
                                 style="width: {{ $project->progress }}%">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $project->getProgressText() }}</p>
                    </div>

                    @if($project->isSubmitted())
                    <div class="mt-3 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-file text-purple-500 mr-2"></i>
                                <div>
                                    <p class="text-sm text-purple-800 dark:text-purple-200 font-medium">{{ $project->file_name }}</p>
                                    <p class="text-xs text-purple-600 dark:text-purple-300">{{ $project->getFormattedFileSize() }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                @if($project->file_path)
                                <a href="{{ route('project-files.view', $project) }}" 
                                   class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-200"
                                   target="_blank"
                                   title="Ver archivo">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('project-files.download', $project) }}" 
                                   class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-200"
                                   title="Descargar archivo">
                                    <i class="fas fa-download"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($project->grade !== null)
                    <div class="mt-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-green-800 dark:text-green-200 font-medium">Calificación: {{ $project->grade }}/100</p>
                                @if($project->feedback)
                                    <p class="text-xs text-green-600 dark:text-green-300 mt-1">{{ $project->feedback }}</p>
                                @endif
                            </div>
                            <button onclick="editGrade({{ $project->id }})" 
                                    class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                    @elseif($project->isSubmitted())
                    <div class="mt-3">
                        <form id="gradeForm{{ $project->id }}" onsubmit="submitGrade(event, {{ $project->id }})">
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
                    <a href="{{ route('projects.show', $project) }}" 
                       class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300" 
                       title="Ver detalles">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <div class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500">
                <i class="fas fa-project-diagram text-4xl"></i>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay proyectos</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                No se han asignado proyectos a tus estudiantes aún.
            </p>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($projects->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        {{ $projects->links() }}
    </div>
    @endif
</div>

<script>
function submitGrade(event, projectId) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    fetch(`/teacher/projects/${projectId}/grade`, {
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
        alert('Error al calificar el proyecto');
    });
}

function editGrade(projectId) {
    // Implementar edición de calificación si es necesario
    alert('Funcionalidad de edición de calificación en desarrollo');
}
</script>
@endsection
