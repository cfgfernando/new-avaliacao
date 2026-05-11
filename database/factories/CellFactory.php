<?php

namespace Database\Factories;

use App\Models\Cell;
use App\Models\HierarchyNode;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CellFactory extends Factory
{
    protected $model = Cell::class;

    public function definition(): array
    {
        return [
            'name' => 'Célula ' . $this->faker->unique()->firstName(),
            'node_id' => HierarchyNode::factory(),
            'leader_id' => User::factory(),
            'meeting_day' => $this->faker->randomElement(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']),
            'meeting_time' => $this->faker->time('H:i'),
            'address' => $this->faker->streetAddress(),
            'neighborhood' => $this->faker->word(),
            'city' => $this->faker->city(),
            'active' => true,
        ];
    }
}
