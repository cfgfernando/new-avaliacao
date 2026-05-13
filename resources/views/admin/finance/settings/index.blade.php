@extends('layouts.app')

@section('title', 'Painel Central de Controle')

@section('content')
<div class="space-y-6 animate-reveal-up pb-20">
    <!-- Header Elite V8 -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-xl font-black text-slate-800 uppercase tracking-tight">Painel Central de Controle</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Gestão técnica de unidades, fornecedores e parâmetros financeiros</p>
        </div>
        <div class="flex items-center gap-4 px-5 py-2.5 bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="text-right">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block leading-none mb-1">Status do Sistema</span>
                <span class="text-[10px] font-black text-emerald-600 uppercase">Ambiente Seguro Elite V8</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-primary">
                <i class="fas fa-server text-sm"></i>
            </div>
        </div>
    </div>

    <!-- Dynamic Tab Bar (Elite V8) -->
    <div class="card-neo !p-2 bg-white/80 backdrop-blur-xl border border-slate-100 shadow-lg sticky top-24 z-40 rounded-3xl">
        <div class="flex items-center space-x-2 overflow-x-auto scrollbar-hide px-2 py-1" id="settings-tabs">
            @php
                $tabs = [
                    ['id' => 'unidades', 'label' => 'Unidades', 'icon' => 'fa-church'],
                    ['id' => 'fornecedores', 'label' => 'Fornecedores', 'icon' => 'fa-truck-field'],
                    ['id' => 'plano-contas', 'label' => 'Plano de Contas', 'icon' => 'fa-sitemap'],
                    ['id' => 'centros-custo', 'label' => 'Centros de Custo', 'icon' => 'fa-tags'],
                    ['id' => 'bancos', 'label' => 'Bancos', 'icon' => 'fa-university'],
                    ['id' => 'contas', 'label' => 'Contas Financeiras', 'icon' => 'fa-credit-card'],
                    ['id' => 'bloqueios', 'label' => 'Bloqueios Contábeis', 'icon' => 'fa-lock-clock'],
                ];
            @endphp

            @foreach($tabs as $tab)
                <button onclick="switchTab('{{ $tab['id'] }}')" 
                        data-tab-btn="{{ $tab['id'] }}"
                        class="tab-btn flex items-center gap-3 px-6 py-4 rounded-2xl transition-all duration-300 whitespace-nowrap min-w-fit">
                    <i class="fas {{ $tab['icon'] }} text-xs"></i>
                    <span class="text-[11px] font-black tracking-widest uppercase">{{ $tab['label'] }}</span>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Tab Contents -->
    <div id="tab-contents" class="pb-20">
        
        <!-- UNIDADES (Table with Filters) -->
        <div data-tab-content="unidades" class="tab-pane hidden space-y-6">
            <div class="card-neo">
                <!-- Advanced Filters -->
                <div class="flex flex-col md:flex-row gap-4 justify-between items-end mb-8">
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
                        <div class="relative">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block italic">Pesquisa Rápida</label>
                            <input type="text" class="input-neo !py-2.5 pl-10 text-xs" placeholder="Nome da unidade...">
                            <i class="fas fa-search absolute left-4 top-9 text-slate-300 text-xs"></i>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block italic">Tipo de Unidade</label>
                            <select class="input-neo !py-2.5 text-xs font-bold uppercase italic">
                                <option>Todos os Tipos</option>
                                <option>Sede</option>
                                <option>Campus</option>
                                <option>Nucleo</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block italic">Status</label>
                            <select class="input-neo !py-2.5 text-xs font-bold uppercase italic">
                                <option>Ativos</option>
                                <option>Inativos</option>
                            </select>
                        </div>
                    </div>
                    <button onclick="openModal('modal-units')" class="btn-neo btn-primary uppercase tracking-widest text-[10px] !py-3 !px-6">
                        <i class="fas fa-plus mr-2"></i> Nova Unidade
                    </button>
                </div>

                <!-- Table -->
                <div class="overflow-hidden rounded-2xl border border-slate-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Unidade / Campus</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">CNPJ / Identificador</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Responsável</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($units ?? [] as $unit)
                            <tr class="hover:bg-white transition-all group cursor-pointer even:bg-slate-50" onclick="editEntity('units', {{ $unit->id }})">
                                <td class="px-6 py-4 border-r border-slate-50/50">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100 group-hover:bg-accent group-hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-church text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="text-xs font-black text-slate-800 uppercase block tracking-tight">{{ $unit->name }}</span>
                                            <span class="text-[9px] text-slate-600 font-bold italic">Sede Administrativa</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-800 font-mono italic border-r border-slate-50/50">{{ $unit->tax_id ?? '00.000.000/0001-00' }}</td>
                                <td class="px-6 py-4 border-r border-slate-50/50">
                                    <span class="text-[10px] font-black text-slate-800 uppercase">Admin Principal</span>
                                </td>
                                <td class="px-6 py-4 border-r border-slate-50/50">
                                    <span class="badge-success !bg-emerald-500 !text-white border-none text-[8px] px-2 py-0.5">Ativo</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2" onclick="event.stopPropagation()">
                                        <button onclick="editEntity('units', {{ $unit->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-800 hover:bg-accent hover:text-white flex items-center justify-center transition-all shadow-sm">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </button>
                                        <button class="w-8 h-8 rounded-lg bg-slate-50/50 text-slate-800 hover:bg-rose-600 hover:text-white transition-all">
                                            <i class="fas fa-trash text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="py-10 text-center text-slate-400 uppercase text-[10px] font-black italic bg-slate-50">Nenhuma unidade configurada</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $units->appends(['tab' => 'unidades'])->links() }}
                </div>
            </div>
        </div>

        <!-- CONTAS FINANCEIRAS (Table with Filters) -->
        <div data-tab-content="contas" class="tab-pane hidden space-y-6">
            <div class="card-neo">
                 <!-- Advanced Filters -->
                 <div class="flex flex-col md:flex-row gap-4 justify-between items-end mb-8">
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
                        <div class="relative">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block italic">Pesquisa</label>
                            <input type="text" class="input-neo !py-2.5 pl-10 text-xs" placeholder="Nome da conta...">
                            <i class="fas fa-search absolute left-4 top-9 text-slate-300 text-xs"></i>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block italic">Banco</label>
                            <select class="input-neo !py-2.5 text-xs font-bold uppercase italic">
                                <option>Todos os Bancos</option>
                                <option>Itaú</option>
                                <option>Santander</option>
                                <option>Bradesco</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block italic">Unidade</label>
                            <select class="input-neo !py-2.5 text-xs font-bold uppercase italic">
                                <option>Todas as Unidades</option>
                            </select>
                        </div>
                    </div>
                    <button onclick="openModal('modal-accounts')" class="btn-neo btn-primary uppercase tracking-widest text-[10px] !py-3 !px-6">
                        <i class="fas fa-plus mr-2"></i> Nova Conta/Cofre
                    </button>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-100 shadow-md">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">Instituição / Conta</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">Agência / Conta</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">Saldo Atual</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest text-right italic">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($financialAccounts ?? [] as $account)
                            <tr class="hover:bg-white transition-all group cursor-pointer even:bg-slate-50" onclick="editEntity('accounts', {{ $account->id }})">
                                <td class="px-6 py-4 border-r border-slate-50/50">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100 group-hover:bg-accent group-hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-university text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="text-xs font-black text-slate-800 uppercase block tracking-tight">{{ $account->name }}</span>
                                            <span class="text-[8px] text-slate-600 font-bold uppercase tracking-widest italic">Conta Corrente</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-800 font-mono border-r border-slate-50/50 italic">{{ $account->agency ?? '0001' }} / {{ $account->account_number ?? '12345-6' }}</td>
                                <td class="px-6 py-4 border-r border-slate-50/50">
                                    <span class="text-xs font-black text-emerald-900 font-money italic">R$ {{ number_format($account->balance ?? 0, 2, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 border-r border-slate-50/50">
                                    <span class="badge-success !bg-emerald-500 !text-white border-none text-[8px] px-2 py-0.5">Ativa</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                     <div class="flex justify-end gap-2" onclick="event.stopPropagation()">
                                        <button onclick="editEntity('accounts', {{ $account->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-800 hover:bg-accent hover:text-white flex items-center justify-center transition-all shadow-sm">
                                            <i class="fas fa-gear text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="py-10 text-center text-slate-400 uppercase text-[10px] font-black italic bg-slate-50">Nenhuma conta encontrada</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $financialAccounts->appends(['tab' => 'contas'])->links() }}
                </div>
            </div>
        </div>

        <!-- PLANO DE CONTAS (Existing Table with New Filters) -->
        <div data-tab-content="plano-contas" class="tab-pane hidden space-y-6">
            <div class="card-neo">
                <!-- Advanced Filters -->
                <div class="flex flex-col md:flex-row gap-4 justify-between items-end mb-8">
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4 w-full">
                        <div class="md:col-span-2 relative">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block italic">Pesquisa por Código ou Nome</label>
                            <input type="text" class="input-neo !py-2.5 pl-10 text-xs" placeholder="Ex: 1.01.01 ou Dízimos...">
                            <i class="fas fa-search absolute left-4 top-9 text-slate-300 text-xs"></i>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block italic">Tipo</label>
                            <select class="input-neo !py-2.5 text-xs font-bold uppercase italic">
                                <option>Todos</option>
                                <option>Receita</option>
                                <option>Despesa</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block italic">Nível</label>
                            <select class="input-neo !py-2.5 text-xs font-bold uppercase italic">
                                <option>Todos</option>
                                <option>Sintética</option>
                                <option>Analítica</option>
                            </select>
                        </div>
                    </div>
                    <button onclick="openModal('modal-chart-of-accounts')" class="btn-neo btn-primary uppercase tracking-widest text-[10px] !py-3 !px-6">
                        <i class="fas fa-plus mr-2"></i> Nova Conta
                    </button>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-100 shadow-md">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">Código</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">Nome da Conta</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">Tipo</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest text-right italic">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($chartOfAccounts ?? [] as $coa)
                            <tr class="hover:bg-white transition-colors cursor-pointer even:bg-slate-50" onclick="editEntity('chart-of-accounts', {{ $coa->id }})">
                                <td class="px-6 py-4 text-xs font-black text-slate-800 border-r border-slate-50/50 italic">{{ $coa->code }}</td>
                                <td class="px-6 py-4 font-black text-slate-800 uppercase text-xs tracking-tight border-r border-slate-50/50">{{ $coa->name }}</td>
                                <td class="px-6 py-4 border-r border-slate-50/50">
                                    <span class="px-2 py-1 rounded text-[8px] font-black uppercase tracking-widest {{ $coa->type === 'revenue' ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                                        {{ $coa->type === 'revenue' ? 'Receita' : 'Despesa' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 border-r border-slate-50/50">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full {{ $coa->is_active ? 'bg-emerald-600' : 'bg-slate-400' }} border border-black/20"></div>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-800 italic">
                                            {{ $coa->is_active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2" onclick="event.stopPropagation()">
                                        <button onclick="editEntity('chart-of-accounts', {{ $coa->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-800 hover:bg-accent hover:text-white flex items-center justify-center transition-all shadow-sm">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $chartOfAccounts->appends(['tab' => 'plano-contas'])->links() }}
                </div>
            </div>
        </div>

        <!-- BLOQUEIOS (Unified Layout) -->
        <div data-tab-content="bloqueios" class="tab-pane hidden space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="card-neo">
                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-[0.2em] italic mb-6 border-b border-slate-50 pb-4">Segurança Contábil</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Data de Corte Retroativo</label>
                                <input type="date" value="{{ date('Y-m-d', strtotime('-1 month')) }}" class="input-neo font-black !py-3">
                                <p class="text-[8px] text-slate-400 italic">Trava de segurança contra edições em períodos auditados.</p>
                            </div>
                            <div class="flex flex-col justify-end pb-4">
                                <button class="btn-neo btn-primary w-full uppercase tracking-[0.2em] text-[10px] !py-4 shadow-xl">
                                    <i class="fas fa-lock"></i> Aplicar Trava Global
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-neo">
                        <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-widest italic mb-6">Histórico de Fechamentos</h3>
                        <div class="overflow-hidden rounded-2xl border border-slate-100 shadow-md">
                            <table class="w-full text-left border-collapse">
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($closures ?? [] as $closure)
                                    <tr class="hover:bg-white transition-all group even:bg-slate-50">
                                        <td class="px-6 py-4 border-r border-slate-50/50">
                                            <div class="flex items-center gap-4">
                                                <div class="w-8 h-8 rounded-lg bg-accent text-white flex items-center justify-center">
                                                    <i class="fas fa-calendar-check text-xs"></i>
                                                </div>
                                                <span class="text-[11px] font-black text-slate-800 uppercase tracking-tight">Fechamento {{ \Carbon\Carbon::createFromDate($closure->year, $closure->month, 1)->translatedFormat('F Y') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-[9px] text-slate-600 font-bold uppercase italic border-r border-slate-50/50">Auditado em {{ $closure->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="badge-success !bg-emerald-500 !text-white border-none text-[8px] px-2 py-0.5">Encerrado</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td class="py-10 text-center text-[10px] font-black uppercase tracking-widest italic text-slate-400 bg-slate-50">Sem histórico</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="card-neo shadow-sm border border-slate-100">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-accent border border-slate-100 shadow-sm">
                                <i class="fas fa-shield-check text-xl"></i>
                            </div>
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest leading-tight">Protocolo de <br>Auditoria</h3>
                        </div>
                        <p class="text-[11px] font-bold text-slate-500 leading-relaxed mb-8 uppercase tracking-wide">
                            O bloqueio impede edições retroativas em todas as unidades, garantindo a integridade dos relatórios contábeis para o conselho fiscal.
                        </p>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-widest text-slate-600">
                                <span>Última Trava</span>
                                <span class="text-accent">{{ count($closures) > 0 ? \Carbon\Carbon::createFromDate($closures[0]->year, $closures[0]->month, 1)->translatedFormat('M Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FORNECEDORES (Real Table) -->
        <div data-tab-content="fornecedores" class="tab-pane hidden space-y-6">
            <div class="card-neo">
                <div class="flex justify-between items-center mb-8">
                    <div class="relative w-1/3">
                        <input type="text" class="input-neo !py-2.5 pl-10 text-xs" placeholder="Buscar fornecedor...">
                        <i class="fas fa-search absolute left-4 top-3 text-slate-300 text-xs"></i>
                    </div>
                    <button onclick="openModal('modal-supplier')" class="btn-neo btn-primary uppercase tracking-widest text-[10px] !py-3 !px-8">
                        <i class="fas fa-plus mr-2"></i> Novo Fornecedor
                    </button>
                </div>
                <div class="overflow-hidden rounded-2xl border border-slate-100 shadow-md">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">Fornecedor</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">CNPJ/CPF</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">Contato</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest text-right italic">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($suppliers as $supplier)
                            <tr class="hover:bg-white transition-all group cursor-pointer even:bg-slate-50" onclick="editEntity('suppliers', {{ $supplier->id }})">
                                <td class="px-6 py-4 border-r border-slate-50/50">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100 group-hover:bg-accent group-hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-truck-field text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="text-xs font-black text-slate-800 uppercase block tracking-tight">{{ $supplier->name }}</span>
                                            @if($supplier->nickname)
                                                <span class="text-[9px] text-accent font-black uppercase block mt-0.5 italic">{{ $supplier->nickname }}</span>
                                            @endif
                                            <span class="text-[9px] text-slate-600 font-bold italic">
                                                {{ $supplier->email ?: ($supplier->contacts['finance']['email'] ?? ($supplier->contacts['sales']['email'] ?? 'Sem e-mail')) }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-800 font-mono border-r border-slate-50/50 italic">{{ $supplier->document ?? '---' }}</td>
                                <td class="px-6 py-4 border-r border-slate-50/50">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-slate-800 uppercase">
                                            {{ $supplier->phone ?: ($supplier->contacts['finance']['phone'] ?? ($supplier->contacts['sales']['phone'] ?? '---')) }}
                                        </span>
                                        @if(!empty($supplier->contacts['finance']['name']))
                                            <span class="text-[8px] text-slate-600 font-bold italic uppercase">{{ $supplier->contacts['finance']['name'] }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 border-r border-slate-50/50">
                                    <span class="{{ $supplier->status === 'active' ? 'badge-success !bg-emerald-500 !text-white' : 'badge-danger !bg-rose-500 !text-white' }} border-none text-[8px] px-2 py-0.5 uppercase">{{ $supplier->status === 'active' ? 'Ativo' : 'Inativo' }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2" onclick="event.stopPropagation()">
                                        <button onclick="editEntity('suppliers', {{ $supplier->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-800 hover:bg-accent hover:text-white flex items-center justify-center transition-all shadow-sm">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </button>
                                        <button onclick="deleteEntity('suppliers', {{ $supplier->id }})" class="w-8 h-8 rounded-lg bg-slate-50/50 text-slate-800 hover:bg-rose-600 hover:text-white transition-all">
                                            <i class="fas fa-trash text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="py-10 text-center text-[10px] font-black uppercase tracking-widest italic text-slate-400 bg-slate-50">Nenhum fornecedor cadastrado</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $suppliers->appends(['tab' => 'fornecedores'])->links() }}
                </div>
            </div>
        </div>

        <!-- CENTROS DE CUSTO (Real Table) -->
        <div data-tab-content="centros-custo" class="tab-pane hidden space-y-6">
            <div class="card-neo">
                <div class="flex justify-between items-center mb-8">
                    <div class="relative w-1/3">
                        <input type="text" class="input-neo !py-2.5 pl-10 text-xs" placeholder="Buscar centro de custo...">
                        <i class="fas fa-search absolute left-4 top-3 text-slate-300 text-xs"></i>
                    </div>
                    <button onclick="openModal('modal-cost-center')" class="btn-neo btn-primary uppercase tracking-widest text-[10px] !py-3 !px-8">
                        <i class="fas fa-plus mr-2"></i> Novo Centro de Custo
                    </button>
                </div>
                <div class="overflow-hidden rounded-2xl border border-slate-100 shadow-md">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">Código</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">Nome</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest text-right italic">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($costCenters as $cc)
                            <tr class="hover:bg-white transition-all group cursor-pointer even:bg-slate-50" onclick="editEntity('cost-centers', {{ $cc->id }})">
                                <td class="px-6 py-4 text-xs font-black text-slate-800 border-r border-slate-50/50 italic">{{ $cc->code }}</td>
                                <td class="px-6 py-4 font-black text-slate-800 uppercase text-xs tracking-tight border-r border-slate-50/50">{{ $cc->name }}</td>
                                <td class="px-6 py-4 border-r border-slate-50/50">
                                    <span class="{{ $cc->is_active ? 'badge-success !bg-emerald-500 !text-white' : 'badge-danger !bg-rose-500 !text-white' }} border-none text-[8px] px-2 py-0.5 uppercase">{{ $cc->is_active ? 'Ativo' : 'Inativo' }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2" onclick="event.stopPropagation()">
                                        <button onclick="editEntity('cost-centers', {{ $cc->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-800 hover:bg-accent hover:text-white flex items-center justify-center transition-all shadow-sm">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </button>
                                        <button onclick="deleteEntity('cost-centers', {{ $cc->id }})" class="w-8 h-8 rounded-lg bg-slate-50/50 text-slate-800 hover:bg-rose-600 hover:text-white transition-all">
                                            <i class="fas fa-trash text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="py-10 text-center text-[10px] font-black uppercase tracking-widest italic text-slate-400 bg-slate-50">Nenhum centro de custo cadastrado</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $costCenters->appends(['tab' => 'centros-custo'])->links() }}
                </div>
            </div>
        </div>

        <!-- BANCOS (Real Table) -->
        <div data-tab-content="bancos" class="tab-pane hidden space-y-6">
            <div class="card-neo">
                <div class="flex justify-between items-center mb-8">
                    <div class="relative w-1/3">
                        <input type="text" class="input-neo !py-2.5 pl-10 text-xs" placeholder="Buscar banco...">
                        <i class="fas fa-search absolute left-4 top-3 text-slate-300 text-xs"></i>
                    </div>
                    <button onclick="openModal('modal-bank')" class="btn-neo btn-primary uppercase tracking-widest text-[10px] !py-3 !px-8">
                        <i class="fas fa-plus mr-2"></i> Novo Banco
                    </button>
                </div>
                <div class="overflow-hidden rounded-2xl border border-slate-100 shadow-md">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">Código</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">Nome do Banco</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">ISPB</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest border-r border-slate-50/50 italic">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-800 uppercase tracking-widest text-right italic">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($banks as $bank)
                            <tr class="hover:bg-white transition-all group cursor-pointer even:bg-slate-50" onclick="editEntity('banks', {{ $bank->id }})">
                                <td class="px-6 py-4 text-xs font-black text-slate-800 border-r border-slate-50/50 italic">{{ $bank->code ?? '---' }}</td>
                                <td class="px-6 py-4 font-black text-slate-800 uppercase text-xs tracking-tight border-r border-slate-50/50">{{ $bank->name }}</td>
                                <td class="px-6 py-4 text-[10px] font-bold text-slate-800 font-mono border-r border-slate-50/50">{{ $bank->ispb ?? '---' }}</td>
                                <td class="px-6 py-4 border-r border-slate-50/50">
                                    <span class="{{ $bank->is_active ? 'badge-success !bg-emerald-500 !text-white' : 'badge-danger !bg-rose-500 !text-white' }} border-none text-[8px] px-2 py-0.5 uppercase">{{ $bank->is_active ? 'Ativo' : 'Inativo' }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2" onclick="event.stopPropagation()">
                                        <button onclick="editEntity('banks', {{ $bank->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-800 hover:bg-accent hover:text-white flex items-center justify-center transition-all shadow-sm">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </button>
                                        <button onclick="deleteEntity('banks', {{ $bank->id }})" class="w-8 h-8 rounded-lg bg-slate-50/50 text-slate-800 hover:bg-rose-600 hover:text-white transition-all">
                                            <i class="fas fa-trash text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="py-10 text-center text-[10px] font-black uppercase tracking-widest italic text-slate-400 bg-slate-50">Nenhum banco cadastrado</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $banks->appends(['tab' => 'bancos'])->links() }}
                </div>
            </div>
        </div>

    </div>
    @endsection

@push('modals')
    <!-- MODALS -->
    <!-- Modal Fornecedor -->
    <div id="modal-supplier" class="fixed inset-0 z-[9999] hidden bg-black/80 backdrop-blur-md items-center justify-center p-4">
        <div class="card-neo w-full max-w-4xl max-h-[90vh] animate-reveal-up !p-0 overflow-hidden shadow-2xl flex flex-col border-white/20">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                <div>
                    <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter flex items-center gap-3">
                        <i class="fas fa-truck-field text-accent"></i>
                        Ficha do Fornecedor
                    </h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-0.5">Módulo de Gestão de Parceiros</p>
                </div>
                <button onclick="closeModal('modal-supplier')" class="w-12 h-12 rounded-xl flex items-center justify-center bg-accent text-white hover:bg-accent-hover transition-all shadow-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Tabs -->
            <div class="flex border-b border-slate-100 bg-slate-50/50 px-8 gap-6 shrink-0">
                <button onclick="switchSupplierTab('geral')" class="supplier-tab-btn active px-4 py-4 text-[10px] font-bold uppercase tracking-widest border-b-2 border-accent text-accent transition-all">Dados Gerais</button>
                <button onclick="switchSupplierTab('contatos')" class="supplier-tab-btn px-4 py-4 text-[10px] font-bold uppercase tracking-widest border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all">Contatos</button>
                <button onclick="switchSupplierTab('historico')" class="supplier-tab-btn px-4 py-4 text-[10px] font-bold uppercase tracking-widest border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all">Histórico</button>
            </div>
            
            <form id="form-supplier" class="flex-1 overflow-y-auto custom-scrollbar">
                <input type="hidden" name="id" id="supplier_id">
                
                <!-- Tab: Geral -->
                <div id="supplier-tab-geral" class="supplier-tab-content p-8 space-y-10">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
                        <!-- Identificação -->
                        <div class="md:col-span-12">
                            <h4 class="text-[11px] font-bold text-slate-600 uppercase tracking-widest mb-6 flex items-center gap-3">
                                <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center text-[10px] shadow-sm">01</span>
                                Identificação Jurídica
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                <div class="md:col-span-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Documento Principal</label>
                                        <div class="flex bg-slate-50/50 p-1 rounded-xl scale-90 origin-right border border-slate-300">
                                            <button type="button" onclick="setDocType('cnpj')" id="btn-doc-cnpj" class="text-[9px] font-black uppercase px-3 py-1.5 rounded-lg transition-all bg-accent text-white shadow-md">CNPJ</button>
                                            <button type="button" onclick="setDocType('cpf')" id="btn-doc-cpf" class="text-[9px] font-black uppercase px-3 py-1.5 rounded-lg transition-all text-slate-600">CPF</button>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <input type="text" name="document" id="supplier_document" class="input-neo !py-4 mask-cnpj w-full text-sm border-slate-100" required placeholder="00.000.000/0000-00">
                                        <input type="hidden" name="document_type" id="supplier_document_type" value="cnpj">
                                        <div id="cnpj-loader" class="hidden absolute right-4 top-1/2 -translate-y-1/2"><i class="fas fa-circle-notch fa-spin text-slate-800 text-sm"></i></div>
                                    </div>
                                </div>
                                
                                <div class="md:col-span-8">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2 block">Razão Social (Nome Completo)</label>
                                    <input type="text" name="name" id="supplier_name" class="input-neo !py-4 w-full text-sm border-slate-100" required placeholder="Digite a razão social completa">
                                </div>

                                <div class="md:col-span-4">
                                    <label class="text-[10px] font-black uppercase tracking-wider text-slate-800 mb-2 block italic">Status do Registro</label>
                                    <select name="status" id="supplier_status_input" class="input-neo !py-4 w-full text-[10px] font-black uppercase text-slate-800 cursor-pointer appearance-none border-slate-100 bg-slate-50">
                                        <option value="active">🟢 Ativo / Operante</option>
                                        <option value="inactive">🔴 Inativo / Bloqueado</option>
                                    </select>
                                </div>

                                <div class="md:col-span-8">
                                    <label class="text-[10px] font-black uppercase tracking-wider text-slate-800 mb-2 block italic">Nome Fantasia / Apelido</label>
                                    <input type="text" name="nickname" id="supplier_nickname" class="input-neo !py-4 w-full text-sm border-slate-100" placeholder="Nome como a empresa é conhecida popularmente">
                                </div>
                            </div>
                        </div>

                        <!-- Localização -->
                        <div class="md:col-span-12">
                            <h4 class="text-[12px] font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-3 italic">
                                <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center text-[11px] shadow-sm">02</span>
                                Localização e Sede
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                <div class="md:col-span-3">
                                    <label class="text-[10px] font-black uppercase tracking-wider text-slate-800 mb-2 block italic">CEP</label>
                                    <input type="text" name="address[zip_code]" id="supplier_zip_code" class="input-neo !py-4 mask-cep w-full text-sm border-slate-100" placeholder="00000-000">
                                </div>
                                <div class="md:col-span-4">
                                    <label class="text-[10px] font-black uppercase tracking-wider text-slate-800 mb-2 block italic">Bairro</label>
                                    <input type="text" name="address[neighborhood]" id="supplier_neighborhood" class="input-neo !py-4 w-full text-sm border-slate-100" placeholder="Ex: Jardim Paulista">
                                </div>
                                <div class="md:col-span-5">
                                    <label class="text-[10px] font-black uppercase tracking-wider text-slate-800 mb-2 block italic">Cidade / UF</label>
                                    <input type="text" name="address[city]" id="supplier_city" class="input-neo !py-4 w-full text-sm border-slate-100" placeholder="Ex: São Paulo / SP">
                                </div>
                                <div class="md:col-span-12">
                                    <label class="text-[10px] font-black uppercase tracking-wider text-slate-800 mb-2 block italic">Logradouro / Rua (Completo)</label>
                                    <input type="text" name="address[street]" id="supplier_address" class="input-neo !py-4 w-full text-sm border-slate-100" placeholder="Rua, Avenida, Número, Complemento...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Contatos -->
                <div id="supplier-tab-contatos" class="supplier-tab-content hidden p-8 space-y-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="card-neo !p-6 border border-slate-100 bg-slate-50/50">
                            <h5 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <i class="fas fa-file-invoice-dollar text-slate-800"></i>
                                Contato Financeiro
                            </h5>
                            <div class="space-y-5">
                                <div>
                                    <label class="text-[10px] font-black text-slate-600 uppercase block mb-2 italic">Nome do Responsável</label>
                                    <input type="text" name="contacts[finance][name]" id="supplier_fin_name" class="input-neo w-full !py-3" placeholder="Ex: João da Silva">
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-slate-600 uppercase block mb-2 italic">E-mail</label>
                                    <input type="email" name="contacts[finance][email]" id="supplier_fin_email" class="input-neo w-full !py-3" placeholder="financeiro@fornecedor.com">
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-slate-600 uppercase block mb-2 italic">Telefone / WhatsApp</label>
                                    <input type="text" name="contacts[finance][phone]" id="supplier_fin_phone" class="input-neo w-full !py-3 mask-phone" placeholder="(00) 00000-0000">
                                </div>
                            </div>
                        </div>
                        <div class="card-neo !p-6 border border-slate-100 bg-slate-50/50">
                            <h5 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <i class="fas fa-handshake text-slate-800"></i>
                                Contato Comercial
                            </h5>
                            <div class="space-y-5">
                                <div>
                                    <label class="text-[10px] font-black text-slate-600 uppercase block mb-2 italic">Consultor de Vendas</label>
                                    <input type="text" name="contacts[sales][name]" id="supplier_sales_name" class="input-neo w-full !py-3" placeholder="Ex: Maria Oliveira">
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-slate-600 uppercase block mb-2 italic">E-mail Comercial</label>
                                    <input type="email" name="contacts[sales][email]" id="supplier_sales_email" class="input-neo w-full !py-3" placeholder="vendas@fornecedor.com">
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-slate-600 uppercase block mb-2 italic">Telefone Direto</label>
                                    <input type="text" name="contacts[sales][phone]" id="supplier_sales_phone" class="input-neo w-full !py-3 mask-phone" placeholder="(00) 00000-0000">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Histórico -->
                <div id="supplier-tab-historico" class="supplier-tab-content hidden p-8 space-y-8">
                    <div>
                        <label class="text-[11px] font-black text-slate-800 uppercase block mb-3 italic tracking-widest">Observações Internas / Histórico Técnico</label>
                        <textarea name="notes" id="supplier_notes" rows="6" class="input-neo !py-4 w-full text-sm border-slate-100" placeholder="Registre aqui detalhes importantes sobre este fornecedor..."></textarea>
                    </div>

                    <div class="bg-amber-50 text-amber-600 p-5 rounded-2xl flex gap-5 items-center border border-amber-100 shadow-sm">
                        <i class="fas fa-shield-halved text-xl"></i>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest leading-tight">Nota de Auditoria</p>
                            <p class="text-[9px] font-medium mt-1 opacity-80 uppercase">Este histórico é registrado para fins de compliance financeiro.</p>
                        </div>
                    </div>
                </div>
            </form>

            <div class="p-8 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-5 shrink-0">
                <button type="button" onclick="closeModal('modal-supplier')" class="px-8 py-4 bg-white border border-slate-100 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">Cancelar</button>
                <button type="submit" form="form-supplier" class="px-10 py-4 bg-accent text-white border border-accent-hover rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-accent-hover transition-all shadow-xl">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Ficha Cadastral
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Centro de Custo -->
    <div id="modal-cost-center" class="fixed inset-0 z-[9999] hidden bg-black/80 backdrop-blur-md items-center justify-center p-4">
        <div class="card-neo w-full max-w-lg max-h-[90vh] animate-reveal-up !p-0 overflow-hidden shadow-2xl flex flex-col border-slate-100">
            <div class="p-6 border-b-2 border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                <div>
                    <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight flex items-center gap-2">
                        <i class="fas fa-tags text-slate-800"></i>
                        Centro de Custo
                    </h3>
                    <p class="text-[10px] text-slate-600 font-black uppercase tracking-widest mt-1 italic">Configuração / Orçamentário</p>
                </div>
                <button onclick="closeModal('modal-cost-center')" class="w-10 h-10 rounded-xl flex items-center justify-center bg-accent text-white hover:bg-accent-hover transition-all shadow-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="form-cost-center" class="p-8 space-y-6 overflow-y-auto custom-scrollbar flex-1 bg-white">
                <input type="hidden" name="id" id="cc_id">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-wider text-slate-800 mb-2 block italic">Código Classificador</label>
                        <input type="text" name="code" id="cc_code" class="input-neo !py-4 w-full text-sm border-slate-100" required placeholder="Ex: 01.001">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-wider text-slate-800 mb-2 block italic">Nome do Centro de Custo</label>
                        <input type="text" name="name" id="cc_name" class="input-neo !py-4 w-full text-sm border-slate-100" required placeholder="Ex: Secretaria Executiva">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-wider text-slate-800 mb-2 block italic">Descrição Técnica</label>
                        <textarea name="description" id="cc_description" class="input-neo !py-4 w-full text-sm h-28 border-slate-100" placeholder="Finalidade deste centro..."></textarea>
                    </div>
                </div>
            </form>

            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-4 shrink-0">
                <button type="button" onclick="closeModal('modal-cost-center')" class="px-6 py-3 bg-white border border-slate-100 rounded-xl text-[10px] font-bold text-slate-500 uppercase tracking-widest hover:bg-slate-50 transition-all">Cancelar</button>
                <button type="submit" form="form-cost-center" class="px-8 py-3 bg-accent text-white border border-accent-hover rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-accent-hover transition-all shadow-lg">Salvar Centro</button>
            </div>
        </div>
    </div>

    <!-- Modal Instituição Bancária -->
    <div id="modal-bank" class="fixed inset-0 z-[9999] hidden bg-black/80 backdrop-blur-md items-center justify-center p-4">
        <div class="card-neo w-full max-w-xl max-h-[90vh] animate-reveal-up !p-0 overflow-hidden shadow-2xl flex flex-col border-white/20">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                <div>
                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight flex items-center gap-2">
                        <i class="fas fa-university text-accent"></i>
                        Instituição Bancária
                    </h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Parâmetros / Integração</p>
                </div>
                <button onclick="closeModal('modal-bank')" class="w-10 h-10 rounded-xl flex items-center justify-center bg-accent text-white hover:bg-accent-hover transition-all shadow-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="form-bank" class="p-8 space-y-6 overflow-y-auto custom-scrollbar flex-1 bg-white">
                <input type="hidden" name="id" id="bank_id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-wider text-slate-800 mb-2 block italic">Nome da Instituição</label>
                        <input type="text" name="name" id="bank_name" class="input-neo !py-4 w-full text-sm border-slate-100" required placeholder="Ex: Itaú Unibanco S.A.">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-wider text-slate-800 mb-2 block italic">Código COMPE</label>
                        <input type="text" name="code" id="bank_code" class="input-neo !py-4 w-full text-sm border-slate-100" placeholder="Ex: 341">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-wider text-slate-800 mb-2 block italic">Código ISPB</label>
                        <input type="text" name="ispb" id="bank_ispb" class="input-neo !py-4 w-full text-sm border-slate-100" placeholder="00000000">
                    </div>
                </div>
            </form>

            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-4 shrink-0">
                <button type="button" onclick="closeModal('modal-bank')" class="px-6 py-3 bg-white border border-slate-100 rounded-xl text-[10px] font-bold text-slate-500 uppercase tracking-widest hover:bg-slate-50 transition-all">Cancelar</button>
                <button type="submit" form="form-bank" class="px-8 py-3 bg-accent text-white border border-accent-hover rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-accent-hover transition-all shadow-lg">Salvar Banco</button>
            </div>
        </div>
    </div>

    <!-- Modal Unidade -->
    <div id="modal-units" class="fixed inset-0 z-[9999] hidden bg-black/80 backdrop-blur-md items-center justify-center p-4">
        <div class="card-neo w-full max-w-lg max-h-[90vh] animate-reveal-up !p-0 overflow-hidden shadow-2xl flex flex-col border-slate-100">
            <div class="p-6 border-b-2 border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                <div>
                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight flex items-center gap-2">
                        <i class="fas fa-church text-accent"></i>
                        Unidade / Campus
                    </h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Hierarquia Organizacional</p>
                </div>
                <button onclick="closeModal('modal-units')" class="w-10 h-10 rounded-xl flex items-center justify-center bg-accent text-white hover:bg-accent-hover transition-all shadow-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="form-units" class="p-8 space-y-6 overflow-y-auto custom-scrollbar flex-1 bg-white">
                <input type="hidden" name="id" id="unit_id">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-wider text-slate-800 mb-2 block italic">Nome da Unidade / Campus</label>
                        <input type="text" name="name" id="unit_name" class="input-neo !py-4 w-full text-sm border-slate-100" required placeholder="Ex: Sede Principal">
                    </div>
                </div>
            </form>

            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-4 shrink-0">
                <button type="button" onclick="closeModal('modal-units')" class="px-6 py-3 bg-white border border-slate-100 rounded-xl text-[10px] font-bold text-slate-500 uppercase tracking-widest hover:bg-slate-50 transition-all">Cancelar</button>
                <button type="submit" form="form-units" class="px-8 py-3 bg-accent text-white border border-accent-hover rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-accent-hover transition-all shadow-lg">Salvar Unidade</button>
            </div>
        </div>
    </div>

    <!-- Modal Plano de Contas -->
    <div id="modal-chart-of-accounts" class="fixed inset-0 z-[9999] hidden bg-black/80 backdrop-blur-md items-center justify-center p-4">
        <div class="card-neo w-full max-w-lg max-h-[90vh] animate-reveal-up !p-0 overflow-hidden shadow-2xl flex flex-col border-slate-100">
            <div class="p-6 border-b-2 border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                <div>
                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight flex items-center gap-2">
                        <i class="fas fa-sitemap text-accent"></i>
                        Plano de Contas
                    </h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Contábil / Estrutura</p>
                </div>
                <button onclick="closeModal('modal-chart-of-accounts')" class="w-10 h-10 rounded-xl flex items-center justify-center bg-slate-100 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            
            <form id="form-chart-of-accounts" class="p-8 space-y-6 overflow-y-auto custom-scrollbar flex-1 bg-white">
                <input type="hidden" name="id" id="coa_id">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-wider text-slate-800 mb-2 block italic">Código Classificador</label>
                        <input type="text" name="code" id="coa_code" class="input-neo !py-4 w-full text-sm border-slate-100" required placeholder="Ex: 1.01.01">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-wider text-slate-800 mb-2 block italic">Tipo de Conta</label>
                        <select name="type" id="coa_type" class="input-neo !py-4 w-full text-sm border-slate-100 bg-slate-50" required>
                            <option value="revenue">Receita</option>
                            <option value="expense">Despesa</option>
                            <option value="asset">Ativo</option>
                            <option value="liability">Passivo</option>
                            <option value="equity">Patrimônio</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-wider text-slate-800 mb-2 block italic">Nome da Conta / Descrição</label>
                        <input type="text" name="name" id="coa_name" class="input-neo !py-4 w-full text-sm border-slate-100" required placeholder="Ex: Dízimos e Ofertas">
                    </div>
                </div>
            </form>
            <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-4 shrink-0">
                <button type="button" onclick="closeModal('modal-chart-of-accounts')" class="px-6 py-3 bg-white border border-slate-100 rounded-xl text-[10px] font-bold text-slate-500 uppercase tracking-widest hover:bg-slate-50 transition-all">Cancelar</button>
                <button type="submit" form="form-chart-of-accounts" class="px-8 py-3 bg-accent text-white border border-accent-hover rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-accent-hover transition-all shadow-lg">Salvar Conta</button>
            </div>
        </div>
    </div>

    <!-- Modal Conta Financeira -->
    <div id="modal-accounts" class="fixed inset-0 z-[9999] hidden bg-black/60 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-white rounded-[30px] border border-slate-100 w-full max-w-xl max-h-[90vh] animate-reveal-up !p-0 overflow-hidden shadow-2xl flex flex-col">
            <div class="p-6 border-b-2 border-slate-100 flex justify-between items-center bg-slate-50/30 shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-accent flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-wallet text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Conta Financeira</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Tesouraria / Disponibilidades</p>
                    </div>
                </div>
                <button onclick="closeModal('modal-accounts')" class="w-10 h-10 rounded-xl flex items-center justify-center hover:bg-rose-500 hover:text-white text-slate-400 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="form-accounts" class="p-8 space-y-6 overflow-y-auto custom-scrollbar flex-1 bg-slate-50/50">
                <input type="hidden" name="id" id="account_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 card-neo p-4 border border-slate-100">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-800 mb-2 block italic">Nome da Conta / Identificador</label>
                        <input type="text" name="name" id="account_name" class="input-neo !py-3 text-sm font-black text-slate-800 border-slate-300 focus:border-black" required placeholder="Ex: Itaú - Movimentação">
                    </div>
                    <div class="card-neo p-4 border border-slate-100">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-800 mb-2 block italic">Tipo de Conta</label>
                        <select name="type" id="account_type" class="input-neo !py-3 text-sm font-black uppercase border-slate-300 focus:border-black" required>
                            <option value="bank">Banco / Corrente</option>
                            <option value="cash">Caixa / Dinheiro</option>
                            <option value="investment">Investimento</option>
                        </select>
                    </div>
                    <div id="initial_balance_container" class="card-neo p-4 border border-slate-100">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-800 mb-2 block italic">Saldo Inicial (R$)</label>
                        <input type="text" name="initial_balance" id="account_initial_balance" class="input-neo !py-3 text-sm font-black border-slate-300 focus:border-black mask-money" placeholder="0,00">
                    </div>
                    
                    <div class="md:col-span-2 p-6 bg-slate-50/30 border border-slate-100 rounded-2xl p-6 grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-800 mb-2 block italic">Banco Vinculado (Opcional)</label>
                            <select name="bank_id" id="account_bank_id" class="input-neo !py-3 text-sm font-black border-slate-300 focus:border-black">
                                <option value="">Nenhum Banco</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="card-neo p-3 border border-slate-300 bg-white">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-500 mb-1 block">Agência</label>
                            <input type="text" name="agency" id="account_agency" class="input-neo !py-2 text-xs font-black text-slate-800 border-none !p-0" placeholder="0000">
                        </div>
                        <div class="card-neo p-3 border border-slate-300 bg-white">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-500 mb-1 block">Número Conta</label>
                            <input type="text" name="account_number" id="account_number" class="input-neo !py-2 text-xs font-black text-slate-800 border-none !p-0" placeholder="00000-0">
                        </div>
                    </div>
                </div>
            </form>

            <div class="p-8 border-t border-slate-100 bg-slate-50/50 flex justify-between items-center shrink-0">
                <button type="button" onclick="closeModal('modal-accounts')" class="text-[10px] font-black text-primary-light uppercase tracking-widest hover:text-rose-600 transition-colors">Cancelar</button>
                <button type="submit" form="form-accounts" class="btn-neo btn-primary !py-4 !px-12 !rounded-2xl transition-all hover:scale-105 active:scale-95">Salvar Configuração</button>
            </div>
        </div>
    </div>
</div>

@endpush

@push('styles')
<style>
    .tab-btn {
        background: transparent;
        color: #64748b;
        border: 1px solid transparent;
        @apply font-black tracking-widest uppercase;
    }

    .tab-btn.active {
        @apply bg-accent text-white shadow-xl shadow-accent/20 border-accent scale-105;
    }

    .tab-btn:hover:not(.active) {
        @apply bg-slate-50 text-slate-800 border-slate-100;
    }

    #settings-tabs::-webkit-scrollbar {
        height: 4px;
    }
    #settings-tabs::-webkit-scrollbar-thumb {
        @apply bg-slate-50/50 rounded-full;
    }
    
    .card-neo {
        @apply bg-white rounded-[32px] border border-slate-100 p-8 shadow-xl relative overflow-hidden transition-all duration-300;
    }

    .input-neo {
        @apply bg-white border border-slate-200 rounded-2xl px-5 py-4 text-slate-800 font-bold text-xs transition-all duration-300 placeholder:text-slate-400 placeholder:font-medium focus:border-primary-dark focus:ring-4 focus:ring-primary-dark/5 outline-none;
    }

    .badge-success { @apply px-2.5 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100; }
    .badge-danger { @apply px-2.5 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest bg-rose-50 text-rose-600 border border-rose-100; }
</style>
@endpush

@push('scripts')
<script>
    function switchTab(tabId) {
        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));
        const activePane = document.querySelector(`[data-tab-content="${tabId}"]`);
        if (activePane) {
            activePane.classList.remove('hidden');
            activePane.classList.add('animate-reveal-up');
        }

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.getAttribute('data-tab-btn') === tabId) btn.classList.add('active');
        });

        localStorage.setItem('activeFinanceTab', tabId);
        
        // Update URL to keep tab on refresh if needed (Optional)
        const url = new URL(window.location);
        url.searchParams.set('tab', tabId);
        window.history.pushState({}, '', url);
    }

    function setDocType(type) {
        const input = $('#supplier_document');
        $('#supplier_document_type').val(type);
        input.val(''); // Clear on change to avoid mask issues
        
        if (type === 'cnpj') {
            input.mask('00.000.000/0000-00');
            input.attr('placeholder', '00.000.000/0000-00');
            $('#btn-doc-cnpj').addClass('bg-white text-accent shadow-sm').removeClass('text-slate-400');
            $('#btn-doc-cpf').removeClass('bg-white text-accent shadow-sm').addClass('text-slate-400');
        } else {
            input.mask('000.000.000-00');
            input.attr('placeholder', '000.000.000-00');
            $('#btn-doc-cpf').addClass('bg-white text-accent shadow-sm').removeClass('text-slate-400');
            $('#btn-doc-cnpj').removeClass('bg-white text-accent shadow-sm').addClass('text-slate-400');
        }
    }

    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.getElementById(modalId).classList.add('flex');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.getElementById(modalId).classList.remove('flex');
        // Reset form
        const form = document.querySelector(`#${modalId} form`);
        if (form) {
            form.reset();
            const idField = form.querySelector('input[type="hidden"]');
            if (idField) idField.value = '';
            
            // Special case for accounts balance
            const balanceContainer = document.getElementById('initial_balance_container');
            if (balanceContainer) balanceContainer.style.display = 'block';
        }
    }

    async function editEntity(type, id) {
        try {
            const response = await fetch(`/admin/finance/${type}/${id}`);
            if (!response.ok) throw new Error('Falha na resposta do servidor');
            
            const data = await response.json();
            
            const setValue = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.value = val || '';
            };

            if (type === 'suppliers') {
                setValue('supplier_id', data.id);
                setValue('supplier_name', data.name);
                setValue('supplier_nickname', data.nickname);
                setValue('supplier_document', data.document);
                setValue('supplier_document_type', data.document_type);
                setValue('supplier_email', data.email);
                setValue('supplier_phone', data.phone);
                setValue('supplier_status_input', data.status);
                setValue('supplier_notes', data.notes);
                
                if (data.contacts) {
                    if (data.contacts.finance) {
                        setValue('supplier_fin_name', data.contacts.finance.name);
                        setValue('supplier_fin_email', data.contacts.finance.email);
                        setValue('supplier_fin_phone', data.contacts.finance.phone);
                    }
                    if (data.contacts.sales) {
                        setValue('supplier_sales_name', data.contacts.sales.name);
                        setValue('supplier_sales_email', data.contacts.sales.email);
                        setValue('supplier_sales_phone', data.contacts.sales.phone);
                    }
                }

                if (data.address) {
                    setValue('supplier_zip_code', data.address.zip_code);
                    setValue('supplier_neighborhood', data.address.neighborhood);
                    setValue('supplier_city', data.address.city);
                    setValue('supplier_address', data.address.street);
                }
                
                openModal('modal-supplier');
            } else if (type === 'cost-centers') {
                setValue('cc_id', data.id);
                setValue('cc_code', data.code);
                setValue('cc_name', data.name);
                setValue('cc_description', data.description);
                setValue('cc_is_active', data.is_active ? '1' : '0');
                openModal('modal-cost-center');
            } else if (type === 'banks') {
                setValue('bank_id', data.id);
                setValue('bank_name', data.name);
                setValue('bank_code', data.code);
                setValue('bank_ispb', data.ispb);
                setValue('bank_is_active', data.is_active ? '1' : '0');
                openModal('modal-bank');
            } else if (type === 'chart-of-accounts') {
                setValue('coa_id', data.id);
                setValue('coa_code', data.code);
                setValue('coa_name', data.name);
                setValue('coa_type', data.type);
                openModal('modal-chart-of-accounts');
            } else if (type === 'accounts') {
                setValue('account_id', data.id);
                setValue('account_name', data.name);
                setValue('account_type', data.type);
                
                const balanceContainer = document.getElementById('initial_balance_container');
                if (balanceContainer) balanceContainer.style.display = 'none';
                
                openModal('modal-accounts');
            } else if (type === 'units') {
                setValue('unit_id', data.id);
                setValue('unit_name', data.name);
                openModal('modal-units');
            }
        } catch (error) {
            console.error('Erro ao buscar dados:', error);
            alert('Erro ao carregar dados para edição: ' + error.message);
        }
    }

    // Alternar Abas do Fornecedor
    function switchSupplierTab(tabId) {
        $('.supplier-tab-btn').removeClass('active border-accent text-accent').addClass('border-transparent text-slate-400');
        $(`.supplier-tab-btn[onclick*="${tabId}"]`).addClass('active border-accent text-accent').removeClass('border-transparent text-slate-400');
        $('.supplier-tab-content').addClass('hidden');
        $(`#supplier-tab-${tabId}`).removeClass('hidden');
    }

    // Busca Automática de CNPJ (Expandida)
    $('#supplier_document').on('blur', function() {
        let val = $(this).val().replace(/\D/g, '');
        
        if (val.length === 14) {
            $('#cnpj-loader').removeClass('hidden');
            $.ajax({
                url: `https://brasilapi.com.br/api/cnpj/v1/${val}`,
                method: 'GET',
                success: function(data) {
                    $('#supplier_name').val(data.razao_social);
                    $('#supplier_nickname').val(data.nome_fantasia || data.razao_social);
                    $('#supplier_cnae').val(`${data.cnae_fiscal} - ${data.cnae_fiscal_descricao}`);
                    $('#supplier_opening').val(data.data_inicio_atividade);
                    
                    if (data.capital_social) {
                        let formattedCapital = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(data.capital_social);
                        $('#supplier_capital').val(formattedCapital);
                    }

                    if (data.cep) {
                        $('#supplier_zip_code').val(data.cep).trigger('input').trigger('blur');
                    }
                },
                complete: function() {
                    $('#cnpj-loader').addClass('hidden');
                }
            });
        }
    });

    // Busca Automática de CEP
    $('#supplier_zip_code').on('blur', function() {
        let val = $(this).val().replace(/\D/g, '');
        if (val.length === 8) {
            $.ajax({
                url: `https://viacep.com.br/ws/${val}/json/`,
                method: 'GET',
                success: function(data) {
                    if (!data.erro) {
                        $('#supplier_neighborhood').val(data.bairro);
                        $('#supplier_address').val(data.logradouro);
                        $('#supplier_city').val(`${data.localidade} / ${data.uf}`);
                    }
                }
            });
        }
    });

    // Inicializar Mascaras Dinâmicas
    const maskBehavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    };
    const options = {
        onKeyPress: function(val, e, field, options) {
            field.mask(maskBehavior.apply({}, arguments), options);
        }
    };
    $('.mask-phone').mask(maskBehavior, options);
    // Inicialização de Máscaras
    const applyMasks = () => {
        $('.mask-cep').mask('00000-000');
        $('.mask-cnpj').mask('00.000.000/0000-00');
        $('.mask-cpf').mask('000.000.000-00');
        $('.mask-money').mask('#.##0,00', {reverse: true});
        $('.mask-phone').mask(maskBehavior, { 
            onKeyPress: function(val, e, field, options) { 
                field.mask(maskBehavior.apply({}, arguments), options); 
            } 
        });
    };

    applyMasks();

    async function deleteEntity(type, id) {
        if (!confirm('Tem certeza que deseja excluir este registro?')) return;
        
        try {
            const response = await fetch(`/admin/finance/${type}/${id}`, {
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
            console.error('Erro ao excluir:', error);
            alert('Erro de conexão ao tentar excluir.');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const forms = ['form-supplier', 'form-cost-center', 'form-bank', 'form-chart-of-accounts', 'form-accounts', 'form-units'];
        
        forms.forEach(formId => {
            const form = document.getElementById(formId);
            if (!form) return;
            
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                const id = formData.get('id');
                const type = formId.replace('form-', '').replace('cost-center', 'cost-centers').replace('supplier', 'suppliers').replace('bank', 'banks');
                
                const url = id ? `/admin/finance/${type}/${id}` : `/admin/finance/${type}`;
                
                const payload = {};
                formData.forEach((value, key) => {
                    if (key.includes('[')) {
                        const keys = key.split(/\[|\]/).filter(Boolean);
                        let current = payload;
                        for (let i = 0; i < keys.length; i++) {
                            const k = keys[i];
                            if (i === keys.length - 1) {
                                current[k] = value;
                            } else {
                                if (!current[k]) current[k] = {};
                                current = current[k];
                            }
                        }
                    } else {
                        payload[key] = value;
                    }
                });

                if (id) payload['_method'] = 'PUT';

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        location.reload();
                    } else {
                        alert(result.message || 'Erro ao salvar registro.');
                    }
                } catch (error) {
                    console.error('Erro ao salvar:', error);
                    alert('Erro de conexão ao tentar salvar.');
                }
            });
        });

        // Re-apply masks when modals open
        $(document).on('click', '[onclick*="openModal"], [onclick*="editEntity"]', function() {
            setTimeout(applyMasks, 100);
        });

        // Initialize Tab
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        const savedTab = tabParam || localStorage.getItem('activeFinanceTab') || 'unidades';
        switchTab(savedTab);
    });
</script>
@endpush
