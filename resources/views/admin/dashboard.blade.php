@extends('layouts.admin')

@section('title', 'Panel del Administrador')
@section('page-title', 'Panel del Administrador')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Usuarios</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $stats['total_users'] }}</p>
            </div>
            <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Estudiantes</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $stats['total_students'] }}</p>
            </div>
            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                <i class="fas fa-graduation-cap"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Profesores</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $stats['total_teachers'] }}</p>
            </div>
            <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pendientes</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $stats['pending_users'] }}</p>
            </div>
            <div class="p-3 rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Pending Users -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Usuarios Pendientes</h3>
            <a href="{{ route('admin.users') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                Ver todos
            </a>
        </div>
        
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($pendingUsers as $user)
            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img class="h-10 w-10 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $user->name }} {{ $user->last_name }}
                            </h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Registrado: {{ $user->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <form method="POST" action="{{ route('admin.users.approve', $user->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md text-sm font-medium">
                                Aprobar
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.reject', $user->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm font-medium">
                                Rechazar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                <i class="fas fa-check-circle text-4xl mb-2"></i>
                <p>No hay usuarios pendientes de aprobación</p>
            </div>
            @endforelse
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Actividad Reciente</h3>
        </div>
        
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($recentTasks->take(3) as $task)
            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-tasks text-indigo-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            Nueva tarea: {{ $task->title }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $task->user->name }} - {{ $task->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
            
            @forelse($recentProjects->take(2) as $project)
            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-project-diagram text-green-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            Nuevo proyecto: {{ $project->name }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $project->user->name }} - {{ $project->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
            
            @if($recentTasks->count() === 0 && $recentProjects->count() === 0)
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                <i class="fas fa-history text-4xl mb-2"></i>
                <p>No hay actividad reciente</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Acciones Rápidas</h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.users') }}" 
                   class="flex items-center p-4 bg-blue-50 dark:bg-blue-900 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-blue-600 dark:text-blue-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">Gestionar Usuarios</h4>
                        <p class="text-sm text-blue-700 dark:text-blue-300">Ver y administrar todos los usuarios</p>
                    </div>
                </a>
                
                <a href="{{ route('admin.teachers') }}" 
                   class="flex items-center p-4 bg-green-50 dark:bg-green-900 rounded-lg hover:bg-green-100 dark:hover:bg-green-800 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-chalkboard-teacher text-green-600 dark:text-green-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-green-900 dark:text-green-100">Gestionar Profesores</h4>
                        <p class="text-sm text-green-700 dark:text-green-300">Administrar profesores y roles</p>
                    </div>
                </a>
                
                <a href="{{ route('admin.teachers.create') }}" 
                   class="flex items-center p-4 bg-purple-50 dark:bg-purple-900 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-plus text-purple-600 dark:text-purple-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-purple-900 dark:text-purple-100">Crear Profesor</h4>
                        <p class="text-sm text-purple-700 dark:text-purple-300">Agregar nuevo profesor al sistema</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
