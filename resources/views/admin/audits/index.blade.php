@extends('layouts.app')

@section('header_title', 'Auditoria Forense')

@section('content')
<div class="space-y-8">
    <!-- Action Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-title tracking-tight">Logs de Auditoria</h1>
            <p class="text-slate-500 font-medium mt-1">Rastreabilidade completa de todas as alterações realizadas no sistema.</p>
        </div>
        <div class="px-4 py-2 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                <i class="fas fa-shield-halved"></i>
            </div>
            <span class="text-[10px] font-black text-red-700 uppercase tracking-widest">Nível de Segurança: Máximo</span>
        </div>
    </div>

    <!-- Audit List -->
    <div class="card-elite">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">Evento / Usuário</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">Módulo (Auditable)</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">Endereço IP</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">Data/Hora</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($audits as $audit)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                @php
                                    $eventColors = [
                                        'created' => 'bg-emerald-50 text-emerald-600',
                                        'updated' => 'bg-blue-50 text-blue-600',
                                        'deleted' => 'bg-red-50 text-red-600',
                                    ];
                                    $eventColor = $eventColors[$audit->event] ?? 'bg-slate-50 text-slate-600';
                                @endphp
                                <div class="w-10 h-10 rounded-xl {{ $eventColor }} flex items-center justify-center text-[10px] font-black uppercase group-hover:rotate-6 transition-transform">
                                    {{ substr($audit->event, 0, 3) }}
                                </div>
                                <div>
                                    <div class="text-sm font-black text-title">{{ $audit->user->name ?? 'Sistema' }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $audit->event }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-xs font-bold text-slate-600">
                                {{ class_basename($audit->auditable_type) }}
                            </span>
                            <span class="text-[10px] font-medium text-slate-400 ml-1">#{{ $audit->auditable_id }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <code class="text-[10px] font-black text-slate-500 bg-slate-100 px-2 py-1 rounded-md">{{ $audit->ip_address }}</code>
                        </td>
                        <td class="px-8 py-6 text-xs font-bold text-slate-500">
                            {{ $audit->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="px-8 py-6 text-right">
                            <a href="{{ route('admin.audits.show', $audit) }}" class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                                <i class="fas fa-search-plus text-xs"></i>
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
