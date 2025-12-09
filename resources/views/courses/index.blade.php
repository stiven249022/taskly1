@extends('layouts.dashboard')

@section('title', 'Mis Materias')
@section('page-title', 'Mis Materias')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total materias</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ is_countable($courses) ? count($courses) : $courses->count() }}</p>
            </div>
            <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400">
                <i class="fas fa-book"></i>
            </div>
        </div>
            </div>
            
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tareas activas</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ auth()->user()->tasks()->where('status', 'pending')->count() }}</p>
                    </div>
            <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                <i class="fas fa-tasks"></i>
            </div>
        </div>
            </div>
            
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
                    <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Proyectos activos</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ auth()->user()->projects()->where('status', 'active')->count() }}</p>
            </div>
            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                <i class="fas fa-project-diagram"></i>
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
                    
<!-- Courses Section -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Mis Materias</h3>
        @can('create', App\Models\Course::class)
        <a href="{{ route('courses.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i> Nueva Materia
        </a>
        @endcan
                    </div>
    
    <!-- Courses List -->
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($courses as $course)
        <div class="task-item p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold" 
                         style="background-color: {{ $course->color ?? '#4f46e5' }}">
                        {{ strtoupper(substr($course->name, 0, 2)) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $course->name }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $course->description }}</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-tasks mr-1"></i>
                                {{ $course->tasks()->count() }} tareas
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-project-diagram mr-1"></i>
                                {{ $course->projects()->count() }} proyectos
                            </span>
                            @if($course->credits)
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-graduation-cap mr-1"></i>
                                {{ $course->credits }} créditos
                            </span>
                @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('courses.show', $course) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors duration-200" title="Ver materia">
                        <i class="fas fa-eye"></i>
                    </a>
                    @if($course->user_id === auth()->id())
                        <a href="{{ route('courses.edit', $course) }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200" title="Editar materia">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button 
                            onclick="confirmDelete('{{ route('courses.destroy', $course) }}', '{{ $course->name }}', 'materia')"
                            class="text-red-400 dark:text-red-500 hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200"
                            title="Eliminar materia">
                            <i class="fas fa-trash"></i>
                        </button>
                    @else
                        <span class="text-xs text-gray-400 dark:text-gray-500" title="Materia asignada por administrador">
                            <i class="fas fa-lock"></i>
                        </span>
                    @endif
                </div>
                        </div>
                    </div>
                    @empty
        <div class="p-12 text-center">
            <div class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500">
                <i class="fas fa-book text-4xl"></i>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay materias</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Comienza agregando tu primera materia.
            </p>
            <div class="mt-6">
                <a href="{{ route('courses.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <i class="fas fa-plus mr-2"></i> Nueva Materia
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>
    
    <!-- Pagination -->
    @if(method_exists($courses, 'hasPages') && $courses->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        {{ $courses->links() }}
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