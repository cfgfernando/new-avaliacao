<?php

namespace Tests\Feature;

use App\Models\Office;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OfficeAdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $chefia;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Araucária',
            'email' => 'admin@araucaria.pr.gov.br',
            'password' => Hash::make('senha123'),
            'role' => 'Admin',
            'lotacao' => 'CAPD Central',
            'evaluation_group' => 'geral'
        ]);

        $this->chefia = User::create([
            'name' => 'Supervisor José',
            'email' => 'jose@araucaria.pr.gov.br',
            'password' => Hash::make('senha123'),
            'role' => 'Supervisor',
            'lotacao' => 'Secretaria de Saúde',
            'evaluation_group' => 'saude'
        ]);
    }

    public function test_non_admin_cannot_access_office_management(): void
    {
        $response = $this->actingAs($this->chefia)->get(route('admin.offices.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->chefia)->get(route('admin.offices.create'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_office_management_index_and_create_views(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.offices.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)->get(route('admin.offices.create'));
        $response->assertStatus(200);
    }

    public function test_admin_can_store_office(): void
    {
        $officeData = [
            'name' => 'Secretaria Municipal de Obras Públicas',
            'sigla' => 'SMOP',
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.offices.store'), $officeData);

        $response->assertRedirect(route('admin.offices.index'));
        $response->assertSessionHas('success', 'Secretaria/Lotação criada com sucesso.');

        $this->assertEquals(1, Office::count());
        $office = Office::first();
        
        $this->assertEquals('Secretaria Municipal de Obras Públicas', $office->name);
        $this->assertEquals('SMOP', $office->sigla);
        $this->assertTrue($office->is_active);
    }

    public function test_admin_can_update_office(): void
    {
        $office = Office::create([
            'name' => 'Secretaria Provisória',
            'sigla' => 'SP',
            'is_active' => true,
        ]);

        $updateData = [
            'name' => 'Secretaria Municipal de Meio Ambiente',
            'sigla' => 'SMMA',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.offices.update', $office), $updateData);

        $response->assertRedirect(route('admin.offices.index'));
        $response->assertSessionHas('success', 'Secretaria/Lotação atualizada com sucesso.');

        $office->refresh();
        $this->assertEquals('Secretaria Municipal de Meio Ambiente', $office->name);
        $this->assertEquals('SMMA', $office->sigla);
        $this->assertFalse($office->is_active); // Não foi enviado no form, logo é false
    }

    public function test_admin_cannot_delete_office_with_linked_users(): void
    {
        $office = Office::create([
            'name' => 'Secretaria de Educação',
            'sigla' => 'SMED',
            'is_active' => true,
        ]);

        $user = User::create([
            'name' => 'Professor Carlos',
            'email' => 'carlos@araucaria.pr.gov.br',
            'password' => Hash::make('senha123'),
            'role' => 'Leader',
            'office_id' => $office->id,
            'registration_number' => '112233',
            'cargo' => 'Professor',
            'evaluation_group' => 'educacao',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.offices.destroy', $office));

        $response->assertRedirect(route('admin.offices.index'));
        $response->assertSessionHas('error', 'Não é possível excluir esta secretaria/lotação pois existem servidores vinculados a ela.');
        
        $this->assertEquals(1, Office::count());
    }

    public function test_admin_can_delete_empty_office(): void
    {
        $office = Office::create([
            'name' => 'Secretaria Vazia',
            'sigla' => 'SV',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.offices.destroy', $office));

        $response->assertRedirect(route('admin.offices.index'));
        $response->assertSessionHas('success', 'Secretaria/Lotação excluída com sucesso.');
        
        $this->assertEquals(0, Office::count());
    }
}
