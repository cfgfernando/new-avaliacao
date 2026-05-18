@php
    $totalPending  = \App\Models\WeeklyReport::whereIn('status', ['Draft', 'Submitted'])->count();
    $monthlyTotal  = \App\Models\WeeklyReport::whereMonth('meeting_date', now()->month)
                        ->whereYear('meeting_date', now()->year)
                        ->sum(\Illuminate\Support\Facades\DB::raw('offer_pix + offer_cash'));
    $conciliated   = \App\Models\WeeklyReport::where('status', 'Conciliated')
                        ->whereMonth('meeting_date', now()->month)
                        ->whereYear('meeting_date', now()->year)
                        ->count();
@endphp

<div class="space-y-8 animate-reveal-up">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <p class="text-[10px] font-black text-primary-light uppercase tracking-[0.3em] mb-2">Módulo Operacional</p>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Gestão de Malotes</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Visão global — todas as células da rede</p>
        </div>
        <a href="{{ route('reports.create') }}" hx-boost="false"
           class="btn-neo bg-accent text-white text-[10px] font-black uppercase tracking-widest px-6 py-3 flex items-center gap-2 self-start md:self-auto shadow-lg shadow-accent/30">
            <i class="fas fa-file-circle-plus"></i> Novo Malote
        </a>
    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card-neo p-6 flex flex-col gap-2 border-l-4 border-red-400">
            <div class="flex justify-between items-start">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Relatórios Pendentes</span>
                <div class="w-10 h-10 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center text-red-500">
                    <i class="fas fa-clock text-sm"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-3xl font-black text-slate-800 tracking-tighter">{{ $totalPending }}</span>
                <span class="text-[10px] font-bold text-red-500 uppercase tracking-widest block mt-1">Aguardando conciliação</span>
            </div>
        </div>

        <div class="card-neo p-6 flex flex-col gap-2 border-l-4 border-accent">
            <div class="flex justify-between items-start">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Arrecadado (Mês)</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-accent">
                    <i class="fas fa-sack-dollar text-sm"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-3xl font-black text-slate-800 tracking-tighter font-money">R$ {{ number_format($monthlyTotal, 2, ',', '.') }}</span>
                <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest block mt-1">{{ now()->format('M / Y') }}</span>
            </div>
        </div>

        <div class="card-neo p-6 flex flex-col gap-2 border-l-4 border-emerald-400">
            <div class="flex justify-between items-start">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Conciliados (Mês)</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <i class="fas fa-check-double text-sm"></i>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-3xl font-black text-slate-800 tracking-tighter">{{ $conciliated }}</span>
                <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest block mt-1">Malotes finalizados</span>
            </div>
        </div>
    </div>

    {{-- FILTROS --}}
    <div class="card-neo p-5 animate-reveal-up">
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Buscar Célula / Líder</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nome da célula ou líder..."
                           class="input-neo !py-2.5 !pl-9 w-full">
                </div>
            </div>

            <div class="min-w-[150px] flex-1">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Área</label>
                <select name="area_id" id="filter_area" class="input-neo !py-2.5 w-full bg-white cursor-pointer">
                    <option value="">Todas as Áreas</option>
                    @foreach($areasList as $area)
                        <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
                            {{ $area->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="min-w-[150px] flex-1">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Setor</label>
                <select name="sector_id" id="filter_sector" class="input-neo !py-2.5 w-full bg-white cursor-pointer">
                    <option value="">Todos os Setores</option>
                    @foreach($sectorsList as $sector)
                        <option value="{{ $sector->id }}" {{ request('sector_id') == $sector->id ? 'selected' : '' }} data-area="{{ $sector->parent_id }}">
                            {{ $sector->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="min-w-[150px] flex-1">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Célula</label>
                <select name="cell_id" id="filter_cell" class="input-neo !py-2.5 w-full bg-white cursor-pointer">
                    <option value="">Todas as Células</option>
                    @foreach($cellsList as $cell)
                        <option value="{{ $cell->id }}" {{ request('cell_id') == $cell->id ? 'selected' : '' }} data-sector="{{ $cell->node_id }}">
                            {{ $cell->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="min-w-[140px] flex-1">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Status</label>
                <select name="status" class="input-neo !py-2.5 w-full bg-white cursor-pointer">
                    <option value="">Todos os Status</option>
                    <option value="Draft"       {{ request('status') === 'Draft'       ? 'selected' : '' }}>🟡 Rascunho</option>
                    <option value="Submitted"   {{ request('status') === 'Submitted'   ? 'selected' : '' }}>🟠 Submetido</option>
                    <option value="Conciliated" {{ request('status') === 'Conciliated' ? 'selected' : '' }}>✅ Conciliado</option>
                </select>
            </div>

            <div class="min-w-[130px] flex-1">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Data Início</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-neo !py-2.5 w-full">
            </div>

            <div class="min-w-[130px] flex-1">
                <label class="block text-[9px] font-black text-primary-light uppercase tracking-widest mb-2">Data Fim</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-neo !py-2.5 w-full">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn-neo bg-primary text-white text-[10px] font-black uppercase tracking-widest px-5 py-2.5 flex items-center gap-2">
                    <i class="fas fa-magnifying-glass"></i> Filtrar
                </button>
                @if(request()->anyFilled(['status', 'date_from', 'date_to', 'search', 'cell_id', 'sector_id', 'area_id']))
                <a href="{{ route('reports.index') }}" class="btn-neo bg-white border border-slate-200 text-slate-500 text-[10px] font-black uppercase tracking-widest px-5 py-2.5 flex items-center gap-2 hover:border-red-300 hover:text-red-500 transition-colors">
                    <i class="fas fa-times"></i> Limpar
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- TABELA GLOBAL --}}
    <div class="card-neo !p-0 overflow-hidden">
        <div class="px-6 py-4 bg-slate-50/60 border-b border-slate-100 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center">
                    <i class="fas fa-inbox text-primary text-xs"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Malotes Registrados</p>
                    <p class="text-[9px] text-slate-400 font-bold">{{ $reports->total() }} resultado(s)</p>
                </div>
            </div>
            <span class="hidden md:inline text-[9px] font-black text-slate-400 uppercase tracking-widest">
                Página {{ $reports->currentPage() }} de {{ $reports->lastPage() }}
            </span>
        </div>

        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 bg-slate-50/30">
                        <th class="px-6 py-4 text-left">Célula / Data</th>
                        <th class="px-6 py-4 text-center hidden lg:table-cell">Frequência</th>
                        <th class="px-6 py-4 text-center hidden md:table-cell">Financeiro</th>
                        <th class="px-6 py-4 text-right">Oferta Total</th>
                        <th class="px-6 py-4 text-center">Situação</th>
                        <th class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($reports as $report)
                    <tr class="hover:bg-slate-50/60 transition-colors group cursor-pointer"
                        onclick="window.location='{{ route('reports.show', $report) }}'">

                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-2xl bg-primary/8 flex items-center justify-center text-primary shrink-0 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                                    <i class="fas fa-calendar-week text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-slate-800 uppercase tracking-tight leading-tight">{{ $report->cell->name }}</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                        {{ $report->meeting_date?->format('D, d M Y') }}
                                    </p>
                                    @if($report->submittedBy)
                                    <p class="text-[9px] text-slate-400 font-bold mt-0.5">
                                        <i class="fas fa-user mr-1 opacity-50"></i>{{ $report->submittedBy->name }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5 text-center hidden lg:table-cell">
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-xl font-black text-slate-800 tracking-tighter leading-none">{{ $report->total_presence }}</span>
                                <div class="flex items-center gap-1.5 flex-wrap justify-center">
                                    <span class="text-[8px] font-black text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded-md">{{ $report->present_members }}M</span>
                                    <span class="text-[8px] font-black text-blue-500 bg-blue-50 px-1.5 py-0.5 rounded-md">{{ $report->visitors }}V</span>
                                    <span class="text-[8px] font-black text-violet-500 bg-violet-50 px-1.5 py-0.5 rounded-md">{{ $report->children }}C</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5 text-center hidden md:table-cell">
                            @php $hasMalote = ($report->total_offer ?? 0) > 0; @endphp
                            <div class="flex items-center justify-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full shrink-0 {{ $hasMalote ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                                <span class="text-[9px] font-black uppercase tracking-widest {{ $hasMalote ? 'text-emerald-600' : 'text-red-500' }}">
                                    {{ $hasMalote ? 'Enviado' : 'Pendente' }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-5 text-right">
                            <p class="text-sm font-black text-emerald-600 tracking-tighter font-money">R$ {{ number_format($report->total_offer, 2, ',', '.') }}</p>
                            <p class="text-[8px] text-slate-400 font-bold mt-0.5">
                                <span class="text-sky-500">PIX {{ number_format($report->offer_pix, 2, ',', '.') }}</span>
                                <span class="mx-1 opacity-30">|</span>
                                <span>Din. {{ number_format($report->offer_cash, 2, ',', '.') }}</span>
                            </p>
                        </td>

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
                                    <button type="submit" title="Enviar"
                                            class="btn-action bg-amber-50 border border-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white hover:border-amber-500">
                                        <i class="fas fa-paper-plane text-xs"></i>
                                    </button>
                                </form>
                                @endif
                                @if($report->status === 'Submitted' && ($user->isAdmin() || $user->isTreasurer()))
                                <form action="{{ route('reports.conciliate', $report) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Confirmar conciliação deste malote?')">
                                    @csrf
                                    <button type="submit" title="Conciliar"
                                            class="btn-action bg-emerald-50 border border-emerald-100 text-emerald-600 hover:bg-emerald-500 hover:text-white hover:border-emerald-500">
                                        <i class="fas fa-check-double text-xs"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 rounded-3xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-200">
                                    <i class="fas fa-inbox text-4xl"></i>
                                </div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nenhum malote encontrado.</p>
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
                        {{-- Top: Nome da Célula, Data e Status --}}
                        <div class="flex justify-between items-start gap-2">
                            <div class="flex flex-col">
                                <span class="text-[14px] font-bold text-slate-800 tracking-tight leading-tight">{{ $report->cell->name }}</span>
                                <span class="text-[11px] text-slate-400 font-bold tracking-wider mt-1.5 uppercase flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $report->meeting_date?->format('d \d\e M, Y') }}
                                </span>
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

                        {{-- Valor Recebido e Ações --}}
                        <div class="flex justify-between items-end border-t border-slate-100 pt-4 gap-4">
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Valor Arrecadado</p>
                                <span class="text-[18px] font-bold text-slate-800 tracking-tight font-money">R$ {{ number_format($report->total_offer, 2, ',', '.') }}</span>
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
                                @if($report->status === 'Submitted' && ($user->isAdmin() || $user->isTreasurer()))
                                    <form action="{{ route('reports.conciliate', $report) }}" method="POST" class="inline" onsubmit="return confirm('Confirmar conciliação deste malote?')">
                                        @csrf
                                        <button type="submit" title="Conciliar"
                                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 hover:bg-emerald-500 hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        {{-- Metadata: Líder, Status de Auditoria e Frequência --}}
                        <div class="flex flex-col gap-2 pt-3 border-t border-slate-100 text-[11px] text-slate-500">
                            <div class="flex justify-between items-center">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Líder: <span class="font-bold text-slate-700 ml-0.5">{{ $report->submittedBy ? $report->submittedBy->name : 'N/A' }}</span>
                                </span>
                                @php
                                    $auditStyle = match($report->status) {
                                        'Conciliated' => 'bg-emerald-50 text-emerald-600',
                                        'Submitted'   => 'bg-amber-50 text-amber-600',
                                        default       => 'bg-slate-100 text-slate-600',
                                    };
                                    $auditText = match($report->status) {
                                        'Conciliated' => 'Finalizado',
                                        'Submitted'   => 'Aguardando',
                                        default       => 'Em rascunho',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 text-[9px] font-bold rounded {{ $auditStyle }}">
                                    {{ $auditText }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-[10px] bg-slate-50 p-2.5 rounded-lg border border-slate-100/50 mt-1">
                                <span class="flex items-center gap-1 font-bold text-slate-600">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Freq. Total: <span class="text-slate-800 font-extrabold text-[11px] ml-0.5">{{ $report->total_presence }}</span>
                                </span>
                                <div class="flex gap-1.5 font-bold text-[10px]">
                                    <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200/60 text-slate-500">{{ $report->present_members }}M</span>
                                    <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200/60 text-blue-500">{{ $report->visitors }}V</span>
                                    <span class="bg-white px-1.5 py-0.5 rounded border border-slate-200/60 text-violet-500">{{ $report->children }}C</span>
                                </div>
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
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nenhum malote encontrado.</p>
                    </div>
                @endforelse
            </div>
        </div>

        @if($reports->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30 flex flex-col md:flex-row items-center justify-between gap-4">
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
                Mostrando {{ $reports->firstItem() }}–{{ $reports->lastItem() }} de {{ $reports->total() }}
            </span>
            <div class="flex items-center gap-2">
                @if(!$reports->onFirstPage())
                <a href="{{ $reports->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center border border-slate-200 rounded-xl text-slate-500 hover:border-primary hover:text-primary transition-all">
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
                <a href="{{ $reports->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center border border-slate-200 rounded-xl text-slate-500 hover:border-primary hover:text-primary transition-all">
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
                    const monthlyTotal = "R$ {{ number_format($monthlyTotal, 2, ',', '.') }}";
                    const totalPending = "{{ $totalPending }}";
                    const conciliated = "{{ $conciliated }}";
                    
                    const search = "{{ request('search') }}" || 'Nenhum';
                    const area = "{{ request('area_id') ? \App\Models\HierarchyNode::find(request('area_id'))?->name : 'Todas' }}";
                    const sector = "{{ request('sector_id') ? \App\Models\HierarchyNode::find(request('sector_id'))?->name : 'Todos' }}";
                    const cell = "{{ request('cell_id') ? \App\Models\Cell::find(request('cell_id'))?->name : 'Todas' }}";

                    const summaryText = `📊 *RESUMO GESTÃO DE MALOTES* 📊\n` +
                                        `*Arrecadado no Mês:* ${monthlyTotal}\n` +
                                        `*Pendentes:* ${totalPending}\n` +
                                        `*Conciliados no Mês:* ${conciliated}\n\n` +
                                        `*Filtros Aplicados:*\n` +
                                        `• Busca: ${search}\n` +
                                        `• Área: ${area}\n` +
                                        `• Setor: ${sector}\n` +
                                        `• Célula: ${cell}\n\n` +
                                        `_Gerado via ERP MDA Church_`;
                    
                    if (navigator.share) {
                        navigator.share({
                            title: 'Resumo Global de Malotes',
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

<script>
$(document).ready(function () {
    const areaSelect = $('#filter_area');
    const sectorSelect = $('#filter_sector');
    const cellSelect = $('#filter_cell');

    // Clone all options for sectors and cells to have a clean reference copy
    const allSectors = sectorSelect.find('option').clone();
    const allCells = cellSelect.find('option').clone();

    // Cache current selections to restore them after filtering
    const initialSector = '{{ request('sector_id') }}';
    const initialCell = '{{ request('cell_id') }}';

    function filterSectors() {
        const areaId = areaSelect.val();

        // Empty current options and keep only the placeholder
        sectorSelect.empty().append(allSectors.filter(function () {
            const areaAttr = $(this).data('area');
            return !areaId || !areaAttr || areaAttr == areaId || $(this).val() === '';
        }));

        // Restore selected value if it's still available in the filtered list
        if (initialSector && sectorSelect.find(`option[value="${initialSector}"]`).length > 0) {
            sectorSelect.val(initialSector);
        } else {
            sectorSelect.val('');
        }

        // Trigger cell filter
        filterCells();
    }

    function filterCells() {
        const sectorId = sectorSelect.val();
        const areaId = areaSelect.val();

        cellSelect.empty().append(allCells.filter(function () {
            const sectorAttr = $(this).data('sector');
            
            // If sector is selected, filter cells by sector
            if (sectorId) {
                return !sectorAttr || sectorAttr == sectorId || $(this).val() === '';
            }
            
            // If area is selected but no sector is selected, filter cells by all sectors of that area
            if (areaId) {
                const sectorOpt = allSectors.filter(`option[value="${sectorAttr}"]`);
                const sectorArea = sectorOpt.data('area');
                return !sectorArea || sectorArea == areaId || $(this).val() === '';
            }
            
            return true;
        }));

        // Restore selected value if it's still available in the filtered list
        if (initialCell && cellSelect.find(`option[value="${initialCell}"]`).length > 0) {
            cellSelect.val(initialCell);
        } else {
            cellSelect.val('');
        }
    }

    // Bind change events
    areaSelect.on('change', function () {
        filterSectors();
    });

    sectorSelect.on('change', function () {
        filterCells();
    });

    // Run initial filter on page load
    if (areaSelect.val()) {
        filterSectors();
    } else if (sectorSelect.val()) {
        filterCells();
    }
});
</script>
