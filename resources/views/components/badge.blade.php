@props([
    'variant' => 'info', // success, info, warning, danger, neutral
])

@php
    $variants = [
        'success' => 'bg-green-50 text-green-600 border-green-200',
        'info' => 'bg-blue-50 text-blue-600 border-blue-200',
        'warning' => 'bg-amber-50 text-amber-600 border-amber-200',
        'danger' => 'bg-red-50 text-red-600 border-red-200',
        'neutral' => 'bg-slate-50 text-slate-500 border-slate-200',
    ];
    
    $class = $variants[$variant] ?? $variants['neutral'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 font-medium text-xs px-2.5 py-0.5 rounded-full border $class"]) }}>
    {{ $slot }}
</span>
