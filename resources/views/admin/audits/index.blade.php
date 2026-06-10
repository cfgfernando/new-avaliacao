@extends('layouts.app')

@section('header_title', 'Auditoria Forense')

@section('content')
<div class="space-y-8">
    <!-- Action Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
        <div>
            <h1 class="text-4xl font-black text-title leading-none tracking-tighter">AUDITORIA <br><span class="text-red-500">FORENSE.</span></h1>
            <p class="text-neutral-500 font-medium mt-4">Rastreabilidade completa e integridade de dados.</p>
        </div>
        <div class="px-6 py-4 border border-red-500/20 bg-red-500/5 flex items-center gap-4">
            <i class="fas fa-shield-halved text-red-500"></i>
            <span class="text-[10px] font-black text-red-500 uppercase tracking-[0.3em]">Protocolo de Segurança: Nível 5</span>
        </div>
    </div>

    <!-- Audit List -->
    <div class="card-neo bg-primary-card">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-black/20 text-neutral-600 text-[9px] uppercase font-black tracking-widest">
                    <tr>
                        <th class="px-12 py-6">Evento / Operador</th>
                        <th class="px-12 py-6">Objeto Afetado</th>
                        <th class="px-12 py-6">Endereço IP</th>
                        <th class="px-12 py-6">Timestamp</th>
                        <th class="px-12 py-6 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($audits as $audit)
                    <tr class="hover:bg-title/[0.02] transition-colors group font-mono">
                        <td class="px-12 py-8">
                            <div class="flex items-center gap-6">
                                @php
                                    $eventColors = [
                                        'created' => 'border-accent text-accent',
                                        'updated' => 'border-white text-title',
                                        'deleted' => 'border-red-500 text-red-500',
                                    ];
                                    $eventColor = $eventColors[$audit->event] ?? 'border-neutral-700 text-neutral-500';
                                @endphp
                                <div class="w-12 h-12 border {{ $eventColor }} flex items-center justify-center text-[9px] font-black uppercase group-hover:bg-current group-hover:text-black transition-all">
                                    {{ substr($audit->event, 0, 3) }}
                                </div>
                                <div>
                                    <div class="text-xs font-black text-title uppercase tracking-widest">{{ $audit->user?->name ?? 'SYSTEM' }}</div>
                                    <div class="text-[9px] font-bold text-neutral-600 uppercase tracking-widest">{{ $audit->event }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-12 py-8">
                            <span class="text-[10px] font-black text-title uppercase tracking-widest">
                                {{ class_basename($audit->auditable_type) }}
                            </span>
                            <span class="text-[9px] font-bold text-accent ml-2">#{{ $audit->auditable_id }}</span>
                        </td>
                        <td class="px-12 py-8">
                            <code class="text-[10px] font-black text-neutral-500 bg-neutral-900 border border-white/5 px-3 py-1">{{ $audit->ip_address }}</code>
                        </td>
                        <td class="px-12 py-8 text-[10px] font-black text-neutral-600 uppercase tracking-widest">
                            {{ $audit->created_at->format('Y.m.d | H:i:s') }}
                        </td>
                        <td class="px-12 py-8 text-right">
                            <a href="{{ route('admin.audits.show', $audit) }}" class="text-neutral-500 hover:text-title transition-all">
                                <i class="fas fa-terminal"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <p class="text-slate-400 font-bold">Nenhum log de auditoria registrado até o momento.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($audits->hasPages())
        <div class="px-8 py-6 bg-slate-50/30 border-t border-slate-100">
            {{ $audits->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
