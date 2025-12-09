@extends('layouts.dashboard')

@section('title', 'Calificar Tareas')
@section('page-title', 'Calificar Tareas')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tareas Pendientes de Calificación</h2>
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Total: {{ $tasks->total() }} tareas
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Lista de Tareas</h3>
        <form action="{{ route('teacher.tasks') }}" method="GET" class="w-full max-w-sm">
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar tareas..." class="w-full pl-10 pr-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </form>
    </div>
    
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($tasks as $task)
        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-2">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $task->title }}</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Completada
                        </span>
                    </div>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $task->description }}</p>

                    @if($task->isSubmitted())
                    <div class="mt-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-file text-blue-500 mr-2"></i>
                                <div>
                                    <p class="text-sm text-blue-800 dark:text-blue-200">{{ $task->file_name }}</p>
                                    <p class="text-xs text-blue-600 dark:text-blue-300">{{ $task->getFormattedFileSize() }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('task-files.view', $task) }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200" title="Ver archivo">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('task-files.download', $task) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200" title="Descargar archivo">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                        <div class="flex items-center">
                            <i class="fas fa-user mr-1"></i>
                            <span>{{ $task->user->name }} {{ $task->user->last_name }}</span>
                        </div>
                        @if($task->course)
                        <div class="flex items-center">
                            <i class="fas fa-book mr-1"></i>
                            <span>{{ $task->course->name }}</span>
                        </div>
                        @endif
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-1"></i>
                            <span>Completada: {{ $task->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
                
                @if($task->isSubmitted())
                <div class="flex items-center space-x-2">
                    <button onclick="openGradeModal('task', {{ $task->id }}, '{{ $task->title }}')" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-graduation-cap mr-1"></i> Calificar
                    </button>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
            <i class="fas fa-check-circle text-4xl mb-2"></i>
            <p class="text-lg font-medium">No hay tareas pendientes de calificación</p>
            <p class="text-sm">Todas las tareas completadas han sido calificadas</p>
        </div>
        @endforelse
    </div>
    
    @if($tasks->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        {{ $tasks->links() }}
    </div>
    @endif
</div>

<!-- Grade Modal -->
<div id="gradeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div id="modalContent" class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white" id="modalTitle">Calificar Tarea</h3>
                <div class="flex items-center space-x-2">
                    <button onclick="toggleModalDarkMode()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="Alternar modo oscuro/claro">
                        <i id="modalDarkIcon" class="fas fa-moon"></i>
                    </button>
                    <button onclick="closeGradeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <form id="gradeForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="grade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Calificación (0-100)
                    </label>
                    <input type="number" id="grade" name="grade" min="0" max="100" step="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                
                <div class="mb-4">
                    <label for="feedback" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Comentarios (opcional)
                    </label>
                    <textarea id="feedback" name="feedback" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeGradeModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Calificar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openGradeModal(type, id, title) {
    document.getElementById('modalTitle').textContent = `Calificar Tarea: ${title}`;
    document.getElementById('gradeForm').action = `/teacher/tasks/${id}/grade`;
    document.getElementById('gradeModal').classList.remove('hidden');
}

function closeGradeModal() {
    document.getElementById('gradeModal').classList.add('hidden');
    document.getElementById('gradeForm').reset();
}

function toggleModalDarkMode() {
    const modalContent = document.getElementById('modalContent');
    const icon = document.getElementById('modalDarkIcon');
    
    if (modalContent) {
        const isDark = modalContent.classList.contains('dark-mode');
        
        if (isDark) {
            // Cambiar a modo claro
            modalContent.classList.remove('dark-mode');
            modalContent.classList.remove('bg-gray-800');
            modalContent.classList.remove('border-gray-700');
            modalContent.classList.add('bg-white');
            modalContent.classList.add('border-gray-200');
            
            // Actualizar icono
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
            
            // Actualizar texto y labels
            const labels = modalContent.querySelectorAll('label, h3, p, span, input, textarea');
            labels.forEach(el => {
                el.classList.remove('dark:text-white', 'dark:text-gray-300', 'dark:text-gray-400', 'dark:bg-gray-700', 'dark:border-gray-600');
                el.classList.add('text-gray-900', 'text-gray-700', 'text-gray-500');
            });
        } else {
            // Cambiar a modo oscuro
            modalContent.classList.add('dark-mode');
            modalContent.classList.remove('bg-white');
            modalContent.classList.remove('border-gray-200');
            modalContent.classList.add('bg-gray-800');
            modalContent.classList.add('border-gray-700');
            
            // Actualizar icono
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
            
            // Actualizar texto y labels
            const labels = modalContent.querySelectorAll('label, h3, p, span');
            labels.forEach(el => {
                el.classList.remove('text-gray-900', 'text-gray-700', 'text-gray-500');
                el.classList.add('dark:text-white', 'dark:text-gray-300', 'dark:text-gray-400');
            });
            
            const inputs = modalContent.querySelectorAll('input, textarea');
            inputs.forEach(el => {
                el.classList.remove('bg-white', 'border-gray-300');
                el.classList.add('dark:bg-gray-700', 'dark:border-gray-600');
            });
        }
    }
}
</script>
@endsection
