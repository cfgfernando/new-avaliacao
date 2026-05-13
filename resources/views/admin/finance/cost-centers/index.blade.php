@extends('layouts.app')

@section('title', 'Centros de Custo')

@section('content')
<div class="space-y-8 animate-reveal-up">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-black text-primary-dark tracking-tight">CENTROS DE CUSTO</h2>
            <p class="text-primary-light font-medium mt-1">Classificação por departamentos ou projetos.</p>
        </div>
        <button onclick="openFinanceModal('costCenterModal')" class="btn-neo btn-primary">
            <i class="fas fa-plus"></i>
            <span>Novo Centro de Custo</span>
        </button>
    </div>

    <!-- Tabela de Centros de Custo -->
    <div class="card-neo overflow-hidden">
        <div class="p-4">
            <table id="costCentersTable" class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="w-32">Código</th>
                    <th>Nome do Centro</th>
                    <th>Descrição</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($centers as $center)
                <tr class="group" onclick="editCostCenter({{ $center->id }})">
                    <td>
                        <span class="text-xs font-black text-primary-dark tracking-tighter">{{ $center->code }}</span>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-tag text-accent text-xs"></i>
                            <span class="text-sm font-black text-primary-dark uppercase tracking-tight group-hover:text-accent transition-colors">
                                {{ $center->name }}
                            </span>
                        </div>
                    </td>
                    <td>
                        <span class="text-xs text-slate-500 font-medium">{{ $center->description ?? 'Sem descrição' }}</span>
                    </td>
                    <td class="text-center" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="editCostCenter({{ $center->id }})" class="btn-action bg-slate-50 text-slate-500 hover:bg-accent hover:text-white" title="Editar">
                                <i class="fas fa-pencil-alt text-[10px]"></i>
                            </button>
                            <button onclick="deleteCostCenter({{ $center->id }})" class="btn-action bg-slate-50 text-slate-500 hover:bg-rose-500 hover:text-white" title="Excluir">
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
<!-- Modal Centro de Custo -->
<div id="costCenterModal" class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[100] flex items-center justify-center p-6">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-reveal-up">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 id="ccModalTitle" class="text-xl font-black text-primary-dark uppercase tracking-tight">Novo Centro de Custo</h3>
            <button type="button" onclick="closeFinanceModal('costCenterModal')" class="w-10 h-10 rounded-xl flex items-center justify-center hover:bg-rose-50 text-slate-400 hover:text-rose-500 transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="ccForm" action="{{ route('admin.finance.cost-centers.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <input type="hidden" name="_method" id="ccFormMethod" value="POST">
            
            <div class="grid grid-cols-3 gap-6">
                <div class="col-span-1">
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Código</label>
                    <input type="text" name="code" required class="input-neo" placeholder="Ex: 01.001">
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Nome do Centro</label>
                    <input type="text" name="name" required class="input-neo" placeholder="Ex: Secretaria">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Descrição (Opcional)</label>
                <textarea name="description" class="input-neo h-24" placeholder="Descreva a finalidade deste centro de custo..."></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="btn-neo btn-primary w-full py-4 text-xs font-black tracking-widest">SALVAR CENTRO DE CUSTO</button>
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
            dom: '<"flex justify-between items-center mb-6"f l>rt<"flex justify-between items-center mt-6"i p>',
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

    function editCostCenter(id) {
        openFinanceModal('costCenterModal');
        $('#ccModalTitle').text('Editar Centro #' + id);
        $('#ccFormMethod').val('PUT');
    }

    function deleteCostCenter(id) {
        if(confirm('Tem certeza que deseja excluir este centro de custo?')) {
            alert('Excluindo centro #' + id);
        }
    }
</script>
@endpush
