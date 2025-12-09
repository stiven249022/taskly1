@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">
                {{ isset($task) ? 'Editar Tarea' : 'Nueva Tarea' }}
            </h1>

            <form action="{{ isset($task) ? route('tasks.update', $task) : route('tasks.store') }}" method="POST">
                @csrf
                @if(isset($task))
                    @method('PUT')
                @endif

                <!-- Título -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                    <input type="text" name="title" id="title" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                           value="{{ old('title', $task->title ?? '') }}" required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">{{ old('description', $task->description ?? '') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Curso -->
                <div class="mb-4">
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Curso</label>
                    <select name="course_id" id="course_id" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200" required>
                        <option value="">Selecciona un curso</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" 
                                {{ old('course_id', $task->course_id ?? '') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prioridad -->
                <div class="mb-4">
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                    <select name="priority" id="priority" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200" required>
                        <option value="high" {{ old('priority', $task->priority ?? '') == 'high' ? 'selected' : '' }}>Alta</option>
                        <option value="medium" {{ old('priority', $task->priority ?? '') == 'medium' ? 'selected' : '' }}>Media</option>
                        <option value="low" {{ old('priority', $task->priority ?? '') == 'low' ? 'selected' : '' }}>Baja</option>
                    </select>
                    @error('priority')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha de Inicio -->
                <div class="mb-4">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio</label>
                    <input type="datetime-local" name="start_date" id="start_date" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                           value="{{ old('start_date', isset($task) && $task->start_date ? $task->start_date->format('Y-m-d\TH:i') : '') }}" required>
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha de Entrega -->
                <div class="mb-4">
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Entrega</label>
                    <input type="datetime-local" name="due_date" id="due_date" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                           value="{{ old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '') }}" required>
                    @error('due_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="status" id="status" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200" required>
                        <option value="pending" {{ old('status', $task->status ?? '') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="completed" {{ old('status', $task->status ?? '') == 'completed' ? 'selected' : '' }}>Completada</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Etiquetas -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Etiquetas</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach($tags as $tag)
                            <div class="flex items-center">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag_{{ $tag->id }}"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                                       {{ in_array($tag->id, old('tags', isset($task) ? $task->tags->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                                <label for="tag_{{ $tag->id }}" class="ml-2 text-sm text-gray-700">
                                    <span class="inline-block w-3 h-3 rounded-full mr-1" style="background-color: {{ $tag->color }}"></span>
                                    {{ $tag->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('tags')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('tasks.index') }}" 
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        {{ isset($task) ? 'Actualizar' : 'Crear' }} Tarea
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 