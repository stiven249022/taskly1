@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado y Botón de Nueva Etiqueta -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Etiquetas</h1>
        <a href="{{ route('tags.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Nueva Etiqueta
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="{{ route('tags.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                    placeholder="Buscar por nombre...">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">
                    <i class="fas fa-search mr-2"></i>
                    Buscar
                </button>
            </div>
        </form>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="text-2xl font-semibold">{{ $tags->count() }}</p>
                </div>
                <span class="text-purple-500">
                    <i class="fas fa-tags text-2xl"></i>
                </span>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">En Tareas</p>
                    <p class="text-2xl font-semibold">{{ $tags->sum(function($tag) { return $tag->tasks->count(); }) }}</p>
                </div>
                <span class="text-blue-500">
                    <i class="fas fa-tasks text-2xl"></i>
                </span>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">En Proyectos</p>
                    <p class="text-2xl font-semibold">{{ $tags->sum(function($tag) { return $tag->projects->count(); }) }}</p>
                </div>
                <span class="text-green-500">
                    <i class="fas fa-project-diagram text-2xl"></i>
                </span>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">En Recordatorios</p>
                    <p class="text-2xl font-semibold">{{ $tags->sum(function($tag) { return $tag->reminders->count(); }) }}</p>
                </div>
                <span class="text-yellow-500">
                    <i class="fas fa-bell text-2xl"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Lista de Etiquetas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tags as $tag)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $tag->name }}</h3>
                    <span class="w-6 h-6 rounded-full" style="background-color: {{ $tag->color }}"></span>
                </div>
                
                <div class="space-y-4">
                    <!-- Uso en Tareas -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Tareas ({{ $tag->tasks->count() }})</h4>
                        <div class="space-y-2">
                            @forelse($tag->tasks->take(3) as $task)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">{{ $task->title }}</span>
                                <span class="px-2 py-1 text-xs rounded-full {{ $task->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $task->status === 'completed' ? 'Completada' : 'Pendiente' }}
                                </span>
                            </div>
                            @empty
                            <p class="text-sm text-gray-500">No hay tareas</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Uso en Proyectos -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Proyectos ({{ $tag->projects->count() }})</h4>
                        <div class="space-y-2">
                            @forelse($tag->projects->take(3) as $project)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">{{ $project->title }}</span>
                                <span class="px-2 py-1 text-xs rounded-full {{ $project->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $project->status === 'completed' ? 'Completado' : 'Activo' }}
                                </span>
                            </div>
                            @empty
                            <p class="text-sm text-gray-500">No hay proyectos</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Uso en Recordatorios -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Recordatorios ({{ $tag->reminders->count() }})</h4>
                        <div class="space-y-2">
                            @forelse($tag->reminders->take(3) as $reminder)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">{{ $reminder->title }}</span>
                                <span class="px-2 py-1 text-xs rounded-full {{ $reminder->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $reminder->status === 'completed' ? 'Completado' : 'Pendiente' }}
                                </span>
                            </div>
                            @empty
                            <p class="text-sm text-gray-500">No hay recordatorios</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex justify-end space-x-2">
                    <a href="{{ route('tags.edit', $tag) }}" class="text-blue-600 hover:text-blue-900">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('tags.destroy', $tag) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro de eliminar esta etiqueta?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
                No hay etiquetas disponibles
            </div>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    <div class="mt-6">
        {{ $tags->links() }}
    </div>
</div>
@endsection 