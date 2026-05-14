<?php

namespace App\Providers;

use App\Models\Cell;
use App\Models\Member;
use App\Policies\CellPolicy;
use App\Policies\MemberPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registrar serviços da aplicação.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap dos serviços da aplicação.
     */
    public function boot(): void
    {
        // =====================================================================
        // Registro de Policies RBAC
        // O Laravel 11 utiliza o AppServiceProvider para registrar policies
        // (o AuthServiceProvider foi mesclado nele).
        // =====================================================================
        Gate::policy(Cell::class,         CellPolicy::class);
        Gate::policy(Member::class,       MemberPolicy::class);
        Gate::policy(WeeklyReport::class, WeeklyReportPolicy::class);

        // Futuras policies (descomentar conforme as fases avançam):
        // Gate::policy(\App\Models\Expense::class,      \App\Policies\ExpensePolicy::class);
        // Gate::policy(\App\Models\Account::class,      \App\Policies\AccountPolicy::class);
        // Gate::policy(\App\Models\Visitor::class,      \App\Policies\VisitorPolicy::class);

        Gate::define('view-reports', function ($user) {
            return in_array($user->role, ['Admin', 'Treasurer']);
        });

        Gate::define('view-audit-logs', function ($user) {
            return $user->role === 'Admin';
        });

        // ---------------------------------------------------------------------
        // Menus Dinâmicos (Sidebar)
        // ---------------------------------------------------------------------
        view()->composer('layouts.app', function ($view) {
            if (\Illuminate\Support\Facades\Schema::hasTable('menu_categories')) {
                $menuCategories = \App\Models\MenuCategory::with(['items' => function($q) {
                    $q->where('is_active', true)->orderBy('order');
                }])->where('is_active', true)->orderBy('order')->get();
                $view->with('menuCategories', $menuCategories);
            }
        });

        // ---------------------------------------------------------------------
        // Observers de Auditoria e Saldo
        // ---------------------------------------------------------------------
        \App\Models\Finance\Transaction::observe(\App\Observers\TransactionObserver::class);

        Paginator::useTailwind();
    }
}
