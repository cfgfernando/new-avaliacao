<?php

namespace Database\Factories;

use App\Models\Cell;
use App\Models\WeeklyReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeeklyReportFactory extends Factory
{
    protected $model = WeeklyReport::class;

    public function definition(): array
    {
        return [
            'cell_id' => Cell::factory(),
            'report_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'present_members' => $this->faker->numberBetween(5, 20),
            'visitors' => $this->faker->numberBetween(0, 5),
            'children' => $this->faker->numberBetween(0, 5),
            'mda_count' => $this->faker->numberBetween(2, 10),
            'conversions' => $this->faker->numberBetween(0, 2),
            'kg_social' => $this->faker->randomFloat(2, 0, 10),
            'offer_pix' => $this->faker->randomFloat(2, 0, 200),
            'offer_cash' => $this->faker->randomFloat(2, 0, 150),
            'status' => $this->faker->randomElement(['Draft', 'Submitted', 'Conciliated']),
            'submitted_by' => User::factory(),
            'submitted_at' => now(),
            'observations' => $this->faker->sentence(),
        ];
    }
}
