@extends('layouts.dashboard')

@section('title', 'Calendario')
@section('page-title', 'Calendario')

@section('content')
    <style>
.today {
    background-color: #fef3c7 !important;
    font-weight: bold;
}

.fc-event {
    font-size: 0.75rem;
    padding: 2px 4px;
    margin-bottom: 2px;
    border-radius: 3px;
    cursor: pointer;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.fc-event:hover {
    opacity: 0.8;
}

.task {
    background-color: #3b82f6 !important;
}

.project {
    background-color: #10b981 !important;
}

.reminder {
    background-color: #f59e0b !important;
}

.calendar-day {
    min-height: 100px;
    transition: background-color 0.2s;
}

.calendar-day:hover {
    background-color: #f9fafb;
}

/* Day view styles */
.day-view-slot {
    min-height: 60px;
    border-bottom: 1px solid #f3f4f6;
}

.day-view-slot:hover {
    background-color: #f9fafb;
}

.day-view-time {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

/* Week view styles */
.week-view-day {
    min-height: 120px;
}

.week-view-day-header {
    text-align: center;
    padding: 8px;
    border-bottom: 1px solid #e5e7eb;
}

.week-view-day-name {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    font-weight: 500;
}

.week-view-day-number {
    font-size: 1.125rem;
    font-weight: bold;
    color: #111827;
}

/* Month view styles - Template inspired */
.month-view-day {
    min-height: 120px;
    border: 1px solid #e5e7eb;
    background-color: white;
    transition: background-color 0.15s ease;
    position: relative;
    display: flex;
    flex-direction: column;
}

.month-view-day:hover {
    background-color: #f3f4f6;
}

.month-view-day.other-month {
    background-color: #f9fafb;
    color: #9ca3af;
}

.month-view-day.other-month .month-view-day-number {
    color: #9ca3af !important;
}

.month-view-day.today {
    background-color: #e0e7ff;
    border: 2px solid #4f46e5;
}

.month-view-day.today .month-view-day-number {
    color: #1e40af !important;
    font-weight: bold;
}

.month-view-day.selected {
    background-color: #10b981;
    border-color: #10b981;
    color: white;
}

.month-view-day.selected .month-view-day-number {
    color: white !important;
    font-weight: 600;
}

.month-view-day-number {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    padding: 8px;
    text-align: right;
    flex-shrink: 0;
}

.month-view-day-number:hover {
    color: #4f46e5;
}

.month-view-events {
    flex: 1;
    padding: 0 8px 8px 8px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: stretch;
}

/* Event styles for template */
.fc-event.task {
    background-color: #3b82f6 !important;
}

.fc-event.project {
    background-color: #10b981 !important;
}

.fc-event.reminder {
    background-color: #f59e0b !important;
}

/* Calendar container styles */
#calendarView {
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

/* Smooth transitions for all calendar elements */
.calendar-day, .month-view-day, .week-view-day {
    transition: all 0.2s ease;
}

/* Hover effects for event indicators */
.event-indicator:hover {
    transform: scale(1.3);
}

/* Improve event display in month view */
.month-view-events .fc-event {
    margin-bottom: 2px;
    font-size: 0.75rem;
    padding: 4px 6px;
    border-radius: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-weight: 500;
}

.month-view-events .fc-event:hover {
    opacity: 0.8;
    transform: scale(1.02);
}

.month-view-events .fc-event.more {
    font-size: 0.7rem;
    padding: 2px 4px;
    font-weight: normal;
}
</style>

                <!-- Calendar Navigation -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div class="flex items-center mb-4 md:mb-0">
                        <button id="prevMonth" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 mr-2">
                            <i class="fas fa-chevron-left text-gray-600 dark:text-gray-400"></i>
                        </button>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white" id="currentMonthYear">Mayo 2023</h3>
                        <button id="nextMonth" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 ml-2">
                            <i class="fas fa-chevron-right text-gray-600 dark:text-gray-400"></i>
                        </button>
                        <button id="todayBtn" class="ml-4 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Hoy
                        </button>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button id="dayView" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Día
                        </button>
                        <button id="weekView" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Semana
                        </button>
                        <button id="monthView" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Mes
                        </button>
                        <button id="addEventBtn" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-plus mr-1"></i> Nuevo Evento
                        </button>
                    </div>
                </div>

                <!-- Calendar View -->
                <div id="calendarView" class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <!-- Weekday Headers -->
                    <div class="grid grid-cols-7 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <div class="py-3 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Domingo</div>
                        <div class="py-3 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Lunes</div>
                        <div class="py-3 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Martes</div>
                        <div class="py-3 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Miércoles</div>
                        <div class="py-3 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Jueves</div>
                        <div class="py-3 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Viernes</div>
                        <div class="py-3 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Sábado</div>
                    </div>

                    <!-- Calendar Days -->
                    <div class="grid grid-cols-7 auto-rows-fr" style="min-height: 600px;">
        <!-- Calendar days will be populated by JavaScript -->
                        </div>
                        </div>
                        
<!-- Event Details Modal -->
<div id="eventDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border border-gray-300 dark:border-gray-600 w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Detalles del Evento</h3>
            <div id="eventDetailsContent">
                <!-- Event details will be populated here -->
                            </div>
            <div class="flex justify-end space-x-3 mt-4">
                <button type="button" id="closeEventDetailsBtn" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                    Cerrar
                </button>
                        </div>
                        </div>
                        </div>
                        </div>
                        
<!-- Add Event Modal -->
<div id="addEventModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border border-gray-300 dark:border-gray-600 w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Nuevo Evento</h3>
            <form id="addEventForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título</label>
                    <input type="text" id="eventTitle" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                        </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                    <textarea id="eventDescription" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                        </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha Inicio</label>
                        <input type="datetime-local" id="eventStart" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                        </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha Fin</label>
                        <input type="datetime-local" id="eventEnd" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                        <select id="eventType" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="task">Tarea</option>
                            <option value="project">Proyecto</option>
                            <option value="reminder">Recordatorio</option>
                        </select>
                        </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Materia</label>
                        <select id="eventCourse" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">Sin materia</option>
                            @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                                    </div>
                                        </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelEventBtn" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500">
                        Cancelar
                                        </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Crear Evento
                                        </button>
                                    </div>
            </form>
                                </div>
                        </div>
                    </div>

<script>
// Calendar functionality
let currentDate = new Date();
let events = [];
let currentView = 'month'; // 'month', 'week', 'day'

// Initialize calendar
document.addEventListener('DOMContentLoaded', function() {
    loadEvents();
    renderCalendar();
    updateViewButtons();
    
    // Event listeners
    document.getElementById('prevMonth').addEventListener('click', () => {
        switch(currentView) {
            case 'day':
                currentDate.setDate(currentDate.getDate() - 1);
                break;
            case 'week':
                currentDate.setDate(currentDate.getDate() - 7);
                break;
            case 'month':
                currentDate.setMonth(currentDate.getMonth() - 1);
                break;
        }
        renderCalendar();
    });
    
    document.getElementById('nextMonth').addEventListener('click', () => {
        switch(currentView) {
            case 'day':
                currentDate.setDate(currentDate.getDate() + 1);
                break;
            case 'week':
                currentDate.setDate(currentDate.getDate() + 7);
                break;
            case 'month':
                currentDate.setMonth(currentDate.getMonth() + 1);
                break;
        }
        renderCalendar();
    });
    
    document.getElementById('todayBtn').addEventListener('click', () => {
        currentDate = new Date();
        renderCalendar();
    });
    
    // View buttons
    document.getElementById('dayView').addEventListener('click', () => {
        currentView = 'day';
        updateViewButtons();
        renderCalendar();
    });
    
    document.getElementById('weekView').addEventListener('click', () => {
        currentView = 'week';
        updateViewButtons();
        renderCalendar();
    });
    
    document.getElementById('monthView').addEventListener('click', () => {
        currentView = 'month';
        updateViewButtons();
        renderCalendar();
    });
    
    document.getElementById('addEventBtn').addEventListener('click', () => {
        // Establecer valores por defecto
        const now = new Date();
        const tomorrow = new Date(now);
        tomorrow.setDate(tomorrow.getDate() + 1);
        tomorrow.setHours(9, 0, 0, 0);
        
        const endTime = new Date(tomorrow);
        endTime.setHours(10, 0, 0, 0);
        
        document.getElementById('eventStart').value = tomorrow.toISOString().slice(0, 16);
        document.getElementById('eventEnd').value = endTime.toISOString().slice(0, 16);
        
        document.getElementById('addEventModal').classList.remove('hidden');
    });
    
    document.getElementById('cancelEventBtn').addEventListener('click', () => {
        document.getElementById('addEventModal').classList.add('hidden');
    });
    
    document.getElementById('addEventForm').addEventListener('submit', function(e) {
        e.preventDefault();
        createEvent();
    });
    
    document.getElementById('closeEventDetailsBtn').addEventListener('click', () => {
        document.getElementById('eventDetailsModal').classList.add('hidden');
    });
    
    // Close modals when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target.id === 'addEventModal') {
            document.getElementById('addEventModal').classList.add('hidden');
        }
        if (e.target.id === 'eventDetailsModal') {
            document.getElementById('eventDetailsModal').classList.add('hidden');
        }
    });
});

