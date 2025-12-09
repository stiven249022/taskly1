@extends('layouts.dashboard')

@section('title', 'Recordatorios')
@section('page-title', 'Recordatorios')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total recordatorios</p>
                <p class="text-2xl font-bold mt-1">{{ $reminders->count() }}</p>
            </div>
            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                <i class="fas fa-bell"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Pendientes</p>
                <p class="text-2xl font-bold mt-1">{{ $reminders->where('status', 'pending')->count() }}</p>
            </div>
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Completados</p>
                <p class="text-2xl font-bold mt-1">{{ $reminders->where('status', 'completed')->count() }}</p>
            </div>
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Vencidos</p>
                <p class="text-2xl font-bold mt-1">{{ $reminders->where('status', 'pending')->where('due_date', '<', now())->count() }}</p>
            </div>
            <div class="p-3 rounded-full bg-red-100 text-red-600">
                <i class="fas fa-exclamation-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Reminders Section -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900">Mis Recordatorios</h3>
        <a href="{{ route('reminders.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i> Nuevo Recordatorio
        </a>
    </div>
    
    <!-- Filters -->
    <div class="px-6 py-4 border-b border-gray-200">
        <form action="{{ route('reminders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Materia</label>
                <select name="course" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                    <option value="">Todas las materias</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                    <option value="">Todos los estados</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                <select name="priority" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                    <option value="">Todas las prioridades</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Alta</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Media</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Baja</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-filter mr-2"></i> Aplicar Filtros
                </button>
                <a href="{{ route('reminders.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-refresh mr-2"></i> Resetear
                </a>
            </div>
        </form>
    </div>

    <!-- Reminders List -->
    <div class="divide-y divide-gray-200">
        @forelse($reminders as $reminder)
        <div class="task-item p-6 hover:bg-gray-50 transition-all duration-200 {{ $reminder->priority }}_priority">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <input type="checkbox" 
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                           {{ $reminder->status === 'completed' ? 'checked' : '' }}
                           onchange="toggleReminderStatus({{ $reminder->id }}, this.checked)">
                    <div class="flex-1">
                        <h4 class="text-lg font-medium text-gray-900 {{ $reminder->status === 'completed' ? 'line-through text-gray-500' : '' }}">
                            {{ $reminder->title }}
                        </h4>
                        <p class="text-sm text-gray-600 mt-1">{{ $reminder->description }}</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $reminder->priority === 'high' ? 'bg-red-100 text-red-800' : 
                                   ($reminder->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($reminder->priority) }}
                            </span>
                            @if($reminder->course)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $reminder->course->name }}
                            </span>
                            @endif
                            @if($reminder->due_date)
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                Vence: {{ $reminder->due_date->format('d/m/Y') }}
                            </span>
                            @else
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                Sin fecha límite
                            </span>
                            @endif
        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('reminders.edit', $reminder) }}" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button onclick="deleteReminder({{ $reminder->id }})" class="text-red-400 hover:text-red-600">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <div class="mx-auto h-12 w-12 text-gray-400">
                <i class="fas fa-bell text-4xl"></i>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay recordatorios</h3>
            <p class="mt-1 text-sm text-gray-500">
                Comienza creando tu primer recordatorio.
            </p>
            <div class="mt-6">
                <a href="{{ route('reminders.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <i class="fas fa-plus mr-2"></i> Nuevo Recordatorio
                </a>
        </div>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($reminders->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $reminders->links() }}
    </div>
    @endif
    </div>

    <script>
function toggleReminderStatus(reminderId, completed) {
    fetch(`/reminders/${reminderId}/toggle-status`, {
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

function deleteReminder(reminderId) {
    if (confirm('¿Estás seguro de que quieres eliminar este recordatorio?')) {
        fetch(`/reminders/${reminderId}`, {
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