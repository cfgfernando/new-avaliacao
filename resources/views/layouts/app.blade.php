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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@800;900&family=JetBrains+Mono:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    <!-- Alpine.js Cloak -->
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* FORCED TECH DESIGN - ELITE V8 */
        
        /* 1. Grade e Bordas Suaves */
        .card-neo table {
            border-collapse: separate !important;
            border-spacing: 0 !important;
            width: 100% !important;
            border: 1px solid #f1f5f9 !important; /* border-slate-100 */
            border-radius: 16px !important;
            overflow: hidden !important;
        }

        .card-neo table th, 
        .card-neo table td {
            border-bottom: 1px solid #f8fafc !important;
            border-right: 1px solid #f8fafc !important;
            padding: 12px 20px !important;
        }

        .card-neo table thead th {
            background-color: #f8fafc !important; /* bg-slate-50 */
            color: #64748b !important; /* text-slate-500 */
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            font-size: 10px !important;
        }

        /* 2. Linhas Zebra Suaves */
        .card-neo table tbody tr:nth-child(even) {
            background-color: #ffffff !important;
        }
        .card-neo table tbody tr:hover {
            background-color: #f8fafc !important;
        }
        
        .card-neo table tbody tr:hover {
            background-color: #f1f5f9 !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
        }

        /* 3. Botões de Ação Maiores */
        .btn-action {
            width: 44px !important;
            height: 44px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 12px !important;
            transition: all 0.3s ease !important;
            margin: 0 4px !important;
        }
        
        .btn-action:hover {
            transform: translateY(-3px) scale(1.1) !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }

        .btn-action i {
            font-size: 16px !important;
        }

        /* Tipografia Financeira */
        .font-money {
            font-family: 'JetBrains Mono', monospace !important;
            font-weight: 800 !important;
        }

        /* 4. FIDELIDADE DE IMPRESSÃO (A4/PDF) */
        @media print {
            .sidebar-neo, .topbar-neo, .btn-neo, .no-print, button, form {
                display: none !important;
            }
            
            body {
                background: white !important;
                padding: 0 !important;
            }

            main {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }

            .card-neo {
                border: none !important;
                box-shadow: none !important;
                background: white !important;
                padding: 0 !important;
            }

            table {
                width: 100% !important;
                border: 1px solid #000 !important;
            }

            th, td {
                border: 1px solid #ddd !important;
                color: black !important;
                font-size: 10px !important;
                padding: 8px !important;
            }

            .print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 30px;
                border-bottom: 2px solid #000;
                padding-bottom: 10px;
            }

            .print-footer {
                display: block !important;
                position: fixed;
                bottom: 0;
                width: 100%;
                text-align: center;
                font-size: 8px;
                border-top: 1px solid #ddd;
                padding-top: 5px;
            }
        }

        .print-header, .print-footer {
            display: none;
        }
    </style>
