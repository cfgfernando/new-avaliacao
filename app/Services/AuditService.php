<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Registra uma ação no log de auditoria.
     *
     * @param string $action Nome da ação (ex: 'UPDATE_MENU_ORDER')
     * @param mixed $description Detalhes da ação (string ou array)
     * @return void
     */
    public static function log(string $action, $description = null): void
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
