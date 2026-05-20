<x-app-layout>
    @section('title', 'Dashboard')

    <div class="space-y-8 animate-reveal-up" x-data="{ tab: 'users' }">

        {{-- ===== HEADER PRINCIPAL COM DATA ===== --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#f59e0b] animate-pulse inline-block"></span>
                    Administração Geral
                </p>
                <h1 class="text-3xl font-bold text-[#111827] tracking-tight uppercase">Dashboard</h1>
                <p class="text-slate-500 text-sm mt-1">Bem-vindo ao centro de controle do Boilerplate Core.</p>
            </div>
            <div class="flex items-center gap-2 text-slate-600 bg-white border border-gray-100 shadow-sm px-4 py-2.5 rounded-lg text-xs font-semibold">
                <span class="material-symbols-outlined text-[18px] text-[#f59e0b]">calendar_today</span>
                <span>Hoje, {{ now()->translatedFormat('d \d\e F Y') }}</span>
            </div>
        </div>

        {{-- ===== KPIs BENTO GRID (4 CARDS DE RESUMO) ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- KPI 1: Usuários Cadastrados -->
            <div class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-all group flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                        <span class="material-symbols-outlined text-[24px]">group</span>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full border border-blue-100">Contas</span>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Usuários Cadastrados</p>
                    <h2 class="text-3xl font-black text-[#111827] tracking-tighter mt-1">{{ number_format($totalUsers) }}</h2>
                </div>
            </div>

            <!-- KPI 2: Perfis de Acesso -->
            <div class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-all group flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-lg">
                        <span class="material-symbols-outlined text-[24px]">admin_panel_settings</span>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 bg-amber-50 text-amber-700 rounded-full border border-amber-100">Perfis</span>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Perfis de Acesso</p>
                    <h2 class="text-3xl font-black text-[#111827] tracking-tighter mt-1">{{ number_format($totalRoles) }}</h2>
                </div>
            </div>

            <!-- KPI 3: Permissões Mapeadas -->
            <div class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-all group flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-lg">
                        <span class="material-symbols-outlined text-[24px]">key</span>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 bg-purple-50 text-purple-700 rounded-full border border-purple-100">Chaves</span>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Permissões Mapeadas</p>
                    <h2 class="text-3xl font-black text-[#111827] tracking-tighter mt-1">{{ number_format($totalPermissions) }}</h2>
                </div>
            </div>

            <!-- KPI 4: Trilha de Auditoria (Logs) -->
            <div class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-all group flex flex-col justify-between border-l-4 border-[#f59e0b]">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg">
                        <span class="material-symbols-outlined text-[24px]">history</span>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full border border-emerald-100">Logs</span>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Logs Registrados</p>
                    <h2 class="text-3xl font-black text-[#111827] tracking-tighter mt-1">{{ number_format($totalLogs) }}</h2>
                </div>
            </div>
        </div>

        {{-- ===== BENTO CENTRAL GRID ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- BENTO MAIN: GRÁFICO DE EVOLUÇÃO MENSAL (Alpine.js) -->
            <div class="lg:col-span-2 bg-white p-8 rounded-lg border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-xl font-bold text-[#111827] tracking-tight">Atividade do Sistema</h3>
                        <p class="text-slate-500 text-xs mt-1">Histórico de mutação e evolução nos últimos 6 meses</p>
                    </div>
                    <div class="flex bg-slate-100 p-1 rounded-lg">
                        <button @click="tab = 'users'" :class="tab === 'users' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="text-xs px-3.5 py-1.5 rounded-md font-bold transition-all">
                            Contas
                        </button>
                        <button @click="tab = 'logs'" :class="tab === 'logs' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="text-xs px-3.5 py-1.5 rounded-md font-bold transition-all">
                            Auditoria
                        </button>
                    </div>
                </div>

                <!-- Evolução de Usuários -->
                <div x-show="tab === 'users'" class="h-64 flex items-end gap-2 relative transition-all duration-300" style="margin-bottom: 2rem;">
                    <div class="absolute bottom-0 left-0 w-full h-[1px] bg-slate-100"></div>
                    <div class="flex-grow flex items-end justify-between h-full group px-2 gap-4">
                        @php
                            $maxUser = $userGrowth->max() ?: 1;
                        @endphp
                        @foreach($userGrowth as $month => $val)
                        @php
                            $isLast = $loop->last;
                            $heightPct = ($val / $maxUser) * 85;
                        @endphp
                        <div class="flex-1 flex flex-col items-center justify-end h-full relative group/bar">
                            <!-- Label superior -->
                            <span class="absolute -top-6 font-bold text-xs text-slate-800 opacity-0 group-hover/bar:opacity-100 transition-opacity bg-slate-800 text-white px-2 py-0.5 rounded shadow-sm z-20">{{ $val }}</span>
                            <!-- Barra -->
                            <div class="w-full rounded-t-sm transition-all duration-300 {{ $isLast ? 'bg-[#f59e0b]' : 'bg-slate-100 group-hover/bar:bg-[#f59e0b]/40' }}" style="height: {{ $heightPct }}%"></div>
                            <!-- Nome do Mês -->
                            <div class="absolute -bottom-7 text-slate-500 text-xs font-semibold whitespace-nowrap">{{ $month }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Evolução de Logs -->
                <div x-show="tab === 'logs'" class="h-64 flex items-end gap-2 relative transition-all duration-300" style="margin-bottom: 2rem; display: none;">
                    <div class="absolute bottom-0 left-0 w-full h-[1px] bg-slate-100"></div>
                    <div class="flex-grow flex items-end justify-between h-full group px-2 gap-4">
                        @php
                            $maxLog = $logGrowth->max() ?: 1;
                        @endphp
                        @foreach($logGrowth as $month => $val)
                        @php
                            $isLast = $loop->last;
                            $heightPct = ($val / $maxLog) * 85;
                        @endphp
                        <div class="flex-1 flex flex-col items-center justify-end h-full relative group/bar">
                            <!-- Label superior -->
                            <span class="absolute -top-6 font-bold text-xs text-slate-800 opacity-0 group-hover/bar:opacity-100 transition-opacity bg-slate-800 text-white px-2 py-0.5 rounded shadow-sm z-20">{{ $val }}</span>
                            <!-- Barra -->
                            <div class="w-full rounded-t-sm transition-all duration-300 {{ $isLast ? 'bg-[#f59e0b]' : 'bg-slate-100 group-hover/bar:bg-[#f59e0b]/40' }}" style="height: {{ $heightPct }}%"></div>
                            <!-- Nome do Mês -->
                            <div class="absolute -bottom-7 text-slate-500 text-xs font-semibold whitespace-nowrap">{{ $month }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Linhas de contorno decorativas -->
                <div class="absolute inset-0 pointer-events-none opacity-[0.03] pt-24 px-8 pb-16">
                    <div class="h-full w-full border-b border-t border-primary flex flex-col justify-between">
                        <div class="w-full h-[1px] bg-primary"></div>
                        <div class="w-full h-[1px] bg-primary"></div>
                        <div class="w-full h-[1px] bg-primary"></div>
                    </div>
                </div>
            </div>

            <!-- CARD ASYMMETRIC LATERAL: STATUS E INFORMAÇÕES DO CORE -->
            <div class="bg-[#1c2434] p-6 rounded-lg shadow-lg flex flex-col justify-between text-white border border-[#2d3a54]">
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <span class="material-symbols-outlined text-[#f59e0b] text-[28px]" style="font-variation-settings: 'FILL' 1">settings_system_daydream</span>
                        <div>
                            <h3 class="text-lg font-bold text-white tracking-tight leading-tight">Status do Core</h3>
                            <p class="text-slate-400 text-[11px] mt-0.5 uppercase tracking-widest font-black">Ambiente e Variáveis</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-white/5 border border-white/10 p-4 rounded-lg flex items-center justify-between">
                            <div class="min-w-0 flex-1">
                                <p class="text-slate-400 text-xs font-semibold uppercase">Ambiente</p>
                                <p class="text-white font-bold text-sm mt-0.5">{{ app()->environment() }}</p>
                            </div>
                            <span class="text-xs font-bold px-2 py-1 bg-emerald-500/10 text-emerald-400 rounded border border-emerald-500/20">Online</span>
                        </div>

                        <div class="bg-white/5 border border-white/10 p-4 rounded-lg flex items-center justify-between">
                            <div class="min-w-0 flex-1">
                                <p class="text-slate-400 text-xs font-semibold uppercase">Laravel Versão</p>
                                <p class="text-white font-bold text-sm mt-0.5">v{{ app()->version() }}</p>
                            </div>
                        </div>

                        <div class="bg-white/5 border border-white/10 p-4 rounded-lg flex items-center justify-between">
                            <div class="min-w-0 flex-1">
                                <p class="text-slate-400 text-xs font-semibold uppercase">PHP Versão</p>
                                <p class="text-white font-bold text-sm mt-0.5">{{ phpversion() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <div class="p-4 bg-white/5 rounded-lg border border-dashed border-white/10 text-center">
                        <p class="text-slate-300 text-xs leading-relaxed">Sistema higienizado. Módulos eclesiásticos e financeiros foram removidos.</p>
                        <a href="{{ route('admin.users.index') }}" class="mt-3 block text-[#f59e0b] font-bold text-xs hover:underline uppercase tracking-widest">Gerenciar Usuários</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== RADAR DE ATIVIDADE RECENTE (TABELA DE LOGS) ===== --}}
        <div class="bg-white p-8 rounded-lg border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold text-[#111827] tracking-tight">Atividades Recentes</h3>
                    <p class="text-slate-500 text-xs mt-1">Trilha de auditoria forense do sistema em tempo real</p>
                </div>
                <a href="{{ route('admin.logs.index') }}" class="inline-block bg-slate-50 text-slate-600 hover:bg-[#f59e0b] hover:text-slate-900 border border-slate-200 px-5 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-full transition-all">
                    Ver Todos os Logs
                </a>
            </div>
            
            <div class="relative">
                <!-- Linha da Timeline -->
                <div class="absolute left-6 top-0 bottom-0 w-[2px] bg-slate-100"></div>
                <div class="space-y-8">
                    @forelse($recentLogs as $log)
                    <div class="relative flex items-start gap-6 group">
                        <div class="z-10 h-12 w-12 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shadow-sm border-4 border-white transition-transform group-hover:scale-105 shrink-0">
                            <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1">security</span>
                        </div>
                        <div class="flex-grow pt-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                <p class="text-slate-800 text-sm font-medium">
                                    O usuário <span class="font-bold text-slate-900">{{ $log->user->name ?? 'Sistema' }}</span> realizou a ação: <strong class="text-[#1c2434]">{{ $log->action }}</strong>
                                </p>
                                <span class="text-slate-400 text-xs font-semibold shrink-0">{{ $log->created_at ? $log->created_at->diffForHumans() : 'Agora' }}</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-2">
                                <span class="text-xs text-slate-500 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px] text-slate-400">devices</span> IP: <strong class="text-slate-700">{{ $log->ip_address }}</strong>
                                </span>
                                @if($log->user)
                                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-blue-50 text-blue-600 border border-blue-100">
                                    {{ $log->user->role }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center bg-slate-50 rounded-lg border border-dashed border-slate-200">
                        <span class="material-symbols-outlined text-slate-400 text-[36px] mb-2">article</span>
                        <p class="text-slate-500 text-sm font-medium">Nenhum log de auditoria recente registrado.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ===== FOOTER SISTEMA ===== --}}
        <footer class="py-8 px-6 border-t border-gray-100 text-slate-400 text-xs text-center">
            © {{ date('Y') }} Boilerplate Core. Painel de Controle de Alta Performance.
        </footer>

    </div>
</x-app-layout>
