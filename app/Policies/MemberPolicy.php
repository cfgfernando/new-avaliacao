<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemberPolicy
{
    use HandlesAuthorization;

    /**
     * Admin sempre tem acesso total.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Listar membros: todos os papéis, filtrado no controller por escopo.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Ver um membro específico:
     * - Tesoureiro: acesso total (auditoria financeira)
     * - Supervisor: apenas membros das células do seu nó
     * - Líder: apenas membros da sua própria célula
     */
    public function view(User $user, Member $member): bool
    {
        return match ($user->role) {
            'Treasurer' => true,
            'Supervisor' => $user->accessibleCellIds()->contains($member->user->cell_id),
            'Leader'     => $member->user->cell_id === $user->cell_id,
            default      => false,
        };
    }

    /**
     * Criar membro (perfil espiritual):
     * - Supervisor: em qualquer célula do seu nó
     * - Líder: apenas na sua própria célula
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['Supervisor', 'Leader']);
    }

    /**
     * Editar membro: mesmas regras de view.
     */
    public function update(User $user, Member $member): bool
    {
        return match ($user->role) {
            'Supervisor' => $user->accessibleCellIds()->contains($member->user->cell_id),
            'Leader'     => $member->user->cell_id === $user->cell_id,
            default      => false,
        };
    }

    /**
     * Excluir (soft-delete): somente Admin (before) e Supervisor do nó.
     */
    public function delete(User $user, Member $member): bool
    {
        return $user->isSupervisor()
            && $user->accessibleCellIds()->contains($member->user->cell_id);
    }

    public function restore(User $user, Member $member): bool
    {
        return false; // Apenas Admin via before()
    }

    public function forceDelete(User $user, Member $member): bool
    {
        return false; // Apenas Admin via before()
    }
}
