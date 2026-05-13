@extends('layouts.app')

@section('title', 'Plano de Contas')

@section('content')
<div class="space-y-6 animate-reveal-up">
    <!-- Header Elite V8 -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Plano de Contas</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Estrutura hierárquica para classificação contábil</p>
        </div>
        <button onclick="openFinanceModal('coaModal')" class="btn-neo btn-primary text-xs py-2.5">
            <i class="fas fa-plus"></i>
            <span>NOVA CONTA CONTÁBIL</span>
        </button>
    </div>

    <!-- Tabela Elite V8 -->
    <div class="card-neo !p-0 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Configuração de Estrutura Contábil</h3>
        </div>
        <div class="p-6">
            <table id="coaTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 bg-slate-50/50 uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4 w-40">Código</th>
                        <th class="px-6 py-4">Nome da Conta / Classificação</th>
                        <th class="px-6 py-4">Tipo / Natureza</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($accounts as $account)
                    <tr class="group {{ $account->parent_id ? 'bg-transparent' : 'bg-slate-50/20' }} hover:bg-slate-50 transition-all cursor-pointer" onclick="editCOA({{ $account->id }})">
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-bold {{ $account->parent_id ? 'text-slate-400' : 'text-slate-800' }}">{{ $account->code }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3 {{ $account->parent_id ? 'pl-8' : '' }}">
                                @if(!$account->parent_id)
                                    <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center text-xs shadow-sm group-hover:bg-accent group-hover:text-white transition-all">
                                        <i class="fas fa-folder"></i>
                                    </div>
                                @else
                                    <i class="fas fa-chevron-right text-slate-200 text-[8px]"></i>
                                @endif
                                <span class="text-sm font-bold text-slate-800 leading-tight group-hover:text-accent transition-colors">
                                    {{ $account->name }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2.5 py-1 bg-slate-50 border border-slate-100 rounded-lg text-[9px] font-black text-slate-500 uppercase tracking-widest">
                                {{ $account->type === 'revenue' ? 'Receita' : ($account->type === 'expense' ? 'Despesa' : 'Patrimonial') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <div class="w-2 h-2 rounded-full {{ $account->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ $account->is_active ? 'Ativo' : 'Inativo' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="editCOA({{ $account->id }})" class="p-2 hover:bg-white rounded-lg text-slate-400 hover:text-accent transition-colors shadow-sm border border-transparent hover:border-slate-100" title="Editar">
                                    <i class="fas fa-pencil-alt text-[10px]"></i>
                                </button>
                                <button onclick="deleteCOA({{ $account->id }})" class="p-2 hover:bg-white rounded-lg text-slate-400 hover:text-rose-500 transition-colors shadow-sm border border-transparent hover:border-slate-100" title="Excluir">
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
<div id="coaModal" class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[999] items-center justify-center p-6">
    <div class="bg-white w-full max-w-lg animate-reveal-up overflow-hidden shadow-2xl border border-white/20 rounded-[2rem] flex flex-col">
        
        <!-- Header -->
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
            <div>
                <h3 id="coaModalTitle" class="text-xl font-black text-slate-800 uppercase tracking-tight">Nova Conta Contábil</h3>
                <p class="text-[9px] text-primary-light font-black uppercase tracking-widest mt-1">Estruturação Hierárquica</p>
            </div>
            <button type="button" onclick="closeFinanceModal('coaModal')" class="w-10 h-10 rounded-xl flex items-center justify-center bg-slate-100 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="coaForm" action="{{ route('admin.finance.chart-of-accounts.store') }}" method="POST" class="p-8 space-y-8 bg-white">
            @csrf
            <input type="hidden" name="_method" id="coaFormMethod" value="POST">
            
            <div class="grid grid-cols-3 gap-6">
                <div class="col-span-1">
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Código</label>
                    <input type="text" name="code" required class="input-neo" placeholder="1.1.01">
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Nome da Conta Contábil</label>
                    <input type="text" name="name" required class="input-neo uppercase" placeholder="Ex: Doações Mensais">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Natureza do Lançamento</label>
                <select name="type" required class="input-neo uppercase">
                    <option value="revenue">Receita (Entrada Operacional)</option>
                    <option value="expense">Despesa (Saída Operacional)</option>
                    <option value="asset">Ativo (Patrimônio Disponível)</option>
                    <option value="liability">Passivo (Obrigações/Dívidas)</option>
                    <option value="equity">Patrimônio Líquido</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Conta Superior (Pai)</label>
                <select name="parent_id" class="input-neo uppercase">
                    <option value="">-- Nível Principal (Raiz) --</option>
                    @foreach($accounts->whereNull('parent_id') as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->code }} - {{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-6 border-t border-slate-100 flex gap-4">
                <button type="button" onclick="closeFinanceModal('coaModal')" class="flex-1 px-6 py-3 border border-slate-100 rounded-xl text-[10px] font-black text-primary-light uppercase tracking-widest hover:bg-slate-50 transition-all">Cancelar</button>
                <button type="submit" class="flex-[2] btn-neo btn-primary text-[10px] font-black uppercase tracking-widest">
                    <i class="fas fa-check-circle mr-2"></i> Confirmar Estrutura
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
        $('#coaTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
            },
            pageLength: 50,
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
        $('#coaForm')[0].reset();
        $('#coaModalTitle').text('Nova Conta Contábil');
        $('#coaFormMethod').val('POST');
        $('button[type="submit"]').html('<i class="fas fa-check-circle mr-2"></i> Confirmar Estrutura');
    }

    function editCOA(id) {
        openFinanceModal('coaModal');
        $('#coaModalTitle').text('Editar Conta Contábil: #' + id);
        $('#coaForm').attr('action', `/admin/finance/chart-of-accounts/${id}`);
        $('#coaFormMethod').val('PUT');
        $('button[type="submit"]').html('<i class="fas fa-save mr-2"></i> Salvar Alterações');
    }

    function deleteCOA(id) {
        if(confirm('A exclusão de contas contábeis pode gerar inconsistências históricas. Deseja realmente excluir?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/finance/chart-of-accounts/${id}`;
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
