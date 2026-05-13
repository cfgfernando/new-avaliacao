@extends('layouts.app')

@section('title', 'Painel Central de Controle')

@section('content')
<div class="space-y-8 animate-reveal-up">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-black text-primary-dark tracking-tight uppercase">Painel Central de <span class="text-accent italic">Controle</span></h2>
            <p class="text-primary-light font-medium mt-1">Gestão técnica de unidades, fornecedores e parâmetros financeiros.</p>
        </div>
        <div class="hidden md:flex items-center gap-3">
            <div class="text-right">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Status do Sistema</span>
                <span class="text-[10px] font-black text-emerald-500 uppercase italic">Ambiente Seguro</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-accent border border-slate-100">
                <i class="fas fa-server"></i>
            </div>
        </div>
    </div>

    <!-- Dynamic Tab Bar (Fixed Cut-off) -->
    <div class="card-neo !p-2 bg-white/90 backdrop-blur-md border-slate-200/60 shadow-md sticky top-24 z-40">
        <div class="flex items-center space-x-2 overflow-x-auto scrollbar-hide px-2 py-1" id="settings-tabs">
            @php
                $tabs = [
                    ['id' => 'unidades', 'label' => 'Unidade / Campus', 'icon' => 'fa-church'],
                    ['id' => 'fornecedores', 'label' => 'Fornecedores', 'icon' => 'fa-truck-field'],
                    ['id' => 'plano-contas', 'label' => 'Plano de Contas', 'icon' => 'fa-sitemap'],
                    ['id' => 'centros-custo', 'label' => 'Centro de Custos', 'icon' => 'fa-tags'],
                    ['id' => 'bancos', 'label' => 'Bancos', 'icon' => 'fa-university'],
                    ['id' => 'contas', 'label' => 'Contas Financeiras', 'icon' => 'fa-credit-card'],
                    ['id' => 'bloqueios', 'label' => 'Bloqueios Contábeis', 'icon' => 'fa-lock-clock'],
                ];
            @endphp

            @foreach($tabs as $tab)
                <button onclick="switchTab('{{ $tab['id'] }}')" 
                        data-tab-btn="{{ $tab['id'] }}"
                        class="tab-btn flex items-center gap-3 px-5 py-3 rounded-xl transition-all duration-300 whitespace-nowrap min-w-fit">
                    <i class="fas {{ $tab['icon'] }} text-xs"></i>
                    <span class="text-[10px] font-black tracking-widest uppercase">{{ $tab['label'] }}</span>
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
                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">Unidade / Campus</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">CNPJ / Identificador</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">Responsável</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($units ?? [] as $unit)
                            <tr class="hover:bg-slate-50/30 transition-all group cursor-pointer" onclick="editEntity('units', {{ $unit->id }})">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-accent group-hover:text-white transition-all">
                                            <i class="fas fa-church"></i>
                                        </div>
                                        <div>
                                            <span class="text-xs font-black text-primary-dark uppercase block tracking-tight">{{ $unit->name }}</span>
                                            <span class="text-[9px] text-primary-light font-bold italic">Sede Administrativa</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-500 font-mono italic">{{ $unit->tax_id ?? '00.000.000/0001-00' }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-[10px] font-black text-primary-dark uppercase">Admin Principal</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="badge-success">Ativo</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2" onclick="event.stopPropagation()">
                                        <button onclick="editEntity('units', {{ $unit->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-accent hover:text-white flex items-center justify-center transition-all">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </button>
                                        <button class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-rose-500 hover:text-white transition-all">
                                            <i class="fas fa-trash text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="py-10 text-center text-primary-light uppercase text-[10px] font-black italic">Nenhuma unidade configurada</td></tr>
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

                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">Instituição / Conta</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">Agência / Conta</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">Saldo Atual</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($financialAccounts ?? [] as $account)
                            <tr class="hover:bg-slate-50/30 transition-all group cursor-pointer" onclick="editEntity('accounts', {{ $account->id }})">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-accent text-sm group-hover:bg-accent group-hover:text-white transition-all">
                                            <i class="fas fa-university"></i>
                                        </div>
                                        <div>
                                            <span class="text-xs font-black text-primary-dark uppercase block tracking-tight">{{ $account->name }}</span>
                                            <span class="text-[8px] text-slate-400 font-bold uppercase tracking-widest italic">Conta Corrente</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-500 font-mono">{{ $account->agency ?? '0001' }} / {{ $account->account_number ?? '12345-6' }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-black text-emerald-600 font-money italic">R$ {{ number_format($account->balance ?? 0, 2, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="badge-success">Ativa</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                     <div class="flex justify-end gap-2" onclick="event.stopPropagation()">
                                        <button onclick="editEntity('accounts', {{ $account->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-accent hover:text-white flex items-center justify-center transition-all">
                                            <i class="fas fa-gear text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="py-10 text-center text-primary-light uppercase text-[10px] font-black italic">Nenhuma conta encontrada</td></tr>
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

                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/80">
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest italic">Código</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest italic">Nome da Conta</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest italic">Tipo</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest italic">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest italic text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($chartOfAccounts ?? [] as $coa)
                            <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer" onclick="editEntity('chart-of-accounts', {{ $coa->id }})">
                                <td class="px-6 py-4 text-xs font-black text-accent italic">{{ $coa->code }}</td>
                                <td class="px-6 py-4 font-black text-primary-dark uppercase text-xs tracking-tight">{{ $coa->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-[8px] font-black uppercase tracking-widest {{ $coa->type === 'revenue' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                        {{ $coa->type === 'revenue' ? 'Receita' : 'Despesa' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $coa->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}"></div>
                                        <span class="text-[9px] font-black uppercase tracking-widest {{ $coa->is_active ? 'text-emerald-600' : 'text-slate-400' }}">
                                            {{ $coa->is_active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2" onclick="event.stopPropagation()">
                                        <button onclick="editEntity('chart-of-accounts', {{ $coa->id }})" class="text-primary-light hover:text-accent p-2">
                                            <i class="fas fa-pen text-xs"></i>
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
                        <h3 class="text-xs font-black text-primary-dark uppercase tracking-[0.2em] italic mb-6 border-b border-slate-50 pb-4">Segurança Contábil</h3>
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
                        <h3 class="text-[10px] font-black text-primary-dark uppercase tracking-widest italic mb-6">Histórico de Fechamentos</h3>
                        <div class="overflow-hidden rounded-xl border border-slate-50">
                            <table class="w-full text-left">
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($closures ?? [] as $closure)
                                    <tr class="hover:bg-slate-50/50 transition-all">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center">
                                                    <i class="fas fa-calendar-check text-xs"></i>
                                                </div>
                                                <span class="text-[11px] font-black text-primary-dark uppercase tracking-tight">Fechamento {{ \Carbon\Carbon::createFromDate($closure->year, $closure->month, 1)->translatedFormat('F Y') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-[9px] text-slate-400 font-bold uppercase italic">Auditado em {{ $closure->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="badge-success">Encerrado</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td class="py-10 text-center text-[10px] font-black uppercase tracking-widest italic text-slate-300">Sem histórico</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="card-neo bg-gradient-to-br from-primary-dark to-slate-800 text-white border-none shadow-2xl">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-accent">
                                <i class="fas fa-shield-check text-xl"></i>
                            </div>
                            <h3 class="text-sm font-black uppercase tracking-widest italic leading-tight">Protocolo de <br>Auditoria</h3>
                        </div>
                        <p class="text-[11px] font-medium leading-relaxed mb-8 opacity-80 italic">
                            O bloqueio impede edições retroativas em todas as unidades, garantindo a integridade dos relatórios contábeis para o conselho fiscal.
                        </p>
                        <div class="bg-white/5 p-4 rounded-xl border border-white/10">
                            <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-widest">
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
                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">Fornecedor</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">CNPJ/CPF</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">Contato</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($suppliers as $supplier)
                            <tr class="hover:bg-slate-50/30 transition-all group cursor-pointer" onclick="editEntity('suppliers', {{ $supplier->id }})">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-accent group-hover:text-white transition-all">
                                            <i class="fas fa-truck-field"></i>
                                        </div>
                                        <div>
                                            <span class="text-xs font-black text-primary-dark uppercase block tracking-tight">{{ $supplier->name }}</span>
                                            @if($supplier->nickname)
                                                <span class="text-[9px] text-accent font-black uppercase block mt-0.5">{{ $supplier->nickname }}</span>
                                            @endif
                                            <span class="text-[9px] text-primary-light font-bold italic">
                                                {{ $supplier->email ?: ($supplier->contacts['finance']['email'] ?? ($supplier->contacts['sales']['email'] ?? 'Sem e-mail')) }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-500 font-mono italic">{{ $supplier->document ?? '---' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-primary-dark uppercase">
                                            {{ $supplier->phone ?: ($supplier->contacts['finance']['phone'] ?? ($supplier->contacts['sales']['phone'] ?? '---')) }}
                                        </span>
                                        @if(!empty($supplier->contacts['finance']['name']))
                                            <span class="text-[8px] text-primary-light font-bold italic uppercase">{{ $supplier->contacts['finance']['name'] }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="{{ $supplier->status === 'active' ? 'badge-success' : 'badge-danger' }}">{{ $supplier->status === 'active' ? 'Ativo' : 'Inativo' }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2" onclick="event.stopPropagation()">
                                        <button onclick="editEntity('suppliers', {{ $supplier->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-accent hover:text-white transition-all">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </button>
                                        <button onclick="deleteEntity('suppliers', {{ $supplier->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-rose-500 hover:text-white transition-all">
                                            <i class="fas fa-trash text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="py-10 text-center text-[10px] font-black uppercase tracking-widest italic text-slate-300">Nenhum fornecedor cadastrado</td></tr>
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
                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">Código</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">Nome</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($costCenters as $cc)
                            <tr class="hover:bg-slate-50/30 transition-all group cursor-pointer" onclick="editEntity('cost-centers', {{ $cc->id }})">
                                <td class="px-6 py-4 text-xs font-black text-accent italic">{{ $cc->code }}</td>
                                <td class="px-6 py-4 font-black text-primary-dark uppercase text-xs tracking-tight">{{ $cc->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="{{ $cc->is_active ? 'badge-success' : 'badge-danger' }}">{{ $cc->is_active ? 'Ativo' : 'Inativo' }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2" onclick="event.stopPropagation()">
                                        <button onclick="editEntity('cost-centers', {{ $cc->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-accent hover:text-white transition-all">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </button>
                                        <button onclick="deleteEntity('cost-centers', {{ $cc->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-rose-500 hover:text-white transition-all">
                                            <i class="fas fa-trash text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="py-10 text-center text-[10px] font-black uppercase tracking-widest italic text-slate-300">Nenhum centro de custo cadastrado</td></tr>
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
                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">Código</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">Nome do Banco</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">ISPB</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-primary-light uppercase tracking-widest text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($banks as $bank)
                            <tr class="hover:bg-slate-50/30 transition-all group cursor-pointer" onclick="editEntity('banks', {{ $bank->id }})">
                                <td class="px-6 py-4 text-xs font-black text-accent italic">{{ $bank->code ?? '---' }}</td>
                                <td class="px-6 py-4 font-black text-primary-dark uppercase text-xs tracking-tight">{{ $bank->name }}</td>
                                <td class="px-6 py-4 text-[10px] font-bold text-slate-500 font-mono">{{ $bank->ispb ?? '---' }}</td>
                                <td class="px-6 py-4">
                                    <span class="{{ $bank->is_active ? 'badge-success' : 'badge-danger' }}">{{ $bank->is_active ? 'Ativo' : 'Inativo' }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2" onclick="event.stopPropagation()">
                                        <button onclick="editEntity('banks', {{ $bank->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-accent hover:text-white transition-all">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </button>
                                        <button onclick="deleteEntity('banks', {{ $bank->id }})" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-rose-500 hover:text-white transition-all">
                                            <i class="fas fa-trash text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="py-10 text-center text-[10px] font-black uppercase tracking-widest italic text-slate-300">Nenhum banco cadastrado</td></tr>
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
    <div id="modal-supplier" class="fixed inset-0 z-[9999] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="card-neo w-full max-w-5xl max-h-[90vh] animate-reveal-up !p-0 overflow-hidden shadow-2xl flex flex-col">
            <!-- Header with Tabs -->
            <div class="bg-slate-50/50 border-b border-slate-100 shrink-0">
                <div class="p-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-black text-primary-dark uppercase tracking-tight flex items-center gap-2">
                            <i class="fas fa-truck-field text-accent"></i>
                            Ficha do Fornecedor
                        </h3>
                        <p class="text-[10px] text-primary-light font-bold uppercase tracking-widest mt-1">Gestão Avançada de Parceiros</p>
                    </div>
                    <button onclick="closeModal('modal-supplier')" class="w-10 h-10 rounded-xl flex items-center justify-center hover:bg-rose-50 text-slate-400 hover:text-rose-500 transition-all">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- Navigation Tabs -->
                <div class="flex px-6 gap-8">
                    <button type="button" onclick="switchSupplierTab('geral')" class="supplier-tab-btn active border-b-2 border-accent pb-3 text-xs font-bold uppercase tracking-widest text-accent">Dados Gerais</button>
                    <button type="button" onclick="switchSupplierTab('contatos')" class="supplier-tab-btn border-b-2 border-transparent pb-3 text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-primary-dark transition-all">Contatos Adicionais</button>
                    <button type="button" onclick="switchSupplierTab('historico')" class="supplier-tab-btn border-b-2 border-transparent pb-3 text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-primary-dark transition-all">Histórico & Negociações</button>
                </div>
            </div>
            
            <form id="form-supplier" class="flex-1 overflow-y-auto custom-scrollbar">
                <input type="hidden" name="id" id="supplier_id">
                
                <!-- Tab: Geral -->
                <div id="supplier-tab-geral" class="supplier-tab-content p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <!-- Identificação -->
                        <div class="md:col-span-12">
                            <h4 class="text-[12px] font-black text-primary-dark uppercase tracking-widest mb-4 flex items-center gap-3">
                                <span class="w-7 h-7 rounded bg-accent/10 text-accent flex items-center justify-center text-[11px]">01</span>
                                Identificação Jurídica
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                                <div class="md:col-span-4">
                                    <div class="flex justify-between items-center mb-1.5">
                                        <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light block">Documento</label>
                                        <div class="flex bg-slate-100 p-0.5 rounded-lg scale-90 origin-right">
                                            <button type="button" onclick="setDocType('cnpj')" id="btn-doc-cnpj" class="text-[9px] font-black uppercase px-2 py-1 rounded-md transition-all bg-white text-accent shadow-sm">CNPJ</button>
                                            <button type="button" onclick="setDocType('cpf')" id="btn-doc-cpf" class="text-[9px] font-black uppercase px-2 py-1 rounded-md transition-all text-slate-400">CPF</button>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <input type="text" name="document" id="supplier_document" class="input-neo font-bold !py-2.5 mask-cnpj w-full text-sm" required placeholder="00.000.000/0000-00">
                                        <input type="hidden" name="document_type" id="supplier_document_type" value="cnpj">
                                        <div id="cnpj-loader" class="hidden absolute right-3 top-1/2 -translate-y-1/2"><i class="fas fa-circle-notch fa-spin text-accent text-xs"></i></div>
                                    </div>
                                </div>
                                
                                <div class="md:col-span-8">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1.5 block">Razão Social (Nome Completo)</label>
                                    <input type="text" name="name" id="supplier_name" class="input-neo font-bold !py-2.5 w-full text-sm" required placeholder="Digite a razão social completa">
                                </div>

                                <div class="md:col-span-3">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1.5 block">Status do Registro</label>
                                    <select name="status" id="supplier_status_input" class="input-neo !py-2.5 w-full text-[11px] font-black uppercase text-accent cursor-pointer appearance-none">
                                        <option value="active">🟢 Ativo / Operante</option>
                                        <option value="inactive">🔴 Inativo / Bloqueado</option>
                                    </select>
                                </div>

                                <div class="md:col-span-9">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1.5 block">Nome Fantasia / Apelido</label>
                                    <input type="text" name="nickname" id="supplier_nickname" class="input-neo !py-2.5 w-full text-sm" placeholder="Nome como a empresa é conhecida popularmente">
                                </div>
                            </div>
                        </div>

                        <!-- Localização -->
                        <div class="md:col-span-12">
                            <h4 class="text-[12px] font-black text-primary-dark uppercase tracking-widest mb-4 flex items-center gap-3">
                                <span class="w-7 h-7 rounded bg-accent/10 text-accent flex items-center justify-center text-[11px]">02</span>
                                Localização e Sede
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                                <div class="md:col-span-3">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1.5 block">CEP</label>
                                    <input type="text" name="address[zip_code]" id="supplier_zip_code" class="input-neo font-bold !py-2.5 mask-cep w-full text-sm" placeholder="00000-000">
                                </div>
                                <div class="md:col-span-4">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1.5 block">Bairro</label>
                                    <input type="text" name="address[neighborhood]" id="supplier_neighborhood" class="input-neo !py-2.5 w-full text-sm" placeholder="Ex: Jardim Paulista">
                                </div>
                                <div class="md:col-span-5">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1.5 block">Cidade / UF</label>
                                    <input type="text" name="address[city]" id="supplier_city" class="input-neo !py-2.5 w-full text-sm" placeholder="Ex: São Paulo / SP">
                                </div>
                                <div class="md:col-span-12">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1.5 block">Logradouro / Rua (Completo)</label>
                                    <input type="text" name="address[street]" id="supplier_address" class="input-neo !py-2.5 w-full text-sm" placeholder="Rua, Avenida, Número, Complemento...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Contatos -->
                <div id="supplier-tab-contatos" class="supplier-tab-content hidden p-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="card-neo !shadow-none border-dashed bg-slate-50/30 p-6">
                            <h5 class="text-xs font-black text-primary-dark uppercase tracking-widest mb-4">Contato Financeiro</h5>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-[11px] font-bold text-primary-light uppercase block mb-1">Nome do Responsável</label>
                                    <input type="text" name="contacts[finance][name]" id="supplier_fin_name" class="input-neo" placeholder="Ex: João da Silva">
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold text-primary-light uppercase block mb-1">E-mail</label>
                                    <input type="email" name="contacts[finance][email]" id="supplier_fin_email" class="input-neo" placeholder="financeiro@fornecedor.com">
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold text-primary-light uppercase block mb-1">Telefone / WhatsApp</label>
                                    <input type="text" name="contacts[finance][phone]" id="supplier_fin_phone" class="input-neo mask-phone" placeholder="(00) 00000-0000">
                                </div>
                            </div>
                        </div>
                        <div class="card-neo !shadow-none border-dashed bg-slate-50/30 p-6">
                            <h5 class="text-xs font-black text-primary-dark uppercase tracking-widest mb-4">Contato Comercial / Vendas</h5>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-[11px] font-bold text-primary-light uppercase block mb-1">Consultor de Vendas</label>
                                    <input type="text" name="contacts[sales][name]" id="supplier_sales_name" class="input-neo" placeholder="Ex: Maria Oliveira">
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold text-primary-light uppercase block mb-1">E-mail Comercial</label>
                                    <input type="email" name="contacts[sales][email]" id="supplier_sales_email" class="input-neo" placeholder="vendas@fornecedor.com">
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold text-primary-light uppercase block mb-1">Telefone Direto</label>
                                    <input type="text" name="contacts[sales][phone]" id="supplier_sales_phone" class="input-neo mask-phone" placeholder="(00) 00000-0000">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Histórico -->
                <div id="supplier-tab-historico" class="supplier-tab-content hidden p-8 space-y-6">
                    <div>
                        <label class="text-[11px] font-bold text-primary-light uppercase block mb-2">Observações Internas / Histórico de Negociação</label>
                        <textarea name="notes" id="supplier_notes" rows="4" class="input-neo !py-4 text-sm" placeholder="Registre aqui detalhes importantes sobre este fornecedor..."></textarea>
                    </div>

                    <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl flex gap-4 items-center mb-6">
                        <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                        <p class="text-xs text-blue-700 font-medium">Este histórico é alimentado automaticamente pelas transações e observações registradas para este parceiro.</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="w-10 shrink-0 flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-accent/10 text-accent flex items-center justify-center text-xs ring-4 ring-white"><i class="fas fa-plus"></i></div>
                                <div class="flex-1 w-px bg-slate-200 my-2"></div>
                            </div>
                            <div class="flex-1 pb-6">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="text-xs font-black text-primary-dark uppercase tracking-tight">Cadastro Inicial</span>
                                    <span class="text-[10px] font-bold text-slate-400">13/05/2026 12:57</span>
                                </div>
                                <p class="text-xs text-slate-500 leading-relaxed italic">Fornecedor cadastrado no sistema via Painel de Configurações.</p>
                            </div>
                        </div>
                        
                        <!-- Timeline Placeholder -->
                        <div class="flex gap-4">
                            <div class="w-10 shrink-0 flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xs"><i class="fas fa-handshake"></i></div>
                            </div>
                            <div class="flex-1">
                                <span class="text-xs font-black text-slate-400 uppercase tracking-tight">Nenhuma negociação registrada recentemente</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-primary-light mb-2 block">Dica de Negociação</label>
                        <p class="text-[10px] text-slate-400 leading-relaxed italic">As anotações acima são persistidas diretamente no registro principal do fornecedor.</p>
                    </div>
                </div>
            </form>

            <div class="p-6 border-t border-slate-100 bg-slate-50/30 flex justify-end gap-4 shrink-0">
                <button type="button" onclick="closeModal('modal-supplier')" class="btn-neo uppercase tracking-widest text-[10px] !py-3 !px-8 hover:bg-slate-100 transition-all">Cancelar</button>
                <button type="submit" form="form-supplier" class="btn-neo btn-primary uppercase tracking-widest text-[11px] !py-3.5 !px-10 shadow-lg shadow-accent/20 transition-all">
                    <i class="fas fa-save"></i>
                    Salvar Ficha Completa
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Centro de Custo -->
    <div id="modal-cost-center" class="fixed inset-0 z-[9999] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="card-neo w-full max-w-lg max-h-[90vh] animate-reveal-up !p-0 overflow-hidden shadow-2xl flex flex-col">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                <div>
                    <h3 class="text-xl font-black text-primary-dark uppercase tracking-tight flex items-center gap-2">
                        <i class="fas fa-tags text-accent"></i>
                        Centro de Custo
                    </h3>
                    <p class="text-[10px] text-primary-light font-bold uppercase tracking-widest mt-1">Configuração / Orçamentário</p>
                </div>
                <button onclick="closeModal('modal-cost-center')" class="w-10 h-10 rounded-xl flex items-center justify-center hover:bg-rose-50 text-slate-400 hover:text-rose-500 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="form-cost-center" class="p-5 space-y-4 overflow-y-auto custom-scrollbar flex-1">
                <input type="hidden" name="id" id="cc_id">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1 block">Código</label>
                        <input type="text" name="code" id="cc_code" class="input-neo !py-2 text-sm" required placeholder="Ex: 01.001">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1 block">Nome do Centro de Custo</label>
                        <input type="text" name="name" id="cc_name" class="input-neo !py-2 text-sm" required placeholder="Ex: Secretaria Executiva">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1 block">Descrição (Opcional)</label>
                        <textarea name="description" id="cc_description" class="input-neo !py-2 text-sm h-20" placeholder="Finalidade deste centro..."></textarea>
                    </div>
                </div>
            </form>

            <div class="p-4 border-t border-slate-100 bg-slate-50/30 flex justify-end gap-3 shrink-0">
                <button type="button" onclick="closeModal('modal-cost-center')" class="btn-neo uppercase tracking-widest text-[10px] !py-2 !px-6 hover:bg-slate-100 transition-all">Cancelar</button>
                <button type="submit" form="form-cost-center" class="btn-neo btn-primary uppercase tracking-widest text-[10px] !py-2 !px-6 shadow-lg shadow-accent/20 transition-all">Salvar Centro</button>
            </div>
        </div>
    </div>

    <!-- Modal Banco -->
    <div id="modal-bank" class="fixed inset-0 z-[9999] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="card-neo w-full max-w-xl max-h-[90vh] animate-reveal-up !p-0 overflow-hidden shadow-2xl flex flex-col">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                <div>
                    <h3 class="text-xl font-black text-primary-dark uppercase tracking-tight flex items-center gap-2">
                        <i class="fas fa-university text-accent"></i>
                        Instituição Bancária
                    </h3>
                    <p class="text-[10px] text-primary-light font-bold uppercase tracking-widest mt-1">Parâmetros / Integração</p>
                </div>
                <button onclick="closeModal('modal-bank')" class="w-10 h-10 rounded-xl flex items-center justify-center hover:bg-rose-50 text-slate-400 hover:text-rose-500 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="form-bank" class="p-5 space-y-4 overflow-y-auto custom-scrollbar flex-1">
                <input type="hidden" name="id" id="bank_id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1 block">Nome da Instituição</label>
                        <input type="text" name="name" id="bank_name" class="input-neo !py-2 text-sm" required placeholder="Ex: Itaú Unibanco S.A.">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1 block">Código COMPE</label>
                        <input type="text" name="code" id="bank_code" class="input-neo !py-2 text-sm" placeholder="Ex: 341">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1 block">Código ISPB</label>
                        <input type="text" name="ispb" id="bank_ispb" class="input-neo !py-2 text-sm" placeholder="00000000">
                    </div>
                </div>
            </form>

            <div class="p-4 border-t border-slate-100 bg-slate-50/30 flex justify-end gap-3 shrink-0">
                <button type="button" onclick="closeModal('modal-bank')" class="btn-neo uppercase tracking-widest text-[10px] !py-2 !px-6 hover:bg-slate-100 transition-all">Cancelar</button>
                <button type="submit" form="form-bank" class="btn-neo btn-primary uppercase tracking-widest text-[10px] !py-2 !px-6 shadow-lg shadow-accent/20 transition-all">Salvar Banco</button>
            </div>
        </div>
    </div>

    <!-- Modal Unidade -->
    <div id="modal-units" class="fixed inset-0 z-[9999] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="card-neo w-full max-w-lg max-h-[90vh] animate-reveal-up !p-0 overflow-hidden shadow-2xl flex flex-col">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                <div>
                    <h3 class="text-xl font-black text-primary-dark uppercase tracking-tight flex items-center gap-2">
                        <i class="fas fa-church text-accent"></i>
                        Unidade / Campus
                    </h3>
                    <p class="text-[10px] text-primary-light font-bold uppercase tracking-widest mt-1">Hierarquia Organizacional</p>
                </div>
                <button onclick="closeModal('modal-units')" class="w-10 h-10 rounded-xl flex items-center justify-center hover:bg-rose-50 text-slate-400 hover:text-rose-500 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="form-units" class="p-5 space-y-4 overflow-y-auto custom-scrollbar flex-1">
                <input type="hidden" name="id" id="unit_id">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1 block">Nome da Unidade / Campus</label>
                        <input type="text" name="name" id="unit_name" class="input-neo !py-2 text-sm" required placeholder="Ex: Sede Principal">
                    </div>
                </div>
            </form>

            <div class="p-4 border-t border-slate-100 bg-slate-50/30 flex justify-end gap-3 shrink-0">
                <button type="button" onclick="closeModal('modal-units')" class="btn-neo uppercase tracking-widest text-[10px] !py-2 !px-6 hover:bg-slate-100 transition-all">Cancelar</button>
                <button type="submit" form="form-units" class="btn-neo btn-primary uppercase tracking-widest text-[10px] !py-2 !px-6 shadow-lg shadow-accent/20 transition-all">Salvar Unidade</button>
            </div>
        </div>
    </div>

    <!-- Modal Plano de Contas -->
    <div id="modal-chart-of-accounts" class="fixed inset-0 z-[9999] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="card-neo w-full max-w-lg max-h-[90vh] animate-reveal-up !p-0 overflow-hidden shadow-2xl flex flex-col">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                <div>
                    <h3 class="text-xl font-black text-primary-dark uppercase tracking-tight flex items-center gap-2">
                        <i class="fas fa-sitemap text-accent"></i>
                        Plano de Contas
                    </h3>
                    <p class="text-[10px] text-primary-light font-bold uppercase tracking-widest mt-1">Contábil / Estrutura</p>
                </div>
                <button onclick="closeModal('modal-chart-of-accounts')" class="w-10 h-10 rounded-xl flex items-center justify-center hover:bg-rose-50 text-slate-400 hover:text-rose-500 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="form-chart-of-accounts" class="p-8 space-y-6 overflow-y-auto custom-scrollbar flex-1">
                <input type="hidden" name="id" id="coa_id">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-primary-light mb-2 block">Código Classificador</label>
                        <input type="text" name="code" id="coa_code" class="input-neo" required placeholder="Ex: 1.01.01">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-primary-light mb-2 block">Tipo de Conta</label>
                        <select name="type" id="coa_type" class="input-neo" required>
                            <option value="revenue">Receita</option>
                            <option value="expense">Despesa</option>
                            <option value="asset">Ativo</option>
                            <option value="liability">Passivo</option>
                            <option value="equity">Patrimônio</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-primary-light mb-2 block">Nome da Conta / Descrição</label>
                        <input type="text" name="name" id="coa_name" class="input-neo" required placeholder="Ex: Dízimos e Ofertas">
                    </div>
                </div>
                <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-slate-50">
                    <button type="button" onclick="closeModal('modal-chart-of-accounts')" class="btn-neo uppercase tracking-widest text-[10px] !py-3 !px-8 hover:bg-slate-100 transition-all">Cancelar</button>
                    <button type="submit" class="btn-neo btn-primary uppercase tracking-widest text-[10px] !py-3 !px-12">
                        <i class="fas fa-save mr-2"></i> Salvar Conta
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Conta Financeira -->
    <div id="modal-accounts" class="fixed inset-0 z-[9999] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="card-neo w-full max-w-xl max-h-[90vh] animate-reveal-up !p-0 overflow-hidden shadow-2xl flex flex-col">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                <div>
                    <h3 class="text-xl font-black text-primary-dark uppercase tracking-tight flex items-center gap-2">
                        <i class="fas fa-wallet text-accent"></i>
                        Conta Financeira
                    </h3>
                    <p class="text-[10px] text-primary-light font-bold uppercase tracking-widest mt-1">Tesouraria / Disponibilidades</p>
                </div>
                <button onclick="closeModal('modal-accounts')" class="w-10 h-10 rounded-xl flex items-center justify-center hover:bg-rose-50 text-slate-400 hover:text-rose-500 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="form-accounts" class="p-5 space-y-4 overflow-y-auto custom-scrollbar flex-1">
                <input type="hidden" name="id" id="account_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1 block">Nome da Conta / Identificador</label>
                        <input type="text" name="name" id="account_name" class="input-neo !py-2 text-sm" required placeholder="Ex: Itaú - Movimentação">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1 block">Tipo de Conta</label>
                        <select name="type" id="account_type" class="input-neo !py-2 text-sm" required>
                            <option value="bank">Banco / Corrente</option>
                            <option value="cash">Caixa / Dinheiro</option>
                            <option value="investment">Investimento</option>
                        </select>
                    </div>
                    <div id="initial_balance_container">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1 block">Saldo Inicial (R$)</label>
                        <input type="text" name="initial_balance" id="account_initial_balance" class="input-neo !py-2 text-sm mask-money" placeholder="0,00">
                    </div>
                    
                    <div class="md:col-span-2 p-4 bg-slate-50/50 rounded-xl border border-slate-100 grid grid-cols-2 gap-3">
                        <div class="col-span-2">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1 block italic">Banco Vinculado (Opcional)</label>
                            <select name="bank_id" id="account_bank_id" class="input-neo !py-2 text-sm">
                                <option value="">Nenhum Banco</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1 block">Agência</label>
                            <input type="text" name="agency" id="account_agency" class="input-neo !py-2 text-sm" placeholder="0000">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-wider text-primary-light mb-1 block">Número Conta</label>
                            <input type="text" name="account_number" id="account_number" class="input-neo !py-2 text-sm" placeholder="00000-0">
                        </div>
                    </div>
                </div>
            </form>

            <div class="p-4 border-t border-slate-100 bg-slate-50/30 flex justify-end gap-3 shrink-0">
                <button type="button" onclick="closeModal('modal-accounts')" class="btn-neo uppercase tracking-widest text-[10px] !py-2 !px-6 hover:bg-slate-100 transition-all">Cancelar</button>
                <button type="submit" form="form-accounts" class="btn-neo btn-primary uppercase tracking-widest text-[10px] !py-2 !px-6 shadow-lg shadow-accent/20 transition-all">Salvar Conta</button>
            </div>
        </div>
    </div>
</div>

@endpush

@push('styles')
<style>
    .tab-btn {
        background: transparent;
        color: #94a3b8;
        border: 1px solid transparent;
    }

    .tab-btn.active {
        @apply bg-accent text-white shadow-lg shadow-accent/20 border-accent;
    }

    .tab-btn:hover:not(.active) {
        @apply bg-slate-50 text-primary-dark;
    }

    #settings-tabs::-webkit-scrollbar {
        height: 3px;
    }
    #settings-tabs::-webkit-scrollbar-thumb {
        @apply bg-slate-200 rounded-full;
    }
    
    .badge-success { @apply px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600; }
    .badge-danger { @apply px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest bg-rose-50 text-rose-600; }
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
