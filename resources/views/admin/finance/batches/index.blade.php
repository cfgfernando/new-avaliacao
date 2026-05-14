@extends('layouts.app')

@section('title', 'Malotes e Remessas')

@section('content')
<div class="space-y-6 animate-reveal-up">
    <!-- Header Elite V8 -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Malotes e Remessas</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Controle de remessas e conferência de valores</p>
        </div>
        <button onclick="openFinanceModal('batchModal')" class="btn-neo btn-primary text-xs py-2.5">
            <i class="fas fa-plus"></i>
            <span>NOVO MALOTE</span>
        </button>
    </div>

    <!-- Tabela Elite V8 -->
    <div class="card-neo !p-0 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Inventário de Malotes</h3>
        </div>
        <div class="p-4">
            <table id="batchesTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 bg-slate-50/50 uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4">Código</th>
                        <th class="px-6 py-4">Responsável / Data</th>
                        <th class="px-6 py-4 text-right">Valor Declarado</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($batches as $batch)
                    <tr class="group hover:bg-slate-50/50 transition-all cursor-pointer" onclick="editBatch({{ $batch->id }})">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center shadow-sm group-hover:bg-accent group-hover:text-white transition-all">
                                    <i class="fas fa-box-archive text-[10px]"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-800 tracking-tight">{{ $batch->code }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-primary-light uppercase tracking-widest mb-0.5">Tesouraria</span>
                                <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">{{ \Carbon\Carbon::parse($batch->created_at)->format('d/m/Y H:i') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right bg-slate-50/30">
                            <span class="text-sm font-money text-slate-800">
                                R$ {{ number_format($batch->declared_amount, 2, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 {{ $batch->status === 'confirmed' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : ($batch->status === 'divergent' ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-amber-50 text-amber-600 border-amber-100') }} border rounded-lg text-[9px] font-black uppercase tracking-widest">
                                {{ $batch->status === 'confirmed' ? 'Conferido' : ($batch->status === 'divergent' ? 'Divergente' : 'Pendente') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2" onclick="event.stopPropagation()">
                                <button onclick="viewBatch({{ $batch->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-all shadow-sm group/btn" title="Conferir">
                                    <i class="fas fa-list-check text-[10px]"></i>
                                </button>
                                <button onclick="editBatch({{ $batch->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-accent hover:text-white flex items-center justify-center transition-all shadow-sm group/btn" title="Editar">
                                    <i class="fas fa-pen text-[10px]"></i>
                                </button>
                                <button onclick="deleteBatch({{ $batch->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all shadow-sm group/btn" title="Excluir">
                                    <i class="fas fa-trash text-[10px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('modals')
<!-- Modal Elite V8 -->
<div id="batchModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[9999] flex items-center justify-center p-4 sm:p-6 overflow-y-auto custom-scrollbar">
    <div class="bg-white w-full max-w-lg m-auto animate-reveal-up overflow-hidden shadow-2xl border border-white/20 rounded-[2rem] flex flex-col">
        
        <!-- Header -->
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
            <div>
                <h3 id="batchModalTitle" class="text-xl font-black text-slate-800 uppercase tracking-tight">Registro de Malote</h3>
                <p class="text-[9px] text-primary-light font-black uppercase tracking-widest mt-1">Controle de Remessas</p>
            </div>
            <button type="button" onclick="closeFinanceModal('batchModal')" class="w-10 h-10 rounded-xl flex items-center justify-center bg-slate-100 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="batchForm" action="{{ route('admin.finance.batches.store') }}" method="POST" class="p-8 space-y-6 bg-white">
            @csrf
            <input type="hidden" name="_method" id="batchFormMethod" value="POST">
            
            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Código do Malote</label>
                <input type="text" name="code" required class="input-neo uppercase" placeholder="MAL-2024-XXX">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Valor Declarado (R$)</label>
                    <input type="text" name="declared_amount" required class="input-neo font-money mask-money" placeholder="0,00">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Status</label>
                    <select name="status" required class="input-neo uppercase">
                        <option value="pending">Aguardando Conferência</option>
                        <option value="confirmed">Conferência Finalizada</option>
                        <option value="divergent">Divergência Detectada</option>
                    </select>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex gap-4">
                <button type="button" onclick="closeFinanceModal('batchModal')" class="flex-1 px-6 py-3 border border-slate-100 rounded-xl text-[10px] font-black text-primary-light uppercase tracking-widest hover:bg-slate-50 transition-all">Cancelar</button>
                <button type="submit" class="flex-[2] btn-neo btn-primary text-[10px] font-black uppercase tracking-widest">
                    <i class="fas fa-check-circle mr-2"></i> Confirmar Registro
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#batchesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
            },
            dom: '<"flex justify-between items-center mb-8 px-2"f l>rt<"flex justify-between items-center mt-8 px-2"i p>',
            columnDefs: [
                { orderable: false, targets: 4 }
            ],
            drawCallback: function() {
                $('.dataTables_paginate .paginate_button').addClass('px-3 py-1 bg-white border border-slate-100 rounded-lg text-[10px] font-black text-slate-600 uppercase tracking-widest mx-1 hover:bg-slate-50 transition-all');
            }
        });
    });

    function openFinanceModal(id) {
        $(`#${id}`).removeClass('hidden').addClass('flex');
        $('body').addClass('overflow-hidden');
    }

    function closeFinanceModal(id) {
        $(`#${id}`).addClass('hidden').removeClass('flex');
        $('body').removeClass('overflow-hidden');
        $('#batchForm')[0].reset();
        $('#batchForm').attr('action', '{{ route('admin.finance.batches.store') }}');
        $('#batchFormMethod').val('POST');
        $('#batchForm input, #batchForm select').prop('disabled', false);
        $('button[type="submit"]').removeClass('hidden').html('<i class="fas fa-check-circle mr-2"></i> Confirmar Registro');
        $('#batchModalTitle').text('Registro de Malote');
    }

    async function editBatch(id) {
        openFinanceModal('batchModal');
        $('#batchModalTitle').text('Gestão de Malote: #' + id);
        $('#batchForm').attr('action', `/admin/finance/batches/${id}`);
        $('#batchFormMethod').val('PUT');
        $('button[type="submit"]').html('<i class="fas fa-save mr-2"></i> Salvar Alterações');

        try {
            const response = await fetch(`/admin/finance/batches/${id}`);
            const data = await response.json();

            $('input[name="code"]').val(data.code);
            $('input[name="declared_amount"]').val(new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(data.declared_amount));
            $('select[name="status"]').val(data.status);
            
            if (typeof $.fn.mask === 'function') {
                $('.mask-money').mask('#.##0,00', {reverse: true});
            }
        } catch (error) {
            console.error('Erro ao carregar dados do malote:', error);
        }
    }

    function viewBatch(id) {
        editBatch(id).then(() => {
            $('#batchModalTitle').text('Auditoria de Malote: #' + id);
            $('#batchForm input, #batchForm select').prop('disabled', true);
            $('button[type="submit"]').addClass('hidden');
        });
    }

    async function deleteBatch(id) {
        if(confirm('ALERTA CRÍTICO: Deseja realmente excluir este registro de malote? Esta operação é irreversível e deixará um log de auditoria.')) {
            try {
                const response = await fetch(`/admin/finance/batches/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                if (result.success) {
                    location.reload();
                } else {
                    alert(result.message || 'Erro ao excluir.');
                }
            } catch (error) {
                console.error('Erro ao excluir malote:', error);
                alert('Erro de conexão ao tentar excluir.');
            }
        }
    }

</script>
@endpush

