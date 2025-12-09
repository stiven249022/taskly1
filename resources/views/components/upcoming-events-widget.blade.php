@props(['events' => [], 'isTeacher' => false])

<div id="upcomingEventsWidget" class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <div class="flex items-center space-x-2">
            @if($isTeacher)
                <i class="fas fa-graduation-cap text-indigo-600 dark:text-indigo-400"></i>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tareas para Calificar</h3>
            @else
                <i class="fas fa-bell text-indigo-600 dark:text-indigo-400"></i>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Eventos Próximos</h3>
            @endif
            <span id="upcomingEventsCount" class="ml-2 px-2 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full text-xs font-medium">
                {{ count($events) }}
            </span>
        </div>
        <button onclick="refreshUpcomingEvents(event)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="Actualizar">
            <i class="fas fa-sync-alt"></i>
        </button>
    </div>
    
    <div id="upcomingEventsList" class="divide-y divide-gray-200 dark:divide-gray-700 max-h-96 overflow-y-auto">
        @if(count($events) > 0)
            @foreach($events as $event)
                <div class="upcoming-event-item p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 cursor-pointer" 
                     data-event-type="{{ $event['type'] }}" 
                     data-event-id="{{ $event['id'] }}"
                     data-url="{{ $event['url'] ?? '#' }}">
                    <div class="flex items-start space-x-3">
                        <!-- Icono según tipo -->
                        <div class="flex-shrink-0">
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
                        </div>
                        
                        <!-- Contenido -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                        {{ $event['title'] }}
                                    </h4>
                                    <div class="flex items-center space-x-3 mt-1">
                                        <!-- Fecha -->
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ \Carbon\Carbon::parse($event['date'])->format('d/m/Y') }}
                                        </span>
                                        
                                        <!-- Tiempo restante o información del estudiante -->
                                        @if(isset($event['is_for_grade']) && $event['is_for_grade'])
                                            <span class="text-xs font-medium text-red-600 dark:text-red-400">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                Pendiente de calificación
                                            </span>
                                            @if(isset($event['student_name']))
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    <i class="fas fa-user mr-1"></i>
                                                    {{ $event['student_name'] }}
                                                </span>
                                            @endif
                                        @else
                                            @php
                                                $daysUntilDue = max(0, min(999, (int)($event['days_until_due'] ?? 0)));
                                            @endphp
                                            <span class="text-xs font-medium 
                                                @if($daysUntilDue === 0) text-red-600 dark:text-red-400
                                                @elseif($daysUntilDue === 1) text-orange-600 dark:text-orange-400
                                                @else text-blue-600 dark:text-blue-400 @endif">
                                                @if($daysUntilDue === 0)
                                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                                    ¡Vence HOY!
                                                @elseif($daysUntilDue === 1)
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Vence mañana
                                                @else
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $daysUntilDue }} días restantes
                                                @endif
                                            </span>
                                        @endif
                                        
                                        <!-- Curso -->
                                        @if(isset($event['course']) && $event['course'])
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                <i class="fas fa-book mr-1"></i>
                                                {{ $event['course'] }}
                                            </span>
                                        @endif
                                        
                                        <!-- Prioridad (solo para tareas) -->
                                        @if(isset($event['priority']) && $event['type'] === 'task')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                @if($event['priority'] === 'high') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @elseif($event['priority'] === 'medium') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif">
                                                {{ ucfirst($event['priority']) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="p-8 text-center">
                @if($isTeacher)
                    <i class="fas fa-check-circle text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                    <p class="text-sm text-gray-500 dark:text-gray-400">No hay tareas para calificar</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Todas las tareas han sido calificadas</p>
                @else
                    <i class="fas fa-calendar-check text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                    <p class="text-sm text-gray-500 dark:text-gray-400">No hay eventos próximos</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Las notificaciones aparecerán aquí cuando tengas eventos próximos</p>
                @endif
            </div>
        @endif
    </div>
    
    @if(count($events) > 0)
        <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 text-center">
            @if($isTeacher)
                <a href="{{ route('teacher.student-tasks') }}" 
                   class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                    Ver todas las tareas para calificar
                </a>
            @else
                <a href="{{ route('notifications.upcoming-events.view') }}" 
                   class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                    Ver todos los eventos próximos
                </a>
            @endif
        </div>
    @endif
</div>

<script>
// Agregar listeners de clic a los eventos
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.upcoming-event-item').forEach(item => {
        item.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            if (url && url !== '#') {
                window.location.href = url;
            }
        });
    });
});

