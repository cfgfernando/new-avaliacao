<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Finance\FinancialAccount;
use App\Models\Finance\ChartOfAccount;
use App\Models\Finance\CostCenter;
use App\Models\Finance\FixedAsset;
use App\Models\Finance\FinancialBatch;
use App\Models\Finance\Transaction;
use App\Models\User;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FinanceModuleSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('pt_BR');
        $this->command->info('Iniciando Configuração de Menus e Permissões...');

        // 1. Criar Categoria de Menu Financeiro
        $catFinance = DB::table('menu_categories')->updateOrInsert(
            ['name' => 'MÓDULO FINANCEIRO'],
            ['order' => 2, 'is_active' => true]
        );
        $catId = DB::table('menu_categories')->where('name', 'MÓDULO FINANCEIRO')->first()->id;

        // 2. Criar Itens de Menu (Sincronizado com as novas telas)
        $menuItems = [
            ['title' => 'Dashboard Financeiro', 'url' => 'admin/finance', 'icon' => 'fas fa-chart-line', 'order' => 1],
            ['title' => 'Receitas / Ofertas', 'url' => 'admin/finance/income', 'icon' => 'fas fa-arrow-trend-up', 'order' => 2],
            ['title' => 'Despesas / Saídas', 'url' => 'admin/finance/expenses', 'icon' => 'fas fa-arrow-trend-down', 'order' => 3],
            ['title' => 'Contas e Cofres', 'url' => 'admin/finance/accounts', 'icon' => 'fas fa-wallet', 'order' => 4],
            ['title' => 'Malotes / Remessas', 'url' => 'admin/finance/batches', 'icon' => 'fas fa-envelope-open-text', 'order' => 5],
            ['title' => 'Patrimônio', 'url' => 'admin/finance/fixed-assets', 'icon' => 'fas fa-boxes-stacked', 'order' => 6],
            ['title' => 'Livro Diário', 'url' => 'admin/finance/journal', 'icon' => 'fas fa-book-bookmark', 'order' => 7],
            ['title' => 'Plano de Contas', 'url' => 'admin/finance/chart-of-accounts', 'icon' => 'fas fa-sitemap', 'order' => 8],
            ['title' => 'Centros de Custo', 'url' => 'admin/finance/cost-centers', 'icon' => 'fas fa-tags', 'order' => 9],
            ['title' => 'Fechamento Contábil', 'url' => 'admin/finance/closures', 'icon' => 'fas fa-calendar-check', 'order' => 10],
        ];

        // Remover o antigo link de transações
        DB::table('menus')->where('url', 'admin/finance/transactions')->delete();

        foreach ($menuItems as $item) {
            DB::table('menus')->updateOrInsert(
                ['url' => $item['url']],
                array_merge($item, ['category_id' => $catId, 'is_active' => true, 'is_admin_only' => false])
            );
        }

        // 3. Criar Permissões Financeiras
        $permissions = [
            'finance.dashboard',
            'finance.income',
            'finance.expenses',
            'finance.accounts',
            'finance.batches',
            'finance.assets',
            'finance.journal',
            'finance.settings',
            'finance.closures'
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // 4. Atribuir Permissões aos Perfis
        $roleAdmin = Role::findOrCreate('Admin');
        $roleTreasurer = Role::findOrCreate('Treasurer');

        $roleAdmin->givePermissionTo($permissions);
        $roleTreasurer->givePermissionTo($permissions);

        $this->command->info('Menus e Permissões configurados!');
    }
}
