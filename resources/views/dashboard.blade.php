<x-app-layout>
    @section('header_title', 'Dashboard Administrativo')

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
        <!-- Card: Membros -->
        <div class="card-neo p-12 bg-primary-card">
            <div class="flex items-center justify-between mb-10">
                <div class="text-neutral-500">
                    <i class="fas fa-users text-3xl"></i>
                </div>
                <div class="h-[1px] w-8 bg-accent"></div>
            </div>
            <div>
                <h3 class="text-neutral-600 text-[9px] font-black uppercase tracking-[0.3em] mb-4">Membros Ativos</h3>
                <p class="text-6xl font-black text-white tracking-tighter leading-none">1.2k</p>
                <div class="mt-8 text-[9px] font-black text-accent uppercase tracking-widest">+12.4% Δ</div>
            </div>
        </div>

        <!-- Card: Células -->
        <div class="card-neo p-12 bg-primary-card">
            <div class="flex items-center justify-between mb-10">
                <div class="text-neutral-500">
                    <i class="fas fa-house-chimney text-3xl"></i>
                </div>
                <div class="h-[1px] w-8 bg-accent"></div>
            </div>
            <div>
                <h3 class="text-neutral-600 text-[9px] font-black uppercase tracking-[0.3em] mb-4">Unidades Célula</h3>
                <p class="text-6xl font-black text-white tracking-tighter leading-none">120</p>
                <div class="mt-8 text-[9px] font-black text-white uppercase tracking-widest">ESTÁVEL</div>
            </div>
        </div>

        <!-- Card: Conversões -->
        <div class="card-neo p-12 bg-primary-card border-t-2 border-accent">
            <div class="flex items-center justify-between mb-10">
                <div class="text-accent">
                    <i class="fas fa-bolt text-3xl"></i>
                </div>
                <div class="h-[1px] w-8 bg-accent"></div>
            </div>
            <div>
                <h3 class="text-neutral-600 text-[9px] font-black uppercase tracking-[0.3em] mb-4">Novas Conversões</h3>
                <p class="text-6xl font-black text-accent tracking-tighter leading-none">84</p>
                <div class="mt-8 text-[9px] font-black text-neutral-500 uppercase tracking-widest">YTD 2026</div>
            </div>
        </div>

        <!-- Card: Saldo -->
        <div class="card-neo p-12 bg-white">
            <div class="flex items-center justify-between mb-10">
                <div class="text-neutral-900">
                    <i class="fas fa-sack-dollar text-3xl"></i>
                </div>
                <div class="h-[1px] w-8 bg-neutral-900"></div>
            </div>
            <div>
                <h3 class="text-neutral-400 text-[9px] font-black uppercase tracking-[0.3em] mb-4">Consolidação Caixa</h3>
                <p class="text-5xl font-black text-neutral-900 tracking-tighter leading-none">42.5k</p>
                <div class="mt-8 text-[9px] font-black text-neutral-400 uppercase tracking-widest">BRL TOTAL</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Relatórios Recentes -->
        <div class="lg:col-span-2 card-neo bg-primary-card overflow-hidden">
            <div class="p-12 border-b border-white/5 flex justify-between items-center">
                <h3 class="text-[11px] font-black text-white uppercase tracking-[0.3em]">Fluxo de Malotes Recentes</h3>
                <a href="{{ route('reports.index') }}" class="text-[10px] font-black text-accent uppercase tracking-widest hover:underline">
                    Ver Todos
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-black/20 text-neutral-600 text-[9px] uppercase font-black tracking-widest">
                        <tr>
                            <th class="px-12 py-6">Unidade</th>
                            <th class="px-12 py-6">Liderança</th>
                            <th class="px-12 py-6 text-center">Status</th>
                            <th class="px-12 py-6 text-right">Valor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-12 py-8 font-black text-white text-xs uppercase tracking-widest">Célula Shalom</td>
                            <td class="px-12 py-8 text-[10px] text-neutral-500 font-bold uppercase tracking-widest">Ricardo Silva</td>
                            <td class="px-12 py-8 text-center">
                                <span class="px-4 py-2 border border-accent text-accent text-[9px] font-black uppercase tracking-widest">CONCILIADO</span>
                            </td>
                            <td class="px-12 py-8 text-right font-black text-emerald-500 text-sm">450.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="space-y-8">
            <div class="card-neo p-12 bg-primary-card">
                <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em] mb-10">Operações</h3>
                <div class="grid grid-cols-1 gap-6">
                    <a href="{{ route('reports.create') }}" class="btn-neo text-center">
                        Novo Relatório
                    </a>
                    <a href="{{ route('members.create') }}" class="btn-neo bg-white text-black border-white hover:bg-neutral-200 hover:text-black text-center">
                        Novo Registro
                    </a>
                </div>
            </div>

            <div class="card-neo p-12 bg-neutral-900 border-l-2 border-accent">
                <p class="text-[10px] font-black text-accent uppercase tracking-widest mb-4">Nota do Sistema</p>
                <p class="text-neutral-500 text-xs leading-relaxed font-medium">Ambiente configurado para Alta Performance. Todas as transações são auditadas em tempo real.</p>
            </div>
        </div>
    </div>
</x-app-layout>
