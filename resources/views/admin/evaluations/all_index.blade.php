@extends('layouts.app')

@section('title', 'Todas as Avaliações')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b pb-4">
        <h1 class="text-lg font-bold">Todas as Avaliações</h1>
        <a href="{{ route('admin.evaluation-results.index') }}" class="px-3 py-2 bg-white border rounded">Voltar</a>
    </div>

    <div class="bg-white rounded-xl border p-4">
        <form method="GET" action="{{ route('admin.evaluations.all') }}" class="grid gap-4 md:grid-cols-4" id="filters-form">
            <div>
                <label class="text-xs font-bold uppercase">Ciclo</label>
                <select name="cycle_id" class="input-neo">
                    <option value="">— Todos —</option>
                    @foreach($cycles as $c)
                        <option value="{{ $c->id }}" {{ (isset($cycleId) && $cycleId == $c->id) ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs font-bold uppercase">Lotação</label>
                <select name="lotacao" class="input-neo">
                    <option value="">— Todas —</option>
                    @foreach($lotacoes as $l)
                        <option value="{{ $l }}" {{ (isset($lotacao) && $lotacao == $l) ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs font-bold uppercase">Servidor (nome)</label>
                <input type="text" name="evaluated_name" value="{{ $evaluatedName ?? '' }}" class="input-neo" placeholder="Nome do servidor">
            </div>

            <div>
                <label class="text-xs font-bold uppercase">Avaliador (nome)</label>
                <input type="text" name="evaluator_name" value="{{ $evaluatorName ?? '' }}" class="input-neo" placeholder="Nome do avaliador">
            </div>

            <div>
                <label class="text-xs font-bold uppercase">Data início (submitted_from)</label>
                <input type="date" name="submitted_from" value="{{ $submittedFrom ?? '' }}" class="input-neo">
            </div>

            <div>
                <label class="text-xs font-bold uppercase">Data fim (submitted_to)</label>
                <input type="date" name="submitted_to" value="{{ $submittedTo ?? '' }}" class="input-neo">
            </div>

            <div>
                <label class="text-xs font-bold uppercase">Nota mínima</label>
                <input type="number" step="0.01" name="score_min" value="{{ $scoreMin ?? '' }}" class="input-neo" placeholder="0.00">
            </div>

            <div>
                <label class="text-xs font-bold uppercase">Nota máxima</label>
                <input type="number" step="0.01" name="score_max" value="{{ $scoreMax ?? '' }}" class="input-neo" placeholder="5.00">
            </div>

            <div>
                <label class="text-xs font-bold uppercase">Categoria</label>
                <input type="text" name="categoria" value="{{ $categoria ?? '' }}" class="input-neo" placeholder="Ex: técnica">
            </div>

            <div>
                <label class="text-xs font-bold uppercase">Status</label>
                <select name="status" class="input-neo">
                    <option value="">— Todos —</option>
                    <option value="submitted" {{ (isset($status) && $status === 'submitted') ? 'selected' : '' }}>Enviada</option>
                    <option value="completed" {{ (isset($status) && $status === 'completed') ? 'selected' : '' }}>Concluída</option>
                    <option value="pending" {{ (isset($status) && $status === 'pending') ? 'selected' : '' }}>Rascunho</option>
                </select>
            </div>

            <div class="md:col-span-4 flex items-end justify-end">
                <div class="flex gap-2">
                    <button type="button" id="preset-30" class="btn-neo px-3 py-2 bg-slate-100 border rounded">Últimos 30 dias</button>
                    <button type="button" id="preset-cycle" class="btn-neo px-3 py-2 bg-slate-100 border rounded">Ciclo atual</button>
                    <button type="submit" class="btn-neo px-4 py-2 bg-blue-600 text-white rounded">Filtrar</button>
                    <div class="relative">
                        <button type="button" id="export-btn" class="btn-neo px-3 py-2 bg-green-600 text-white rounded">Exportar</button>
                        <div id="export-menu" class="absolute right-0 mt-2 bg-white border rounded shadow-md hidden">
                            <a href="#" class="block px-4 py-2 text-sm export-format" data-format="csv">CSV</a>
                            <a href="#" class="block px-4 py-2 text-sm export-format" data-format="xls">XLS (CSV)</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-slate-500 uppercase">
                        <th class="p-2">ID</th>
                        <th class="p-2">Servidor</th>
                        <th class="p-2">Avaliador</th>
                        <th class="p-2">Ciclo</th>
                        <th class="p-2">Lotação</th>
                        <th class="p-2">Nota Final</th>
                        <th class="p-2">Enviada Em</th>
                        <th class="p-2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($evaluations as $ev)
                        <tr class="border-t">
                            <td class="p-2">{{ $ev->id }}</td>
                            <td class="p-2">{{ $ev->evaluated?->name }}</td>
                            <td class="p-2">{{ $ev->evaluator?->name }}</td>
                            <td class="p-2">{{ $ev->cycle?->name }}</td>
                            <td class="p-2">{{ $ev->evaluated?->lotacao }}</td>
                            <td class="p-2">{{ number_format($ev->final_score ?? 0, 2, ',', '.') }}</td>
                            <td class="p-2">{{ $ev->submitted_at ? $ev->submitted_at->format('d/m/Y H:i') : '-' }}</td>
                            <td class="p-2">
                                <a href="{{ route('admin.evaluation-results.show', $ev->id) }}" hx-boost="false" class="text-blue-600">Ver</a>
                                <span class="mx-2">|</span>
                                <a href="{{ route('evaluations.fill', $ev->id) }}" hx-boost="false" class="text-green-600">Abrir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-4 text-center text-slate-500">Nenhuma avaliação encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $evaluations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function(){
            $('select[name="cycle_id"]').select2({ width: '100%' });
            $('select[name="lotacao"]').select2({ width: '100%' });

            $('#preset-30').on('click', function(){
                var to = new Date();
                var from = new Date();
                from.setDate(to.getDate() - 30);
                function fmt(d){ return d.toISOString().slice(0,10); }
                $('input[name=submitted_from]').val(fmt(from));
                $('input[name=submitted_to]').val(fmt(to));
                $('#filters-form').submit();
            });

            $('#preset-cycle').on('click', function(){
                // select current cycle if present
                var current = $('select[name=cycle_id] option[data-current="1"]').val();
                if(current){ $('select[name=cycle_id]').val(current).trigger('change'); }
                $('#filters-form').submit();
            });

            $('#export-btn').on('click', function(){ $('#export-menu').toggle(); });
            $('.export-format').on('click', function(e){
                e.preventDefault();
                var format = $(this).data('format');
                // build URL with current query params
                var params = $('#filters-form').serialize();
                var url = '{{ route('admin.evaluations.all.export') }}' + '?' + params + '&format=' + format;
                window.location = url;
            });
        });
    </script>
@endsection
