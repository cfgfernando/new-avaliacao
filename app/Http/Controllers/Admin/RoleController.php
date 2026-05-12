<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Services\AuditService;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('order')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string|max:255',
            'permissions' => 'array'
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
            'guard_name' => 'web' // default
        ]);

        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        AuditService::log('CREATE_ROLE', [
            'role_id' => $role->id,
            'name' => $role->name,
            'permissions' => $validated['permissions'] ?? []
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Perfil criado com sucesso.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
            'description' => 'nullable|string|max:255',
            'permissions' => 'array'
        ]);

        $role->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        } else {
            $role->syncPermissions([]);
        }

        AuditService::log('UPDATE_ROLE', [
            'role_id' => $role->id,
            'name' => $role->name,
            'permissions' => $validated['permissions'] ?? []
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Perfil atualizado com sucesso.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        AuditService::log('DELETE_ROLE', [
            'role_id' => $role->id,
            'name' => $role->name
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Perfil excluído com sucesso.');
    }

    public function order(Request $request)
    {
        $order = $request->input('order', []);
        
        foreach ($order as $index => $id) {
            Role::where('id', $id)->update(['order' => $index + 1]);
        }

        AuditService::log('UPDATE_ROLE_ORDER', [
            'new_order' => $order
        ]);

        return response()->json(['success' => true, 'message' => 'Ordem salva com sucesso']);
    }
}
