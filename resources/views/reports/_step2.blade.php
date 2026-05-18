{{-- STEP 2: Chamada da Célula (Stitch pattern) --}}
<div x-show="step === 2" x-cloak class="space-y-5">

    {{-- CHAMADA NOMINAL --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#f59e0b] text-[20px]"
                      style="font-variation-settings:'FILL' 1">groups</span>
                <h3 class="text-[13px] font-black text-[#1c2434] uppercase tracking-wide">Chamada da Célula</h3>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-[11px] font-semibold text-emerald-600 bg-emerald-50 border border-emerald-100 px-3 py-1 rounded-full"
                      x-text="presentMemberIds.length + ' presentes'"></span>
                <button type="button" @click="toggleSelectAll()"
                        class="text-[12px] font-bold text-[#f59e0b] hover:underline"
                        x-text="presentMemberIds.length === cellMembers.length ? 'Zerar' : 'Todos'"></button>
            </div>
        </div>

        <div class="divide-y divide-slate-50 max-h-[420px] overflow-y-auto custom-scrollbar"
             x-show="cellMembers.length > 0">
            <template x-for="member in cellMembers" :key="member.id">
                <div class="flex items-center justify-between px-5 py-3.5 cursor-pointer hover:bg-slate-50 transition-colors"
                     @click="toggleMember(member.id)">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-[12px] font-black uppercase transition-all"
                             :class="presentMemberIds.includes(member.id)
                                 ? 'bg-emerald-500 text-white'
                                 : 'bg-slate-100 text-slate-500'"
                             x-text="member.name.split(' ').map(n=>n[0]).slice(0,2).join('')"></div>
                        <span class="text-[14px] font-semibold text-slate-700" x-text="member.name"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-bold uppercase tracking-wide px-3 py-1 rounded-full transition-all"
                              :class="presentMemberIds.includes(member.id)
                                  ? 'bg-emerald-100 text-emerald-700'
                                  : 'bg-red-50 text-red-400'"
                              x-text="presentMemberIds.includes(member.id) ? 'Presente' : 'Ausente'"></span>
                    </div>
                    <input type="hidden" name="present_member_ids[]"
                           :value="member.id"
                           :disabled="!presentMemberIds.includes(member.id)">
                </div>
            </template>
        </div>

        <div x-show="cellMembers.length === 0"
             class="py-16 flex flex-col items-center gap-3 text-slate-300">
            <span class="material-symbols-outlined text-[52px]">groups</span>
            <p class="text-[12px] font-semibold uppercase tracking-widest">Selecione a célula no passo anterior</p>
        </div>
    </div>

    {{-- Tip --}}
    <div class="flex items-start gap-3 p-4 bg-slate-100 rounded-xl border border-slate-200/80">
        <span class="material-symbols-outlined text-[#1c2434] text-[20px] shrink-0">info</span>
        <p class="text-[14px] text-[#1c2434]/80">Toque no nome do membro para alternar entre Presente e Ausente.</p>
    </div>
</div>
