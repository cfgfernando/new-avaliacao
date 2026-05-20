<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OperacionalController extends Controller
{
    /**
     * Exibe o painel administrativo principal com indicadores core e logs de auditoria.
     */
    public function dashboard(Request $request): View
    {
        // KPIs Principais
        $totalUsers       = User::count();
        $totalRoles       = Role::count();
        $totalPermissions = Permission::count();
        $totalLogs        = AuditLog::count();

        // Logs de atividade recentes (últimos 10)
        $recentLogs = AuditLog::with('user')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        // Histórico de Evolução (últimos 6 meses)
        $userGrowth = collect();
        $logGrowth  = collect();
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $endOfMonth = $date->copy()->endOfMonth();
            
            // Total acumulado de usuários criados até o fim daquele mês
            $uCount = User::where('created_at', '<=', $endOfMonth)->count();
            
            // Total de logs gerados especificamente naquele mês
            $lCount = AuditLog::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
                
            $monthName = $date->translatedFormat('M');
            // Limpa ponto final que o PHP/Carbon às vezes gera no PT-BR (ex: "mai.")
            $monthName = str_replace('.', '', $monthName);
            $monthName = mb_convert_case($monthName, MB_CASE_TITLE, "UTF-8");
            
            $userGrowth->put($monthName, $uCount);
            $logGrowth->put($monthName, $lCount);
        }

        return view('dashboard', compact(
            'totalUsers',
            'totalRoles',
            'totalPermissions',
            'totalLogs',
            'recentLogs',
            'userGrowth',
            'logGrowth'
        ));
    }
}
