@extends('layouts.app')

@section('title', 'Relatório de Célula')

@section('content')

{{-- Remove o padding padrão do main-content para esta tela --}}
<style>
    #main-content { padding: 0 !important; }
    .wizard-canvas {
        max-width: 860px;
        margin: 0 auto;
        padding: 24px 16px 100px;
    }
    @media (min-width: 768px) {
        .wizard-canvas { padding: 32px 40px 80px; }
    }
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(13%) sepia(10%) saturate(2256%) hue-rotate(182deg) brightness(95%) contrast(88%);
    }
</style>

<div class="wizard-canvas"
     x-data='weeklyReportWizard({
         cellId: "{{ $defaultCellId ?? "" }}",
         meetingLocation: {!! json_encode($defaultCell->address ?? "") !!}
     })'>

    {{-- ═══════════════════════════════════════
         BARRA DE PROGRESSO (estilo Stitch)
    ═══════════════════════════════════════ --}}
    <div class="mb-8 space-y-3">
        {{-- Barra fina --}}
        <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
            <div class="h-full bg-[#f59e0b] rounded-full transition-all duration-500"
                 :style="'width: ' + ((step / 5) * 100) + '%'"></div>
        </div>
        {{-- Label do step --}}
        <div>
            <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-widest"
                  x-text="'PASSO ' + step + ' DE 5'"></span>
            <h2 class="text-[18px] font-bold text-[#1c2434] mt-0.5"
                 x-text="['Informações Gerais','Chamada','Visitantes','Social e Ofertas','Revisão Final'][step-1]"></h2>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         FORM
    ═══════════════════════════════════════ --}}
    <form action="{{ route('reports.store') }}" method="POST"
          id="wizardForm" hx-boost="false"
          x-on:submit="submitting = true">
        @csrf

        {{-- STEP 1 --}}
        @include('reports._step1', ['cells' => $cells])

        {{-- STEP 2 --}}
        @include('reports._step2')

        {{-- STEP 3 --}}
        @include('reports._step3')

        {{-- STEP 4 --}}
        @include('reports._step4')

        {{-- STEP 5 --}}
        @include('reports._step5')

        {{-- ─── NAVEGAÇÃO ─── --}}
        <div class="flex items-center gap-3 mt-6">

            {{-- Botão Voltar --}}
            <button type="button" x-show="step > 1" @click="prevStep()"
                    class="flex items-center gap-2 px-6 py-4 bg-white border border-slate-200
                           text-[#1c2434] text-[13px] font-semibold rounded-xl
                           hover:bg-slate-50 transition-all active:scale-[0.98]">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Voltar
            </button>

            {{-- Botão Continuar --}}
            <button type="button" x-show="step < 5" @click="nextStep()"
                    class="flex-1 flex items-center justify-center gap-2 py-4
                           bg-[#f59e0b] text-white text-[15px] font-bold rounded-xl
                           hover:brightness-105 active:scale-[0.98]
                           shadow-lg shadow-amber-200 transition-all group">
                <span>Continuar para Próximo Passo</span>
                <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform text-[20px]">
                    arrow_forward
                </span>
            </button>

            {{-- Botão Finalizar --}}
            <button type="submit" x-show="step === 5" :disabled="submitting"
                    class="flex-[2] flex items-center justify-center gap-2 py-4
                           bg-[#f59e0b] text-white text-[14px] font-black uppercase tracking-wide rounded-xl
                           hover:brightness-105 active:scale-[0.98]
                           shadow-lg shadow-amber-200 transition-all
                           disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!submitting">Confirmar e Enviar</span>
                <span x-show="submitting">Salvando...</span>
                <span class="material-symbols-outlined text-[20px]" x-show="!submitting">send</span>
                <span class="material-symbols-outlined text-[20px] animate-spin" x-show="submitting">progress_activity</span>
            </button>
        </div>
    </form>
</div>

@endsection
