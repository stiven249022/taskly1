@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado y Botón de Nuevo Recordatorio -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Recordatorios</h1>
        <a href="{{ route('reminders.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Nuevo Recordatorio
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
        <form action="{{ route('reminders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Curso</label>
                <select name="course" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    <option value="">Todos los cursos</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                <select name="status" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    <option value="">Todos los estados</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg">
                    <i class="fas fa-filter mr-2"></i>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $reminders->count() }}</p>
                </div>
                <span class="text-yellow-500 dark:text-yellow-400">
                    <i class="fas fa-bell text-2xl"></i>
                </span>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pendientes</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $reminders->where('status', 'pending')->count() }}</p>
                </div>
                <span class="text-blue-500 dark:text-blue-400">
                    <i class="fas fa-clock text-2xl"></i>
                </span>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Completados</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $reminders->where('status', 'completed')->count() }}</p>
                </div>
                <span class="text-green-500 dark:text-green-400">
                    <i class="fas fa-check-circle text-2xl"></i>
                </span>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Vencidos</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $reminders->where('status', 'pending')->where('due_date', '<', now())->count() }}</p>
                </div>
                <span class="text-red-500 dark:text-red-400">
                    <i class="fas fa-exclamation-circle text-2xl"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Lista de Recordatorios -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Título</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Curso</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Prioridad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($reminders as $reminder)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $reminder->title }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($reminder->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-4 w-4 rounded-full mr-2" style="background-color: {{ $reminder->course->color }}"></div>
                                <div class="text-sm text-gray-900 dark:text-white">{{ $reminder->course->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $reminder->priority === 'high' ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' : 
                                   ($reminder->priority === 'medium' ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' : 
                                    'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200') }}">
                                {{ ucfirst($reminder->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($reminder->due_date)
                            <div class="text-sm text-gray-900 dark:text-white">{{ $reminder->due_date->format('d/m/Y') }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $reminder->due_date->diffForHumans() }}</div>
                            @else
                            <div class="text-sm text-gray-900 dark:text-white">Sin fecha</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">-</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $reminder->status === 'completed' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' }}">
                                {{ $reminder->status === 'completed' ? 'Completado' : 'Pendiente' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button 
                                    data-toggle="{{ route('reminders.toggle-status', $reminder) }}"
                                    data-item-type="recordatorio"
                                    class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 transition-colors duration-200"
                                    title="{{ $reminder->status === 'completed' ? 'Marcar como pendiente' : 'Marcar como completado' }}">
                                    <i class="fas {{ $reminder->status === 'completed' ? 'fa-undo' : 'fa-check' }}"></i>
                                </button>
                                <a href="{{ route('reminders.edit', $reminder) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition-colors duration-200" title="Editar recordatorio">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button 
                                    onclick="confirmDelete('{{ route('reminders.destroy', $reminder) }}', '{{ $reminder->title }}', 'recordatorio')"
                                    class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors duration-200"
                                    title="Eliminar recordatorio">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No hay recordatorios disponibles
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $reminders->links() }}
    </div>
</div>
@endsection 