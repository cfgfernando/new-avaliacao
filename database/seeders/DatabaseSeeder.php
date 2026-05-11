<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Cell;
use App\Models\HierarchyNode;
use App\Models\Member;
use App\Models\User;
use App\Models\WeeklyReport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Iniciando Seeding de Alta Volumetria (Stress Test)...');

        // 1. Plano de Contas Básico (Obrigatório para contabilidade)
        $this->createChartOfAccounts();

        // 2. Usuário Admin Principal
        $admin = User::create([
            'name' => 'Admin Master',
            'email' => 'admin@mdachurch.com',
            'password' => Hash::make('admin123'),
            'role' => 'Admin',
            'active' => true,
        ]);

        // 3. Criar Hierarquia (1 Rede -> 5 Distritos -> 10 Áreas -> 20 Setores)
        $this->command->line('Criando hierarquia organizacional...');
        $network = HierarchyNode::factory()->create(['name' => 'Rede Principal', 'type' => 'Network']);
        
        $sectors = [];
        HierarchyNode::factory(5)->create(['type' => 'District', 'parent_id' => $network->id])
            ->each(function ($district) use (&$sectors) {
                HierarchyNode::factory(2)->create(['type' => 'Area', 'parent_id' => $district->id])
                    ->each(function ($area) use (&$sectors) {
                        $newSectors = HierarchyNode::factory(2)->create(['type' => 'Sector', 'parent_id' => $area->id]);
                        foreach($newSectors as $s) $sectors[] = $s;
                    });
            });

        // 4. Criar 100+ Células distribuídas nos setores
        $this->command->line('Criando 120 células...');
        $cells = [];
        foreach ($sectors as $sector) {
            $newCells = Cell::factory(6)->create(['node_id' => $sector->id]);
            foreach($newCells as $c) $cells[] = $c;
        }

        // 5. Criar 1.000+ Membros e vincular a células/mentores
        $this->command->line('Criando 1.200 membros...');
        $mentors = User::where('role', 'Supervisor')->orWhere('role', 'Leader')->get();
        
        Member::factory(1200)->create()->each(function ($member) use ($cells, $mentors) {
            $cell = $cells[array_rand($cells)];
            $member->user->update(['cell_id' => $cell->id]);
            $member->update(['mentor_id' => $mentors->random()->id]);
        });

        // 6. Criar 2.000+ Relatórios Semanais (Histórico de 1 ano)
        $this->command->line('Criando 2.500 relatórios semanais...');
        foreach ($cells as $cell) {
            WeeklyReport::factory(20)->create([
                'cell_id' => $cell->id,
                'submitted_by' => $cell->leader_id
            ]);
        }

        $this->command->info('Seeding concluído com sucesso!');
    }

    private function createChartOfAccounts()
    {
        // Ativo
        $disponivel = Account::create(['code' => '1.1.01', 'name' => 'Disponível', 'type' => 'Asset', 'nature' => 'Debit', 'is_analytical' => false]);
        Account::create(['code' => '1.1.01.001', 'name' => 'Caixa Geral', 'type' => 'Asset', 'nature' => 'Debit', 'parent_id' => $disponivel->id]);

        // Receita
        $receitasOp = Account::create(['code' => '4.1', 'name' => 'Receitas Operacionais', 'type' => 'Revenue', 'nature' => 'Credit', 'is_analytical' => false]);
        Account::create(['code' => '4.1.01', 'name' => 'Ofertas de Células', 'type' => 'Revenue', 'nature' => 'Credit', 'parent_id' => $receitasOp->id]);
        
        // Despesa
        $despesasOp = Account::create(['code' => '5.1', 'name' => 'Despesas Operacionais', 'type' => 'Expense', 'nature' => 'Debit', 'is_analytical' => false]);
        Account::create(['code' => '5.1.01', 'name' => 'Aluguel e Taxas', 'type' => 'Expense', 'nature' => 'Debit', 'parent_id' => $despesasOp->id]);
    }
}
