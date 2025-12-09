@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Temas y Personalización</h1>
            <p class="text-gray-600 mt-2">Personaliza la apariencia de Taskly</p>
        </div>

        <!-- Grid de Contenido -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Temas -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-800 mb-4">Temas</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tema Claro -->
                            <div class="border rounded-lg p-4 {{ $theme === 'light' ? 'border-blue-500' : 'border-gray-200' }}">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-800">Tema Claro</h3>
                                        <p class="text-xs text-gray-500">Interfaz clara y limpia</p>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="theme" value="light" {{ $theme === 'light' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="space-y-2">
                                        <div class="h-4 bg-white rounded"></div>
                                        <div class="h-4 bg-gray-200 rounded"></div>
                                        <div class="h-4 bg-white rounded"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tema Oscuro -->
                            <div class="border rounded-lg p-4 {{ $theme === 'dark' ? 'border-blue-500' : 'border-gray-200' }}">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-800">Tema Oscuro</h3>
                                        <p class="text-xs text-gray-500">Modo nocturno para reducir la fatiga visual</p>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="theme" value="dark" {{ $theme === 'dark' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    </div>
                                </div>
                                <div class="bg-gray-800 rounded-lg p-4">
                                    <div class="space-y-2">
                                        <div class="h-4 bg-gray-700 rounded"></div>
                                        <div class="h-4 bg-gray-600 rounded"></div>
                                        <div class="h-4 bg-gray-700 rounded"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tema del Sistema -->
                            <div class="border rounded-lg p-4 {{ $theme === 'system' ? 'border-blue-500' : 'border-gray-200' }}">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-800">Tema del Sistema</h3>
                                        <p class="text-xs text-gray-500">Sigue la configuración de tu sistema</p>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="theme" value="system" {{ $theme === 'system' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    </div>
                                </div>
                                <div class="bg-gradient-to-r from-gray-50 to-gray-800 rounded-lg p-4">
                                    <div class="space-y-2">
                                        <div class="h-4 bg-gradient-to-r from-white to-gray-700 rounded"></div>
                                        <div class="h-4 bg-gradient-to-r from-gray-200 to-gray-600 rounded"></div>
                                        <div class="h-4 bg-gradient-to-r from-white to-gray-700 rounded"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colores -->
                <div class="mt-6 bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-800 mb-4">Colores</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Color Primario -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Color Primario</label>
                                <div class="flex space-x-2">
                                    <button onclick="setPrimaryColor('blue')" class="w-8 h-8 rounded-full bg-blue-600 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"></button>
                                    <button onclick="setPrimaryColor('indigo')" class="w-8 h-8 rounded-full bg-indigo-600 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"></button>
                                    <button onclick="setPrimaryColor('purple')" class="w-8 h-8 rounded-full bg-purple-600 focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"></button>
                                    <button onclick="setPrimaryColor('pink')" class="w-8 h-8 rounded-full bg-pink-600 focus:ring-2 focus:ring-offset-2 focus:ring-pink-500"></button>
                                    <button onclick="setPrimaryColor('red')" class="w-8 h-8 rounded-full bg-red-600 focus:ring-2 focus:ring-offset-2 focus:ring-red-500"></button>
                                </div>
                            </div>

                            <!-- Color Secundario -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Color Secundario</label>
                                <div class="flex space-x-2">
                                    <button onclick="setSecondaryColor('gray')" class="w-8 h-8 rounded-full bg-gray-600 focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"></button>
                                    <button onclick="setSecondaryColor('green')" class="w-8 h-8 rounded-full bg-green-600 focus:ring-2 focus:ring-offset-2 focus:ring-green-500"></button>
                                    <button onclick="setSecondaryColor('yellow')" class="w-8 h-8 rounded-full bg-yellow-600 focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"></button>
                                    <button onclick="setSecondaryColor('orange')" class="w-8 h-8 rounded-full bg-orange-600 focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"></button>
                                    <button onclick="setSecondaryColor('teal')" class="w-8 h-8 rounded-full bg-teal-600 focus:ring-2 focus:ring-offset-2 focus:ring-teal-500"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tipografía -->
                <div class="mt-6 bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-800 mb-4">Tipografía</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Familia de Fuente -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Familia de Fuente</label>
                                <select name="font_family" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                    <option value="inter">Inter</option>
                                    <option value="roboto">Roboto</option>
                                    <option value="open-sans">Open Sans</option>
                                    <option value="montserrat">Montserrat</option>
                                </select>
                            </div>

                            <!-- Tamaño de Fuente -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tamaño de Fuente</label>
                                <select name="font_size" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                    <option value="small">Pequeño</option>
                                    <option value="medium">Mediano</option>
                                    <option value="large">Grande</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="lg:col-span-1">
                <!-- Vista Previa -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Vista Previa</h2>
                    <div class="border rounded-lg p-4">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-800">Botón Primario</h3>
                                <button class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                    Botón de Acción
                                </button>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-800">Tarjeta</h3>
                                <div class="mt-2 p-4 border rounded-lg">
                                    <p class="text-sm text-gray-600">Contenido de ejemplo</p>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-800">Texto</h3>
                                <p class="mt-2 text-sm text-gray-600">
                                    Este es un ejemplo de texto con la fuente seleccionada.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="space-y-4">
                        <button onclick="saveThemeSettings()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            Guardar Cambios
                        </button>
                        <button onclick="resetThemeSettings()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
                            Restablecer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function setPrimaryColor(color) {
    // Implementar lógica para cambiar color primario
}

function setSecondaryColor(color) {
    // Implementar lógica para cambiar color secundario
}

function saveThemeSettings() {
    // Implementar lógica para guardar configuración de tema
}

function resetThemeSettings() {
    if (confirm('¿Estás seguro de que deseas restablecer la configuración del tema?')) {
        // Implementar lógica para restablecer configuración
    }
}

// Manejo de cambios de tema
document.querySelectorAll('input[name="theme"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Implementar lógica para cambiar tema
    });
});

// Manejo de cambios de tipografía
document.querySelector('select[name="font_family"]').addEventListener('change', function() {
    // Implementar lógica para cambiar familia de fuente
});

document.querySelector('select[name="font_size"]').addEventListener('change', function() {
    // Implementar lógica para cambiar tamaño de fuente
});
</script>
@endpush
@endsection 