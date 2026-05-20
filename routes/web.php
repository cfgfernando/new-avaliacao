<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\OperacionalController;
use Illuminate\Support\Facades\Route;

// ============================================================
// ROTAS PÚBLICAS
// ============================================================
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ============================================================
// ÁREA AUTENTICADA (Breeze + Admin Core)
// ============================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // ----------------------------------------------------------
    // DASHBOARD & PERFIL
    // ----------------------------------------------------------
    Route::get('dashboard', [OperacionalController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ----------------------------------------------------------
    // MÓDULO DE ADMINISTRAÇÃO CORE (Apenas Administradores)
    // ----------------------------------------------------------
    Route::middleware('role:Admin')->prefix('admin')->group(function () {
        
        // Categorias de Menu (Gerenciar Menus)
        Route::post('menus/categories', [\App\Http\Controllers\Admin\MenuController::class, 'storeCategory'])->name('admin.menus.categories.store');
        Route::post('menus/categories/reorder', [\App\Http\Controllers\Admin\MenuController::class, 'reorderCategories'])->name('admin.menus.categories.reorder');
        Route::post('menus/categories/reorder-single', [\App\Http\Controllers\Admin\MenuController::class, 'reorderSingleCategory'])->name('admin.menus.categories.reorder-single');
        Route::put('menus/categories/{category}', [\App\Http\Controllers\Admin\MenuController::class, 'updateCategory'])->name('admin.menus.categories.update');

        // Itens de Menu (Gerenciar Menus)
        Route::get('menus', [\App\Http\Controllers\Admin\MenuController::class, 'index'])->name('admin.menus.index');
        Route::post('menus/store', [\App\Http\Controllers\Admin\MenuController::class, 'store'])->name('admin.menus.store');
        Route::put('menus/{menu}', [\App\Http\Controllers\Admin\MenuController::class, 'update'])->name('admin.menus.update');
        Route::patch('menus/{menu}/toggle', [\App\Http\Controllers\Admin\MenuController::class, 'toggleStatus'])->name('admin.menus.toggle');
        Route::post('menus/reorder', [\App\Http\Controllers\Admin\MenuController::class, 'reorder'])->name('admin.menus.reorder');
        Route::delete('menus/{menu}', [\App\Http\Controllers\Admin\MenuController::class, 'destroy'])->name('admin.menus.destroy');

        // Gestão de Usuários
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->names('admin.users');

        // Gestão de Perfis (Roles)
        Route::post('roles/order', [\App\Http\Controllers\Admin\RoleController::class, 'order'])->name('admin.roles.order');
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->names('admin.roles');

        // Gestão de Permissões (Permissions)
        Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class)->names('admin.permissions');

        // Trilha de Auditoria Customizada (Logs de Atividade)
        Route::get('logs', function() {
            $logs = \App\Models\AuditLog::with('user')->orderBy('created_at', 'desc')->paginate(30);
            return view('admin.audits.custom_index', compact('logs'));
        })->name('admin.logs.index');

        // Auditoria Forense Geral (Spatie Audits se configurado)
        Route::get('audits', [AuditController::class, 'index'])->name('admin.audits.index');
        Route::get('audits/{audit}', [AuditController::class, 'show'])->name('admin.audits.show');
    });
});

// ============================================================
// ROTAS DE AUTENTICAÇÃO (Breeze)
// ============================================================
require __DIR__.'/auth.php';
