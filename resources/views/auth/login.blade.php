<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="relative group mt-2">
            <x-input-label for="email" :value="__('E-mail Corporativo')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-accent transition-colors">
                    <i class="fas fa-envelope"></i>
                </div>
                <x-text-input id="email" class="block w-full pl-12" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="seu.nome@sistema.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="relative group mt-6">
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Senha de Acesso')" class="mb-0" />
                @if (Route::has('password.request'))
                    <a class="text-[10px] font-bold text-accent hover:text-accent-hover transition-colors mb-2 tracking-widest uppercase" href="{{ route('password.request') }}">
                        {{ __('Esqueceu?') }}
                    </a>
                @endif
            </div>

            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-accent transition-colors">
                    <i class="fas fa-lock"></i>
                </div>
                <x-text-input id="password" class="block w-full pl-12"
                                type="password"
                                name="password"
                                required autocomplete="current-password"
                                placeholder="••••••••" />
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-6">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-accent shadow-sm focus:ring-accent/30 transition-all cursor-pointer" name="remember">
                <span class="ms-3 text-xs font-bold text-slate-500 group-hover:text-primary-dark transition-colors">{{ __('Manter conectado') }}</span>
            </label>
        </div>

        <div class="mt-8">
            <x-primary-button class="group">
                {{ __('Acessar Sistema') }}
                <i class="fas fa-arrow-right ml-2 opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
