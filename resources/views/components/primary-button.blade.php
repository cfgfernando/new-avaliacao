<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full btn-primary uppercase tracking-widest text-xs py-4']) }}>
    {{ $slot }}
</button>
