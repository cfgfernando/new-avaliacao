@props(['variant' => 'primary', 'type' => 'button'])

@php
    $baseClass = 'btn-neo';
    $variantClass = [
        'primary' => 'bg-accent hover:bg-accent-hover text-white shadow-md shadow-blue-500/10 hover:-translate-y-0.5',
        'secondary' => 'bg-background text-primary-dark hover:bg-slate-200',
        'tertiary' => 'bg-transparent text-primary-dark hover:underline hover:bg-slate-50',
    ][$variant] ?? '';
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClass $variantClass"]) }}>
    {{ $slot }}
</button>
