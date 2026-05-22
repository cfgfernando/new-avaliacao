@extends('layouts.app')

@section('title', 'Avaliações Arquivadas')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b pb-4">
        <h1 class="text-lg font-bold">Avaliações Arquivadas</h1>
        <a href="{{ route('admin.evaluation-results.index') }}" class="px-3 py-2 bg-white border rounded">Voltar</a>
    </div>

    <div class="bg-white rounded-xl border p-4">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-slate-500 uppercase">
                    <th class="p-2">ID</th>
                    <th class="p-2">Servidor</th>
                    <th class="p-2">Ciclo</th>
                    <th class="p-2">Arquivado Por</th>
                    <th class="p-2">Arquivado Em</th>
                    <th class="p-2">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations as $ev)
                    <tr class="border-t">
                        <td class="p-2">{{ $ev->id }}</td>
                        <td class="p-2">{{ $ev->evaluated?->name }} ({{ $ev->evaluated?->cargo }})</td>
                        <td class="p-2">{{ $ev->cycle?->name }}</td>
                        <td class="p-2">{{ $ev->archived_by ? optional($ev->archivedBy)->name : '-' }}</td>
                        <td class="p-2">{{ $ev->archived_at ? $ev->archived_at->format('d/m/Y H:i') : '-' }}</td>
                        <td class="p-2">
                            <a href="{{ route('admin.evaluation-results.show', $ev->id) }}" hx-boost="false" class="text-blue-600">Ver</a>
                            <span class="mx-2">|</span>
                            <a href="{{ route('evaluations.fill', $ev->id) }}" hx-boost="false" class="text-green-600">Abrir rascunho</a>
                            <span class="mx-2">|</span>
                            <form action="{{ route('admin.evaluations.restore', $ev->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Restaurar este rascunho e torná-lo ativo novamente?');">
                                @csrf
                                <button type="submit" class="text-yellow-600 hover:underline">Restaurar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-slate-500">Nenhuma avaliação arquivada encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $evaluations->links() }}
        </div>
    </div>
</div>
@endsection
