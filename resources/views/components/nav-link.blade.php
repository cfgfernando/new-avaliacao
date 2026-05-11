@props(['active', 'icon'])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-4 px-6 py-4 rounded-xl nav-link-active transition-all group'
            : 'flex items-center gap-4 px-6 py-4 rounded-xl nav-link-inactive transition-all group';

$iconClasses = ($active ?? false)
                ? 'nav-icon-active'
                : 'nav-icon-inactive';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <div class="w-6 flex justify-center">
        <i class="{{ $icon }} {{ $iconClasses }} text-lg"></i>
    </div>
    <span class="text-sm tracking-tight">{{ $slot }}</span>
</a>
