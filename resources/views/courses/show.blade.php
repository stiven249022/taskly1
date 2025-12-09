<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly - {{ $course->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: absolute;
                z-index: 10;
                height: 100vh;
            }
            
            .sidebar-open {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
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
                                <a href="{{ route('projects.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50">
                                    <i class="fas fa-project-diagram mr-3 text-gray-500"></i> Proyectos
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ route('courses.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md bg-indigo-50 text-indigo-700">
                                    <i class="fas fa-book mr-3 text-indigo-500"></i> Materias
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ route('calendar.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50">
                                    <i class="fas fa-calendar-alt mr-3 text-gray-500"></i> Calendario
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center">
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="User" class="w-8 h-8 rounded-full mr-2 object-cover" id="headerProfileImage">
                    <div>
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">Estudiante</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <button id="sidebarToggle" class="mr-4 text-gray-500 md:hidden">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('courses.index') }}" class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-book"></i>
                            </a>
                            <span class="text-gray-400">/</span>
                            <h2 class="text-xl font-semibold text-gray-800">{{ $course->name }}</h2>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        @if($course->user_id === auth()->id())
                        <a href="{{ route('courses.edit', $course) }}" 
                           class="px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-edit mr-2"></i> Editar
                        </a>
                        @endif
                        @if($course->user_id === auth()->id())
                        <a href="{{ route('tasks.create') }}?course={{ $course->id }}" 
                           class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-plus mr-2"></i> Nueva Tarea
                        </a>
                        @endif
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-6xl mx-auto">
                    <!-- Course Info -->
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $course->color }}"></div>
                                <h3 class="text-lg font-medium text-gray-900">Información de la Materia</h3>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ $course->name }}</h4>
                                    <div class="space-y-3">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Clase:</span>
                                            <span class="font-medium">{{ $className ?? '—' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Código:</span>
                                            <span class="font-medium">{{ $course->code }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Profesor:</span>
                                            <span class="font-medium">{{ $course->professor }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Semestre:</span>
                                            <span class="font-medium">{{ $course->semester }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Horario:</span>
                                            <span class="font-medium">{{ $course->schedule }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Créditos:</span>
                                            <span class="font-medium">{{ $course->credits }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    @if($course->description)
                                        <h5 class="text-sm font-medium text-gray-700 mb-2">Descripción:</h5>
                                        <p class="text-gray-600 text-sm">{{ $course->description }}</p>
                                    @else
                                        <p class="text-gray-500 text-sm italic">Sin descripción</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                                    <i class="fas fa-tasks text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Total Tareas</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ $course->tasks->count() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-600">
                                    <i class="fas fa-project-diagram text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Total Proyectos</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ $course->projects->count() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                    <i class="fas fa-bell text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Total Recordatorios</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ $course->reminders->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Tasks -->
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-medium text-gray-900">Tareas Recientes</h3>
                        </div>
                        
                        <div class="p-6">
                            @if($course->tasks->count() > 0)
                                <div class="space-y-4">
                                    @foreach($course->tasks->take(5) as $task)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-3 {{ $task->status === 'completed' ? 'bg-green-500' : 'bg-yellow-500' }}"></div>
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $task->title }}</h4>
                                                @if($task->due_date)
                                                <p class="text-sm text-gray-500">Vence: {{ $task->due_date->format('d/m/Y H:i') }}</p>
                                                @else
                                                <p class="text-sm text-gray-500">Sin fecha límite</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : 
                                                   ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                            <a href="{{ route('tasks.edit', $task) }}" class="text-indigo-600 hover:text-indigo-800">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                @if($course->tasks->count() > 5)
                                    <div class="mt-4 text-center">
                                        <a href="{{ route('tasks.index') }}?course={{ $course->id }}" 
                                           class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                            Ver todas las tareas
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-tasks text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">No hay tareas para esta materia</p>
                                    <a href="{{ route('tasks.create') }}?course={{ $course->id }}" 
                                       class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                        <i class="fas fa-plus mr-2"></i> Crear primera tarea
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('sidebar-open');
        });
    </script>
</body>
</html> 
