@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Navegación Lateral -->
            <div class="md:w-64 flex-shrink-0">
                <div class="sticky top-4">
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="mb-4">
                            <input type="text" placeholder="Buscar en la documentación..." class="w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <nav class="space-y-1">
                            @foreach($sections as $section)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">
                                    {{ $section->title }}
                                </h3>
                                <ul class="space-y-1">
                                    @foreach($section->articles as $article)
                                    <li>
                                        <a href="#{{ $article->slug }}" class="block px-3 py-2 text-sm rounded-md {{ $article->isActive ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                                            {{ $article->title }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endforeach
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="flex-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <!-- Encabezado -->
                    <div class="mb-8">
                        <h1 class="text-2xl font-bold text-gray-800">{{ $article->title }}</h1>
                        <div class="flex items-center mt-2 text-sm text-gray-500">
                            <span>Última actualización: {{ $article->updated_at->format('d/m/Y') }}</span>
                            <span class="mx-2">•</span>
                            <span>Tiempo de lectura: {{ $article->reading_time }} min</span>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="prose max-w-none">
                        {!! $article->content !!}
                    </div>

                    <!-- Navegación de Artículos -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex justify-between">
                            @if($article->previous)
                            <a href="{{ route('help.documentation', $article->previous->slug) }}" class="flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fas fa-arrow-left mr-2"></i>
                                {{ $article->previous->title }}
                            </a>
                            @else
                            <div></div>
                            @endif

                            @if($article->next)
                            <a href="{{ route('help.documentation', $article->next->slug) }}" class="flex items-center text-blue-600 hover:text-blue-800">
                                {{ $article->next->title }}
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                            @else
                            <div></div>
                            @endif
                        </div>
                    </div>

                    <!-- Feedback -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <h3 class="text-lg font-medium text-gray-800 mb-2">¿Fue útil este artículo?</h3>
                            <div class="flex justify-center space-x-4">
                                <button onclick="submitFeedback('helpful')" class="flex items-center px-4 py-2 rounded-lg bg-green-100 text-green-700 hover:bg-green-200">
                                    <i class="fas fa-thumbs-up mr-2"></i>
                                    Sí
                                </button>
                                <button onclick="submitFeedback('not_helpful')" class="flex items-center px-4 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200">
                                    <i class="fas fa-thumbs-down mr-2"></i>
                                    No
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Artículos Relacionados -->
                    @if($article->related->count() > 0)
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Artículos Relacionados</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($article->related as $related)
                            <a href="{{ route('help.documentation', $related->slug) }}" class="block p-4 rounded-lg border border-gray-200 hover:border-blue-500 hover:bg-blue-50">
                                <h4 class="font-medium text-gray-800">{{ $related->title }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($related->excerpt, 100) }}</p>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Navegación suave
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Envío de feedback
function submitFeedback(type) {
    fetch('/help/feedback', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            article_id: '{{ $article->id }}',
            type: type
        })
    }).then(response => {
        if (response.ok) {
            // Mostrar mensaje de agradecimiento
            alert('¡Gracias por tu feedback!');
        }
    });
}

// Búsqueda en la documentación
const searchInput = document.querySelector('input[type="text"]');
searchInput.addEventListener('input', function(e) {
    const query = e.target.value.toLowerCase();
    document.querySelectorAll('nav ul li a').forEach(link => {
        const text = link.textContent.toLowerCase();
        if (text.includes(query)) {
            link.parentElement.style.display = '';
        } else {
            link.parentElement.style.display = 'none';
        }
    });
});
</script>
@endpush
@endsection 