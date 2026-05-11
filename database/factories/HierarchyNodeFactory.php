<?php

namespace Database\Factories;

use App\Models\HierarchyNode;
use Illuminate\Database\Eloquent\Factories\Factory;

class HierarchyNodeFactory extends Factory
{
    protected $model = HierarchyNode::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word() . ' ' . $this->faker->randomElement(['Network', 'District', 'Area', 'Sector']),
            'type' => $this->faker->randomElement(['Network', 'District', 'Area', 'Sector']),
            'parent_id' => null,
            'active' => true,
        ];
    }
}
