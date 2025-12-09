@extends('layouts.admin')

@section('title', 'Gestión de Profesores')
@section('page-title', 'Gestión de Profesores')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Profesores</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $teachers->total() }}</p>
            </div>
            <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Activos</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $teachers->where('status', 'active')->count() }}</p>
            </div>
            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pendientes</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $teachers->where('status', 'pending')->count() }}</p>
            </div>
            <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tareas Calificadas</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">
                    @php
                        $totalGraded = 0;
                        foreach($teachers as $teacher) {
                            $totalGraded += \App\Models\Task::where('graded_by', $teacher->id)->count();
                            $totalGraded += \App\Models\Project::where('graded_by', $teacher->id)->count();
                        }
                    @endphp
                    {{ $totalGraded }}
                </p>
            </div>
            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                <i class="fas fa-graduation-cap"></i>
            </div>
        </div>
    </div>
</div>

<!-- Teachers Table -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Lista de Profesores</h3>
        <a href="{{ route('admin.teachers.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i> Crear Profesor
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Profesor
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Estado
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Registro
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Actividad
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Calificaciones
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($teachers as $teacher)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="{{ $teacher->profile_photo_url }}" alt="{{ $teacher->name }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $teacher->name }} {{ $teacher->last_name }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $teacher->email }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($teacher->status === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($teacher->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                            {{ ucfirst($teacher->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $teacher->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        <div class="flex items-center space-x-2">
                            <span class="text-indigo-600">{{ $teacher->tasks->count() }}</span>
                            <span class="text-gray-400">tareas</span>
                            <span class="text-gray-400">|</span>
                            <span class="text-green-600">{{ $teacher->projects->count() }}</span>
                            <span class="text-gray-400">proyectos</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        @php
                            $gradedTasks = \App\Models\Task::where('graded_by', $teacher->id)->count();
                            $gradedProjects = \App\Models\Project::where('graded_by', $teacher->id)->count();
                            $totalGraded = $gradedTasks + $gradedProjects;
                        @endphp
                        <div class="flex items-center space-x-2">
                            <span class="text-blue-600">{{ $totalGraded }}</span>
                            <span class="text-gray-400">calificaciones</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            @if($teacher->status === 'pending')
                                <form method="POST" action="{{ route('admin.users.approve', $teacher->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.reject', $teacher->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @endif
                            
                            <button onclick="viewTeacherDetails({{ $teacher->id }})" 
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            <button onclick="editTeacher({{ $teacher->id }})" 
                                    class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <a href="{{ route('admin.teachers.download-report', $teacher->id) }}" 
                               class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                               title="Descargar Informe">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-chalkboard-teacher text-4xl mb-2"></i>
                        <p>No hay profesores registrados</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        {{ $teachers->links() }}
    </div>
</div>

<!-- Teacher Details Modal -->
<div id="teacherModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Detalles del Profesor</h3>
                <button onclick="closeTeacherModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="teacherDetails">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function viewTeacherDetails(teacherId) {
    const container = document.getElementById('teacherDetails');
    container.innerHTML = `
        <div class="text-center text-gray-500 dark:text-gray-400">
            <i class="fas fa-spinner fa-spin text-4xl mb-2"></i>
            <p>Cargando detalles del profesor...</p>
        </div>
    `;
    document.getElementById('teacherModal').classList.remove('hidden');

    fetch(`/admin/teachers/${teacherId}`)
        .then(r => {
            if (!r.ok) throw new Error('No se pudo cargar el detalle');
            return r.json();
        })
        .then(t => {
            container.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <div class="flex items-center">
                            <img src="${t.profile_photo_url}" alt="${t.name}" class="w-16 h-16 rounded-full mr-4 object-cover">
                            <div>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">${t.name} ${t.last_name ?? ''}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">${t.email}</p>
                            </div>
                        </div>
                        <div class="mt-4 space-y-2">
                            <p class="text-sm"><span class="font-medium">Estado:</span> ${t.status}</p>
                            <p class="text-sm"><span class="font-medium">Rol:</span> ${t.role}</p>
                            <p class="text-sm"><span class="font-medium">Registro:</span> ${t.created_at ?? '-'}</p>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded">
                                <p class="text-xs text-gray-500 dark:text-gray-300">Tareas</p>
                                <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400">${t.counts.tasks}</p>
                            </div>
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded">
                                <p class="text-xs text-gray-500 dark:text-gray-300">Proyectos</p>
                                <p class="text-xl font-bold text-green-600 dark:text-green-400">${t.counts.projects}</p>
                            </div>
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded">
                                <p class="text-xs text-gray-500 dark:text-gray-300">Tareas calificadas</p>
                                <p class="text-xl font-bold text-blue-600 dark:text-blue-400">${t.counts.graded_tasks}</p>
                            </div>
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded">
                                <p class="text-xs text-gray-500 dark:text-gray-300">Proyectos calificados</p>
                                <p class="text-xl font-bold text-purple-600 dark:text-purple-400">${t.counts.graded_projects}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        })
        .catch(err => {
            container.innerHTML = `
                <div class="text-center text-red-600 dark:text-red-400">
                    <i class="fas fa-exclamation-triangle text-4xl mb-2"></i>
                    <p>Error al cargar detalles del profesor.</p>
                </div>
            `;
        });
}

function editTeacher(teacherId) {
    // This would redirect to an edit page for the specific teacher
    window.location.href = `/admin/teachers/${teacherId}/edit`;
}

function closeTeacherModal() {
    document.getElementById('teacherModal').classList.add('hidden');
}
</script>
@endsection
