<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full btn-neo btn-primary uppercase tracking-widest text-xs py-4 shadow-xl']) }}>
    {{ $slot }}
</button>
