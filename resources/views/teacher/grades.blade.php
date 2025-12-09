@extends('layouts.dashboard')

@section('title', 'Calificaciones')
@section('page-title', 'Calificaciones')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Calificaciones Realizadas</h2>
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Tareas: {{ $gradedTasks->total() }} | Proyectos: {{ $gradedProjects->total() }}
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Tareas Calificadas -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tareas Calificadas</h3>
        </div>
        
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($gradedTasks as $task)
            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $task->title }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $task->user->name }} {{ $task->user->last_name }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $task->course->name ?? 'Sin curso' }}
                        </p>
                        @if($task->feedback)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 italic">
                            "{{ Str::limit($task->feedback, 100) }}"
                        </p>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($task->grade >= 90) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($task->grade >= 70) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                            {{ number_format($task->grade, 1) }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $task->graded_at->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                <i class="fas fa-graduation-cap text-4xl mb-2"></i>
                <p>No hay tareas calificadas</p>
            </div>
            @endforelse
        </div>
        
        @if($gradedTasks->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $gradedTasks->links() }}
        </div>
        @endif
    </div>
    
    <!-- Proyectos Calificados -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Proyectos Calificados</h3>
        </div>
        
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($gradedProjects as $project)
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
                        @if($project->feedback)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 italic">
                            "{{ Str::limit($project->feedback, 100) }}"
                        </p>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($project->grade >= 90) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($project->grade >= 70) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                            {{ number_format($project->grade, 1) }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $project->graded_at->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                <i class="fas fa-project-diagram text-4xl mb-2"></i>
                <p>No hay proyectos calificados</p>
            </div>
            @endforelse
        </div>
        
        @if($gradedProjects->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $gradedProjects->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Estadísticas de Calificaciones -->
<div class="mt-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Estadísticas de Calificaciones</h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                        {{ $gradedTasks->total() + $gradedProjects->total() }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Calificaciones</div>
                </div>
                
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                        {{ $gradedTasks->where('grade', '>=', 90)->count() + $gradedProjects->where('grade', '>=', 90)->count() }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Excelentes (90+)</div>
                </div>
                
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                        {{ $gradedTasks->whereBetween('grade', [70, 89.99])->count() + $gradedProjects->whereBetween('grade', [70, 89.99])->count() }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Buenas (70-89)</div>
                </div>
                
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                        {{ $gradedTasks->where('grade', '<', 70)->count() + $gradedProjects->where('grade', '<', 70)->count() }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Necesitan Mejora (<70)</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
