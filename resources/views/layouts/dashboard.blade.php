<!DOCTYPE html>
<html lang="es" class="{{ auth()->user()->dark_mode ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Taskly')</title>
    
    <!-- Preload critical resources -->
    <link rel="preload" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom Dark Mode CSS -->
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
    
    <!-- Tailwind CSS - Optimizado -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Dark Mode Script -->
    <script>
        // Función para aplicar el modo oscuro
        function applyDarkMode() {
            const localStorageDarkMode = localStorage.getItem('darkMode');
            const serverDarkMode = {{ auth()->user()->dark_mode ? 'true' : 'false' }};
            
            // Priorizar localStorage si existe (es la preferencia más reciente del usuario)
            // Si no existe, usar la del servidor
            let shouldBeDark;
            if (localStorageDarkMode !== null) {
                shouldBeDark = localStorageDarkMode === 'true';
                // Sincronizar con el servidor en segundo plano si difiere
                if (shouldBeDark !== serverDarkMode) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (csrfToken) {
                        fetch('/profile/preferences', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({ dark_mode: shouldBeDark })
                        }).catch(err => console.error('Error sincronizando modo oscuro:', err));
                    }
                }
            } else {
                // Si no hay localStorage, usar la del servidor
                shouldBeDark = serverDarkMode;
                localStorage.setItem('darkMode', serverDarkMode.toString());
            }
            
            if (shouldBeDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        // Aplicar modo oscuro inmediatamente para evitar flash
        applyDarkMode();

        // Escuchar cambios en la preferencia del sistema (solo si no hay preferencia guardada)
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            if (localStorage.getItem('darkMode') === null) {
                applyDarkMode();
            }
        });
    </script>
    
    <script>
        // Configuración de Tailwind optimizada
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            'bg-primary': '#1a1a1a',
                            'bg-secondary': '#2d2d2d',
                            'text-primary': '#ffffff',
                            'text-secondary': '#e5e5e5',
                            'border': '#404040'
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- CSS crítico inline para mejorar velocidad -->
    <style>
        /* Estilos críticos para modo oscuro */
        .dark { color-scheme: dark; }
        .dark body { background-color: #111827; color: #f9fafb; }
        .dark .bg-white { background-color: #1f2937; }
        .dark .bg-gray-50 { background-color: #1f2937; }
        .dark .bg-gray-100 { background-color: #374151; }
        .dark .text-gray-800 { color: #f9fafb; }
        .dark .text-gray-700 { color: #e5e7eb; }
        .dark .text-gray-600 { color: #d1d5db; }
        .dark .text-gray-500 { color: #9ca3af; }
        .dark .text-gray-400 { color: #6b7280; }
        .dark .border-gray-200 { border-color: #374151; }
        .dark .border-gray-300 { border-color: #4b5563; }
        .dark .hover\:bg-gray-50:hover { background-color: #374151; }
        .dark .hover\:bg-gray-100:hover { background-color: #4b5563; }
        .dark .bg-indigo-50 { background-color: #312e81; }
        .dark .bg-indigo-100 { background-color: #4338ca; }
        .dark .text-indigo-700 { color: #a5b4fc; }
        .dark .text-indigo-500 { color: #818cf8; }
        .dark .bg-blue-50 { background-color: #1e3a8a; }
        .dark .bg-green-50 { background-color: #14532d; }
        .dark .bg-purple-50 { background-color: #581c87; }
        .dark .shadow { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3), 0 1px 2px 0 rgba(0, 0, 0, 0.2); }
        .dark .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2); }
        
        /* Config Panel Styles */
        .config-panel {
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100vh;
            background: white;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            transition: right 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }
        .dark .config-panel { background: #2d2d2d; color: #ffffff; }
        .config-panel.open { right: 0; }
        .config-panel-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .dark .config-panel-header { border-bottom-color: #404040; }
        .config-panel-body { padding: 1.5rem; }
        .config-section { margin-bottom: 2rem; }
        .config-section h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #374151;
        }
        .dark .config-section h3 { color: #e5e5e5; }
        .config-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .dark .config-item { border-bottom-color: #404040; }
        .config-item:last-child { border-bottom: none; }
        
        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .toggle-slider { background-color: #4f46e5; }
        input:checked + .toggle-slider:before { transform: translateX(26px); }
        
        /* Notifications Dropdown */
        .notifications-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            width: 320px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            z-index: 50;
            margin-top: 0.5rem;
        }
        .dark .notifications-dropdown { background: #2d2d2d; border-color: #404040; }
        .notification-item.unread { background-color: #fef3c7; }
        .dark .notification-item.unread { background-color: #451a03; }
        
        /* Progress Bar */
        .progress-bar {
            width: 100%;
            height: 8px;
            background-color: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }
        .dark .progress-bar { background-color: #404040; }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4f46e5, #6366f1);
            transition: width 0.3s ease;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.open { transform: translateX(0); }
            .config-panel { width: 100%; right: -100%; }
        }
        
        /* Optimizaciones de rendimiento */
        .sidebar { will-change: transform; }
        .config-panel { will-change: right; }
        .notifications-dropdown { will-change: opacity; }
        
        /* Loading states */
        .loading { opacity: 0.6; pointer-events: none; }
        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid #f3f4f6;
            border-top: 2px solid #4f46e5;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-white w-64 border-r border-gray-200 flex flex-col dark:bg-gray-800 dark:border-gray-700">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-2xl font-bold text-indigo-600 flex items-center dark:text-indigo-400">
                    <i class="fas fa-graduation-cap mr-2"></i> Taskly
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Organiza tu éxito académico</p>
            </div>
            
            <div class="flex-1 overflow-y-auto">
                <nav class="p-4">
                    @if(auth()->user()->hasRole('teacher'))
                        <!-- Menú para Profesores -->
                        <div class="mb-6">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Enseñanza</h3>
                            <ul>
                                <li class="mb-1">
                                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        <i class="fas fa-tachometer-alt mr-3 {{ request()->routeIs('dashboard') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Dashboard
                                    </a>
                                </li>
                                <li class="mb-1">
                                    <a href="{{ route('teacher.students') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('teacher.students') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        <i class="fas fa-users mr-3 {{ request()->routeIs('teacher.students') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Mis Estudiantes
                                    </a>
                                </li>
                                <li class="mb-1">
                                    <a href="{{ route('teacher.student-tasks') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('teacher.student-tasks.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        <i class="fas fa-tasks mr-3 {{ request()->routeIs('teacher.student-tasks.*') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Tareas de Estudiantes
                                    </a>
                                </li>
                                <li class="mb-1">
                                    <a href="{{ route('teacher.student-projects') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('teacher.student-projects.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        <i class="fas fa-project-diagram mr-3 {{ request()->routeIs('teacher.student-projects.*') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Proyectos de Estudiantes
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Reportes</h3>
                            <ul>
                                <li class="mb-1">
                                    <a href="{{ route('teacher.grades') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('teacher.grades.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        <i class="fas fa-chart-line mr-3 {{ request()->routeIs('teacher.grades.*') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Calificaciones
                                    </a>
                                </li>
                                <li class="mb-1">
                                    <a href="{{ route('teacher.analytics') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('teacher.analytics.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        <i class="fas fa-chart-bar mr-3 {{ request()->routeIs('teacher.analytics.*') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Análisis
                                    </a>
                                </li>
                                <li class="mb-1">
                                    <a href="{{ route('teacher.create-task') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('teacher.create-task.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        <i class="fas fa-plus-circle mr-3 {{ request()->routeIs('teacher.create-task.*') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Crear Tarea
                                    </a>
                                </li>
                                <li class="mb-1">
                                    <a href="{{ route('teacher.create-project') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('teacher.create-project.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        <i class="fas fa-project-diagram mr-3 {{ request()->routeIs('teacher.create-project.*') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Crear Proyecto
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @else
                        <!-- Menú para Estudiantes -->
                        <div class="mb-6">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Menú</h3>
                            <ul>
                                <li class="mb-1">
                                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        <i class="fas fa-tachometer-alt mr-3 {{ request()->routeIs('dashboard') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Dashboard
                                    </a>
                                </li>
                                <li class="mb-1">
                                    <a href="{{ route('tasks.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('tasks.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        <i class="fas fa-tasks mr-3 {{ request()->routeIs('tasks.*') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Tareas
                                    </a>
                                </li>
                                <li class="mb-1">
                                    <a href="{{ route('projects.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('projects.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        <i class="fas fa-project-diagram mr-3 {{ request()->routeIs('projects.*') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Proyectos
                                    </a>
                                </li>
                                <li class="mb-1">
                                    <a href="{{ route('courses.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('courses.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        <i class="fas fa-book mr-3 {{ request()->routeIs('courses.*') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Materias
                                    </a>
                                </li>
                                <li class="mb-1">
                                    <a href="{{ route('calendar.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('calendar.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        <i class="fas fa-calendar-alt mr-3 {{ request()->routeIs('calendar.*') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Calendario
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif
                    
                    <div class="mb-6">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Mis Cursos</h3>
                        <ul>
                            @php
                                $user = auth()->user();
                                $ownCourses = $user->courses ?? collect();
                                
                                // Obtener cursos asignados para estudiantes y profesores
                                if ($user->isStudent() || $user->isTeacher()) {
                                    $assignedCourseIds = \App\Models\TeacherStudentAssignment::where($user->isStudent() ? 'student_id' : 'teacher_id', $user->id)
                                        ->where('status', 'active')
                                        ->whereNotNull('course_id')
                                        ->pluck('course_id')
                                        ->unique()
                                        ->toArray();
                                    
                                    $assignedCourses = \App\Models\Course::whereIn('id', $assignedCourseIds)->get();
                                    $allCourses = $ownCourses->merge($assignedCourses)->unique('id');
                                } else {
                                    $allCourses = $ownCourses;
                                }
                            @endphp
                            @foreach($allCourses as $course)
                            <li class="mb-1">
                                <a href="{{ route('courses.show', $course) }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                                    <span class="w-2 h-2 mr-3 rounded-full" style="background-color: {{ $course->color ?? '#10b981' }}"></span> {{ $course->name }}
                                </a>
                            </li>
                            @endforeach
                            @if($allCourses->isEmpty())
                            <li class="text-xs text-gray-400 dark:text-gray-500 px-3 py-2">No hay cursos</li>
                            @endif
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Etiquetas</h3>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Examen</span>
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Tarea</span>
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Proyecto</span>
                            <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">Lectura</span>
                        </div>
                    </div>
                </nav>
            </div>
            
            @include('components.user-profile')
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <button id="sidebarToggle" class="mr-4 text-gray-500 md:hidden dark:text-gray-400">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">@yield('page-title', 'Dashboard')</h2>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button id="notificationsBtn" class="p-1 text-gray-500 hover:text-gray-700 relative dark:text-gray-400 dark:hover:text-gray-200">
                                <i class="fas fa-bell"></i>
                                <span id="notificationBadge" class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-red-500 text-white text-xs flex items-center justify-center {{ auth()->user()->unreadNotifications()->count() > 0 ? '' : 'hidden' }}">
                                    {{ auth()->user()->unreadNotifications()->count() > 9 ? '9+' : auth()->user()->unreadNotifications()->count() }}
                                </span>
                            </button>
                            
                            <!-- Notifications Dropdown -->
                            <div id="notificationsDropdown" class="notifications-dropdown hidden absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50 dark:bg-gray-800 dark:ring-gray-700">
                                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Notificaciones</h3>
                                        <button id="markAllAsRead" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Marcar todas como leídas</button>
                                    </div>
                                </div>
                                <div id="notificationsList" class="divide-y divide-gray-200 dark:divide-gray-700 max-h-96 overflow-y-auto">
                                    @forelse(auth()->user()->unreadNotifications()->take(5) as $notification)
                                    <div class="notification-item {{ $notification->read_at ? '' : 'unread' }} p-4 hover:bg-gray-50 cursor-pointer dark:hover:bg-gray-700" data-id="{{ $notification->id }}">
                                        <div class="flex">
                                            <div class="flex-shrink-0 mr-3">
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 dark:bg-indigo-900 dark:text-indigo-400">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ $notification->data['title'] ?? 'Notificación' }}</p>
                                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                    @if($notification->data['type'] ?? '' === 'grade')
                                                        Tu {{ $notification->data['item_type'] ?? 'tarea' }} ha sido calificada con una nota de <strong class="text-indigo-600 dark:text-indigo-400">{{ number_format($notification->data['grade'] ?? 0, 0) }}</strong>.
                                                        @if($notification->data['feedback'] ?? null)
                                                            <br><span class="text-xs italic">{{ $notification->data['feedback'] }}</span>
                                                        @endif
                                                    @else
                                                        {{ $notification->data['message'] ?? '' }}
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1 dark:text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-bell text-2xl mb-2 text-gray-300 dark:text-gray-600"></i>
                                        <p class="text-sm">No tienes notificaciones</p>
                                    </div>
                                    @endforelse
                                </div>
                                <div class="p-3 border-t border-gray-200 text-center dark:border-gray-700">
                                    <a href="{{ route('notifications.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">Ver todas las notificaciones</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <form action="{{ route('search') }}" method="GET">
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400 dark:text-gray-500"></i>
                            </form>
                        </div>
                        
                        <button id="darkModeBtn" 
                                data-dark-mode-button
                                class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 mr-2">
                            <i class="fas fa-sun dark:hidden"></i>
                            <i class="fas fa-moon hidden dark:inline"></i>
                        </button>
                        
                        <button id="configBtn" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <i class="fas fa-cog"></i>
                        </button>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Config Panel -->
    <div id="configPanel" class="config-panel">
        <div class="config-panel-header">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Configuración</h2>
            <button id="closeConfigBtn" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="config-panel-body">
            <div class="config-section">
                <h3>Preferencias</h3>
                <div class="config-item">
                    
                    <label class="toggle-switch">
                        <input type="checkbox" 
                               id="darkModeToggle" 
                               data-dark-mode-toggle
                               {{ auth()->user()->dark_mode ? 'checked' : '' }}>
                        
                    </label>
                </div>
                <div class="config-item">
                    <span>Notificaciones Email</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="emailNotificationsToggle" {{ auth()->user()->email_notifications ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="config-item">
                    <span>Notificaciones Push</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="pushNotificationsToggle" {{ auth()->user()->push_notifications ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="config-item">
                    <span>Recordatorios Tareas</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="taskRemindersToggle" {{ auth()->user()->task_reminders ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="config-item">
                    <span>Fechas Límite Proyectos</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="projectDeadlinesToggle" {{ auth()->user()->project_deadlines ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="config-item">
                    <span>Recordatorios Exámenes</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="examRemindersToggle" {{ auth()->user()->exam_reminders ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript optimizado -->
    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('open');
        });

        // Notifications Dropdown
        document.getElementById('notificationsBtn')?.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = document.getElementById('notificationsDropdown');
            dropdown.classList.toggle('hidden');
        });

        // Close notifications dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationsDropdown');
            const button = document.getElementById('notificationsBtn');
            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Cargar notificaciones de eventos próximos
        function loadUpcomingEvents() {
            fetch('{{ route("notifications.upcoming-events") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.events && data.events.length > 0) {
                    // Actualizar badge si hay eventos próximos
                    updateNotificationBadge();
                }
            })
            .catch(error => console.error('Error al cargar eventos próximos:', error));
        }

        // Función para actualizar el badge de notificaciones
        function updateNotificationBadge() {
            fetch('{{ route("notifications.unread") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notificationBadge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count > 9 ? '9+' : data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
                
                // Actualizar lista de notificaciones
                updateNotificationsList(data.notifications);
            })
            .catch(error => console.error('Error al actualizar notificaciones:', error));
        }

        // Función para actualizar la lista de notificaciones en el dropdown
        function updateNotificationsList(notifications) {
            const notificationsList = document.getElementById('notificationsList');
            if (!notificationsList) return;

            if (notifications.length === 0) {
                notificationsList.innerHTML = `
                    <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-bell text-2xl mb-2 text-gray-300 dark:text-gray-600"></i>
                        <p class="text-sm">No tienes notificaciones</p>
                    </div>
                `;
                return;
            }

            let html = '';
            notifications.forEach(notification => {
                const data = notification.data || {};
                const isRead = notification.read_at !== null;
                const icon = data.icon || 'fas fa-exclamation-circle';
                const color = data.color || '#6366F1';
                
                html += `
                    <div class="notification-item ${isRead ? '' : 'unread'} p-4 hover:bg-gray-50 cursor-pointer dark:hover:bg-gray-700" data-id="${notification.id}">
                        <div class="flex">
                            <div class="flex-shrink-0 mr-3">
                                <div class="h-10 w-10 rounded-full flex items-center justify-center text-white" style="background-color: ${color};">
                                    <i class="${icon}"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">${data.title || 'Notificación'}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    ${data.type === 'grade' 
                                        ? `Tu ${data.item_type || 'tarea'} ha sido calificada con una nota de <strong class="text-indigo-600 dark:text-indigo-400">${Math.round(data.grade || 0)}</strong>.${data.feedback ? '<br><span class="text-xs italic">' + data.feedback + '</span>' : ''}` 
                                        : (data.message || '')}
                                </p>
                                <p class="text-xs text-gray-400 mt-1 dark:text-gray-500">${formatTime(notification.created_at)}</p>
                            </div>
                        </div>
                    </div>
                `;
            });

            notificationsList.innerHTML = html;

            // Agregar event listeners para marcar como leído
            notificationsList.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function() {
                    const notificationId = this.getAttribute('data-id');
                    if (notificationId && !this.classList.contains('read')) {
                        markAsRead(notificationId);
                    }
                    
                    // Navegar a la URL si existe
                    const notification = notifications.find(n => n.id == notificationId);
                    if (notification && notification.data && notification.data.url) {
                        window.location.href = notification.data.url;
                    }
                });
            });
        }

        // Función para marcar notificación como leída
        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = document.querySelector(`[data-id="${notificationId}"]`);
                    if (item) {
                        item.classList.remove('unread');
                        item.classList.add('read');
                    }
                    updateNotificationBadge();
                }
            })
            .catch(error => console.error('Error al marcar como leído:', error));
        }

        // Función para formatear fecha
        function formatTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000); // diferencia en segundos

            if (diff < 60) return 'Hace un momento';
            if (diff < 3600) return `Hace ${Math.floor(diff / 60)} minutos`;
            if (diff < 86400) return `Hace ${Math.floor(diff / 3600)} horas`;
            if (diff < 604800) return `Hace ${Math.floor(diff / 86400)} días`;
            
            return date.toLocaleDateString('es-ES', { 
                day: 'numeric', 
                month: 'short', 
                year: 'numeric' 
            });
        }

        // Marcar todas como leídas
        document.getElementById('markAllAsRead')?.addEventListener('click', function(e) {
            e.stopPropagation();
            fetch('{{ route("notifications.mark-all-as-read") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateNotificationBadge();
                }
            })
            .catch(error => console.error('Error:', error));
        });

        // Cargar notificaciones al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            updateNotificationBadge();
            loadUpcomingEvents();
            
            // Actualizar cada 5 minutos
            setInterval(function() {
                updateNotificationBadge();
                loadUpcomingEvents();
            }, 300000); // 5 minutos
        });


        // Config Panel
        document.getElementById('configBtn')?.addEventListener('click', function() {
            document.getElementById('configPanel').classList.add('open');
        });

        document.getElementById('closeConfigBtn')?.addEventListener('click', function() {
            document.getElementById('configPanel').classList.remove('open');
        });

        // Close config panel when clicking outside
        document.addEventListener('click', function(event) {
            const panel = document.getElementById('configPanel');
            const button = document.getElementById('configBtn');
            if (panel && !panel.contains(event.target) && !button.contains(event.target)) {
                panel.classList.remove('open');
            }
        });

        // Config toggles
        document.getElementById('darkModeToggle')?.addEventListener('change', function() {
            updateConfig();
        });

        document.getElementById('emailNotificationsToggle')?.addEventListener('change', function() {
            updateConfig();
        });

        document.getElementById('pushNotificationsToggle')?.addEventListener('change', function() {
            updateConfig();
        });

        document.getElementById('taskRemindersToggle')?.addEventListener('change', function() {
            updateConfig();
        });

        document.getElementById('projectDeadlinesToggle')?.addEventListener('change', function() {
            updateConfig();
        });

        document.getElementById('examRemindersToggle')?.addEventListener('change', function() {
            updateConfig();
        });


        function updateConfig() {
            const config = {
                dark_mode: document.getElementById('darkModeToggle').checked,
                email_notifications: document.getElementById('emailNotificationsToggle').checked,
                push_notifications: document.getElementById('pushNotificationsToggle').checked,
                task_reminders: document.getElementById('taskRemindersToggle').checked,
                project_deadlines: document.getElementById('projectDeadlinesToggle').checked,
                exam_reminders: document.getElementById('examRemindersToggle').checked
            };

            fetch('/profile/preferences', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(config)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Aplicar modo oscuro inmediatamente
                    if (config.dark_mode) {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('darkMode', 'true');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('darkMode', 'false');
                    }
                    console.log('Dark mode updated:', config.dark_mode);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>

    <!-- Incluir funciones comunes de la aplicación -->
    @if(file_exists(public_path('js/app.js')))
        <script src="{{ asset('js/app.js') }}"></script>
    @endif

    <!-- Dark Mode Manager -->
    <script src="{{ asset('js/dark-mode.js') }}"></script>

    <!-- Incluir modal de confirmación de eliminación -->
    @include('components.delete-confirmation-modal')

    <!-- Incluir mensajes flash -->
    @include('components.flash-messages')
</body>
</html> 