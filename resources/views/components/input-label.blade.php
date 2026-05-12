@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-[11px] font-black text-primary-dark uppercase tracking-widest mb-2']) }}>
    {{ $value ?? $slot }}
</label>
