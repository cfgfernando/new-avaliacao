<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Cell;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Member::class, 'member');
    }

    /**
     * Busca de membros para Select2 AJAX.
     */
    public function search(Request $request)
    {
        $term = $request->q;
        if (strlen($term) < 3) return response()->json([]);

        $members = Member::query()
            ->whereHas('user', function($q) use ($term) {
                $q->where('name', 'like', "%{$term}%");
            })
            ->with('user')
            ->limit(15)
            ->get()
            ->map(function($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->user->name
                ];
            });

        return response()->json($members);
    }

    // =========================================================================
    // INDEX
    // =========================================================================

    /**
     * Lista membros filtrados pelo escopo RBAC do usuário logado.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $members = Member::query()
            ->with(['user.cell', 'mentor'])
            ->when(
                ! $user->isAdmin() && ! $user->isTreasurer(),
                fn ($q) => $q->whereHas('user', fn ($uq) =>
                    $uq->whereIn('cell_id', $user->accessibleCellIds())
                )
            )
            ->when($request->filled('search'), fn ($q) =>
                $q->whereHas('user', fn ($uq) =>
                    $uq->where('name', 'like', "%{$request->search}%")
                       ->orWhere('email', 'like', "%{$request->search}%")
                )
            )
            ->when($request->filled('status'), fn ($q) =>
                $q->where('status', $request->status)
            )
            ->when($request->filled('cell_id'), fn ($q) =>
                $q->whereHas('user', fn ($uq) =>
                    $uq->where('cell_id', $request->cell_id)
                )
            )
            ->when($request->boolean('baptized_only'), fn ($q) =>
                $q->baptized()
            )
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        // Para o filtro de células no frontend (escopo RBAC)
        $accessibleCells = Cell::whereIn('id', $user->accessibleCellIds())
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('members.index', compact('members', 'accessibleCells'));
    }

    // =========================================================================
    // CREATE / STORE
    // =========================================================================

    public function create(Request $request): View
    {
        $user = $request->user();

        // Células disponíveis para seleção (respeitando o escopo RBAC)
        $cells   = Cell::whereIn('id', $user->accessibleCellIds())->orderBy('name')->get();
        $mentors = User::whereIn('role', ['Leader', 'Supervisor'])
            ->orderBy('name')
            ->get();

        return view('members.create', compact('cells', 'mentors'));
    }

    public function store(StoreMemberRequest $request): RedirectResponse
    {
        $member = Member::create($request->validated());

        return redirect()
            ->route('members.show', $member)
            ->with('success', "Membro \"{$member->user->name}\" cadastrado com sucesso.");
    }

    // =========================================================================
    // SHOW
    // =========================================================================

    public function show(Member $member): View
    {
        $member->load([
            'user.cell.node',
            'mentor',
            'disciples' => fn ($q) => $q->with('user'),
        ]);

        return view('members.show', compact('member'));
    }

    // =========================================================================
    // EDIT / UPDATE
    // =========================================================================

    public function edit(Member $member): View
    {
        $user = request()->user();

        $member->load('user', 'mentor');

        $cells   = Cell::whereIn('id', $user->accessibleCellIds())->orderBy('name')->get();
        $mentors = User::whereIn('role', ['Leader', 'Supervisor'])
            ->where('id', '!=', $member->user_id)
            ->orderBy('name')
            ->get();

        return view('members.edit', compact('member', 'cells', 'mentors'));
    }

    public function update(UpdateMemberRequest $request, Member $member): RedirectResponse
    {
        $member->update($request->validated());

        return redirect()
            ->route('members.show', $member)
            ->with('success', "Perfil de \"{$member->user->name}\" atualizado com sucesso.");
    }

    // =========================================================================
    // DESTROY
    // =========================================================================

    public function destroy(Member $member): RedirectResponse
    {
        $name = $member->user->name;
        $member->delete();

        return redirect()
            ->route('members.index')
            ->with('success', "Perfil de membro de \"{$name}\" removido.");
    }
}
