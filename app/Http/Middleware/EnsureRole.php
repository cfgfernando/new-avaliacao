<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de verificação de papel (role) RBAC.
 *
 * Uso nas rotas:
 *   ->middleware('role:Admin')
 *   ->middleware('role:Admin,Treasurer')
 *   ->middleware('role:Admin,Supervisor,Leader')
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Acesso não autorizado para este perfil.',
                ], 403);
            }

            abort(403, 'Acesso não autorizado. Seu perfil não tem permissão para esta ação.');
        }

        return $next($request);
    }
}
