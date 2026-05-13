<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'Sistema' => [
                'Super Usuário (Acesso Total)',
                'Ver Usuários',
                'Criar Usuários',
                'Editar Usuários',
                'Ver Perfis',
                'Criar Perfis',
                'Editar Perfis',
                'Ver Trilha de Auditoria',
                'Ver Configurações'
            ],
            'Financeiro' => [
                'Acesso ao Módulo',
                'Gerenciar Plano de Contas',
                'Gerenciar Centros de Custo',
                'Gerenciar Contas',
                'Gerenciar Malotes',
                'Gerenciar Transações',
                'Ver Livro Diário',
                'Gerenciar Imobilizado',
            ]
        ];

        foreach ($modules as $module => $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $module . '.' . $permission, 'guard_name' => 'web']);
            }
        }
    }
}
