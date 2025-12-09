@extends('layouts.dashboard')

@section('title', 'Análisis')
@section('page-title', 'Análisis')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Análisis de Rendimiento</h2>
    <p class="text-gray-600 dark:text-gray-400">Estadísticas y métricas de tus estudiantes</p>
</div>

<!-- Estadísticas Generales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Estudiantes</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $analytics['total_students'] }}</p>
            </div>
            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tareas Totales</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $analytics['total_tasks'] }}</p>
            </div>
            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                <i class="fas fa-tasks"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Proyectos Totales</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ $analytics['total_projects'] }}</p>
            </div>
            <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                <i class="fas fa-project-diagram"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Promedio General</p>
                <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-white">{{ number_format($analytics['average_grade'], 1) }}</p>
            </div>
            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400">
                <i class="fas fa-star"></i>
            </div>
        </div>
    </div>
</div>

<!-- Progreso de Calificaciones -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Progreso de Calificaciones</h3>
        </div>
        
        <div class="p-6">
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <span>Tareas Calificadas</span>
                        <span>{{ $analytics['graded_tasks'] }} / {{ $analytics['total_tasks'] }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $analytics['total_tasks'] > 0 ? ($analytics['graded_tasks'] / $analytics['total_tasks']) * 100 : 0 }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <span>Proyectos Calificados</span>
                        <span>{{ $analytics['graded_projects'] }} / {{ $analytics['total_projects'] }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $analytics['total_projects'] > 0 ? ($analytics['graded_projects'] / $analytics['total_projects']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Distribución de Calificaciones</h3>
        </div>
        
        <div class="p-6">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Excelentes (90-100)</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-20 bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 25%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">25%</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Buenas (70-89)</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-20 bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                            <div class="bg-yellow-600 h-2 rounded-full" style="width: 45%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">45%</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Regulares (50-69)</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-20 bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                            <div class="bg-orange-600 h-2 rounded-full" style="width: 20%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">20%</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Necesitan Mejora (<50)</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-20 bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                            <div class="bg-red-600 h-2 rounded-full" style="width: 10%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">10%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Estudiantes -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Estudiantes</h3>
        <a href="{{ route('teacher.analytics.download') }}" 
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium inline-flex items-center">
            <i class="fas fa-download mr-2"></i> Descargar Informe
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Estudiante
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Tareas
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Proyectos
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Promedio
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Estado
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($students as $student)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <img class="h-10 w-10 rounded-full" src="{{ $student->profile_photo_url }}" alt="{{ $student->name }}">
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $student->name }} {{ $student->last_name }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $student->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        {{ $student->tasks->count() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        {{ $student->projects->count() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        {{ number_format($student->average_grade ?? 0, 1) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $avgGrade = $student->average_grade ?? 0;
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($avgGrade >= 90) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($avgGrade >= 70) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($avgGrade > 0) bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                            @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                            @if($avgGrade >= 90) Excelente
                            @elseif($avgGrade >= 70) Bueno
                            @elseif($avgGrade >= 50) Regular
                            @elseif($avgGrade > 0) Necesita Mejora
                            @else Sin Calificar
                            @endif
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        No hay estudiantes registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
