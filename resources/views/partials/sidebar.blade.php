<div class="sidebar bg-white w-64 border-r border-gray-200 flex flex-col">
    <div class="p-4 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-indigo-600 flex items-center">
            <i class="fas fa-graduation-cap mr-2"></i> Taskly
        </h1>
        <p class="text-sm text-gray-500">Organiza tu éxito académico</p>
    </div>
    <div class="flex-1 overflow-y-auto">
        <nav class="p-4">
            <div class="mb-6">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Menú</h3>
                <ul>
                    <li class="mb-1">
                        <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50">
                            <i class="fas fa-tachometer-alt mr-3 text-gray-500"></i> Dashboard
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('tasks.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50">
                            <i class="fas fa-tasks mr-3 text-gray-500"></i> Tareas
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('projects.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md bg-indigo-50 text-indigo-700">
                            <i class="fas fa-project-diagram mr-3 text-indigo-500"></i> Proyectos
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('calendar.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50">
                            <i class="fas fa-calendar-alt mr-3 text-gray-500"></i> Calendario
                        </a>
                    </li>
                </ul>
            </div>
            <div class="mb-6">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Mis Cursos</h3>
                <ul>
                    @foreach($courses as $course)
                    <li class="mb-1">
                        <a href="#" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50">
                            <span class="w-2 h-2 mr-3 rounded-full bg-{{ $course->color ?? 'gray' }}-500"></span> {{ $course->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Etiquetas</h3>
                <div class="flex flex-wrap gap-2">
                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Examen</span>
                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Tarea</span>
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Proyecto</span>
                    <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">Lectura</span>
                </div>
            </div>
        </nav>
    </div>
    @include('components.user-profile')
</div> 