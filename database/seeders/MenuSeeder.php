<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Criar Categorias de Menu
        $catPrincipal     = MenuCategory::create(['name' => 'Navegação Principal', 'order' => 1]);
        $catAdministracao = MenuCategory::create(['name' => 'Administração', 'order' => 2]);
        $catConfig        = MenuCategory::create(['name' => 'Configurações', 'order' => 3]);

        // 2. Itens - Navegação Principal
        Menu::create([
            'category_id' => $catPrincipal->id,
            'title'       => 'Dashboard',
            'url'         => '/dashboard',
            'icon'        => 'fas fa-th-large',
            'order'       => 1
        ]);

        // 3. Itens - Administração
        Menu::create([
            'category_id' => $catAdministracao->id,
            'title'       => 'Gerenciar Menus',
            'url'         => '/admin/menus',
            'icon'        => 'fas fa-bars-staggered',
            'order'       => 1
        ]);
 
        Menu::create([
            'category_id' => $catAdministracao->id,
            'title'       => 'Usuários',
            'url'         => '/admin/users',
            'icon'        => 'fas fa-users',
            'order'       => 2
        ]);
 
        Menu::create([
            'category_id' => $catAdministracao->id,
            'title'       => 'Perfis (Roles)',
            'url'         => '/admin/roles',
            'icon'        => 'fas fa-shield-alt',
            'order'       => 3
        ]);
 
        Menu::create([
            'category_id' => $catAdministracao->id,
            'title'       => 'Permissões',
            'url'         => '/admin/permissions',
            'icon'        => 'fas fa-key',
            'order'       => 4
        ]);
 
        Menu::create([
            'category_id' => $catAdministracao->id,
            'title'       => 'Logs de Sistema',
            'url'         => '/admin/logs',
            'icon'        => 'fas fa-fingerprint',
            'order'       => 5
        ]);

        // 4. Itens - Configurações
        Menu::create([
            'category_id' => $catConfig->id,
            'title'       => 'Sistema',
            'url'         => '#',
            'icon'        => 'fas fa-cog',
            'order'       => 1
        ]);
    }
}
