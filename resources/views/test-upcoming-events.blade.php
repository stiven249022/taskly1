@extends('layouts.dashboard')

@section('title', 'Prueba - Eventos Próximos')
@section('page-title', 'Prueba - Sistema de Notificaciones de Eventos Próximos')

@section('content')
<div class="space-y-6">
    <!-- Información General -->
    <div class="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
            📊 Estado del Sistema
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                <div class="text-sm text-blue-600 dark:text-blue-400 font-medium">Tareas Próximas</div>
                <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $tasksCount }}</div>
                <div class="text-xs text-blue-500 dark:text-blue-400 mt-1">Vencen en los próximos 3 días</div>
            </div>
            
            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                <div class="text-sm text-green-600 dark:text-green-400 font-medium">Proyectos Próximos</div>
                <div class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $projectsCount }}</div>
                <div class="text-xs text-green-500 dark:text-green-400 mt-1">Vencen en los próximos 7 días</div>
            </div>
            
            <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-lg">
                <div class="text-sm text-amber-600 dark:text-amber-400 font-medium">Recordatorios Próximos</div>
                <div class="text-2xl font-bold text-amber-700 dark:text-amber-300">{{ $remindersCount }}</div>
                <div class="text-xs text-amber-500 dark:text-amber-400 mt-1">Vencen en los próximos 2 días</div>
            </div>
        </div>
    </div>

    <!-- Eventos Próximos -->
    <div class="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                📅 Eventos Próximos Encontrados
            </h2>
            <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full text-sm font-medium">
                {{ $count }} eventos
            </span>
        </div>

        @if(count($events) > 0)
            <div class="space-y-3">
                @foreach($events as $event)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-3 flex-1">
                                @if($event['type'] === 'task')
                                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                        <i class="fas fa-tasks text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                @elseif($event['type'] === 'project')
                                    <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                        <i class="fas fa-project-diagram text-green-600 dark:text-green-400"></i>
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900 flex items-center justify-center">
                                        <i class="fas fa-bell text-amber-600 dark:text-amber-400"></i>
                                    </div>
                                @endif
                                
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                        {{ $event['title'] }}
                                    </h3>
                                    <div class="flex items-center space-x-4 mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        <span>
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ \Carbon\Carbon::parse($event['date'])->format('d/m/Y H:i') }}
                                        </span>
                                        <span>
                                            <i class="fas fa-clock mr-1"></i>
                                            @if($event['days_until_due'] === 0)
                                                <span class="text-red-600 dark:text-red-400 font-medium">Vence HOY</span>
                                            @elseif($event['days_until_due'] === 1)
                                                <span class="text-orange-600 dark:text-orange-400 font-medium">Vence mañana</span>
                                            @else
                                                Vence en {{ $event['days_until_due'] }} días
                                            @endif
                                        </span>
                                        @if(isset($event['course']))
                                            <span>
                                                <i class="fas fa-book mr-1"></i>
                                                {{ $event['course'] }}
                                            </span>
                                        @endif
                                        @if(isset($event['priority']))
                                            <span>
                                                <i class="fas fa-flag mr-1"></i>
                                                {{ ucfirst($event['priority']) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <a href="{{ $event['url'] }}" 
                               class="ml-4 px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-sm transition-colors">
                                Ver
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-calendar-times text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <p class="text-gray-500 dark:text-gray-400">No hay eventos próximos en este momento</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">
                    Crea tareas, proyectos o recordatorios con fechas próximas para ver las notificaciones
                </p>
            </div>
        @endif
    </div>

    <!-- Notificaciones Creadas -->
    <div class="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
            🔔 Notificaciones Creadas
        </h2>
        
        @php
            $notifications = auth()->user()->notifications()
                ->where('type', 'App\Notifications\UpcomingEventNotification')
                ->latest()
                ->take(10)
                ->get();
        @endphp

        @if($notifications->count() > 0)
            <div class="space-y-3">
                @foreach($notifications as $notification)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 {{ $notification->read_at ? '' : 'bg-blue-50 dark:bg-blue-900/20' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-3 flex-1">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white" 
                                     style="background-color: {{ $notification->data['color'] ?? '#6366F1' }};">
                                    <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }}"></i>
                                </div>
                                
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                        {{ $notification->data['title'] ?? 'Notificación' }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>
                                    <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        <span>
                                            <i class="fas fa-tag mr-1"></i>
                                            {{ ucfirst($notification->data['event_type'] ?? 'evento') }}
                                        </span>
                                        <span>
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                        @if($notification->read_at)
                                            <span class="text-green-600 dark:text-green-400">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Leída
                                            </span>
                                        @else
                                            <span class="text-blue-600 dark:text-blue-400">
                                                <i class="fas fa-circle mr-1"></i>
                                                No leída
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-bell-slash text-3xl text-gray-300 dark:text-gray-600 mb-3"></i>
                <p class="text-gray-500 dark:text-gray-400">No hay notificaciones creadas aún</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">
                    Las notificaciones se crean automáticamente cuando hay eventos próximos
                </p>
            </div>
        @endif
    </div>

    <!-- Acciones de Prueba -->
    <div class="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
            🧪 Acciones de Prueba
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <button onclick="loadUpcomingEvents()" 
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                <i class="fas fa-sync mr-2"></i>
                Recargar Eventos Próximos
            </button>
            
            <button onclick="updateNotificationBadge()" 
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                <i class="fas fa-bell mr-2"></i>
                Actualizar Badge de Notificaciones
            </button>
            
            <a href="{{ route('notifications.index') }}" 
               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-center">
                <i class="fas fa-list mr-2"></i>
                Ver Todas las Notificaciones
            </a>
            
            <a href="{{ route('calendar.index') }}" 
               class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors text-center">
                <i class="fas fa-calendar mr-2"></i>
                Ver Calendario
            </a>
        </div>
    </div>

    <!-- Información de Debug -->
    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
            🔍 Información de Debug
        </h2>
        
        <div class="space-y-2 text-sm font-mono">
            <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Total de notificaciones:</span>
                <span class="text-gray-900 dark:text-white font-bold">{{ auth()->user()->notifications()->count() }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Notificaciones no leídas:</span>
                <span class="text-gray-900 dark:text-white font-bold">{{ auth()->user()->unreadNotifications()->count() }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Notificaciones de eventos próximos:</span>
                <span class="text-gray-900 dark:text-white font-bold">
                    {{ auth()->user()->notifications()->where('type', 'App\Notifications\UpcomingEventNotification')->count() }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">Ruta de eventos próximos:</span>
                <span class="text-gray-900 dark:text-white">{{ route('notifications.upcoming-events') }}</span>
            </div>
        </div>
    </div>
</div>

<script>
function loadUpcomingEvents() {
    fetch('{{ route("notifications.upcoming-events") }}', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Eventos próximos cargados:', data);
        if (typeof showNotification !== 'undefined') {
            showNotification(`Se encontraron ${data.count} eventos próximos`, 'success');
        } else {
            alert(`Se encontraron ${data.count} eventos próximos`);
        }
        setTimeout(() => location.reload(), 1500);
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof showNotification !== 'undefined') {
            showNotification('Error al cargar eventos próximos', 'error');
        } else {
            alert('Error al cargar eventos próximos');
        }
    });
}

function updateNotificationBadge() {
    fetch('{{ route("notifications.unread") }}', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Notificaciones actualizadas:', data);
        if (typeof showNotification !== 'undefined') {
            showNotification(`Tienes ${data.count} notificaciones no leídas`, 'info');
        } else {
            alert(`Tienes ${data.count} notificaciones no leídas`);
        }
        setTimeout(() => location.reload(), 1500);
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof showNotification !== 'undefined') {
            showNotification('Error al actualizar notificaciones', 'error');
        } else {
            alert('Error al actualizar notificaciones');
        }
    });
}
</script>
@endsection


