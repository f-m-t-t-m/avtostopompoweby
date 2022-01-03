<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->jobTitle(),
            'short_name' => $this->faker->citySuffix(),
            'user_id' => $this->faker->randomElement(User::query()->where('role', 'head')->pluck('id'))
        ];
    }
}
