<?php

use App\Http\Controllers\CellController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WeeklyReportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\Finance\TransactionController;
use App\Http\Controllers\Finance\IncomeController;
use App\Http\Controllers\Finance\ExpenseController;
use App\Http\Controllers\Finance\ApprovalController;
use App\Http\Controllers\Finance\FinancialAccountController;
use App\Http\Controllers\Finance\FinancialBatchController;
use App\Http\Controllers\Finance\ChartOfAccountController;
use App\Http\Controllers\Finance\CostCenterController;
use App\Http\Controllers\Finance\ClosingController;
use App\Http\Controllers\Finance\ReconciliationController;
use App\Http\Controllers\Accounting\PDFReportController;
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
    Route::get('members/search', [MemberController::class, 'search'])->name('admin.members.search');
    Route::resource('members', MemberController::class);

    // ----------------------------------------------------------
    // MODULO FINANCEIRO (Admin + Tesoureiro)
    // ----------------------------------------------------------
    Route::middleware('role:Admin,Treasurer')->prefix('finance')->name('finance.')->group(function () {
        Route::get('reports/dre', [ReportController::class, 'dre'])->name('reports.dre');
    });

    // ----------------------------------------------------------
    // MÓDULO FINANCEIRO - ERP V8 (Administrador & Tesouraria)
    // ----------------------------------------------------------
    Route::middleware(['role:Admin,Treasurer', 'accounting_closure'])->prefix('admin/finance')->name('admin.finance.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Finance\FinanceDashboardController::class, 'index'])->name('dashboard');
        Route::resource('transactions', \App\Http\Controllers\Finance\TransactionController::class);
        Route::resource('income', \App\Http\Controllers\Finance\IncomeController::class);
        Route::get('income/{transaction}/receipt', [\App\Http\Controllers\Finance\IncomeController::class, 'receipt'])->name('income.receipt');
        Route::get('expenses/risk-zone', [\App\Http\Controllers\Finance\ExpenseController::class, 'riskZone'])->name('expenses.risk-zone');
        Route::resource('expenses', \App\Http\Controllers\Finance\ExpenseController::class);
        Route::get('settings', [\App\Http\Controllers\Finance\FinanceSettingController::class, 'index'])->name('settings.index');
        Route::resource('batches', \App\Http\Controllers\Finance\FinancialBatchController::class);
        
        Route::resource('batches', \App\Http\Controllers\Finance\FinancialBatchController::class);
        

        Route::resource('chart-of-accounts', \App\Http\Controllers\Finance\ChartOfAccountController::class);
        Route::resource('cost-centers', \App\Http\Controllers\Finance\CostCenterController::class);
        Route::resource('accounts', \App\Http\Controllers\Finance\FinancialAccountController::class);
        Route::resource('journal', \App\Http\Controllers\Finance\JournalEntryController::class);
        Route::resource('fixed-assets', \App\Http\Controllers\Finance\FixedAssetController::class);
        Route::resource('closures', \App\Http\Controllers\Finance\ClosureController::class);
        Route::resource('suppliers', \App\Http\Controllers\Finance\SupplierController::class);
        Route::resource('banks', \App\Http\Controllers\Finance\BankController::class);
        Route::resource('units', \App\Http\Controllers\Finance\UnitController::class);

        Route::resource('closures', \App\Http\Controllers\Finance\ClosureController::class);
        Route::resource('suppliers', \App\Http\Controllers\Finance\SupplierController::class);
        Route::resource('banks', \App\Http\Controllers\Finance\BankController::class);
        Route::resource('units', \App\Http\Controllers\Finance\UnitController::class);
    });

    // ----------------------------------------------------------
    // MÓDULO CONTÁBIL - INTELLIGENCE (Admin & Contador)
    // ----------------------------------------------------------
    Route::middleware(['role:Admin,Treasurer'])->prefix('admin/accounting')->name('admin.accounting.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Accounting\AccountingDashboardController::class, 'index'])->name('dashboard');
        Route::get('/journal', function() {
            $entries = \App\Models\Finance\JournalEntry::with(['items.chartOfAccount'])->orderBy('date', 'desc')->paginate(30);
            return view('admin.accounting.journal.index', compact('entries'));
        })->name('journal.index');
        Route::get('/reports/trial-balance', [\App\Http\Controllers\Accounting\TrialBalanceController::class, 'index'])->name('reports.trial-balance');
        Route::get('/reports/income-statement', [\App\Http\Controllers\Accounting\IncomeStatementController::class, 'index'])->name('reports.income-statement');
        Route::get('/reports/balance-sheet', [\App\Http\Controllers\Accounting\BalanceSheetController::class, 'index'])->name('reports.balance-sheet');

        // Fila de Aprovação
        Route::get('/approvals', [\App\Http\Controllers\Finance\ApprovalController::class, 'index'])->name('approvals.index');
        Route::post('/approvals/{transaction}/approve', [\App\Http\Controllers\Finance\ApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{transaction}/reject', [\App\Http\Controllers\Finance\ApprovalController::class, 'reject'])->name('approvals.reject');

        // Conciliação Bancária
        Route::get('/reconciliation', [ReconciliationController::class, 'index'])->name('reconciliation.index');
        Route::post('/reconciliation/process', [ReconciliationController::class, 'process'])->name('reconciliation.process');
        Route::post('/reconciliation/confirm', [ReconciliationController::class, 'reconcile'])->name('reconciliation.confirm');

        // Manual Entries
        Route::post('/manual-entry', [\App\Http\Controllers\Accounting\ManualEntryController::class, 'store'])->name('manual-entry.store');

        // Relatórios PDF
        Route::get('reports/pdf/trial-balance', [PDFReportController::class, 'trialBalance'])->name('reports.pdf.trial-balance');
        Route::get('reports/pdf/income-statement', [PDFReportController::class, 'incomeStatement'])->name('reports.pdf.income-statement');
        Route::get('reports/pdf/balance-sheet', [PDFReportController::class, 'balanceSheet'])->name('reports.pdf.balance-sheet');
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
        Route::post('menus/categories/reorder', [\App\Http\Controllers\Admin\MenuController::class, 'reorderCategories'])->name('admin.menus.categories.reorder');
        Route::put('menus/categories/{category}', [\App\Http\Controllers\Admin\MenuController::class, 'updateCategory'])->name('admin.menus.categories.update');


        // Menus
        Route::get('menus', [\App\Http\Controllers\Admin\MenuController::class, 'index'])->name('admin.menus.index');
        Route::post('menus/store', [\App\Http\Controllers\Admin\MenuController::class, 'store'])->name('admin.menus.store');
        Route::put('menus/{menu}', [\App\Http\Controllers\Admin\MenuController::class, 'update'])->name('admin.menus.update');
        Route::patch('menus/{menu}/toggle', [\App\Http\Controllers\Admin\MenuController::class, 'toggleStatus'])->name('admin.menus.toggle');
        Route::post('menus/reorder', [\App\Http\Controllers\Admin\MenuController::class, 'reorder'])->name('admin.menus.reorder');
        Route::delete('menus/{menu}', [\App\Http\Controllers\Admin\MenuController::class, 'destroy'])->name('admin.menus.destroy');

        // Usuários, Perfis e Permissões
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->names('admin.users');
        Route::post('roles/order', [\App\Http\Controllers\Admin\RoleController::class, 'order'])->name('admin.roles.order');
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->names('admin.roles');
        Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class)->names('admin.permissions');

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
