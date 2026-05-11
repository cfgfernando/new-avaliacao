<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MDA CHURCH') }} - Neo-Architectural ERP</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-primary-dark text-neutral-400 antialiased selection:bg-accent selection:text-black bg-mesh-dark">

    <div class="flex min-h-screen relative overflow-hidden">
        <!-- ASYMMETRIC SIDEBAR -->
        <aside class="w-[320px] sidebar-neo hidden lg:flex flex-col sticky top-0 h-screen z-40 bg-black/50 backdrop-blur-md">
            <div class="p-12">
                <div class="flex flex-col gap-2 group">
                    <div class="w-12 h-12 bg-white flex items-center justify-center transition-all group-hover:bg-accent group-hover:-translate-y-1">
                        <i class="fas fa-church text-black text-2xl"></i>
                    </div>
                    <div class="flex flex-col mt-4">
                        <span class="text-3xl font-display font-black text-white tracking-[0.1em] uppercase leading-none">MDA</span>
                        <span class="text-xs text-accent font-black uppercase tracking-[0.4em] mt-1">CHURCH</span>
                    </div>
                </div>
            </div>

            <nav class="flex-1 space-y-1 py-8">
                <div class="px-12 mb-8">
                    <div class="h-[1px] w-8 bg-accent/30"></div>
                </div>
                
                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="fas fa-grid-2">Dashboard</x-nav-link>
                <x-nav-link href="{{ route('cells.index') }}" :active="request()->routeIs('cells.*')" icon="fas fa-users-rectangle">Gestão de Células</x-nav-link>
                <x-nav-link href="{{ route('members.index') }}" :active="request()->routeIs('members.*')" icon="fas fa-user-group">Membros & Discípulos</x-nav-link>
                <x-nav-link href="{{ route('reports.index') }}" :active="request()->routeIs('reports.*')" icon="fas fa-file-invoice-dollar">Relatórios Semanais</x-nav-link>

                @if(auth()->user()->role === 'Admin' || auth()->user()->role === 'Treasurer')
                <div class="px-12 py-8">
                    <div class="h-[1px] w-8 bg-neutral-800"></div>
                </div>
                <x-nav-link href="{{ route('finance.reports.dre') }}" :active="request()->routeIs('finance.*')" icon="fas fa-vault">Contabilidade</x-nav-link>
                <x-nav-link href="{{ route('admin.audits.index') }}" :active="request()->routeIs('admin.audits.*')" icon="fas fa-fingerprint">Auditoria</x-nav-link>
                @endif
            </nav>

            <div class="p-12 border-t border-white/5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-4 text-neutral-600 hover:text-white transition-all font-black text-[10px] uppercase tracking-[0.2em]">
                        <i class="fas fa-power-off"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- ASYMMETRIC HEADER -->
            <header class="h-32 flex items-center justify-between px-16 z-30">
                <div class="flex flex-col">
                    <span class="text-[10px] font-black text-accent uppercase tracking-[0.5em] mb-2">Workspace</span>
                    <h2 class="text-4xl font-display font-black text-white tracking-tighter uppercase">
                        @yield('header_title', 'Visão Geral')
                    </h2>
                </div>

                <div class="flex items-center gap-12">
                    <!-- Search -->
                    <div class="hidden md:flex items-center gap-4 text-neutral-500 border-b border-white/5 pb-2 transition-all focus-within:border-accent">
                        <i class="fas fa-search text-xs"></i>
                        <input type="text" placeholder="BUSCAR..." class="bg-transparent border-none focus:ring-0 p-0 text-[10px] font-black tracking-widest placeholder:text-neutral-700 w-40">
                    </div>

                    <!-- User -->
                    <div class="flex items-center gap-6 group cursor-pointer">
                        <div class="flex flex-col text-right">
                            <span class="text-[10px] font-black text-white uppercase tracking-widest">{{ Auth::user()->name }}</span>
                            <span class="text-[8px] font-black text-accent uppercase tracking-[0.3em]">Authorized</span>
                        </div>
                        <div class="w-12 h-12 bg-white flex items-center justify-center transition-all group-hover:bg-accent group-hover:-rotate-12">
                            <i class="fas fa-user text-black"></i>
                        </div>
                    </div>
                </div>
            </header>

            <!-- VIEWPORT -->
            <main class="px-16 pb-16">
                <div class="reveal-stagger">
                    {{ $slot ?? '' }}
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- DECORATIVE ELEMENTS (Z-Axis Depth) -->
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-accent/5 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="absolute top-1/2 -left-20 w-64 h-64 bg-accent/5 blur-[100px] rounded-full pointer-events-none"></div>
    </div>

    @stack('scripts')
</body>
</html>

</html>
