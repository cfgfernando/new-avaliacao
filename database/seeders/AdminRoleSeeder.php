<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class AdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
        $role->description = 'Acesso total ao sistema';
        $role->is_active = true;
        $role->save();

        $role->syncPermissions(Permission::all());

        // Assign to an existing Admin user so the user can test
        $user = User::where('role', 'Admin')->first();
        if ($user) {
            $user->assignRole($role);
        }
    }
}
