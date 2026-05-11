<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'mentor_id' => null, // Será preenchido no seeder
            'conversion_date' => $this->faker->date(),
            'baptism_date' => $this->faker->optional()->date(),
            'phone' => $this->faker->phoneNumber(),
            'cpf' => $this->faker->numerify('###########'),
            'birth_date' => $this->faker->date('Y-m-d', '-18 years'),
            'gender' => $this->faker->randomElement(['M', 'F']),
            'marital_status' => $this->faker->randomElement(['Single', 'Married', 'Divorced', 'Widowed']),
            'status' => 'Active',
        ];
    }
}
