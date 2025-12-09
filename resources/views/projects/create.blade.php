<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly - Crear Proyecto</title>
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
        
        /* Estilos para los checkboxes personalizados */
        .custom-checkbox input:checked ~ .checkmark {
            @apply bg-indigo-600 border-indigo-600;
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
                            @foreach($courses ?? [] as $course)
                            <li class="mb-1">
                                <a href="{{ route('courses.show', $course) }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50">
                                    <div class="w-2 h-2 mr-3 rounded-full" style="background-color: {{ $course->color }}"></div> {{ $course->name }}
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
                            <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-project-diagram"></i>
                            </a>
                            <span class="text-gray-400">/</span>
                            <h2 class="text-xl font-semibold text-gray-800">Nuevo Proyecto</h2>
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
                    <!-- Form Container -->
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                        <!-- Form Header -->
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-medium text-gray-900">Información del Proyecto</h3>
                            <p class="text-sm text-gray-500 mt-1">Completa todos los campos requeridos para crear tu nuevo proyecto</p>
                        </div>
                        
                        <!-- Mensajes de error y éxito -->
                        @if(session('error'))
                            <div class="mx-6 mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <strong class="font-bold">¡Error!</strong>
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div class="mx-6 mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <strong class="font-bold">¡Error de validación!</strong>
                                <ul class="mt-1 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <!-- Form -->
                        <form method="POST" action="{{ route('projects.store') }}" class="px-6 py-6">
                            @csrf
                            <div class="mb-6">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Proyecto <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                       placeholder="Ej. Sistema de gestión escolar">
                                <p class="mt-1 text-sm text-gray-500">Un nombre descriptivo que identifique claramente tu proyecto.</p>
                            </div>
                            
                            <div class="mb-6">
                                <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Curso/Materia <span class="text-red-500">*</span></label>
                                <select id="course_id" name="course_id" required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="" disabled selected>Selecciona un curso</option>
                                    @foreach($courses ?? [] as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="cursor-pointer">
                                        <input type="radio" id="priority_low" name="priority" value="low" class="hidden peer" required>
                                        <label for="priority_low" class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50">
                                            <div class="flex items-center justify-between">
                                                <h3 class="font-medium text-gray-900">Baja</h3>
                                                <i class="fas fa-check-circle text-green-500 hidden peer-checked:inline"></i>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">Sin prisa, puedes tomarte tu tiempo</p>
                                        </label>
                                    </div>
                                    
                                    <div class="cursor-pointer">
                                        <input type="radio" id="priority_medium" name="priority" value="medium" class="hidden peer">
                                        <label for="priority_medium" class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-yellow-500 peer-checked:bg-yellow-50 hover:bg-gray-50">
                                            <div class="flex items-center justify-between">
                                                <h3 class="font-medium text-gray-900">Media</h3>
                                                <i class="fas fa-check-circle text-yellow-500 hidden peer-checked:inline"></i>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">Importante pero no urgente</p>
                                        </label>
                                    </div>
                                    
                                    <div class="cursor-pointer">
                                        <input type="radio" id="priority_high" name="priority" value="high" class="hidden peer">
                                        <label for="priority_high" class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-red-500 peer-checked:bg-red-50 hover:bg-gray-50">
                                            <div class="flex items-center justify-between">
                                                <h3 class="font-medium text-gray-900">Alta</h3>
                                                <i class="fas fa-check-circle text-red-500 hidden peer-checked:inline"></i>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">Urgente y muy importante</p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                                <textarea id="description" name="description" rows="4" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                          placeholder="Describe los objetivos, requisitos y especificaciones de tu proyecto..."></textarea>
                            </div>
                            
                            <div class="mb-6">
                                <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Etiquetas</label>
                                <input type="text" id="tags" name="tags" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                       placeholder="Etiquetas separadas por comas (ej: proyecto, investigación, presentación)">
                                <p class="mt-1 text-sm text-gray-500">Separa las etiquetas con comas</p>
                            </div>
                            
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fechas</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="start_date" class="block text-xs text-gray-500 mb-1">Fecha de inicio</label>
                                        <div class="relative">
                                            <input type="date" id="start_date" name="start_date" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <i class="fas fa-calendar text-gray-400"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="end_date" class="block text-xs text-gray-500 mb-1">Fecha de entrega</label>
                                        <div class="relative">
                                            <input type="date" id="end_date" name="end_date" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <i class="fas fa-calendar text-gray-400"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-6" id="reminderToggle">
                                <label class="flex items-center">
                                    <div class="custom-checkbox relative cursor-pointer">
                                        <input type="checkbox" class="sr-only" id="enable_reminder" name="enable_reminder" value="1">
                                        <div class="checkmark w-5 h-5 border-2 border-gray-300 rounded-sm flex items-center justify-center">
                                        </div>
                                    </div>
                                    <span class="ml-2 text-sm font-medium text-gray-700">Recordatorio antes de la entrega</span>
                                </label>
                                
                                <div id="reminderOptions" class="mt-3 ml-7 hidden">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="reminder_days" class="block text-xs text-gray-500 mb-1">Días antes</label>
                                            <select id="reminder_days" name="reminder_days" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="1">1 día antes</option>
                                                <option value="3">3 días antes</option>
                                                <option value="7">1 semana antes</option>
                                                <option value="14">2 semanas antes</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="reminder_time" class="block text-xs text-gray-500 mb-1">Hora</label>
                                            <input type="time" id="reminder_time" name="reminder_time" value="09:00" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-between">
                                <a href="{{ route('dashboard') }}" 
                                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    <i class="fas fa-arrow-left mr-2"></i> Cancelar
                                </a>
                                <div class="flex items-center gap-2">
                                    <button type="submit" 
                                            class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-check-circle mr-2"></i> Crear Proyecto
                                    </button>
                                    @if(auth()->user()->hasAnyRole(['teacher','admin']))
                                    <input type="hidden" name="create_subtasks" id="create_subtasks" value="">
                                    <button type="button"
                                            onclick="document.getElementById('create_subtasks').value = '1'; this.closest('form').submit();"
                                            class="px-6 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                        <i class="fas fa-tasks mr-2"></i> Crear Subtareas
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('sidebar-open');
        });
        
        // Reminder toggle
        document.getElementById('enable_reminder').addEventListener('change', function() {
            const reminderOptions = document.getElementById('reminderOptions');
            if (this.checked) {
                reminderOptions.classList.remove('hidden');
            } else {
                reminderOptions.classList.add('hidden');
            }
        });
        
        // Priority radio buttons visual feedback
        document.querySelectorAll('input[name="priority"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove all checked styles
                document.querySelectorAll('input[name="priority"]').forEach(r => {
                    r.nextElementSibling.classList.remove('peer-checked:border-green-500', 'peer-checked:border-yellow-500', 'peer-checked:border-red-500', 'peer-checked:bg-green-50', 'peer-checked:bg-yellow-50', 'peer-checked:bg-red-50');
                });
                
                // Add checked style based on value
                if (this.value === 'low') {
                    this.nextElementSibling.classList.add('peer-checked:border-green-500', 'peer-checked:bg-green-50');
                } else if (this.value === 'medium') {
                    this.nextElementSibling.classList.add('peer-checked:border-yellow-500', 'peer-checked:bg-yellow-50');
                } else if (this.value === 'high') {
                    this.nextElementSibling.classList.add('peer-checked:border-red-500', 'peer-checked:bg-red-50');
                }
            });
        });
    </script>
</body>
</html> 
