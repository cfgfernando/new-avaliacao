<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Categorias
        $catPrincipal = MenuCategory::create(['name' => 'Navegação Principal', 'order' => 1]);
        $catConfig = MenuCategory::create(['name' => 'Configurações', 'order' => 2]);

        // Itens - Principal
        Menu::create([
            'category_id' => $catPrincipal->id,
            'title' => 'Dashboard',
            'url' => '/dashboard',
            'icon' => 'fas fa-th-large',
            'order' => 1
        ]);

        Menu::create([
            'category_id' => $catPrincipal->id,
            'title' => 'Membros',
            'url' => '/members',
            'icon' => 'fas fa-users',
            'order' => 2
        ]);

        Menu::create([
            'category_id' => $catPrincipal->id,
            'title' => 'Relatórios',
            'url' => '/reports',
            'icon' => 'fas fa-file-invoice-dollar',
            'order' => 3
        ]);

        // Itens - Config
        Menu::create([
            'category_id' => $catConfig->id,
            'title' => 'Sistema',
            'url' => '#',
            'icon' => 'fas fa-cog',
            'order' => 1
        ]);
    }
}
