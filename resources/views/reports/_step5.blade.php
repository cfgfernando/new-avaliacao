{{-- STEP 5: Revisão Final (Stitch pattern — Passo 5) --}}
<div x-show="step === 5" x-cloak class="space-y-5">

    {{-- Barra 100% --}}
    <div class="flex justify-between items-center">
        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">PASSO 5 DE 5</span>
        <span class="text-[11px] font-bold text-[#f59e0b]">100% Concluído</span>
    </div>

    {{-- Hero de Revisão --}}
    <div class="bg-[#1c2434] rounded-xl p-6 flex flex-col items-center text-center gap-3">
        <div class="w-16 h-16 bg-[#f59e0b] rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/30">
            <span class="material-symbols-outlined text-white text-[32px]"
                  style="font-variation-settings:'FILL' 1,'wght' 600">task_alt</span>
        </div>
        <div>
            <h2 class="text-[20px] font-bold text-white">Revisão Final</h2>
            <p class="text-[14px] text-white/60 mt-1">Confira os dados antes de enviar o relatório.</p>
        </div>
    </div>

    {{-- INFORMAÇÕES GERAIS --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4">
        <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
            <span class="material-symbols-outlined text-[#f59e0b] text-[20px]">event_note</span>
            <h3 class="text-[13px] font-black text-[#1c2434] uppercase tracking-wide">Informações Gerais</h3>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Data</p>
                <p class="text-[15px] font-semibold text-[#1c2434] mt-0.5" x-text="meetingDate || '—'"></p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Local</p>
                <p class="text-[15px] font-semibold text-[#1c2434] mt-0.5" x-text="meetingLocation || '—'"></p>
            </div>
            <div class="col-span-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tema da Palavra</p>
                <p class="text-[15px] font-semibold text-[#f59e0b] mt-0.5" x-text="document.querySelector('[name=word_theme]')?.value || '—'"></p>
            </div>
        </div>
    </div>

    {{-- PRESENÇA --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4">
        <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
            <span class="material-symbols-outlined text-[#f59e0b] text-[20px]">group</span>
            <h3 class="text-[13px] font-black text-[#1c2434] uppercase tracking-wide">Presença</h3>
        </div>
        <div class="flex divide-x divide-slate-100">
            <div class="flex-1 text-center py-2">
                <p class="text-[28px] font-black text-[#1c2434]" x-text="presentMemberIds.length"></p>
                <p class="text-[12px] text-slate-400 font-medium">Presentes</p>
            </div>
            <div class="flex-1 text-center py-2">
                <p class="text-[28px] font-black text-red-500"
                   x-text="cellMembers.length - presentMemberIds.length"></p>
                <p class="text-[12px] text-slate-400 font-medium">Ausentes</p>
            </div>
            <div class="flex-1 text-center py-2">
                <p class="text-[28px] font-black text-[#1c2434]" x-text="totalPresence()"></p>
                <p class="text-[12px] text-slate-400 font-medium">Total</p>
            </div>
        </div>
    </div>

    {{-- VISITANTES E OUTROS --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4">
        <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
            <span class="material-symbols-outlined text-[#f59e0b] text-[20px]">person_add</span>
            <h3 class="text-[13px] font-black text-[#1c2434] uppercase tracking-wide">Visitantes e Outros</h3>
        </div>
        <div x-show="visitorList.filter(v=>v.name).length > 0">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nomes dos Visitantes</p>
            <div class="flex flex-wrap gap-2">
                <template x-for="(visitor, i) in visitorList.filter(v=>v.name)" :key="i">
                    <span class="bg-slate-100 text-[#1c2434] text-[12px] font-medium px-3 py-1 rounded-full" x-text="visitor.name"></span>
                </template>
            </div>
        </div>
        <div class="flex justify-between pt-2">
            <div>
                <p class="text-[11px] text-slate-400 font-medium">Crianças</p>
                <p class="text-[16px] font-bold text-[#1c2434]" x-text="children"></p>
            </div>
            <div class="text-right">
                <p class="text-[11px] text-slate-400 font-medium">Outras Células</p>
                <p class="text-[16px] font-bold text-[#1c2434]" x-text="otherCellVisitors"></p>
            </div>
        </div>
    </div>

    {{-- SOCIAL E OFERTAS --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4">
        <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
            <span class="material-symbols-outlined text-[#f59e0b] text-[20px]">payments</span>
            <h3 class="text-[13px] font-black text-[#1c2434] uppercase tracking-wide">Social e Ofertas</h3>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#f59e0b] text-[16px]">favorite</span>
                </div>
                <div>
                    <p class="text-[11px] text-slate-400">Decisões</p>
                    <p class="text-[16px] font-bold text-[#1c2434]" x-text="conversions"></p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-emerald-500 text-[16px]">handshake</span>
                </div>
                <div>
                    <p class="text-[11px] text-slate-400">Reconciliações</p>
                    <p class="text-[16px] font-bold text-[#1c2434]" x-text="reconciliations"></p>
                </div>
            </div>
            <div class="col-span-2 flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                <span class="text-[13px] font-black text-[#1c2434] uppercase tracking-wide">Valor da Oferta</span>
                <span class="text-[22px] font-black text-[#f59e0b]" x-text="formatMoney(totalOffer())"></span>
            </div>
        </div>
    </div>

    {{-- OBSERVAÇÕES --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-3">
        <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
            <span class="material-symbols-outlined text-[#f59e0b] text-[20px]">edit_note</span>
            <h3 class="text-[13px] font-black text-[#1c2434] uppercase tracking-wide">Observações do Líder</h3>
        </div>
        <textarea name="notes" rows="4"
                  x-model="notes"
                  placeholder="Alguma observação importante sobre a reunião desta semana?"
                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[14px] resize-none
                         placeholder:text-slate-300 text-slate-700
                         focus:ring-2 focus:ring-[#f59e0b] focus:border-[#f59e0b] focus:bg-white outline-none transition-all"></textarea>
    </div>

</div>
