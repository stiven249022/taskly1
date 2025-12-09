@extends('layouts.dashboard')

@section('title', 'Gestión de Estudiantes')
@section('page-title', 'Gestión de Estudiantes')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Estudiantes</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $students->total() }}</p>
            </div>
            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tareas Completadas</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $students->sum(function($student) { return $student->tasks->where('status', 'completed')->count(); }) }}</p>
            </div>
            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Proyectos Activos</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $students->sum(function($student) { return $student->projects->where('status', 'active')->count(); }) }}</p>
            </div>
            <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                <i class="fas fa-project-diagram"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Promedio General</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">
                    @php
                        $totalGrades = 0;
                        $count = 0;
                        foreach($students as $student) {
                            $taskGrades = $student->tasks->whereNotNull('grade')->pluck('grade');
                            $projectGrades = $student->projects->whereNotNull('grade')->pluck('grade');
                            $allGrades = $taskGrades->merge($projectGrades);
                            if($allGrades->count() > 0) {
                                $totalGrades += $allGrades->avg();
                                $count++;
                            }
                        }
                        $average = $count > 0 ? round($totalGrades / $count, 1) : 0;
                    @endphp
                    {{ $average }}%
                </p>
            </div>
            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
</div>

<!-- Students Table -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Lista de Estudiantes</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Estudiante
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Tareas
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Proyectos
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Promedio
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($students as $student)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="{{ $student->profile_photo_url }}" alt="{{ $student->name }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $student->display_name }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $student->email }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">
                            <div class="flex items-center space-x-2">
                                <span class="text-green-600">{{ $student->tasks->where('status', 'completed')->count() }}</span>
                                <span class="text-gray-400">/</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ $student->tasks->count() }}</span>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                @php
                                    $gradedTasks = $student->tasks->whereNotNull('grade');
                                    $avgTaskGrade = $gradedTasks->count() > 0 ? round($gradedTasks->avg('grade'), 1) : 0;
                                @endphp
                                Promedio: {{ $avgTaskGrade }}%
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">
                            <div class="flex items-center space-x-2">
                                <span class="text-green-600">{{ $student->projects->where('status', 'completed')->count() }}</span>
                                <span class="text-gray-400">/</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ $student->projects->count() }}</span>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                @php
                                    $gradedProjects = $student->projects->whereNotNull('grade');
                                    $avgProjectGrade = $gradedProjects->count() > 0 ? round($gradedProjects->avg('grade'), 1) : 0;
                                @endphp
                                Promedio: {{ $avgProjectGrade }}%
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $taskGrades = $student->tasks->whereNotNull('grade')->pluck('grade');
                            $projectGrades = $student->projects->whereNotNull('grade')->pluck('grade');
                            $allGrades = $taskGrades->merge($projectGrades);
                            $overallAverage = $allGrades->count() > 0 ? round($allGrades->avg(), 1) : 0;
                        @endphp
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $overallAverage }}%
                            </div>
                            <div class="ml-2 w-16 bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $overallAverage }}%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="viewStudentDetails({{ $student->id }})" 
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="gradeStudentWork({{ $student->id }})" 
                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                <i class="fas fa-graduation-cap"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-users text-4xl mb-2"></i>
                        <p>No hay estudiantes registrados</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        {{ $students->links() }}
    </div>
</div>

