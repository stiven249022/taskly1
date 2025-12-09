@extends('layouts.dashboard')

@section('title', 'Panel de Proyectos')
@section('page-title', 'Panel de Proyectos')

@section('content')
<!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total proyectos</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $projects->count() }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400">
                                <i class="fas fa-project-diagram"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Activos</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $projects->where('status', 'active')->count() }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                <i class="fas fa-play-circle"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completados</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $projects->where('status', 'completed')->count() }}</p>
                            </div>
            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Promedio progreso</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ number_format($projects->avg('progress'), 0) }}%</p>
                            </div>
            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
<!-- Projects Section -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Mis Proyectos</h3>
        <a href="{{ route('projects.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i> Nuevo Proyecto
                    </a>
                </div>
                
                <!-- Filters -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <form action="{{ route('projects.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre o descripción..." class="w-full pl-10 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Materia</label>
                <select name="course" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                    <option value="">Todas las materias</option>
                                @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
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
                    <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>En Pausa</option>
                            </select>
                        </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Progreso</label>
                <select name="progress" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                    <option value="">Cualquier progreso</option>
                    <option value="0-25" {{ request('progress') == '0-25' ? 'selected' : '' }}>0-25%</option>
                    <option value="26-50" {{ request('progress') == '26-50' ? 'selected' : '' }}>26-50%</option>
                    <option value="51-75" {{ request('progress') == '51-75' ? 'selected' : '' }}>51-75%</option>
                    <option value="76-100" {{ request('progress') == '76-100' ? 'selected' : '' }}>76-100%</option>
                            </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-filter mr-2"></i> Aplicar Filtros
                </button>
                <a href="{{ route('projects.index') }}" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-refresh mr-2"></i> Resetear
                </a>
            </div>
        </form>
                        </div>
                        
    <!-- Projects List -->
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($projects as $project)
        <div class="task-item p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $project->name }}</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $project->status === 'active' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 
                               ($project->status === 'completed' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200' : 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200') }}">
                            {{ ucfirst($project->status) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">{{ $project->description }}</p>
                    <div class="flex items-center space-x-4 mb-3">
                        @if($project->course)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                            {{ $project->course->name }}
                        </span>
                        @endif
                        @if($project->grade !== null)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                            <i class="fas fa-star mr-1"></i>
                            Nota: {{ number_format($project->grade, 0) }}/100
                        </span>
                        @endif
                        @if($project->isSubmitted())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                <i class="fas fa-paperclip mr-1"></i> Entregado
                            </span>
                        @endif
                        @php
                            $taskStats = $project->getTaskStats();
                        @endphp
                        @if($taskStats['total'] > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                <i class="fas fa-tasks mr-1"></i> {{ $taskStats['completed'] }}/{{ $taskStats['total'] }} subtareas
                            </span>
                        @endif
                        @if($project->end_date)
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-calendar mr-1"></i>
                            Vence: {{ $project->end_date->format('d/m/Y') }}
                        </span>
                        @else
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-calendar mr-1"></i>
                            Sin fecha límite
                        </span>
                        @endif
                    </div>
                    <div class="mb-2">
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300 mb-1">
                            <span>Progreso</span>
                            <span>{{ $project->progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                            <div class="h-2 rounded-full {{ $project->getProgressColor() }}" 
                                 style="width: {{ $project->progress }}%">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $project->getProgressText() }}</p>
                    </div>
                </div>
                    <div class="flex items-center space-x-2 ml-4">
                        @if($project->isSubmitted())
                            <a href="{{ route('project-files.view', $project) }}" 
                               class="text-purple-400 dark:text-purple-500 hover:text-purple-600 dark:hover:text-purple-300" 
                               target="_blank"
                               title="Ver archivo">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('project-files.download', $project) }}" 
                               class="text-purple-400 dark:text-purple-500 hover:text-purple-600 dark:hover:text-purple-300" 
                               title="Descargar archivo">
                                <i class="fas fa-download"></i>
                            </a>
                        @else
                            <button 
                                data-toggle="{{ route('projects.toggle-status', $project) }}"
                                data-item-type="proyecto"
                                class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200"
                                title="{{ $project->status === 'completed' ? 'Marcar como activo' : 'Marcar como completado' }}">
                                <i class="fas {{ $project->status === 'completed' ? 'fa-undo' : 'fa-check' }}"></i>
                            </button>
                        @endif
                        <a href="{{ route('project-tasks.index', $project) }}" 
                           class="text-indigo-400 dark:text-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-300 transition-colors duration-200" 
                           title="Gestionar subtareas">
                            <i class="fas fa-tasks"></i>
                        </a>
                        <a href="{{ route('projects.show', $project) }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('update', $project)
                        <a href="{{ route('projects.edit', $project) }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200" title="Editar proyecto">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                        <button 
                            onclick="confirmDelete('{{ route('projects.destroy', $project) }}', '{{ $project->name }}', 'proyecto')"
                            class="text-red-400 dark:text-red-500 hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200"
                            title="Eliminar proyecto">
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
                        
    <!-- Pagination -->
    @if($projects->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        {{ $projects->links() }}
                            </div>
    @endif
    </div>

    <script>
// Las funcionalidades están manejadas por el archivo app.js
// Validación de formularios
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            if (!isValid) {
                    e.preventDefault();
                Taskly.showNotification('Por favor, completa todos los campos requeridos', 'warning');
                    }
                });
            });
});
    </script>
@endsection 
