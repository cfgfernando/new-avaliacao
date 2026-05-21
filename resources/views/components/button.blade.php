@props([
    'variant' => 'primary',
    'href' => null,
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 px-6 py-3 font-bold transition-all duration-200 active:scale-95 text-sm cursor-pointer rounded-lg';
    
    $variants = [
        'primary' => 'bg-accent hover:bg-accent-hover text-white shadow-md shadow-blue-500/10 hover:-translate-y-0.5 border border-transparent',
        'secondary' => 'bg-slate-100 hover:bg-slate-200 text-slate-700 border border-transparent',
        'tertiary' => 'bg-transparent text-slate-700 hover:bg-slate-50 hover:text-slate-950 border border-transparent hover:border-slate-200',
    ];
    
    $class = $variants[$variant] ?? $variants['primary'];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "$baseClasses $class"]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClasses $class"]) }}>
        {{ $slot }}
    </button>
@endif