function loadEvents() {
    fetch('/calendar/events')
        .then(response => response.json())
        .then(data => {
            events = data;
            renderCalendar();
        })
        .catch(error => console.error('Error loading events:', error));
}

function updateViewButtons() {
    // Reset all buttons
    document.getElementById('dayView').className = 'px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700';
    document.getElementById('weekView').className = 'px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700';
    document.getElementById('monthView').className = 'px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700';
    
    // Highlight current view
    switch(currentView) {
        case 'day':
            document.getElementById('dayView').className = 'px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500';
            break;
        case 'week':
            document.getElementById('weekView').className = 'px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500';
            break;
        case 'month':
            document.getElementById('monthView').className = 'px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500';
            break;
    }
}

function renderCalendar() {
    const calendarDays = document.querySelector('#calendarView .grid');
    calendarDays.innerHTML = '';
    
    switch(currentView) {
        case 'day':
            renderDayView();
            break;
        case 'week':
            renderWeekView();
            break;
        case 'month':
            renderMonthView();
            break;
    }
}

function renderMonthView() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    document.getElementById('currentMonthYear').textContent = 
        new Date(year, month).toLocaleDateString('es-ES', { month: 'long', year: 'numeric' });
    
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startDate = new Date(firstDay);
    startDate.setDate(startDate.getDate() - firstDay.getDay());
    
    const calendarDays = document.querySelector('#calendarView .grid');
    calendarDays.className = 'grid grid-cols-7';
    calendarDays.style.minHeight = '600px';
    
    for (let i = 0; i < 42; i++) {
        const date = new Date(startDate);
        date.setDate(startDate.getDate() + i);
        
        const dayElement = document.createElement('div');
        dayElement.className = 'month-view-day';
        
        if (date.getMonth() !== month) {
            dayElement.classList.add('other-month');
        }
        
        if (date.toDateString() === new Date().toDateString()) {
            dayElement.classList.add('today');
        }
        
        const dayEvents = getEventsForDate(date);
        const eventTypes = [...new Set(dayEvents.map(event => event.type))];
        
        dayElement.innerHTML = `
            <div class="month-view-day-number cursor-pointer" 
                 onclick="goToDay('${date.toISOString().split('T')[0]}')">
                ${date.getDate()}
                                    </div>
            <div class="month-view-events">
                ${dayEvents.length > 0 ? `
                    <div class="mt-1 space-y-1">
                        ${dayEvents.slice(0, 3).map(event => `
                            <div class="fc-event ${event.type} cursor-pointer" 
                                 style="background-color: ${event.color}; color: white;"
                                 onclick="showEventDetails('${event.id}', '${event.type}')"
                                 title="${event.title}">
                                ${event.title}
                            </div>
                        `).join('')}
                        ${dayEvents.length > 3 ? `
                            <div class="fc-event more cursor-pointer bg-gray-100 text-gray-800" 
                                 onclick="showDayEvents('${date.toISOString().split('T')[0]}')"
                                 title="${dayEvents.length - 3} eventos más">
                                +${dayEvents.length - 3} más
                        </div>
                        ` : ''}
                    </div>
                ` : ''}
                </div>
        `;
        
        calendarDays.appendChild(dayElement);
    }
}

