@extends('layouts.app')

@section('title', 'Gestão de Malotes')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════════════════
     ROTEAMENTO DE LAYOUT POR PERFIL
     - Admin / Tesoureiro → Visão global (todos os malotes)
     - Supervisor         → Malotes do seu setor
     - Líder de Célula    → Apenas sua própria célula
═══════════════════════════════════════════════════════════════════════════ --}}

@php $user = auth()->user(); @endphp

@if($user->isLeader())
    {{-- ══════════════════════════════════════════
         MODO LÍDER DE CÉLULA
    ══════════════════════════════════════════ --}}
    @include('reports._leader_view', ['reports' => $reports, 'user' => $user])
@else
    {{-- ══════════════════════════════════════════
         MODO ADMIN / TESOUREIRO / SUPERVISOR
    ══════════════════════════════════════════ --}}
    @include('reports._admin_view', ['reports' => $reports, 'user' => $user])
@endif

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // Feedback de flash
    @if(session('success'))
        // Sucesso: {{ addslashes(session('success')) }}
    @endif
    @if(session('error'))
        // Erro: {{ addslashes(session('error')) }}
    @endif
});
</script>
@endpush
