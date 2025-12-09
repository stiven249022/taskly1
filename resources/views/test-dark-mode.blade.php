<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Dark Mode</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
    <style>
        .dark { color-scheme: dark; }
    </style>
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
    <div class="min-h-screen p-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold mb-8">Test del Modo Oscuro</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tarjeta de prueba -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold mb-4">Tarjeta de Prueba</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Esta es una tarjeta de prueba para verificar que el modo oscuro funcione correctamente.
                    </p>
                    <button id="toggleDark" class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white px-4 py-2 rounded">
                        Cambiar Modo
                    </button>
                </div>
                
                <!-- Información del estado -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold mb-4">Estado Actual</h2>
                    <div class="space-y-2">
                        <p><strong>Modo Oscuro:</strong> <span id="darkModeStatus">Detectando...</span></p>
                        <p><strong>localStorage:</strong> <span id="localStorageStatus">Detectando...</span></p>
                        <p><strong>Sistema:</strong> <span id="systemStatus">Detectando...</span></p>
                    </div>
                </div>
            </div>
            
            <!-- Botones de prueba -->
            <div class="mt-8 space-x-4">
                <button onclick="setDarkMode(true)" class="bg-gray-800 text-white px-4 py-2 rounded">
                    Activar Modo Oscuro
                </button>
                <button onclick="setDarkMode(false)" class="bg-gray-200 text-gray-800 px-4 py-2 rounded">
                    Activar Modo Claro
                </button>
                <button onclick="clearPreference()" class="bg-red-600 text-white px-4 py-2 rounded">
                    Limpiar Preferencia
                </button>
            </div>
        </div>
    </div>

    <script>
        function updateStatus() {
            const isDark = document.documentElement.classList.contains('dark');
            const localStorageValue = localStorage.getItem('darkMode');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            document.getElementById('darkModeStatus').textContent = isDark ? 'Activado' : 'Desactivado';
            document.getElementById('localStorageStatus').textContent = localStorageValue || 'No definido';
            document.getElementById('systemStatus').textContent = systemPrefersDark ? 'Oscuro' : 'Claro';
        }
        
        function setDarkMode(isDark) {
            if (isDark) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
            }
            updateStatus();
        }
        
        function clearPreference() {
            localStorage.removeItem('darkMode');
            // Aplicar preferencia del sistema
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            setDarkMode(systemPrefersDark);
        }
        
        // Toggle button
        document.getElementById('toggleDark').addEventListener('click', function() {
            const isDark = document.documentElement.classList.contains('dark');
            setDarkMode(!isDark);
        });
        
        // Aplicar modo oscuro al cargar
        function applyDarkMode() {
            const isDarkMode = localStorage.getItem('darkMode') === 'true' || 
                             (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);
            
            setDarkMode(isDarkMode);
        }
        
        // Inicializar
        applyDarkMode();
        updateStatus();
        
        // Escuchar cambios en la preferencia del sistema
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            if (!localStorage.getItem('darkMode')) {
                setDarkMode(e.matches);
            }
        });
    </script>
</body>
</html> 