function renderWeekView() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const day = currentDate.getDate();
    
    // Get the start of the week (Sunday)
    const weekStart = new Date(currentDate);
    weekStart.setDate(weekStart.getDate() - weekStart.getDay());
    
    document.getElementById('currentMonthYear').textContent = 
        `Semana del ${weekStart.toLocaleDateString('es-ES', { day: 'numeric', month: 'long' })} al ${new Date(weekStart.getTime() + 6 * 24 * 60 * 60 * 1000).toLocaleDateString('es-ES', { day: 'numeric', month: 'long', year: 'numeric' })}`;
    
    const calendarDays = document.querySelector('#calendarView .grid');
    calendarDays.className = 'grid grid-cols-7 auto-rows-fr';
    calendarDays.style.minHeight = '400px';
    
    const weekdays = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    
    for (let i = 0; i < 7; i++) {
        const date = new Date(weekStart);
        date.setDate(weekStart.getDate() + i);
        
        const dayElement = document.createElement('div');
        dayElement.className = 'border border-gray-100 p-2 calendar-day';
        
        if (date.toDateString() === new Date().toDateString()) {
            dayElement.classList.add('today');
        }
        
        dayElement.innerHTML = `
            <div class="week-view-day-header cursor-pointer hover:bg-gray-50" 
                 onclick="goToDay('${date.toISOString().split('T')[0]}')">
                <div class="week-view-day-name">${weekdays[i]}</div>
                <div class="week-view-day-number">${date.getDate()}</div>
                        </div>
            <div class="events-container p-2">
                ${getEventsForDate(date).map(event => `
                    <div class="fc-event ${event.type} mb-1 p-1 text-xs rounded cursor-pointer" 
                         style="background-color: ${event.color}; color: white;"
                         onclick="showEventDetails('${event.id}', '${event.type}')"
                         title="${event.title}">
                        ${event.title}
                        </div>
                `).join('')}
                    </div>
        `;
        
        calendarDays.appendChild(dayElement);
    }
}

