@extends('layouts.app')

@section('title', 'Log de Auditoria')

@section('content')
<div class="audit-logs space-y-8 animate-reveal-up">
    <div class="flex justify-between items-end mb-6">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight uppercase">Histórico de Ações</h2>
            <p class="text-primary-light font-medium mt-1">Rastreamento completo de alterações críticas no sistema.</p>
        </div>
        <div class="bg-slate-100 px-4 py-2 rounded-xl border border-slate-200">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total de Registros: {{ $logs->total() }}</span>
        </div>
    </div>

    <div class="card-neo !p-0 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-8 py-5 text-[10px] font-black text-primary-light uppercase tracking-widest">Usuário</th>
                    <th class="px-8 py-5 text-[10px] font-black text-primary-light uppercase tracking-widest">Ação</th>
                    <th class="px-8 py-5 text-[10px] font-black text-primary-light uppercase tracking-widest">Detalhes</th>
                    <th class="px-8 py-5 text-[10px] font-black text-primary-light uppercase tracking-widest text-right">Data/Hora</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($logs as $log)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-800 font-black text-xs">
                                    {{ substr($log->user->name ?? '?', 0, 1) }}
                                </div>
                                <span class="text-sm font-bold text-slate-800">{{ $log->user->name ?? 'Sistema' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            @php
                                $colorClass = 'bg-slate-100 text-slate-600';
                                if (str_contains($log->action, 'CREATE')) $colorClass = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                                if (str_contains($log->action, 'UPDATE')) $colorClass = 'bg-amber-50 text-amber-600 border-amber-100';
                                if (str_contains($log->action, 'DELETE')) $colorClass = 'bg-rose-50 text-rose-600 border-rose-100';
                            @endphp
                            <span class="px-3 py-1 rounded-lg text-[10px] font-black border {{ $colorClass }} uppercase tracking-wider">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <button onclick="showDetails('{{ addslashes(json_encode($log->description)) }}')" class="text-xs font-medium text-accent hover:underline flex items-center gap-2">
                                <i class="fas fa-eye"></i> Visualizar Payload
                            </button>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="text-sm font-bold text-slate-800">{{ $log->created_at->format('d/m/Y') }}</div>
                            <div class="text-[10px] text-primary-light font-medium">{{ $log->created_at->format('H:i:s') }}</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $logs->links() }}
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showDetails(data) {
        try {
            const json = JSON.parse(data);
            Swal.fire({
                title: 'Detalhes da Ação',
                html: `<pre class="text-left text-xs bg-slate-900 text-emerald-400 p-6 rounded-2xl overflow-auto max-h-[500px]">${JSON.stringify(json, null, 4)}</pre>`,
                width: '800px',
                confirmButtonColor: '#0ea5e9',
                confirmButtonText: 'FECHAR',
                background: '#fff',
            });
        } catch (e) {
            Swal.fire({
                title: 'Detalhes da Ação',
                text: data,
                confirmButtonColor: '#0ea5e9',
            });
        }
    }
</script>
@endpush
@endsection
