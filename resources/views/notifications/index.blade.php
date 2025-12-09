@extends('layouts.dashboard')

@section('title', 'Notificaciones')
@section('page-title', 'Notificaciones')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900">Mis Notificaciones</h3>
        <button id="markAllAsRead" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-check-double mr-2"></i> Marcar todas como leídas
        </button>
    </div>
    
    <div class="divide-y divide-gray-200">
        @forelse($notifications as $notification)
        <div class="notification-item {{ $notification->read_at ? '' : 'unread' }} p-6 hover:bg-gray-50 transition-all duration-200" data-id="{{ $notification->id }}">
            <div class="flex items-start">
                <div class="flex-shrink-0 mr-4">
                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-900">{{ $notification->data['title'] ?? 'Notificación' }}</p>
                        <div class="flex items-center space-x-2">
                            @if(!$notification->read_at)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Nuevo
                            </span>
                            @endif
                            <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">
                        @if(($notification->data['type'] ?? '') === 'grade')
                            Tu {{ $notification->data['item_type'] ?? 'tarea' }} ha sido calificada con una nota de <strong class="text-indigo-600">{{ number_format($notification->data['grade'] ?? 0, 0) }}</strong>.
                            @if($notification->data['feedback'] ?? null)
                                <br><span class="text-xs italic text-gray-500">{{ $notification->data['feedback'] }}</span>
                            @endif
                        @else
                            {{ $notification->data['message'] ?? '' }}
                        @endif
                    </p>
                    @if(isset($notification->data['action_url']))
                    <div class="mt-3">
                        <a href="{{ $notification->data['action_url'] }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                            Ver detalles <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    @endif
                </div>
                <div class="flex items-center space-x-2 ml-4">
                    @if(!$notification->read_at)
                    <button onclick="markAsRead('{{ $notification->id }}')" class="text-gray-400 hover:text-gray-600" title="Marcar como leída">
                        <i class="fas fa-check"></i>
                    </button>
                    @endif
                    <button onclick="deleteNotification('{{ $notification->id }}')" class="text-red-400 hover:text-red-600" title="Eliminar">
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
            <h3 class="mt-2 text-sm font-medium text-gray-900">No tienes notificaciones</h3>
            <p class="mt-1 text-sm text-gray-500">
                Cuando tengas notificaciones, aparecerán aquí.
            </p>
        </div>
        @endforelse
    </div>
    
    @if($notifications->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notification = document.querySelector(`[data-id="${notificationId}"]`);
            notification.classList.remove('unread');
            notification.querySelector('.bg-red-100')?.remove();
            notification.querySelector('.fa-check')?.parentElement.remove();
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteNotification(notificationId) {
    if (confirm('¿Estás seguro de que quieres eliminar esta notificación?')) {
        fetch(`/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notification = document.querySelector(`[data-id="${notificationId}"]`);
                notification.remove();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

document.getElementById('markAllAsRead')?.addEventListener('click', function() {
    fetch('/notifications/mark-all-as-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.notification-item').forEach(item => {
                item.classList.remove('unread');
            });
            document.querySelectorAll('.bg-red-100').forEach(badge => {
                badge.remove();
            });
            document.querySelectorAll('.fa-check').forEach(icon => {
                icon.parentElement.remove();
            });
        }
    })
    .catch(error => console.error('Error:', error));
});
</script>
@endsection 