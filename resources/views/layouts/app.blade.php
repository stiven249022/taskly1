<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Taskly') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Dark Mode Script -->
        <script>
            // Función para aplicar el modo oscuro
            function applyDarkMode() {
                const isDarkMode = localStorage.getItem('darkMode') === 'true' || 
                                 (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);
                
                if (isDarkMode) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }

            // Aplicar modo oscuro inmediatamente para evitar flash
            applyDarkMode();

            // Escuchar cambios en la preferencia del sistema
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', applyDarkMode);
        </script>
    </head>
    <body class="font-sans antialiased">
        @if(request()->routeIs('dashboard'))
            @yield('content')
        @else
            <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    <!-- Alertas -->
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                        @if(session('success'))
                            <x-alert type="success" :message="session('success')" />
                        @endif

                        @if(session('error'))
                            <x-alert type="error" :message="session('error')" />
                        @endif

                        @if(session('warning'))
                            <x-alert type="warning" :message="session('warning')" />
                        @endif

                        @if(session('info'))
                            <x-alert type="info" :message="session('info')" />
                        @endif

                        @if($errors->any())
                            @foreach($errors->all() as $error)
                                <x-alert type="error" :message="$error" />
                            @endforeach
                        @endif
                    </div>

                    @yield('content')
                </main>
            </div>
        @endif
    </body>
</html>
