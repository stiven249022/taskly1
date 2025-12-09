@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Reportes y Estadísticas</h1>
            <p class="text-gray-600 mt-2">Visualiza el rendimiento y progreso de tus actividades</p>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form action="{{ route('reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Período</label>
                    <select name="period" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="week" {{ request('period') === 'week' ? 'selected' : '' }}>Esta semana</option>
                        <option value="month" {{ request('period') === 'month' ? 'selected' : '' }}>Este mes</option>
                        <option value="quarter" {{ request('period') === 'quarter' ? 'selected' : '' }}>Este trimestre</option>
                        <option value="year" {{ request('period') === 'year' ? 'selected' : '' }}>Este año</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Curso</label>
                    <select name="course_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="">Todos los cursos</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Actividad</label>
                    <select name="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="">Todas las actividades</option>
                        <option value="tasks" {{ request('type') === 'tasks' ? 'selected' : '' }}>Tareas</option>
                        <option value="projects" {{ request('type') === 'projects' ? 'selected' : '' }}>Proyectos</option>
                        <option value="reminders" {{ request('type') === 'reminders' ? 'selected' : '' }}>Recordatorios</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">
                        <i class="fas fa-filter mr-2"></i>
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Resumen General -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-tasks text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Tareas Completadas</p>
                        <p class="text-2xl font-semibold text-gray-800">{{ $stats['completed_tasks'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-project-diagram text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Proyectos Activos</p>
                        <p class="text-2xl font-semibold text-gray-800">{{ $stats['active_projects'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Tiempo Promedio</p>
                        <p class="text-2xl font-semibold text-gray-800">{{ $stats['avg_completion_time'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Eficiencia</p>
                        <p class="text-2xl font-semibold text-gray-800">{{ $stats['efficiency'] }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Progreso por Curso -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Progreso por Curso</h2>
                <canvas id="courseProgressChart" height="300"></canvas>
            </div>

            <!-- Distribución de Actividades -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Distribución de Actividades</h2>
                <canvas id="activityDistributionChart" height="300"></canvas>
            </div>
        </div>

        <!-- Tabla de Rendimiento -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Rendimiento por Curso</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tareas Completadas</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyectos Activos</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiempo Promedio</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eficiencia</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($courseStats as $stat)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $stat->course_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $stat->completed_tasks }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $stat->active_projects }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $stat->avg_completion_time }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $stat->efficiency }}%
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Datos para los gráficos
const courseProgressData = @json($courseProgressData);
const activityDistributionData = @json($activityDistributionData);

// Gráfico de Progreso por Curso
new Chart(document.getElementById('courseProgressChart'), {
    type: 'bar',
    data: {
        labels: courseProgressData.labels,
        datasets: [{
            label: 'Progreso',
            data: courseProgressData.data,
            backgroundColor: 'rgba(59, 130, 246, 0.5)',
            borderColor: 'rgb(59, 130, 246)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            }
        }
    }
});

// Gráfico de Distribución de Actividades
new Chart(document.getElementById('activityDistributionChart'), {
    type: 'doughnut',
    data: {
        labels: activityDistributionData.labels,
        datasets: [{
            data: activityDistributionData.data,
            backgroundColor: [
                'rgba(59, 130, 246, 0.5)',
                'rgba(16, 185, 129, 0.5)',
                'rgba(245, 158, 11, 0.5)'
            ],
            borderColor: [
                'rgb(59, 130, 246)',
                'rgb(16, 185, 129)',
                'rgb(245, 158, 11)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endpush
@endsection 