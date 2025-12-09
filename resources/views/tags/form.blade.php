@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            {{ isset($tag) ? 'Editar Etiqueta' : 'Nueva Etiqueta' }}
        </h1>

        <form action="{{ isset($tag) ? route('tags.update', $tag) : route('tags.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
            @csrf
            @if(isset($tag))
                @method('PUT')
            @endif

            <!-- Nombre -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" name="name" id="name" value="{{ old('name', $tag->name ?? '') }}" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Color -->
            <div class="mb-6">
                <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                <div class="flex items-center space-x-4">
                    <input type="color" name="color" id="color" value="{{ old('color', $tag->color ?? '#3B82F6') }}" 
                        class="w-16 h-10 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('color') border-red-500 @enderror">
                    <div class="flex-1">
                        <input type="text" id="colorHex" value="{{ old('color', $tag->color ?? '#3B82F6') }}" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                            placeholder="#RRGGBB" pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$">
                        <p class="text-sm text-gray-500 mt-1">Ingresa un color en formato hexadecimal (#RRGGBB)</p>
                    </div>
                </div>
                @error('color')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Vista Previa -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Vista Previa</label>
                <div class="flex items-center space-x-4">
                    <span class="px-3 py-1 rounded-full text-sm font-medium" id="previewTag">
                        {{ old('name', $tag->name ?? 'Nombre de la etiqueta') }}
                    </span>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('tags.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    {{ isset($tag) ? 'Actualizar' : 'Crear' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('color');
    const colorHexInput = document.getElementById('colorHex');
    const previewTag = document.getElementById('previewTag');

    function updateColor(color) {
        colorInput.value = color;
        colorHexInput.value = color;
        previewTag.style.backgroundColor = color + '20';
        previewTag.style.color = color;
    }

    colorInput.addEventListener('input', function(e) {
        updateColor(e.target.value);
    });

    colorHexInput.addEventListener('input', function(e) {
        const color = e.target.value;
        if (/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(color)) {
            updateColor(color);
        }
    });

    // Inicializar con el color actual
    updateColor(colorInput.value);
});
</script>
@endpush
@endsection 