@extends('layouts.app')

@section('header_title', 'Detalhes do Relatório')

@section('content')
<div class="relative">
    <!-- Massive Background Typography -->
    <div class="absolute -top-20 -left-10 text-[200px] font-black text-white/[0.02] pointer-events-none select-none tracking-tighter uppercase leading-none">
        DETALHES
    </div>

    <div class="max-w-7xl space-y-10 relative z-10">
        {{-- Top Bar --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('reports.index') }}" class="text-neutral-600 hover:text-accent font-black text-[10px] uppercase tracking-[0.3em] flex items-center gap-4 transition-all group">
                <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-2"></i>
                Voltar
            </a>

            <div class="flex items-center gap-3">
                @if($report->status === 'Draft')
                    <a href="{{ route('reports.edit', $report) }}" class="btn-neo bg-white border border-slate-200 text-slate-500 text-[10px] font-black uppercase tracking-widest px-6 py-3">
                        <i class="fas fa-pen mr-2"></i> Editar
                    </a>
                    <form action="{{ route('reports.submit', $report) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn-neo bg-primary text-white text-[10px] font-black uppercase tracking-widest px-6 py-3">
                            <i class="fas fa-paper-plane mr-2"></i> Submeter Malote
                        </button>
                    </form>
                @endif

                @if($report->status === 'Submitted' && (auth()->user()->isAdmin() || auth()->user()->isTreasurer()))
                    <form action="{{ route('reports.conciliate', $report) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn-neo bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest px-6 py-3">
                            <i class="fas fa-check-double mr-2"></i> Conciliar Relatório
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Header Card --}}
        <div class="card-neo p-10 bg-primary-card overflow-hidden relative">
            <div class="absolute top-0 right-0 p-10">
                <span class="text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl
                    {{ $report->status === 'Conciliated' ? 'bg-emerald-500/10 text-emerald-500' : 
                       ($report->status === 'Submitted' ? 'bg-amber-500/10 text-amber-500' : 'bg-slate-500/10 text-slate-400') }}">
                    Status: {{ $report->status_label }}
                </span>
            </div>

            <div class="flex flex-col md:flex-row gap-10 items-start md:items-center">
                <div class="size-20 rounded-3xl bg-accent/10 flex items-center justify-center text-accent text-3xl">
                    <i class="fas fa-church"></i>
                </div>
                <div class="space-y-2">
                    <p class="text-[10px] font-black text-accent uppercase tracking-widest italic leading-none">Célula Registrada</p>
                    <h1 class="text-5xl font-black text-title tracking-tighter leading-none">{{ $report->cell->name }}</h1>
                    <div class="flex flex-wrap gap-6 pt-2">
                        <div class="flex items-center gap-2">
                            <i class="far fa-calendar-alt text-neutral-500"></i>
                            <span class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider">{{ $report->meeting_date?->format('d \d\e M, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-book-open text-neutral-500"></i>
                            <span class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider">{{ $report->word_theme ?? 'Sem tema registrado' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-neutral-500"></i>
                            <span class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider">{{ $report->meeting_location ?? 'Local não informado' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            {{-- Column 1: Frequência --}}
            <div class="space-y-6">
                <div class="card-neo p-8 bg-primary-card">
                    <h3 class="text-[10px] font-black text-accent uppercase tracking-widest mb-8 flex items-center gap-3">
                        <i class="fas fa-users"></i> Pilar Humano
                    </h3>
                    <div class="grid grid-cols-2 gap-y-8">
                        <div>
                            <p class="text-[9px] font-black text-neutral-500 uppercase italic">Comprometidos</p>
                            <p class="text-3xl font-black text-title">{{ $report->committed_members }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-neutral-500 uppercase italic">Presentes</p>
                            <p class="text-3xl font-black text-emerald-500">{{ $report->present_members }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-neutral-500 uppercase italic">Visitantes</p>
                            <p class="text-3xl font-black text-amber-500">{{ $report->visitors }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-neutral-500 uppercase italic">Crianças</p>
                            <p class="text-3xl font-black text-indigo-500">{{ $report->children }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-neutral-500 uppercase italic">Outras Células</p>
                            <p class="text-3xl font-black text-neutral-400">{{ $report->other_cell_visitors }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-neutral-500 uppercase italic">Público Total</p>
                            <p class="text-3xl font-black text-primary">{{ $report->total_presence }}</p>
                        </div>
                    </div>
                </div>

                {{-- Membros Presentes (JSON List) --}}
                <div class="card-neo p-8 bg-primary-card">
                    <h3 class="text-[10px] font-black text-neutral-400 uppercase tracking-widest mb-6">Lista de Chamada</h3>
                    <div class="space-y-2">
                        @php
                            $memberIds = is_array($report->present_member_ids) ? $report->present_member_ids : json_decode($report->present_member_ids, true) ?? [];
                            $presentMembers = \App\Models\Member::whereIn('id', $memberIds)->get();
                        @endphp
                        @forelse($presentMembers as $member)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-black/5 border border-white/5">
                                <div class="size-6 rounded-full bg-emerald-500/20 text-emerald-500 flex items-center justify-center text-[10px] font-black">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span class="text-[11px] font-bold text-neutral-300 uppercase tracking-wider">{{ $member->name }}</span>
                            </div>
                        @empty
                            <p class="text-[10px] font-bold text-neutral-500 italic">Nenhum membro registrado.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Column 2: Pastoral & Social --}}
            <div class="space-y-6">
                <div class="card-neo p-8 bg-primary-card">
                    <h3 class="text-[10px] font-black text-accent uppercase tracking-widest mb-8 flex items-center gap-3">
                        <i class="fas fa-heart"></i> Impacto Ministerial
                    </h3>
                    <div class="grid grid-cols-1 gap-6">
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-black/5 border border-white/5">
                            <div>
                                <p class="text-[9px] font-black text-neutral-500 uppercase italic">Casas de Paz</p>
                                <p class="text-2xl font-black text-title">{{ $report->house_of_peace }}</p>
                            </div>
                            <div class="size-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-500">
                                <i class="fas fa-home"></i>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-black/5 border border-white/5">
                            <div>
                                <p class="text-[9px] font-black text-neutral-500 uppercase italic">MDAs / Discipulados</p>
                                <p class="text-2xl font-black text-title">{{ $report->mdas_done }}</p>
                            </div>
                            <div class="size-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-black/5 border border-white/5">
                            <div>
                                <p class="text-[9px] font-black text-neutral-500 uppercase italic">Quilo do Amor</p>
                                <p class="text-2xl font-black text-title">{{ number_format($report->kg_of_love, 1) }} Kg</p>
                            </div>
                            <div class="size-10 rounded-xl bg-rose-500/10 flex items-center justify-center text-rose-500">
                                <i class="fas fa-hand-holding-heart"></i>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-black/5 border border-white/5">
                                <p class="text-[9px] font-black text-neutral-500 uppercase italic">Conversões</p>
                                <p class="text-2xl font-black text-emerald-500">{{ $report->conversions }}</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-black/5 border border-white/5">
                                <p class="text-[9px] font-black text-neutral-500 uppercase italic">Reconciliações</p>
                                <p class="text-2xl font-black text-title">{{ $report->reconciliations }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Visitantes (JSON List) --}}
                <div class="card-neo p-8 bg-primary-card">
                    <h3 class="text-[10px] font-black text-neutral-400 uppercase tracking-widest mb-6">Visitantes da Noite</h3>
                    <div class="space-y-2">
                        @php
                            $visitors = is_array($report->visitor_names) ? $report->visitor_names : json_decode($report->visitor_names, true) ?? [];
                        @endphp
                        @forelse($visitors as $v)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-black/5 border border-white/5">
                                <div class="size-6 rounded-full bg-accent/20 text-accent flex items-center justify-center text-[10px] font-black">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <span class="text-[11px] font-bold text-neutral-300 uppercase tracking-wider">{{ $v['name'] ?? $v }}</span>
                            </div>
                        @empty
                            <p class="text-[10px] font-bold text-neutral-500 italic">Nenhum visitante registrado.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Column 3: Financeiro & Auditoria --}}
            <div class="space-y-6">
                <div class="card-neo p-8 bg-emerald-950/20 border-emerald-500/20">
                    <h3 class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-8 flex items-center gap-3">
                        <i class="fas fa-coins"></i> Consolidado Financeiro
                    </h3>
                    
                    <div class="space-y-6">
                        <div class="flex justify-between items-end border-b border-white/5 pb-6">
                            <div>
                                <p class="text-[9px] font-black text-neutral-500 uppercase italic">Oferta Total</p>
                                <p class="text-4xl font-black text-emerald-500 tracking-tighter italic">R$ {{ number_format($report->total_offer, 2, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[8px] font-black text-emerald-500/50 uppercase tracking-widest">Semanal</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="size-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-500 text-[10px]">PIX</div>
                                    <span class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider">Transferência</span>
                                </div>
                                <span class="text-sm font-black text-title italic">R$ {{ number_format($report->offer_pix, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="size-8 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-500 text-[10px]">CASH</div>
                                    <span class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider">Espécie</span>
                                </div>
                                <span class="text-sm font-black text-title italic">R$ {{ number_format($report->offer_cash, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Auditoria --}}
                <div class="card-neo p-8 bg-primary-card">
                    <h3 class="text-[10px] font-black text-neutral-400 uppercase tracking-widest mb-8 flex items-center gap-3">
                        <i class="fas fa-shield-alt"></i> Auditoria
                    </h3>
                    <div class="space-y-6">
                        @if($report->submittedBy)
                            <div class="flex gap-4">
                                <div class="size-8 rounded-full bg-primary/20 flex items-center justify-center text-[10px] font-black text-primary italic">L</div>
                                <div>
                                    <p class="text-[9px] font-black text-neutral-500 uppercase italic">{{ $report->status === 'Draft' ? 'Criado por' : 'Líder que Submeteu' }}</p>
                                    <p class="text-xs font-black text-title uppercase">{{ $report->submittedBy->name }}</p>
                                    <p class="text-[8px] font-bold text-neutral-600 uppercase tracking-widest">{{ ($report->submitted_at ?? $report->created_at)->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($report->conciliatedBy)
                            <div class="flex gap-4">
                                <div class="size-8 rounded-full bg-emerald-500/20 flex items-center justify-center text-[10px] font-black text-emerald-500 italic">T</div>
                                <div>
                                    <p class="text-[9px] font-black text-neutral-500 uppercase italic">Conciliado por</p>
                                    <p class="text-xs font-black text-title uppercase">{{ $report->conciliatedBy->name }}</p>
                                    <p class="text-[8px] font-bold text-neutral-600 uppercase tracking-widest">{{ $report->conciliated_at?->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Notas --}}
                @if($report->notes)
                <div class="card-neo p-8 bg-primary-card">
                    <h3 class="text-[10px] font-black text-neutral-400 uppercase tracking-widest mb-4 italic">Observações Pastorais</h3>
                    <p class="text-xs font-medium text-neutral-500 leading-relaxed italic">"{{ $report->notes }}"</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
