@props(['color' => 'blue'])

@php
    $classes = [
        'blue' => 'bg-semantic-blue/10 text-semantic-blue',
        'green' => 'bg-semantic-green/10 text-semantic-green',
        'purple' => 'bg-semantic-purple/10 text-semantic-purple',
        'red' => 'bg-semantic-red/10 text-semantic-red',
        'amber' => 'bg-amber-500/10 text-amber-600',
    ][$color] ?? 'bg-slate-100 text-slate-600';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border border-transparent uppercase tracking-wider $classes"]) }}>
    {{ $slot }}
</span>
