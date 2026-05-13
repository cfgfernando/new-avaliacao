@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-black text-start text-base font-black text-black bg-slate-100 focus:outline-none focus:text-black focus:bg-slate-200 focus:border-black transition duration-150 ease-in-out uppercase italic tracking-widest'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-bold text-slate-500 hover:text-black hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:text-black focus:bg-slate-50 focus:border-slate-300 transition duration-150 ease-in-out uppercase italic tracking-widest';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
