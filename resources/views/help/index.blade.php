@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Centro de Ayuda</h1>
            <p class="text-gray-600 mt-2">Encuentra respuestas a tus preguntas y aprende a usar Taskly</p>
        </div>

        <!-- Barra de Búsqueda -->
        <div class="max-w-2xl mx-auto mb-8">
            <form action="{{ route('help.search') }}" method="GET" class="relative">
                <input type="text" name="q" placeholder="Buscar en la documentación..." class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Secciones Principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Guías de Inicio -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-blue-600 mb-4">
                    <i class="fas fa-rocket text-2xl"></i>
                </div>
                <h2 class="text-lg font-medium text-gray-800 mb-2">Guías de Inicio</h2>
                <p class="text-gray-600 mb-4">Aprende los conceptos básicos y comienza a usar Taskly</p>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('help.guides.getting-started') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-chevron-right text-xs mr-2"></i>
                            Primeros Pasos
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('help.guides.interface') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-chevron-right text-xs mr-2"></i>
                            Interfaz de Usuario
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('help.guides.tasks') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-chevron-right text-xs mr-2"></i>
                            Gestión de Tareas
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Características -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-green-600 mb-4">
                    <i class="fas fa-star text-2xl"></i>
                </div>
                <h2 class="text-lg font-medium text-gray-800 mb-2">Características</h2>
                <p class="text-gray-600 mb-4">Descubre todas las funcionalidades de Taskly</p>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('help.features.projects') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-chevron-right text-xs mr-2"></i>
                            Proyectos
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('help.features.calendar') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-chevron-right text-xs mr-2"></i>
                            Calendario
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('help.features.reports') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-chevron-right text-xs mr-2"></i>
                            Reportes
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Solución de Problemas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-red-600 mb-4">
                    <i class="fas fa-wrench text-2xl"></i>
                </div>
                <h2 class="text-lg font-medium text-gray-800 mb-2">Solución de Problemas</h2>
                <p class="text-gray-600 mb-4">Resuelve problemas comunes y errores</p>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('help.troubleshooting.common-issues') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-chevron-right text-xs mr-2"></i>
                            Problemas Comunes
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('help.troubleshooting.faq') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-chevron-right text-xs mr-2"></i>
                            Preguntas Frecuentes
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('help.troubleshooting.contact') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-chevron-right text-xs mr-2"></i>
                            Contactar Soporte
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Preguntas Frecuentes -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-lg font-medium text-gray-800 mb-4">Preguntas Frecuentes</h2>
            <div class="space-y-4">
                @foreach($faqs as $faq)
                <div class="border-b border-gray-200 pb-4">
                    <button onclick="toggleFAQ({{ $loop->index }})" class="w-full text-left flex justify-between items-center">
                        <h3 class="text-gray-800 font-medium">{{ $faq->question }}</h3>
                        <i class="fas fa-chevron-down text-gray-500 transform transition-transform" id="faq-icon-{{ $loop->index }}"></i>
                    </button>
                    <div id="faq-answer-{{ $loop->index }}" class="mt-2 text-gray-600 hidden">
                        {{ $faq->answer }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Contacto -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <h2 class="text-lg font-medium text-gray-800 mb-2">¿Necesitas más ayuda?</h2>
                <p class="text-gray-600 mb-4">Nuestro equipo de soporte está aquí para ayudarte</p>
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('help.contact') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        Contactar Soporte
                    </a>
                    <a href="{{ route('help.documentation') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg">
                        Ver Documentación Completa
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleFAQ(index) {
    const answer = document.getElementById(`faq-answer-${index}`);
    const icon = document.getElementById(`faq-icon-${index}`);
    
    if (answer.classList.contains('hidden')) {
        answer.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        answer.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}
</script>
@endpush
@endsection 