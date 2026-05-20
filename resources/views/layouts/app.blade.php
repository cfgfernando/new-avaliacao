<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema Padrão') }} — @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@100..900&family=JetBrains+Mono:wght@700;800&display=swap" rel="stylesheet">

    <!-- Material Symbols (Stitch pattern) -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Font Awesome (legacy icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts CDN (ordem importa: jQuery primeiro) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

    <!-- Vite (depois do jQuery para Alpine não conflitar) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- ╔══════════════════════════════════════════════════
         ║ SIDEBAR TOGGLE — script inline puro no <head>
         ║ DEVE ficar aqui para estar disponível ANTES
         ║ que qualquer bundle Vite/Alpine processe a página
         ╚══════════════════════════════════════════════════ -->
    <script>
        function openSidebar() {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebar-overlay');
            if (!sidebar || !overlay) return;
            sidebar.style.transform = 'translateX(0)';
            sidebar.setAttribute('data-open', '1');
            overlay.style.display = 'block';
            requestAnimationFrame(function() { overlay.style.opacity = '1'; });
        }
        function closeSidebar() {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebar-overlay');
            if (!sidebar || !overlay) return;
            sidebar.style.transform = '';
            sidebar.removeAttribute('data-open');
            overlay.style.opacity = '0';
            setTimeout(function() { overlay.style.display = 'none'; }, 300);
        }
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            if (!sidebar) return;
            if (sidebar.getAttribute('data-open') === '1') {
                closeSidebar();
            } else {
                openSidebar();
            }
        }
    </script>


    <style>
        /* ─── BASE ─── */
        * { -webkit-font-smoothing: antialiased; }
        [x-cloak] { display: none !important; }
        body { font-family: 'Libre Franklin', sans-serif; }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        /* ─── SCROLLBAR ─── */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

        /* ─── SIDEBAR ─── */
        #sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: 280px;
            background: #1c2434;
            color: white;
            display: flex;
            flex-direction: column;
            z-index: 60;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
            box-shadow: 4px 0 24px rgba(0,0,0,0.15);
        }

        @media (min-width: 1024px) {
            #sidebar { transform: translateX(0); }
        }

        #sidebar-overlay {
            transition: opacity 0.3s ease;
        }

        /* ─── NAV LINKS ─── */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 11px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            color: rgba(255,255,255,0.65);
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            margin-bottom: 2px;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.9);
        }

        .nav-item.active {
            background: #f59e0b;
            color: white !important;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(245,158,11,0.35);
        }

        .nav-item.active .material-symbols-outlined,
        .nav-item.active i {
            color: white !important;
        }

        .nav-item-danger {
            color: rgba(248,113,113,0.8) !important;
        }

        .nav-item-danger:hover {
            background: rgba(248,113,113,0.1) !important;
            color: #f87171 !important;
        }

        /* ─── TOPBAR ─── */
        #topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 64px;
            background: #ffffff;
            color: #1e293b;
            z-index: 50;
            display: flex;
            align-items: center;
            padding: 0 20px;
            gap: 16px;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: left 0.3s ease;
        }

        @media (min-width: 1024px) {
            #topbar { left: 280px; }
        }

        /* ─── MAIN CONTENT ─── */
        #main-wrapper {
            padding-top: 64px;
            min-height: 100vh;
        }

        @media (min-width: 1024px) {
            #main-wrapper { margin-left: 280px; }
        }

        /* ─── SPA PROGRESS ─── */
        #spa-progress {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            height: 3px;
            background: #f59e0b;
            z-index: 9999;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.2s ease;
        }
        .htmx-request #spa-progress { transform: scaleX(1); }
        .htmx-request.nav-item { opacity: 0.6; pointer-events: none; }
        .nav-item * { pointer-events: none; }

        /* ─── CARDS & COMPONENTS ─── */
        .card-neo {
            background: white;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .btn-neo {
            display: inline-flex;
            align-items: center;
            border-radius: 12px;
            font-weight: 800;
            font-size: 10px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-neo:hover { transform: translateY(-1px); }
        .btn-neo:active { transform: translateY(0); }

        .btn-action {
            width: 40px; height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.25s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        .input-neo {
            width: 100%;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0 14px;
            font-size: 13px;
            font-weight: 500;
            color: #1e293b;
            transition: all 0.2s ease;
            outline: none;
        }

        .input-neo:focus {
            border-color: #f59e0b;
            background: white;
            box-shadow: 0 0 0 3px rgba(245,158,11,0.12);
        }

        .font-money {
            font-family: 'JetBrains Mono', monospace !important;
            font-weight: 800 !important;
        }

        /* ─── PRINT ─── */
        @media print {
            #sidebar, #topbar, .btn-neo, .no-print, button, form { display: none !important; }
            body { background: white !important; }
            #main-wrapper { margin: 0 !important; padding: 0 !important; }
            .card-neo { border: none !important; box-shadow: none !important; }
        }

        .print-header, .print-footer { display: none; }
    </style>
</head>
<body class="bg-[#f1f5f9] text-slate-800">

    <!-- SPA Progress Bar -->
    <div id="spa-progress"></div>

    <!-- ═══════════════════════════════════════
         SIDEBAR OVERLAY (mobile)
    ═══════════════════════════════════════ -->
    <div id="sidebar-overlay"
         style="display:none; opacity:0; transition: opacity 0.3s ease;"
         class="fixed inset-0 bg-black/50 z-[59]"
         onclick="closeSidebar()"></div>

    <!-- ═══════════════════════════════════════
         SIDEBAR
    ═══════════════════════════════════════ -->
    <aside id="sidebar">

        <!-- Logo -->
        <div class="px-8 py-8 flex items-center gap-4 shrink-0">
            <div class="w-12 h-12 bg-[#f59e0b] rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                <span class="material-symbols-outlined text-white text-[28px]"
                      style="font-variation-settings:'FILL' 1,'wght' 600">widgets</span>
            </div>
            <div class="flex flex-col">
                <span class="text-[17px] font-black text-white tracking-tight leading-tight uppercase">Painel</span>
                <span class="text-[9px] font-black text-[#f59e0b] tracking-[0.25em] uppercase mt-0.5">Administrativo</span>
            </div>
        </div>

        <!-- Nav -->
        <nav id="sidebar-nav"
             class="flex-1 px-4 pb-4 overflow-y-auto custom-scrollbar space-y-0.5"
             hx-target="#main-content"
             hx-select="#main-content"
             hx-swap="innerHTML transition:true"
             hx-push-url="true"
             hx-indicator="#spa-progress"
             hx-boost="false">

            @if(isset($menuCategories) && $menuCategories->count() > 0)
                @foreach($menuCategories as $category)
                    @php
                        $isAlert = $category->name === 'CONTROLE DE CRISE';
                        $catColor = $isAlert ? 'text-rose-400' : 'text-slate-500';
                    @endphp
                    <p class="px-4 pt-6 pb-3 text-[10px] font-black {{ $catColor }} uppercase tracking-[0.25em] opacity-80">
                        {{ $category->name }}
                    </p>

                    @foreach($category->items as $item)
                        @if($item->is_active && (!$item->is_admin_only || (Auth::user() && Auth::user()->role === 'Admin')))
                            @php
                                $itemUrlClean = trim($item->url, '/');
                                $isActive = request()->is($itemUrlClean) || request()->is($itemUrlClean . '/*');
                                $isDanger = $category->name === 'CONTROLE DE CRISE';
                            @endphp
                            <a href="{{ str_starts_with($item->url, 'http') ? $item->url : url($item->url) }}"
                               class="nav-item {{ $isActive ? 'active' : '' }} {{ $isDanger && !$isActive ? 'nav-item-danger' : '' }}">
                                <i class="{{ $item->icon ?: 'fas fa-link' }} w-5 text-[15px]"></i>
                                <span>{{ $item->title }}</span>
                                @if($item->title === 'Zona de Risco')
                                    <span class="ml-auto w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                                @endif
                            </a>
                        @endif
                    @endforeach
                @endforeach
            @else
                <p class="px-4 pt-6 pb-3 text-[10px] font-black text-slate-500 uppercase tracking-[0.25em]">Navegação Principal</p>
                <a href="{{ route('dashboard') }}"
                   class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-outlined text-[20px]">dashboard</span>
                    <span>Dashboard</span>
                </a>
            @endif

            {{-- Menus estáticos removidos para evitar duplicação com os dinâmicos --}}

            <!-- Logout -->
            <div class="pt-4 px-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="nav-item w-full nav-item-danger">
                        <i class="fas fa-power-off w-5 text-[14px]"></i>
                        <span>Encerrar Sessão</span>
                    </button>
                </form>
            </div>
        </nav>

        <!-- Footer -->
        <div class="px-6 pb-6 shrink-0">
            <div class="p-4 bg-white/5 rounded-xl border border-white/8">
                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest">Versão 2.5.0</p>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <p class="text-[10px] text-white/40">Status: Conectado</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- ═══════════════════════════════════════
         TOPBAR
    ═══════════════════════════════════════ -->
    <header id="topbar">
        <!-- Mobile menu button -->
        <button class="lg:hidden p-2 rounded-xl hover:bg-slate-100 transition-colors flex items-center justify-center"
                onclick="toggleSidebar()" id="toggle-sidebar">
            <span class="material-symbols-outlined text-slate-800 text-[24px]">menu</span>
        </button>

        <!-- Page title -->
        <div class="flex flex-col">
            <h1 class="text-[18px] font-black text-slate-800 tracking-tight leading-tight uppercase">
                @yield('title', 'Dashboard')
            </h1>
            <div class="hidden sm:flex items-center gap-2 text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                <span class="text-[#f59e0b]">Sistema Padrão</span>
                <span>›</span>
                <span class="text-slate-400">@yield('title', 'Dashboard')</span>
            </div>
        </div>

        <div class="ml-auto flex items-center gap-4">
            <!-- Search (desktop) -->
            <div class="hidden md:flex items-center gap-3 bg-slate-50 border border-gray-200 rounded-xl px-4 py-2
                        focus-within:bg-slate-100 focus-within:border-[#f59e0b]/50 transition-all duration-300">
                <span class="material-symbols-outlined text-slate-400 text-[18px]">search</span>
                <input type="text"
                       placeholder="Pesquisar no sistema..."
                       class="bg-transparent border-none outline-none focus:ring-0 text-[12px] font-medium w-52 placeholder:text-slate-400 text-slate-700">
            </div>

            <!-- User -->
            <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                <div class="text-right hidden sm:block">
                    <p class="text-[13px] font-black text-slate-800 leading-none">{{ Auth::user()->name ?? 'Administrador' }}</p>
                    <p class="text-[9px] font-bold text-[#f59e0b] mt-1 uppercase tracking-widest">{{ Auth::user()->role_label ?? 'Master' }}</p>
                </div>
                <div class="relative">
                    <div class="w-10 h-10 rounded-xl bg-[#f59e0b] flex items-center justify-center text-white font-black text-sm shadow-lg shadow-amber-500/30 cursor-pointer">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                    </div>
                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-400 border-2 border-white rounded-full"></div>
                </div>
            </div>
        </div>
    </header>

    <!-- ═══════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════ -->
    <div id="main-wrapper">
        <div id="main-content"
             class="p-6 md:p-10 max-w-[1600px] mx-auto w-full transition-opacity duration-300"
             hx-target="#main-content"
             hx-select="#main-content"
             hx-swap="innerHTML transition:true"
             hx-boost="true">
            @yield('content')
            {{ $slot ?? '' }}
        </div>
    </div>

    @stack('modals')
    @stack('scripts')

    <script>
    $(document).ready(function () {
        // Persistência de scroll da sidebar
        var sidebarNav = document.getElementById('sidebar-nav');
        var savedPos = localStorage.getItem('sidebarScrollPos');
        if (savedPos && sidebarNav) sidebarNav.scrollTop = savedPos;

        // Links da sidebar
        $('#sidebar-nav a').on('click', function () {
            $('#sidebar-nav a').removeClass('active');
            $(this).addClass('active');
            if (sidebarNav) localStorage.setItem('sidebarScrollPos', sidebarNav.scrollTop);
            if (window.innerWidth < 1024) closeSidebar();
        });

        // Máscaras
        $('.mask-money').mask('#.##0,00', {reverse: true});
        $('.mask-phone').mask('(00) 00000-0000');
        $('.mask-cpf').mask('000.000.000-00');

        // HTMX after swap
        document.addEventListener('htmx:afterSwap', function (evt) {
            htmx.process(evt.detail.elt);

            if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                window.Alpine.initTree(evt.detail.elt);
            }

            $('.mask-money').mask('#.##0,00', {reverse: true});
            $('.mask-phone').mask('(00) 00000-0000');
            $('.mask-cpf').mask('000.000.000-00');

            if (window.innerWidth < 1024) closeSidebar();
            window.scrollTo({ top: 0, behavior: 'smooth' });

            var currentPath = window.location.pathname.replace(/\/$/, "");
            $('#sidebar-nav a').removeClass('active');
            $('#sidebar-nav a').each(function () {
                var href = $(this).attr('href');
                if (href) {
                    var hrefPath = new URL(href, window.location.origin).pathname.replace(/\/$/, "");
                    if (hrefPath && (currentPath === hrefPath || currentPath.startsWith(hrefPath + '/'))) {
                        $(this).addClass('active');
                    }
                }
            });
        });
    });
    </script>
</body>
</html>
