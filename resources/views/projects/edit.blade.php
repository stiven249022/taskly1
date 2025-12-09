<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly - Editar Proyecto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }
        
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
        
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Estilos para el editor de descripción */
        .editor-toolbar {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem 0.375rem 0 0;
            background-color: #f9fafb;
            padding: 0.5rem;
        }
        
        .editor-content {
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 0.375rem 0.375rem;
            min-height: 150px;
            padding: 1rem;
        }
        
        .editor-content:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 1px var(--primary);
        }
        
        /* Estilos para los checkboxes personalizados */
        .custom-checkbox input:checked ~ .checkmark {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
        
        .custom-checkbox input:checked ~ .checkmark:after {
            display: block;
        }
        
        .custom-checkbox .checkmark:after {
            content: "";
            position: absolute;
            display: none;
            left: 5px;
            top: 0px;
            width: 4px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
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
                                <a href="{{ route('projects.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md bg-indigo-50 text-indigo-700">
                                    <i class="fas fa-project-diagram mr-3 text-indigo-500"></i> Proyectos
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
                            @foreach(auth()->user()->courses as $course)
                            <li class="mb-1">
                                <a href="{{ route('courses.show', $course) }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50">
                                    <div class="w-2 h-2 mr-3 rounded-full" style="background-color: {{ $course->color ?? '#10b981' }}"></div>
                                    {{ $course->name }}
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
            
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center">
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full mr-2">
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
                            <a href="{{ route('projects.index') }}" class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-project-diagram"></i>
                            </a>
                            <span class="text-gray-400">/</span>
                            <h2 class="text-xl font-semibold text-gray-800">Editar Proyecto</h2>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button class="p-1 text-gray-500 hover:text-gray-700">
                                <i class="fas fa-bell"></i>
                                <span class="absolute top-0 right-0 w-2 h-2 rounded-full bg-red-500"></span>
                            </button>
                        </div>
                        <div class="relative">
                            <input type="text" placeholder="Buscar..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-4xl mx-auto">
                    <!-- Resumen de Proyectos -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                    <i class="fas fa-project-diagram text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Proyectos Activos</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->projects()->where('status', 'active')->count() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-600">
                                    <i class="fas fa-check-circle text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Completados</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->projects()->where('status', 'completed')->count() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-red-100 text-red-600">
                                    <i class="fas fa-exclamation-triangle text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Atrasados</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->projects()->where('status', 'active')->where('end_date', '<', now())->count() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                    <i class="fas fa-chart-line text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Progreso Promedio</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->projects()->count() > 0 ? round(auth()->user()->projects()->avg('progress')) : 0 }}%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Container -->
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                        <!-- Form Header -->
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-medium text-gray-900">Editar Información del Proyecto</h3>
                            <p class="text-sm text-gray-500 mt-1">Modifica los campos que necesites actualizar</p>
                        </div>
                        
                        <!-- Errores de validación -->
                        @if ($errors->any())
                            <div class="px-6 py-4 bg-red-50 border-b border-red-200">
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                    <strong>¡Ups! Hay problemas con tu entrada:</strong>
                                    <ul class="mt-2 list-disc list-inside text-sm">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Form -->
                        <form id="projectForm" class="p-6" action="{{ route('projects.update', $project) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nombre del Proyecto -->
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Proyecto <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $project->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ej. Sistema de gestión escolar">
                                    <p class="mt-1 text-sm text-gray-500">Un nombre descriptivo que identifique claramente tu proyecto.</p>
                                    @error('name')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                
                                <!-- Curso/Materia -->
                                <div>
                                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Curso/Materia <span class="text-red-500">*</span></label>
                                    <select id="course_id" name="course_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Selecciona un curso</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ old('course_id', $project->course_id) == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('course_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                
                                <!-- Prioridad -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div class="cursor-pointer">
                                            <input type="radio" id="priorityLow" name="priority" value="low" class="hidden peer" {{ old('priority', $project->priority) == 'low' ? 'checked' : '' }}>
                                            <label for="priorityLow" class="block p-3 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50">
                                                <div class="flex items-center justify-between">
                                                    <h3 class="font-medium text-gray-900 text-sm">Baja</h3>
                                                    <i class="fas fa-check-circle text-green-500 hidden peer-checked:inline"></i>
                                                </div>
                                            </label>
                                        </div>
                                        
                                        <div class="cursor-pointer">
                                            <input type="radio" id="priorityMedium" name="priority" value="medium" class="hidden peer" {{ old('priority', $project->priority) == 'medium' ? 'checked' : '' }}>
                                            <label for="priorityMedium" class="block p-3 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-yellow-500 peer-checked:bg-yellow-50 hover:bg-gray-50">
                                                <div class="flex items-center justify-between">
                                                    <h3 class="font-medium text-gray-900 text-sm">Media</h3>
                                                    <i class="fas fa-check-circle text-yellow-500 hidden peer-checked:inline"></i>
                                                </div>
                                            </label>
                                        </div>
                                        
                                        <div class="cursor-pointer">
                                            <input type="radio" id="priorityHigh" name="priority" value="high" class="hidden peer" {{ old('priority', $project->priority) == 'high' ? 'checked' : '' }}>
                                            <label for="priorityHigh" class="block p-3 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-red-500 peer-checked:bg-red-50 hover:bg-gray-50">
                                                <div class="flex items-center justify-between">
                                                    <h3 class="font-medium text-gray-900 text-sm">Alta</h3>
                                                    <i class="fas fa-check-circle text-red-500 hidden peer-checked:inline"></i>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    @error('priority')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                
                                <!-- Descripción -->
                                <div class="md:col-span-2">
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                                    <div class="editor-container">
                                        <div class="editor-toolbar flex space-x-1">
                                            <button type="button" class="p-1 text-gray-600 hover:text-indigo-600 hover:bg-gray-100 rounded">
                                                <i class="fas fa-bold"></i>
                                            </button>
                                            <button type="button" class="p-1 text-gray-600 hover:text-indigo-600 hover:bg-gray-100 rounded">
                                                <i class="fas fa-italic"></i>
                                            </button>
                                            <button type="button" class="p-1 text-gray-600 hover:text-indigo-600 hover:bg-gray-100 rounded">
                                                <i class="fas fa-underline"></i>
                                            </button>
                                            <button type="button" class="p-1 text-gray-600 hover:text-indigo-600 hover:bg-gray-100 rounded">
                                                <i class="fas fa-list-ul"></i>
                                            </button>
                                            <button type="button" class="p-1 text-gray-600 hover:text-indigo-600 hover:bg-gray-100 rounded">
                                                <i class="fas fa-list-ol"></i>
                                            </button>
                                        </div>
                                        <div id="description" class="editor-content" contenteditable="true" placeholder="Describe los objetivos, requisitos y especificaciones de tu proyecto...">{{ old('description', $project->description) }}</div>
                                        <input type="hidden" name="description" id="description-hidden">
                                    </div>
                                    @error('description')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                
                                <!-- Etiquetas -->
                                <div class="md:col-span-2">
                                    <label for="projectTags" class="block text-sm font-medium text-gray-700 mb-1">Etiquetas</label>
                                    <div class="relative">
                                        <div class="flex flex-wrap items-center gap-2 min-h-[42px] px-3 py-2 border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500">
                                            @foreach($project->tags as $tag)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $tag->name }}
                                                <button type="button" class="ml-1.5 inline-flex text-blue-500 hover:text-blue-700">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </span>
                                            @endforeach
                                            
                                            <input type="text" id="projectTags" class="flex-1 min-w-[100px] border-none focus:outline-none focus:ring-0 text-sm" placeholder="Agregar etiqueta...">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Fechas -->
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha de inicio</label>
                                    <div class="relative">
                                        <input type="date" id="start_date" name="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('start_date', $project->start_date) }}">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <i class="fas fa-calendar text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('start_date')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha de entrega</label>
                                    <div class="relative">
                                        <input type="date" id="end_date" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('end_date', $project->end_date) }}">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <i class="fas fa-calendar text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('end_date')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                
                                <!-- Recordatorio -->
                                <div class="md:col-span-2" id="reminderToggle">
                                    <label class="flex items-center">
                                        <div class="custom-checkbox relative cursor-pointer">
                                            <input type="checkbox" class="sr-only" id="enableReminder" name="enable_reminder" {{ $project->reminder ? 'checked' : '' }}>
                                            <div class="checkmark w-5 h-5 border-2 border-gray-300 rounded-sm flex items-center justify-center">
                                            </div>
                                        </div>
                                        <span class="ml-2 text-sm font-medium text-gray-700">Recordatorio antes de la entrega</span>
                                    </label>
                                    
                                    <div id="reminderOptions" class="mt-3 ml-7 {{ $project->reminder ? '' : 'hidden' }}">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="reminderDays" class="block text-xs text-gray-500 mb-1">Días antes</label>
                                                <select id="reminderDays" name="reminder_days" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                    <option value="1" {{ $project->reminder_days == 1 ? 'selected' : '' }}>1 día antes</option>
                                                    <option value="3" {{ $project->reminder_days == 3 ? 'selected' : '' }}>3 días antes</option>
                                                    <option value="7" {{ $project->reminder_days == 7 ? 'selected' : '' }}>1 semana antes</option>
                                                    <option value="14" {{ $project->reminder_days == 14 ? 'selected' : '' }}>2 semanas antes</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="reminderTime" class="block text-xs text-gray-500 mb-1">Hora</label>
                                                <input type="time" id="reminderTime" name="reminder_time" value="{{ $project->reminder_time ?? '09:00' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botones de acción -->
                            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                                <a href="{{ route('projects.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    <i class="fas fa-arrow-left mr-2"></i> Cancelar
                                </a>
                                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-save mr-2"></i> Actualizar Proyecto
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                <div>
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">¡Proyecto actualizado con éxito!</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Tu proyecto ha sido actualizado correctamente. Los cambios han sido guardados.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6">
                    <button type="button" id="modalCloseBtn" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                        Ir al listado de proyectos
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('sidebar-open');
        });
        
        // Reminder toggle
        document.getElementById('enableReminder').addEventListener('change', function() {
            const reminderOptions = document.getElementById('reminderOptions');
            if (this.checked) {
                reminderOptions.classList.remove('hidden');
            } else {
                reminderOptions.classList.add('hidden');
            }
        });
        
        // Tags input
        document.getElementById('projectTags').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const value = this.value.trim();
                if (value) {
                    addTag(value);
                    this.value = '';
                }
            }
        });
        
        function addTag(value) {
            const container = document.getElementById('projectTags').parentNode;
            
            const newTag = document.createElement('span');
            newTag.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
            newTag.innerHTML = `${value} <button type="button" class="ml-1.5 inline-flex text-blue-500 hover:text-blue-700"><i class="fas fa-times"></i></button>`;
            
            // Insert before the input
            container.insertBefore(newTag, document.getElementById('projectTags'));
            
            // Add delete event
            newTag.querySelector('button').addEventListener('click', function() {
                newTag.remove();
            });
        }
        
        // Form submission
        document.getElementById('projectForm').addEventListener('submit', function(e) {
            // Update hidden description field
            document.getElementById('description-hidden').value = document.getElementById('description').innerHTML;
        });
        
        // Close modal
        document.getElementById('modalCloseBtn').addEventListener('click', function() {
            document.getElementById('successModal').classList.add('hidden');
            // Redirigir al listado de proyectos
            window.location.href = '{{ route("projects.index") }}';
        });
        
        // Remove tag functionality
        document.querySelectorAll('#projectTags ~ span button').forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.remove();
            });
        });
    </script>
</body>
</html> 