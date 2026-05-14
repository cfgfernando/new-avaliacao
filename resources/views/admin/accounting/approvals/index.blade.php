@extends('layouts.app')

@section('title', 'Aprovações Financeiras')

@section('content')
<div class="space-y-6 animate-reveal-up">
    <!-- Header Elite V8 -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Fila de Aprovação</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Governança e Controle de Saídas de Caixa</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="bg-amber-50 text-amber-700 px-4 py-2 rounded-2xl border border-amber-100 flex items-center gap-2">
                <i class="fas fa-hourglass-half animate-pulse"></i>
                <span class="text-[10px] font-black uppercase tracking-widest">{{ $pendingTransactions->total() }} Pendentes</span>
            </div>
        </div>
    </div>

    <!-- Filtros e Busca (Opcional para o futuro) -->

    <!-- Lista de Pendências -->
    <div class="grid grid-cols-1 gap-4">
        @forelse($pendingTransactions as $tx)
            <div id="tx-row-{{ $tx->id }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-primary-light/30 transition-all duration-300">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    
                    <!-- Info Principal -->
                    <div class="flex items-center gap-5 flex-1">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-primary-light/10 group-hover:text-primary-light transition-colors">
                            <i class="fas fa-receipt text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 uppercase tracking-tight text-sm">{{ $tx->description }}</h3>
                            <div class="flex flex-wrap items-center gap-3 mt-1">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1">
                                    <i class="far fa-calendar"></i> {{ $tx->transaction_date->format('d/m/Y') }}
                                </span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1">
                                    <i class="fas fa-wallet"></i> {{ $tx->financialAccount->name }}
                                </span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1">
                                    <i class="fas fa-tag"></i> {{ $tx->chartOfAccount->name }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Valor -->
                    <div class="text-right px-6 border-x border-slate-50 hidden lg:block">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Valor Solicitado</p>
                        <p class="text-xl font-black text-slate-900 tracking-tighter">
                            R$ {{ number_format($tx->amount, 2, ',', '.') }}
                        </p>
                    </div>

                    <!-- Ações -->
                    <div class="flex items-center gap-3 w-full lg:w-auto">
                        <button onclick="approveTransaction({{ $tx->id }})" class="flex-1 lg:flex-none btn-neo bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest px-6 py-3 flex items-center justify-center gap-2 hover:bg-emerald-600 shadow-emerald-100">
                            <i class="fas fa-check"></i> APROVAR
                        </button>
                        <button onclick="openRejectModal({{ $tx->id }})" class="flex-1 lg:flex-none btn-neo bg-white text-rose-500 text-[10px] font-black uppercase tracking-widest px-6 py-3 flex items-center justify-center gap-2 border border-rose-100 hover:bg-rose-50">
                            <i class="fas fa-times"></i> REJEITAR
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-slate-50 rounded-3xl p-12 text-center border-2 border-dashed border-slate-200">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <i class="fas fa-check-double text-emerald-500 text-xl"></i>
                </div>
                <h3 class="font-black text-slate-800 uppercase tracking-tight">Tudo em dia!</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Não há despesas aguardando aprovação no momento.</p>
            </div>
        @endforelse
    </div>

    <!-- Paginação -->
    <div class="mt-8">
        {{ $pendingTransactions->links() }}
    </div>
</div>

<!-- Modal de Rejeição -->
<div id="rejectModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden animate-reveal-up">
        <div class="p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">Rejeitar Despesa</h2>
                    <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest mt-1">Informe o motivo da rejeição</p>
                </div>
                <button onclick="closeRejectModal()" class="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <input type="hidden" id="rejectTxId">
            <div class="space-y-4">
                <textarea id="rejectionReason" class="input-neo w-full min-h-[120px] !rounded-3xl" placeholder="Ex: Valor incorreto, falta de nota fiscal, etc..."></textarea>
                
                <button onclick="confirmReject()" class="w-full btn-neo bg-rose-500 text-white py-4 font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-rose-600 shadow-rose-100">
                    <i class="fas fa-times-circle"></i> CONFIRMAR REJEIÇÃO
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function approveTransaction(id) {
        if (!confirm('Deseja realmente aprovar esta despesa? O saldo da conta será debitado imediatamente.')) return;

        fetch(`/admin/accounting/approvals/${id}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $(`#tx-row-${id}`).fadeOut(300, function() { 
                    $(this).remove(); 
                    if ($('.group').length === 0) location.reload();
                });
                toastr.success(data.message);
            }
        });
    }

    function openRejectModal(id) {
        $('#rejectTxId').val(id);
        $('#rejectionReason').val('');
        $('#rejectModal').removeClass('hidden').addClass('flex');
    }

    function closeRejectModal() {
        $('#rejectModal').addClass('hidden').removeClass('flex');
    }

    function confirmReject() {
        const id = $('#rejectTxId').val();
        const reason = $('#rejectionReason').val();

        if (!reason) {
            toastr.error('Por favor, informe o motivo da rejeição.');
            return;
        }

        fetch(`/admin/accounting/approvals/${id}/reject`, {

            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeRejectModal();
                $(`#tx-row-${id}`).fadeOut(300, function() { 
                    $(this).remove(); 
                    if ($('.group').length === 0) location.reload();
                });
                toastr.warning(data.message);
            }
        });
    }
</script>
@endpush
@endsection
