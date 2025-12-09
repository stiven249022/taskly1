@extends('layouts.app')

@section('title', 'Configuración de Notificaciones')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Configuración de Notificaciones</h1>
            <p class="text-gray-600 mt-2">Gestiona cómo y cuándo recibir notificaciones</p>
        </div>

        <form action="{{ route('settings.notifications.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Preferencias Generales -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Preferencias Generales</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-gray-700 font-medium">Notificaciones por Email</label>
                            <p class="text-sm text-gray-500">Recibe notificaciones por correo electrónico</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="email_notifications" class="sr-only peer" {{ $user->email_notifications ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-gray-700 font-medium">Notificaciones Push</label>
                            <p class="text-sm text-gray-500">Recibe notificaciones en tiempo real en el navegador</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="push_notifications" class="sr-only peer" {{ $user->push_notifications ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Tipos de Notificaciones -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Tipos de Notificaciones</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-gray-700 font-medium">Recordatorios de Tareas</label>
                            <p class="text-sm text-gray-500">Notificaciones sobre tareas pendientes y vencidas</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="task_reminders" class="sr-only peer" {{ $user->task_reminders ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-gray-700 font-medium">Fechas límite de Proyectos</label>
                            <p class="text-sm text-gray-500">Notificaciones sobre proyectos próximos a vencer</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="project_deadlines" class="sr-only peer" {{ $user->project_deadlines ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-gray-700 font-medium">Recordatorios de Exámenes</label>
                            <p class="text-sm text-gray-500">Notificaciones sobre exámenes próximos</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="exam_reminders" class="sr-only peer" {{ $user->exam_reminders ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Configuración de Recordatorios -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Configuración de Recordatorios</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Frecuencia de Recordatorios</label>
                        <select name="reminder_frequency" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <option value="15" {{ $user->reminder_frequency == 15 ? 'selected' : '' }}>15 minutos antes</option>
                            <option value="30" {{ $user->reminder_frequency == 30 ? 'selected' : '' }}>30 minutos antes</option>
                            <option value="60" {{ $user->reminder_frequency == 60 ? 'selected' : '' }}>1 hora antes</option>
                            <option value="120" {{ $user->reminder_frequency == 120 ? 'selected' : '' }}>2 horas antes</option>
                            <option value="1440" {{ $user->reminder_frequency == 1440 ? 'selected' : '' }}>1 día antes</option>
                        </select>
                        <p class="text-sm text-gray-500 mt-1">Selecciona cuándo quieres recibir recordatorios</p>
                    </div>
                </div>
            </div>

            <!-- Horarios de Notificaciones -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Horarios de Notificaciones</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-gray-700 font-medium">Notificaciones Silenciosas</label>
                            <p class="text-sm text-gray-500">Recibe notificaciones sin sonido durante las horas de sueño</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hora de Inicio (Silencioso)</label>
                            <input type="time" value="22:00" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hora de Fin (Silencioso)</label>
                            <input type="time" value="08:00" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('settings.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 border border-transparent rounded-md text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
<div id="successMessage" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg z-50">
    {{ session('success') }}
</div>

<script>
setTimeout(() => {
    document.getElementById('successMessage').remove();
}, 3000);
</script>
@endif
@endsection 