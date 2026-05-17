<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCellRequest;
use App\Http\Requests\UpdateCellRequest;
use App\Models\Cell;
use App\Models\HierarchyNode;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CellController extends Controller
{
    public function __construct()
    {
        // Registra a Policy automaticamente para todos os métodos resource
        $this->authorizeResource(Cell::class, 'cell');
    }

    // =========================================================================
    // INDEX
    // =========================================================================

    /**
     * Lista as células visíveis conforme o RBAC do usuário logado.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $cells = Cell::query()
            ->with(['node', 'leader'])
            ->when(
                ! $user->isAdmin() && ! $user->isTreasurer(),
                fn ($q) => $q->whereIn('id', $user->accessibleCellIds())
            )
            ->when($request->filled('search'), fn ($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('city', 'like', "%{$request->search}%")
            )
            ->when($request->filled('meeting_day'), fn ($q) =>
                $q->where('meeting_day', $request->meeting_day)
            )
            ->when($request->boolean('active_only', true), fn ($q) =>
                $q->where('active', true)
            )
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('cells.index', compact('cells'));
    }

    // =========================================================================
    // CREATE / STORE
    // =========================================================================

    public function create(): View
    {
        $nodes   = HierarchyNode::active()->ofType('Sector')->orderBy('name')->get();
        $leaders = User::where('role', 'Leader')->orderBy('name')->get();

        return view('cells.create', compact('nodes', 'leaders'));
    }

    public function store(StoreCellRequest $request): RedirectResponse
    {
        $cell = Cell::create($request->validated());

        // Se um líder foi designado, vincula o cell_id ao usuário
        if ($cell->leader_id) {
            User::where('id', $cell->leader_id)->update(['cell_id' => $cell->id]);
        }

        return redirect()
            ->route('cells.show', $cell)
            ->with('success', "Célula \"{$cell->name}\" criada com sucesso.");
    }

    // =========================================================================
    // SHOW
    // =========================================================================

    public function show(Cell $cell): View
    {
        $cell->load([
            'node',
            'leader',
            'members',
            'visitors' => fn ($q) => $q->whereNotIn('status', ['Converted', 'Inactive']),
            'latestReport',
        ]);

        return view('cells.show', compact('cell'));
    }

    // =========================================================================
    // EDIT / UPDATE
    // =========================================================================

    public function edit(Cell $cell): View
    {
        $nodes   = HierarchyNode::active()->ofType('Sector')->orderBy('name')->get();
        $leaders = User::where('role', 'Leader')->orderBy('name')->get();

        $cell->load('node', 'leader');

        return view('cells.edit', compact('cell', 'nodes', 'leaders'));
    }

    public function update(UpdateCellRequest $request, Cell $cell): RedirectResponse
    {
        $previousLeaderId = $cell->leader_id;
        $cell->update($request->validated());

        // Atualiza cell_id nos usuários quando o líder muda
        if ($request->filled('leader_id') && $previousLeaderId !== $cell->leader_id) {
            // Remove vínculo do líder anterior
            if ($previousLeaderId) {
                User::where('id', $previousLeaderId)->where('cell_id', $cell->id)
                    ->update(['cell_id' => null]);
            }
            // Vincula o novo líder
            User::where('id', $cell->leader_id)->update(['cell_id' => $cell->id]);
        }

        return redirect()
            ->route('cells.show', $cell)
            ->with('success', "Célula \"{$cell->name}\" atualizada com sucesso.");
    }

    // =========================================================================
    // DESTROY
    // =========================================================================

    public function destroy(Cell $cell): RedirectResponse
    {
        $name = $cell->name;
        $cell->delete();

        return redirect()
            ->route('cells.index')
            ->with('success', "Célula \"{$name}\" removida.");
    }

    /**
     * Retorna membros da célula (para API utilitária)
     */
    public function members(Cell $cell): \Illuminate\Http\JsonResponse
    {
        return response()->json($cell->members()->select(['id', 'name'])->orderBy('name')->get());
    }
}
