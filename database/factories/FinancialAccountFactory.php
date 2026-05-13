<?php

namespace Database\Factories\Finance;

use App\Models\Finance\FinancialAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinancialAccountFactory extends Factory
{
    protected $model = FinancialAccount::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company() . ' - ' . $this->faker->randomElement(['Caixa', 'Conta Corrente', 'Investimento']),
            'type' => $this->faker->randomElement(['bank', 'cash', 'investment']),
            'bank_name' => $this->faker->optional()->company(),
            'agency' => $this->faker->optional()->numerify('####'),
            'account_number' => $this->faker->optional()->numerify('#####-#'),
            'balance_cache' => $this->faker->randomFloat(2, 0, 50000),
            'is_active' => true,
        ];
    }
}