function renderDayView() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const day = currentDate.getDate();
    
    document.getElementById('currentMonthYear').textContent = 
        currentDate.toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    
    const calendarDays = document.querySelector('#calendarView .grid');
    calendarDays.className = 'grid grid-cols-1';
    calendarDays.style.minHeight = '600px';
    
    const dayElement = document.createElement('div');
    dayElement.className = 'border border-gray-200 p-4 calendar-day';
    
    if (currentDate.toDateString() === new Date().toDateString()) {
        dayElement.classList.add('today');
    }
    
    // Get events for this day and sort by time
    const dayEvents = getEventsForDate(currentDate).sort((a, b) => {
        return new Date(a.start) - new Date(b.start);
    });
    
    const timeSlots = [];
    for (let hour = 0; hour < 24; hour++) {
        const timeSlot = {
            hour: hour,
            events: dayEvents.filter(event => {
                const eventHour = new Date(event.start).getHours();
                return eventHour === hour;
            })
        };
        timeSlots.push(timeSlot);
    }
    
    dayElement.innerHTML = `
        <div class="text-center mb-4">
            <h2 class="text-2xl font-bold">${currentDate.getDate()}</h2>
            <p class="text-gray-600">${currentDate.toLocaleDateString('es-ES', { weekday: 'long', month: 'long' })}</p>
                    </div>
        <div class="space-y-2">
            ${timeSlots.map(slot => `
                <div class="day-view-slot flex">
                    <div class="w-16 text-sm text-gray-500 text-right pr-3 day-view-time">
                        ${slot.hour.toString().padStart(2, '0')}:00
                </div>
                    <div class="flex-1 pl-3">
                        ${slot.events.map(event => `
                            <div class="fc-event ${event.type} mb-1 p-2 rounded cursor-pointer" 
                                 style="background-color: ${event.color}; color: white;"
                                 onclick="showEventDetails('${event.id}', '${event.type}')"
                                 title="${event.title}">
                                <div class="font-medium">${event.title}</div>
                                <div class="text-xs opacity-75">${new Date(event.start).toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' })}</div>
                </div>
                        `).join('')}
            </div>
        </div>
            `).join('')}
    </div>
    `;
    
    calendarDays.appendChild(dayElement);
}

