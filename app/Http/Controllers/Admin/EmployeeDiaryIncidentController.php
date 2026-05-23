<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDiaryIncident;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeDiaryIncidentController extends Controller
{
    public function index(Request $request)
    {
        $query = EmployeeDiaryIncident::with(['employee', 'reporter']);

        // Filtro por Servidor específico (se vier via query string ou do dashboard)
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filtro de Busca de Texto
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhereHas('employee', function($empQ) use ($search) {
                      $empQ->where('name', 'like', "%{$search}%")
                           ->orWhere('registration_number', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro por Tipo de Incidente
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtro por Categoria
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $incidents = $query->orderBy('incident_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Estatísticas para o Bento Grid (Dinâmicas)
        $totalIncidents = EmployeeDiaryIncident::count();
        $totalPositive = EmployeeDiaryIncident::where('type', 'positive')->count();
        $totalNegative = EmployeeDiaryIncident::where('type', 'negative')->count();

        // Média de incidentes por servidor (excluindo admins)
        $totalServidores = User::where('role', '!=', 'Admin')->count();
        $averageImpact = $totalServidores > 0 ? number_format($totalIncidents / $totalServidores, 1) : '0.0';

        // Sentiment Score: percentual de incidentes positivos
        $sentimentScore = $totalIncidents > 0 ? round(($totalPositive / $totalIncidents) * 100) : 100;

        // Lista de Servidores para o formulário de cadastro rápido
        $employees = User::where('role', '!=', 'Admin')
            ->orderBy('name')
            ->get();

        // Lista única de categorias cadastradas no banco para o filtro
        $categories = EmployeeDiaryIncident::pluck('category')
            ->unique()
            ->filter()
            ->values();

        return view('admin.employee-diary-incidents.index', compact(
            'incidents',
            'totalIncidents',
            'averageImpact',
            'sentimentScore',
            'totalPositive',
            'totalNegative',
            'employees',
            'categories'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'category' => 'required|string|max:50',
            'description' => 'required|string',
            'type' => 'required|in:positive,negative',
            'incident_date' => 'required|date',
            'evidence_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx,zip|max:5120',
        ]);

        $evidencePath = null;
        if ($request->hasFile('evidence_file')) {
            $file = $request->file('evidence_file');
            if ($file->isValid()) {
                $filename = $file->hashName();
                $evidencePath = 'evidence/' . $filename;
                Storage::disk('public')->put($evidencePath, file_get_contents($file->getPathname()));
            }
        }

        $incident = EmployeeDiaryIncident::create([
            'employee_id' => $validated['employee_id'],
            'reporter_id' => auth()->id(),
            'category' => $validated['category'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'incident_date' => $validated['incident_date'],
            'evidence_file' => $evidencePath,
        ]);

        AuditService::log('CREATE_DIARY_INCIDENT', [
            'incident_id' => $incident->id,
            'employee_id' => $incident->employee_id,
            'category' => $incident->category,
            'type' => $incident->type,
        ]);

        return redirect()->route('admin.employee-diary-incidents.index')->with('success', 'Incidente registrado com sucesso no diário de bordo.');
    }

    public function update(Request $request, EmployeeDiaryIncident $employeeDiaryIncident)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'category' => 'required|string|max:50',
            'description' => 'required|string',
            'type' => 'required|in:positive,negative',
            'incident_date' => 'required|date',
            'evidence_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx,zip|max:5120',
        ]);

        $evidencePath = $employeeDiaryIncident->evidence_file;
        if ($request->hasFile('evidence_file')) {
            $file = $request->file('evidence_file');
            if ($file->isValid()) {
                // Remover anterior se existir
                if ($evidencePath) {
                    Storage::disk('public')->delete($evidencePath);
                }
                $filename = $file->hashName();
                $evidencePath = 'evidence/' . $filename;
                Storage::disk('public')->put($evidencePath, file_get_contents($file->getPathname()));
            }
        }

        $employeeDiaryIncident->update([
            'employee_id' => $validated['employee_id'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'incident_date' => $validated['incident_date'],
            'evidence_file' => $evidencePath,
        ]);

        AuditService::log('UPDATE_DIARY_INCIDENT', [
            'incident_id' => $employeeDiaryIncident->id,
            'updated_fields' => array_keys($validated),
        ]);

        return redirect()->route('admin.employee-diary-incidents.index')->with('success', 'Incidente do diário atualizado com sucesso.');
    }

    public function destroy(EmployeeDiaryIncident $employeeDiaryIncident)
    {
        // Remover arquivo do storage
        if ($employeeDiaryIncident->evidence_file) {
            Storage::disk('public')->delete($employeeDiaryIncident->evidence_file);
        }

        $id = $employeeDiaryIncident->id;
        $employee_id = $employeeDiaryIncident->employee_id;
        $employeeDiaryIncident->delete();

        AuditService::log('DELETE_DIARY_INCIDENT', [
            'incident_id' => $id,
            'employee_id' => $employee_id,
        ]);

        return redirect()->route('admin.employee-diary-incidents.index')->with('success', 'Incidente do diário excluído com sucesso.');
    }
}
