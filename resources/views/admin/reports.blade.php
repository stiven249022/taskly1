@extends('layouts.admin')

@section('title', 'Reportes del Sistema')
@section('page-title', 'Reportes del Sistema')

@section('content')
<!-- Report Generation -->
<div class="mb-8">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">Generador de Reportes</h1>
                <p class="text-blue-100">Genera reportes detallados del sistema y actividad de usuarios</p>
            </div>
            <div class="text-right">
                <i class="fas fa-chart-bar text-4xl opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<!-- Report Types -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- User Activity Report -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                <i class="fas fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
            </div>
            <span class="text-sm text-gray-500 dark:text-gray-400">PDF</span>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Reporte de Usuarios</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Actividad y estadísticas de todos los usuarios del sistema</p>
        <a href="{{ route('admin.reports.users') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors text-center">
            <i class="fas fa-download mr-2"></i> Generar Reporte
        </a>
    </div>

    <!-- Teacher Performance Report -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                <i class="fas fa-chalkboard-teacher text-green-600 dark:text-green-400 text-xl"></i>
            </div>
            <span class="text-sm text-gray-500 dark:text-gray-400">Excel</span>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Rendimiento de Profesores</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Calificaciones y actividad de profesores</p>
        <a href="{{ route('admin.reports.teachers-performance') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors text-center">
            <i class="fas fa-download mr-2"></i> Generar Reporte
        </a>
    </div>

    <!-- System Usage Report -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                <i class="fas fa-chart-line text-purple-600 dark:text-purple-400 text-xl"></i>
            </div>
            <span class="text-sm text-gray-500 dark:text-gray-400">CSV</span>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Uso del Sistema</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Estadísticas de uso y actividad general</p>
        <a href="{{ route('admin.reports.system-usage') }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors text-center">
            <i class="fas fa-download mr-2"></i> Generar Reporte
        </a>
    </div>
</div>

<!-- Recent Reports -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Reportes Recientes</h3>
    </div>
    
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded">
                        <i class="fas fa-file-pdf text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Reporte de Usuarios - Diciembre 2024</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Generado hace 2 horas</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.reports.users', ['month' => 12, 'year' => 2024]) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="Descargar">
                        <i class="fas fa-download"></i>
                    </a>
                    <button class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded">
                        <i class="fas fa-file-excel text-green-600 dark:text-green-400"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Rendimiento Profesores - Noviembre 2024</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Generado hace 1 día</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.reports.teachers-performance', ['month' => 11, 'year' => 2024]) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="Descargar">
                        <i class="fas fa-download"></i>
                    </a>
                    <button class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
            <i class="fas fa-chart-bar text-4xl mb-2"></i>
            <p>No hay más reportes disponibles</p>
        </div>
    </div>
</div>
@endsection
