@props(['type' => null, 'message' => null])

@php
    $type = $type ?? (session('success') ? 'success' : (session('error') ? 'error' : (session('warning') ? 'warning' : (session('info') ? 'info' : null))));
    $message = $message ?? session($type);
    
    $config = match ($type) {
        'success' => [
            'classes' => 'bg-green-50 border-green-200 text-green-800',
            'icon' => '<svg class="w-5 h-5 text-green-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        ],
        'error' => [
            'classes' => 'bg-red-50 border-red-200 text-red-800',
            'icon' => '<svg class="w-5 h-5 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        ],
        'warning' => [
            'classes' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
            'icon' => '<svg class="w-5 h-5 text-yellow-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>',
        ],
        'info' => [
            'classes' => 'bg-blue-50 border-blue-200 text-blue-800',
            'icon' => '<svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        ],
        default => null,
    };
@endphp

@if ($config && $message)
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, '{{ $type }}' === 'error' ? 8000 : 4000)"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         {{ $attributes->merge(['class' => 'flex items-center gap-3 p-4 rounded-xl border ' . $config['classes']]) }}>
        {!! $config['icon'] !!}
        <p class="text-sm font-medium">{{ $message }}</p>
    </div>
@endif

