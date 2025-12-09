@extends('layouts.dashboard')

@section('title', 'Asignar Estudiantes')
@section('page-title', 'Asignar Estudiantes a Mi Clase')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Mi Clase</h2>
        <button onclick="openAssignModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i> Asignar Estudiante
        </button>
    </div>
</div>

<!-- Estudiantes Asignados -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Estudiantes de Mi Clase ({{ $assignments->count() }})</h3>
    </div>
    
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($assignments as $assignment)
        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img class="h-12 w-12 rounded-full" src="{{ $assignment->student->profile_photo_url }}" alt="{{ $assignment->student->name }}">
                    <div class="flex-1">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ $assignment->student->name }} {{ $assignment->student->last_name }}
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $assignment->student->email }}</p>
                        <div class="flex items-center space-x-2 mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <i class="fas fa-check mr-1"></i> Asignado
                            </span>
                            <span class="text-xs text-gray-700 dark:text-gray-400 font-medium">
                                Clase: {{ $assignment->class_name ?? 'Sin nombre' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    <button onclick="openEditClassModal({{ $assignment->id }}, '{{ $assignment->class_name ?? '' }}')" 
                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm">
                        <i class="fas fa-edit mr-1"></i> Editar Clase
                    </button>
                    <form method="POST" action="{{ route('teacher.assign-students.remove', $assignment->id) }}" class="inline" 
                          onsubmit="return confirm('¿Estás seguro de remover este estudiante de tu clase?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-sm">
                            <i class="fas fa-times mr-1"></i> Remover
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
            <i class="fas fa-users text-4xl mb-2"></i>
            <p class="text-lg font-medium">No tienes estudiantes asignados</p>
            <p class="text-sm">Asigna estudiantes a tu clase para comenzar a calificar</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Estudiantes Disponibles -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Estudiantes Disponibles ({{ $allStudents->count() }})</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Estudiantes que aún no están asignados a ningún profesor</p>
    </div>
    
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($allStudents as $student)
        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img class="h-12 w-12 rounded-full" src="{{ $student->profile_photo_url }}" alt="{{ $student->name }}">
                    <div class="flex-1">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ $student->name }} {{ $student->last_name }}
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $student->email }}</p>
                        <div class="flex items-center space-x-2 mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                <i class="fas fa-user mr-1"></i> Disponible
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    <button onclick="openAssignStudentModal({{ $student->id }}, '{{ $student->name }} {{ $student->last_name }}')" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-md text-sm font-medium">
                        <i class="fas fa-plus mr-1"></i> Asignar
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
            <i class="fas fa-check-circle text-4xl mb-2"></i>
            <p class="text-lg font-medium">Todos los estudiantes están asignados</p>
            <p class="text-sm">No hay estudiantes disponibles para asignar</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal para Asignar Estudiante -->
<div id="assignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white" id="assignModalTitle">Asignar Estudiante</h3>
                <button onclick="closeAssignModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="assignForm" method="POST" action="{{ route('teacher.assign-students.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Estudiante
                    </label>
                    <select id="student_id" name="student_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Seleccionar estudiante</option>
                        @foreach($allStudents as $student)
                        <option value="{{ $student->id }}">{{ $student->name }} {{ $student->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="class_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nombre de la Clase
                    </label>
                    <input type="text" id="class_name" name="class_name"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="Ej: Clase A - Matemáticas" value="Mi Clase">
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

<!-- Modal para Editar Nombre de Clase -->
<div id="editClassModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Editar Nombre de Clase</h3>
                <button onclick="closeEditClassModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editClassForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="edit_class_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nombre de la Clase
                    </label>
                    <input type="text" id="edit_class_name" name="class_name"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="Ej: Clase A - Matemáticas">
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditClassModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Actualizar
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

function openAssignStudentModal(studentId, studentName) {
    document.getElementById('assignModalTitle').textContent = `Asignar ${studentName}`;
    document.getElementById('student_id').value = studentId;
    document.getElementById('assignModal').classList.remove('hidden');
}

function openEditClassModal(assignmentId, currentClassName) {
    document.getElementById('edit_class_name').value = currentClassName;
    document.getElementById('editClassForm').action = `/teacher/assign-students/${assignmentId}/class-name`;
    document.getElementById('editClassModal').classList.remove('hidden');
}

function closeEditClassModal() {
    document.getElementById('editClassModal').classList.add('hidden');
    document.getElementById('editClassForm').reset();
}
</script>
@endsection
