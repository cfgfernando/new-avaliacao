<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
    <title>Detalhes do Relatório — MDA Church</title>
    
    <!-- Google Fonts: Libre Franklin -->
    <link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
          theme: {
            extend: {
              colors: {
                'brand-dark': '#1c2434',
                'brand-primary': '#1351b4',
                'brand-accent': '#ff9c00',
                'brand-bg': '#f3f4f6',
                'brand-success': '#10b981'
              },
              fontFamily: {
                sans: ['Libre Franklin', 'sans-serif'],
              },
              borderRadius: {
                'custom': '8px',
              }
            }
          }
        }
    </script>
    
    <style data-purpose="custom-layout">
        body {
          background-color: #f3f4f6;
          -webkit-tap-highlight-color: transparent;
        }
        /* Hide scrollbar for horizontal stats */
        .no-scrollbar::-webkit-scrollbar {
          display: none;
        }
        .no-scrollbar {
          -ms-overflow-style: none;
          scrollbar-width: none;
        }
    </style>
</head>
<body class="font-sans text-brand-dark antialiased">

    <!-- BEGIN: TopAppBar -->
    <nav class="sticky top-0 z-50 bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('reports.index') }}" aria-label="Voltar" class="p-1 -ml-1 text-brand-dark">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                </svg>
            </a>
            <h1 class="text-lg font-bold">Detalhes do Relatório</h1>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('reports.pdf', $report) }}" target="_blank" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-3 py-2 rounded-custom flex items-center gap-1.5 border border-slate-200 transition-all">
                <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                PDF A4
            </a>

            @if($report->status === 'Submitted' && (auth()->user()->isAdmin() || auth()->user()->isTreasurer()))
                <form action="{{ route('reports.conciliate', $report) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-brand-success/10 hover:bg-brand-success/20 text-brand-success text-xs font-bold px-3 py-2 rounded-custom flex items-center gap-2 border border-brand-success/20 transition-all">
                        <svg class="h-4 w-4" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" fill-rule="evenodd"></path>
                        </svg>
                        CONCILIAR
                    </button>
                </form>
            @endif

            @if($report->status === 'Draft')
                <a href="{{ route('reports.edit', $report) }}" class="bg-gray-150 hover:bg-gray-250 text-brand-dark text-xs font-bold px-3 py-2 rounded-custom border border-gray-200 transition-all">
                    EDITAR
                </a>
                <form action="{{ route('reports.submit', $report) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-brand-primary text-white text-xs font-bold px-3 py-2 rounded-custom transition-all">
                        SUBMETER
                    </button>
                </form>
            @endif
        </div>
    </nav>
    <!-- END: TopAppBar -->

    <main class="p-4 space-y-4 pb-10 max-w-lg mx-auto">

        <!-- BEGIN: Header Card -->
        <section class="bg-brand-dark rounded-custom p-5 text-white relative overflow-hidden shadow-lg" data-purpose="summary-header">
            <!-- Background Graphic Pattern Decoration -->
            <div class="absolute right-0 top-0 opacity-10 pointer-events-none">
                <svg fill="currentColor" height="120" viewbox="0 0 24 24" width="120">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
            </div>
            
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-brand-accent rounded-custom flex items-center justify-center">
                            <svg class="h-6 w-6 text-brand-dark" fill="none" stroke="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-brand-accent uppercase tracking-wider">Célula</p>
                            <h2 class="text-xl font-bold">{{ $report->cell->name }}</h2>
                        </div>
                    </div>

                    @if($report->status === 'Conciliated')
                        <span class="bg-brand-success/15 text-brand-success text-[10px] font-black px-2 py-1 rounded flex items-center gap-1 border border-brand-success/30">
                            <span class="w-1.5 h-1.5 bg-brand-success rounded-full"></span>
                            CONCILIADO
                        </span>
                    @elseif($report->status === 'Submitted')
                        <span class="bg-brand-accent/15 text-[#ffb020] text-[10px] font-black px-2 py-1 rounded flex items-center gap-1 border border-brand-accent/30">
                            <span class="w-1.5 h-1.5 bg-[#ffb020] rounded-full"></span>
                            SUBMETIDO
                        </span>
                    @else
                        <span class="bg-gray-500/15 text-gray-400 text-[10px] font-black px-2 py-1 rounded flex items-center gap-1 border border-gray-500/30">
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                            RASCUNHO
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 gap-2 text-xs font-medium text-gray-300">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-brand-accent" fill="none" stroke="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        <span>{{ $report->meeting_date?->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2 italic">
                        <svg class="h-4 w-4 text-brand-accent" fill="none" stroke="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        <span>"{{ $report->word_theme ?? 'Sem tema registrado' }}"</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-brand-accent" fill="none" stroke="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        <span>{{ $report->meeting_location ?? 'Local não informado' }}</span>
                    </div>
                </div>
            </div>
        </section>
        <!-- END: Header Card -->

        <!-- BEGIN: Stats Summary -->
        <div class="grid grid-cols-3 gap-3 w-full" data-purpose="stats-grid">
            <!-- Presentes -->
            <div class="bg-white p-3 rounded-custom shadow-sm border border-gray-100">
                <p class="text-[10px] font-bold text-gray-500 uppercase">Presentes</p>
                <div class="flex items-end justify-between mt-1">
                    <span class="text-2xl font-bold text-brand-success">{{ $report->present_members }}</span>
                    <div class="bg-brand-success/10 p-1 rounded-full">
                        <svg class="h-4 w-4 text-brand-success" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" fill-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Visitantes -->
            <div class="bg-white p-3 rounded-custom shadow-sm border border-gray-100">
                <p class="text-[10px] font-bold text-gray-500 uppercase">Visitantes</p>
                <div class="flex items-end justify-between mt-1">
                    <span class="text-2xl font-bold text-brand-accent">{{ $report->visitors }}</span>
                    <div class="bg-brand-accent/10 p-1 rounded-full">
                        <svg class="h-4 w-4 text-brand-accent" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Total Geral -->
            <div class="bg-white p-3 rounded-custom shadow-sm border border-gray-100">
                <p class="text-[10px] font-bold text-gray-500 uppercase">Total Geral</p>
                <div class="flex items-end justify-between mt-1">
                    <span class="text-2xl font-bold text-brand-primary">{{ $report->total_presence }}</span>
                    <div class="bg-brand-primary/10 p-1 rounded-full">
                        <svg class="h-4 w-4 text-brand-primary" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" fill-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Stats Summary -->

        <!-- BEGIN: Financial Card -->
        <section class="bg-white rounded-custom shadow-sm border border-gray-100 overflow-hidden" data-purpose="financial-consolidated">
            <div class="p-4 border-b border-gray-50 flex items-center gap-2">
                <div class="w-6 h-6 bg-brand-accent/10 rounded flex items-center justify-center">
                    <svg class="h-4 w-4 text-brand-accent" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                        <path clip-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" fill-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="text-xs font-bold text-brand-dark uppercase tracking-tight">Consolidado Financeiro</h3>
            </div>
            <div class="p-4 bg-blue-50/30">
                <div class="text-center py-4 bg-white rounded-custom border border-blue-100 mb-4 shadow-sm">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Total Arrecadado</p>
                    <p class="text-3xl font-black text-brand-accent mt-1">R$ {{ number_format($report->total_offer, 2, ',', '.') }}</p>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-medium">Ofertas em PIX</span>
                        <span class="font-bold text-brand-dark">R$ {{ number_format($report->offer_pix, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-medium">Ofertas em Espécie</span>
                        <span class="font-bold text-brand-dark">R$ {{ number_format($report->offer_cash, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </section>
        <!-- END: Financial Card -->

        <!-- BEGIN: Ministerial Impact Card -->
        <section class="bg-white rounded-custom shadow-sm border border-gray-100 overflow-hidden" data-purpose="ministerial-impact">
            <div class="p-4 border-b border-gray-50 flex items-center gap-2">
                <div class="w-6 h-6 bg-red-100 rounded flex items-center justify-center">
                    <svg class="h-4 w-4 text-red-500" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" fill-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="text-xs font-bold text-brand-dark uppercase tracking-tight">Impacto Ministerial</h3>
            </div>
            <div class="p-4 space-y-4">
                <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                    <span class="text-sm text-gray-600">Decisões</span>
                    <span class="text-base font-bold text-brand-dark">{{ $report->conversions }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                    <span class="text-sm text-gray-600">Reconciliações</span>
                    <span class="text-base font-bold text-brand-dark">{{ $report->reconciliations }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                    <span class="text-sm text-gray-600">Casas de Paz</span>
                    <span class="text-base font-bold text-brand-dark">{{ $report->house_of_peace }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                    <span class="text-sm text-gray-600">Discipulados (MDAs)</span>
                    <span class="text-base font-bold text-brand-dark">{{ $report->mdas_done }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Quilo do Amor</span>
                    <span class="text-base font-bold text-brand-dark">{{ number_format($report->kg_of_love, 1) }} Kg</span>
                </div>
            </div>
        </section>
        <!-- END: Ministerial Impact Card -->

        <!-- BEGIN: Call List -->
        @php
            $memberIds = is_array($report->present_member_ids) ? $report->present_member_ids : json_decode($report->present_member_ids, true) ?? [];
            $presentMembers = \App\Models\Member::whereIn('id', $memberIds)->get();
        @endphp

        @if($presentMembers->count() > 0)
            <section class="bg-white rounded-custom shadow-sm border border-gray-100 overflow-hidden" data-purpose="call-list">
                <div class="p-4 border-b border-gray-50 flex items-center gap-2">
                    <div class="w-6 h-6 bg-brand-primary/10 rounded flex items-center justify-center">
                        <svg class="h-4 w-4 text-brand-primary" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                            <path clip-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" fill-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-xs font-bold text-brand-dark uppercase tracking-tight">Lista de Chamada</h3>
                </div>
                <div class="divide-y divide-gray-50 max-h-[360px] overflow-y-auto">
                    @foreach($presentMembers as $member)
                        <div class="p-3.5 flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-brand-primary text-white flex items-center justify-center text-xs font-bold uppercase">
                                    {{ strtoupper(substr($member->name, 0, 2)) }}
                                </div>
                                <span class="text-sm font-bold text-gray-700">{{ $member->name }}</span>
                            </div>
                            <span class="bg-brand-success/10 text-brand-success text-[10px] font-bold px-2 py-0.5 rounded-custom border border-brand-success/20 uppercase">
                                Presente
                            </span>
                        </div>
                    @endforeach
                </div>
            </section>
        @else
            <!-- BEGIN: Empty Call List -->
            <section class="bg-white rounded-custom shadow-sm border border-gray-100 overflow-hidden" data-purpose="call-list-empty">
                <div class="p-4 border-b border-gray-50 flex items-center gap-2">
                    <div class="w-6 h-6 bg-brand-primary/10 rounded flex items-center justify-center">
                        <svg class="h-4 w-4 text-brand-primary" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                            <path clip-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" fill-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-xs font-bold text-brand-dark uppercase tracking-tight">Lista de Chamada</h3>
                </div>
                <div class="p-8 flex flex-col items-center justify-center text-center">
                    <div class="mb-4 opacity-20">
                        <svg class="h-16 w-16 text-brand-dark" fill="none" stroke="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Nenhuma presença computada</p>
                </div>
            </section>
            <!-- END: Empty Call List -->
        @endif

        <!-- BEGIN: Visitors List -->
        @php
            $visitorsList = is_array($report->visitor_names) ? $report->visitor_names : json_decode($report->visitor_names, true) ?? [];
            $hasVisitors = collect($visitorsList)->map(fn($v) => is_array($v) ? ($v['name'] ?? '') : $v)->filter()->count() > 0;
        @endphp

        @if($hasVisitors)
            <section class="bg-white rounded-custom shadow-sm border border-gray-100 overflow-hidden" data-purpose="visitors-list">
                <div class="p-4 border-b border-gray-50 flex items-center gap-2">
                    <div class="w-6 h-6 bg-brand-accent/10 rounded flex items-center justify-center">
                        <svg class="h-4 w-4 text-brand-accent" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xs font-bold text-brand-dark uppercase tracking-tight">Visitantes Registrados</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($visitorsList as $visitor)
                        @php
                            $vName = is_array($visitor) ? ($visitor['name'] ?? '') : $visitor;
                        @endphp
                        @if($vName)
                            <div class="p-3.5 flex items-center gap-3 hover:bg-gray-50 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-brand-accent text-white flex items-center justify-center text-xs font-bold uppercase">
                                    {{ strtoupper(substr($vName, 0, 2)) }}
                                </div>
                                <span class="text-sm font-bold text-gray-700">{{ $vName }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </section>
        @else
            <!-- BEGIN: Empty Visitors List -->
            <section class="bg-white rounded-custom shadow-sm border border-gray-100 overflow-hidden" data-purpose="visitors-list-empty">
                <div class="p-4 border-b border-gray-50 flex items-center gap-2">
                    <div class="w-6 h-6 bg-brand-accent/10 rounded flex items-center justify-center">
                        <svg class="h-4 w-4 text-brand-accent" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xs font-bold text-brand-dark uppercase tracking-tight">Visitantes Registrados</h3>
                </div>
                <div class="p-8 flex flex-col items-center justify-center text-center">
                    <div class="mb-4 opacity-20">
                        <svg class="h-16 w-16 text-brand-dark" fill="none" stroke="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Nenhum visitante esta semana</p>
                </div>
            </section>
            <!-- END: Empty Visitors List -->
        @endif

        <!-- BEGIN: Auditoria -->
        <section class="bg-white rounded-custom shadow-sm border border-gray-100 overflow-hidden" data-purpose="audit-section">
            <div class="p-4 border-b border-gray-50 flex items-center gap-2">
                <div class="w-6 h-6 bg-brand-dark/10 rounded flex items-center justify-center">
                    <svg class="h-4 w-4 text-brand-dark" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" d="M2.166 4.9L9.032 1.567a1.002 1.002 0 01.936 0l6.866 3.333c.487.236.8.724.8 1.258V10c0 5.424-3.52 10.05-8.334 11.41a1.002 1.002 0 01-.532 0C3.52 20.05 0 15.424 0 10V6.158c0-.534.313-1.022.8-1.258L2.166 4.9zm8.834 6.1a1 1 0 10-2 0v3a1 1 0 102 0v-3zM10 6a1 1 0 100 2 1 1 0 000-2z" fill-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="text-xs font-bold text-brand-dark uppercase tracking-tight">Auditoria</h3>
            </div>
            
            <div class="p-4 flex items-center gap-3">
                <div class="bg-gray-100 p-2 rounded-full">
                    <svg class="h-5 w-5 text-gray-500" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" fill-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">Enviado por</span>
                    <span class="text-sm font-bold text-brand-dark">{{ $report->submittedBy->name ?? 'Admin Master' }}</span>
                    <span class="text-[10px] text-gray-400 mt-0.5">{{ ($report->submitted_at ?? $report->created_at)->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            @if($report->conciliatedBy)
                <div class="p-4 flex items-center gap-3 border-t border-gray-50">
                    <div class="bg-brand-success/10 p-2 rounded-full">
                        <svg class="h-5 w-5 text-brand-success" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" fill-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">Conciliado por</span>
                        <span class="text-sm font-bold text-brand-dark">{{ $report->conciliatedBy->name }}</span>
                        <span class="text-[10px] text-gray-400 mt-0.5">{{ $report->conciliated_at?->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            @endif
        </section>
        <!-- END: Auditoria -->

        <!-- BEGIN: Observações -->
        @if($report->notes)
            <section class="bg-white rounded-custom shadow-sm border border-gray-100 overflow-hidden" data-purpose="notes-section">
                <div class="p-4 border-b border-gray-50 flex items-center gap-2">
                    <svg class="h-4 w-4 text-brand-accent animate-pulse" fill="none" stroke="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                    <h3 class="text-xs font-bold text-brand-dark uppercase tracking-tight">Observações do Líder</h3>
                </div>
                <div class="p-4 text-sm text-gray-600 leading-relaxed italic">
                    "{{ $report->notes }}"
                </div>
            </section>
        @endif

    </main>



</body>
</html>
