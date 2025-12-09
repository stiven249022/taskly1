@extends('layouts.app')

@section('title', 'Configuración General')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Configuración General</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Gestiona la información básica de tu cuenta</p>
        </div>

        <!-- Formulario de Configuración -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <form action="{{ route('settings.general.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <!-- Información Personal -->
                <div class="mb-8">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Información Personal</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Preferencias de la Aplicación -->
                <div class="mb-8">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Preferencias de la Aplicación</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tema</label>
                            <select name="theme" id="themeSelect" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                <option value="light" {{ $user->theme === 'light' ? 'selected' : '' }}>Claro</option>
                                <option value="dark" {{ $user->theme === 'dark' ? 'selected' : '' }}>Oscuro</option>
                                <option value="system" {{ $user->theme === 'system' ? 'selected' : '' }}>Sistema</option>
                            </select>
                            @error('theme')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Idioma</label>
                            <select name="language" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                <option value="es" {{ $user->language === 'es' ? 'selected' : '' }}>Español</option>
                                <option value="en" {{ $user->language === 'en' ? 'selected' : '' }}>English</option>
                            </select>
                            @error('language')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Configuración de Zona Horaria -->
                <div class="mb-8">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Zona Horaria</h2>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Zona Horaria</label>
                        <select name="timezone" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <option value="America/Mexico_City" {{ $user->timezone === 'America/Mexico_City' ? 'selected' : '' }}>Ciudad de México (GMT-6)</option>
                            <option value="America/New_York" {{ $user->timezone === 'America/New_York' ? 'selected' : '' }}>Nueva York (GMT-5)</option>
                            <option value="America/Los_Angeles" {{ $user->timezone === 'America/Los_Angeles' ? 'selected' : '' }}>Los Ángeles (GMT-8)</option>
                            <option value="Europe/Madrid" {{ $user->timezone === 'Europe/Madrid' ? 'selected' : '' }}>Madrid (GMT+1)</option>
                            <option value="Europe/London" {{ $user->timezone === 'Europe/London' ? 'selected' : '' }}>Londres (GMT+0)</option>
                            <option value="Asia/Tokyo" {{ $user->timezone === 'Asia/Tokyo' ? 'selected' : '' }}>Tokio (GMT+9)</option>
                        </select>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Selecciona tu zona horaria para mostrar las fechas correctamente</p>
                        @error('timezone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="mb-8">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Información Adicional</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Teléfono</label>
                            <input type="tel" name="phone" value="{{ $user->phone }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ubicación</label>
                            <input type="text" name="location" value="{{ $user->location }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            @error('location')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Biografía</label>
                        <textarea name="bio" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">{{ $user->bio }}</textarea>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Cuéntanos un poco sobre ti (opcional)</p>
                        @error('bio')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('settings.index') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 border border-transparent rounded-md text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const themeSelect = document.getElementById('themeSelect');
    
    if (themeSelect) {
        themeSelect.addEventListener('change', function() {
            const selectedTheme = this.value;
            
            // Aplicar tema inmediatamente
            if (selectedTheme === 'dark') {
                document.documentElement.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
            } else if (selectedTheme === 'light') {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
            } else if (selectedTheme === 'system') {
                // Usar preferencia del sistema
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (systemPrefersDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                localStorage.removeItem('darkMode');
            }
            
            // Actualizar preferencia en el servidor
            fetch('/settings/preferences', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    theme: selectedTheme
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Tema actualizado correctamente');
                }
            })
            .catch(error => {
                console.error('Error al actualizar tema:', error);
            });
        });
    }
});
</script>
@endsection 