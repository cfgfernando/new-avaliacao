<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Services\AuditService;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::paginate(20);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
        ]);

        $permission = Permission::create([
            'name' => $validated['name'],
            'guard_name' => 'web'
        ]);

        AuditService::log('CREATE_PERMISSION', [
            'permission_id' => $permission->id,
            'name' => $permission->name
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permissão criada com sucesso.');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,'.$permission->id,
        ]);

        $permission->update(['name' => $validated['name']]);

        AuditService::log('UPDATE_PERMISSION', [
            'permission_id' => $permission->id,
            'name' => $permission->name
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permissão atualizada com sucesso.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        AuditService::log('DELETE_PERMISSION', [
            'permission_id' => $permission->id,
            'name' => $permission->name
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permissão excluída com sucesso.');
    }
}
