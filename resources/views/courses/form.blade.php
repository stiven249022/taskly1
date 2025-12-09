@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            {{ isset($course) ? 'Editar Curso' : 'Nuevo Curso' }}
        </h1>

        <form action="{{ isset($course) ? route('courses.update', $course) : route('courses.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
            @csrf
            @if(isset($course))
                @method('PUT')
            @endif

            <!-- Nombre -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" name="name" id="name" value="{{ old('name', $course->name ?? '') }}" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Código -->
            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                <input type="text" name="code" id="code" value="{{ old('code', $course->code ?? '') }}" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('code') border-red-500 @enderror">
                @error('code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="description" id="description" rows="3" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('description') border-red-500 @enderror">{{ old('description', $course->description ?? '') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Color -->
            <div class="mb-4">
                <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                <input type="color" name="color" id="color" value="{{ old('color', $course->color ?? '#3B82F6') }}" 
                    class="w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('color') border-red-500 @enderror">
                @error('color')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Semestre -->
            <div class="mb-4">
                <label for="semester" class="block text-sm font-medium text-gray-700 mb-1">Semestre</label>
                <select name="semester" id="semester" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('semester') border-red-500 @enderror">
                    @foreach(range(1, 10) as $semester)
                        <option value="{{ $semester }}" {{ old('semester', $course->semester ?? '') == $semester ? 'selected' : '' }}>
                            Semestre {{ $semester }}
                        </option>
                    @endforeach
                </select>
                @error('semester')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Profesor -->
            <div class="mb-4">
                <label for="professor" class="block text-sm font-medium text-gray-700 mb-1">Profesor</label>
                <input type="text" name="professor" id="professor" value="{{ old('professor', $course->professor ?? '') }}" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('professor') border-red-500 @enderror">
                @error('professor')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Horario -->
            <div class="mb-4">
                <label for="schedule" class="block text-sm font-medium text-gray-700 mb-1">Horario</label>
                <input type="text" name="schedule" id="schedule" value="{{ old('schedule', $course->schedule ?? '') }}" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('schedule') border-red-500 @enderror"
                    placeholder="Ej: Lunes y Miércoles 10:00 - 12:00">
                @error('schedule')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Créditos -->
            <div class="mb-6">
                <label for="credits" class="block text-sm font-medium text-gray-700 mb-1">Créditos</label>
                <select name="credits" id="credits" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 @error('credits') border-red-500 @enderror">
                    @foreach(range(1, 6) as $credits)
                        <option value="{{ $credits }}" {{ old('credits', $course->credits ?? '') == $credits ? 'selected' : '' }}>
                            {{ $credits }} créditos
                        </option>
                    @endforeach
                </select>
                @error('credits')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('courses.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    {{ isset($course) ? 'Actualizar' : 'Crear' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 