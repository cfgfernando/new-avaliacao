<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MDA Church ERP') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>
<body class="font-sans antialiased bg-background text-primary-dark">
    <div class="flex min-h-screen overflow-hidden">
        <!-- SIDEBAR -->
        <aside id="sidebar" class="sidebar-neo shrink-0 -translate-x-full lg:translate-x-0">
            <div class="flex items-center gap-5 px-10 py-12">
                <div class="w-12 h-12 bg-gradient-to-br from-accent to-accent-hover rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg shadow-accent/20">
                    <i class="fas fa-church"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-black tracking-tighter text-white uppercase leading-none">MDA Church</span>
                    <span class="text-[9px] font-black tracking-[0.4em] text-accent mt-1 uppercase">Enterprise</span>
                </div>
            </div>

            <nav class="mt-4 px-2 space-y-1.5 overflow-y-auto max-h-[calc(100vh-350px)] custom-scrollbar">
                @if(isset($menuCategories) && $menuCategories->count() > 0)
                    @foreach($menuCategories as $category)
                        <div class="text-[10px] font-black text-primary-light uppercase tracking-[0.3em] px-8 mb-4 mt-8 opacity-50">{{ $category->name }}</div>
                        
                        @foreach($category->items as $item)
                            @if($item->is_active && (!$item->is_admin_only || (Auth::user() && Auth::user()->role === 'Admin')))
                                <a href="{{ str_starts_with($item->url, 'http') ? $item->url : url($item->url) }}" 
                                   class="nav-link-neo {{ request()->is(trim($item->url, '/')) ? 'active' : '' }}">
                                    <i class="{{ $item->icon ?: 'fas fa-link' }} w-5"></i>
                                    <span>{{ $item->title }}</span>
                                </a>
                            @endif
                        @endforeach
                    @endforeach
                @else
                    <div class="text-[10px] font-black text-primary-light uppercase tracking-[0.3em] px-8 mb-6 opacity-50">Navegação Principal</div>
                    
                    <a href="{{ route('dashboard') }}" class="nav-link-neo {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-th-large w-5"></i>
                        <span>Dashboard</span>
                    </a>
                @endif

                @if(Auth::user() && Auth::user()->role === 'Admin')
                    <div class="pt-10 text-[10px] font-black text-rose-400 uppercase tracking-[0.3em] px-8 mb-6 opacity-50">Administração</div>

                    <a href="{{ route('admin.menus.index') }}" class="nav-link-neo {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                        <i class="fas fa-bars-staggered w-5"></i>
                        <span>Gerenciar Menus</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="nav-link-neo {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users w-5"></i>
                        <span>Usuários</span>
                    </a>

                    <a href="{{ route('admin.roles.index') }}" class="nav-link-neo {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <i class="fas fa-shield-alt w-5"></i>
                        <span>Perfis (Roles)</span>
                    </a>

                    <a href="{{ route('admin.permissions.index') }}" class="nav-link-neo {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                        <i class="fas fa-key w-5"></i>
                        <span>Permissões</span>
                    </a>

                    <a href="{{ route('admin.logs.index') }}" class="nav-link-neo {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                        <i class="fas fa-fingerprint w-5"></i>
                        <span>Logs de Sistema</span>
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="pt-10 px-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-4 px-6 py-4 text-rose-400 font-bold text-xs uppercase tracking-widest hover:bg-rose-500/10 hover:text-rose-500 hover:translate-x-1 rounded-xl transition-all duration-300 group">
                        <i class="fas fa-power-off group-hover:rotate-90 group-hover:scale-110 transition-transform"></i>
                        <span>Encerrar Sessão</span>
                    </button>
                </form>
            </nav>
            
            <!-- Sidebar Footer Info -->
            <div class="absolute bottom-10 left-0 w-full px-10">
                <div class="p-5 bg-white/5 rounded-2xl border border-white/5">
                    <p class="text-[10px] font-bold text-primary-light uppercase tracking-widest">Versão 2.5.0</p>
                    <p class="text-[9px] text-primary-light/50 mt-1">Status: Conectado</p>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto">
            <!-- HEADER / TOPBAR (GLASS) -->
            <header class="topbar-neo">
                <div class="flex items-center gap-6">
                    <button id="toggle-sidebar" class="lg:hidden text-primary-dark text-xl p-3 hover:bg-gray-100 rounded-xl transition-all">
                        <i class="fas fa-bars-staggered"></i>
                    </button>
                    <div class="flex flex-col">
                        <h1 class="text-2xl font-black text-primary-dark tracking-tight uppercase">@yield('title', 'Dashboard')</h1>
                        <div class="flex items-center gap-2 text-[10px] text-primary-light font-bold uppercase tracking-widest">
                            <span class="text-accent">Workspace</span>
                            <i class="fas fa-chevron-right text-[8px] opacity-30"></i>
                            <span>Visão Geral</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-8">
                    <!-- Search -->
                    <div class="hidden md:flex items-center bg-slate-100/50 px-5 py-2.5 rounded-xl border border-slate-200/60 focus-within:border-accent focus-within:bg-white focus-within:shadow-xl focus-within:shadow-accent/10 group transition-all duration-500 hover:bg-white">
                        <i class="fas fa-search text-slate-400 group-focus-within:text-accent transition-colors"></i>
                        <input type="text" placeholder="Pesquisar no sistema..." class="bg-transparent border-none focus:ring-0 text-xs font-bold w-72 ml-3 placeholder:text-slate-400 text-primary-dark">
                    </div>

                    <!-- User Profile -->
                    <div class="flex items-center gap-4 pl-8 border-l border-slate-200">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-black text-primary-dark leading-none tracking-tight">{{ Auth::user()->name ?? 'Administrador' }}</p>
                            <p class="text-[10px] font-bold text-accent mt-1.5 uppercase tracking-widest">Master</p>
                        </div>
                        <div class="relative group">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 border border-slate-300 flex items-center justify-center text-primary-dark font-black shadow-sm group-hover:shadow-lg group-hover:-translate-y-0.5 transition-all duration-300 cursor-pointer">
                                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                            </div>
                            <div class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 border-2 border-white rounded-full shadow-sm group-hover:scale-110 transition-transform"></div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- PAGE CONTENT -->
            <div class="p-10 max-w-[1600px] mx-auto w-full">
                @yield('content')
                {{ $slot ?? '' }}
            </div>
        </main>
    </div>

    @stack('scripts')
    <script>
        $(document).ready(function() {
            // Sidebar Toggle for Mobile
            $('#toggle-sidebar').on('click', function() {
                $('#sidebar').toggleClass('-translate-x-full');
            });

            // Input masking examples
            $('.mask-money').mask('#.##0,00', {reverse: true});
            $('.mask-phone').mask('(00) 00000-0000');
            $('.mask-cpf').mask('000.000.000-00');

            // Fluid UI Interactions
            $('input, select').on('focus', function() {
                $(this).closest('.input-group').find('i').addClass('text-accent scale-110');
            }).on('blur', function() {
                $(this).closest('.input-group').find('i').removeClass('text-accent scale-110');
            });
        });
    </script>
</body>
</html>
