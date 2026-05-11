@extends('layouts.app')

@section('header_title', 'Novo Malote')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <a href="{{ route('reports.index') }}" class="text-slate-500 hover:text-title font-black text-xs uppercase tracking-widest flex items-center gap-2 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Voltar para Lista
        </a>
    </div>

    <div class="card-elite p-10">
        <div class="mb-10">
            <h1 class="text-3xl font-black text-title tracking-tight">Novo Relatório Semanal</h1>
            <p class="text-slate-500 font-medium">Preencha os dados da reunião de célula para gerar o malote financeiro.</p>
        </div>

        <form action="{{ route('reports.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Célula -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Célula</label>
                    <select name="cell_id" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-accent/20 text-sm font-bold text-slate-700">
                        <option value="">Selecione a célula...</option>
                        @foreach($cells as $cell)
                            <option value="{{ $cell->id }}">{{ $cell->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Data -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Data da Reunião</label>
                    <input type="date" name="report_date" required value="{{ date('Y-m-d') }}" class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-accent/20 text-sm font-bold text-slate-700">
                </div>

                <!-- Frequência -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Número de Membros</label>
                    <input type="number" name="member_count" required min="0" class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-accent/20 text-sm font-bold text-slate-700" placeholder="0">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Número de Visitantes</label>
                    <input type="number" name="visitor_count" required min="0" class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-accent/20 text-sm font-bold text-slate-700" placeholder="0">
                </div>

                <!-- Oferta -->
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Valor da Oferta (R$)</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 font-black text-slate-400 text-sm">R$</span>
                        <input type="number" step="0.01" name="offering_amount" required class="w-full pl-12 pr-5 py-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-accent/20 text-xl font-black text-emerald-600 placeholder:text-slate-200" placeholder="0,00">
                    </div>
                </div>
            </div>

            <div class="pt-10">
                <button type="submit" class="w-full btn-primary py-5">
                    <i class="fas fa-save text-lg"></i>
                    <span>Salvar Relatório como Rascunho</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
