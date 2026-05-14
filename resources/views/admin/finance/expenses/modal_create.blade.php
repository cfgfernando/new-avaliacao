<!-- Modal Nova Despesa Elite V8 -->
<div id="expenseModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[9999] flex items-center justify-center p-4 sm:p-6 overflow-y-auto custom-scrollbar">
    <div class="bg-white w-full max-w-xl m-auto animate-reveal-up overflow-hidden shadow-2xl border border-white/20 rounded-[2.5rem] flex flex-col">
        
        <!-- Header -->
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
            <div>
                <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Nova Despesa</h3>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Gestão de Saídas & Pagamentos</p>
            </div>
            <button onclick="closeFinanceModal('expenseModal')" class="w-10 h-10 rounded-xl flex items-center justify-center bg-slate-100 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Tabs -->
        <div class="flex border-b border-slate-100 px-8 bg-slate-50/20 shrink-0 gap-6">
            <button onclick="switchTab('expense', 'perfil')" id="tab-expense-perfil" class="modal-tab-clean active">DADOS GERAIS</button>
            <button onclick="switchTab('expense', 'ged')" id="tab-expense-ged" class="modal-tab-clean">DOCUMENTAÇÃO</button>
            <button onclick="switchTab('expense', 'controle')" id="tab-expense-controle" class="modal-tab-clean">AUDITORIA</button>
        </div>

        <!-- Scrollable Content -->
        <div class="overflow-y-auto flex-grow bg-white custom-scrollbar">
            <form action="{{ route('admin.finance.expenses.store') }}" method="POST" id="form-expense" class="p-0" enctype="multipart/form-data">
                @csrf
                <div id="method-container-exp"></div>
                <input type="hidden" name="status" value="paid">
                
                <!-- Tab: Perfil -->
                <div id="content-expense-perfil" class="p-6 space-y-4 tab-content">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Descrição / Fornecedor</label>
                        <input type="text" name="description" required class="input-neo !py-2.5 uppercase" placeholder="EX: PAGAMENTO ENERGIA ELÉTRICA">
                    </div>

                    <div class="grid grid-cols-12 gap-4 items-end">
                        <div class="col-span-5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Valor Total</label>
                            <div class="relative group/val">
                                <div class="absolute inset-y-0 left-0 w-10 flex items-center justify-center bg-slate-50 border-r border-slate-100 rounded-l-xl text-[10px] font-bold text-slate-400 group-focus-within/val:bg-accent group-focus-within/val:text-white transition-all">R$</div>
                                <input type="text" name="amount" required class="input-neo !pl-12 !py-3 !text-lg !font-black !text-slate-800 mask-money" placeholder="0,00">
                            </div>
                        </div>
                        <div class="col-span-7">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Data Vecto/Pagto</label>
                            <input type="date" name="transaction_date" required class="input-neo !py-3 uppercase" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Conta de Origem</label>
                            <select name="financial_account_id" required class="input-neo !py-2.5 uppercase">
                                <option value="">Selecione...</option>
                                @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Plano de Contas</label>
                            <select name="chart_of_account_id" required class="input-neo !py-2.5 uppercase">
                                <option value="">Selecione...</option>
                                @foreach($chartOfAccounts as $coa)
                                <option value="{{ $coa->id }}">{{ $coa->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 px-1">Meio de Movimentação</label>
                        <select name="payment_method" required class="input-neo !py-2.5 uppercase">
                            <option value="pix">PIX Transmissão</option>
                            <option value="cash">Espécie / Dinheiro</option>
                            <option value="credit_card">Cartão Corporativo</option>
                            <option value="bank_transfer">Boleto / Transferência</option>
                        </select>
                    </div>
                </div>

                <!-- Tab: GED -->
                <div id="content-expense-ged" class="p-12 hidden tab-content text-center">
                    <div class="border-2 border-dashed border-slate-100 rounded-3xl p-12 space-y-4 bg-slate-50/50 hover:border-accent group transition-all">
                        <div class="w-16 h-16 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center mx-auto text-slate-400 group-hover:text-accent transition-all">
                            <i class="fas fa-cloud-upload-alt text-2xl"></i>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Anexo de Comprovante / NF</p>
                            <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest">Digitalize o documento para o compliance</p>
                        </div>
                        <input type="file" name="attachment" id="expense_attachment" class="hidden" onchange="$('#file-label-exp').text(this.files[0].name).addClass('text-emerald-500')">
                        <label for="expense_attachment" class="inline-block px-8 py-3 bg-white border border-slate-100 text-primary-light rounded-xl text-[10px] font-black uppercase tracking-widest cursor-pointer hover:bg-slate-50 hover:border-accent hover:text-accent transition-all shadow-sm">
                            <span id="file-label-exp">Selecionar Arquivo</span>
                        </label>
                    </div>
                </div>

                <!-- Tab: Controle -->
                <div id="content-expense-controle" class="p-8 hidden tab-content space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Centro de Custo</label>
                        <select name="cost_center_id" class="input-neo uppercase">
                            @foreach($costCenters as $cc)
                            <option value="{{ $cc->id }}">{{ $cc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="p-6 bg-slate-50/50 rounded-3xl border border-slate-100 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-slate-800 shadow-sm shrink-0">
                            <i class="fas fa-shield-alt text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Auditoria de Segurança</p>
                            <p class="text-[9px] text-primary-light font-black uppercase mt-1 leading-relaxed">Vínculo automático de usuário e IP para compliance contábil.</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-slate-100 flex justify-between items-center bg-slate-50/30 shrink-0">
            <button type="button" onclick="closeFinanceModal('expenseModal')" class="px-6 py-3 bg-white border border-slate-100 rounded-xl text-[10px] font-bold text-slate-500 uppercase tracking-widest hover:bg-slate-50 transition-all">Descartar</button>
            <button type="button" onclick="$('#form-expense').submit()" class="px-10 py-3 btn-neo btn-primary text-[10px] font-bold uppercase tracking-widest">
                <i class="fas fa-check-circle mr-2"></i> Registrar Saída
            </button>
        </div>
    </div>
</div>

<style>
    .modal-tab-clean { padding: 16px 20px; font-size: 10px; font-weight: 900; letter-spacing: 0.15em; color: #64748b; border-bottom: 3px solid transparent; transition: all 0.3s ease; text-transform: uppercase; }
    .modal-tab-clean.active { color: #f59e0b; border-bottom-color: #f59e0b; }
    
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #f1f5f9; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #e2e8f0; }
</style>
