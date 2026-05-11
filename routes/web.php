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

    // ----------------------------------------------------------
    // AUDITORIA FORENSE (Admin)
    // ----------------------------------------------------------
    Route::middleware('role:Admin')->group(function () {
        Route::get('audits', [AuditController::class, 'index'])->name('admin.audits.index');
        Route::get('audits/{audit}', [AuditController::class, 'show'])->name('admin.audits.show');
    });
});

// ============================================================
// ROTAS DE AUTENTICAÇÃO (Breeze)
// ============================================================
require __DIR__.'/auth.php';
