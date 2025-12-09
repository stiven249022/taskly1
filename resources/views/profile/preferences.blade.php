@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Preferencias</h1>
            <p class="text-gray-600 mt-2">Personaliza tu experiencia en la aplicación</p>
        </div>

        <form action="{{ route('profile.preferences.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <!-- Apariencia -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Apariencia</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tema</label>
                        <div class="grid grid-cols-3 gap-4">
                            <label class="relative flex items-center justify-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="theme" value="light" class="sr-only" {{ $preferences->theme === 'light' ? 'checked' : '' }}>
                                <div class="text-center">
                                    <i class="fas fa-sun text-2xl text-yellow-500 mb-2"></i>
                                    <p class="text-sm font-medium text-gray-700">Claro</p>
                                </div>
                            </label>
                            <label class="relative flex items-center justify-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="theme" value="dark" class="sr-only" {{ $preferences->theme === 'dark' ? 'checked' : '' }}>
                                <div class="text-center">
                                    <i class="fas fa-moon text-2xl text-gray-700 mb-2"></i>
                                    <p class="text-sm font-medium text-gray-700">Oscuro</p>
                                </div>
                            </label>
                            <label class="relative flex items-center justify-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="theme" value="system" class="sr-only" {{ $preferences->theme === 'system' ? 'checked' : '' }}>
                                <div class="text-center">
                                    <i class="fas fa-desktop text-2xl text-blue-500 mb-2"></i>
                                    <p class="text-sm font-medium text-gray-700">Sistema</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color Principal</label>
                        <div class="grid grid-cols-6 gap-4">
                            @foreach($colors as $color)
                            <label class="relative">
                                <input type="radio" name="primary_color" value="{{ $color['value'] }}" class="sr-only" {{ $preferences->primary_color === $color['value'] ? 'checked' : '' }}>
                                <div class="w-8 h-8 rounded-full cursor-pointer" style="background-color: {{ $color['value'] }}"></div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vista de Lista -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Vista de Lista</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Densidad</label>
                        <div class="grid grid-cols-3 gap-4">
                            <label class="relative flex items-center justify-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="list_density" value="compact" class="sr-only" {{ $preferences->list_density === 'compact' ? 'checked' : '' }}>
                                <div class="text-center">
                                    <i class="fas fa-compress-alt text-2xl text-gray-700 mb-2"></i>
                                    <p class="text-sm font-medium text-gray-700">Compacta</p>
                                </div>
                            </label>
                            <label class="relative flex items-center justify-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="list_density" value="comfortable" class="sr-only" {{ $preferences->list_density === 'comfortable' ? 'checked' : '' }}>
                                <div class="text-center">
                                    <i class="fas fa-expand-alt text-2xl text-gray-700 mb-2"></i>
                                    <p class="text-sm font-medium text-gray-700">Cómoda</p>
                                </div>
                            </label>
                            <label class="relative flex items-center justify-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="list_density" value="spacious" class="sr-only" {{ $preferences->list_density === 'spacious' ? 'checked' : '' }}>
                                <div class="text-center">
                                    <i class="fas fa-expand text-2xl text-gray-700 mb-2"></i>
                                    <p class="text-sm font-medium text-gray-700">Espaciosa</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ordenamiento Predeterminado</label>
                        <select name="default_sort" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <option value="due_date" {{ $preferences->default_sort === 'due_date' ? 'selected' : '' }}>Fecha de Vencimiento</option>
                            <option value="priority" {{ $preferences->default_sort === 'priority' ? 'selected' : '' }}>Prioridad</option>
                            <option value="created_at" {{ $preferences->default_sort === 'created_at' ? 'selected' : '' }}>Fecha de Creación</option>
                            <option value="title" {{ $preferences->default_sort === 'title' ? 'selected' : '' }}>Título</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Calendario -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Calendario</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vista Predeterminada</label>
                        <select name="calendar_view" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <option value="month" {{ $preferences->calendar_view === 'month' ? 'selected' : '' }}>Mes</option>
                            <option value="week" {{ $preferences->calendar_view === 'week' ? 'selected' : '' }}>Semana</option>
                            <option value="day" {{ $preferences->calendar_view === 'day' ? 'selected' : '' }}>Día</option>
                            <option value="agenda" {{ $preferences->calendar_view === 'agenda' ? 'selected' : '' }}>Agenda</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hora de Inicio</label>
                        <input type="time" name="calendar_start_time" value="{{ $preferences->calendar_start_time }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hora de Fin</label>
                        <input type="time" name="calendar_end_time" value="{{ $preferences->calendar_end_time }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    </div>
                </div>
            </div>

            <!-- Notificaciones -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Notificaciones</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-gray-700 font-medium">Recordatorios por Email</label>
                            <p class="text-sm text-gray-500">Recibe recordatorios por correo electrónico</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="email_reminders" class="sr-only peer" {{ $preferences->email_reminders ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-gray-700 font-medium">Notificaciones Push</label>
                            <p class="text-sm text-gray-500">Recibe notificaciones en el navegador</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="push_notifications" class="sr-only peer" {{ $preferences->push_notifications ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Antelación de Recordatorios</label>
                        <select name="reminder_advance" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <option value="15" {{ $preferences->reminder_advance === '15' ? 'selected' : '' }}>15 minutos</option>
                            <option value="30" {{ $preferences->reminder_advance === '30' ? 'selected' : '' }}>30 minutos</option>
                            <option value="60" {{ $preferences->reminder_advance === '60' ? 'selected' : '' }}>1 hora</option>
                            <option value="1440" {{ $preferences->reminder_advance === '1440' ? 'selected' : '' }}>1 día</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('profile.edit') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Actualizar vista previa del tema
document.querySelectorAll('input[name="theme"]').forEach(input => {
    input.addEventListener('change', function() {
        document.documentElement.classList.remove('light', 'dark');
        if (this.value !== 'system') {
            document.documentElement.classList.add(this.value);
        }
    });
});

// Actualizar vista previa del color principal
document.querySelectorAll('input[name="primary_color"]').forEach(input => {
    input.addEventListener('change', function() {
        document.documentElement.style.setProperty('--primary-color', this.value);
    });
});
</script>
@endpush
@endsection 