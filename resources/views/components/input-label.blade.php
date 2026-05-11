@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-black text-gray-500 uppercase tracking-widest mb-2']) }}>
    {{ $value ?? $slot }}
</label>
