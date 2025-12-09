@extends('layouts.dashboard')

@section('title', 'Calificar Proyectos')
@section('page-title', 'Calificar Proyectos')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Proyectos Pendientes de Calificación</h2>
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Total: {{ $projects->total() }} proyectos
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Lista de Proyectos</h3>
        <form action="{{ route('teacher.projects') }}" method="GET" class="w-full max-w-sm">
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar proyectos..." class="w-full pl-10 pr-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </form>
    </div>
    
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($projects as $project)
        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-2">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $project->name }}</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Completado
                        </span>
                    </div>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $project->description }}</p>

                    <!-- Subtareas del proyecto -->
                    @if($project->projectTasks && $project->projectTasks->count())
                    <div class="mt-3">
                        <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Subtareas</h5>
                        <div class="space-y-2">
                            @foreach($project->projectTasks as $t)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-md p-3">
                                <div class="flex items-start justify-between">
                                    <div class="pr-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $t->title }}</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $t->getStatusColor() }}">{{ $t->getStatusText() }}</span>
                                        </div>
                                        @if($t->isSubmitted())
                                        <div class="mt-2 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded p-2">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <i class="fas fa-file text-purple-500 mr-2"></i>
                                                    <div>
                                                        <p class="text-xs text-purple-800 dark:text-purple-200">{{ $t->file_name }}</p>
                                                        <p class="text-[11px] text-purple-600 dark:text-purple-300">{{ $t->getFormattedFileSize() }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-3">
                                                    <a href="{{ route('project-tasks.view-file', [$project, $t]) }}" target="_blank" class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-200" title="Ver archivo">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('project-tasks.download-file', [$project, $t]) }}" class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-200" title="Descargar archivo">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    @if(auth()->user()->hasAnyRole(['teacher','admin']) && $t->isSubmitted())
                                    <div class="w-72">
                                        @if($t->grade !== null)
                                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded p-2">
                                                <p class="text-xs font-medium text-green-800 dark:text-green-200">Calificación</p>
                                                <p class="text-lg font-bold text-green-600 dark:text-green-300">{{ number_format($t->grade, 0) }}/100</p>
                                                @if($t->feedback)
                                                    <p class="text-xs text-green-600 dark:text-green-300 mt-1">{{ $t->feedback }}</p>
                                                @endif
                                            </div>
                                        @else
                                            <form onsubmit="return gradeSubtask(event, {{ $project->id }}, {{ $t->id }})" class="bg-gray-50 dark:bg-gray-700 rounded p-2 border border-gray-200 dark:border-gray-600">
                                                @csrf
                                                <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 mb-1">Nota (0-100)</label>
                                                <input type="number" name="grade" min="0" max="100" step="1" required class="w-full mb-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                                <textarea name="feedback" rows="2" placeholder="Comentarios (opcional)" class="w-full mb-2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-indigo-300 focus:ring focus:ring-indigo-200"></textarea>
                                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-2 py-1 rounded text-xs font-medium">
                                                    Calificar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($project->isSubmitted())
                    <div class="mt-2 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-file text-purple-500 mr-2"></i>
                                <div>
                                    <p class="text-sm text-purple-800 dark:text-purple-200">{{ $project->file_name }}</p>
                                    <p class="text-xs text-purple-600 dark:text-purple-300">{{ $project->getFormattedFileSize() }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('project-files.view', $project) }}" target="_blank" class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-200" title="Ver archivo">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('project-files.download', $project) }}" class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-200" title="Descargar archivo">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                        <div class="flex items-center">
                            <i class="fas fa-user mr-1"></i>
                            <span>{{ $project->user->name }} {{ $project->user->last_name }}</span>
                        </div>
                        @if($project->course)
                        <div class="flex items-center">
                            <i class="fas fa-book mr-1"></i>
                            <span>{{ $project->course->name }}</span>
                        </div>
                        @endif
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-1"></i>
                            <span>Completado: {{ $project->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-percentage mr-1"></i>
                            <span>Progreso: {{ $project->progress }}%</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    @php
                        $avg = $project->projectTasks->whereNotNull('submitted_at')->whereNotNull('grade')->avg('grade');
                    @endphp
                    @if(!is_null($avg))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Promedio: {{ number_format($avg, 0) }}/100
                        </span>
                    @else
                        <span class="text-xs text-gray-500 dark:text-gray-400">Sin calificaciones de subtareas</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
            <i class="fas fa-check-circle text-4xl mb-2"></i>
            <p class="text-lg font-medium">No hay proyectos pendientes de calificación</p>
            <p class="text-sm">Todos los proyectos completados han sido calificados</p>
        </div>
        @endforelse
    </div>
    
    @if($projects->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        {{ $projects->links() }}
    </div>
    @endif
</div>


<script>
function gradeSubtask(event, projectId, subtaskId) {
    event.preventDefault();
    const formData = new FormData(event.target);
    fetch(`/projects/${projectId}/tasks/${subtaskId}/grade`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => r.json())
    .then(d => { if (d.success) { location.reload(); } else { alert('Error: ' + d.message); } })
    .catch(e => { console.error(e); alert('Error al calificar la subtarea'); });
}
</script>
@endsection
