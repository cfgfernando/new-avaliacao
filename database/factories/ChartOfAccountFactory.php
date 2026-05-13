<?php

namespace Database\Factories\Finance;

use App\Models\Finance\ChartOfAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChartOfAccountFactory extends Factory
{
    protected $model = ChartOfAccount::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->numerify('#.#.##'),
            'name' => $this->faker->words(3, true),
            'type' => $this->faker->randomElement(['asset', 'liability', 'equity', 'revenue', 'expense']),
            'is_active' => true,
        ];
    }
}