</head>
<body class="font-sans antialiased bg-background text-slate-800">
    <!-- SPA Progress Bar -->
    <div class="htmx-indicator fixed top-0 left-0 w-full h-1 bg-accent z-[9999] transition-all duration-200 origin-left scale-x-0" id="spa-progress"></div>
    <style>
        .htmx-request#spa-progress { transform: scaleX(1); opacity: 1; }
        .htmx-request.nav-link-neo { opacity: 0.7; pointer-events: none; }
        /* Garantir que cliques no ícone ou texto não falhem */
        .nav-link-neo * { pointer-events: none; }
    </style>

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

            <nav id="sidebar-nav" 
                 class="mt-4 px-2 space-y-1.5 overflow-y-auto max-h-[calc(100vh-350px)] custom-scrollbar"
                 hx-target="#main-content" 
                 hx-select="#main-content" 
                 hx-swap="innerHTML transition:true"
                 hx-push-url="true"
                 hx-indicator="#spa-progress"
                 hx-boost="false">
                @if(isset($menuCategories) && $menuCategories->count() > 0)
                    @foreach($menuCategories as $category)
                        @php
                            $catColor = match($category->name) {
                                'CONTROLE DE CRISE' => 'text-rose-500',
                                'INTELIGÊNCIA CONTÁBIL' => 'text-slate-400',
                                'MÓDULO FINANCEIRO' => 'text-slate-400',
                                'ADMINISTRAÇÃO' => 'text-slate-400',
                                default => 'text-slate-400'
                            };
                        @endphp
                        <div class="text-[10px] font-black {{ $catColor }} uppercase tracking-[0.3em] px-8 mb-4 mt-8 opacity-80">{{ $category->name }}</div>
                        
                        @foreach($category->items as $item)
                            @if($item->is_active && (!$item->is_admin_only || (Auth::user() && Auth::user()->role === 'Admin')))
                                <a href="{{ str_starts_with($item->url, 'http') ? $item->url : url($item->url) }}" 
                                   class="nav-link-neo {{ request()->is(trim($item->url, '/')) ? 'active' : '' }} {{ $category->name === 'CONTROLE DE CRISE' ? '!text-rose-400 group' : '' }}">
                                    <i class="{{ $item->icon ?: 'fas fa-link' }} w-5 {{ $category->name === 'CONTROLE DE CRISE' ? 'group-hover:animate-pulse' : '' }}"></i>
                                    <span>{{ $item->title }}</span>
                                    @if($item->title === 'Zona de Risco')
                                        <span class="ml-auto w-2 h-2 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.6)] animate-ping"></span>
                                    @endif
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
                    <div class="pt-10 text-[10px] font-black text-rose-400 uppercase tracking-[0.3em] px-8 mb-6 opacity-80">Administração</div>

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
            <div id="main-content" class="p-10 max-w-[1600px] mx-auto w-full transition-opacity duration-300"
                 hx-target="#main-content" hx-select="#main-content" hx-swap="innerHTML transition:true" hx-boost="true">
                @yield('content')
                {{ $slot ?? '' }}
            </div>
        </main>
    </div>

    @stack('modals')
    @stack('scripts')
    <script>
        $(document).ready(function() {
            // Persistência de Scroll da Sidebar
            const sidebarNav = document.getElementById('sidebar-nav');
            
            // Restaurar posição salva ao carregar
            const savedScrollPos = localStorage.getItem('sidebarScrollPos');
            if (savedScrollPos && sidebarNav) {
                sidebarNav.scrollTop = savedScrollPos;
            }

            // Salvar posição e feedback instantâneo ao clicar
            $('#sidebar-nav a').on('click', function() {
                $('#sidebar-nav a').removeClass('active');
                $(this).addClass('active');
                
                if (sidebarNav) {
                    localStorage.setItem('sidebarScrollPos', sidebarNav.scrollTop);
                }
            });

            // Sidebar Toggle for Mobile
            $('#toggle-sidebar').on('click', function() {
                $('#sidebar').toggleClass('-translate-x-full');
            });

            // Re-inicialização após navegação SPA (HTMX)
            document.addEventListener('htmx:afterSwap', function(evt) {
                // Re-processar elementos HTMX no novo conteúdo
                htmx.process(evt.detail.elt);

                // Re-inicializar Alpine.js para componentes dinâmicos
                if (window.Alpine) {
                    if (typeof window.Alpine.initTree === 'function') {
                        window.Alpine.initTree(evt.detail.elt);
                    } else {
                        // Fallback se initTree não estiver disponível
                        window.Alpine.discoverUninitializedComponents();
                    }
                }

                // Re-inicializar Máscaras
                $('.mask-money').mask('#.##0,00', {reverse: true});
                $('.mask-phone').mask('(00) 00000-0000');
                $('.mask-cpf').mask('000.000.000-00');
                
                // Fechar sidebar no mobile se estiver aberta
                if (window.innerWidth < 1024) {
                    $('#sidebar').addClass('-translate-x-full');
                }

                // Scroll para o topo
                window.scrollTo({ top: 0, behavior: 'smooth' });

                // Sincronizar classes ativas no menu
                const currentPath = window.location.pathname;
                $('#sidebar-nav a').removeClass('active');
                $(`#sidebar-nav a`).each(function() {
                    const href = $(this).attr('href');
                    if (href && (href === currentPath || href === window.location.origin + currentPath)) {
                        $(this).addClass('active');
                    }
                });
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
