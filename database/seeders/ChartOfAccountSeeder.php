<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Finance\ChartOfAccount;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['code' => '1.1.01', 'name' => 'Dízimos', 'type' => 'revenue'],
            ['code' => '1.1.02', 'name' => 'Ofertas de Célula', 'type' => 'revenue'],
            ['code' => '1.1.03', 'name' => 'Ofertas de Culto', 'type' => 'revenue'],
            ['code' => '1.1.04', 'name' => 'Missões / Campanhas', 'type' => 'revenue'],
            ['code' => '1.1.05', 'name' => 'Trabalho Voluntário (Valor Justo)', 'type' => 'revenue'],
            ['code' => '1.1.06', 'name' => 'Cantina / Lanchonete', 'type' => 'revenue'],
            ['code' => '1.1.07', 'name' => 'Livraria / Gráfica', 'type' => 'revenue'],
            ['code' => '1.1.08', 'name' => 'Eventos Pagos', 'type' => 'revenue'],
            
            // Exemplos de Despesas para completar o Plano
            ['code' => '2.1.01', 'name' => 'Aluguel / Condomínio', 'type' => 'expense'],
            ['code' => '2.1.02', 'name' => 'Energia / Água / Telefone', 'type' => 'expense'],
            ['code' => '2.1.03', 'name' => 'Manutenção Predial', 'type' => 'expense'],
        ];

        foreach ($accounts as $account) {
            $account['is_active'] = true;
            ChartOfAccount::updateOrCreate(
                ['code' => $account['code']],
                $account
            );
        }
    }
}
