@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Resultados de Búsqueda</h1>
            <p class="text-gray-600">Resultados para: "{{ $query }}"</p>
        </div>

        <!-- Barra de Búsqueda -->
        <div class="max-w-2xl mx-auto mb-8">
            <form action="{{ route('help.search') }}" method="GET" class="relative">
                <input type="text" name="query" value="{{ $query }}" placeholder="Buscar en la documentación..." 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Resultados -->
        <div class="space-y-6">
            @forelse($results as $result)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <span class="w-8 h-8 rounded-full flex items-center justify-center" style="background-color: {{ $result->color }}20">
                            <i class="{{ $result->icon }} text-sm" style="color: {{ $result->color }}"></i>
                        </span>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <a href="{{ $result->url }}" class="hover:text-blue-600">{{ $result->title }}</a>
                        </h3>
                        <p class="text-sm text-gray-500 mb-2">{{ $result->category }}</p>
                        <p class="text-gray-600">{{ $result->excerpt }}</p>
                        <div class="mt-2">
                            <a href="{{ $result->url }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Leer más <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-search text-4xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-800 mb-2">No se encontraron resultados</h3>
                <p class="text-gray-600">
                    Intenta con otras palabras clave o revisa la documentación completa.
                </p>
                <div class="mt-4">
                    <a href="{{ route('help.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Ver toda la documentación
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($results->hasPages())
        <div class="mt-6">
            {{ $results->links() }}
        </div>
        @endif

        <!-- Sugerencias -->
        @if($suggestions->isNotEmpty())
        <div class="mt-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Sugerencias Relacionadas</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($suggestions as $suggestion)
                <a href="{{ $suggestion->url }}" class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow">
                    <div class="flex items-center">
                        <span class="w-8 h-8 rounded-full flex items-center justify-center" style="background-color: {{ $suggestion->color }}20">
                            <i class="{{ $suggestion->icon }} text-sm" style="color: {{ $suggestion->color }}"></i>
                        </span>
                        <span class="ml-3 text-gray-800">{{ $suggestion->title }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection 