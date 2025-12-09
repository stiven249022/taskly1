<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly - Editar Tarea</title>
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
                                <a href="{{ route('tasks.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md bg-indigo-50 text-indigo-700">
                                    <i class="fas fa-tasks mr-3 text-indigo-500"></i> Tareas
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ route('projects.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50">
                                    <i class="fas fa-project-diagram mr-3 text-gray-500"></i> Proyectos
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ route('courses.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50">
                                    <i class="fas fa-book mr-3 text-gray-500"></i> Materias
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
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Mis Materias</h3>
                        <ul>
                            @foreach($courses as $course)
                            <li class="mb-1">
                                <a href="{{ route('courses.show', $course) }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50">
                                    <div class="w-2 h-2 mr-3 rounded-full" style="background-color: {{ $course->color }}"></div> {{ $course->name }}
                                </a>
                            </li>
                            @endforeach
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
                            <a href="{{ route('tasks.index') }}" class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-tasks"></i>
                            </a>
                            <span class="text-gray-400">/</span>
                            <h2 class="text-xl font-semibold text-gray-800">Editar Tarea</h2>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Hay errores en el formulario:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="max-w-4xl mx-auto">
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                        <!-- Form Header -->
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-medium text-gray-900">Editar Información de la Tarea</h3>
                            <p class="text-sm text-gray-500 mt-1">Modifica los campos que necesites actualizar</p>
                        </div>
                        
                        <!-- Form -->
                        <form action="{{ route('tasks.update', $task) }}" method="POST" class="px-6 py-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Título -->
                                <div class="md:col-span-2">
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título de la tarea <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" id="title" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           value="{{ old('title', $task->title) }}" required>
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tipo de Tarea -->
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Tarea <span class="text-red-500">*</span></label>
                                    <select name="type" id="type" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                        <option value="">Seleccionar tipo</option>
                                        <option value="task" {{ old('type', $task->type) == 'task' ? 'selected' : '' }}>Tarea</option>
                                        <option value="exam" {{ old('type', $task->type) == 'exam' ? 'selected' : '' }}>Examen</option>
                                        <option value="project" {{ old('type', $task->type) == 'project' ? 'selected' : '' }}>Proyecto</option>
                                        <option value="reading" {{ old('type', $task->type) == 'reading' ? 'selected' : '' }}>Lectura</option>
                                    </select>
                                    @error('type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Curso -->
                                <div>
                                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Curso <span class="text-red-500">*</span></label>
                                    <select name="course_id" id="course_id" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                        <option value="">Seleccionar curso</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" 
                                                {{ old('course_id', $task->course_id) == $course->id ? 'selected' : '' }}>
                                                {{ $course->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('course_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Estado -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado <span class="text-red-500">*</span></label>
                                    <select name="status" id="status" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                        <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completada</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Prioridad -->
                                <div>
                                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Prioridad <span class="text-red-500">*</span></label>
                                    <select name="priority" id="priority" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                        <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>Alta</option>
                                        <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Media</option>
                                        <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Baja</option>
                                    </select>
                                    @error('priority')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Fecha de inicio -->
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha de inicio <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" name="start_date" id="start_date" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           value="{{ old('start_date', $task->start_date ? $task->start_date->format('Y-m-d\TH:i') : '') }}" required>
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Fecha de entrega -->
                                <div>
                                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha de entrega <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" name="due_date" id="due_date" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '') }}" required>
                                    @error('due_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Descripción -->
                                <div class="md:col-span-2">
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                                    <textarea name="description" id="description" rows="4" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $task->description) }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
                                <a href="{{ route('tasks.index') }}" 
                                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    <i class="fas fa-arrow-left mr-2"></i> Cancelar
                                </a>
                                <button type="submit" 
                                        class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-check-circle mr-2"></i> Actualizar Tarea
                                </button>
                            </div>
                        </form>
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