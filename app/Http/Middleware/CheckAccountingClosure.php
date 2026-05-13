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

        // Tenta pegar a data da requisição (comum em lançamentos financeiros)
        $date = $request->input('date') ?? $request->input('payment_date') ?? $request->input('created_at');

        // Se não houver data na requisição, mas for uma edição/exclusão, 
        // em um cenário ideal buscaríamos o registro no BD para ver a data original.
        // Por agora, validaremos a data de entrada se existir.
        
        if ($date && AccountingClosure::isPeriodClosed($date)) {
            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Este período contábil está FECHADO e não permite alterações.'
                ], 403);
            }

            return back()->with('error', 'Operação bloqueada: Este período contábil já foi encerrado.');
        }

        return $next($request);
    }
}
