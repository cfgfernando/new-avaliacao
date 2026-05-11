@props(['active', 'icon'])

@php
$classes = ($active ?? false)
            ? 'nav-link-neo active'
            : 'nav-link-neo';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <div class="w-8 flex justify-center">
        <i class="{{ $icon }} text-base {{ $active ? 'text-accent' : '' }}"></i>
    </div>
    <span class="">{{ $slot }}</span>
</a>

