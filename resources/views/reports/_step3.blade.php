{{-- STEP 3: Visitantes (Stitch pattern — Passo 3) --}}
<div x-show="step === 3" x-cloak class="space-y-5">

    {{-- NOMES DOS VISITANTES --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4">
        <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
            <span class="material-symbols-outlined text-[#f59e0b] text-[20px]"
                  style="font-variation-settings:'FILL' 1">person_add</span>
            <h3 class="text-[13px] font-black text-[#1c2434] uppercase tracking-wide">Nomes dos Visitantes</h3>
        </div>

        <div class="space-y-4" x-show="visitorList.length > 0">
            <template x-for="(visitor, index) in visitorList" :key="index">
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"
                           x-text="'Visitante ' + (index + 1)"></label>
                    <div class="flex gap-2">
                        <input type="text" name="visitor_names[]"
                               x-model="visitor.name"
                               placeholder="Digite o nome completo..."
                               class="flex-1 h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl text-[15px]
                                      placeholder:text-slate-400
                                      focus:ring-2 focus:ring-[#f59e0b] focus:border-[#f59e0b]
                                      focus:bg-white outline-none transition-all">
                        <button type="button" @click="removeVisitor(index)"
                                class="w-12 h-12 flex items-center justify-center text-slate-300 hover:text-red-400 hover:bg-red-50 rounded-xl transition-all">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="visitorList.length === 0" class="py-4 text-center text-slate-400 text-[14px]">
            Nenhum visitante adicionado ainda.
        </div>

        <button type="button" @click="addVisitor()"
                class="flex items-center gap-2 text-[#f59e0b] font-bold text-[14px] hover:opacity-80 transition-opacity">
            <span class="material-symbols-outlined text-[20px]">add_circle</span>
            Adicionar outro visitante
        </button>

        <input type="hidden" name="visitors" :value="visitorList.length">
    </div>

    {{-- OUTROS PÚBLICOS --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-1">
        <div class="flex items-center gap-2 pb-3 mb-2 border-b border-slate-100">
            <span class="material-symbols-outlined text-[#f59e0b] text-[20px]"
                  style="font-variation-settings:'FILL' 1">groups_3</span>
            <h3 class="text-[13px] font-black text-[#1c2434] uppercase tracking-wide">Outros Públicos</h3>
        </div>

        {{-- Crianças --}}
        <div class="flex items-center justify-between py-4 border-b border-slate-50">
            <div>
                <h4 class="text-[16px] font-bold text-[#1c2434]">Crianças</h4>
                <p class="text-[12px] text-slate-400">Presentes na reunião</p>
            </div>
            <div class="flex items-center gap-4">
                <button type="button" @click="children = Math.max(0, children - 1)"
                        class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center text-[#1c2434]
                               hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[20px]">remove</span>
                </button>
                <span class="text-[22px] font-black text-[#1c2434] w-7 text-center" x-text="children"></span>
                <button type="button" @click="children++"
                        class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center text-[#1c2434]
                               hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                </button>
            </div>
            <input type="hidden" name="children" :value="children">
        </div>

        {{-- Outras Células --}}
        <div class="flex items-center justify-between py-4">
            <div>
                <h4 class="text-[16px] font-bold text-[#1c2434]">Outras Células</h4>
                <p class="text-[12px] text-slate-400">Membros em visita</p>
            </div>
            <div class="flex items-center gap-4">
                <button type="button" @click="otherCellVisitors = Math.max(0, otherCellVisitors - 1)"
                        class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center text-[#1c2434]
                               hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[20px]">remove</span>
                </button>
                <span class="text-[22px] font-black text-[#1c2434] w-7 text-center" x-text="otherCellVisitors"></span>
                <button type="button" @click="otherCellVisitors++"
                        class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center text-[#1c2434]
                               hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                </button>
            </div>
            <input type="hidden" name="other_cell_visitors" :value="otherCellVisitors">
        </div>
    </div>

</div>
