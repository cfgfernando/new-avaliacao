<?php

use App\Http\Controllers\CellController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WeeklyReportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditController;
use Illuminate\Support\Facades\Route;

// ============================================================
// ROTAS PÚBLICAS
// ============================================================
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ============================================================
// ÁREA AUTENTICADA (Breeze + ERP)
// ============================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // ----------------------------------------------------------
    // DASHBOARD & PERFIL
    // ----------------------------------------------------------
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ----------------------------------------------------------
    // GESTÃO DE CÉLULAS
    // ----------------------------------------------------------
    Route::resource('cells', CellController::class);

    // ----------------------------------------------------------
    // GESTÃO DE MEMBROS
    // ----------------------------------------------------------
    Route::resource('members', MemberController::class);

    // ----------------------------------------------------------
    // MODULO FINANCEIRO (Admin + Tesoureiro)
    // ----------------------------------------------------------
    Route::middleware('role:Admin,Treasurer')->prefix('finance')->name('finance.')->group(function () {
        Route::get('reports/dre', [ReportController::class, 'dre'])->name('reports.dre');
    });

    // ----------------------------------------------------------
    // RELATÓRIOS SEMANAIS (Malotes)
    // ----------------------------------------------------------
    Route::resource('reports', WeeklyReportController::class);
    Route::post('reports/{report}/submit', [WeeklyReportController::class, 'submit'])->name('reports.submit');
    Route::post('reports/{report}/conciliate', [WeeklyReportController::class, 'conciliate'])->name('reports.conciliate');

    Route::middleware('role:Admin')->prefix('admin')->group(function () {
        // Categorias de Menu
        Route::post('menus/categories', [\App\Http\Controllers\Admin\MenuController::class, 'storeCategory'])->name('admin.menus.categories.store');
        Route::put('menus/categories/{category}', [\App\Http\Controllers\Admin\MenuController::class, 'updateCategory'])->name('admin.menus.categories.update');

        // Menus
        Route::get('menus', [\App\Http\Controllers\Admin\MenuController::class, 'index'])->name('admin.menus.index');
        Route::post('menus/store', [\App\Http\Controllers\Admin\MenuController::class, 'store'])->name('admin.menus.store');
        Route::put('menus/{menu}', [\App\Http\Controllers\Admin\MenuController::class, 'update'])->name('admin.menus.update');
        Route::patch('menus/{menu}/toggle', [\App\Http\Controllers\Admin\MenuController::class, 'toggleStatus'])->name('admin.menus.toggle');
        Route::post('menus/reorder', [\App\Http\Controllers\Admin\MenuController::class, 'reorder'])->name('admin.menus.reorder');
        Route::delete('menus/{menu}', [\App\Http\Controllers\Admin\MenuController::class, 'destroy'])->name('admin.menus.destroy');

        // Auditoria Customizada
        Route::get('logs', function() {
            $logs = \App\Models\AuditLog::with('user')->orderBy('created_at', 'desc')->paginate(30);
            return view('admin.audits.custom_index', compact('logs'));
        })->name('admin.logs.index');

        // Auditoria Forense (Existente)
        Route::get('audits', [AuditController::class, 'index'])->name('admin.audits.index');
        Route::get('audits/{audit}', [AuditController::class, 'show'])->name('admin.audits.show');
    });
});

// ============================================================
// ROTAS DE AUTENTICAÇÃO (Breeze)
// ============================================================
require __DIR__.'/auth.php';
