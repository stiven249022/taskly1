<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ auth()->user()->dark_mode ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Profesor') - {{ config('app.name', 'Taskly') }}</title>

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
        .dark .notifications-dropdown { 
            background: #1f2937; 
            border-color: #374151; 
        }
        
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
                    // Actualizar servidor para mantener sincronización (sin bloquear UI)
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
                <h1 class="text-2xl font-bold text-indigo-600 flex items-center dark:text-indigo-400">
                    <i class="fas fa-graduation-cap mr-2"></i> Taskly
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Organiza tu éxito académico</p>
            </div>
            
            <div class="flex-1 overflow-y-auto">
                <nav class="p-4">
                    <div class="mb-6">
                        <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                            <i class="fas fa-home mr-3 text-gray-500 dark:text-gray-400"></i> Dashboard
                        </a>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Enseñanza</h3>
                        <ul>
                            <li class="mb-1">
                                <a href="{{ route('teacher.students') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('teacher.students') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <i class="fas fa-users mr-3 {{ request()->routeIs('teacher.students') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Mis Estudiantes
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ route('teacher.tasks') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('teacher.tasks.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <i class="fas fa-tasks mr-3 {{ request()->routeIs('teacher.tasks.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Calificar Tareas
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ route('teacher.projects') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('teacher.projects.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <i class="fas fa-project-diagram mr-3 {{ request()->routeIs('teacher.projects.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Calificar Proyectos
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Reportes</h3>
                        <ul>
                            <li class="mb-1">
                                <a href="{{ route('teacher.grades') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('teacher.grades.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <i class="fas fa-chart-line mr-3 {{ request()->routeIs('teacher.grades.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Calificaciones
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="{{ route('teacher.analytics') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('teacher.analytics.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <i class="fas fa-chart-bar mr-3 {{ request()->routeIs('teacher.analytics.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}"></i> Análisis
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
            <header class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 relative">
                                <i class="fas fa-bell text-xl"></i>
                                @if(auth()->user()->unreadNotifications()->count() > 0)
                                <span class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center {{ auth()->user()->unreadNotifications()->count() > 9 ? 'px-1' : '' }}">
                                    {{ auth()->user()->unreadNotifications()->count() > 9 ? '9+' : auth()->user()->unreadNotifications()->count() }}
                                </span>
                                @endif
                            </button>
                            
                            <div x-show="open" @click.away="open = false" 
                                 class="notifications-dropdown" 
                                 x-cloak>
                                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Notificaciones</h3>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                                    <div class="notification-item p-4 border-b border-gray-200 dark:border-gray-700">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 dark:bg-blue-900 dark:text-blue-400">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ $notification->data['title'] ?? 'Notificación' }}</p>
                                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">{{ $notification->data['message'] ?? '' }}</p>
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
                            </div>
                        </div>

                        <!-- Dark Mode Toggle -->
                        <button id="darkModeBtn" 
                                data-dark-mode-button
                                class="p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                            <i class="fas fa-sun dark:hidden text-yellow-500 text-lg"></i>
                            <i class="fas fa-moon hidden dark:inline text-gray-300 text-lg"></i>
                        </button>
                        
                        <!-- Config Button -->
                        <button id="configBtn" class="p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <i class="fas fa-cog"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded dark:bg-green-900 dark:border-green-700 dark:text-green-300">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded dark:bg-red-900 dark:border-red-700 dark:text-red-300">
                            {{ session('error') }}
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
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
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
                        
                        // Mostrar mensaje de éxito
                        if (window.showNotification) {
                            window.showNotification('Preferencias actualizadas correctamente', 'success');
                        } else {
                            console.log('Preferencias actualizadas correctamente');
                        }
                    } else {
                        console.error('Error:', data.message || 'Error desconocido');
                    }
                })
                .catch(error => {
                    console.error('Error al actualizar preferencias:', error);
                    alert('Error al actualizar las preferencias. Por favor, intenta nuevamente.');
                });
            }
        });
    </script>
</body>
</html>
