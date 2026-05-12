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
            // Outros módulos (ex: Financeiro, RH, etc) serão adicionados dinamicamente no futuro.
        ];

        foreach ($modules as $module => $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $module . '.' . $permission, 'guard_name' => 'web']);
            }
        }
    }
}
