<x-app-layout>
    @section('title', 'Dashboard')

    <div class="space-y-10">
        <!-- STATS CARDS WITH REVEAL -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Total de Membros -->
            <div class="stats-card group animate-reveal-up">
                <div class="stats-icon bg-blue-50 text-blue-600 ring-8 ring-blue-50/50">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <p class="text-primary-light text-[10px] font-bold uppercase tracking-widest mb-1">Total de Membros</p>
                    <p class="text-3xl font-black text-primary-dark tracking-tighter">1.248</p>
                </div>
            </div>

            <!-- Células Ativas -->
            <div class="stats-card group animate-reveal-up delay-100">
                <div class="stats-icon bg-green-50 text-green-600 ring-8 ring-green-50/50">
                    <i class="fas fa-home"></i>
                </div>
                <div>
                    <p class="text-primary-light text-[10px] font-bold uppercase tracking-widest mb-1">Células Ativas</p>
                    <p class="text-3xl font-black text-primary-dark tracking-tighter">42</p>
                </div>
            </div>

            <!-- Arrecadação Mensal -->
            <div class="stats-card group animate-reveal-up delay-200">
                <div class="stats-icon bg-amber-50 text-amber-600 ring-8 ring-amber-50/50">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div>
                    <p class="text-primary-light text-[10px] font-bold uppercase tracking-widest mb-1">Arrecadação Mensal</p>
                    <p class="text-3xl font-black text-primary-dark tracking-tighter">R$ 45.2k</p>
                </div>
            </div>

            <!-- Novos este Mês -->
            <div class="stats-card group animate-reveal-up delay-300">
                <div class="stats-icon bg-purple-50 text-purple-600 ring-8 ring-purple-50/50">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <p class="text-primary-light text-[10px] font-bold uppercase tracking-widest mb-1">Novos este Mês</p>
                    <p class="text-3xl font-black text-primary-dark tracking-tighter">+84</p>
                </div>
            </div>
        </div>

        <!-- RECENT ACTIVITY AREA -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 animate-reveal-up delay-300">
            <!-- DATA TABLE -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center justify-between px-2">
                    <h3 class="text-2xl font-black text-primary-dark tracking-tight">Atividades Recentes</h3>
                    <a href="{{ route('reports.index') }}" class="btn-neo bg-gray-100 text-primary-dark hover:bg-accent hover:text-white px-4 py-2">
                        Ver Todos <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <div class="card-neo !p-0 overflow-hidden border-none shadow-xl">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50/80 backdrop-blur-md border-b border-gray-100">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black text-primary-light uppercase tracking-[0.2em]">Célula</th>
                                <th class="px-8 py-5 text-[10px] font-black text-primary-light uppercase tracking-[0.2em]">Líder</th>
                                <th class="px-8 py-5 text-[10px] font-black text-primary-light uppercase tracking-[0.2em] text-center">Membros</th>
                                <th class="px-8 py-5 text-[10px] font-black text-primary-light uppercase tracking-[0.2em]">Status</th>
                                <th class="px-8 py-5 text-[10px] font-black text-primary-light uppercase tracking-[0.2em] text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach([1,2,3,4,5] as $i)
                            <tr class="hover:bg-gray-50/80 transition-all duration-300 group/row">
                                <td class="px-8 py-6">
                                    <p class="text-sm font-bold text-primary-dark group-hover/row:text-accent transition-colors">Célula Shalom {{ $i }}</p>
                                    <p class="text-[10px] text-primary-light font-medium uppercase mt-1">Sexta-feira • 20:00</p>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-[10px] font-black text-primary-dark border border-gray-200 transition-transform group-hover/row:scale-110">
                                            FL
                                        </div>
                                        <span class="text-sm font-bold text-primary-dark">Fernando Lima</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-50 rounded-lg border border-gray-100">
                                        <span class="text-xs font-black text-primary-dark">12</span>
                                        <span class="text-[10px] text-primary-light font-bold">/ 15</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="badge-success">Lido</span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <button class="w-10 h-10 rounded-xl bg-gray-50 text-primary-light hover:bg-accent hover:text-white transition-all shadow-sm hover:shadow-lg hover:shadow-accent/30">
                                        <i class="fas fa-arrow-right text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SIDE ACTION CARDS -->
            <div class="space-y-8">
                <h3 class="text-2xl font-black text-primary-dark tracking-tight px-2">Ações Rápidas</h3>
                
                <div class="grid grid-cols-1 gap-6">
                    <a href="{{ route('members.create') }}" class="card-neo !p-5 flex items-center gap-5 group border-none shadow-lg">
                        <div class="w-14 h-14 rounded-2xl bg-accent/10 flex items-center justify-center text-accent group-hover:bg-accent group-hover:text-white transition-all duration-500 shadow-sm group-hover:shadow-accent/40 group-hover:rotate-6">
                            <i class="fas fa-user-plus text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black text-primary-dark group-hover:text-accent transition-colors">Novo Membro</p>
                            <p class="text-[10px] text-primary-light font-bold uppercase tracking-widest mt-1">Cadastro Ministerial</p>
                        </div>
                    </a>

                    <a href="{{ route('reports.create') }}" class="card-neo !p-5 flex items-center gap-5 group border-none shadow-lg">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500 shadow-sm group-hover:shadow-blue-600/40 group-hover:-rotate-6">
                            <i class="fas fa-file-signature text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black text-primary-dark group-hover:text-blue-600 transition-colors">Lançar Relatório</p>
                            <p class="text-[10px] text-primary-light font-bold uppercase tracking-widest mt-1">Reunião de Célula</p>
                        </div>
                    </a>
                </div>

                <!-- PREMIUM SUPPORT CARD -->
                <div class="bg-gradient-to-br from-primary to-[#2d3a54] rounded-[2rem] p-10 text-white relative overflow-hidden group shadow-2xl">
                    <div class="absolute -right-10 -bottom-10 opacity-10 group-hover:scale-125 group-hover:-rotate-12 transition-transform duration-700 pointer-events-none">
                        <i class="fas fa-church text-[12rem]"></i>
                    </div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center mb-6 border border-white/20">
                            <i class="fas fa-headset text-accent"></i>
                        </div>
                        <h4 class="text-2xl font-black mb-3 tracking-tight text-white">Suporte Elite</h4>
                        <p class="text-sm text-slate-200 font-medium leading-relaxed mb-8">Experiência tecnológica de alta performance para sua gestão ministerial.</p>
                        <button class="w-full btn-neo btn-primary !py-4 shadow-2xl">
                            Abrir Chamado <i class="fas fa-external-link-alt text-[10px]"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
