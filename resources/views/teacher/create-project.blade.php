@extends('layouts.dashboard')

@section('title', 'Crear Proyecto')
@section('page-title', 'Asignar Nuevo Proyecto')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Asignar Nuevo Proyecto</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Crea un nuevo proyecto para tus estudiantes</p>
        </div>
        
        <form method="POST" action="{{ route('teacher.create-project.store') }}" class="p-6" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Estudiante -->
                <div class="md:col-span-2">
                    <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Estudiante <span class="text-red-500">*</span>
                    </label>
                    <select id="student_id" name="student_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Seleccionar estudiante</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->display_name }}
                        </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Curso -->
                <div class="md:col-span-2">
                    <label for="course_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Curso <span class="text-red-500">*</span>
                    </label>
                    <select id="course_id" name="course_id" required disabled
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Primero selecciona un estudiante</option>
                    </select>
                    @error('course_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Nombre del Proyecto -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nombre del Proyecto <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="Ej: Sistema de Gestión de Inventario">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Descripción -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Descripción
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                              placeholder="Describe los objetivos y requisitos del proyecto...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Fecha de Finalización -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Fecha de Finalización <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Prioridad -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Prioridad <span class="text-red-500">*</span>
                    </label>
                    <select id="priority" name="priority" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Seleccionar prioridad</option>
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Baja</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Media</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Alta</option>
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Material de apoyo (opcional) -->
            <div class="mt-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Material de apoyo (opcional)</label>
                <input type="file" name="support_file" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PDF, DOC, DOCX, TXT, JPG, PNG, GIF, ZIP, RAR (máx. 10MB)</p>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('teacher.projects') }}" 
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                    Cancelar
                </a>
                <input type="hidden" name="create_subtasks" id="create_subtasks" value="">
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    <i class="fas fa-project-diagram mr-2"></i> Asignar Proyecto
                </button>
                <button type="button"
                        onclick="document.getElementById('create_subtasks').value = '1'; this.closest('form').submit();"
                        class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                    <i class="fas fa-tasks mr-2"></i> Crear Subtareas
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentSelect = document.getElementById('student_id');
    const courseSelect = document.getElementById('course_id');
    const oldCourseId = @json(old('course_id'));
    
    studentSelect.addEventListener('change', function() {
        const studentId = this.value;
        
        if (!studentId) {
            courseSelect.innerHTML = '<option value="">Primero selecciona un estudiante</option>';
            courseSelect.disabled = true;
            return;
        }
        
        // Mostrar loading
        courseSelect.innerHTML = '<option value="">Cargando cursos...</option>';
        courseSelect.disabled = true;
        
        // Hacer petición AJAX para obtener los cursos del estudiante
        fetch(`/teacher/students/${studentId}/courses`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            courseSelect.innerHTML = '<option value="">Seleccionar curso</option>';
            
            if (data.length === 0) {
                courseSelect.innerHTML += '<option value="">Este estudiante no tiene cursos asignados</option>';
            } else {
                data.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id;
                    option.textContent = course.name;
                    if (oldCourseId && oldCourseId == course.id) {
                        option.selected = true;
                    }
                    courseSelect.appendChild(option);
                });
            }
            
            courseSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error:', error);
            courseSelect.innerHTML = '<option value="">Error al cargar los cursos</option>';
            courseSelect.disabled = true;
        });
    });
    
    // Si hay un estudiante seleccionado previamente (después de un error de validación), cargar sus cursos
    if (studentSelect.value) {
        studentSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
