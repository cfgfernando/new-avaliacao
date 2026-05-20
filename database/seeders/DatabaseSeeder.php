<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Iniciando Seeding do Boilerplate Core...');

        // 1. Popular as Permissões
        $this->call(PermissionSeeder::class);

        // 2. Criar ou Obter o Usuário Administrador Master Padrão
        $admin = User::firstOrCreate(
            ['email' => 'admin@sistema.com'],
            [
                'name' => 'Administrador Master',
                'password' => Hash::make('admin123'),
                'role' => 'Admin',
                'active' => true,
            ]
        );

        // 3. Rodar Perfil Administrador e Vincular Permissões
        $this->call(AdminRoleSeeder::class);

        // 4. Popular os Menus e Categorias
        $this->call(MenuSeeder::class);

        $this->command->info('Seeding do Boilerplate Core concluído com sucesso!');
    }
}
