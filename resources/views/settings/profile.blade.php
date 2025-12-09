@extends('layouts.app')

@section('title', 'Perfil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Perfil</h1>
            <p class="text-gray-600 mt-2">Gestiona la información de tu perfil</p>
        </div>

        <!-- Formulario de Perfil -->
        <div class="bg-white rounded-lg shadow-md">
            <form action="{{ route('settings.profile.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <!-- Información Personal -->
                <div class="mb-8">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Información Personal</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="mb-8">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Información de Contacto</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                            <input type="tel" name="phone" value="{{ $user->phone }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ubicación</label>
                            <input type="text" name="location" value="{{ $user->location }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            @error('location')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Biografía -->
                <div class="mb-8">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Biografía</h2>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sobre ti</label>
                        <textarea name="bio" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200" placeholder="Cuéntanos un poco sobre ti...">{{ $user->bio }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Esta información será visible en tu perfil público</p>
                        @error('bio')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Preferencias -->
                <div class="mb-8">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Preferencias</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tema</label>
                            <select name="theme" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                <option value="light" {{ $user->theme === 'light' ? 'selected' : '' }}>Claro</option>
                                <option value="dark" {{ $user->theme === 'dark' ? 'selected' : '' }}>Oscuro</option>
                                <option value="system" {{ $user->theme === 'system' ? 'selected' : '' }}>Sistema</option>
                            </select>
                            @error('theme')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Idioma</label>
                            <select name="language" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                <option value="es" {{ $user->language === 'es' ? 'selected' : '' }}>Español</option>
                                <option value="en" {{ $user->language === 'en' ? 'selected' : '' }}>English</option>
                            </select>
                            @error('language')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
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