function getEventsForDate(date) {
    return events.filter(event => {
        const eventDate = new Date(event.start);
        return eventDate.toDateString() === date.toDateString();
    });
}

function createEvent() {
    const eventData = {
        title: document.getElementById('eventTitle').value,
        description: document.getElementById('eventDescription').value,
        start: document.getElementById('eventStart').value,
        end: document.getElementById('eventEnd').value,
        type: document.getElementById('eventType').value,
        course_id: document.getElementById('eventCourse').value || null
    };
    
    fetch('/calendar/events', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(eventData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.id) {
            // Recargar todos los eventos para asegurar sincronización
            loadEvents();
            document.getElementById('addEventModal').classList.add('hidden');
            document.getElementById('addEventForm').reset();
            
            // Mostrar mensaje de éxito
            showNotification('Evento creado exitosamente', 'success');
        }
    })
    .catch(error => {
        console.error('Error creating event:', error);
        showNotification('Error al crear el evento', 'error');
    });
}

function showEventDetails(eventId, eventType) {
    const event = events.find(e => e.id === eventId);
    if (!event) return;
    
    const modal = document.getElementById('eventDetailsModal');
    const content = document.getElementById('eventDetailsContent');
    
    const eventDate = new Date(event.start).toLocaleDateString('es-ES', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    content.innerHTML = `
        <div class="space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Título</label>
                <p class="text-sm text-gray-900">${event.title}</p>
            </div>
            ${event.description ? `
            <div>
                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                <p class="text-sm text-gray-900">${event.description}</p>
            </div>
            ` : ''}
            <div>
                <label class="block text-sm font-medium text-gray-700">Fecha</label>
                <p class="text-sm text-gray-900">${eventDate}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tipo</label>
                <p class="text-sm text-gray-900 capitalize">${event.type}</p>
            </div>
            ${event.course ? `
            <div>
                <label class="block text-sm font-medium text-gray-700">Materia</label>
                <p class="text-sm text-gray-900">${event.course}</p>
            </div>
            ` : ''}
        </div>
    `;
    
    modal.classList.remove('hidden');
}

function goToDay(dateString) {
    currentDate = new Date(dateString);
    currentView = 'day';
    updateViewButtons();
    renderCalendar();
}

function showDayEvents(dateString) {
    const date = new Date(dateString);
    const dayEvents = getEventsForDate(date);
    
    if (dayEvents.length === 0) {
        showNotification('No hay eventos para este día', 'info');
        return;
    }
    
    const modal = document.getElementById('eventDetailsModal');
    const content = document.getElementById('eventDetailsContent');
    
    const formattedDate = date.toLocaleDateString('es-ES', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    content.innerHTML = `
        <div class="space-y-3">
            <div class="text-center mb-4">
                <h4 class="text-lg font-semibold text-gray-900">${formattedDate}</h4>
                <p class="text-sm text-gray-600">${dayEvents.length} evento${dayEvents.length > 1 ? 's' : ''}</p>
            </div>
            <div class="space-y-2">
                ${dayEvents.map(event => `
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-3 h-3 rounded-full mr-3 ${event.type}" style="background-color: ${event.color};"></div>
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">${event.title}</div>
                            <div class="text-sm text-gray-600 capitalize">${event.type}</div>
                            ${event.course ? `<div class="text-xs text-gray-500">${event.course}</div>` : ''}
                        </div>
                        <button onclick="showEventDetails('${event.id}', '${event.type}')" 
                                class="text-indigo-600 hover:text-indigo-800 text-sm">
                            Ver
                        </button>
                    </div>
                `).join('')}
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
}

function showNotification(message, type = 'success') {
    // Crear notificación temporal
    const notification = document.createElement('div');
    let bgColor = 'bg-green-500';
    
    switch(type) {
        case 'success':
            bgColor = 'bg-green-500';
            break;
        case 'error':
            bgColor = 'bg-red-500';
            break;
        case 'info':
            bgColor = 'bg-blue-500';
            break;
        case 'warning':
            bgColor = 'bg-yellow-500';
            break;
    }
    
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-md text-white z-50 ${bgColor}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
    </script>
@endsection 