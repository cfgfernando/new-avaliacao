@extends('layouts.app')

@section('content')
<div class="max-w-[1400px] mx-auto">
    <!-- Header Elite V8 -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="animate-reveal-left">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-2xl bg-primary flex items-center justify-center shadow-lg shadow-primary/20">
                    <i class="fas fa-university text-white text-sm"></i>
                </div>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Conciliação Bancária</h1>
            </div>
            <p class="text-[10px] font-black text-primary-light uppercase tracking-[0.2em] flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Sincronização de Fluxo de Caixa (OFX)
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Coluna de Upload -->
        <div class="lg:col-span-1">
            <div class="card-neo animate-reveal-up">
                <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Importar Extrato</h3>
                    <p class="text-[9px] text-slate-400 font-black uppercase mt-1">Selecione a conta e o arquivo OFX</p>
                </div>
                
                <form action="{{ route('admin.accounting.reconciliation.process') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Conta Bancária</label>
                        <select name="financial_account_id" required class="input-neo">
                            <option value="">Selecione uma conta...</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->bank_name }} - {{ $account->account_number }})</option>
                            @endforeach

                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-primary-light uppercase tracking-widest mb-3 px-1">Arquivo OFX</label>
                        <div class="relative group">
                            <input type="file" name="ofx_file" required accept=".ofx" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="input-neo flex items-center justify-between group-hover:border-primary transition-all">
                                <span class="text-[10px] text-slate-400 uppercase font-black" id="fileNameDisplay">Selecionar arquivo...</span>
                                <i class="fas fa-upload text-slate-300 group-hover:text-primary"></i>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full btn-neo btn-primary py-4 text-xs tracking-widest font-black uppercase">
                        <i class="fas fa-sync-alt mr-2"></i> Iniciar Processamento
                    </button>
                </form>
            </div>

            <!-- Dicas de Segurança -->
            <div class="mt-8 p-6 bg-blue-50/50 rounded-[2rem] border border-blue-100/50">
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-blue-500 shadow-sm shrink-0">
                        <i class="fas fa-shield-alt text-xs"></i>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-black text-blue-800 uppercase tracking-widest mb-1">Dica de Segurança</h4>
                        <p class="text-[10px] text-blue-600 leading-relaxed">
                            A conciliação bancária garante que os lançamentos do ERP coincidam exatamente com o que ocorreu no banco.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instruções / Ilustração -->
        <div class="lg:col-span-2">
            <div class="card-neo min-h-[400px] flex flex-col items-center justify-center p-12 text-center animate-reveal-up" style="animation-delay: 0.1s">
                <div class="w-32 h-32 rounded-full bg-slate-50 flex items-center justify-center mb-8">
                    <i class="fas fa-file-invoice-dollar text-5xl text-slate-200"></i>
                </div>
                <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight mb-4">Aguardando Importação</h3>
                <p class="text-xs text-slate-400 max-w-sm leading-relaxed mb-8 uppercase font-bold">
                    Importe seu arquivo OFX exportado do banco para iniciar o batimento automático com o Livro Caixa da igreja.
                </p>
                <div class="flex gap-8">
                    <div class="text-center">
                        <span class="block text-xl font-black text-slate-800">1</span>
                        <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">Upload</span>
                    </div>
                    <div class="w-8 h-px bg-slate-100 self-center mt-2"></div>
                    <div class="text-center">
                        <span class="block text-xl font-black text-slate-800">2</span>
                        <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">Batimento</span>
                    </div>
                    <div class="w-8 h-px bg-slate-100 self-center mt-2"></div>
                    <div class="text-center">
                        <span class="block text-xl font-black text-slate-800">3</span>
                        <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">Confirmar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelector('input[name="ofx_file"]').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'Selecionar arquivo...';
        document.getElementById('fileNameDisplay').textContent = fileName;
    });
</script>
@endpush
