@extends('layouts.dashboard')

@section('title', 'Panel de Tareas')
@section('page-title', 'Panel de Tareas')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total tareas</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $tasks->count() }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400">
                                <i class="fas fa-tasks"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pendientes</p>
                                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $tasks->where('status', 'pending')->count() }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completadas</p>
                                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $tasks->where('status', 'completed')->count() }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Vencidas</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $tasks->where('status', 'pending')->where('due_date', '<', now())->count() }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tasks Section -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Mis Tareas</h3>
        <a href="{{ route('tasks.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i> Nueva Tarea
                                </a>
                            </div>
                            
    <!-- Filters -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <form action="{{ route('tasks.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por título o descripción..." class="w-full pl-10 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
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
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prioridad</label>
                <select name="priority" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                                <option value="">Todas las prioridades</option>
                                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Alta</option>
                                                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Media</option>
                                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Baja</option>
                                            </select>
                                        </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                <select name="status" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                                <option value="">Todos los estados</option>
                                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completada</option>
                                            </select>
                                        </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-filter mr-2"></i> Aplicar Filtros
                                        </button>
                <a href="{{ route('tasks.index') }}" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-refresh mr-2"></i> Resetear
                                        </a>
                                    </div>
                                </form>
                            </div>
                            
    <!-- Tasks List -->
                            <div class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($tasks as $task)
        <div class="task-item p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 {{ $task->priority }}_priority">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                                                    <input type="checkbox" 
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded"
                                                           {{ $task->status === 'completed' ? 'checked' : '' }}
                           onchange="toggleTaskStatus({{ $task->id }}, this.checked)">
                    <div class="flex-1">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white {{ $task->status === 'completed' ? 'line-through text-gray-500 dark:text-gray-400' : '' }}">
                                                        {{ $task->title }}
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $task->description }}</p>
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
                            @if($task->grade !== null)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                <i class="fas fa-star mr-1"></i>
                                Nota: {{ number_format($task->grade, 0) }}/100
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
                    @if($task->isSubmitted())
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                            <i class="fas fa-paperclip mr-1"></i> Entregada
                        </span>
                        <a href="{{ route('task-files.view', $task) }}" 
                           class="text-blue-400 dark:text-blue-500 hover:text-blue-600 dark:hover:text-blue-300" 
                           target="_blank"
                           title="Ver archivo">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('task-files.download', $task) }}" 
                           class="text-blue-400 dark:text-blue-500 hover:text-blue-600 dark:hover:text-blue-300" 
                           title="Descargar archivo">
                            <i class="fas fa-download"></i>
                        </a>
                    @else
                        <button 
                            data-toggle="{{ route('tasks.toggle-status', $task) }}"
                            data-item-type="tarea"
                            class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300"
                            title="{{ $task->status === 'completed' ? 'Marcar como pendiente' : 'Marcar como completada' }}">
                            <i class="fas {{ $task->status === 'completed' ? 'fa-undo' : 'fa-check' }}"></i>
                        </button>
                    @endif
                    <a href="{{ route('tasks.show', $task) }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300" title="Ver detalles">
                        <i class="fas fa-eye"></i>
                    </a>
                    @can('update', $task)
                    <a href="{{ route('tasks.edit', $task) }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300" title="Editar tarea">
                        <i class="fas fa-edit"></i>
                    </a>
                    @endcan
                    <button 
                        onclick="confirmDelete('{{ route('tasks.destroy', $task) }}', '{{ $task->title }}', 'tarea')"
                        class="text-red-400 dark:text-red-500 hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200"
                        title="Eliminar tarea">
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
                            
                            <!-- Pagination -->
    @if($tasks->hasPages())
                            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                                {{ $tasks->links() }}
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
