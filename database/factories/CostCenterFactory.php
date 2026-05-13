<?php

namespace Database\Factories\Finance;

use App\Models\Finance\CostCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

class CostCenterFactory extends Factory
{
    protected $model = CostCenter::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->numerify('CC-##'),
            'name' => $this->faker->department(),
            'description' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}
