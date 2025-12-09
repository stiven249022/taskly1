@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Reporte Detallado</h1>
                <p class="text-gray-600 mt-2">{{ $report->title }}</p>
            </div>
            <div class="flex space-x-2">
                <button onclick="exportToPDF()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Exportar PDF
                </button>
                <button onclick="exportToExcel()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-file-excel mr-2"></i>
                    Exportar Excel
                </button>
            </div>
        </div>

        <!-- Información del Reporte -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Período</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $report->period }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Tipo de Reporte</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $report->type }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Generado</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $report->generated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Métricas Principales -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            @foreach($metrics as $metric)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full" style="background-color: {{ $metric['color'] }}20">
                        <i class="{{ $metric['icon'] }} text-xl" style="color: {{ $metric['color'] }}"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">{{ $metric['label'] }}</p>
                        <p class="text-2xl font-semibold text-gray-800">{{ $metric['value'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Gráficos Detallados -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Tendencias -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Tendencias</h2>
                <canvas id="trendsChart" height="300"></canvas>
            </div>

            <!-- Comparativa -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Comparativa</h2>
                <canvas id="comparisonChart" height="300"></canvas>
            </div>
        </div>

        <!-- Tabla de Actividades -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Actividades</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actividad</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Inicio</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Fin</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duración</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progreso</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($activities as $activity)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $activity->title }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" style="background-color: {{ $activity->status_color }}20; color: {{ $activity->status_color }}">
                                        {{ $activity->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $activity->start_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $activity->end_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $activity->duration }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $activity->progress }}%"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Análisis y Recomendaciones -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-medium text-gray-800 mb-4">Análisis y Recomendaciones</h2>
            <div class="space-y-4">
                @foreach($analysis as $item)
                <div class="border-l-4 border-blue-500 pl-4 py-2">
                    <h3 class="font-medium text-gray-800">{{ $item['title'] }}</h3>
                    <p class="text-gray-600 mt-1">{{ $item['description'] }}</p>
                    @if(isset($item['recommendations']))
                    <ul class="mt-2 space-y-1">
                        @foreach($item['recommendations'] as $recommendation)
                        <li class="text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            {{ $recommendation }}
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Datos para los gráficos
const trendsData = @json($trendsData);
const comparisonData = @json($comparisonData);

// Gráfico de Tendencias
new Chart(document.getElementById('trendsChart'), {
    type: 'line',
    data: {
        labels: trendsData.labels,
        datasets: trendsData.datasets.map(dataset => ({
            label: dataset.label,
            data: dataset.data,
            borderColor: dataset.color,
            backgroundColor: dataset.color + '20',
            fill: true,
            tension: 0.4
        }))
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Gráfico de Comparativa
new Chart(document.getElementById('comparisonChart'), {
    type: 'radar',
    data: {
        labels: comparisonData.labels,
        datasets: [{
            label: 'Actual',
            data: comparisonData.current,
            backgroundColor: 'rgba(59, 130, 246, 0.2)',
            borderColor: 'rgb(59, 130, 246)',
            pointBackgroundColor: 'rgb(59, 130, 246)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(59, 130, 246)'
        }, {
            label: 'Anterior',
            data: comparisonData.previous,
            backgroundColor: 'rgba(156, 163, 175, 0.2)',
            borderColor: 'rgb(156, 163, 175)',
            pointBackgroundColor: 'rgb(156, 163, 175)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(156, 163, 175)'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            r: {
                beginAtZero: true
            }
        }
    }
});

// Funciones de exportación
function exportToPDF() {
    // Implementar exportación a PDF
    alert('Exportando a PDF...');
}

function exportToExcel() {
    // Implementar exportación a Excel
    alert('Exportando a Excel...');
}
</script>
@endpush
@endsection 