@extends('layouts.app')

@section('header_title', 'Novo Malote')

@section('content')
<div class="relative">
    <!-- Massive Background Typography (Architectural Element) -->
    <div class="absolute -top-20 -left-10 text-[200px] font-black text-white/[0.02] pointer-events-none select-none tracking-tighter uppercase leading-none">
        MALOTE
    </div>

    <div class="max-w-5xl space-y-12 relative z-10">
        <div class="flex items-center justify-between">
            <a href="{{ route('reports.index') }}" class="text-neutral-600 hover:text-accent font-black text-[10px] uppercase tracking-[0.3em] flex items-center gap-4 transition-all group">
                <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-2"></i>
                Voltar
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Form Info -->
            <div class="lg:col-span-4 space-y-6">
                <h1 class="text-6xl font-black text-white leading-none tracking-tighter">REGISTRO <br><span class="text-accent">MENSAL.</span></h1>
                <p class="text-neutral-500 font-medium text-sm leading-relaxed">
                    A precisão no registro de dados reflete a excelência da mordomia na obra do Senhor. Preencha os campos com atenção.
                </p>
                <div class="pt-6">
                    <div class="h-[2px] w-20 bg-accent"></div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="lg:col-span-8">
                <div class="card-neo p-12 bg-primary-card">
                    <form action="{{ route('reports.store') }}" method="POST" class="space-y-12">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <!-- Célula -->
                            <div class="space-y-3">
                                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-accent">Identificação da Célula</label>
                                <select name="cell_id" required class="input-neo">
                                    <option value="">SELECIONE...</option>
                                    @foreach($cells as $cell)
                                        <option value="{{ $cell->id }}">{{ $cell->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Data -->
                            <div class="space-y-3">
                                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-neutral-600">Cronologia do Evento</label>
                                <input type="date" name="report_date" required value="{{ date('Y-m-d') }}" class="input-neo">
                            </div>

                            <!-- Frequência -->
                            <div class="space-y-3">
                                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-neutral-600">Total de Membros</label>
                                <input type="number" name="member_count" required min="0" class="input-neo" placeholder="00">
                            </div>

                            <div class="space-y-3">
                                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-neutral-600">Total de Visitantes</label>
                                <input type="number" name="visitor_count" required min="0" class="input-neo" placeholder="00">
                            </div>

                            <!-- Oferta -->
                            <div class="md:col-span-2 space-y-4 pt-4">
                                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-accent">Montante da Oferta</label>
                                <div class="relative group">
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 font-black text-accent text-xl transition-all group-focus-within:scale-110">BRL</span>
                                    <input type="number" step="0.01" name="offering_amount" required class="input-neo pl-20 text-4xl py-8 border-b-2 border-accent/20 focus:border-accent" placeholder="0,00">
                                </div>
                            </div>
                        </div>

                        <div class="pt-8">
                            <button type="submit" class="btn-neo w-full py-6 text-sm">
                                <i class="fas fa-check-double"></i>
                                <span>Autenticar e Salvar Registro</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

