<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->title(),
            'group_id' => $this->faker->randomElement(Group::query()->pluck('id')),
            'user_id' => $this->faker->randomElement(User::query()->where('role','teacher')->pluck('id')),
        ];
    }
}
