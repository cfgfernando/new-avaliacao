<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WeeklyReport;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

/**
 * WeeklyReportPolicy — Controla acesso ao relatório semanal com
 * implementação do "Sistema de Lock de Malotes".
 *
 * REGRA DO MALOTE:
 *   Draft       → Líder pode editar/submeter | Supervisor/Admin podem editar/deletar
 *   Submitted   → MALOTE FECHADO para Líder (403) | Apenas Tesoureiro/Admin podem conciliar
 *   Conciliated → IMUTÁVEL para todos (exceto Admin via before())
 */
class WeeklyReportPolicy
{
    use HandlesAuthorization;

    /**
     * Admin tem acesso irrestrito a qualquer ação.
     * Retorna null para continuar para os métodos específicos.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Listar relatórios: todos os papéis (filtrado por escopo no Controller).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Ver um relatório:
     * - Tesoureiro/Supervisor: acesso de leitura total dentro do seu escopo
     * - Líder: apenas da própria célula
     */
    public function view(User $user, WeeklyReport $report): bool
    {
        return match ($user->role) {
            'Treasurer' => true,
            'Supervisor' => $user->accessibleCellIds()->contains($report->cell_id),
            'Leader'     => $user->cell_id === $report->cell_id,
            default      => false,
        };
    }

    /**
     * Criar relatório:
     * - Líder: apenas para a própria célula
     * - Supervisor: para qualquer célula do seu nó
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['Leader', 'Supervisor']);
    }

    /**
     * LOCK DO MALOTE — Editar relatório:
     *
     * Relatórios com status != 'Draft' estão BLOQUEADOS para edição.
     * Um Líder não pode editar um malote que já foi submetido.
     * Supervisor pode corrigir rascunhos dentro do seu nó.
     */
    public function update(User $user, WeeklyReport $report): bool
    {
        // ✦ MALOTE FECHADO: Apenas Admin (before()) pode forçar edição
        if ($report->status !== 'Draft') {
            Log::warning("Tentativa de edição em malote bloqueado (Status: {$report->status})", [
                'user_id' => $user->id,
                'report_id' => $report->id,
                'ip' => request()->ip()
            ]);
            return false;
        }

        return match ($user->role) {
            'Supervisor' => $user->accessibleCellIds()->contains($report->cell_id),
            'Leader'     => $user->cell_id === $report->cell_id,
            default      => false,
        };
    }

    /**
     * LOCK DO MALOTE — Excluir relatório:
     * Somente rascunhos podem ser excluídos e apenas por Supervisor/Admin.
     * Líderes jamais excluem (apenas submetem ou descartam via rascunho).
     */
    public function delete(User $user, WeeklyReport $report): bool
    {
        // ✦ MALOTE FECHADO: não pode deletar Submitted ou Conciliated
        if ($report->status !== 'Draft') {
            return false;
        }

        return $user->isSupervisor()
            && $user->accessibleCellIds()->contains($report->cell_id);
    }

    /**
     * Submeter relatório (Draft → Submitted):
     * Exclusivo do Líder da própria célula OU Supervisor do nó.
     * Só é possível se o relatório estiver em Draft.
     */
    public function submit(User $user, WeeklyReport $report): bool
    {
        if (!$report->canBeSubmitted()) {
            return false; // Já foi submetido ou conciliado
        }

        return match ($user->role) {
            'Supervisor' => $user->accessibleCellIds()->contains($report->cell_id),
            'Leader'     => $user->cell_id === $report->cell_id,
            default      => false,
        };
    }

    /**
     * Conciliar malote (Submitted → Conciliated):
     * Exclusivo do Tesoureiro ou Admin.
     * Só é possível se o relatório estiver em Submitted.
     */
    public function conciliate(User $user, WeeklyReport $report): bool
    {
        if (!$report->canBeConciliated()) {
            return false; // Não está no status correto
        }

        return $user->isTreasurer(); // Admin tratado no before()
    }

    /**
     * Restaurar (soft-delete): apenas Admin via before().
     */
    public function restore(User $user, WeeklyReport $report): bool
    {
        return false;
    }

    /**
     * Exclusão permanente: apenas Admin via before().
     */
    public function forceDelete(User $user, WeeklyReport $report): bool
    {
        return false;
    }
}
