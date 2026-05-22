<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\Request;
use App\Services\AuditService;

class OfficeController extends Controller
{
    public function index()
    {
        $offices = Office::withCount('users')->paginate(10);
        return view('admin.offices.index', compact('offices'));
    }

    public function create()
    {
        return view('admin.offices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:offices,name',
            'sigla' => 'nullable|string|max:20',
            'is_active' => 'nullable',
        ]);

        $office = Office::create([
            'name' => $validated['name'],
            'sigla' => $validated['sigla'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        AuditService::log('CREATE_OFFICE', [
            'office_id' => $office->id,
            'name' => $office->name,
            'sigla' => $office->sigla,
        ]);

        return redirect()->route('admin.offices.index')->with('success', 'Secretaria/Lotação criada com sucesso.');
    }

    public function edit(Office $office)
    {
        return view('admin.offices.edit', compact('office'));
    }

    public function update(Request $request, Office $office)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:offices,name,' . $office->id,
            'sigla' => 'nullable|string|max:20',
            'is_active' => 'nullable',
        ]);

        $office->update([
            'name' => $validated['name'],
            'sigla' => $validated['sigla'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        AuditService::log('UPDATE_OFFICE', [
            'office_id' => $office->id,
            'updated_fields' => $validated,
        ]);

        return redirect()->route('admin.offices.index')->with('success', 'Secretaria/Lotação atualizada com sucesso.');
    }

    public function destroy(Office $office)
    {
        if ($office->users()->count() > 0) {
            return redirect()->route('admin.offices.index')->with('error', 'Não é possível excluir esta secretaria/lotação pois existem servidores vinculados a ela.');
        }

        $office->delete();

        AuditService::log('DELETE_OFFICE', [
            'office_id' => $office->id,
            'name' => $office->name,
        ]);

        return redirect()->route('admin.offices.index')->with('success', 'Secretaria/Lotação excluída com sucesso.');
    }
}
