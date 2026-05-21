@props([
    'title',
    'value',
    'icon',
    'iconBg' => 'bg-blue-50',
    'iconColor' => 'text-blue-600',
    'badge' => null,
    'badgeBg' => 'bg-blue-50',
    'badgeColor' => 'text-blue-700',
    'badgeBorder' => 'border-blue-100',
    'borderLeft' => false,
])

<div {{ $attributes->merge(['class' => 'bg-white p-6 rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex items-center justify-between' . ($borderLeft ? ' border-l-4 border-l-blue-600' : '')]) }}>
    <div class="flex items-center gap-4">
        <!-- Ícone Grande à Esquerda -->
        <div class="w-14 h-14 shrink-0 rounded-lg flex items-center justify-center {{ $iconBg }} {{ $iconColor }}">
            @if(str_starts_with($icon, 'fa-') || str_starts_with($icon, 'fas ') || str_starts_with($icon, 'far '))
                <i class="{{ $icon }} text-xl"></i>
            @else
                <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 0, 'wght' 500">{{ $icon }}</span>
            @endif
        </div>
        <!-- Estatísticas à Direita -->
        <div>
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">{{ $title }}</p>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight mt-1 font-mono">{{ $value }}</h2>
        </div>
    </div>
    
    @if($badge)
        <div class="self-start">
            <span class="text-xs font-bold px-2.5 py-1 {{ $badgeBg }} {{ $badgeColor }} rounded-full border {{ $badgeBorder }}">
                {{ $badge }}
            </span>
        </div>
    @endif
</div>
