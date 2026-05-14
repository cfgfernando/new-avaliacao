@extends('layouts.app')

@section('title', 'Recibo de Doação - TX' . $transaction->id)

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6">
    <!-- Botões de Ação (não aparecem na impressão) -->
    <div class="flex justify-between items-center mb-10 no-print">
        <a href="{{ route('admin.finance.income.index') }}" class="btn-neo bg-white text-slate-600 text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            <span>Voltar à Listagem</span>
        </a>
        <div class="flex gap-4">
            <button onclick="window.print()" class="btn-neo btn-primary text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-print"></i>
                <span>Imprimir Recibo</span>
            </button>
        </div>
    </div>

    <!-- O Recibo Real -->
    <div class="bg-white shadow-2xl rounded-[2.5rem] border border-slate-100 overflow-hidden print:shadow-none print:border-slate-200">
        <!-- Topo Estilizado -->
        <div class="bg-slate-900 px-12 py-10 text-white flex justify-between items-center print:bg-white print:text-slate-900 print:border-b-2 print:border-slate-100">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tighter">Comprovante de Contribuição</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-1 print:text-slate-500">MDA Church - Gestão Eclesiástica V8</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-black font-money text-accent print:text-slate-900">R$ {{ number_format($transaction->amount, 2, ',', '.') }}</div>
                <div class="text-[9px] font-black uppercase tracking-widest text-slate-500 mt-1">Nº Registro: #TX-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        <!-- Corpo do Recibo -->
        <div class="p-16 space-y-12">
            <div class="text-slate-600 leading-relaxed text-lg">
                <p class="mb-8">
                    Recebemos de <strong class="text-slate-900 uppercase underline decoration-accent decoration-2 underline-offset-4">{{ $transaction->member->name ?? 'Doador não Identificado' }}</strong>, 
                    a importância de <strong class="text-slate-900 uppercase">R$ {{ number_format($transaction->amount, 2, ',', '.') }}</strong> 
                    ({{ $transaction->amount_words ?? 'Valor supra mencionado' }}), 
                    referente a <strong class="text-slate-900 uppercase">{{ $transaction->chartOfAccount->name }}</strong>.
                </p>
                
                <p>
                    O presente valor foi destinado ao fundo de <strong class="text-slate-900 uppercase">{{ $transaction->costCenter->name }}</strong> 
                    da <strong>MDA Church</strong>, conforme registros contábeis auditados.
                </p>
            </div>

            <!-- Detalhes Técnicos -->
            <div class="grid grid-cols-2 gap-12 py-10 border-y border-slate-50 print:border-slate-100">
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Método de Pagamento</h4>
                    <p class="text-sm font-bold text-slate-800 uppercase tracking-tight">
                        <i class="fas fa-{{ $transaction->payment_method === 'pix' ? 'bolt text-emerald-500' : ($transaction->payment_method === 'credit_card' ? 'credit-card text-primary' : 'money-bill-wave text-amber-500') }} mr-2"></i>
                        {{ strtoupper($transaction->payment_method) }}
                    </p>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Data da Operação</h4>
                    <p class="text-sm font-bold text-slate-800 uppercase tracking-tight">
                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->translatedFormat('d \d\e F \d\e Y') }}
                    </p>
                </div>
            </div>

            <!-- Assinatura -->
            <div class="flex justify-between items-end pt-12">
                <div class="text-[10px] font-bold text-slate-400 uppercase leading-loose">
                    Emitido por: {{ auth()->user()->name }}<br>
                    Data de Emissão: {{ now()->format('d/m/Y H:i') }}
                </div>
                <div class="text-center w-72">
                    <div class="border-b-2 border-slate-900 mb-3 h-10"></div>
                    <p class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Tesouraria Geral</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">MDA Church - Sede Administrativa</p>
                </div>
            </div>
        </div>

        <!-- Rodapé / Marca D'água -->
        <div class="bg-slate-50 px-16 py-6 border-t border-slate-100 flex justify-between items-center no-print">
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Documento autêntico gerado eletronicamente</span>
            <div class="flex gap-2">
                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                <div class="w-2 h-2 rounded-full bg-primary"></div>
                <div class="w-2 h-2 rounded-full bg-accent"></div>
            </div>
        </div>
    </div>

    <!-- Nota de Rodapé para Impressão -->
    <div class="hidden print:block text-center mt-12 text-[9px] text-slate-400 font-bold uppercase tracking-widest">
        Este recibo é válido como comprovante de entrega de valores à instituição religiosa sem fins lucrativos.<br>
        MDA Church - CNPJ: 00.000.000/0001-00 - Todos os direitos reservados.
    </div>
</div>

<style>
    @media print {
        body { background: white !important; }
        .card-neo { box-shadow: none !important; border: 1px solid #e2e8f0 !important; border-radius: 0 !important; }
        .no-print { display: none !important; }
        .print\:shadow-none { box-shadow: none !important; }
        .print\:border-slate-200 { border-color: #e2e8f0 !important; }
        .print\:bg-white { background-color: white !important; }
        .print\:text-slate-900 { color: #0f172a !important; }
    }
</style>
@endsection
