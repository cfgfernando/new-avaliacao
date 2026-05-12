<x-app-layout>
    @section('title', 'Novo Membro')

    <div class="max-w-4xl mx-auto space-y-8">
        <div class="flex items-center justify-between">
            <a href="{{ route('members.index') }}" class="text-primary-light hover:text-accent font-bold text-sm flex items-center gap-2 transition-all">
                <i class="fas fa-arrow-left"></i>
                Voltar para Lista
            </a>
        </div>

        <div class="card-neo">
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-primary-dark">Cadastro de Membro</h2>
                <p class="text-primary-light text-sm mt-1">Preencha os dados abaixo para registrar um novo membro.</p>
            </div>

            <form action="{{ route('members.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Nome -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-primary-light">Nome Completo</label>
                        <input type="text" name="name" required class="input-neo" placeholder="Ex: João Silva">
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-primary-light">Email</label>
                        <input type="email" name="email" required class="input-neo" placeholder="joao@email.com">
                    </div>

                    <!-- Célula -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-primary-light">Célula</label>
                        <select name="cell_id" class="input-neo">
                            <option value="">Selecione uma célula...</option>
                            @foreach($cells as $cell)
                                <option value="{{ $cell->id }}">{{ $cell->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Mentor -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-primary-light">Mentor (Discipulador)</label>
                        <select name="mentor_id" class="input-neo">
                            <option value="">Selecione um mentor...</option>
                            @foreach($mentors as $mentor)
                                <option value="{{ $mentor->id }}">{{ $mentor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-8 border-t border-gray-100">
                    <!-- Status -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-primary-light">Status Inicial</label>
                        <select name="status" class="input-neo">
                            <option value="Active">Ativo</option>
                            <option value="Visitor">Visitante</option>
                            <option value="Converted">Convertido</option>
                        </select>
                    </div>

                    <!-- Batizado -->
                    <div class="flex items-center gap-4 pt-8">
                        <input type="checkbox" name="is_baptized" value="1" class="w-5 h-5 rounded border-gray-300 text-accent focus:ring-accent">
                        <label class="text-sm font-bold text-primary-dark uppercase tracking-tight">Batizado</label>
                    </div>

                    <!-- Dizimista -->
                    <div class="flex items-center gap-4 pt-8">
                        <input type="checkbox" name="is_tither" value="1" class="w-5 h-5 rounded border-gray-300 text-accent focus:ring-accent">
                        <label class="text-sm font-bold text-primary-dark uppercase tracking-tight">Dizimista</label>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full btn-neo btn-primary py-4 text-base">
                        <i class="fas fa-check-circle"></i>
                        Confirmar Cadastro
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
