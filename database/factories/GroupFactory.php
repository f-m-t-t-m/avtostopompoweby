<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'short_name' => $this->faker->colorName(),
            'code' => $this->faker->currencyCode(),
            'department_id' => $this->faker->randomElement(Department::query()->pluck('id'))
        ];
    }
}
