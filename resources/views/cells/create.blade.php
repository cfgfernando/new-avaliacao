<x-app-layout>
    @section('header_title', 'Cadastrar Unidade')

    <div class="max-w-5xl mx-auto">
        <form action="{{ route('cells.store') }}" method="POST" class="flex flex-col gap-8">
            @csrf

            <!-- SECTION: INFORMAÇÕES BÁSICAS -->
            <div class="card-elite">
                <div class="p-8 border-b border-black/5 dark:border-white/5 bg-gray-500/5 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg gold-gradient flex items-center justify-center text-white shadow-accent">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-display font-extrabold text-title uppercase tracking-tighter">Identificação da Célula</h3>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Dados essenciais para registro</p>
                    </div>
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest">Nome da Célula <span class="text-accent">*</span></label>
                        <input type="text" name="name" required class="input-elite" placeholder="Ex: Célula Shalom">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest">Líder Responsável <span class="text-accent">*</span></label>
                        <select name="leader_id" required class="input-elite appearance-none">
                            <option value="">Selecione um líder...</option>
                            @foreach($leaders as $leader)
                                <option value="{{ $leader->id }}">{{ $leader->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest">Dia da Reunião <span class="text-accent">*</span></label>
                        <select name="meeting_day" required class="input-elite appearance-none">
                            <option value="Segunda">Segunda-feira</option>
                            <option value="Terça">Terça-feira</option>
                            <option value="Quarta">Quarta-feira</option>
                            <option value="Quinta">Quinta-feira</option>
                            <option value="Sexta">Sexta-feira</option>
                            <option value="Sábado" selected>Sábado</option>
                            <option value="Domingo">Domingo</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest">Horário <span class="text-accent">*</span></label>
                        <input type="time" name="meeting_time" value="19:30" required class="input-elite">
                    </div>
                </div>
            </div>

            <!-- SECTION: LOCALIZAÇÃO -->
            <div class="card-elite">
                <div class="p-8 border-b border-black/5 dark:border-white/5 bg-gray-500/5 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg gold-gradient flex items-center justify-center text-white shadow-accent">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-display font-extrabold text-title uppercase tracking-tighter">Geolocalização & Endereço</h3>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Onde as reuniões acontecem</p>
                    </div>
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest">CEP</label>
                        <div class="relative group">
                            <input type="text" name="zip_code" id="zip_code" class="input-elite w-full pr-10" placeholder="00000-000">
                            <i class="fas fa-search absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-accent cursor-pointer" id="btn-search-cep"></i>
                        </div>
                    </div>

                    <div class="md:col-span-2 flex flex-col gap-2">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest">Logradouro / Rua</label>
                        <input type="text" name="address" id="address" class="input-elite" placeholder="Av. das Oliveiras, 123">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest">Bairro</label>
                        <input type="text" name="neighborhood" id="neighborhood" class="input-elite" placeholder="Jardim do Éden">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest">Cidade</label>
                        <input type="text" name="city" id="city" class="input-elite" placeholder="Cidade Santa">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest">Estado (UF)</label>
                        <input type="text" name="state" id="state" class="input-elite" placeholder="SP">
                    </div>
                </div>
            </div>

            <!-- FORM ACTIONS -->
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('cells.index') }}" class="px-8 py-3 text-gray-500 font-bold hover:text-accent transition-all uppercase tracking-widest text-xs">Cancelar</a>
                <button type="submit" class="btn-primary min-w-[200px] uppercase tracking-[0.15em] text-xs py-4">
                    Confirmar Cadastro
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#zip_code').inputmask('99999-999');

            $('#btn-search-cep').on('click', function() {
                let cep = $('#zip_code').val().replace(/\D/g, '');
                if (cep.length === 8) {
                    $(this).addClass('fa-spin');
                    $.getJSON(`https://viacep.com.br/ws/${cep}/json/`, function(data) {
                        if (!data.erro) {
                            $('#address').val(data.logradouro);
                            $('#neighborhood').val(data.bairro);
                            $('#city').val(data.localidade);
                            $('#state').val(data.uf);
                        }
                        $('#btn-search-cep').removeClass('fa-spin');
                    });
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
