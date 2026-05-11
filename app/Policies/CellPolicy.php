<?php

namespace App\Policies;

use App\Models\Cell;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CellPolicy
{
    use HandlesAuthorization;

    /**
     * Admin sempre tem acesso total (before gate).
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null; // Continua para o método específico
    }

    /**
     * Listar células: todos os papéis podem, filtrado pelo controller.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Ver uma célula específica.
     * - Tesoureiro: acesso total (leitura)
     * - Supervisor: apenas células do seu nó ou nós filhos
     * - Líder: apenas a própria célula
     */
    public function view(User $user, Cell $cell): bool
    {
        return match ($user->role) {
            'Treasurer' => true,
            'Supervisor' => $this->supervisorCanAccessCell($user, $cell),
            'Leader'     => $user->cell_id === $cell->id,
            default      => false,
        };
    }

    /**
     * Criar célula: Admin (before) ou Supervisor.
     */
    public function create(User $user): bool
    {
        return $user->isSupervisor();
    }

    /**
     * Editar célula:
     * - Supervisor: apenas células do seu nó
     * - Líder: apenas a própria célula (dados básicos)
     */
    public function update(User $user, Cell $cell): bool
    {
        return match ($user->role) {
            'Supervisor' => $this->supervisorCanAccessCell($user, $cell),
            'Leader'     => $user->cell_id === $cell->id,
            default      => false,
        };
    }

    /**
     * Excluir célula: somente Admin (tratado no before).
     */
    public function delete(User $user, Cell $cell): bool
    {
        return false; // Admin tratado no before(); demais papéis nunca deletam
    }

    /**
     * Restaurar célula soft-deleted: somente Admin.
     */
    public function restore(User $user, Cell $cell): bool
    {
        return false;
    }

    /**
     * Exclusão permanente: somente Admin.
     */
    public function forceDelete(User $user, Cell $cell): bool
    {
        return false;
    }

    // =========================================================================
    // HELPERS INTERNOS
    // =========================================================================

    /**
     * Um Supervisor pode acessar uma célula se ela pertence ao seu nó
     * ou a qualquer nó filho direto do seu nó.
     */
    private function supervisorCanAccessCell(User $user, Cell $cell): bool
    {
        if (! $user->node_id) {
            return false;
        }

        // Célula pertence diretamente ao nó do supervisor
        if ($cell->node_id === $user->node_id) {
            return true;
        }

        // Célula pertence a um nó filho (ex: Supervisor de Área → Setores)
        return $cell->node?->parent_id === $user->node_id;
    }
}
