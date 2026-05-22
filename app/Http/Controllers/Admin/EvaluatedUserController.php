<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditService;

class EvaluatedUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $officeId = $request->get('office_id');
        $group = $request->get('evaluation_group');

        $query = User::where('role', 'Servidor');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($officeId) {
            $query->where('office_id', $officeId);
        }

        if ($group) {
            $query->where('evaluation_group', $group);
        }

        $users = $query->with('office')->paginate(10)->withQueryString();
        $offices = Office::where('is_active', true)->orderBy('name')->get();

        return view('admin.evaluated-users.index', compact('users', 'offices', 'search', 'officeId', 'group'));
    }

    public function create()
    {
        $offices = Office::where('is_active', true)->orderBy('name')->get();
        $evaluators = User::orderBy('name')->get();
        return view('admin.evaluated-users.create', compact('offices', 'evaluators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'registration_number' => 'required|string|max:50|unique:users,registration_number',
            'cargo' => 'required|string|max:100',
            'office_id' => 'required|exists:offices,id',
            'evaluation_group' => 'required|in:geral,saude,guarda,educacao',
            'evaluator_id' => 'nullable|exists:users,id',
            'has_active_pad' => 'nullable',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'registration_number' => $validated['registration_number'],
            'cargo' => $validated['cargo'],
            'office_id' => $validated['office_id'],
            'evaluation_group' => $validated['evaluation_group'],
            'evaluator_id' => $validated['evaluator_id'] ?? null,
            'has_active_pad' => $request->has('has_active_pad'),
            'role' => 'Servidor',
            'password' => Hash::make($validated['password']),
        ]);

        AuditService::log('CREATE_EVALUATED_USER', [
            'user_id' => $user->id,
            'name' => $user->name,
            'registration_number' => $user->registration_number,
        ]);

        return redirect()->route('admin.evaluated-users.index')->with('success', 'Servidor avaliado cadastrado com sucesso.');
    }

    public function edit($id)
    {
        $evaluatedUser = User::findOrFail($id);
        $offices = Office::where('is_active', true)->orderBy('name')->get();
        $evaluators = User::where('id', '!=', $evaluatedUser->id)->orderBy('name')->get();
        return view('admin.evaluated-users.edit', compact('evaluatedUser', 'offices', 'evaluators'));
    }

    public function update(Request $request, $id)
    {
        $evaluatedUser = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $evaluatedUser->id,
            'registration_number' => 'required|string|max:50|unique:users,registration_number,' . $evaluatedUser->id,
            'cargo' => 'required|string|max:100',
            'office_id' => 'required|exists:offices,id',
            'evaluation_group' => 'required|in:geral,saude,guarda,educacao',
            'evaluator_id' => 'nullable|exists:users,id',
            'has_active_pad' => 'nullable',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'registration_number' => $validated['registration_number'],
            'cargo' => $validated['cargo'],
            'office_id' => $validated['office_id'],
            'evaluation_group' => $validated['evaluation_group'],
            'evaluator_id' => $validated['evaluator_id'] ?? null,
            'has_active_pad' => $request->has('has_active_pad'),
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $evaluatedUser->update($data);

        AuditService::log('UPDATE_EVALUATED_USER', [
            'user_id' => $evaluatedUser->id,
            'updated_fields' => $validated,
        ]);

        return redirect()->route('admin.evaluated-users.index')->with('success', 'Servidor avaliado atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $evaluatedUser = User::findOrFail($id);
        if ($evaluatedUser->evaluations()->count() > 0 || $evaluatedUser->evaluationsAsEvaluator()->count() > 0) {
            return redirect()->route('admin.evaluated-users.index')->with('error', 'Não é possível excluir este servidor pois existem avaliações associadas a ele.');
        }

        $evaluatedUser->delete();

        AuditService::log('DELETE_EVALUATED_USER', [
            'user_id' => $evaluatedUser->id,
            'name' => $evaluatedUser->name,
        ]);

        return redirect()->route('admin.evaluated-users.index')->with('success', 'Servidor avaliado excluído com sucesso.');
    }
}
