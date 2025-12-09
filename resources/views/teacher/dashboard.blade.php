@extends('layouts.dashboard')

@section('title', 'Dashboard Profesor')
@section('page-title', 'Dashboard Profesor')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Estudiantes</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $stats['total_students'] }}</p>
            </div>
            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tareas Pendientes</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $stats['pending_tasks'] }}</p>
            </div>
            <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                <i class="fas fa-tasks"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Proyectos Activos</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $stats['active_projects'] }}</p>
            </div>
            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                <i class="fas fa-project-diagram"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Por Calificar</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $tasksToGrade->count() + $projectsToGrade->count() }}</p>
            </div>
            <div class="p-3 rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400">
                <i class="fas fa-graduation-cap"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tareas para Calificar -->
<div class="mb-6">
    <x-upcoming-events-widget :events="$upcomingEvents ?? []" :isTeacher="true" />
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Tasks to Grade -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tareas por Calificar</h3>
                <a href="{{ route('teacher.students') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                    Ver todas
                </a>
            </div>
            
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($tasksToGrade as $task)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $task->title }}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Completada
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Estudiante: {{ $task->user->name }} {{ $task->user->last_name }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Curso: {{ $task->course->name ?? 'Sin curso' }}
                            </p>
                            
                            <!-- Archivo subido -->
                            @if($task->hasFile())
                            <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2 flex-1 min-w-0">
                                        <i class="fas fa-file text-indigo-600 dark:text-indigo-400"></i>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $task->file_name }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $task->getFormattedFileSize() }}
                                                @if($task->submitted_at)
                                                    • Entregado: {{ $task->submitted_at->format('d/m/Y H:i') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2 ml-3">
                                        @if(in_array($task->file_type, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf']))
                                        <a href="{{ $task->getFileUrl() }}" target="_blank" 
                                           class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300"
                                           title="Ver archivo">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @endif
                                        <a href="{{ $task->getFileUrl() }}" download="{{ $task->file_name }}"
                                           class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300"
                                           title="Descargar archivo">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="mt-3 p-2 bg-yellow-50 dark:bg-yellow-900 rounded-lg border border-yellow-200 dark:border-yellow-700">
                                <p class="text-xs text-yellow-800 dark:text-yellow-200 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    No se ha subido ningún archivo
                                </p>
                            </div>
                            @endif
                        </div>
                        @if($task->isSubmitted())
                        <div class="flex items-center space-x-2 ml-4">
                            <button onclick="openGradeModal('task', {{ $task->id }}, '{{ $task->title }}')" 
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-md text-sm font-medium">
                                Calificar
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                    <i class="fas fa-check-circle text-4xl mb-2"></i>
                    <p>No hay tareas pendientes de calificación</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Projects to Grade -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Proyectos por Calificar</h3>
                <a href="{{ route('teacher.students') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                    Ver todos
                </a>
            </div>
            
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($projectsToGrade as $project)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $project->name }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $project->user->name }} {{ $project->user->last_name }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $project->course->name ?? 'Sin curso' }}
                            </p>
                            
                            <!-- Archivo subido -->
                            @if($project->hasFile())
                            <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2 flex-1 min-w-0">
                                        <i class="fas fa-file text-indigo-600 dark:text-indigo-400"></i>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $project->file_name }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $project->getFormattedFileSize() }}
                                                @if($project->submitted_at)
                                                    • Entregado: {{ $project->submitted_at->format('d/m/Y H:i') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2 ml-3">
                                        @if(in_array($project->file_type, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf']))
                                        <a href="{{ $project->getFileUrl() }}" target="_blank" 
                                           class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300"
                                           title="Ver archivo">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @endif
                                        <a href="{{ $project->getFileUrl() }}" download="{{ $project->file_name }}"
                                           class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300"
                                           title="Descargar archivo">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="mt-3 p-2 bg-yellow-50 dark:bg-yellow-900 rounded-lg border border-yellow-200 dark:border-yellow-700">
                                <p class="text-xs text-yellow-800 dark:text-yellow-200 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    No se ha subido ningún archivo
                                </p>
                            </div>
                            @endif
                        </div>
                        @if($project->isSubmitted())
                        <div class="flex items-center space-x-2 ml-4">
                            <button onclick="openGradeModal('project', {{ $project->id }}, '{{ $project->name }}')" 
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-md text-sm font-medium">
                                Calificar
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                    <i class="fas fa-check-circle text-4xl mb-2"></i>
                    <p>No hay proyectos pendientes de calificación</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="mt-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Actividades Recientes</h3>
        </div>
        
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($recentActivities as $activity)
            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        @if($activity->type === 'task')
                            <i class="fas fa-tasks text-indigo-600"></i>
                        @else
                            <i class="fas fa-project-diagram text-green-600"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $activity->type === 'task' ? $activity->title : $activity->name }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $activity->user->name }} {{ $activity->user->last_name }} - 
                            {{ $activity->updated_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($activity->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($activity->status === 'active') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                            {{ ucfirst($activity->status) }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                <i class="fas fa-history text-4xl mb-2"></i>
                <p>No hay actividades recientes</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Grade Modal -->
<div id="gradeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white" id="modalTitle">Calificar</h3>
                <button onclick="closeGradeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="gradeForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="grade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Calificación (0-100)
                    </label>
                    <input type="number" id="grade" name="grade" min="0" max="100" step="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                
                <div class="mb-4">
                    <label for="feedback" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Comentarios (opcional)
                    </label>
                    <textarea id="feedback" name="feedback" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeGradeModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Calificar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Navegación Rápida -->
<div class="mt-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Acciones Rápidas</h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('teacher.students') }}" 
                   class="flex items-center p-4 bg-blue-50 dark:bg-blue-900 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-blue-600 dark:text-blue-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">Mis Estudiantes</h4>
                        <p class="text-sm text-blue-700 dark:text-blue-300">Ver lista de estudiantes</p>
                    </div>
                </a>
                
                <a href="{{ route('teacher.tasks') }}" 
                   class="flex items-center p-4 bg-green-50 dark:bg-green-900 rounded-lg hover:bg-green-100 dark:hover:bg-green-800 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-tasks text-green-600 dark:text-green-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-green-900 dark:text-green-100">Calificar Tareas</h4>
                        <p class="text-sm text-green-700 dark:text-green-300">Revisar tareas pendientes</p>
                    </div>
                </a>
                
                <a href="{{ route('teacher.projects') }}" 
                   class="flex items-center p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-800 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-project-diagram text-yellow-600 dark:text-yellow-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-yellow-900 dark:text-yellow-100">Calificar Proyectos</h4>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300">Revisar proyectos pendientes</p>
                    </div>
                </a>
                
                <a href="{{ route('teacher.analytics') }}" 
                   class="flex items-center p-4 bg-purple-50 dark:bg-purple-900 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-chart-bar text-purple-600 dark:text-purple-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-purple-900 dark:text-purple-100">Análisis</h4>
                        <p class="text-sm text-purple-700 dark:text-purple-300">Ver estadísticas y métricas</p>
                    </div>
                </a>
                
                <a href="{{ route('teacher.create-task') }}" 
                   class="flex items-center p-4 bg-green-50 dark:bg-green-900 rounded-lg hover:bg-green-100 dark:hover:bg-green-800 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-plus-circle text-green-600 dark:text-green-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-green-900 dark:text-green-100">Crear Tarea</h4>
                        <p class="text-sm text-green-700 dark:text-green-300">Asignar nueva tarea a estudiantes</p>
                    </div>
                </a>
                
                <a href="{{ route('teacher.create-project') }}" 
                   class="flex items-center p-4 bg-blue-50 dark:bg-blue-900 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-project-diagram text-blue-600 dark:text-blue-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">Crear Proyecto</h4>
                        <p class="text-sm text-blue-700 dark:text-blue-300">Asignar nuevo proyecto a estudiantes</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function openGradeModal(type, id, title) {
    document.getElementById('modalTitle').textContent = `Calificar ${type === 'task' ? 'Tarea' : 'Proyecto'}: ${title}`;
    document.getElementById('gradeForm').action = type === 'task' 
        ? `/teacher/tasks/${id}/grade` 
        : `/teacher/projects/${id}/grade`;
    document.getElementById('gradeModal').classList.remove('hidden');
}

function closeGradeModal() {
    document.getElementById('gradeModal').classList.add('hidden');
    document.getElementById('gradeForm').reset();
}
</script>
@endsection
