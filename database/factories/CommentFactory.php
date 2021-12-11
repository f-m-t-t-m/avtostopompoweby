<?php

namespace Database\Factories;

use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'text' => $this->faker->text(),
            'user_id' => $this->faker->randomElement(User::query()->where('role', 'student')->pluck('id')),
            'section_id' => $this->faker->randomElement(Section::query()->pluck('id')),
        ];
    }
}
