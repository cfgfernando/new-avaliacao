@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="mb-8">
        <a href="{{ route('admin.evaluation-cycles.index') }}" class="text-xs font-bold text-primary-light hover:text-accent uppercase tracking-wider transition-colors">
            <i class="fas fa-arrow-left mr-1"></i> Voltar para Ciclos
        </a>
        <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight mt-3">Novo Ciclo de Avaliação</h1>
        <p class="text-sm font-bold text-primary-light uppercase tracking-widest mt-1">Configure o período, pesos e nota de corte</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
        <form action="{{ route('admin.evaluation-cycles.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Nome do Ciclo</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Ex: Ciclo 2026.1 - Avaliação Periódica" class="w-full border border-slate-200 rounded-lg px-4 py-3 text-sm font-semibold text-slate-800 focus:border-accent focus:ring-0 outline-none @error('name') border-rose-300 @enderror">
                    @error('name') <p class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Data de Início</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full border border-slate-200 rounded-lg px-4 py-3 text-sm font-semibold text-slate-800 focus:border-accent focus:ring-0 outline-none @error('start_date') border-rose-300 @enderror">
                        @error('start_date') <p class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Data de Fim</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full border border-slate-200 rounded-lg px-4 py-3 text-sm font-semibold text-slate-800 focus:border-accent focus:ring-0 outline-none @error('end_date') border-rose-300 @enderror">
                        @error('end_date') <p class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Nota de Corte</label>
                        <input type="number" step="0.01" min="1" max="5" name="cutoff_score" value="{{ old('cutoff_score', '3.00') }}" class="w-full border border-slate-200 rounded-lg px-4 py-3 text-sm font-bold font-mono text-slate-800 focus:border-accent focus:ring-0 outline-none @error('cutoff_score') border-rose-300 @enderror">
                        @error('cutoff_score') <p class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Status</label>
                        <select name="status" class="w-full border border-slate-200 rounded-lg px-4 py-3 text-sm font-semibold text-slate-800 focus:border-accent focus:ring-0 outline-none @error('status') border-rose-300 @enderror">
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inativo</option>
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }} {{ old('status') === null ? 'selected' : '' }}>Ativo</option>
                            <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Fechado</option>
                        </select>
                        @error('status') <p class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">Restrições Funcionais</label>
                    <div class="flex items-center gap-3 bg-slate-50 p-4 rounded-lg border border-slate-200">
                        <input type="hidden" name="block_on_pad" value="0">
                        <input type="checkbox" name="block_on_pad" id="block_on_pad" value="1" {{ old('block_on_pad', '1') == '1' ? 'checked' : '' }} class="rounded border-slate-200 text-blue-600 focus:ring-blue-500/15 focus:ring-0 outline-none w-4 h-4">
                        <label for="block_on_pad" class="text-xs font-bold text-slate-700 select-none cursor-pointer">Bloquear avaliações de servidores com Processo Administrativo Disciplinar (PAD) ativo</label>
                    </div>
                    @error('block_on_pad') <p class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="border-t border-slate-200 pt-6">
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-tight mb-4">Pesos das Categorias</h4>
                    <p class="text-xs text-slate-500 font-medium mb-4">Defina o peso de cada categoria no cálculo da nota final ponderada.</p>

                    @php
                        $categories = [
                            'assiduidade' => 'Assiduidade e Pontualidade',
                            'disciplina' => 'Disciplina',
                            'iniciativa' => 'Capacidade de Iniciativa',
                            'responsabilidade' => 'Responsabilidade',
                            'cooperacao' => 'Cooperação',
                            'qualidade' => 'Qualidade do Trabalho',
                            'desenvolvimento_rh' => 'Desenvolvimento RH',
                            'avaliacao_usuario' => 'Avaliação pelo Usuário',
                        ];
                        $defaultWeights = old('weights', [
                            'assiduidade' => 2, 'disciplina' => 1, 'iniciativa' => 1,
                            'responsabilidade' => 2, 'cooperacao' => 1, 'qualidade' => 2,
                            'desenvolvimento_rh' => 1, 'avaliacao_usuario' => 1,
                        ]);
                    @endphp

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($categories as $key => $label)
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono block mb-2">{{ $label }}</label>
                                <input type="number" name="weights[{{ $key }}]" value="{{ $defaultWeights[$key] ?? 1 }}" min="0" max="10" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm font-bold font-mono text-slate-800 focus:border-accent focus:ring-0 outline-none bg-white text-center">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="border-t border-slate-200 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-tight">Metas Quantitativas Globais (RH)</h4>
                            <p class="text-xs text-slate-500 font-medium mt-1">Defina as metas quantitativas padrões que todos os servidores avaliados deverão cumprir.</p>
                        </div>
                        <button type="button" id="add-global-goal" class="px-3 py-1.5 text-xs font-bold text-white bg-accent hover:bg-accent-hover rounded-lg shadow-sm shadow-blue-500/10 transition flex items-center gap-1.5">
                            <i class="fas fa-plus"></i> Adicionar Meta
                        </button>
                    </div>

                    <div class="overflow-x-auto bg-slate-50 rounded-xl border border-slate-200 p-4">
                        <table class="w-full text-left border-collapse" id="global-goals-table">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="pb-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono w-5/12">Descrição da Meta</th>
                                    <th class="pb-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono w-3/12">Métrica</th>
                                    <th class="pb-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono w-2/12 text-center">Alvo Pactuado</th>
                                    <th class="pb-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono w-1/12 text-center">Peso</th>
                                    <th class="pb-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest font-mono w-1/12 text-center">Ações</th>
                                </tr>
                            </thead>
                            @php
                                $globalGoals = old('global_goals', []);
                            @endphp
                            <tbody id="global-goals-tbody" class="divide-y divide-slate-100">
                                @forelse($globalGoals as $index => $goal)
                                    <tr class="goal-row" data-index="{{ $index }}">
                                        <td class="py-3 pr-2">
                                            <input type="text" name="global_goals[{{ $index }}][description]" value="{{ $goal['description'] ?? '' }}" required placeholder="Ex: Processos Analisados" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 focus:border-accent focus:ring-0 outline-none bg-white">
                                        </td>
                                        <td class="py-3 pr-2">
                                            <input type="text" name="global_goals[{{ $index }}][metric]" value="{{ $goal['metric'] ?? '' }}" required placeholder="Ex: Processos" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 focus:border-accent focus:ring-0 outline-none bg-white">
                                        </td>
                                        <td class="py-3 pr-2">
                                            <input type="number" step="0.01" min="0" name="global_goals[{{ $index }}][target_value]" value="{{ $goal['target_value'] ?? '' }}" required placeholder="0.00" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs font-bold font-mono text-slate-800 text-center focus:border-accent focus:ring-0 outline-none bg-white">
                                        </td>
                                        <td class="py-3 pr-2">
                                            <input type="number" step="0.1" min="0" max="100" name="global_goals[{{ $index }}][weight]" value="{{ $goal['weight'] ?? '' }}" required placeholder="1.0" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs font-bold font-mono text-slate-800 text-center focus:border-accent focus:ring-0 outline-none bg-white">
                                        </td>
                                        <td class="py-3 text-center">
                                            <button type="button" class="remove-goal text-rose-500 hover:text-rose-700 transition duration-150" title="Remover Meta">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="no-goals-row" class="text-center">
                                        <td colspan="5" class="py-6 text-xs font-semibold text-slate-400 italic">Nenhuma meta cadastrada para este ciclo. Clique em "Adicionar Meta" para começar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="border-t border-slate-200 pt-6 flex items-center justify-end gap-4">
                    <a href="{{ route('admin.evaluation-cycles.index') }}" class="px-5 py-2.5 text-sm font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-lg transition">Cancelar</a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-accent hover:bg-accent-hover rounded-lg shadow-md shadow-blue-500/10 transition flex items-center gap-2">
                        <i class="fas fa-save text-[14px]"></i>
                        Salvar Ciclo
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let goalIndex = $('#global-goals-tbody tr.goal-row').length;

    function checkEmptyTable() {
        if ($('#global-goals-tbody tr.goal-row').length === 0) {
            if ($('#no-goals-row').length === 0) {
                $('#global-goals-tbody').append(`
                    <tr id="no-goals-row" class="text-center">
                        <td colspan="5" class="py-6 text-xs font-semibold text-slate-400 italic">Nenhuma meta cadastrada para este ciclo. Clique em "Adicionar Meta" para começar.</td>
                    </tr>
                `);
            } else {
                $('#no-goals-row').show();
            }
        } else {
            $('#no-goals-row').hide();
        }
    }

    // Inicializa a tabela
    checkEmptyTable();

    $('#add-global-goal').click(function() {
        goalIndex++;
        const row = `
            <tr class="goal-row" data-index="${goalIndex}">
                <td class="py-3 pr-2">
                    <input type="text" name="global_goals[${goalIndex}][description]" required placeholder="Ex: Processos Analisados" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 focus:border-accent focus:ring-0 outline-none bg-white">
                </td>
                <td class="py-3 pr-2">
                    <input type="text" name="global_goals[${goalIndex}][metric]" required placeholder="Ex: Processos" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-800 focus:border-accent focus:ring-0 outline-none bg-white">
                </td>
                <td class="py-3 pr-2">
                    <input type="number" step="0.01" min="0" name="global_goals[${goalIndex}][target_value]" required placeholder="0.00" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs font-bold font-mono text-slate-800 text-center focus:border-accent focus:ring-0 outline-none bg-white">
                </td>
                <td class="py-3 pr-2">
                    <input type="number" step="0.1" min="0" max="100" name="global_goals[${goalIndex}][weight]" required placeholder="1.0" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs font-bold font-mono text-slate-800 text-center focus:border-accent focus:ring-0 outline-none bg-white">
                </td>
                <td class="py-3 text-center">
                    <button type="button" class="remove-goal text-rose-500 hover:text-rose-700 transition duration-150" title="Remover Meta">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        `;
        
        // Remove no-goals-row se existir antes de dar append
        $('#no-goals-row').remove();
        
        $('#global-goals-tbody').append(row);
        checkEmptyTable();
    });

    $(document).on('click', '.remove-goal', function() {
        $(this).closest('tr').remove();
        checkEmptyTable();
    });
});
</script>
@endsection
