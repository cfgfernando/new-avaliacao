<!-- Modal Nova Despesa (Compacto e Padronizado) -->
<div id="expenseModal" class="hidden fixed inset-0 bg-primary/40 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-[30px] shadow-2xl w-full max-w-lg overflow-hidden animate-reveal-up flex flex-col max-h-[90vh]">
        
        <!-- Header -->
        <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center text-accent shadow-sm border border-accent/20">
                    <i class="fas fa-arrow-trend-down text-sm rotate-180"></i>
                </div>
                <h3 class="text-lg font-black text-primary-dark uppercase tracking-tight">Nova Despesa</h3>
            </div>
            <button onclick="closeFinanceModal('expenseModal')" class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-rose-50 text-slate-300 hover:text-rose-500 transition-all">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <!-- Tabs -->
        <div class="flex border-b border-slate-50 px-6 bg-white shrink-0">
            <button onclick="switchTab('expense', 'perfil')" id="tab-expense-perfil" class="modal-tab active">PERFIL</button>
            <button onclick="switchTab('expense', 'ged')" id="tab-expense-ged" class="modal-tab">GED</button>
            <button onclick="switchTab('expense', 'controle')" id="tab-expense-controle" class="modal-tab">CONTROLE</button>
        </div>

        <!-- Scrollable Content -->
        <div class="overflow-y-auto flex-grow">
            <form action="{{ route('admin.finance.expenses.store') }}" method="POST" id="form-expense" class="p-0">
                @csrf
                <input type="hidden" name="status" value="paid">
                
                <!-- Tab: Perfil -->
                <div id="content-expense-perfil" class="p-6 space-y-4 tab-content">
                    <div>
                        <label class="block text-[9px] font-black text-primary-light uppercase tracking-[0.2em] mb-1.5 px-1 italic">Descrição da Despesa / Fornecedor</label>
                        <input type="text" name="description" class="input-neo !py-2.5 w-full font-bold text-primary-dark italic" placeholder="Ex: Pagamento Copel Jan/2026...">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] font-black text-primary-light uppercase tracking-[0.2em] mb-1.5 px-1 italic">Data Vecto/Pagto</label>
                            <input type="date" name="transaction_date" class="input-neo !py-2.5 w-full font-black text-primary-dark" value="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-primary-light uppercase tracking-[0.2em] mb-1.5 px-1 italic">Valor Total (R$)</label>
                            <input type="text" name="amount" class="input-neo !py-2.5 w-full font-money text-right font-black" placeholder="0,00">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] font-black text-primary-light uppercase tracking-[0.2em] mb-1.5 px-1 italic">Conta Origem</label>
                            <select name="financial_account_id" class="input-neo !py-2.5 w-full font-black italic">
                                <option value="">Selecione...</option>
                                @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-primary-light uppercase tracking-[0.2em] mb-1.5 px-1 italic">Plano de Contas</label>
                            <select name="chart_of_account_id" class="input-neo !py-2.5 w-full font-black italic">
                                <option value="">Selecione...</option>
                                @foreach($chartOfAccounts as $coa)
                                <option value="{{ $coa->id }}">{{ $coa->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-black text-primary-light uppercase tracking-[0.2em] mb-1.5 px-1 italic">Meio Pagto.</label>
                        <select name="payment_method" class="input-neo !py-2.5 w-full font-black italic">
                            <option value="cash">Dinheiro</option>
                            <option value="pix">PIX</option>
                            <option value="credit_card">Cartão</option>
                            <option value="bank_transfer">Boleto</option>
                        </select>
                    </div>
                </div>

                <!-- Tab: GED -->
                <div id="content-expense-ged" class="p-6 hidden tab-content">
                    <div class="border-2 border-dashed border-slate-100 rounded-2xl p-8 text-center space-y-3">
                        <i class="fas fa-file-pdf text-3xl text-slate-100"></i>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Anexe a nota fiscal ou boleto.</p>
                        <button type="button" class="btn-neo btn-primary !py-2 !px-6 !text-[8px] !rounded-lg">Upload</button>
                    </div>
                </div>

                <!-- Tab: Controle -->
                <div id="content-expense-controle" class="p-6 hidden tab-content space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] font-black text-primary-light uppercase tracking-[0.2em] mb-1.5 px-1 italic">Centro Custo</label>
                            <select name="cost_center_id" class="input-neo !py-2.5 w-full font-black italic">
                                @foreach($costCenters as $cc)
                                <option value="{{ $cc->id }}">{{ $cc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-slate-50 flex justify-between items-center bg-white shrink-0">
            <button type="button" onclick="closeFinanceModal('expenseModal')" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-rose-500">CANCELAR</button>
            <button type="submit" form="form-expense" class="btn-neo btn-primary px-10 py-3.5 !rounded-xl flex items-center gap-2 group transition-all">
                <span class="text-[10px] font-black uppercase tracking-widest">REGISTRAR DESPESA</span>
                <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>
    </div>
</div>
