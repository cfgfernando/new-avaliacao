<?php

namespace Database\Factories\Finance;

use App\Models\Finance\Transaction;
use App\Models\Finance\FinancialAccount;
use App\Models\Finance\ChartOfAccount;
use App\Models\Finance\CostCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'financial_account_id' => FinancialAccount::factory(),
            'chart_of_account_id' => ChartOfAccount::factory(),
            'cost_center_id' => CostCenter::factory(),
            'type' => $this->faker->randomElement(['income', 'expense', 'transfer']),
            'payment_method' => $this->faker->randomElement(['cash', 'pix', 'transfer', 'credit_card', 'debit_card', 'slip']),
            'amount' => $this->faker->randomFloat(2, 10, 5000),
            'description' => $this->faker->sentence(),
            'transaction_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['pending', 'paid', 'cancelled']),
        ];
    }
}
