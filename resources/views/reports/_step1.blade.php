{{-- STEP 1: Informações Gerais (Stitch Design) --}}
<div x-show="step === 1" class="space-y-5">

    {{-- HERO IMAGE --}}
    <div class="relative h-48 w-full rounded-xl overflow-hidden shadow-md">
        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBoBG0bHKsyPMaBwL1sYHpalOd53NbREeGSgxsgJtJHfZOekGZM3TViy1Lwo-eiB7s9mO7EwkQeEfOtethTIcbFrbm6OQdDOmruzjkSo0W7TK-kmO9f3bzgfcW8-t9SY9mAm-Lj22w4zFHlG94fTn-m4NDYRFML1OfyGhc9jBJUuegqnJIxHCBpgeBpHMoq0KI7x3LVksMvh6oGHneJ95TOLF-ig3BUvv5uH0oNrrqzi9zcA2uM9E0Ou3G0tZ5LXB3QcpxXaqbA2DY"
             alt="Reunião de Célula"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-[#1c2434]/85 via-[#1c2434]/20 to-transparent"></div>
        <div class="absolute bottom-4 left-5 text-white">
            <p class="text-[12px] font-black uppercase tracking-[0.2em]">Sistema MDA</p>
            <p class="text-[14px] opacity-90 mt-0.5">Inicie o registro das atividades da semana</p>
        </div>
    </div>

    {{-- FORM FIELDS CARD --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-6">

        {{-- Célula (só para admin/supervisor) --}}
        @if(isset($cells) && count($cells) > 1)
        <div class="space-y-2">
            <label class="flex items-center gap-2 text-[15px] font-bold text-[#1c2434]">
                <span class="material-symbols-outlined text-[18px]">account_tree</span>
                Célula
            </label>
            <select name="cell_id" required
                    class="w-full h-12 px-4 bg-white border border-slate-200 rounded-lg text-[15px]
                           focus:ring-2 focus:ring-[#f59e0b] focus:border-[#f59e0b] outline-none transition-all"
                    x-model="cellId" @change="fetchMembers()">
                <option value="">Selecione a Célula...</option>
                @foreach($cells as $cell)
                    <option value="{{ $cell->id }}" data-location="{{ $cell->address }}">{{ $cell->name }}</option>
                @endforeach
            </select>
        </div>
        @else
            {{-- Célula única: campo hidden --}}
            <input type="hidden" name="cell_id" value="{{ $cells->first()->id ?? '' }}">
        @endif

        {{-- Data da Reunião --}}
        <div class="space-y-2">
            <label class="flex items-center gap-2 text-[15px] font-bold text-[#1c2434]">
                <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                Data da Reunião
            </label>
            <input type="date" name="meeting_date" required
                   x-model="meetingDate"
                   class="w-full h-12 px-4 bg-white border border-slate-200 rounded-lg text-[15px]
                          focus:ring-2 focus:ring-[#f59e0b] focus:border-[#f59e0b] outline-none transition-all">
        </div>

        {{-- Tema da Palavra --}}
        <div class="space-y-2">
            <label class="flex items-center gap-2 text-[15px] font-bold text-[#1c2434]">
                <span class="material-symbols-outlined text-[18px]">menu_book</span>
                Tema da Palavra
            </label>
            <input type="text" name="word_theme"
                   value="{{ $report->word_theme ?? '' }}"
                   placeholder="Ex: A Importância da Comunhão"
                   class="w-full h-12 px-4 bg-white border border-slate-200 rounded-lg text-[15px]
                          placeholder:text-slate-400
                          focus:ring-2 focus:ring-[#f59e0b] focus:border-[#f59e0b] outline-none transition-all">
        </div>

        {{-- Local da Reunião --}}
        <div class="space-y-2">
            <label class="flex items-center gap-2 text-[15px] font-bold text-[#1c2434]">
                <span class="material-symbols-outlined text-[18px]">location_on</span>
                Local da Reunião
            </label>
            <div class="flex gap-2">
                <input type="text" name="meeting_location"
                       x-model="meetingLocation"
                       placeholder="Digite o endereço ou nome do anfitrião"
                       class="flex-1 h-12 px-4 bg-white border border-slate-200 rounded-lg text-[15px]
                              placeholder:text-slate-400
                              focus:ring-2 focus:ring-[#f59e0b] focus:border-[#f59e0b] outline-none transition-all">
                <button type="button"
                        class="w-12 h-12 flex items-center justify-center bg-slate-100 border border-slate-200
                               rounded-lg hover:bg-slate-200 transition-all text-[#1c2434]">
                    <span class="material-symbols-outlined text-[20px]">my_location</span>
                </button>
            </div>
        </div>
    </div>

    {{-- TIP CARD --}}
    <div class="flex items-start gap-3 p-4 bg-slate-100 rounded-xl border border-slate-200/80">
        <span class="material-symbols-outlined text-[#1c2434] text-[20px] shrink-0 mt-0.5">info</span>
        <p class="text-[14px] text-[#1c2434]/80 leading-relaxed">
            Preencha os dados básicos corretamente para garantir a precisão do histórico da sua célula.
        </p>
    </div>

</div>
