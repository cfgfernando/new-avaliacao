@extends('layouts.app')

@section('title', 'Centros de Custo')

@section('content')
<div class="space-y-6 animate-reveal-up">
    <!-- Header Elite V8 -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Centros de Custo</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Estrutura de classificação e projetos</p>
        </div>
        <button onclick="openFinanceModal('costCenterModal')" class="btn-neo btn-primary text-xs py-2.5">
            <i class="fas fa-plus"></i>
            <span>NOVO CENTRO DE CUSTO</span>
        </button>
    </div>

    <!-- Tabela Elite V8 -->
    <div class="card-neo !p-0 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Gerenciamento de Unidades de Custo</h3>
        </div>
        <div class="p-6">
            <table id="costCentersTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 bg-slate-50/50 uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4 w-40">Código CC</th>
                        <th class="px-6 py-4">Nome do Centro de Custo</th>
                        <th class="px-6 py-4">Finalidade / Descrição</th>
                        <th class="px-6 py-4 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($costCenters as $center)
                    <tr class="group hover:bg-slate-50/50 transition-all cursor-pointer" onclick="editCostCenter({{ $center->id }}, '{{ $center->code }}', '{{ $center->name }}', '{{ $center->description }}')">
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold text-slate-800">{{ $center->code }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center shadow-sm group-hover:bg-accent group-hover:text-white transition-all">
                                    <i class="fas fa-tag text-[10px]"></i>
                                </div>
                                <span class="text-sm font-bold text-slate-800 leading-tight group-hover:text-accent transition-colors">
                                    {{ $center->name }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest">{{ $center->description ?? 'Nenhuma observação registrada' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2" onclick="event.stopPropagation()">
                                <button onclick="editCostCenter({{ $center->id }}, '{{ $center->code }}', '{{ $center->name }}', '{{ $center->description }}')" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-accent hover:text-white flex items-center justify-center transition-all shadow-sm group/btn" title="Editar">
                                    <i class="fas fa-pencil-alt text-[10px]"></i>
                                </button>
                                <button onclick="deleteCostCenter({{ $center->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all shadow-sm group/btn" title="Excluir">
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
<div id="costCenterModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[9999] flex items-center justify-center p-4 sm:p-6 overflow-y-auto custom-scrollbar">
    <div class="bg-white w-full max-w-xl m-auto animate-reveal-up overflow-hidden shadow-2xl border border-white/20 rounded-[2rem] flex flex-col">
        
        <!-- Header -->
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
            <div>
                <h3 id="ccModalTitle" class="text-xl font-black text-slate-800 uppercase tracking-tight">Novo Centro de Custo</h3>
                <p class="text-[9px] text-primary-light font-black uppercase tracking-widest mt-1">Configuração Orçamentária</p>
            </div>
            <button type="button" onclick="closeFinanceModal('costCenterModal')" class="w-10 h-10 rounded-xl flex items-center justify-center bg-slate-100 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="ccForm" action="{{ route('admin.finance.cost-centers.store') }}" method="POST" class="p-8 space-y-8 bg-white">
            @csrf
            <input type="hidden" name="_method" id="ccFormMethod" value="POST">
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-4">
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Código CC</label>
                    <input type="text" name="code" id="cc_code" required class="input-neo" placeholder="Ex: 01.001">
                </div>
                <div class="md:col-span-8">
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Nome do Centro de Custo</label>
                    <input type="text" name="name" id="cc_name" required class="input-neo uppercase" placeholder="Ex: Secretaria Executiva">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Descrição (Opcional)</label>
                <textarea name="description" id="cc_description" class="input-neo h-32" placeholder="Descreva a finalidade deste centro..."></textarea>
            </div>

            <div class="pt-6 border-t border-slate-100 flex gap-4">
                <button type="button" onclick="closeFinanceModal('costCenterModal')" class="flex-1 px-6 py-3 border border-slate-100 rounded-xl text-[10px] font-black text-primary-light uppercase tracking-widest hover:bg-slate-50 transition-all">Cancelar</button>
                <button type="submit" class="flex-[2] btn-neo btn-primary text-[10px] font-black uppercase tracking-widest">
                    <i class="fas fa-save mr-2"></i> Confirmar Cadastro
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
        $('#costCentersTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
            },
            dom: '<"flex justify-between items-center mb-8 px-2"f l>rt<"flex justify-between items-center mt-8 px-2"i p>',
            columnDefs: [
                { orderable: false, targets: 3 }
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
        $('#ccForm')[0].reset();
        $('#ccModalTitle').text('Novo Centro de Custo');
        $('#ccFormMethod').val('POST');
        $('button[type="submit"]').html('<i class="fas fa-save mr-2"></i> Confirmar Cadastro');
    }

    function editCostCenter(id, code, name, description) {
        openFinanceModal('costCenterModal');
        $('#ccModalTitle').text('Editar Centro: ' + code);
        $('#ccFormMethod').val('PUT');
        $('#ccForm').attr('action', '{{ url("admin/finance/cost-centers") }}/' + id);
        $('button[type="submit"]').html('<i class="fas fa-save mr-2"></i> Salvar Alterações');
        
        // Populate fields
        $('#cc_code').val(code);
        $('#cc_name').val(name);
        $('#cc_description').val(description);
    }

    function deleteCostCenter(id) {
        if(confirm('Tem certeza que deseja excluir este centro de custo?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ url("admin/finance/cost-centers") }}/' + id;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#costCentersTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
            },
            dom: '<"flex justify-between items-center mb-8 px-2"f l>rt<"flex justify-between items-center mt-8 px-2"i p>',
            columnDefs: [
                { orderable: false, targets: 3 }
            ]
        });
    });

    function openFinanceModal(id) {
        $(`#${id}`).removeClass('hidden').addClass('flex');
    }
    function closeFinanceModal(id) {
        $(`#${id}`).addClass('hidden').removeClass('flex');
    }

    function editCostCenter(id, code, name, description) {
        openFinanceModal('costCenterModal');
        $('#ccModalTitle').text('Editar Centro: ' + code);
        $('#ccFormMethod').val('PUT');
        $('#ccForm').attr('action', '{{ url("admin/finance/cost-centers") }}/' + id);
        
        // Populate fields
        $('#cc_code').val(code);
        $('#cc_name').val(name);
        $('#cc_description').val(description);
    }

    function deleteCostCenter(id) {
        if(confirm('Tem certeza que deseja excluir este centro de custo?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ url("admin/finance/cost-centers") }}/' + id;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
