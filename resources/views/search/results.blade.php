@extends('layouts.dashboard')

@section('title', 'Resultados de búsqueda')
@section('page-title', 'Resultados de búsqueda')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <form action="{{ route('search') }}" method="GET" class="flex items-center gap-3">
            <div class="relative flex-1">
                <input type="text" name="q" value="{{ $query }}" placeholder="Buscar tareas, proyectos, cursos..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" autofocus>
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <button class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Buscar</button>
        </form>
    </div>

    @if($query === '')
        <div class="text-center text-gray-500 dark:text-gray-400">
            Ingresa un término para buscar.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Tareas</h3>
                @forelse($tasks as $task)
                    <a href="{{ route('tasks.show', $task) }}" class="block p-3 rounded hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $task->title }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ optional($task->course)->name }}</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $task->updated_at->diffForHumans() }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sin resultados</p>
                @endforelse
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Proyectos</h3>
                @forelse($projects as $project)
                    <a href="{{ route('projects.show', $project) }}" class="block p-3 rounded hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $project->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ optional($project->course)->name }}</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $project->updated_at->diffForHumans() }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sin resultados</p>
                @endforelse
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Cursos</h3>
                @forelse($courses as $course)
                    <a href="{{ route('courses.show', $course) }}" class="block p-3 rounded hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $course->name }}</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $course->updated_at->diffForHumans() }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sin resultados</p>
                @endforelse
            </div>

            @if(auth()->user()->hasRole('admin'))
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Usuarios</h3>
                @forelse($users as $u)
                    <a href="#" class="block p-3 rounded hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $u->name }} {{ $u->last_name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $u->email }}</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $u->updated_at->diffForHumans() }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sin resultados</p>
                @endforelse
            </div>
            @endif
        </div>
    @endif
</div>
@endsection


