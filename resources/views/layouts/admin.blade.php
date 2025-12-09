<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ auth()->user()->dark_mode ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Administración') - {{ config('app.name', 'Taskly') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Dark Mode CSS -->
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        // Configuración de Tailwind
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Estilos mejorados para modo oscuro */
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
        .dark .config-panel { background: #1f2937; color: #f9fafb; }
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
    </style>
    
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
            // Solo aplicar si no hay preferencia guardada en localStorage
            if (localStorage.getItem('darkMode') === null) {
                applyDarkMode();
            }
        });
    </script>
</head>
<body class="font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-white w-64 border-r border-gray-200 flex flex-col dark:bg-gray-800 dark:border-gray-700">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-2xl font-bold text-red-600 flex items-center dark:text-red-400">
                    <i class="fas fa-shield-alt mr-2"></i> Panel de Administración
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Panel de Administración</p>
            </div>
            
            <div class="flex-1 overflow-y-auto">
                <nav class="p-4">
                    <div class="mb-6">
                        <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                            <i class="fas fa-home mr-3 text-gray-500 dark:text-gray-400"></i> Inicio
                        </a>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Administración</h3>
                        <ul>
                            <li class="mb-1">
                                <a href="{{ route('admin.users') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users') ? 'bg-red-50 text-red-700 dark:bg-red-900 dark:text-red-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <i class="fas fa-users mr-3 {{ request()->routeIs('admin.users') ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Gestión de Usuarios
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ route('admin.teachers') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.teachers.*') ? 'bg-red-50 text-red-700 dark:bg-red-900 dark:text-red-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <i class="fas fa-chalkboard-teacher mr-3 {{ request()->routeIs('admin.teachers.*') ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Gestión de Profesores
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ route('admin.teachers.create') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.teachers.create') ? 'bg-red-50 text-red-700 dark:bg-red-900 dark:text-red-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <i class="fas fa-user-plus mr-3 {{ request()->routeIs('admin.teachers.create') ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Crear Profesor
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ route('admin.assignments') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.assignments.*') ? 'bg-red-50 text-red-700 dark:bg-red-900 dark:text-red-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <i class="fas fa-users-cog mr-3 {{ request()->routeIs('admin.assignments.*') ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Asignar Estudiantes
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Sistema</h3>
                        <ul>
                            <li class="mb-1">
                                <a href="{{ route('admin.reports') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.reports.*') ? 'bg-red-50 text-red-700 dark:bg-red-900 dark:text-red-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <i class="fas fa-chart-bar mr-3 {{ request()->routeIs('admin.reports.*') ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Reportes
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ route('admin.settings') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings.*') ? 'bg-red-50 text-red-700 dark:bg-red-900 dark:text-red-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <i class="fas fa-cog mr-3 {{ request()->routeIs('admin.settings.*') ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Configuración
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ route('admin.logs') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.logs.*') ? 'bg-red-50 text-red-700 dark:bg-red-900 dark:text-red-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <i class="fas fa-file-alt mr-3 {{ request()->routeIs('admin.logs.*') ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Logs del Sistema
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            
            <!-- Perfil del Usuario -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-4">
                    <img src="{{ Auth::user()->profile_photo_url }}" 
                         alt="{{ Auth::user()->name }}" 
                         class="w-10 h-10 rounded-full mr-3 object-cover"
                         id="sidebarProfileImage">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="space-y-2 mb-4">
                    <a href="{{ route('profile.edit') }}" 
                       class="block w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md transition-colors dark:text-gray-400 dark:hover:bg-gray-600">
                        <i class="fas fa-user-cog mr-2"></i> Configuración General
                    </a>
                    <a href="{{ route('notifications.settings') }}" 
                       class="block w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md transition-colors dark:text-gray-400 dark:hover:bg-gray-600">
                        <i class="fas fa-bell mr-2"></i> Notificaciones
                    </a>
                    <a href="{{ route('profile.edit') }}" 
                       class="block w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md transition-colors dark:text-gray-400 dark:hover:bg-gray-600">
                        <i class="fas fa-shield-alt mr-2"></i> Seguridad
                    </a>
                </div>

                <!-- Cerrar Sesión -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center px-3 py-2 text-sm font-medium rounded-md text-red-600 hover:bg-red-50 transition-colors dark:text-red-400 dark:hover:bg-red-900">
                        <i class="fas fa-sign-out-alt mr-3"></i> Cerrar sesión
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">@yield('page-title', 'Administración')</h2>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button id="notificationsBtn" class="p-1 text-gray-500 hover:text-gray-700 relative dark:text-gray-400 dark:hover:text-gray-200">
                                <i class="fas fa-bell"></i>
                                <span id="notificationBadge" class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-red-500 text-white text-xs flex items-center justify-center {{ auth()->user()->unreadNotifications()->count() > 0 ? '' : 'hidden' }}">
                                    {{ auth()->user()->unreadNotifications()->count() > 9 ? '9+' : auth()->user()->unreadNotifications()->count() }}
                                </span>
                            </button>
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
                                                    @if(($notification->data['type'] ?? '') === 'grade')
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
                        
                        <!-- Search -->
                        <div class="relative">
                            <form action="{{ route('search') }}" method="GET">
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar..." class="w-64 px-4 py-2 pl-10 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </form>
                        </div>
                        
                        <!-- Theme Toggle -->
                        <button id="darkModeBtn" 
                                data-dark-mode-button
                                class="p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                            <i class="fas fa-sun dark:hidden text-yellow-500 text-lg"></i>
                            <i class="fas fa-moon hidden dark:inline text-gray-300 text-lg"></i>
                        </button>
                        
                        <!-- Settings -->
                        <button id="configBtn" class="p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <i class="fas fa-cog text-lg"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded dark:bg-green-900 dark:border-green-700 dark:text-green-300" role="alert">
                            <div class="flex">
                                <div class="py-1"><i class="fas fa-check-circle mr-2"></i></div>
                                <div>{{ session('success') }}</div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded dark:bg-red-900 dark:border-red-700 dark:text-red-300" role="alert">
                            <div class="flex">
                                <div class="py-1"><i class="fas fa-exclamation-circle mr-2"></i></div>
                                <div>{{ session('error') }}</div>
                            </div>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded dark:bg-yellow-900 dark:border-yellow-700 dark:text-yellow-300" role="alert">
                            <div class="flex">
                                <div class="py-1"><i class="fas fa-exclamation-triangle mr-2"></i></div>
                                <div>{{ session('warning') }}</div>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
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
                    <span>Modo Oscuro</span>
                    <label class="toggle-switch">
                        <input type="checkbox" 
                               id="darkModeToggle" 
                               data-dark-mode-toggle
                               {{ auth()->user()->dark_mode ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
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

    <!-- Dark Mode Manager -->
    <script src="{{ asset('js/dark-mode.js') }}"></script>
    
    <script>
        // Sincronizar el modo oscuro al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            // El modo oscuro ya se aplicó en el script del head
            // Aquí solo sincronizamos los toggles si el dark mode manager está disponible
            if (window.darkModeManager) {
                window.darkModeManager.syncToggles();
            }
            
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
                if (panel && !panel.contains(event.target) && button && !button.contains(event.target)) {
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
                    dark_mode: document.getElementById('darkModeToggle')?.checked || false,
                    email_notifications: document.getElementById('emailNotificationsToggle')?.checked || false,
                    push_notifications: document.getElementById('pushNotificationsToggle')?.checked || false,
                    task_reminders: document.getElementById('taskRemindersToggle')?.checked || false,
                    project_deadlines: document.getElementById('projectDeadlinesToggle')?.checked || false,
                    exam_reminders: document.getElementById('examRemindersToggle')?.checked || false
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
                        console.log('Preferencias actualizadas correctamente');
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            document.getElementById('notificationsBtn')?.addEventListener('click', function(e) {
                e.stopPropagation();
                const dropdown = document.getElementById('notificationsDropdown');
                dropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('notificationsDropdown');
                const button = document.getElementById('notificationsBtn');
                if (dropdown && !dropdown.contains(event.target) && button && !button.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
            });

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

                    updateNotificationsList(data.notifications);
                })
                .catch(error => console.error('Error al actualizar notificaciones:', error));
            }

            function updateNotificationsList(notifications) {
                const notificationsList = document.getElementById('notificationsList');
                if (!notificationsList) return;

                if (!notifications || notifications.length === 0) {
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
                                    <p class="text-sm text-gray-500 dark:text-gray-400">${data.message || ''}</p>
                                    <p class="text-xs text-gray-400 mt-1 dark:text-gray-500">${formatTime(notification.created_at)}</p>
                                </div>
                            </div>
                        </div>
                    `;
                });

                notificationsList.innerHTML = html;

                notificationsList.querySelectorAll('.notification-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const notificationId = this.getAttribute('data-id');
                        markAsRead(notificationId);
                    });
                });
            }

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
                        updateNotificationBadge();
                    }
                })
                .catch(error => console.error('Error al marcar como leído:', error));
            }

            function formatTime(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diff = Math.floor((now - date) / 1000);
                if (diff < 60) return 'Hace un momento';
                if (diff < 3600) return `Hace ${Math.floor(diff / 60)} minutos`;
                if (diff < 86400) return `Hace ${Math.floor(diff / 3600)} horas`;
                if (diff < 604800) return `Hace ${Math.floor(diff / 86400)} días`;
                return date.toLocaleDateString('es-ES', { day: 'numeric', month: 'short', year: 'numeric' });
            }

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

            updateNotificationBadge();
            setInterval(function() { updateNotificationBadge(); }, 300000);
        });
    </script>
</body>
</html>
