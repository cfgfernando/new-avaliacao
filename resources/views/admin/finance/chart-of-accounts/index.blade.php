@extends('layouts.app')

@section('title', 'Plano de Contas')

@section('content')
<div class="space-y-8 animate-reveal-up">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-black text-primary-dark tracking-tight">PLANO DE CONTAS</h2>
            <p class="text-primary-light font-medium mt-1">Estrutura hierárquica para classificação contábil.</p>
        </div>
        <button onclick="openFinanceModal('coaModal')" class="btn-neo btn-primary">
            <i class="fas fa-plus"></i>
            <span>Nova Conta Contábil</span>
        </button>
    </div>

    <!-- Tabela de Plano de Contas -->
    <div class="card-neo overflow-hidden">
        <div class="p-4">
            <table id="coaTable" class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="w-32">Código</th>
                    <th>Nome da Conta</th>
                    <th>Tipo / Natureza</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($accounts as $account)
                <tr class="group {{ $account->parent_id ? 'bg-transparent' : 'bg-slate-50/50' }}" onclick="editCOA({{ $account->id }})">
                    <td>
                        <span class="text-xs font-black {{ $account->parent_id ? 'text-slate-500' : 'text-primary-dark' }} tracking-tighter">{{ $account->code }}</span>
                    </td>
                    <td>
                        <div class="flex items-center gap-2 {{ $account->parent_id ? 'pl-6' : '' }}">
                            @if(!$account->parent_id)
                                <i class="fas fa-folder text-accent text-xs"></i>
                            @else
                                <i class="fas fa-chevron-right text-slate-300 text-[10px]"></i>
                            @endif
                            <span class="text-sm font-black text-primary-dark uppercase tracking-tight group-hover:text-accent transition-colors">
                                {{ $account->name }}
                            </span>
                        </div>
                    </td>
                    <td>
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[9px] font-black uppercase tracking-tighter">
                            {{ $account->type === 'revenue' ? 'Receita' : ($account->type === 'expense' ? 'Despesa' : 'Patrimonial') }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="w-2 h-2 rounded-full inline-block {{ $account->is_active ? 'bg-emerald-500' : 'bg-rose-500' }} shadow-lg"></span>
                    </td>
                    <td class="text-center" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="editCOA({{ $account->id }})" class="btn-action bg-slate-50 text-slate-500 hover:bg-accent hover:text-white" title="Editar">
                                <i class="fas fa-pencil-alt text-[10px]"></i>
                            </button>
                            <button onclick="deleteCOA({{ $account->id }})" class="btn-action bg-slate-50 text-slate-500 hover:bg-rose-500 hover:text-white" title="Excluir">
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
<!-- Modal Plano de Contas -->
<div id="coaModal" class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[100] flex items-center justify-center p-6">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-reveal-up">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 id="coaModalTitle" class="text-xl font-black text-primary-dark uppercase tracking-tight">Nova Conta Contábil</h3>
            <button type="button" onclick="closeFinanceModal('coaModal')" class="w-10 h-10 rounded-xl flex items-center justify-center hover:bg-rose-50 text-slate-400 hover:text-rose-500 transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="coaForm" action="{{ route('admin.finance.chart-of-accounts.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <input type="hidden" name="_method" id="coaFormMethod" value="POST">
            
            <div class="grid grid-cols-3 gap-6">
                <div class="col-span-1">
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Código</label>
                    <input type="text" name="code" required class="input-neo" placeholder="1.1.01">
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Nome da Conta</label>
                    <input type="text" name="name" required class="input-neo" placeholder="Ex: Doações de Membros">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Tipo / Natureza</label>
                <select name="type" required class="input-neo">
                    <option value="revenue">Receita (Entrada)</option>
                    <option value="expense">Despesa (Saída)</option>
                    <option value="asset">Ativo (Patrimonial)</option>
                    <option value="liability">Passivo (Obrigação)</option>
                    <option value="equity">Patrimônio Líquido</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Conta Pai (Superior)</label>
                <select name="parent_id" class="input-neo">
                    <option value="">-- Nível Principal (Raiz) --</option>
                    @foreach($accounts->whereNull('parent_id') as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->code }} - {{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-4">
                <button type="submit" class="btn-neo btn-primary w-full py-4 text-xs font-black tracking-widest">SALVAR NO PLANO DE CONTAS</button>
            </div>
        </form>
    </div>
</div>
@endpush

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#coaTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
            },
            pageLength: 50,
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

    function editCOA(id) {
        openFinanceModal('coaModal');
        $('#coaModalTitle').text('Editando Conta Contábil #' + id);
        $('#coaForm').attr('action', `/admin/finance/chart-of-accounts/${id}`);
        $('#coaFormMethod').val('PUT');
        $('button[type="submit"]').text('SALVAR ALTERAÇÕES');
    }

    function deleteCOA(id) {
        if(confirm('Atenção: A exclusão de contas contábeis pode gerar inconsistências nos lançamentos existentes. Deseja realmente excluir a conta #' + id + '?')) {
            alert('Excluindo conta contábil #' + id);
        }
    }

    function closeFinanceModal(id) {
        $(`#${id}`).addClass('hidden').removeClass('flex');
        $('#coaForm')[0].reset();
        $('#coaModalTitle').text('Nova Conta Contábil');
        $('#coaFormMethod').val('POST');
        $('button[type="submit"]').text('SALVAR NO PLANO DE CONTAS');
    }
</script>
@endpush
