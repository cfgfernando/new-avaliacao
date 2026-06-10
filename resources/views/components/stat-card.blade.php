@props(['title', 'value', 'icon', 'color' => 'blue', 'trend' => null, 'trendUp' => true, 'subtitle' => null])

@php
    $iconColors = [
        'blue' => 'bg-semantic-blue/10 text-semantic-blue border-semantic-blue/20',
        'green' => 'bg-semantic-green/10 text-semantic-green border-semantic-green/20',
        'purple' => 'bg-semantic-purple/10 text-semantic-purple border-semantic-purple/20',
        'amber' => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
    ][$color] ?? 'bg-slate-100 text-slate-600 border-slate-200';
@endphp

<x-card>
    <div class="flex justify-between items-start">
        <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">{{ $title }}</p>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center border {{ $iconColors }}">
            <span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>
        </div>
    </div>
    <div class="mt-4 flex items-baseline gap-2">
        <span class="text-3xl font-bold font-mono text-slate-900 tracking-tight">{{ $value }}</span>
        @if($trend)
            <div class="flex items-center {{ $trendUp ? 'text-semantic-green' : 'text-semantic-red' }}">
                <span class="text-[10px] font-mono font-bold">{{ $trend }}</span>
            </div>
        @endif
    </div>
    @if($subtitle)
        <p class="text-[11px] text-slate-500 mt-1.5 font-medium">{{ $subtitle }}</p>
    @else
        {{ $slot }}
    @endif
</x-card>
