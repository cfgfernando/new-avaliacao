{{-- STEP 4: Social & Oferta (Stitch pattern) --}}
<div x-show="step === 4" x-cloak class="space-y-5">

    <div class="space-y-0.5">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Relatório de Célula</p>
        <h3 class="text-[18px] font-bold text-[#1c2434]">Social e Ofertas</h3>
    </div>

    {{-- SOCIAL --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-1">
        <div class="flex items-center gap-2 pb-3 mb-2 border-b border-slate-100">
            <span class="material-symbols-outlined text-[#f59e0b] text-[20px]">favorite</span>
            <h3 class="text-[13px] font-black text-[#1c2434] uppercase tracking-wide">Social</h3>
        </div>

        @php $steppers = [['label'=>'Decisões','sub'=>'Novos convertidos hoje','model'=>'conversions','name'=>'conversions'],['label'=>'Reconciliações','sub'=>'Restaurações na reunião','model'=>'reconciliations','name'=>'reconciliations'],['label'=>'Casas de Paz','sub'=>'Abertas na semana','model'=>'houseOfPeace','name'=>'house_of_peace']]; @endphp

        <div class="divide-y divide-slate-50">
            <div class="flex items-center justify-between py-4">
                <div><h4 class="text-[16px] font-bold text-[#1c2434]">Decisões</h4><p class="text-[12px] text-slate-400">Novos convertidos hoje</p></div>
                <div class="flex items-center gap-4">
                    <button type="button" @click="conversions = Math.max(0, conversions-1)" class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center hover:bg-slate-50 active:scale-95 transition-all"><span class="material-symbols-outlined text-[20px]">remove</span></button>
                    <span class="text-[22px] font-black text-[#1c2434] w-7 text-center" x-text="conversions"></span>
                    <button type="button" @click="conversions++" class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center hover:bg-slate-50 active:scale-95 transition-all"><span class="material-symbols-outlined text-[20px]">add</span></button>
                </div>
                <input type="hidden" name="conversions" :value="conversions">
            </div>
            <div class="flex items-center justify-between py-4">
                <div><h4 class="text-[16px] font-bold text-[#1c2434]">Reconciliações</h4><p class="text-[12px] text-slate-400">Restaurações na reunião</p></div>
                <div class="flex items-center gap-4">
                    <button type="button" @click="reconciliations = Math.max(0, reconciliations-1)" class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center hover:bg-slate-50 active:scale-95 transition-all"><span class="material-symbols-outlined text-[20px]">remove</span></button>
                    <span class="text-[22px] font-black text-[#1c2434] w-7 text-center" x-text="reconciliations"></span>
                    <button type="button" @click="reconciliations++" class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center hover:bg-slate-50 active:scale-95 transition-all"><span class="material-symbols-outlined text-[20px]">add</span></button>
                </div>
                <input type="hidden" name="reconciliations" :value="reconciliations">
            </div>
            <div class="flex items-center justify-between py-4">
                <div><h4 class="text-[16px] font-bold text-[#1c2434]">Casas de Paz</h4><p class="text-[12px] text-slate-400">Abertas na semana</p></div>
                <div class="flex items-center gap-4">
                    <button type="button" @click="houseOfPeace = Math.max(0, houseOfPeace-1)" class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center hover:bg-slate-50 active:scale-95 transition-all"><span class="material-symbols-outlined text-[20px]">remove</span></button>
                    <span class="text-[22px] font-black text-[#1c2434] w-7 text-center" x-text="houseOfPeace"></span>
                    <button type="button" @click="houseOfPeace++" class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center hover:bg-slate-50 active:scale-95 transition-all"><span class="material-symbols-outlined text-[20px]">add</span></button>
                </div>
                <input type="hidden" name="house_of_peace" :value="houseOfPeace">
            </div>
        </div>
    </div>

    {{-- OFERTA --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4">
        <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
            <span class="material-symbols-outlined text-[#f59e0b] text-[20px]">payments</span>
            <h3 class="text-[13px] font-black text-[#1c2434] uppercase tracking-wide">Oferta da Célula</h3>
        </div>
        <div class="space-y-1.5">
            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Valor via PIX</label>
            <div class="relative flex items-center">
                <span class="absolute left-4 text-[#1c2434] font-bold text-[15px]">R$</span>
                <input type="number" step="0.01" name="offer_pix" x-model.number="offerPix" placeholder="0,00"
                       class="w-full h-14 pl-12 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-[22px] font-bold text-[#1c2434] placeholder:text-slate-300 focus:ring-2 focus:ring-[#f59e0b] focus:border-[#f59e0b] focus:bg-white outline-none transition-all">
            </div>
        </div>
        <div class="space-y-1.5">
            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Valor em Espécie</label>
            <div class="relative flex items-center">
                <span class="absolute left-4 text-[#1c2434] font-bold text-[15px]">R$</span>
                <input type="number" step="0.01" name="offer_cash" x-model.number="offerCash" placeholder="0,00"
                       class="w-full h-14 pl-12 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-[22px] font-bold text-[#1c2434] placeholder:text-slate-300 focus:ring-2 focus:ring-[#f59e0b] focus:border-[#f59e0b] focus:bg-white outline-none transition-all">
            </div>
        </div>
        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200">
            <span class="text-[13px] font-black text-[#1c2434] uppercase tracking-wide">Valor Total Arrecadado</span>
            <span class="text-[22px] font-black text-[#f59e0b]" x-text="formatMoney(totalOffer())"></span>
        </div>
        <div class="flex gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200/60">
            <span class="material-symbols-outlined text-slate-400 text-[18px] shrink-0">info</span>
            <p class="text-[11px] leading-relaxed text-slate-500">Certifique-se de contar o valor duas vezes antes de registrar para evitar divergências no caixa da rede.</p>
        </div>
    </div>
</div>
