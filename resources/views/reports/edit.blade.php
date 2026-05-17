@extends('layouts.app')

@section('title', 'Editar Registro')

@section('content')
<div class="min-h-screen bg-[#f1f5f9] -m-8 p-8" 
     x-data='weeklyReportWizard({ 
        cellId: "{{ $report->cell_id }}", 
        meetingLocation: {!! json_encode($report->meeting_location) !!},
        meetingDate: "{{ $report->meeting_date?->format('Y-m-d') ?? "" }}",
        notes: {!! json_encode($report->notes) !!},
        presentMemberIds: {!! json_encode($report->present_member_ids ?? []) !!},
        visitorList: {!! json_encode(collect($report->visitor_names)->map(fn($n) => is_string($n) ? ["name" => $n] : $n)->toArray() ?? []) !!},
        visitors: {{ (int)$report->visitors }},
        children: {{ (int)$report->children }},
        otherCellVisitors: {{ (int)$report->other_cell_visitors }},
        committedMembers: {{ (int)$report->committed_members }},
        houseOfPeace: {{ (int)$report->house_of_peace }},
        mdasDone: {{ (int)$report->mdas_done }},
        kgOfLove: {{ (float)$report->kg_of_love }},
        conversions: {{ (int)$report->conversions }},
        reconciliations: {{ (int)$report->reconciliations }},
        offerPix: {{ (float)$report->offer_pix }},
        offerCash: {{ (float)$report->offer_cash }}
     })'>
    
    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Header da Página --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-[#111827] tracking-tight">Editar Registro</h2>
                <p class="text-sm text-[#8a99af] font-medium">Modifique os dados da reunião realizada</p>
            </div>
            <div class="flex items-center gap-4 bg-white px-6 py-3 rounded-xl shadow-sm border border-gray-100">
                <div class="text-right border-r border-gray-100 pr-4">
                    <p class="text-[10px] font-bold text-[#8a99af] uppercase tracking-wider">Presença Total</p>
                    <p class="text-lg font-bold text-[#111827]" x-text="totalPresence()"></p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-[#8a99af] uppercase tracking-wider">Oferta Total</p>
                    <p class="text-lg font-bold text-[#f59e0b]" x-text="formatMoney(totalOffer())"></p>
                </div>
            </div>
        </div>

        {{-- Wizard Progress Bar --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <template x-for="i in 5" :key="i">
                    <div class="flex flex-col items-center gap-2">
                        <div class="size-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300"
                            :class="step >= i ? 'bg-[#f59e0b] text-white' : 'bg-gray-100 text-[#8a99af]'">
                            <span x-text="i"></span>
                        </div>
                    </div>
                </template>
            </div>
            <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-[#f59e0b] transition-all duration-500" :style="'width: ' + ((step-1) * 25) + '%'"></div>
            </div>
            <div class="mt-4 text-center">
                <p class="text-xs font-bold text-[#111827] uppercase tracking-widest" x-text="stepTitle()"></p>
            </div>
        </div>

        <form action="{{ route('reports.update', $report) }}" method="POST" id="wizardForm" hx-boost="false" x-on:submit="submitting = true">
            @csrf
            @method('PUT')
            
            {{-- STEP 1: Identificação --}}
            <div x-show="step === 1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700 uppercase">Célula</label>
                            <select name="cell_id" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#f59e0b]/20 focus:border-[#f59e0b] outline-none transition-all" x-model="cellId" @change="fetchMembers()">
                                @foreach($cells as $cell)
                                    <option value="{{ $cell->id }}" data-location="{{ $cell->address }}">{{ $cell->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700 uppercase">Data da Reunião</label>
                            <input type="date" name="meeting_date" required x-model="meetingDate" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#f59e0b]/20 focus:border-[#f59e0b] outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700 uppercase">Tema da Palavra</label>
                            <input type="text" name="word_theme" value="{{ $report->word_theme }}" placeholder="Ex: O Coração de Davi" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#f59e0b]/20 focus:border-[#f59e0b] outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700 uppercase">Local do Encontro</label>
                            <input type="text" name="meeting_location" x-model="meetingLocation" placeholder="Ex: Casa do Líder" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#f59e0b]/20 focus:border-[#f59e0b] outline-none transition-all">
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 2: Chamada & Frequência --}}
            <div x-show="step === 2" x-cloak>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 space-y-8">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <h3 class="text-sm font-bold text-[#111827] uppercase tracking-wider">Chamada da Célula</h3>
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-bold text-green-600 uppercase" x-text="presentMemberIds.length + ' Presentes'"></span>
                            <button type="button" @click="toggleSelectAll()" class="text-xs font-bold text-[#f59e0b] uppercase hover:underline" x-text="presentMemberIds.length === cellMembers.length ? 'Zerar Chamada' : 'Confirmar Todos'"></button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        <template x-for="member in cellMembers" :key="member.id">
                            <div class="flex items-center justify-between p-4 rounded-xl border transition-all"
                                :class="presentMemberIds.includes(member.id) ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-100 opacity-70'">
                                <div class="flex items-center gap-3">
                                    <div class="size-2 rounded-full" :class="presentMemberIds.includes(member.id) ? 'bg-green-500' : 'bg-red-500'"></div>
                                    <span class="text-sm font-bold uppercase tracking-tight text-gray-700" x-text="member.name"></span>
                                </div>
                                
                                <button type="button" @click="toggleMember(member.id)" 
                                    class="px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all"
                                    :class="presentMemberIds.includes(member.id) ? 'bg-green-600 text-white shadow-sm' : 'bg-red-600 text-white shadow-sm'">
                                    <span x-text="presentMemberIds.includes(member.id) ? 'Presente' : 'Ausente'"></span>
                                </button>

                                <input type="hidden" name="present_member_ids[]" :value="member.id" x-show="presentMemberIds.includes(member.id)" :disabled="!presentMemberIds.includes(member.id)">
                            </div>
                        </template>
                    </div>

                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-sm font-bold text-[#111827] uppercase tracking-wider">Visitantes & Extras</h3>
                            <button type="button" @click="addVisitor()" class="text-xs font-bold text-blue-600 uppercase flex items-center gap-2 hover:underline">
                                <i class="fas fa-plus-circle"></i> Adicionar Visitante
                            </button>
                        </div>

                        <div class="grid grid-cols-1 gap-3">
                            <template x-for="(visitor, index) in visitorList" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" name="visitor_names[]" x-model="visitor.name" class="flex-1 bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm" placeholder="Nome Completo do Visitante...">
                                    <button type="button" @click="removeVisitor(index)" class="px-4 bg-red-50 text-red-500 rounded-lg border border-red-100"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </template>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-500 uppercase">Total Visitantes</label>
                                <input type="number" name="visitors" x-model.number="visitors" readonly class="w-full bg-gray-100 border border-gray-200 rounded-lg px-4 py-2 text-center font-bold text-gray-600">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-500 uppercase">Crianças</label>
                                <input type="number" name="children" x-model.number="children" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-center font-bold text-lg">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-500 uppercase">Outras Cél.</label>
                                <input type="number" name="other_cell_visitors" x-model.number="otherCellVisitors" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-center font-bold text-lg">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-500 uppercase">M. Ativos</label>
                                <input type="number" name="committed_members" x-model.number="committedMembers" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-center font-bold text-lg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 3: Impacto Pastoral --}}
            <div x-show="step === 3" x-cloak>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4 p-6 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="flex justify-between items-center">
                                <label class="text-xs font-bold text-gray-600 uppercase">Casas de Paz</label>
                                <span class="text-xl font-bold text-[#f59e0b]" x-text="houseOfPeace"></span>
                            </div>
                            <input type="range" name="house_of_peace" x-model="houseOfPeace" min="0" max="20" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-[#f59e0b]">
                        </div>
                        <div class="space-y-4 p-6 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="flex justify-between items-center">
                                <label class="text-xs font-bold text-gray-600 uppercase">MDAs Realizados</label>
                                <span class="text-xl font-bold text-blue-600" x-text="mdasDone"></span>
                            </div>
                            <input type="range" name="mdas_done" x-model="mdasDone" min="0" max="50" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700 uppercase">Quilo do Amor (Kg)</label>
                            <input type="number" step="0.1" name="kg_of_love" x-model="kgOfLove" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700 uppercase">Conversões</label>
                            <input type="number" name="conversions" x-model="conversions" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700 uppercase">Reconciliações</label>
                            <input type="number" name="reconciliations" x-model="reconciliations" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm">
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 4: Financeiro --}}
            <div x-show="step === 4" x-cloak>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700 uppercase">Oferta via PIX</label>
                            <input type="number" step="0.01" name="offer_pix" x-model.number="offerPix" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-6 py-4 text-2xl font-bold text-gray-800" placeholder="0.00">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-700 uppercase">Oferta em Espécie</label>
                            <input type="number" step="0.01" name="offer_cash" x-model.number="offerCash" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-6 py-4 text-2xl font-bold text-gray-800" placeholder="0.00">
                        </div>
                    </div>
                    <div class="bg-green-50 p-8 rounded-xl border border-green-100 text-center">
                        <p class="text-[10px] font-bold text-green-600 uppercase tracking-widest mb-2">Total do Malote</p>
                        <p class="text-5xl font-bold text-green-700" x-text="formatMoney(totalOffer())"></p>
                    </div>
                </div>
            </div>

            {{-- STEP 5: Revisão --}}
            <div x-show="step === 5" x-cloak>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-700 uppercase">Observações Finais</label>
                        <textarea name="notes" rows="6" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-4 text-sm" placeholder="Alguma observação importante sobre a reunião?" x-model="notes"></textarea>
                    </div>
                </div>
            </div>

            {{-- BOTÕES DE NAVEGAÇÃO --}}
            <div class="flex justify-between items-center gap-4 mt-8">
                <button type="button" x-show="step > 1" @click="prevStep()" class="px-8 py-3.5 bg-white border border-gray-200 text-gray-600 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all">
                    Voltar
                </button>
                <div class="flex-1"></div>
                <button type="button" x-show="step < 5" @click="nextStep()" class="px-10 py-3.5 bg-[#f59e0b] text-white rounded-xl font-bold text-sm shadow-lg shadow-orange-200 hover:bg-[#d97706] transition-all flex items-center gap-2">
                    Próximo Passo <i class="fas fa-arrow-right text-xs"></i>
                </button>
                <button type="submit" x-show="step === 5" :disabled="submitting"
                    class="px-10 py-3.5 bg-blue-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!submitting">Salvar Alterações</span>
                    <span x-show="submitting">Salvando...</span>
                    <i class="fas fa-save text-xs" x-show="!submitting"></i>
                    <i class="fas fa-spinner fa-spin text-xs" x-show="submitting"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
