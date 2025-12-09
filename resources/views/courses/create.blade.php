<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly - Nueva Materia</title>
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
        
        .color-option {
            transition: all 0.2s ease;
        }
        
        .color-option:hover {
            transform: scale(1.1);
        }
        
        .color-option.selected {
            border: 3px solid #4f46e5;
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
                            <h2 class="text-xl font-semibold text-gray-800">Nueva Materia</h2>
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
                            <h3 class="text-lg font-medium text-gray-900">Información de la Materia</h3>
                            <p class="text-sm text-gray-500 mt-1">Completa todos los campos para registrar tu nueva materia</p>
                        </div>
                        
                        <!-- Form -->
                        <form method="POST" action="{{ route('courses.store') }}" class="px-6 py-6">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nombre de la Materia -->
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Materia <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" name="name" required 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                           placeholder="Ej. Programación Web Avanzada"
                                           value="{{ old('name') }}">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Código de la Materia -->
                                <div>
                                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Código de la Materia <span class="text-red-500">*</span></label>
                                    <input type="text" id="code" name="code" required 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                           placeholder="Ej. PROG301"
                                           value="{{ old('code') }}">
                                    @error('code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Créditos -->
                                <div>
                                    <label for="credits" class="block text-sm font-medium text-gray-700 mb-1">Créditos <span class="text-red-500">*</span></label>
                                    <select id="credits" name="credits" required 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Selecciona los créditos</option>
                                        @for($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}" {{ old('credits') == $i ? 'selected' : '' }}>{{ $i }} crédito{{ $i > 1 ? 's' : '' }}</option>
                                        @endfor
                                    </select>
                                    @error('credits')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Profesor -->
                                <div>
                                    <label for="professor" class="block text-sm font-medium text-gray-700 mb-1">Profesor <span class="text-red-500">*</span></label>
                                    <input type="text" id="professor" name="professor" required 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                           placeholder="Ej. Dr. Juan Pérez"
                                           value="{{ old('professor') }}">
                                    @error('professor')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Semestre -->
                                <div>
                                    <label for="semester" class="block text-sm font-medium text-gray-700 mb-1">Semestre <span class="text-red-500">*</span></label>
                                    <select id="semester" name="semester" required 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Selecciona el semestre</option>
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="Semestre {{ $i }}" {{ old('semester') == "Semestre $i" ? 'selected' : '' }}>Semestre {{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('semester')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Horario -->
                                <div>
                                    <label for="schedule" class="block text-sm font-medium text-gray-700 mb-1">Horario <span class="text-red-500">*</span></label>
                                    <input type="text" id="schedule" name="schedule" required 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                           placeholder="Ej. Lunes y Miércoles 10:00-12:00"
                                           value="{{ old('schedule') }}">
                                    @error('schedule')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Color -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Color de la Materia <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-8 gap-3">
                                        @php
                                            $colors = [
                                                '#3B82F6', '#10B981', '#F59E0B', '#EF4444',
                                                '#8B5CF6', '#EC4899', '#06B6D4', '#84CC16',
                                                '#F97316', '#6366F1', '#14B8A6', '#F43F5E',
                                                '#A855F7', '#EAB308', '#22C55E', '#EF4444'
                                            ];
                                        @endphp
                                        @foreach($colors as $color)
                                            <div class="color-option w-10 h-10 rounded-full cursor-pointer border-2 border-gray-200" 
                                                 style="background-color: {{ $color }}"
                                                 data-color="{{ $color }}"
                                                 onclick="selectColor('{{ $color }}')"></div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="color" id="selectedColor" value="{{ old('color', '#3B82F6') }}" required>
                                    @error('color')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Descripción -->
                                <div class="md:col-span-2">
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                                    <textarea id="description" name="description" rows="4" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                              placeholder="Describe brevemente el contenido y objetivos de la materia...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
                                <a href="{{ route('courses.index') }}" 
                                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    <i class="fas fa-arrow-left mr-2"></i> Cancelar
                                </a>
                                <button type="submit" 
                                        class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-check-circle mr-2"></i> Crear Materia
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
        
        // Color selection
        function selectColor(color) {
            // Remove selected class from all color options
            document.querySelectorAll('.color-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            event.target.classList.add('selected');
            
            // Update hidden input
            document.getElementById('selectedColor').value = color;
        }
        
        // Initialize with first color selected
        document.addEventListener('DOMContentLoaded', function() {
            const firstColor = document.querySelector('.color-option');
            if (firstColor) {
                firstColor.classList.add('selected');
            }
        });
    </script>
</body>
</html> 