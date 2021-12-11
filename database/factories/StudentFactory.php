<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->randomElement(User::query()->where('role','student')->pluck('id')),
            'group_id' => $this->faker->randomElement(Group::query()->pluck('id')),
        ];
    }
}
