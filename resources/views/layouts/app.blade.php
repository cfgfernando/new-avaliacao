<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema Padrão') }} — @yield('title', 'Dashboard')</title>

    <!-- Fonts (Local) -->
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">

    <!-- Material Symbols (Local) -->
    <link rel="stylesheet" href="{{ asset('css/material-symbols.css') }}">

    <!-- Font Awesome (Local) -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">

    <!-- Scripts (Local - jQuery First) -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-mask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('vendor/htmx/htmx.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/chartjs/chart.umd.js') }}"></script>

    <!-- DataTables CSS (Local) -->
    <link rel="stylesheet" href="{{ asset('vendor/datatables/jquery.dataTables.min.css') }}">

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
        body { font-family: 'Inter', sans-serif; }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        /* ─── SCROLLBAR ─── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(100, 116, 139, 0.2); border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(100, 116, 139, 0.4); }

        /* ─── SIDEBAR ─── */
        #sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: 280px;
            background: #0f172a;
            color: #8a99af;
            display: flex;
            flex-direction: column;
            z-index: 60;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
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
            border-radius: 8px; /* ROUND_EIGHT (8px) */
            font-size: 14px;
            font-weight: 500;
            color: #8a99af;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            margin-bottom: 2px;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
        }

        .nav-item.active {
            background: #2563eb !important;
            color: #ffffff !important;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2) !important;
            border-left: none;
        }

        .nav-item.active .material-symbols-outlined,
        .nav-item.active i {
            color: #ffffff !important;
        }

        .nav-item-danger {
            color: #e11d48 !important; /* text-rose-600 */
        }

        .nav-item-danger:hover {
            background: #fff1f2 !important; /* bg-rose-50 */
            color: #be123c !important; /* text-rose-700 */
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
            background: #2563eb;
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
            border-radius: 12px; /* rounded-xl (12px) */
            border: 1px solid rgba(226, 232, 240, 0.6); /* border-slate-200/60 */
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* shadow-sm */
            transition: all 0.3s ease;
        }
 
        .card-neo:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        .btn-neo {
            display: inline-flex;
            align-items: center;
            border-radius: 8px; /* rounded-lg (8px) */
            font-weight: 750;
            font-size: 14px;
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
            border-radius: 8px; /* ROUND_EIGHT (8px) */
            transition: all 0.25s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        .input-neo {
            width: 100%;
            background: rgba(248, 250, 252, 0.5); /* bg-slate-50/50 */
            border: 1px solid #e2e8f0;
            border-radius: 8px; /* rounded-lg (8px) */
            padding: 14px 16px; /* py-3.5 px-4 */
            font-size: 14px;
            font-weight: 500;
            color: #111827; /* primary-dark */
            transition: all 0.3s ease;
            outline: none;
        }

        /* Foco com Azul Semântico conforme item 6 do Design System */
        input:focus, select:focus, textarea:focus {
            border-color: #3b82f6 !important;
            background-color: #ffffff !important;
            outline: none !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15) !important;
        }

        /* Campos com máscara (jquery.mask.js) e valores em Azul Royal ao focar */
        .mask-money:focus, .mask-phone:focus, .mask-cpf:focus, .mask-cpfcnpj:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15) !important;
            color: #2563eb !important;
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
<body class="bg-background text-primary-dark font-sans">

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
        <div class="px-6 py-6 flex items-center gap-3 shrink-0">
            <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center shadow-md shadow-accent/20">
                <span class="text-white text-md font-black tracking-tighter">SAD</span>
            </div>
            <div class="flex flex-col">
                <span class="text-[13px] font-bold text-white tracking-tight leading-none uppercase">SAD-BARS</span>
                <span class="text-[8px] font-semibold text-slate-400 mt-1 uppercase tracking-wider">Metodologia Mista</span>
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
                        $catColor = $isAlert ? 'text-rose-500' : 'text-slate-400';
                    @endphp
                    <p class="px-4 pt-6 pb-3 text-[9px] font-bold {{ $catColor }} uppercase tracking-widest font-mono">
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
                <a href="{{ route('evaluations.index') }}"
                   class="nav-item {{ request()->routeIs('evaluations.index') || request()->is('evaluations*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined text-[20px]">description</span>
                    <span>Minhas Avaliações</span>
                </a>
                @if(Auth::user() && Auth::user()->isAdmin())
                    <a href="{{ route('admin.evaluations.archived') }}" class="nav-item {{ request()->routeIs('admin.evaluations.archived') ? 'active' : '' }}">
                        <i class="fas fa-archive w-5 text-[15px]"></i>
                        <span>Avaliações Arquivadas</span>
                    </a>
                @endif
            @endif

            @if(Auth::user() && Auth::user()->isAdmin())
                <a href="{{ route('admin.evaluations.archived') }}" class="nav-item {{ request()->routeIs('admin.evaluations.archived') ? 'active' : '' }}">
                    <i class="fas fa-archive w-5 text-[15px]"></i>
                    <span>Avaliações Arquivadas</span>
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
        <div class="px-4 pb-4 shrink-0 mt-auto">
            <div class="p-3 bg-[#111827] rounded-xl border border-white/5 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-accent flex items-center justify-center text-white font-bold text-xs font-mono">
                    {{ strtoupper(substr(Auth::user()->name ?? 'HS', 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-bold text-white truncate leading-none">{{ Auth::user()->name ?? 'Dra. Helena Souza' }}</p>
                    <p class="text-[9px] text-slate-400 truncate mt-1">Gestora Subprefeitura</p>
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

        <!-- Título e Subtítulos da Esquerda -->
        <div class="flex flex-col gap-1 min-w-0">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider font-mono leading-none">Sistema de Avaliação de Desempenho Misto</p>
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-[12px] font-extrabold text-slate-900 tracking-tight leading-tight uppercase font-mono">SAD-BARS Setor Público</span>
                <span class="text-[9px] font-bold bg-slate-100 text-slate-650 px-2 py-0.5 rounded border border-slate-200 uppercase font-mono">Padrão Homologação</span>
            </div>
        </div>

        <!-- Direita: Informações do Avaliador e Servidor em Foco -->
        <div class="ml-auto flex items-center gap-4 shrink-0">
            <div class="text-right hidden md:block">
                <p class="text-[9px] font-bold text-slate-450 uppercase tracking-wider font-mono leading-none">Avaliador de Carreira</p>
                <div class="flex items-center gap-1.5 mt-1 justify-end">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <p class="text-[10px] font-bold text-emerald-600 font-mono">Subprefeitura Ativa</p>
                </div>
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 hidden sm:block">
                <p class="text-[10px] text-slate-500 font-semibold leading-none font-sans">Servidor em Foco: <strong class="text-slate-800 font-bold">João Carlos da Silva</strong></p>
            </div>
        </div>
    </header>

    <!-- ═══════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════ -->
    <div id="main-wrapper">
        <div id="main-content"
             class="p-6 md:p-10 max-w-[1600px] mx-auto w-full transition-opacity duration-300 flex flex-col min-h-[calc(100vh-64px)]"
             hx-target="#main-content"
             hx-select="#main-content"
             hx-swap="innerHTML transition:true"
             hx-boost="true">
            <div class="flex-grow">
                @yield('content')
                {{ $slot ?? '' }}
            </div>
            
            <!-- Rodapé Escuro da Imagem -->
            <footer class="mt-10 bg-primary text-slate-400 text-[10px] px-6 py-4 rounded-xl flex flex-col sm:flex-row items-center justify-between gap-4 font-mono">
                <div class="flex items-center gap-2">
                    <span class="text-accent font-bold">SAD-BARS</span>
                    <span>•</span>
                    <span>Desenvolvido conforme Diretrizes de Gestão de Desempenho Funcional e Desburocratização no Serviço Público.</span>
                </div>
                <div class="flex items-center gap-1.5 text-accent font-bold">
                    <span class="material-symbols-outlined text-[16px] text-accent">verified_user</span>
                    <span>Conformidade Jurídico-Administrativa</span>
                </div>
            </footer>
        </div>
    </div>

    @stack('modals')

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

        // Configurações de Máscara dinâmica CPF/CNPJ
        var cpfCnpjMascara = function (val) {
            return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009' : '00.000.000/0000-00';
        },
        cpfCnpjOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(cpfCnpjMascara.apply({}, arguments), options);
            }
        };

        // Aplicação Inicial de Máscaras
        $('.mask-money').mask('#.##0,00', {reverse: true});
        $('.mask-phone').mask('(00) 00000-0000');
        $('.mask-cpf').mask('000.000.000-00');
        $('.mask-cpfcnpj').mask(cpfCnpjMascara, cpfCnpjOptions);

        // HTMX after swap
        document.addEventListener('htmx:afterSwap', function (evt) {
            htmx.process(evt.detail.elt);

            if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                window.Alpine.initTree(evt.detail.elt);
            }

            $('.mask-money').mask('#.##0,00', {reverse: true});
            $('.mask-phone').mask('(00) 00000-0000');
            $('.mask-cpf').mask('000.000.000-00');
            $('.mask-cpfcnpj').mask(cpfCnpjMascara, cpfCnpjOptions);

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
    @stack('scripts')
</body>
</html>
