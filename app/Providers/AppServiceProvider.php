<?php

namespace App\Providers;

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
        // Registro de Gates RBAC Core
        // =====================================================================
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

        Paginator::useTailwind();
    }
}
