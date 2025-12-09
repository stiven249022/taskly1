@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tareas pendientes</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ auth()->user()->tasks()->where('status', 'pending')->count() ?? 0 }}</p>
            </div>
            <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400">
                <i class="fas fa-tasks"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Proyectos activos</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ auth()->user()->projects()->where('status', '!=', 'completed')->count() ?? 0 }}</p>
            </div>
            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                <i class="fas fa-project-diagram"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Próximos exámenes</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ auth()->user()->tasks()->where('type', 'exam')->where('due_date', '>=', now())->count() ?? 0 }}</p>
            </div>
            <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                <i class="fas fa-clipboard-list"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Promedio general</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ number_format(auth()->user()->average_grade, 1) }}</p>
            </div>
            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400">
                <i class="fas fa-star"></i>
            </div>
        </div>
    </div>
</div>

<!-- Eventos Próximos -->
<div class="mb-6">
    <x-upcoming-events-widget :events="$upcomingEvents ?? []" :isTeacher="false" />
</div>

<!-- Tasks and Projects -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Tasks Section -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Mis Tareas</h3>
                <a href="{{ route('tasks.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i> Nueva Tarea
                </a>
            </div>
            
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse(auth()->user()->tasks()->latest()->take(5)->get() as $task)
                <div class="task-item p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 {{ $task->priority }}_priority">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <input type="checkbox" 
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700"
                                   {{ $task->status === 'completed' ? 'checked' : '' }}
                                   onchange="toggleTaskStatus({{ $task->id }}, this.checked)">
                            <div class="flex-1">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white {{ $task->status === 'completed' ? 'line-through text-gray-500 dark:text-gray-400' : '' }}">
                                    {{ $task->title }}
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $task->description }}</p>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $task->priority === 'high' ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' : 
                                           ($task->priority === 'medium' ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' : 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200') }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                    @if($task->course)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                        {{ $task->course->name }}
                                    </span>
                                    @endif
                                    @if($task->due_date)
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-calendar mr-1"></i>
                                        Vence: {{ $task->due_date->format('d/m/Y') }}
                                    </span>
                                    @else
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-calendar mr-1"></i>
                                        Sin fecha límite
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @can('update', $task)
                            <a href="{{ route('tasks.edit', $task) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan
                            <button onclick="deleteTask({{ $task->id }})" class="text-red-400 hover:text-red-600 dark:hover:text-red-300">
                                <i class="fas fa-trash"></i>
                            </button>
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
                        Comienza creando tu primera tarea.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <i class="fas fa-plus mr-2"></i> Nueva Tarea
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 text-center">
                <a href="{{ route('tasks.index') }}" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium">
                    Ver todas las tareas
                </a>
            </div>
        </div>
    </div>
    
    <!-- Projects Section -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Mis Proyectos</h3>
                <a href="{{ route('projects.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i> Nuevo Proyecto
                </a>
            </div>
            
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse(auth()->user()->projects()->latest()->take(3)->get() as $project)
                <div class="task-item p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $project->name }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $project->description }}</p>
                            <div class="flex items-center space-x-4 mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $project->status === 'active' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 
                                       ($project->status === 'completed' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200' : 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200') }}">
                                    {{ ucfirst($project->status) }}
                                </span>
                                @if($project->course)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                    {{ $project->course->name }}
                                </span>
                                @endif
                            </div>
                            <div class="mt-3">
                                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
                                    <span>Progreso</span>
                                    <span>{{ $project->progress }}%</span>
                                </div>
                                <div class="progress-bar bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="progress-fill bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: {{ $project->progress }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            @can('update', $project)
                            <a href="{{ route('projects.edit', $project) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan
                            <button onclick="deleteProject({{ $project->id }})" class="text-red-400 hover:text-red-600 dark:hover:text-red-300">
                                <i class="fas fa-trash"></i>
                            </button>
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
                        Comienza creando tu primer proyecto.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <i class="fas fa-plus mr-2"></i> Nuevo Proyecto
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 text-center">
                <a href="{{ route('projects.index') }}" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium">
                    Ver todos los proyectos
                </a>
            </div>
        </div>
    </div>
</div>
    
<script>
function toggleTaskStatus(taskId, completed) {
    fetch(`/tasks/${taskId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ completed: completed })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteTask(taskId) {
    if (confirm('¿Estás seguro de que quieres eliminar esta tarea?')) {
        fetch(`/tasks/${taskId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function deleteProject(projectId) {
    if (confirm('¿Estás seguro de que quieres eliminar este proyecto?')) {
        fetch(`/projects/${projectId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

</script>
@endsection
