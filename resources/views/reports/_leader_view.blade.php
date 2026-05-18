@php
    $cell        = $user->cell;
    $lastReport  = $reports->first();
    $monthTotal  = $reports->getCollection()
                    ->whereMonth('meeting_date', now()->month)
                    ->sum('total_offer');
    $totalCount  = $reports->total();
    $conciliated = $reports->getCollection()->where('status', 'Conciliated')->count();
@endphp

<div class="space-y-8 animate-reveal-up">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <p class="text-[10px] font-black text-primary-light uppercase tracking-[0.3em] mb-2">Módulo Operacional</p>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Gestão de Malotes</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Envio de ofertas da sua célula</p>
        </div>
        <a href="{{ route('reports.create') }}" hx-boost="false"
           class="btn-neo bg-accent text-white text-[10px] font-black uppercase tracking-widest px-6 py-3 flex items-center gap-2 self-start md:self-auto shadow-lg shadow-accent/30">
            <i class="fas fa-file-circle-plus"></i> Novo Malote
        </a>
    </div>

    {{-- BANNER DA CÉLULA --}}
    @if($cell)
    <div class="card-neo p-6 bg-gradient-to-r from-primary to-[#24303F] text-white border-0 overflow-hidden relative">
        <div class="absolute right-0 top-0 w-48 h-full opacity-5">
            <i class="fas fa-church text-[160px] absolute -right-6 -top-4"></i>
        </div>
        <div class="flex flex-col md:flex-row md:items-center gap-6 relative z-10">
            <div class="w-16 h-16 rounded-2xl bg-accent/20 border border-accent/30 flex items-center justify-center shrink-0 shadow-lg">
                <i class="fas fa-church text-accent text-2xl"></i>
            </div>
            <div class="flex-1">
                <p class="text-[10px] font-black text-white/50 uppercase tracking-[0.3em] mb-1">Sua Célula</p>
                <h2 class="text-xl font-black text-white tracking-tight leading-tight">{{ $cell->name }}</h2>
                <div class="flex flex-wrap gap-4 mt-2">
                    @if($cell->meeting_day)
                    <span class="flex items-center gap-1.5 text-[10px] font-bold text-white/60 uppercase tracking-widest">
                        <i class="fas fa-calendar-day text-accent/80"></i>
                        {{ $cell->meeting_day_label }}
                    </span>
                    @endif
                    @if($cell->address)
                    <span class="flex items-center gap-1.5 text-[10px] font-bold text-white/60 uppercase tracking-widest">
                        <i class="fas fa-map-marker-alt text-accent/80"></i>
                        {{ $cell->neighborhood ?? $cell->address }}
                    </span>
                    @endif
                    <span class="flex items-center gap-1.5 text-[10px] font-bold text-white/60 uppercase tracking-widest">
                        <i class="fas fa-users text-accent/80"></i>
                        {{ $cell->members->count() }} membros
                    </span>
                </div>
            </div>
            @if($lastReport)
            <div class="shrink-0 text-right">
                <p class="text-[9px] font-black text-white/40 uppercase tracking-widest">Último malote</p>
                <p class="text-sm font-black text-white mt-0.5">{{ $lastReport->meeting_date?->format('d M Y') }}</p>
                <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-lg mt-1
                    {{ $lastReport->status === 'Conciliated' ? 'bg-emerald-500/20 text-emerald-300' :
                       ($lastReport->status === 'Submitted'  ? 'bg-amber-500/20 text-amber-300' :
                                                               'bg-white/10 text-white/60') }}">
                    <i class="fas {{ $lastReport->status === 'Conciliated' ? 'fa-check-double' : ($lastReport->status === 'Submitted' ? 'fa-paper-plane' : 'fa-pencil') }} text-[8px]"></i>
                    {{ $lastReport->status_label }}
                </span>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Card 1: Status do último malote --}}
        <div class="card-neo p-6 flex flex-col gap-3 border-l-4 {{ $lastReport?->status === 'Conciliated' ? 'border-emerald-400' : ($lastReport?->status === 'Submitted' ? 'border-amber-400' : 'border-slate-300') }}">
            <div class="flex justify-between items-start">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Status do Último Malote</span>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center border
                    {{ $lastReport?->status === 'Conciliated' ? 'bg-emerald-50 border-emerald-100 text-emerald-600' :
                       ($lastReport?->status === 'Submitted'  ? 'bg-amber-50 border-amber-100 text-amber-600' :
                                                                'bg-slate-50 border-slate-200 text-slate-500') }}">
                    <i class="fas {{ $lastReport?->status === 'Conciliated' ? 'fa-check-double' : ($lastReport?->status === 'Submitted' ? 'fa-paper-plane' : 'fa-pencil') }} text-sm"></i>
                </div>
            </div>
            <div>
                <span class="text-2xl font-black text-slate-800 tracking-tighter">
                    {{ $lastReport?->status_label ?? '—' }}
                </span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mt-1">
                    @if($lastReport)
                        {{ $lastReport->meeting_date?->format('d M, Y') }}
                    @else
                        Nenhum malote ainda
                    @endif
                </span>
            </div>
        </div>

        {{-- Card 2: Arrecadação do mês --}}
        <div class="card-neo p-6 flex flex-col gap-3 border-l-4 border-accent">
            <div class="flex justify-between items-start">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Arrecadado (Mês)</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-accent">
                    <i class="fas fa-sack-dollar text-sm"></i>
                </div>
            </div>
            <div>
                <span class="text-2xl font-black text-slate-800 tracking-tighter font-money">
                    R$ {{ number_format($monthTotal, 2, ',', '.') }}
                </span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mt-1">
                    {{ now()->format('M / Y') }}
                </span>
            </div>
        </div>

        {{-- Card 3: Total de malotes --}}
        <div class="card-neo p-6 flex flex-col gap-3 border-l-4 border-blue-400">
            <div class="flex justify-between items-start">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Total de Malotes</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-500">
                    <i class="fas fa-inbox text-sm"></i>
                </div>
            </div>
            <div>
                <span class="text-2xl font-black text-slate-800 tracking-tighter">{{ $totalCount }}</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mt-1">
                    {{ $conciliated }} conciliado(s)
                </span>
            </div>
        </div>
    </div>

    {{-- FILTRO SIMPLIFICADO --}}
    <div class="card-neo p-4">
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="min-w-[180px]">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Status</label>
                <select name="status" class="input-neo py-2.5">
                    <option value="">Todos</option>
                    <option value="Draft"       {{ request('status') === 'Draft'       ? 'selected' : '' }}>🟡 Rascunho</option>
                    <option value="Submitted"   {{ request('status') === 'Submitted'   ? 'selected' : '' }}>🟠 Enviado</option>
                    <option value="Conciliated" {{ request('status') === 'Conciliated' ? 'selected' : '' }}>✅ Conciliado</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Data Início</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-neo py-2.5">
            </div>
            <div class="min-w-[150px]">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Data Fim</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-neo py-2.5">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-neo bg-primary text-white text-[10px] font-black uppercase tracking-widest px-5 py-2.5 flex items-center gap-2">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
                @if(request()->anyFilled(['status', 'date_from', 'date_to']))
                <a href="{{ route('reports.index') }}" class="btn-neo bg-white border border-slate-200 text-slate-500 text-[10px] font-black uppercase tracking-widest px-5 py-2.5 flex items-center gap-2">
                    <i class="fas fa-times"></i> Limpar
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- TABELA DE MALOTES DA CÉLULA --}}
    <div class="card-neo !p-0 overflow-hidden">
        <div class="px-6 py-4 bg-slate-50/60 border-b border-slate-100 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center">
                    <i class="fas fa-calendar-week text-primary text-xs"></i>
                </div>
                <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest">
                    {{ $reports->total() }} malote(s) encontrado(s)
                </p>
            </div>
        </div>

        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 bg-slate-50/30">
                        <th class="px-6 py-4 text-left">Data da Reunião</th>
                        <th class="px-6 py-4 text-center hidden md:table-cell">Frequência</th>
                        <th class="px-6 py-4 text-right">Oferta (PIX + Dinheiro)</th>
                        <th class="px-6 py-4 text-center">Situação</th>
                        <th class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($reports as $report)
                    <tr class="hover:bg-slate-50/60 transition-colors group cursor-pointer"
                        onclick="window.location='{{ route('reports.show', $report) }}'">

                        {{-- Data / Tema --}}
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-2xl bg-primary/8 flex items-center justify-center text-primary shrink-0 group-hover:bg-accent group-hover:text-white transition-all duration-300">
                                    <i class="fas fa-calendar-day text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-slate-800 tracking-tight">
                                        {{ $report->meeting_date?->format('d \d\e M, Y') }}
                                    </p>
                                    @if($report->word_theme)
                                    <p class="text-[9px] text-slate-400 font-bold mt-0.5 uppercase tracking-widest truncate max-w-[180px]">
                                        <i class="fas fa-book-open mr-1 opacity-50"></i>{{ $report->word_theme }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Frequência --}}
                        <td class="px-6 py-5 text-center hidden md:table-cell">
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-xl font-black text-slate-800 tracking-tighter leading-none">{{ $report->total_presence }}</span>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[8px] font-black text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded-md">{{ $report->present_members }}M</span>
                                    <span class="text-[8px] font-black text-blue-500 bg-blue-50 px-1.5 py-0.5 rounded-md">{{ $report->visitors }}V</span>
                                    <span class="text-[8px] font-black text-violet-500 bg-violet-50 px-1.5 py-0.5 rounded-md">{{ $report->children }}C</span>
                                </div>
                            </div>
                        </td>

                        {{-- Oferta --}}
                        <td class="px-6 py-5 text-right">
                            <p class="text-sm font-black text-emerald-600 tracking-tighter font-money">
                                R$ {{ number_format($report->total_offer, 2, ',', '.') }}
                            </p>
                            <p class="text-[8px] text-slate-400 font-bold mt-0.5">
                                <span class="text-sky-500">PIX {{ number_format($report->offer_pix, 2, ',', '.') }}</span>
                                <span class="mx-1 opacity-30">|</span>
                                <span>Din. {{ number_format($report->offer_cash, 2, ',', '.') }}</span>
                            </p>
                        </td>

                        {{-- Badge de Status --}}
                        <td class="px-6 py-5 text-center">
                            @php
                                $cfg = match($report->status) {
                                    'Conciliated' => ['fa-check-double', 'bg-emerald-50 border-emerald-100 text-emerald-700'],
                                    'Submitted'   => ['fa-paper-plane',  'bg-amber-50 border-amber-100 text-amber-700'],
                                    default       => ['fa-pencil',        'bg-slate-50 border-slate-200 text-slate-600'],
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-xl border {{ $cfg[1] }}">
                                <i class="fas {{ $cfg[0] }} text-[8px]"></i>{{ $report->status_label }}
                            </span>
                        </td>

                        {{-- Ações --}}
                        <td class="px-6 py-5 text-right" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('reports.show', $report) }}" hx-boost="false" title="Ver"
                                   class="btn-action bg-slate-50 border border-slate-200 text-slate-500 hover:text-primary hover:border-primary hover:bg-white">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>

                                @if($report->status === 'Draft')
                                <a href="{{ route('reports.edit', $report) }}" hx-boost="false" title="Editar"
                                   class="btn-action bg-blue-50 border border-blue-100 text-blue-500 hover:bg-blue-500 hover:text-white hover:border-blue-500">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                <form action="{{ route('reports.submit', $report) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Confirmar envio do malote?')">
                                    @csrf
                                    <button type="submit" title="Enviar Malote"
                                            class="btn-action bg-amber-50 border border-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white hover:border-amber-500">
                                        <i class="fas fa-paper-plane text-xs"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 rounded-3xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-200">
                                    <i class="fas fa-inbox text-4xl"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nenhum malote encontrado.</p>
                                    <p class="text-[9px] text-slate-300 font-bold mt-1">Crie o primeiro malote da sua célula.</p>
                                </div>
                                <a href="{{ route('reports.create') }}" hx-boost="false"
                                   class="btn-neo bg-accent text-white text-[9px] font-black uppercase tracking-widest px-5 py-2.5 flex items-center gap-2 mt-2 shadow-lg shadow-accent/30">
                                    <i class="fas fa-plus"></i> Criar Malote
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Visualização Responsiva para Mobile --}}
        <div class="block md:hidden">
            <div class="flex flex-col gap-4 p-4">
                @forelse($reports as $report)
                    <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex flex-col gap-4 hover:border-slate-200 transition-all cursor-pointer" 
                         onclick="window.location='{{ route('reports.show', $report) }}'">
                        {{-- Top: Data da Reunião, Tema da Palavra e Status --}}
                        <div class="flex justify-between items-start gap-2">
                            <div class="flex flex-col">
                                <span class="text-[14px] font-bold text-slate-800 tracking-tight leading-tight flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Reunião: {{ $report->meeting_date?->format('d \d\e M, Y') }}
                                </span>
                                @if($report->word_theme)
                                    <span class="text-[11px] text-slate-400 font-semibold truncate max-w-[200px] mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                        {{ $report->word_theme }}
                                    </span>
                                @endif
                            </div>
                            @php
                                $badgeStyle = match($report->status) {
                                    'Conciliated' => 'bg-[#10b981]/10 text-[#10b981] border-[#10b981]/20',
                                    'Submitted'   => 'bg-amber-50 text-amber-600 border-amber-200',
                                    default       => 'bg-slate-100 text-slate-600 border-slate-200',
                                };
                            @endphp
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full border shrink-0 {{ $badgeStyle }}">
                                {{ $report->status_label }}
                            </span>
                        </div>

                        {{-- Valor da Oferta e Ações --}}
                        <div class="flex justify-between items-end border-t border-slate-100 pt-4 gap-4">
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Oferta Total</p>
                                <span class="text-[18px] font-bold text-emerald-600 tracking-tight font-money">R$ {{ number_format($report->total_offer, 2, ',', '.') }}</span>
                                <p class="text-[9px] text-slate-400 mt-1 font-semibold">
                                    <span class="text-sky-500">PIX {{ number_format($report->offer_pix, 2, ',', '.') }}</span>
                                    <span class="mx-1 opacity-30">|</span>
                                    <span>Din. {{ number_format($report->offer_cash, 2, ',', '.') }}</span>
                                </p>
                            </div>

                            {{-- Botões de Ação com SVG --}}
                            <div class="flex gap-2" onclick="event.stopPropagation()">
                                <a href="{{ route('reports.show', $report) }}" hx-boost="false" title="Visualizar"
                                   class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                @if($report->status === 'Draft')
                                    <a href="{{ route('reports.edit', $report) }}" hx-boost="false" title="Editar"
                                       class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-50 border border-blue-100 text-blue-500 hover:bg-blue-500 hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('reports.submit', $report) }}" method="POST" class="inline" onsubmit="return confirm('Confirmar envio do malote?')">
                                        @csrf
                                        <button type="submit" title="Enviar"
                                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-amber-50 border border-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        {{-- Metadata: Frequência detalhada e Status de Auditoria --}}
                        <div class="flex justify-between items-center pt-3 border-t border-slate-100 text-[11px] text-slate-500">
                            <span class="flex items-center gap-1 font-bold text-slate-600">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Frequência: <span class="text-slate-800 font-extrabold text-[11px] ml-0.5">{{ $report->total_presence }}</span>
                            </span>
                            <div class="flex gap-1 font-bold text-[10px]">
                                <span class="bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200/60 text-slate-500">{{ $report->present_members }}M</span>
                                <span class="bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200/60 text-blue-500">{{ $report->visitors }}V</span>
                                <span class="bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200/60 text-violet-500">{{ $report->children }}C</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-12 rounded-xl border border-slate-100 text-center flex flex-col items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v2m16 4h-3.88l-.34-1.7a1 1 0 00-.98-.8h-3.6a1 1 0 00-.98.8L10.88 15H7" />
                            </svg>
                        </div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nenhum malote registrado.</p>
                        <a href="{{ route('reports.create') }}" hx-boost="false"
                           class="btn-neo bg-accent text-white text-[9px] font-black uppercase tracking-widest px-5 py-2.5 flex items-center gap-2 mt-2 shadow-lg shadow-accent/30">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Criar Malote
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Paginação --}}
        @if($reports->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30 flex flex-col md:flex-row items-center justify-between gap-4">
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
                Mostrando {{ $reports->firstItem() }}–{{ $reports->lastItem() }} de {{ $reports->total() }}
            </span>
            <div class="flex items-center gap-2">
                @if(!$reports->onFirstPage())
                <a href="{{ $reports->previousPageUrl() }}"
                   class="w-10 h-10 flex items-center justify-center border border-slate-200 rounded-xl text-slate-500 hover:border-accent hover:text-accent transition-all">
                    <i class="fas fa-chevron-left text-xs"></i>
                </a>
                @endif

                @foreach($reports->getUrlRange(max(1, $reports->currentPage()-2), min($reports->lastPage(), $reports->currentPage()+2)) as $page => $url)
                    @if($page == $reports->currentPage())
                        <span class="w-10 h-10 flex items-center justify-center bg-primary text-white rounded-xl text-[10px] font-black shadow-md">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center border border-slate-200 rounded-xl text-slate-500 hover:border-primary hover:text-primary transition-all text-[10px] font-black">{{ $page }}</a>
                    @endif
                @endforeach

                @if($reports->hasMorePages())
                <a href="{{ $reports->nextPageUrl() }}"
                   class="w-10 h-10 flex items-center justify-center border border-slate-200 rounded-xl text-slate-500 hover:border-accent hover:text-accent transition-all">
                    <i class="fas fa-chevron-right text-xs"></i>
                </a>
                @endif
            </div>
        </div>
        @endif
    </div>{{-- /tabela --}}

    {{-- AÇÕES DE EXPORTAÇÃO E COMPARTILHAMENTO --}}
    <div class="card-neo p-4 bg-white border border-slate-100 rounded-xl shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 animate-reveal-up">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-500 shrink-0">
                <i class="fas fa-file-export text-base"></i>
            </div>
            <div>
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Ações de Fechamento</h4>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Exporte e compartilhe dados consolidados</p>
            </div>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <button type="button" id="btn-share-summary"
                    class="btn-neo bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-black uppercase tracking-widest px-5 py-3 flex items-center justify-center gap-2 flex-1 sm:flex-initial transition-all">
                <i class="fas fa-share-nodes text-xs"></i> Compartilhar Resumo
            </button>
            <a href="{{ route('reports.monthly-pdf', request()->all()) }}" target="_blank" hx-boost="false"
               class="btn-neo bg-accent hover:bg-accent/90 text-white text-[10px] font-black uppercase tracking-widest px-5 py-3 flex items-center justify-center gap-2 flex-1 sm:flex-initial shadow-lg shadow-accent/20 transition-all">
                <i class="fas fa-file-pdf text-xs"></i> Exportar PDF Mensal
            </a>
        </div>
    </div>

</div>

<script>
    (function() {
        function init() {
            const btnShare = document.getElementById('btn-share-summary');
            if (btnShare) {
                // Remove existing listeners by cloning and replacing (prevents double binding)
                const newBtnShare = btnShare.cloneNode(true);
                btnShare.parentNode.replaceChild(newBtnShare, btnShare);

                newBtnShare.addEventListener('click', function () {
                    const cellName = "{{ $cell ? $cell->name : 'N/A' }}";
                    const totalOffer = "{{ number_format($monthTotal, 2, ',', '.') }}";
                    const totalMalotes = "{{ $totalCount }}";
                    const conciliationStatus = "{{ $conciliated }} conciliados de {{ $totalCount }}";
                    
                    const summaryText = `📊 *RESUMO MENSAL DA CÉLULA* 📊\n` +
                                        `*Célula:* ${cellName}\n` +
                                        `*Malotes no Mês:* ${totalMalotes}\n` +
                                        `*Total Arrecadado:* R$ ${totalOffer}\n` +
                                        `*Status da Conciliação:* ${conciliationStatus}\n\n` +
                                        `_Gerado via ERP MDA Church_`;
                    
                    if (navigator.share) {
                        navigator.share({
                            title: 'Resumo Mensal de Malotes',
                            text: summaryText
                        }).catch(err => console.log(err));
                    } else {
                        navigator.clipboard.writeText(summaryText).then(() => {
                            const toast = jQuery(`
                                <div class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-slate-900 border border-amber-500/30 text-white rounded-xl shadow-2xl p-4 min-w-[300px] animate-reveal-up">
                                    <div class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center text-amber-500">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest">Sucesso</p>
                                        <p class="text-xs font-bold text-slate-200 mt-0.5">Resumo copiado para a área de transferência!</p>
                                    </div>
                                </div>
                            `);
                            jQuery('body').append(toast);
                            setTimeout(() => {
                                toast.fadeOut(300, function() { jQuery(this).remove(); });
                            }, 3000);
                        }).catch(err => {
                            console.error('Erro ao copiar resumo: ', err);
                        });
                    }
                });
            }
        }

        if (document.readyState !== 'loading') {
            init();
        } else {
            document.addEventListener('DOMContentLoaded', init);
        }
    })();
</script>
