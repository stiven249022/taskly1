<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly - Nueva Tarea</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }

        .task-priority-high {
            border-left: 4px solid var(--danger);
        }

        .task-priority-medium {
            border-left: 4px solid var(--warning);
        }

        .task-priority-low {
            border-left: 4px solid var(--secondary);
        }

        .progress-bar {
            height: 8px;
            border-radius: 4px;
            background-color: #e5e7eb;
        }

        .progress-fill {
            height: 100%;
            border-radius: 4px;
            background-color: var(--primary);
            transition: width 0.3s ease;
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

        .task-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Estilos para el modal */
        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }

        .modal-container {
            transform: translateY(0);
            opacity: 1;
            transition: all 0.3s ease;
        }

        .modal-container.hidden {
            opacity: 0;
            transform: translateY(-20px);
        }

        /* Smooth transitions */
        .transition-smooth {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
                            <a href="{{ route('tasks.index') }}" class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-tasks"></i>
                            </a>
                            <span class="text-gray-400">/</span>
                            <h2 class="text-xl font-semibold text-gray-800">Nueva Tarea</h2>
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
                @if(session('warning'))
                <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-yellow-800">
                                {{ session('warning') }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

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
                            <h3 class="text-lg font-medium text-gray-900">Información de la Tarea</h3>
                            <p class="text-sm text-gray-500 mt-1">Completa todos los campos requeridos para crear tu nueva tarea</p>
                        </div>
                        
                        <!-- Form -->
                        <form method="POST" action="{{ route('tasks.store') }}" class="px-6 py-6">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Título de la tarea -->
                                <div class="md:col-span-2">
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título de la Tarea <span class="text-red-500">*</span></label>
                                    <input type="text" id="title" name="title" required 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                           placeholder="Ej. Resolver problemas de álgebra"
                                           value="{{ old('title') }}">
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Tipo de Tarea -->
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Tarea <span class="text-red-500">*</span></label>
                                    <select id="type" name="type" required 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Selecciona el tipo</option>
                                        <option value="task" {{ old('type') == 'task' ? 'selected' : '' }}>Tarea</option>
                                        <option value="exam" {{ old('type') == 'exam' ? 'selected' : '' }}>Examen</option>
                                        <option value="project" {{ old('type') == 'project' ? 'selected' : '' }}>Proyecto</option>
                                        <option value="reading" {{ old('type') == 'reading' ? 'selected' : '' }}>Lectura</option>
                                    </select>
                                    @error('type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Materia -->
                                <div>
                                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Materia <span class="text-red-500">*</span></label>
                                    <select id="course_id" name="course_id" required 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Selecciona una materia</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                                {{ $course->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('course_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Prioridad -->
                                <div>
                                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Prioridad <span class="text-red-500">*</span></label>
                                    <select id="priority" name="priority" required 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Selecciona la prioridad</option>
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Baja</option>
                                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Media</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Alta</option>
                                    </select>
                                    @error('priority')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Fecha de inicio -->
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" id="start_date" name="start_date" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           value="{{ old('start_date') }}"
                                           min="{{ now()->format('Y-m-d\TH:i') }}">
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Fecha de vencimiento -->
                                <div>
                                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Vencimiento <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" id="due_date" name="due_date" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           value="{{ old('due_date') }}"
                                           min="{{ now()->format('Y-m-d\TH:i') }}">
                                    @error('due_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Descripción -->
                                <div class="md:col-span-2">
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                                    <textarea id="description" name="description" rows="4" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                              placeholder="Describe los detalles de la tarea...">{{ old('description') }}</textarea>
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
                                    <i class="fas fa-check-circle mr-2"></i> Crear Tarea
                                </button>
                            </div>
                        </form>
                        
                        <script>
                            // Validar antes de enviar el formulario
                            document.querySelector('form').addEventListener('submit', function(e) {
                                validateCurrentTime();
                                
                                const startDateInput = document.getElementById('start_date');
                                const dueDateInput = document.getElementById('due_date');
                                const now = new Date();
                                const currentDateTime = now.toISOString().slice(0, 16);
                                
                                if (startDateInput.value < currentDateTime || dueDateInput.value < currentDateTime) {
                                    e.preventDefault();
                                    alert('No se puede crear una tarea con fechas pasadas. Por favor, ajusta las fechas.');
                                    return false;
                                }
                            });
                        </script>
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
        
        // Set default dates and validation
        document.addEventListener('DOMContentLoaded', function() {
            updateDateTimeValidation();
            
            // Actualizar validación cada minuto para mantener la hora actual
            setInterval(updateDateTimeValidation, 60000);
            
            // Validar que la fecha de vencimiento sea posterior a la fecha de inicio
            document.getElementById('start_date').addEventListener('change', function() {
                const startDateValue = this.value;
                const dueDateInput = document.getElementById('due_date');
                
                // Validar que la hora no sea posterior a la actual
                validateCurrentTime();
                
                if (startDateValue) {
                    dueDateInput.min = startDateValue;
                    
                    // Si la fecha de vencimiento es anterior a la nueva fecha de inicio, actualizarla
                    if (dueDateInput.value && dueDateInput.value < startDateValue) {
                        const newDueDate = new Date(startDateValue);
                        newDueDate.setDate(newDueDate.getDate() + 1);
                        dueDateInput.value = newDueDate.toISOString().slice(0, 16);
                    }
                }
            });
            
            // Validar que la fecha de inicio no sea posterior a la fecha de vencimiento
            document.getElementById('due_date').addEventListener('change', function() {
                const dueDateValue = this.value;
                const startDateInput = document.getElementById('start_date');
                
                // Validar que la hora no sea posterior a la actual
                validateCurrentTime();
                
                if (dueDateValue && startDateInput.value && startDateInput.value > dueDateValue) {
                    // Si la fecha de inicio es posterior a la fecha de vencimiento, ajustar la fecha de inicio
                    const newStartDate = new Date(dueDateValue);
                    newStartDate.setDate(newStartDate.getDate() - 1);
                    startDateInput.value = newStartDate.toISOString().slice(0, 16);
                }
            });
        });
        
        // Función para actualizar la validación de fecha y hora
        function updateDateTimeValidation() {
            const now = new Date();
            const tomorrow = new Date(now);
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            const currentDateTime = now.toISOString().slice(0, 16);
            const tomorrowDateTime = tomorrow.toISOString().slice(0, 16);
            
            const startDateInput = document.getElementById('start_date');
            const dueDateInput = document.getElementById('due_date');
            
            // Actualizar fecha mínima para ambos campos (incluye hora exacta)
            startDateInput.min = currentDateTime;
            dueDateInput.min = currentDateTime;
            
            // Si no hay valores establecidos, establecer valores por defecto
            if (!startDateInput.value) {
                startDateInput.value = currentDateTime;
            }
            if (!dueDateInput.value) {
                dueDateInput.value = tomorrowDateTime;
            }
            
            // Validar que los valores actuales no sean menores que la fecha/hora actual
            if (startDateInput.value && startDateInput.value < currentDateTime) {
                startDateInput.value = currentDateTime;
            }
            if (dueDateInput.value && dueDateInput.value < currentDateTime) {
                dueDateInput.value = currentDateTime;
            }
        }
        
        // Función para validar que la hora no sea posterior a la actual
        function validateCurrentTime() {
            const now = new Date();
            const currentDateTime = now.toISOString().slice(0, 16);
            
            const startDateInput = document.getElementById('start_date');
            const dueDateInput = document.getElementById('due_date');
            
            // Validar fecha de inicio
            if (startDateInput.value) {
                const selectedStartDate = new Date(startDateInput.value);
                const currentDate = new Date(currentDateTime);
                
                if (selectedStartDate < currentDate) {
                    startDateInput.value = currentDateTime;
                    alert('La fecha y hora de inicio no puede ser anterior a la fecha y hora actual.');
                }
            }
            
            // Validar fecha de vencimiento
            if (dueDateInput.value) {
                const selectedDueDate = new Date(dueDateInput.value);
                const currentDate = new Date(currentDateTime);
                
                if (selectedDueDate < currentDate) {
                    dueDateInput.value = currentDateTime;
                    alert('La fecha y hora de vencimiento no puede ser anterior a la fecha y hora actual.');
                }
            }
        }
    </script>
</body>
</html> 