@extends('layouts.dashboard')

@section('title', 'Configuración de Notificaciones')
@section('page-title', 'Configuración de Notificaciones')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Configuración de Notificaciones</h3>
            <p class="text-sm text-gray-600 mt-1">Personaliza cómo recibirás las notificaciones</p>
        </div>
        
        <div class="p-6">
            <form id="notificationSettingsForm">
                <!-- Configuración General -->
                <div class="mb-8">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Configuración General</h4>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h5 class="text-sm font-medium text-gray-900">Modo Oscuro</h5>
                                <p class="text-sm text-gray-500">Activar el tema oscuro en la aplicación</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="darkModeToggle" {{ auth()->user()->dark_mode ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h5 class="text-sm font-medium text-gray-900">Notificaciones por Email</h5>
                                <p class="text-sm text-gray-500">Recibir notificaciones por correo electrónico</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="emailNotificationsToggle" {{ auth()->user()->email_notifications ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h5 class="text-sm font-medium text-gray-900">Notificaciones Push</h5>
                                <p class="text-sm text-gray-500">Recibir notificaciones en tiempo real</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="pushNotificationsToggle" {{ auth()->user()->push_notifications ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Configuración de Recordatorios -->
                <div class="mb-8">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Recordatorios</h4>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h5 class="text-sm font-medium text-gray-900">Recordatorios de Tareas</h5>
                                <p class="text-sm text-gray-500">Recibir recordatorios cuando las tareas estén próximas a vencer</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="taskRemindersToggle" {{ auth()->user()->task_reminders ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h5 class="text-sm font-medium text-gray-900">Fechas Límite de Proyectos</h5>
                                <p class="text-sm text-gray-500">Recibir alertas cuando los proyectos estén próximos a vencer</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="projectDeadlinesToggle" {{ auth()->user()->project_deadlines ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h5 class="text-sm font-medium text-gray-900">Recordatorios de Exámenes</h5>
                                <p class="text-sm text-gray-500">Recibir recordatorios para exámenes y evaluaciones</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="examRemindersToggle" {{ auth()->user()->exam_reminders ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Frecuencia de Recordatorios -->
                <div class="mb-8">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Frecuencia de Recordatorios</h4>
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Antelación de Recordatorios</label>
                        <select id="reminderFrequency" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="15" {{ auth()->user()->reminder_frequency == 15 ? 'selected' : '' }}>15 minutos antes</option>
                            <option value="30" {{ auth()->user()->reminder_frequency == 30 ? 'selected' : '' }}>30 minutos antes</option>
                            <option value="60" {{ auth()->user()->reminder_frequency == 60 ? 'selected' : '' }}>1 hora antes</option>
                            <option value="120" {{ auth()->user()->reminder_frequency == 120 ? 'selected' : '' }}>2 horas antes</option>
                            <option value="1440" {{ auth()->user()->reminder_frequency == 1440 ? 'selected' : '' }}>1 día antes</option>
                        </select>
                        <p class="text-sm text-gray-500 mt-1">Selecciona cuándo quieres recibir recordatorios</p>
                    </div>
                </div>
                
                <!-- Botones de Acción -->
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="resetSettings()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Restablecer
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700">
                        Guardar Configuración
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #4f46e5;
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
}
</style>

<script>
document.getElementById('notificationSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        dark_mode: document.getElementById('darkModeToggle').checked,
        email_notifications: document.getElementById('emailNotificationsToggle').checked,
        push_notifications: document.getElementById('pushNotificationsToggle').checked,
        task_reminders: document.getElementById('taskRemindersToggle').checked,
        project_deadlines: document.getElementById('projectDeadlinesToggle').checked,
        exam_reminders: document.getElementById('examRemindersToggle').checked,
        reminder_frequency: document.getElementById('reminderFrequency').value
    };
    
    fetch('/notifications/settings', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Configuración guardada exitosamente', 'success');
        } else {
            showNotification('Error al guardar la configuración', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al guardar la configuración', 'error');
    });
});

function resetSettings() {
    if (confirm('¿Estás seguro de que quieres restablecer la configuración?')) {
        document.getElementById('darkModeToggle').checked = false;
        document.getElementById('emailNotificationsToggle').checked = false;
        document.getElementById('pushNotificationsToggle').checked = false;
        document.getElementById('taskRemindersToggle').checked = false;
        document.getElementById('projectDeadlinesToggle').checked = false;
        document.getElementById('examRemindersToggle').checked = false;
        document.getElementById('reminderFrequency').value = '60';
    }
}
</script>
@endsection 