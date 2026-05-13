@extends('layouts.app')

@section('title', 'Ativos Imobilizados')

@section('content')
<div class="space-y-6 animate-reveal-up">
    <!-- Header Elite V8 -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Ativos Imobilizados</h1>
            <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Gestão de patrimônio e depreciação</p>
        </div>
        <button onclick="openFinanceModal('assetModal')" class="btn-neo btn-primary text-xs py-2.5">
            <i class="fas fa-plus"></i>
            <span>NOVO ATIVO</span>
        </button>
    </div>

    <!-- Tabela Elite V8 -->
    <div class="card-neo !p-0 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Inventário de Ativos</h3>
        </div>
        <div class="p-4">
            <table id="assetsTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 bg-slate-50/50 uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4">Item / Descrição</th>
                        <th class="px-6 py-4">Aquisição</th>
                        <th class="px-6 py-4 text-right">V. Compra</th>
                        <th class="px-6 py-4 text-right">V. Atual</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($assets as $asset)
                    <tr class="group hover:bg-slate-50/50 transition-all cursor-pointer" onclick="editAsset({{ $asset->id }})">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center shadow-sm group-hover:bg-accent group-hover:text-white transition-all">
                                    <i class="fas fa-chair text-[12px]"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-800 leading-tight group-hover:text-accent transition-colors">{{ $asset->name }}</span>
                                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">{{ $asset->location ?? 'Geral' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold text-primary-light">{{ \Carbon\Carbon::parse($asset->purchase_date)->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-xs font-money text-slate-400">R$ {{ number_format($asset->purchase_value, 2, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right bg-slate-50/30">
                            <span class="text-sm font-money text-slate-800">R$ {{ number_format($asset->current_value, 2, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 {{ $asset->status === 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }} border rounded-lg text-[9px] font-black uppercase tracking-widest">
                                {{ $asset->status === 'active' ? 'Operacional' : 'Baixado' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewAsset({{ $asset->id }})" class="p-2 hover:bg-white rounded-lg text-slate-400 hover:text-blue-500 transition-colors shadow-sm border border-transparent hover:border-slate-100" title="Histórico">
                                    <i class="fas fa-clock-rotate-left text-[10px]"></i>
                                </button>
                                <button onclick="editAsset({{ $asset->id }})" class="p-2 hover:bg-white rounded-lg text-slate-400 hover:text-accent transition-colors shadow-sm border border-transparent hover:border-slate-100" title="Editar">
                                    <i class="fas fa-pencil-alt text-[10px]"></i>
                                </button>
                                <button onclick="deleteAsset({{ $asset->id }})" class="p-2 hover:bg-white rounded-lg text-slate-400 hover:text-rose-500 transition-colors shadow-sm border border-transparent hover:border-slate-100" title="Baixar">
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
</div>

@push('modals')
<!-- Modal Elite V8 -->
<div id="assetModal" class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[999] items-center justify-center p-6">
    <div class="bg-white w-full max-w-lg animate-reveal-up overflow-hidden shadow-2xl border border-white/20 rounded-[2rem] flex flex-col">
        
        <!-- Header -->
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
            <div>
                <h3 id="assetModalTitle" class="text-xl font-black text-slate-800 uppercase tracking-tight">Novo Patrimônio</h3>
                <p class="text-[9px] text-primary-light font-black uppercase tracking-widest mt-1">Controle de Imobilizados</p>
            </div>
            <button type="button" onclick="closeFinanceModal('assetModal')" class="w-10 h-10 rounded-xl flex items-center justify-center bg-slate-100 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="assetForm" action="{{ route('admin.finance.fixed-assets.store') }}" method="POST" class="p-8 space-y-6 bg-white">
            @csrf
            <input type="hidden" name="_method" id="assetFormMethod" value="POST">
            
            <div>
                <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Nome do Ativo</label>
                <input type="text" name="name" required class="input-neo uppercase" placeholder="Ex: Ar Condicionado 18000 BTUs">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Data de Aquisição</label>
                    <input type="date" name="purchase_date" required class="input-neo" value="{{ date('Y-m-d') }}">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Valor de Compra (R$)</label>
                    <input type="text" name="purchase_value" required class="input-neo font-money mask-money" placeholder="0,00">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Taxa Depreciação (% ano)</label>
                    <input type="number" step="0.01" name="depreciation_rate" required class="input-neo" placeholder="10.00">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Localização / Setor</label>
                    <input type="text" name="location" class="input-neo uppercase" placeholder="Ex: Auditório Principal">
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex gap-4">
                <button type="button" onclick="closeFinanceModal('assetModal')" class="flex-1 px-6 py-3 border border-slate-100 rounded-xl text-[10px] font-black text-primary-light uppercase tracking-widest hover:bg-slate-50 transition-all">Cancelar</button>
                <button type="submit" class="flex-[2] btn-neo btn-primary text-[10px] font-black uppercase tracking-widest">
                    <i class="fas fa-check-circle mr-2"></i> Confirmar Patrimônio
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
        $('#assetsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
            },
            dom: '<"flex justify-between items-center mb-6"f l>rt<"flex justify-between items-center mt-6"i p>',
            columnDefs: [
                { orderable: false, targets: 5 }
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
        $('#assetForm')[0].reset();
        $('#assetForm input, #assetForm textarea').prop('disabled', false);
        $('button[type="submit"]').removeClass('hidden').html('<i class="fas fa-check-circle mr-2"></i> Confirmar Patrimônio');
        $('#assetFormMethod').val('POST');
        $('#assetModalTitle').text('Novo Patrimônio');
    }

    function editAsset(id) {
        openFinanceModal('assetModal');
        $('#assetModalTitle').text('Editando Patrimônio #' + id);
        $('#assetForm').attr('action', `/admin/finance/fixed-assets/${id}`);
        $('#assetFormMethod').val('PUT');
        $('button[type="submit"]').html('<i class="fas fa-save mr-2"></i> SALVAR ALTERAÇÕES');
    }

    function viewAsset(id) {
        editAsset(id);
        $('#assetModalTitle').text('Ficha Técnica do Ativo #' + id);
        $('#assetForm input, #assetForm textarea').prop('disabled', true);
        $('button[type="submit"]').addClass('hidden');
    }

    function deleteAsset(id) {
        if(confirm('Tem certeza que deseja dar baixa permanente neste ativo? Esta operação é irreversível para fins de inventário.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/finance/fixed-assets/${id}`;
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush

