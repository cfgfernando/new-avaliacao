@extends('layouts.app')

@section('title', 'Ativos Imobilizados')

@section('content')
<div class="space-y-8 animate-reveal-up">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-black text-primary-dark tracking-tight">PATRIMÔNIO</h2>
            <p class="text-primary-light font-medium mt-1">Gestão de ativos imobilizados e depreciação.</p>
        </div>
        <button onclick="openFinanceModal('assetModal')" class="btn-neo btn-primary">
            <i class="fas fa-plus"></i>
            <span>Cadastrar Novo Ativo</span>
        </button>
    </div>

    <!-- Tabela de Ativos -->
    <div class="card-neo overflow-hidden">
        <div class="p-4">
            <table id="assetsTable" class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th>Item / Descrição</th>
                    <th>Aquisição</th>
                    <th class="text-right">V. Compra</th>
                    <th class="text-right">V. Atual</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($assets as $asset)
                <tr class="group" onclick="editAsset({{ $asset->id }})">
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center text-primary-dark shadow-sm border border-white group-hover:bg-accent group-hover:text-white transition-all">
                                <i class="fas fa-chair text-sm"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-primary-dark uppercase tracking-tight">{{ $asset->name }}</span>
                                <span class="text-[9px] text-slate-400 font-bold uppercase">{{ $asset->location ?? 'Geral' }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-xs font-bold text-slate-500">{{ \Carbon\Carbon::parse($asset->purchase_date)->format('d/m/Y') }}</span>
                    </td>
                    <td class="text-right">
                        <span class="text-xs font-money text-slate-400 tracking-tighter">R$ {{ number_format($asset->purchase_value, 2, ',', '.') }}</span>
                    </td>
                    <td class="text-right">
                        <span class="text-sm font-money text-primary-dark tracking-tighter">R$ {{ number_format($asset->current_value, 2, ',', '.') }}</span>
                    </td>
                    <td class="text-center">
                        <span class="px-2 py-0.5 {{ $asset->status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} rounded text-[9px] font-black uppercase">
                            {{ $asset->status === 'active' ? 'Operacional' : 'Baixado' }}
                        </span>
                    </td>
                    <td class="text-center" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="viewAsset({{ $asset->id }})" class="btn-action bg-slate-50 text-slate-500 hover:bg-slate-800 hover:text-white" title="Histórico">
                                <i class="fas fa-clock-rotate-left text-[10px]"></i>
                            </button>
                            <button onclick="editAsset({{ $asset->id }})" class="btn-action bg-slate-50 text-slate-500 hover:bg-accent hover:text-white" title="Editar">
                                <i class="fas fa-pencil-alt text-[10px]"></i>
                            </button>
                            <button onclick="deleteAsset({{ $asset->id }})" class="btn-action bg-slate-50 text-slate-500 hover:bg-rose-500 hover:text-white" title="Dar Baixa">
                                <i class="fas fa-arrow-down-long text-[10px]"></i>
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
<!-- Modal Ativo -->
<div id="assetModal" class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[100] flex items-center justify-center p-6">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-reveal-up">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 id="assetModalTitle" class="text-xl font-black text-primary-dark uppercase tracking-tight">Novo Patrimônio</h3>
            <button type="button" onclick="closeFinanceModal('assetModal')" class="w-10 h-10 rounded-xl flex items-center justify-center hover:bg-rose-50 text-slate-400 hover:text-rose-500 transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="assetForm" action="{{ route('admin.finance.fixed-assets.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <input type="hidden" name="_method" id="assetFormMethod" value="POST">
            
            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Nome do Ativo</label>
                <input type="text" name="name" required class="input-neo" placeholder="Ex: Ar Condicionado 18000 BTUs">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Data de Aquisição</label>
                    <input type="date" name="purchase_date" required class="input-neo" value="{{ date('Y-m-d') }}">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Valor de Compra (R$)</label>
                    <input type="text" name="purchase_value" required class="input-neo mask-money" placeholder="0,00">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Taxa Depreciação (% ano)</label>
                    <input type="number" step="0.01" name="depreciation_rate" required class="input-neo" placeholder="10.00">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-[0.2em] mb-2 px-1">Localização / Setor</label>
                    <input type="text" name="location" class="input-neo" placeholder="Ex: Auditório Principal">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="btn-neo btn-primary w-full py-4 text-xs font-black tracking-widest">SALVAR NO PATRIMÔNIO</button>
            </div>
        </form>
    </div>
</div>
@endpush

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#assetsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
            },
            dom: '<"flex justify-between items-center mb-6"f l>rt<"flex justify-between items-center mt-6"i p>',
            columnDefs: [
                { orderable: false, targets: 5 }
            ]
        });
    });

    function openFinanceModal(id) {
        $(`#${id}`).removeClass('hidden').addClass('flex');
    }
    function closeFinanceModal(id) {
        $(`#${id}`).addClass('hidden').removeClass('flex');
    }

    function editAsset(id) {
        openFinanceModal('assetModal');
        $('#assetModalTitle').text('Editando Patrimônio #' + id);
        $('#assetForm').attr('action', `/admin/finance/fixed-assets/${id}`);
        $('#assetFormMethod').val('PUT');
        $('button[type="submit"]').text('SALVAR ALTERAÇÕES');
    }

    function viewAsset(id) {
        editAsset(id);
        $('#assetModalTitle').text('Ficha Técnica do Ativo #' + id);
        $('#assetForm input, #assetForm textarea').prop('disabled', true);
        $('button[type="submit"]').addClass('hidden');
    }

    function deleteAsset(id) {
        if(confirm('Tem certeza que deseja dar baixa permanente neste ativo? Esta operação é irreversível para fins de inventário.')) {
            alert('Ativo #' + id + ' baixado do sistema.');
        }
    }

    function closeFinanceModal(id) {
        $(`#${id}`).addClass('hidden').removeClass('flex');
        $('#assetForm input, #assetForm textarea').prop('disabled', false);
        $('button[type="submit"]').removeClass('hidden').text('SALVAR NO PATRIMÔNIO');
    }
</script>
@endpush
