<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Finance\Closure as AccountingClosure;

class CheckAccountingClosure
{
    public function handle(Request $request, Closure $next): Response
    {
        // Só validar para métodos que alteram dados (POST, PUT, PATCH, DELETE)
        if ($request->isMethod('GET') || $request->isMethod('HEAD')) {
            return $next($request);
        }

        // 1. Identificar a data da operação
        $date = $request->input('transaction_date') 
                ?? $request->input('purchase_date')
                ?? $request->input('date') 
                ?? $request->input('payment_date');

        // 2. Se for uma rota de recurso (edit/update/delete), tentar inferir a data do modelo
        if (!$date && $request->route()) {
            $params = $request->route()->parameters();
            $modelInstance = reset($params);
            
            if ($modelInstance instanceof \Illuminate\Database\Eloquent\Model) {
                $date = $modelInstance->transaction_date ?? $modelInstance->purchase_date ?? $modelInstance->date;
            }
        }

        // 3. Se ainda não houver data, e for uma rota de "movimentação", usar a data atual
        if (!$date && $request->route()) {
            $routeName = $request->route()->getName();
            $movementRoutes = ['transactions.', 'income.', 'expenses.', 'batches.', 'journal.', 'fixed-assets.'];
            
            foreach ($movementRoutes as $movement) {
                if (str_contains($routeName, $movement)) {
                    $date = now();
                    break;
                }
            }
        }

        // 4. Validar se o período está fechado
        if ($date && AccountingClosure::isPeriodClosed($date)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Período Bloqueado',
                    'message' => 'Este período contábil (' . \Carbon\Carbon::parse($date)->format('m/Y') . ') está FECHADO e não permite alterações.'
                ], 403);
            }

            return back()->with('error', 'Operação bloqueada: O período ' . \Carbon\Carbon::parse($date)->format('m/Y') . ' já foi encerrado contabilmente.');
        }

        return $next($request);
    }
}
