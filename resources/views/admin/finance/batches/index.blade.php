@extends('layouts.app')

@section('title', 'Malotes e Remessas')

@section('content')
<div class="space-y-8 animate-reveal-up">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-black text-primary-dark tracking-tight">MALOTES</h2>
            <p class="text-primary-light font-medium mt-1">Controle de remessas e conferência de valores.</p>
        </div>
        <button onclick="openFinanceModal('batchModal')" class="btn-neo btn-primary">
            <i class="fas fa-plus"></i>
            <span>Abrir Novo Malote</span>
        </button>
    </div>

    <!-- Tabela de Malotes -->
    <div class="card-neo overflow-hidden">
        <div class="p-4">
            <table id="batchesTable" class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Responsável / Data</th>
                    <th class="text-right">Valor Declarado</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($batches as $batch)
                <tr class="group" onclick="editBatch({{ $batch->id }})">
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center text-primary-dark shadow-sm border border-white group-hover:bg-accent group-hover:text-white transition-all">
                                <i class="fas fa-box-archive text-sm"></i>
                            </div>
                            <span class="text-sm font-black text-primary-dark uppercase tracking-tight">{{ $batch->code }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-primary-dark uppercase tracking-tight">Tesouraria</span>
                            <span class="text-[9px] text-slate-400 font-bold">{{ \Carbon\Carbon::parse($batch->created_at)->format('d/m/Y H:i') }}</span>
                        </div>
                    </td>
                    <td class="text-right">
                        <span class="text-sm font-money text-primary-dark tracking-tighter">
                            R$ {{ number_format($batch->declared_amount, 2, ',', '.') }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="px-2 py-0.5 {{ $batch->status === 'confirmed' ? 'bg-emerald-50 text-emerald-600' : ($batch->status === 'divergent' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600') }} rounded text-[9px] font-black uppercase">
                            {{ $batch->status === 'confirmed' ? 'Conferido' : ($batch->status === 'divergent' ? 'Divergente' : 'Pendente') }}
                        </span>
                    </td>
                    <td class="text-center" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="viewBatch({{ $batch->id }})" class="btn-action bg-slate-50 text-slate-500 hover:bg-slate-800 hover:text-white" title="Ver Detalhes">
                                <i class="fas fa-list-check text-[10px]"></i>
                            </button>
                            <button onclick="editBatch({{ $batch->id }})" class="btn-action bg-slate-50 text-slate-500 hover:bg-accent hover:text-white" title="Editar">
                                <i class="fas fa-pencil-alt text-[10px]"></i>
                            </button>
                            <button onclick="deleteBatch({{ $batch->id }})" class="btn-action bg-slate-50 text-slate-500 hover:bg-rose-500 hover:text-white" title="Excluir">
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

@push('modals')
<!-- Modal Malote -->
<div id="batchModal" class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[100] flex items-center justify-center p-6">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-reveal-up">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 id="batchModalTitle" class="text-xl font-black text-primary-dark uppercase tracking-tight">Novo Malote</h3>
            <button type="button" onclick="closeFinanceModal('batchModal')" class="w-10 h-10 rounded-xl flex items-center justify-center hover:bg-rose-50 text-slate-400 hover:text-rose-500 transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="batchForm" action="{{ route('admin.finance.batches.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <input type="hidden" name="_method" id="batchFormMethod" value="POST">
            
            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Código do Malote</label>
                <input type="text" name="code" required class="input-neo" placeholder="Ex: MAL-2024-001">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Valor Declarado (R$)</label>
                    <input type="text" name="declared_amount" required class="input-neo mask-money" placeholder="0,00">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Status Inicial</label>
                    <select name="status" required class="input-neo">
                        <option value="pending">Pendente</option>
                        <option value="confirmed">Conferido</option>
                        <option value="divergent">Divergente</option>
                    </select>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="btn-neo btn-primary w-full py-4 text-xs font-black tracking-widest">REGISTRAR MALOTE</button>
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
            dom: '<"flex justify-between items-center mb-6"f l>rt<"flex justify-between items-center mt-6"i p>',
            columnDefs: [
                { orderable: false, targets: 4 }
            ]
        });
    });

    function openFinanceModal(id) {
        $(`#${id}`).removeClass('hidden').addClass('flex');
    }
    function closeFinanceModal(id) {
        $(`#${id}`).addClass('hidden').removeClass('flex');
    }

    function editBatch(id) {
        openFinanceModal('batchModal');
        $('#batchModalTitle').text('Editando Malote #' + id);
        $('#batchForm').attr('action', `/admin/finance/batches/${id}`);
        $('#batchFormMethod').val('PUT');
        $('button[type="submit"]').text('SALVAR ALTERAÇÕES');
    }

    function viewBatch(id) {
        editBatch(id);
        $('#batchModalTitle').text('Conferência do Malote #' + id);
        $('#batchForm input, #batchForm select').prop('disabled', true);
        $('button[type="submit"]').addClass('hidden');
    }

    function deleteBatch(id) {
        if(confirm('Deseja realmente excluir este registro de malote?')) {
            alert('Excluindo malote #' + id);
        }
    }

    function closeFinanceModal(id) {
        $(`#${id}`).addClass('hidden').removeClass('flex');
        $('#batchForm input, #batchForm select').prop('disabled', false);
        $('button[type="submit"]').removeClass('hidden').text('REGISTRAR MALOTE');
    }
</script>
@endpush
