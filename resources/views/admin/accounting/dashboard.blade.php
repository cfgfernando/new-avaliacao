@extends('layouts.app')

@section('title', 'Dashboard Contábil')

@section('content')
<div class="space-y-8 animate-reveal-up">
    <!-- Header Elite V8 -->
    <div class="flex justify-between items-end mb-8 no-print">
        <div>
            <h1 class="text-xl font-black text-slate-800 uppercase tracking-tight">Inteligência Contábil</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Gestão de Patrimônio, Resultados e Saúde Financeira</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('admin.accounting.reports.trial-balance') }}" class="btn-neo bg-white text-slate-600 text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 border-slate-100 shadow-sm">
                <i class="fas fa-print"></i>
                <span>Balancete</span>
            </a>
            <button type="button" onclick="openManualEntryModal()" class="btn-neo bg-accent text-white text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 shadow-lg shadow-accent/20">
                <i class="fas fa-plus"></i>
                <span>Lançamento Avulso</span>
            </button>
        </div>
    </div>

    <!-- Cards Contábeis Elite V8 -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- ATIVO -->
        <div class="card-neo group overflow-hidden">
            <div class="absolute top-0 right-0 w-1.5 h-full bg-emerald-500/20 group-hover:bg-emerald-500 transition-colors"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Ativo</p>
                    <h3 class="text-2xl font-bold text-slate-800 font-money tracking-tight">R$ {{ number_format($assets, 2, ',', '.') }}</h3>
                    <p class="text-[9px] font-bold text-emerald-500 mt-2 uppercase tracking-widest">Bens e Direitos</p>
                </div>
                <div class="stats-icon bg-emerald-50 text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white">
                    <i class="fas fa-vault"></i>
                </div>
            </div>
        </div>

        <!-- PASSIVO -->
        <div class="card-neo group overflow-hidden">
            <div class="absolute top-0 right-0 w-1.5 h-full bg-rose-500/20 group-hover:bg-rose-500 transition-colors"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Passivo</p>
                    <h3 class="text-2xl font-bold text-slate-800 font-money tracking-tight">R$ {{ number_format($liabilities, 2, ',', '.') }}</h3>
                    <p class="text-[9px] font-bold text-rose-500 mt-2 uppercase tracking-widest">Obrigações</p>
                </div>
                <div class="stats-icon bg-rose-50 text-rose-500 group-hover:bg-rose-500 group-hover:text-white">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
            </div>
        </div>

        <!-- PATRIMÔNIO LÍQUIDO -->
        <div class="card-neo group overflow-hidden">
            <div class="absolute top-0 right-0 w-1.5 h-full bg-slate-800/10 group-hover:bg-accent-dark transition-colors"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Patrimônio Líquido</p>
                    <h3 class="text-2xl font-bold text-slate-800 font-money tracking-tight">R$ {{ number_format($equity, 2, ',', '.') }}</h3>
                    <p class="text-[9px] font-bold text-slate-800 mt-2 uppercase tracking-widest">Capital Próprio</p>
                </div>
                <div class="stats-icon bg-slate-50 text-slate-400 group-hover:bg-accent-dark group-hover:text-white">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>

        <!-- RESULTADO -->
        <div class="card-neo group overflow-hidden">
            <div class="absolute top-0 right-0 w-1.5 h-full {{ $netIncome >= 0 ? 'bg-emerald-500/20' : 'bg-rose-500/20' }} group-hover:{{ $netIncome >= 0 ? 'bg-emerald-500' : 'bg-rose-500' }} transition-colors"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Resultado Líquido</p>
                    <h3 class="text-2xl font-black {{ $netIncome >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-money tracking-tight">R$ {{ number_format($netIncome, 2, ',', '.') }}</h3>
                    <p class="text-[9px] font-bold {{ $netIncome >= 0 ? 'text-emerald-500' : 'text-rose-500' }} mt-2 uppercase tracking-widest">{{ $netIncome >= 0 ? 'Superávit' : 'Déficit' }}</p>
                </div>
                <div class="stats-icon {{ $netIncome >= 0 ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-500' }} group-hover:{{ $netIncome >= 0 ? 'bg-emerald-500' : 'bg-rose-500' }} group-hover:text-white">
                    <i class="fas {{ $netIncome >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção de Análise e Lançamentos Elite V8 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Últimos Lançamentos (Diário) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex justify-between items-center px-1">
                <h3 class="text-xs font-bold text-slate-600 uppercase tracking-widest">Diário Contábil Recente</h3>
                <a href="{{ route('admin.accounting.journal.index') }}" class="text-[10px] font-bold text-primary-light border-b border-primary-light hover:text-accent hover:border-accent transition-all uppercase tracking-widest">Acessar Histórico Completo</a>
            </div>
            
            <div class="card-neo !p-0 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Data / Ref</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Descrição Analítica</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Montante (R$)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recentEntries as $entry)
                        <tr class="group hover:bg-slate-50/50 transition-all cursor-pointer">
                            <td class="px-8 py-5">
                                <span class="text-xs font-bold text-slate-700 block">{{ $entry->date->format('d/m/Y') }}</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 block">{{ $entry->reference }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-sm font-bold text-slate-700 block mb-3 group-hover:text-accent transition-colors">{{ $entry->description }}</span>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($entry->items->take(3) as $item)
                                    <div class="flex items-center gap-2 bg-slate-50 px-2 py-1 rounded-lg border border-slate-100">
                                        <span class="px-1.5 py-0.5 {{ $item->type === 'debit' ? 'bg-accent text-white' : 'bg-rose-100 text-rose-600' }} rounded text-[8px] font-black uppercase">
                                            {{ $item->type === 'debit' ? 'D' : 'C' }}
                                        </span>
                                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">{{ $item->chartOfAccount->name }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right font-money text-base font-bold text-slate-800 bg-slate-50/30">
                                R$ {{ number_format($entry->items->where('type', 'debit')->sum('amount'), 2, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-8 py-20 text-center text-slate-400">
                                <i class="fas fa-file-signature text-5xl mb-4 opacity-20"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest">Nenhuma escrituração recente encontrada.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Indicadores Rápidos Elite V8 -->
        <div class="space-y-6">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest px-1">Performance Estratégica</h3>
            
            <div class="space-y-6">
                <!-- Liquidez Corrente -->
                <div class="card-neo group overflow-hidden">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Liquidez Corrente</span>
                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-500 border border-emerald-100 rounded text-[8px] font-black uppercase">SAUDÁVEL</span>
                    </div>
                    <div class="flex items-baseline gap-2 mb-4">
                        <span class="text-3xl font-black text-slate-800 font-money tracking-tight">2.45</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">(A / P)</span>
                    </div>
                    <div class="w-full bg-slate-50 h-2 rounded-full overflow-hidden border border-slate-100">
                        <div class="bg-accent h-full w-[75%] rounded-full"></div>
                    </div>
                </div>

                <!-- Margem Líquida -->
                <div class="card-neo">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Margem de Sobra</span>
                        <span class="text-xs font-bold text-slate-800 font-money">15.4%</span>
                    </div>
                    <div class="w-full bg-slate-50 h-2 rounded-full overflow-hidden mb-3 border border-slate-100">
                        <div class="bg-accent h-full w-[15.4%] rounded-full"></div>
                    </div>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest leading-relaxed">Excedente financeiro após deduções operacionais.</p>
                </div>

                <!-- Alerta Contábil -->
                <div class="card-neo !bg-amber-50/50 border-amber-100 flex gap-4 items-center">
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 shrink-0 border border-amber-100 shadow-sm">
                        <i class="fas fa-triangle-exclamation text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-[11px] font-black text-amber-600 uppercase tracking-tight">Conciliação Pendente</h4>
                        <p class="text-[9px] text-amber-600/70 font-bold mt-0.5 leading-tight uppercase tracking-widest">12 transações aguardando vínculo contábil para auditoria.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('modals')
<!-- Modal Elite V8 -->
<div id="manualEntryModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[9999] flex items-center justify-center p-4 sm:p-6 no-print overflow-y-auto custom-scrollbar">
    <div class="bg-white w-full max-w-3xl m-auto animate-reveal-up overflow-hidden shadow-2xl border border-slate-100 rounded-[2.5rem] flex flex-col">
        
        <!-- Header -->
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-accent flex items-center justify-center text-white shadow-lg">
                    <i class="fas fa-file-signature text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Lançamento Contábil</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Voucher de Partidas Dobradas</p>
                </div>
            </div>
            <button onclick="closeManualEntryModal()" class="w-10 h-10 rounded-xl flex items-center justify-center bg-slate-100 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <div class="overflow-y-auto flex-grow bg-white">
            <form id="manualEntryForm" class="p-8 space-y-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 px-1">Data da Escrituração</label>
                        <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="input-neo !py-4 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 px-1">Histórico / Descrição Técnica</label>
                        <input type="text" name="description" required placeholder="Ex: Ajuste de saldo referente..." class="input-neo !py-4 text-sm">
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center px-1 border-b border-slate-100 pb-4">
                        <h4 class="text-[10px] font-bold text-slate-600 uppercase tracking-widest flex items-center gap-2">
                            <i class="fas fa-list-ul text-accent"></i>
                            Composição de Partidas
                        </h4>
                        <button type="button" onclick="addEntryLine()" class="px-4 py-2 bg-slate-50 text-slate-600 text-[9px] font-bold uppercase tracking-widest rounded-lg border border-slate-100 hover:bg-slate-100 transition-all">
                            <i class="fas fa-plus-circle mr-2"></i> Adicionar Item
                        </button>
                    </div>

                    <div class="overflow-hidden rounded-2xl border border-slate-100 shadow-sm">
                        <table class="w-full text-left border-collapse" id="entryTable">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="px-6 py-4 text-[9px] font-bold text-slate-400 uppercase tracking-widest w-40">Natureza</th>
                                    <th class="px-6 py-4 text-[9px] font-bold text-slate-400 uppercase tracking-widest">Conta Contábil</th>
                                    <th class="px-6 py-4 text-[9px] font-bold text-slate-400 uppercase tracking-widest text-right w-40">Valor (R$)</th>
                                    <th class="px-6 py-4 w-12"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50" id="entryLines">
                                <tr class="entry-line">
                                    <td class="px-6 py-4">
                                        <select name="items[0][type]" class="input-neo !py-1 text-[10px] border-emerald-100 bg-emerald-50 text-emerald-600 font-bold uppercase">
                                            <option value="debit">Débito (D)</option>
                                            <option value="credit">Crédito (C)</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4">
                                        <select name="items[0][chart_of_account_id]" required class="input-neo !py-1 text-[10px] font-bold uppercase">
                                            <option value="">Selecione...</option>
                                            @foreach(\App\Models\Finance\ChartOfAccount::where('is_active', true)->orderBy('code')->get() as $coa)
                                                <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <input type="number" step="0.01" name="items[0][amount]" required class="input-neo !py-1 text-right font-money text-xs amount-input" placeholder="0,00">
                                    </td>
                                    <td class="px-6 py-4"></td>
                                </tr>
                                <tr class="entry-line bg-slate-50/30">
                                    <td class="px-6 py-4">
                                        <select name="items[1][type]" class="input-neo !py-1 text-[10px] border-rose-100 bg-rose-50 text-rose-600 font-bold uppercase">
                                            <option value="credit">Crédito (C)</option>
                                            <option value="debit">Débito (D)</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4">
                                        <select name="items[1][chart_of_account_id]" required class="input-neo !py-1 text-[10px] font-bold uppercase">
                                            <option value="">Selecione...</option>
                                            @foreach(\App\Models\Finance\ChartOfAccount::where('is_active', true)->orderBy('code')->get() as $coa)
                                                <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <input type="number" step="0.01" name="items[1][amount]" required class="input-neo !py-1 text-right font-money text-xs amount-input" placeholder="0,00">
                                    </td>
                                    <td class="px-6 py-4"></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="bg-slate-50/80 border-t border-slate-100">
                                    <td colspan="2" class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-500 text-right">Diferença de Partida</td>
                                    <td class="px-8 py-5 text-right font-money text-base font-black" id="balanceDiff">R$ 0,00</td>
                                    <td class="bg-slate-50"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </form>
        </div>

        <div class="p-8 border-t border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
            <button type="button" onclick="closeManualEntryModal()" class="px-8 py-3.5 border border-slate-200 rounded-xl text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:bg-white transition-all">Descartar</button>
            <button type="submit" form="manualEntryForm" class="px-10 py-4 bg-accent text-white rounded-2xl text-[10px] font-bold uppercase tracking-widest flex items-center gap-3 shadow-lg shadow-accent/20 hover:bg-accent-hover transition-all">
                <i class="fas fa-check-circle"></i>
                <span>Efetivar Escrituração</span>
            </button>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    let lineCount = 2;

    function openManualEntryModal() {
        $('#manualEntryModal').removeClass('hidden').addClass('flex');
        $('body').addClass('overflow-hidden');
    }

    function closeManualEntryModal() {
        $('#manualEntryModal').addClass('hidden').removeClass('flex');
        $('body').removeClass('overflow-hidden');
    }

    function addEntryLine() {
        const line = `
            <tr class="entry-line animate-reveal-up bg-white">
                <td class="px-6 py-4">
                    <select name="items[${lineCount}][type]" class="input-neo !py-1 text-[10px] uppercase">
                        <option value="debit">Débito (D)</option>
                        <option value="credit">Crédito (C)</option>
                    </select>
                </td>
                <td class="px-6 py-4">
                    <select name="items[${lineCount}][chart_of_account_id]" required class="input-neo !py-1 text-[10px] uppercase">
                        <option value="">Selecione...</option>
                        @foreach(\App\Models\Finance\ChartOfAccount::where('is_active', true)->orderBy('code')->get() as $coa)
                            <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="px-6 py-4 text-right">
                    <input type="number" step="0.01" name="items[${lineCount}][amount]" required class="input-neo !py-1 text-right font-money text-xs amount-input" placeholder="0,00">
                </td>
                <td class="px-6 py-4 text-center">
                    <button type="button" onclick="$(this).closest('tr').remove(); updateBalance();" class="text-rose-400 hover:text-rose-600 transition-colors">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#entryLines').append(line);
        lineCount++;
    }

    function updateBalance() {
        let totalDebit = 0;
        let totalCredit = 0;

        $('.entry-line').each(function() {
            const type = $(this).find('select[name$="[type]"]').val();
            const amount = parseFloat($(this).find('.amount-input').val()) || 0;

            if (type === 'debit') totalDebit += amount;
            else totalCredit += amount;
        });

        const diff = totalDebit - totalCredit;
        const diffElement = $('#balanceDiff');
        
        diffElement.text('R$ ' + Math.abs(diff).toLocaleString('pt-BR', {minimumFractionDigits: 2}));
        
        if (Math.abs(diff) < 0.01) {
            diffElement.removeClass('text-rose-500').addClass('text-emerald-500');
        } else {
            diffElement.removeClass('text-emerald-500').addClass('text-rose-500');
        }
    }

    $(document).on('input', '.amount-input', updateBalance);
    $(document).on('change', 'select[name$="[type]"]', updateBalance);

    $('#manualEntryForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ route('admin.accounting.manual-entry.store') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    alert('Escrituração efetivada com sucesso.');
                    window.location.reload();
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Erro ao processar lançamento.';
                alert(msg);
            }
        });
    });
</script>
@endpush


