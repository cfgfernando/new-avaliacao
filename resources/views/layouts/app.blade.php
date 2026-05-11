<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MDA CHURCH') }} - Elite V8 ERP</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
</head>
<body class="bg-main text-body antialiased selection:bg-accent selection:text-white">

    <div class="flex min-h-screen">
        <!-- SIDEBAR (280px Width as per specification) -->
        <aside class="w-[280px] sidebar-gradient hidden lg:flex flex-col sticky top-0 h-screen z-40">
            <div class="p-10">
                <div class="flex items-center gap-3 group">
                    <div class="w-10 h-10 gold-gradient rounded-lg flex items-center justify-center shadow-lg shadow-accent/20 transition-transform group-hover:rotate-6">
                        <i class="fas fa-church text-white text-xl"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-display font-black text-title tracking-tighter uppercase leading-none">MDA <span class="text-accent">CHURCH</span></span>
                        <span class="text-[9px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-1">Enterprise Solution</span>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-6 space-y-1 py-4">
                <div class="pb-3 pt-2">
                    <span class="text-[10px] font-black text-slate-500 dark:text-slate-600 uppercase tracking-[0.2em] px-4">Menu Principal</span>
                </div>
                
                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="fas fa-grid-2">Dashboard</x-nav-link>
                <x-nav-link href="{{ route('cells.index') }}" :active="request()->routeIs('cells.*')" icon="fas fa-users-rectangle">Gestão de Células</x-nav-link>
                <x-nav-link href="{{ route('members.index') }}" :active="request()->routeIs('members.*')" icon="fas fa-user-group">Membros & Discípulos</x-nav-link>
                <x-nav-link href="{{ route('reports.index') }}" :active="request()->routeIs('reports.*')" icon="fas fa-file-invoice-dollar">Relatórios Semanais</x-nav-link>

                @if(auth()->user()->role === 'Admin' || auth()->user()->role === 'Treasurer')
                <div class="pt-8 pb-3">
                    <span class="text-[10px] font-black text-slate-500 dark:text-slate-600 uppercase tracking-[0.2em] px-4">Controladoria</span>
                </div>
                <x-nav-link href="{{ route('finance.reports.dre') }}" :active="request()->routeIs('finance.*')" icon="fas fa-vault">Contabilidade (DRE)</x-nav-link>
                <x-nav-link href="{{ route('admin.audits.index') }}" :active="request()->routeIs('admin.audits.*')" icon="fas fa-fingerprint">Auditoria Forense</x-nav-link>
                @endif
            </nav>

            <div class="p-8 border-t border-slate-200 dark:border-white/5 bg-slate-100/50 dark:bg-black/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-4 w-full px-6 py-4 text-red-700 dark:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-2xl transition-all font-black text-xs uppercase tracking-widest">
                        <i class="fas fa-power-off text-lg"></i>
                        <span>Encerrar Sessão</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- FLOATING HEADER -->
            <div class="px-8 pt-8">
                <header class="glassmorphism rounded-3xl h-20 flex items-center justify-between px-10 shadow-lg shadow-black/5">
                    <div class="flex items-center gap-6">
                        <!-- Mobile Menu Trigger -->
                        <button class="lg:hidden text-title text-2xl">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h2 class="text-xl font-display font-black text-title tracking-tighter">
                            @yield('header_title', 'Visão Geral')
                        </h2>
                    </div>

                    <div class="flex items-center gap-8">
                        <!-- Theme Toggle -->
                        <button id="theme-toggle" class="w-10 h-10 rounded-xl bg-gray-500/5 dark:bg-white/5 border border-black/5 dark:border-white/10 flex items-center justify-center text-gray-500 hover:text-accent transition-all">
                            <i class="fas fa-sun hidden dark:block"></i>
                            <i class="fas fa-moon block dark:hidden"></i>
                        </button>

                        <!-- Notifications/Search -->
                        <div class="hidden md:flex items-center gap-6 text-slate-400">
                            <div class="relative group cursor-pointer">
                                <i class="fas fa-search group-hover:text-accent transition-colors"></i>
                            </div>
                            <div class="relative group cursor-pointer">
                                <i class="fas fa-bell group-hover:text-accent transition-colors"></i>
                                <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-primary-dark"></span>
                            </div>
                        </div>

                        <!-- User Profile -->
                        <div class="flex items-center gap-4 pl-8 border-l border-slate-200 dark:border-white/10">
                            <div class="flex flex-col text-right hidden sm:block">
                                <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-tighter">Administrador do Sistema</span>
                                <span class="text-xs font-black text-accent uppercase tracking-widest">ADMIN</span>
                            </div>
                            <div class="w-11 h-11 rounded-full gold-gradient p-[2px] shadow-lg shadow-orange-500/20">
                                <div class="w-full h-full bg-main rounded-full flex items-center justify-center text-white font-black text-sm">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
            </div>

            <!-- VIEWPORT CONTENT -->
            <main class="p-10 animate-fade-up">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
        // Lógica de Troca de Tema (Claro/Escuro)
        const themeToggleBtn = document.getElementById('theme-toggle');
        const htmlElement = document.documentElement;

        // Verifica preferência salva
        if (localStorage.getItem('theme') === 'light') {
            htmlElement.classList.remove('dark');
        } else {
            htmlElement.classList.add('dark');
        }

        themeToggleBtn.addEventListener('click', () => {
            if (htmlElement.classList.contains('dark')) {
                htmlElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                htmlElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        });
    </script>
</body>
</html>
