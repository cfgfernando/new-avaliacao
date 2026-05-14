<?php

namespace App\Http\Controllers;

use App\Models\Cell;
use App\Models\Member;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VisitorController extends Controller
{
    // =========================================================================
    // INDEX — Funil de Atendimento
    // =========================================================================

    public function index(Request $request): View
    {
        $visitors = Visitor::with(['assignedCell', 'contactedBy'])
            ->when($request->filled('search'), fn ($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%")
            )
            ->when($request->filled('status'), fn ($q) =>
                $q->where('status', $request->status)
            )
            ->when($request->filled('cell_id'), fn ($q) =>
                $q->where('assigned_cell_id', $request->cell_id)
            )
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        // KPIs do funil
        $funnel = [
            'new'        => Visitor::where('status', 'New')->count(),
            'returning'  => Visitor::where('status', 'Returning')->count(),
            'interested' => Visitor::where('status', 'Interested')->count(),
            'converted'  => Visitor::where('status', 'Converted')->count(),
            'radar'      => Visitor::active()
                ->where(function ($q) {
                    $q->whereNull('last_contact_at')
                      ->orWhere('last_contact_at', '<', now()->subHours(48));
                })->count(),
        ];

        $cells = Cell::where('active', true)->orderBy('name')->pluck('name', 'id');

        return view('visitors.index', compact('visitors', 'funnel', 'cells'));
    }

    // =========================================================================
    // CREATE / STORE
    // =========================================================================

    public function create(): View
    {
        $cells = Cell::where('active', true)->orderBy('name')->get();
        return view('visitors.create', compact('cells'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'assigned_cell_id'=> 'nullable|exists:cells,id',
            'status'          => 'required|in:New,Returning,Interested,Converted,Inactive',
            'how_did_you_know'=> 'nullable|string|max:255',
            'notes'           => 'nullable|string|max:1000',
        ]);

        $visitor = Visitor::create($data);

        return redirect()
            ->route('visitors.show', $visitor)
            ->with('success', "Visitante \"{$visitor->name}\" cadastrado com sucesso.");
    }

    // =========================================================================
    // SHOW
    // =========================================================================

    public function show(Visitor $visitor): View
    {
        $visitor->load(['assignedCell', 'contactedBy']);
        return view('visitors.show', compact('visitor'));
    }

    // =========================================================================
    // EDIT / UPDATE
    // =========================================================================

    public function edit(Visitor $visitor): View
    {
        $cells = Cell::where('active', true)->orderBy('name')->get();
        return view('visitors.edit', compact('visitor', 'cells'));
    }

    public function update(Request $request, Visitor $visitor): RedirectResponse
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'assigned_cell_id'=> 'nullable|exists:cells,id',
            'status'          => 'required|in:New,Returning,Interested,Converted,Inactive',
            'how_did_you_know'=> 'nullable|string|max:255',
            'notes'           => 'nullable|string|max:1000',
        ]);

        $visitor->update($data);

        return redirect()
            ->route('visitors.show', $visitor)
            ->with('success', "Visitante \"{$visitor->name}\" atualizado.");
    }

    // =========================================================================
    // DESTROY
    // =========================================================================

    public function destroy(Visitor $visitor): RedirectResponse
    {
        $name = $visitor->name;
        $visitor->delete();

        return redirect()
            ->route('visitors.index')
            ->with('success', "Visitante \"{$name}\" removido.");
    }

    // =========================================================================
    // CONVERTER → MEMBRO (action)
    // =========================================================================

    public function convert(Request $request, Visitor $visitor): JsonResponse|RedirectResponse
    {
        $visitor->update(['status' => 'Converted']);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Visitante \"{$visitor->name}\" marcado como convertido."]);
        }

        return redirect()
            ->route('visitors.show', $visitor)
            ->with('success', "Visitante \"{$visitor->name}\" marcado como convertido.");
    }

    // =========================================================================
    // REGISTRAR CONTATO (AJAX)
    // =========================================================================

    public function contact(Request $request, Visitor $visitor): JsonResponse
    {
        $request->validate([
            'notes'  => 'nullable|string|max:500',
            'status' => 'required|in:New,Returning,Interested,Converted,Inactive',
        ]);

        $visitor->update([
            'last_contact_at' => now(),
            'contacted_by'    => auth()->id(),
            'notes'           => $request->notes,
            'status'          => $request->status,
        ]);

        return response()->json(['success' => true]);
    // =========================================================================
    // CONSOLIDAÇÃO → TRANSFORMAR EM MEMBRO (View + Action)
    // =========================================================================

    public function consolidate(Visitor $visitor): View
    {
        $cells   = Cell::where('active', true)->orderBy('name')->get();
        $mentors = User::whereIn('role', ['Leader', 'Supervisor'])->orderBy('name')->get();

        return view('visitors.consolidate', compact('visitor', 'cells', 'mentors'));
    }

    public function consolidateStore(Request $request, Visitor $visitor): RedirectResponse
    {
        $data = $request->validate([
            // Dados User
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            // Dados Member
            'cell_id'   => 'required|exists:cells,id',
            'mentor_id' => 'nullable|exists:users,id',
            'phone'     => 'nullable|string|max:20',
            'cpf'       => 'nullable|string|size:14|unique:members,cpf',
        ]);

        // 1. Criar User
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
            'role'     => 'Member',
            'cell_id'  => $data['cell_id'],
        ]);

        // 2. Criar Member
        $member = Member::create([
            'user_id'   => $user->id,
            'mentor_id' => $data['mentor_id'],
            'phone'     => $data['phone'],
            'cpf'       => $data['cpf'],
            'status'    => 'Active',
        ]);

        // 3. Atualizar Visitor
        $visitor->update(['status' => 'Converted']);

        return redirect()
            ->route('members.show', $member)
            ->with('success', "Visitante \"{$visitor->name}\" consolidado como membro com sucesso!");
    }
}
