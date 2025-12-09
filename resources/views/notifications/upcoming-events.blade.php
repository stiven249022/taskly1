@extends('layouts.dashboard')

@section('title', 'Eventos Próximos')
@section('page-title', 'Eventos Próximos')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <i class="fas fa-bell text-indigo-600 dark:text-indigo-400"></i>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Eventos próximos</h3>
            <span class="ml-2 px-2 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full text-xs font-medium">
                {{ collect($events)->count() }}
            </span>
        </div>
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
            Volver al dashboard
        </a>
    </div>

    @php
        $eventsList = collect($events);
    @endphp

    @if($eventsList->isEmpty())
        <div class="p-12 text-center">
            <i class="fas fa-calendar-check text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
            <p class="text-sm text-gray-500 dark:text-gray-400">No hay eventos próximos</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Las notificaciones aparecerán aquí cuando tengas eventos próximos</p>
        </div>
    @else
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($eventsList as $event)
                <a href="{{ $event['url'] ?? '#' }}" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-start space-x-3">
                        @php
                            $iconClass = 'fas fa-bell';
                            $iconBg = 'bg-amber-100 dark:bg-amber-900';
                            $iconColor = 'text-amber-600 dark:text-amber-400';
                            if ($event['type'] === 'task') { $iconClass = 'fas fa-tasks'; $iconBg = 'bg-blue-100 dark:bg-blue-900'; $iconColor = 'text-blue-600 dark:text-blue-400'; }
                            elseif ($event['type'] === 'project') { $iconClass = 'fas fa-project-diagram'; $iconBg = 'bg-green-100 dark:bg-green-900'; $iconColor = 'text-green-600 dark:text-green-400'; }
                            $days = max(0, min(999, (int)($event['days_until_due'] ?? 0)));
                        @endphp
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full {{ $iconBg }} flex items-center justify-center">
                                <i class="{{ $iconClass }} {{ $iconColor }}"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $event['title'] }}</h4>
                                    <div class="flex items-center space-x-3 mt-1 flex-wrap">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ \Carbon\Carbon::parse($event['date'])->format('d/m/Y') }}
                                        </span>
                                        <span class="text-xs font-medium @if($days===0) text-red-600 dark:text-red-400 @elseif($days===1) text-orange-600 dark:text-orange-400 @else text-blue-600 dark:text-blue-400 @endif">
                                            @if($days===0)
                                                <i class="fas fa-exclamation-circle mr-1"></i> ¡Vence HOY!
                                            @elseif($days===1)
                                                <i class="fas fa-clock mr-1"></i> Vence mañana
                                            @else
                                                <i class="fas fa-clock mr-1"></i> {{ $days }} días restantes
                                            @endif
                                        </span>
                                        @if(!empty($event['course']))
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                <i class="fas fa-book mr-1"></i> {{ $event['course'] }}
                                            </span>
                                        @endif
                                        @if(isset($event['priority']) && $event['type'] === 'task')
                                            @php
                                                $priorityClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                                if (($event['priority'] ?? '') === 'high') { $priorityClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'; }
                                                elseif (($event['priority'] ?? '') === 'medium') { $priorityClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'; }
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $priorityClass }}">
                                                {{ ucfirst($event['priority']) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