<!-- Student Details Modal -->
<div id="studentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Detalles del Estudiante</h3>
                <button onclick="closeStudentModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="studentDetails">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function viewStudentDetails(studentId) {
    const container = document.getElementById('studentDetails');
    container.innerHTML = `
        <div class="text-center text-gray-500 dark:text-gray-400">
            <i class="fas fa-spinner fa-spin text-4xl mb-2"></i>
            <p>Cargando detalles del estudiante...</p>
        </div>
    `;
    document.getElementById('studentModal').classList.remove('hidden');
    fetch(`/teacher/students/${studentId}/details`)
        .then(r => r.json())
        .then(data => {
            if (data.error) {
                container.innerHTML = `<div class='text-center text-red-600'>${data.error}</div>`;
                return;
            }
            const s = data.student;
            const stats = data.stats;
            const courses = Array.isArray(data.courses) ? data.courses : [];
            function normalize(str) { return (str || '').trim().replace(/\s+/g,' '); }
            function computeDisplayName(name, last, email) {
                const n = normalize(name);
                const l = normalize(last);
                const isGmail = /@gmail\.com$/i.test(email || '');
                if (!isGmail) {
                    return (n + (l ? ' ' + l : '')).trim();
                }
                if (!l) return n;
                const nLower = n.toLowerCase();
                const lLower = l.toLowerCase();
                if (nLower === lLower) return n;
                if (nLower.includes(lLower)) return n;
                return (n + ' ' + l).trim();
            }
            const displayName = computeDisplayName(s.name, s.last_name, s.email);
            const coursesHtml = courses.length > 0 ? courses.map(c => `
                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded">
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Materia:</span><span class="font-medium">${c.name}</span></div>
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Código:</span><span class="font-medium">${c.code ?? '—'}</span></div>
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Profesor:</span><span class="font-medium">${c.professor ?? '—'}</span></div>
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Semestre:</span><span class="font-medium">${c.semester ?? '—'}</span></div>
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Horario:</span><span class="font-medium">${c.schedule ?? '—'}</span></div>
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Créditos:</span><span class="font-medium">${c.credits ?? '—'}</span></div>
                    <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">${c.description ?? ''}</div>
                </div>
            `).join('') : `<div class='text-sm text-gray-500'>Sin materias asignadas</div>`;
            container.innerHTML = `
                <div class="flex items-center space-x-4 mb-4">
                    <img src="${s.profile_photo_url}" class="w-12 h-12 rounded-full object-cover" />
                    <div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">${displayName}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">${s.email}</div>
                        <div class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Clase:</span> ${s.class_name ?? '—'}</div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="p-4 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                        <div class="text-xs text-gray-500">Tareas (todas)</div>
                        <div class="text-xl font-semibold">${stats.tasks_total}</div>
                    </div>
                    <div class="p-4 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                        <div class="text-xs text-gray-500">Proyectos (todas)</div>
                        <div class="text-xl font-semibold">${stats.projects_total}</div>
                    </div>
                    <div class="p-4 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                        <div class="text-xs text-gray-500">Promedio general</div>
                        <div class="text-xl font-semibold">${stats.average}%</div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="p-4 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                        <div class="text-xs text-gray-500">Tareas asignadas por ti</div>
                        <div class="text-xl font-semibold">${stats.tasks_by_teacher}</div>
                    </div>
                    <div class="p-4 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                        <div class="text-xs text-gray-500">Proyectos asignados por ti</div>
                        <div class="text-xl font-semibold">${stats.projects_by_teacher}</div>
                    </div>
                    <div class="p-4 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                        <div class="text-xs text-gray-500">Tareas calificadas por ti</div>
                        <div class="text-xl font-semibold">${stats.graded_tasks_by_teacher}</div>
                    </div>
                    <div class="p-4 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                        <div class="text-xs text-gray-500">Proyectos calificados por ti</div>
                        <div class="text-xl font-semibold">${stats.graded_projects_by_teacher}</div>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Materias del estudiante</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">${coursesHtml}</div>
                </div>
            `;
        })
        .catch(() => {
            container.innerHTML = `<div class='text-center text-red-600'>Error al cargar detalles.</div>`;
        });
}

function gradeStudentWork(studentId) {
    // This would redirect to a grading page for the specific student
    window.location.href = `/teacher/students/${studentId}/grade`;
}

function closeStudentModal() {
    document.getElementById('studentModal').classList.add('hidden');
}
</script>
@endsection
