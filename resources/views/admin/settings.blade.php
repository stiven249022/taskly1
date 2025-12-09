@extends('layouts.admin')

@section('title', 'Configuración del Sistema')
@section('page-title', 'Configuración del Sistema')

@section('content')
<div x-data="{ activeTab: 'general' }">
    <!-- System Settings Header -->
<div class="mb-8">
    <div class="bg-gradient-to-r from-gray-500 to-gray-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">Configuración del Sistema</h1>
                <p class="text-gray-100">Gestiona la configuración general y parámetros del sistema</p>
            </div>
            <div class="text-right">
                <i class="fas fa-cog text-4xl opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<!-- Settings Tabs -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button @click="activeTab = 'general'" 
                        :class="activeTab === 'general' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                <i class="fas fa-cog mr-2"></i> General
            </button>
                <button @click="activeTab = 'security'" 
                        :class="activeTab === 'security' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                <i class="fas fa-shield-alt mr-2"></i> Seguridad
            </button>
                <button @click="activeTab = 'notifications'" 
                        :class="activeTab === 'notifications' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                <i class="fas fa-bell mr-2"></i> Notificaciones
            </button>
                <button @click="activeTab = 'database'" 
                        :class="activeTab === 'database' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                <i class="fas fa-database mr-2"></i> Base de Datos
            </button>
        </nav>
    </div>

    <div class="p-6">
            <!-- General Settings Tab -->
            <div x-show="activeTab === 'general'" x-cloak>
                <form id="generalForm" class="space-y-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Configuración General</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nombre del Sistema
                        </label>
                                <input type="text" name="system_name" id="system_name" 
                                       value="{{ $settings['system_name'] ?? 'Taskly' }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            URL del Sistema
                        </label>
                                <input type="url" name="system_url" id="system_url" 
                                       value="{{ $settings['system_url'] ?? config('app.url') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Zona Horaria
                        </label>
                                <select name="timezone" id="timezone" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="America/Mexico_City" {{ ($settings['timezone'] ?? 'America/Mexico_City') === 'America/Mexico_City' ? 'selected' : '' }}>América/México</option>
                                    <option value="America/New_York" {{ ($settings['timezone'] ?? '') === 'America/New_York' ? 'selected' : '' }}>América/Nueva York</option>
                                    <option value="Europe/Madrid" {{ ($settings['timezone'] ?? '') === 'Europe/Madrid' ? 'selected' : '' }}>Europa/Madrid</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Idioma por Defecto
                        </label>
                                <select name="default_language" id="default_language" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="es" {{ ($settings['default_language'] ?? 'es') === 'es' ? 'selected' : '' }}>Español</option>
                                    <option value="en" {{ ($settings['default_language'] ?? '') === 'en' ? 'selected' : '' }}>English</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Configuración de Usuarios</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Registro Automático</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Permitir que los usuarios se registren automáticamente</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="auto_registration" id="auto_registration" 
                                           {{ ($settings['auto_registration'] ?? true) ? 'checked' : '' }}
                                           class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Verificación de Email</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Requerir verificación de email para activar cuentas</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="email_verification" id="email_verification" 
                                           {{ ($settings['email_verification'] ?? true) ? 'checked' : '' }}
                                           class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Modo Mantenimiento</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Poner el sistema en modo mantenimiento</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="maintenance_mode" id="maintenance_mode" 
                                           {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }}
                                           class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 dark:peer-focus:ring-red-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-600"></div>
                        </label>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Límites del Sistema</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Máximo de Usuarios
                        </label>
                                <input type="number" name="max_users" id="max_users" 
                                       value="{{ $settings['max_users'] ?? 1000 }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tareas por Usuario
                        </label>
                                <input type="number" name="max_tasks_per_user" id="max_tasks_per_user" 
                                       value="{{ $settings['max_tasks_per_user'] ?? 100 }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Proyectos por Usuario
                        </label>
                                <input type="number" name="max_projects_per_user" id="max_projects_per_user" 
                                       value="{{ $settings['max_projects_per_user'] ?? 50 }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-medium transition-colors">
                            <i class="fas fa-save mr-2"></i> Guardar Configuración General
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security Settings Tab -->
            <div x-show="activeTab === 'security'" x-cloak>
                <form id="securityForm" class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Configuración de Contraseñas</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Longitud Mínima de Contraseña
                                </label>
                                <input type="number" name="password_min_length" id="password_min_length" 
                                       value="{{ $settings['password_min_length'] ?? 8 }}" min="6" max="32"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tiempo de Sesión (minutos)
                                </label>
                                <input type="number" name="session_timeout" id="session_timeout" 
                                       value="{{ $settings['session_timeout'] ?? 120 }}" min="5" max="1440"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                        </div>
                        
                        <div class="mt-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Requerir Mayúsculas</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Las contraseñas deben contener al menos una letra mayúscula</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="password_require_uppercase" id="password_require_uppercase" 
                                           {{ ($settings['password_require_uppercase'] ?? false) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Requerir Minúsculas</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Las contraseñas deben contener al menos una letra minúscula</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="password_require_lowercase" id="password_require_lowercase" 
                                           {{ ($settings['password_require_lowercase'] ?? false) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Requerir Números</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Las contraseñas deben contener al menos un número</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="password_require_numbers" id="password_require_numbers" 
                                           {{ ($settings['password_require_numbers'] ?? false) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Requerir Símbolos</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Las contraseñas deben contener al menos un símbolo</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="password_require_symbols" id="password_require_symbols" 
                                           {{ ($settings['password_require_symbols'] ?? false) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Protección contra Ataques</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Intentos Máximos de Login
                                </label>
                                <input type="number" name="max_login_attempts" id="max_login_attempts" 
                                       value="{{ $settings['max_login_attempts'] ?? 5 }}" min="3" max="10"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Duración de Bloqueo (minutos)
                                </label>
                                <input type="number" name="lockout_duration" id="lockout_duration" 
                                       value="{{ $settings['lockout_duration'] ?? 15 }}" min="1" max="60"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Autenticación de Dos Factores</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Habilitar autenticación de dos factores para mayor seguridad</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="two_factor_enabled" id="two_factor_enabled" 
                                           {{ ($settings['two_factor_enabled'] ?? false) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Lista Blanca de IPs (separadas por comas)
                            </label>
                            <textarea name="ip_whitelist" id="ip_whitelist" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                      placeholder="192.168.1.1, 10.0.0.1">{{ $settings['ip_whitelist'] ?? '' }}</textarea>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-medium transition-colors">
                            <i class="fas fa-save mr-2"></i> Guardar Configuración de Seguridad
                        </button>
                    </div>
                </form>
            </div>

            <!-- Notifications Settings Tab -->
            <div x-show="activeTab === 'notifications'" x-cloak>
                <form id="notificationsForm" class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Configuración de Email</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Email Habilitado
                                </label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="email_enabled" id="email_enabled" 
                                           {{ ($settings['email_enabled'] ?? true) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Dirección Email Remitente
                                </label>
                                <input type="email" name="email_from_address" id="email_from_address" 
                                       value="{{ $settings['email_from_address'] ?? config('mail.from.address') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nombre del Remitente
                                </label>
                                <input type="text" name="email_from_name" id="email_from_name" 
                                       value="{{ $settings['email_from_name'] ?? config('mail.from.name') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Frecuencia de Notificaciones
                                </label>
                                <select name="notification_frequency" id="notification_frequency" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="realtime" {{ ($settings['notification_frequency'] ?? 'realtime') === 'realtime' ? 'selected' : '' }}>Tiempo Real</option>
                                    <option value="hourly" {{ ($settings['notification_frequency'] ?? '') === 'hourly' ? 'selected' : '' }}>Cada Hora</option>
                                    <option value="daily" {{ ($settings['notification_frequency'] ?? '') === 'daily' ? 'selected' : '' }}>Diario</option>
                                    <option value="weekly" {{ ($settings['notification_frequency'] ?? '') === 'weekly' ? 'selected' : '' }}>Semanal</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recordatorios</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Días de Anticipación para Tareas
                                </label>
                                <input type="number" name="task_reminder_days" id="task_reminder_days" 
                                       value="{{ $settings['task_reminder_days'] ?? 1 }}" min="0" max="30"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Días de Anticipación para Proyectos
                                </label>
                                <input type="number" name="project_reminder_days" id="project_reminder_days" 
                                       value="{{ $settings['project_reminder_days'] ?? 3 }}" min="0" max="30"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                        </div>
                        
                        <div class="mt-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Notificaciones Push</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Habilitar notificaciones push en el navegador</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="push_enabled" id="push_enabled" 
                                           {{ ($settings['push_enabled'] ?? false) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Notificaciones SMS</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Habilitar notificaciones por SMS</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="sms_enabled" id="sms_enabled" 
                                           {{ ($settings['sms_enabled'] ?? false) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-medium transition-colors">
                            <i class="fas fa-save mr-2"></i> Guardar Configuración de Notificaciones
                        </button>
                    </div>
                </form>
            </div>

            <!-- Database Settings Tab -->
            <div x-show="activeTab === 'database'" x-cloak>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Información de la Base de Datos</h3>
                        
                        <div id="databaseInfo" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center justify-center">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                                <span class="ml-3 text-gray-600 dark:text-gray-300">Cargando información...</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Acciones de Base de Datos</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <button id="backupBtn" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-md font-medium transition-colors flex items-center justify-center">
                                <i class="fas fa-download mr-2"></i> Crear Respaldo
                            </button>
                            
                            <button id="clearCacheBtn" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-md font-medium transition-colors flex items-center justify-center">
                                <i class="fas fa-broom mr-2"></i> Limpiar Caché
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar información de la base de datos
    loadDatabaseInfo();
    
    // Formulario General
    document.getElementById('generalForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        saveSettings('general', this);
    });
    
    // Formulario Seguridad
    document.getElementById('securityForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        saveSettings('security', this);
    });
    
    // Formulario Notificaciones
    document.getElementById('notificationsForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        saveSettings('notifications', this);
    });
    
    // Botón de respaldo
    document.getElementById('backupBtn')?.addEventListener('click', function() {
        createBackup();
    });
    
    // Botón de limpiar caché
    document.getElementById('clearCacheBtn')?.addEventListener('click', function() {
        clearCache();
    });
    
    function saveSettings(type, form) {
        const formData = new FormData(form);
        const data = {};
        
        // Convertir FormData a objeto
        for (let [key, value] of formData.entries()) {
            if (formData.getAll(key).length > 1) {
                data[key] = formData.getAll(key);
            } else {
                data[key] = value;
            }
        }
        
        // Convertir checkboxes a booleanos
        const checkboxes = form.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            data[checkbox.name] = checkbox.checked;
        });
        
        const button = form.querySelector('button[type="submit"]');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Guardando...';
        
        const routeMap = {
            'general': '/admin/settings/general',
            'security': '/admin/settings/security',
            'notifications': '/admin/settings/notifications'
        };
        
        fetch(routeMap[type], {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Configuración guardada exitosamente', 'success');
            } else {
                showNotification(data.message || 'Error al guardar la configuración', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al guardar la configuración', 'error');
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }
    
    function loadDatabaseInfo() {
        fetch('/admin/settings/database/info', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            const infoDiv = document.getElementById('databaseInfo');
            if (data.success) {
                const db = data.database;
                infoDiv.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nombre de la Base de Datos</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">${db.name}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tamaño</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">${db.size_mb} MB</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Número de Tablas</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">${db.table_count}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Conexión</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">${db.connection}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Registros por Tabla</h4>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            ${Object.entries(db.tables).map(([table, count]) => `
                                <div class="bg-white dark:bg-gray-600 p-2 rounded">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">${table}</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">${count}</p>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            } else {
                infoDiv.innerHTML = `<p class="text-red-600 dark:text-red-400">${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('databaseInfo').innerHTML = '<p class="text-red-600 dark:text-red-400">Error al cargar la información</p>';
        });
    }
    
    function createBackup() {
        const button = document.getElementById('backupBtn');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Creando respaldo...';
        
        fetch('/admin/settings/database/backup', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(`Respaldo creado exitosamente: ${data.filename} (${(data.size / 1024).toFixed(2)} KB)`, 'success');
            } else {
                showNotification(data.message || 'Error al crear el respaldo', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al crear el respaldo', 'error');
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }
    
    function clearCache() {
        const button = document.getElementById('clearCacheBtn');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Limpiando...';
        
        fetch('/admin/settings/cache/clear', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Caché limpiado exitosamente', 'success');
            } else {
                showNotification(data.message || 'Error al limpiar el caché', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al limpiar el caché', 'error');
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }
    
    function showNotification(message, type) {
        // Crear notificación
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(notification);
        
        // Remover después de 3 segundos
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
});
</script>
@endsection
