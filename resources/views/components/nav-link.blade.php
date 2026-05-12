@props(['active', 'icon' => null])

@php
$classes = ($active ?? false)
            ? 'nav-link-neo active'
            : 'nav-link-neo';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <i class="{{ $icon }} w-5"></i>
    @endif
    <span>{{ $slot }}</span>
</a>
