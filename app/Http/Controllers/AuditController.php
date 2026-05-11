<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use Illuminate\View\View;

class AuditController extends Controller
{
    /**
     * Lista todos os logs de auditoria do sistema.
     * Acesso restrito a Admin.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-audit-logs', User::class);

        $audits = Audit::with('user')
            ->orderBy('created_at', 'desc')
            ->when($request->auditable_type, function($q) use ($request) {
                return $q->where('auditable_type', 'LIKE', '%' . $request->auditable_type . '%');
            })
            ->when($request->user_id, function($q) use ($request) {
                return $q->where('user_id', $request->user_id);
            })
            ->paginate(20);

        return view('admin.audits.index', compact('audits'));
    }

    /**
     * Exibe detalhes de uma auditoria específica (Old values vs New values).
     */
    public function show(Audit $audit): View
    {
        $this->authorize('view-audit-logs', User::class);
        
        return view('admin.audits.show', compact('audit'));
    }
}
