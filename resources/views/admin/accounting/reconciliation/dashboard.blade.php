@extends('layouts.app')

@section('content')
<div class="max-w-[1400px] mx-auto">
    <!-- Header Elite V8 -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="animate-reveal-left">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.accounting.reconciliation.index') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Painel de Batimento</h1>
            </div>
            <p class="text-[10px] font-black text-primary-light uppercase tracking-[0.2em] flex items-center gap-2">
                Conta: {{ $account->name }} | Extrato Processado
            </p>
        </div>

        <div class="flex gap-4 animate-reveal-right">
            <div class="bg-white px-6 py-3 rounded-[1.5rem] border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="text-right">
                    <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Saldo no ERP</span>
                    <span class="block text-sm font-black text-slate-800 uppercase tracking-tight">R$ {{ number_format($account->balance, 2, ',', '.') }}</span>
                </div>
                <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center text-slate-300">
                    <i class="fas fa-wallet text-xs"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Batimento -->
    <div class="card-neo animate-reveal-up !p-0 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/30 flex justify-between items-center">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Fluxo de Transações do Extrato</h3>
            <div class="flex gap-4">
                 <span class="flex items-center gap-2 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Match Encontrado
                </span>
                <span class="flex items-center gap-2 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> Novo Lançamento
                </span>
            </div>
        </div>

        <div class="p-4">
            <table class="w-full">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 bg-slate-50/50">
                        <th class="px-6 py-4 text-left">Data Banco</th>
                        <th class="px-6 py-4 text-left">Descrição Extrato</th>
                        <th class="px-6 py-4 text-right">Valor</th>
                        <th class="px-6 py-4 text-center">Correspondência no ERP</th>
                        <th class="px-6 py-4 text-center">Ação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($results as $item)
                    <tr class="group hover:bg-slate-50/30 transition-all">
                        <td class="px-6 py-5">
                            <span class="text-xs font-bold text-slate-800 uppercase tracking-tight">{{ $item['bank']->date->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-slate-800 uppercase tracking-tight leading-tight mb-1 truncate max-w-[250px]">{{ $item['bank']->description }}</span>
                                <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">ID: {{ $item['bank']->id }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right bg-slate-50/20">
                            <span class="text-sm font-money {{ $item['bank']->amount > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $item['bank']->amount > 0 ? '+' : '-' }} R$ {{ number_format(abs($item['bank']->amount), 2, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($item['is_reconciled'])
                                <div class="flex items-center justify-center gap-2">
                                    <span class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 text-[9px] font-black uppercase tracking-widest">
                                        <i class="fas fa-check-circle mr-1"></i> Já Conciliado
                                    </span>
                                </div>
                            @elseif($item['match'])
                                <div class="flex flex-col items-center">
                                    <div class="px-4 py-2 rounded-2xl bg-white border border-emerald-100 shadow-sm flex items-center gap-3">
                                        <div class="text-left">
                                            <span class="block text-[10px] font-black text-slate-800 uppercase leading-none mb-1">ERP: #{{ $item['match']->id }}</span>
                                            <span class="block text-[8px] text-slate-400 font-black uppercase tracking-widest">{{ $item['match']->description }}</span>
                                        </div>
                                        <div class="w-6 h-6 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-500">
                                            <i class="fas fa-link text-[10px]"></i>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">Nenhuma sugestão encontrada</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if(!$item['is_reconciled'])
                                @if($item['match'])
                                    <button onclick="confirmReconcile({{ $item['match']->id }}, '{{ $item['bank']->id }}', this)" class="btn-neo bg-emerald-500 text-white !px-4 !py-2 text-[9px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/20">
                                        Conciliar
                                    </button>
                                @else
                                    <button class="btn-neo bg-amber-500 text-white !px-4 !py-2 text-[9px] font-black uppercase tracking-widest shadow-lg shadow-amber-500/20">
                                        Novo Lançamento
                                    </button>
                                @endif
                            @else
                                <i class="fas fa-lock text-slate-200"></i>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmReconcile(transactionId, bankId, btn) {
        if(!confirm('Deseja vincular este lançamento do extrato à transação do ERP?')) return;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch("{{ route('admin.accounting.reconciliation.confirm') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                transaction_id: transactionId,
                bank_transaction_id: bankId
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                toastr.success(data.message);
                btn.closest('tr').classList.add('opacity-50');
                btn.parentElement.innerHTML = '<i class="fas fa-check-circle text-emerald-500"></i>';
            } else {
                toastr.error(data.message);
                btn.disabled = false;
                btn.innerHTML = 'Conciliar';
            }
        });
    }
</script>
@endpush
