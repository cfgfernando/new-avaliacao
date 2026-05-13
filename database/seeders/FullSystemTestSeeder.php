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
use App\Models\HierarchyNode;
use App\Models\Cell;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class FullSystemTestSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('pt_BR');
        $this->command->info('Iniciando Seeding Completo para Testes...');

        // 1. Hierarquia MDA
        $this->command->line('Criando Estrutura MDA...');
        $network = HierarchyNode::updateOrCreate(['name' => 'Rede Principal'], ['type' => 'Network', 'active' => true]);
        
        $districts = [];
        for ($i = 1; $i <= 3; $i++) {
            $districts[] = HierarchyNode::updateOrCreate(
                ['name' => "Distrito $i", 'parent_id' => $network->id],
                ['type' => 'District', 'active' => true]
            );
        }

        $sectors = [];
        foreach ($districts as $district) {
            for ($j = 1; $j <= 2; $j++) {
                $sectors[] = HierarchyNode::updateOrCreate(
                    ['name' => "Setor $j de " . $district->name, 'parent_id' => $district->id],
                    ['type' => 'Sector', 'active' => true]
                );
            }
        }

        // 2. Células
        $this->command->line('Criando Células...');
        $cells = [];
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        foreach ($sectors as $sector) {
            for ($k = 1; $k <= 4; $k++) {
                $cells[] = Cell::firstOrCreate(
                    ['name' => "Célula " . $faker->colorName . " " . $k],
                    [
                        'node_id' => $sector->id,
                        'address' => $faker->address,
                        'meeting_day' => $faker->randomElement($days),
                        'meeting_time' => '20:00:00',
                        'active' => true
                    ]
                );
            }
        }

        // 3. Usuários e Membros
        $this->command->line('Criando Membros...');
        $validRoles = ['Admin', 'Treasurer', 'Supervisor', 'Leader'];
        
        // Garantir o Admin Master
        User::updateOrCreate(
            ['email' => 'admin@mdachurch.com'],
            [
                'name' => 'Admin Master',
                'password' => bcrypt('admin123'),
                'role' => 'Admin',
                'active' => true
            ]
        );

        for ($i = 0; $i < 50; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password'),
                'role' => $faker->randomElement($validRoles),
                'active' => true
            ]);

            Member::create([
                'user_id' => $user->id,
                'birth_date' => $faker->date('Y-m-d', '-18 years'),
                'gender' => $faker->randomElement(['Male', 'Female']),
                'phone' => substr($faker->phoneNumber, 0, 20),
                'status' => 'Active'
            ]);
        }

        // 4. Estrutura Financeira
        $this->command->line('Criando Estrutura Financeira...');
        
        $accounts = [];
        $accountNames = ['Itau PF', 'Itau PJ', 'Caixa Sede', 'Caixa Eventos', 'Cofre Investimento'];
        foreach ($accountNames as $name) {
            $accounts[] = FinancialAccount::updateOrCreate(
                ['name' => $name],
                [
                    'type' => $faker->randomElement(['bank', 'cash', 'investment']),
                    'balance_cache' => $faker->randomFloat(2, 500, 20000),
                    'is_active' => true
                ]
            );
        }

        $coa = [];
        $coaItems = [
            ['4.1.01', 'Dízimos', 'revenue'],
            ['4.1.02', 'Ofertas Alzira', 'revenue'],
            ['3.1.01', 'Aluguel Prédio', 'expense'],
            ['3.1.02', 'Energia Elétrica', 'expense'],
            ['3.1.03', 'Internet e Software', 'expense'],
            ['1.2.01', 'Móveis e Utensílios', 'asset'],
        ];
        foreach ($coaItems as $item) {
            $coa[] = ChartOfAccount::updateOrCreate(
                ['code' => $item[0]],
                ['name' => $item[1], 'type' => $item[2], 'is_active' => true]
            );
        }

        $ccs = [];
        $ccNames = ['Administrativo', 'Louvor e Artes', 'Kids', 'Jovens', 'Missões'];
        foreach ($ccNames as $name) {
            $ccs[] = CostCenter::updateOrCreate(
                ['name' => $name],
                ['code' => strtoupper(substr($name, 0, 3)), 'is_active' => true]
            );
        }

        // 5. Transações em Massa
        $this->command->line('Gerando 100+ Transações...');
        
        for ($i = 0; $i < 150; $i++) {
            $type = $faker->randomElement(['income', 'expense']);
            $currentCoa = collect($coa)->where('type', ($type == 'income' ? 'revenue' : 'expense'))->random();
            
            Transaction::create([
                'financial_account_id' => collect($accounts)->random()->id,
                'chart_of_account_id' => $currentCoa->id,
                'cost_center_id' => collect($ccs)->random()->id,
                'type' => $type,
                'payment_method' => $faker->randomElement(['cash', 'pix', 'transfer', 'slip']),
                'amount' => $faker->randomFloat(2, 50, 3000),
                'description' => $faker->sentence(3),
                'transaction_date' => $faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
                'status' => 'paid',
            ]);
        }

        $this->command->info('Seeding Completo Concluído!');
    }
}
