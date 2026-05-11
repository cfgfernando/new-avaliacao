<x-app-layout>
    @section('header_title', 'Dashboard Administrativo')

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10 animate-fade">
        <!-- Card: Membros -->
        <div class="card-elite p-8 group">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center icon-box-blue group-hover:scale-110 transition-transform">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <span class="text-[10px] font-black text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full border border-blue-100">+12% mês</span>
            </div>
            <div>
                <h3 class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Total de Membros</h3>
                <p class="text-4xl font-black text-title tracking-tighter">1.240</p>
            </div>
        </div>

        <!-- Card: Células -->
        <div class="card-elite p-8 group">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center icon-box-orange group-hover:scale-110 transition-transform">
                    <i class="fas fa-house-chimney text-2xl"></i>
                </div>
                <span class="text-[10px] font-black text-amber-600 bg-amber-50 px-3 py-1.5 rounded-full border border-amber-100">Ativas</span>
            </div>
            <div>
                <h3 class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Células Ativas</h3>
                <p class="text-4xl font-black text-title tracking-tighter">120</p>
            </div>
        </div>

        <!-- Card: Conversões -->
        <div class="card-elite p-8 group">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center icon-box-green group-hover:scale-110 transition-transform">
                    <i class="fas fa-heart text-2xl"></i>
                </div>
                <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-100">Este Ano</span>
            </div>
            <div>
                <h3 class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Novas Conversões</h3>
                <p class="text-4xl font-black text-title tracking-tighter">84</p>
            </div>
        </div>

        <!-- Card: Saldo -->
        <div class="card-elite p-8 group">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center icon-box-purple group-hover:scale-110 transition-transform">
                    <i class="fas fa-sack-dollar text-2xl"></i>
                </div>
                <span class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-full border border-indigo-100">Consolidado</span>
            </div>
            <div>
                <h3 class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Saldo em Caixa</h3>
                <p class="text-4xl font-black text-title tracking-tighter">R$ 42.500</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade" style="animation-delay: 0.15s">
        <!-- Relatórios Recentes -->
        <div class="lg:col-span-2 card-elite overflow-hidden">
            <div class="p-8 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50/50 dark:bg-black/20">
                <h3 class="text-[11px] font-black text-title uppercase tracking-[0.2em]">Últimos Relatórios de Célula</h3>
                <a href="{{ route('reports.index') }}" class="btn-primary !py-2 !px-4 !rounded-xl">
                    <span class="text-[9px]">Ver Todos</span>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-100/50 dark:bg-black/40 text-gray-500 dark:text-gray-400 text-[10px] uppercase font-black tracking-widest">
                        <tr>
                            <th class="px-8 py-5">Célula</th>
                            <th class="px-8 py-5">Líder</th>
                            <th class="px-8 py-5 text-center">Status</th>
                            <th class="px-8 py-5 text-right">Oferta</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-8 py-6 font-bold text-title">Célula Shalom</td>
                            <td class="px-8 py-6 text-sm text-body font-medium">Ricardo Silva</td>
                            <td class="px-8 py-6 text-center">
                                <span class="px-4 py-1.5 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase rounded-full border border-emerald-200">Conciliado</span>
                            </td>
                            <td class="px-8 py-6 text-right font-black text-title">R$ 450,00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="space-y-6">
            <div class="card-elite p-8">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-8">Ações Estratégicas</h3>
                <div class="grid grid-cols-1 gap-4">
                    <a href="{{ route('reports.create') }}" class="btn-primary">
                        <i class="fas fa-plus-circle text-lg"></i>
                        <span>Novo Relatório</span>
                    </a>
                    <a href="{{ route('members.create') }}" class="bg-white hover:bg-gray-50 text-title p-5 rounded-2xl font-black flex items-center justify-center gap-3 transition-all border-2 border-gray-100 text-[10px] uppercase tracking-widest shadow-sm">
                        <i class="fas fa-user-plus text-lg text-amber-500"></i>
                        Cadastrar Membro
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
