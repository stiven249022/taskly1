@extends('layouts.admin')

@section('title', 'Logs del Sistema')
@section('page-title', 'Logs del Sistema')

@section('content')
<!-- System Logs -->
<div class="mb-8">
    <div class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900 dark:to-red-800 rounded-lg shadow-lg p-6 text-red-800 dark:text-red-100">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">Logs del Sistema</h1>
                <p class="text-red-700 dark:text-red-200">Monitorea la actividad y errores del sistema en tiempo real</p>
            </div>
            <div class="text-right">
                <i class="fas fa-file-alt text-4xl text-red-300 dark:text-red-400"></i>
            </div>
        </div>
    </div>
</div>

<!-- Log Filters -->
<form action="{{ route('admin.logs') }}" method="GET" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
    <div class="flex flex-wrap items-center gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nivel de Log</label>
            <select name="level" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="all" {{ ($filters['level'] ?? 'all') === 'all' ? 'selected' : '' }}>Todos</option>
                <option value="error" {{ ($filters['level'] ?? '') === 'error' ? 'selected' : '' }}>Error</option>
                <option value="warning" {{ ($filters['level'] ?? '') === 'warning' ? 'selected' : '' }}>Advertencia</option>
                <option value="info" {{ ($filters['level'] ?? '') === 'info' ? 'selected' : '' }}>Información</option>
                <option value="debug" {{ ($filters['level'] ?? '') === 'debug' ? 'selected' : '' }}>Debug</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha</label>
            <input type="date" name="date" value="{{ $filters['date'] ?? '' }}" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Buscar en logs..." class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-search mr-2"></i> Filtrar
            </button>
        </div>
    </div>
</form>

<!-- Logs Table -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Registros del Sistema</h3>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.logs.download') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300" title="Descargar logs">
                <i class="fas fa-download"></i>
            </a>
            <form action="{{ route('admin.logs.clear') }}" method="POST" onsubmit="return confirm('¿Seguro que deseas limpiar los logs?');">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300" title="Limpiar logs">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            <a href="{{ route('admin.logs.refresh', request()->only(['level','date','q'])) }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300" title="Refrescar">
                <i class="fas fa-sync"></i>
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nivel
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Fecha/Hora
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Mensaje
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Usuario
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        IP
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($entries as $e)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php($lvl = strtolower($e['level']))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $lvl === 'error' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                               ($lvl === 'warning' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                               ($lvl === 'debug' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' :
                               'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200')) }}">
                            {{ strtoupper($e['level']) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $e['timestamp'] ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                        {{ $e['message'] ?? $e['raw'] }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">-</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">-</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                        No hay registros que coincidan con los filtros.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                Mostrando {{ $entries->firstItem() }}-{{ $entries->lastItem() }} de {{ $entries->total() }} registros
            </div>
            <div class="flex items-center space-x-2">
                {{ $entries->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
