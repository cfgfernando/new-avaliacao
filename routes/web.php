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
// ENDPOINTS DE INTEGRAÇÃO (RH & Ponto)
// ============================================================
Route::prefix('api/v1/integration')->middleware('integration_token')->group(function () {
    Route::post('rh/servidores', [\App\Http\Controllers\Api\IntegrationController::class, 'importServidores']);
    Route::post('ponto/registros', [\App\Http\Controllers\Api\IntegrationController::class, 'importPonto']);
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
    // MÓDULO DE AVALIAÇÃO DE DESEMPENHO (APD)
    // ----------------------------------------------------------
    Route::get('evaluations', [\App\Http\Controllers\EvaluationController::class, 'index'])->name('evaluations.index');
    Route::get('evaluation/{evaluated}', [\App\Http\Controllers\EvaluationController::class, 'create'])->name('evaluation.create');
    Route::post('evaluation', [\App\Http\Controllers\EvaluationController::class, 'store'])->name('evaluation.store');

    // Setup de Nova Avaliação
    Route::get('evaluations/setup', [\App\Http\Controllers\EvaluationSetupController::class, 'create'])->name('evaluations.setup.create');
    Route::post('evaluations/setup', [\App\Http\Controllers\EvaluationSetupController::class, 'store'])->name('evaluations.setup.store');
    Route::post('evaluations/setup/force', [\App\Http\Controllers\EvaluationSetupController::class, 'forceCreate'])->name('evaluations.setup.force');
    // Verificação AJAX de duplicidade / PAD antes da criação
    Route::get('evaluations/check-duplicate', [\App\Http\Controllers\EvaluationSetupController::class, 'checkDuplicate'])->name('evaluations.check_duplicate');
    
    // API Dropdown Dinâmico de Lotação
    Route::get('api/servidores', [\App\Http\Controllers\EvaluationSetupController::class, 'getServidoresByLotacao'])->name('api.lotacao.servidores');
    Route::get('api/servidores/{user}/detalhes', [\App\Http\Controllers\EvaluationSetupController::class, 'getServidorDetalhes'])->name('api.servidores.detalhes');

    // Preenchimento e Submissão pelo ID da Avaliação (Rascunho)
    Route::get('evaluations/{evaluation}/fill', [\App\Http\Controllers\EvaluationController::class, 'fill'])->name('evaluations.fill');
    Route::post('evaluations/{evaluation}/submit', [\App\Http\Controllers\EvaluationController::class, 'submit'])->name('evaluations.submit');
    Route::post('evaluation/incident', [\App\Http\Controllers\EvaluationController::class, 'storeIncident'])->name('evaluation.incident.store');

    // Diário de Incidentes Críticos (Acessível a Avaliadores e Admins)
    Route::resource('employee-diary-incidents', \App\Http\Controllers\Admin\EmployeeDiaryIncidentController::class)->names('admin.employee-diary-incidents');

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

        // Gestão de Secretarias/Lotações
        Route::resource('offices', \App\Http\Controllers\Admin\OfficeController::class)->names('admin.offices');

        // Gestão de Servidores Avaliados
        Route::resource('evaluated-users', \App\Http\Controllers\Admin\EvaluatedUserController::class)->names('admin.evaluated-users');

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

        // Gestão de Ciclos de Avaliação (APD)
        Route::post('evaluation-cycles/{evaluation_cycle}/update-weights', [\App\Http\Controllers\Admin\EvaluationCycleController::class, 'updateWeights'])
            ->name('admin.evaluation-cycles.update-weights');
        Route::resource('evaluation-cycles', \App\Http\Controllers\Admin\EvaluationCycleController::class)->names('admin.evaluation-cycles');

        // Gestão de Perguntas de Avaliação (APD)
        Route::post('bars/generate-anchors', [\App\Http\Controllers\Admin\EvaluationQuestionController::class, 'generateAnchors'])
            ->name('admin.bars.generate-anchors');
        Route::resource('evaluation-questions', \App\Http\Controllers\Admin\EvaluationQuestionController::class)
            ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
            ->names('admin.evaluation-questions');
        Route::post('evaluation-questions/{evaluation_question}/toggle', [\App\Http\Controllers\Admin\EvaluationQuestionController::class, 'toggle'])
            ->name('admin.evaluation-questions.toggle');

        // Resultados de Avaliações (APD)
        Route::get('evaluation-results', [\App\Http\Controllers\Admin\EvaluationResultsController::class, 'index'])->name('admin.evaluation-results.index');
        Route::get('evaluation-results/{evaluation}', [\App\Http\Controllers\Admin\EvaluationResultsController::class, 'show'])->name('admin.evaluation-results.show');
        Route::post('evaluation-results/{evaluation}/goals', [\App\Http\Controllers\Admin\EvaluationResultsController::class, 'updateGoals'])->name('admin.evaluation-results.update-goals');

        // Avaliações Arquivadas (Rascunhos antigos)
        Route::get('evaluations/archived', [\App\Http\Controllers\Admin\ArchivedEvaluationController::class, 'index'])->name('admin.evaluations.archived');
        Route::post('evaluations/{evaluation}/restore', [\App\Http\Controllers\Admin\ArchivedEvaluationController::class, 'restore'])->name('admin.evaluations.restore');
        // Listagem avançada de Avaliações concluídas
        Route::get('evaluations/all', [\App\Http\Controllers\Admin\EvaluationResultsController::class, 'all'])->name('admin.evaluations.all');
        // Export da listagem avançada
        Route::get('evaluations/all/export', [\App\Http\Controllers\Admin\EvaluationResultsController::class, 'export'])->name('admin.evaluations.all.export');

        // Auditoria Forense Geral (Spatie Audits se configurado)
        Route::get('audits', [AuditController::class, 'index'])->name('admin.audits.index');
        Route::get('audits/{audit}', [AuditController::class, 'show'])->name('admin.audits.show');
    });
});

// ============================================================
// ROTAS DE AUTENTICAÇÃO (Breeze)
// ============================================================
require __DIR__.'/auth.php';