// Función para refrescar eventos próximos
function refreshUpcomingEvents(event) {
    const button = event ? event.target.closest('button') : document.querySelector('#upcomingEventsWidget button');
    if (button) {
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
    }
    
    const isTeacher = {{ $isTeacher ? 'true' : 'false' }};
    
    if (isTeacher) {
        // Para profesores, recargar la página para obtener datos actualizados
        location.reload();
        return;
    }
    
    // Para estudiantes, usar el endpoint de eventos próximos
    fetch('{{ route("notifications.upcoming-events") }}', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateUpcomingEventsList(data.events || []);
            if (typeof showNotification !== 'undefined') {
                showNotification(`Eventos actualizados: ${data.count || (data.events || []).length} eventos próximos`, 'success');
            }
        }
    })
    .catch(error => {
        console.error('Error al actualizar eventos:', error);
        if (typeof showNotification !== 'undefined') {
            showNotification('Error al actualizar eventos', 'error');
        }
    })
    .finally(() => {
        if (button) {
            button.innerHTML = '<i class="fas fa-sync-alt"></i>';
            button.disabled = false;
        }
    });
}

// Función para actualizar la lista de eventos
function updateUpcomingEventsList(events) {
    const listContainer = document.getElementById('upcomingEventsList');
    const countBadge = document.getElementById('upcomingEventsCount');
    
    if (!listContainer) return;
    
    countBadge.textContent = events.length;
    
    if (events.length === 0) {
        listContainer.innerHTML = `
            <div class="p-8 text-center">
                <i class="fas fa-calendar-check text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                <p class="text-sm text-gray-500 dark:text-gray-400">No hay eventos próximos</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Las notificaciones aparecerán aquí cuando tengas eventos próximos</p>
            </div>
        `;
        return;
    }
    
    let html = '';
    events.forEach(event => {
        const date = new Date(event.date);
        const formattedDate = date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
        
        let iconClass = 'fas fa-bell';
        let iconBgClass = 'bg-amber-100 dark:bg-amber-900';
        let iconColorClass = 'text-amber-600 dark:text-amber-400';
        
        if (event.type === 'task') {
            iconClass = 'fas fa-tasks';
            iconBgClass = 'bg-blue-100 dark:bg-blue-900';
            iconColorClass = 'text-blue-600 dark:text-blue-400';
        } else if (event.type === 'project') {
            iconClass = 'fas fa-project-diagram';
            iconBgClass = 'bg-green-100 dark:bg-green-900';
            iconColorClass = 'text-green-600 dark:text-green-400';
        }
        
        let timeClass = 'text-blue-600 dark:text-blue-400';
        // Validar que days_until_due sea un número válido
        const daysUntilDue = Math.max(0, Math.min(999, parseInt(event.days_until_due) || 0));
        
        let timeText = `${daysUntilDue} días restantes`;
        let timeIcon = 'fas fa-clock';
        
        if (daysUntilDue === 0) {
            timeClass = 'text-red-600 dark:text-red-400';
            timeText = '¡Vence HOY!';
            timeIcon = 'fas fa-exclamation-circle';
        } else if (daysUntilDue === 1) {
            timeClass = 'text-orange-600 dark:text-orange-400';
            timeText = 'Vence mañana';
            timeIcon = 'fas fa-clock';
        }
        
        let priorityBadge = '';
        if (event.priority && event.type === 'task') {
            let priorityClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
            if (event.priority === 'high') {
                priorityClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
            } else if (event.priority === 'medium') {
                priorityClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
            }
            priorityBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${priorityClass}">${event.priority.charAt(0).toUpperCase() + event.priority.slice(1)}</span>`;
        }
        
        html += `
            <div class="upcoming-event-item p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 cursor-pointer" 
                 data-event-type="${event.type}" 
                 data-event-id="${event.id}"
                 data-url="${event.url || '#'}">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full ${iconBgClass} flex items-center justify-center">
                            <i class="${iconClass} ${iconColorClass}"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                            ${event.title}
                        </h4>
                        <div class="flex items-center space-x-3 mt-1 flex-wrap">
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-calendar mr-1"></i>
                                ${formattedDate}
                            </span>
                            ${event.is_for_grade ? `
                                <span class="text-xs font-medium text-red-600 dark:text-red-400">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    Pendiente de calificación
                                </span>
                                ${event.student_name ? `<span class="text-xs text-gray-500 dark:text-gray-400"><i class="fas fa-user mr-1"></i>${event.student_name}</span>` : ''}
                            ` : `
                                <span class="text-xs font-medium ${timeClass}">
                                    <i class="${timeIcon} mr-1"></i>
                                    ${timeText}
                                </span>
                            `}
                            ${event.course ? `<span class="text-xs text-gray-500 dark:text-gray-400"><i class="fas fa-book mr-1"></i>${event.course}</span>` : ''}
                            ${priorityBadge}
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    listContainer.innerHTML = html;
    
    // Re-agregar listeners
    document.querySelectorAll('.upcoming-event-item').forEach(item => {
        item.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            if (url && url !== '#') {
                window.location.href = url;
            }
        });
    });
}

// Actualizar automáticamente cada 5 minutos (solo para estudiantes)
const isTeacher = {{ $isTeacher ? 'true' : 'false' }};
if (!isTeacher) {
    setInterval(function() {
        refreshUpcomingEvents(null);
    }, 300000); // 5 minutos
}
</script>
