@extends('layouts.admin')

@section('title', 'Asignar Estudiantes')
@section('page-title', 'Asignar Estudiantes a Profesores')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Asignaciones</h2>
        <button onclick="openAssignModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i> Asignar Estudiante
        </button>
    </div>
</div>

<!-- Asignaciones Existentes -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Asignaciones Actuales</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Profesor
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Estudiante
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Materia
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Clase
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Estado
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($assignments as $group)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <img class="h-8 w-8 rounded-full" src="{{ $group['teacher']->profile_photo_url }}" alt="{{ $group['teacher']->name }}">
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $group['teacher']->name }} {{ $group['teacher']->last_name }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $group['teacher']->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <img class="h-8 w-8 rounded-full" src="{{ $group['student']->profile_photo_url }}" alt="{{ $group['student']->name }}">
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $group['student']->name }} {{ $group['student']->last_name }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $group['student']->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                        <div class="flex flex-col space-y-1">
                            @forelse($group['courses'] as $course)
                            <span class="inline-block">{{ $course->name }}</span>
                            @empty
                            <span class="text-gray-400">Sin materia</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                        <div class="flex flex-col space-y-1">
                            @forelse($group['class_names'] as $className)
                            <span class="inline-block">{{ $className }}</span>
                            @empty
                            <span class="text-gray-400">Sin clase</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $activeCount = $group['assignments']->where('status', 'active')->count();
                            $totalCount = $group['assignments']->count();
                        @endphp
                        @if($activeCount === $totalCount)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Activo
                            </span>
                        @elseif($activeCount === 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                Inactivo
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                Parcial ({{ $activeCount }}/{{ $totalCount }})
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex flex-col space-y-1">
                            @foreach($group['assignments'] as $assignment)
                            <div class="flex space-x-2">
                                <form method="POST" action="{{ route('admin.assignments.toggle', $assignment->id) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-xs">
                                        {{ $assignment->status === 'active' ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.assignments.destroy', $assignment->id) }}" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta asignación?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-xs">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        No hay asignaciones registradas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para Asignar Estudiante -->
<div id="assignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Asignar Estudiante a Profesor</h3>
                <button onclick="closeAssignModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="assignForm" method="POST" action="{{ route('admin.assignments.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Profesor
                    </label>
                    <select id="teacher_id" name="teacher_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Seleccionar profesor</option>
                        @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }} {{ $teacher->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Estudiante
                    </label>
                    <select id="student_id" name="student_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Seleccionar estudiante</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->name }} {{ $student->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="course_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Materia <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="course_name" name="course_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="Ej: Matemáticas, Física, Programación..."
                           value="{{ old('course_name') }}">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Escribe el nombre de la materia. Se creará automáticamente y se asignará al profesor y al estudiante.
                    </p>
                    @error('course_name')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="course_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Código de la Materia
                    </label>
                    <input type="text" id="course_code" name="course_code"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="Ej: MAT101, FIS201..."
                           value="{{ old('course_code') }}">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Código opcional para identificar la materia
                    </p>
                </div>
                
                <div class="mb-4">
                    <label for="schedule" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Horario
                    </label>
                    <input type="text" id="schedule" name="schedule"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="Ej: Lunes y Miércoles 8:00–10:00"
                           value="{{ old('schedule') }}">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Si se omite, se guardará como "Por asignar"
                    </p>
                </div>
                
                <div class="mb-4">
                    <label for="class_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nombre de la Clase
                    </label>
                    <input type="text" id="class_name" name="class_name"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="Ej: Clase A - Matemáticas">
                </div>
                
                <div class="mb-6">
                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 bg-gray-50 dark:bg-gray-700">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Información de la Materia</h4>
                        <div class="space-y-1 text-sm">
                            <p class="text-gray-700 dark:text-gray-200"><span class="font-medium">Materia:</span> <span id="infoCourseName">—</span></p>
                            <p class="text-gray-700 dark:text-gray-200"><span class="font-medium">Código:</span> <span id="infoCourseCode">—</span></p>
                            <p class="text-gray-700 dark:text-gray-200"><span class="font-medium">Profesor:</span> <span id="infoProfessor">—</span></p>
                            <p class="text-gray-700 dark:text-gray-200"><span class="font-medium">Semestre:</span> <span id="infoSemester">—</span></p>
                            <p class="text-gray-700 dark:text-gray-200"><span class="font-medium">Horario:</span> <span id="infoSchedule">Por asignar</span></p>
                            <p class="text-gray-700 dark:text-gray-200"><span class="font-medium">Créditos:</span> <span id="infoCredits">3</span></p>
                            <p class="text-gray-700 dark:text-gray-200"><span class="font-medium">Descripción:</span> <span id="infoDescription">Materia asignada por administrador</span></p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAssignModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Asignar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAssignModal() {
    document.getElementById('assignModal').classList.remove('hidden');
}

function closeAssignModal() {
    document.getElementById('assignModal').classList.add('hidden');
    document.getElementById('assignForm').reset();
}

// Actualizar panel de información de la materia en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const teacherSelect = document.getElementById('teacher_id');
    const studentSelect = document.getElementById('student_id');
    const courseNameInput = document.getElementById('course_name');
    const courseCodeInput = document.getElementById('course_code');
    const scheduleInput = document.getElementById('schedule');

    const infoCourseName = document.getElementById('infoCourseName');
    const infoCourseCode = document.getElementById('infoCourseCode');
    const infoProfessor = document.getElementById('infoProfessor');
    const infoSemester = document.getElementById('infoSemester');
    const infoSchedule = document.getElementById('infoSchedule');

    function computeSemester() {
        const now = new Date();
        const y = now.getFullYear();
        return `${y}-${y + 1}`;
    }

    function updateInfo() {
        infoCourseName.textContent = courseNameInput.value || '—';
        infoCourseCode.textContent = courseCodeInput.value || '—';
        infoProfessor.textContent = teacherSelect.selectedIndex > 0 ? teacherSelect.options[teacherSelect.selectedIndex].text : '—';
        infoSemester.textContent = computeSemester();
        infoSchedule.textContent = scheduleInput.value || 'Por asignar';
    }

    [teacherSelect, courseNameInput, courseCodeInput, scheduleInput].forEach(el => {
        el && el.addEventListener('input', updateInfo);
        el && el.addEventListener('change', updateInfo);
    });

    // Inicializar al abrir
    updateInfo();
});
</script>
@endsection
