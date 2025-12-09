<div class="p-4 border-t border-gray-200 dark:border-gray-700">
    <!-- Perfil del Usuario -->
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