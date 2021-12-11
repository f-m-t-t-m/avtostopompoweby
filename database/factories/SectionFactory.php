<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class SectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->domainName(),
            'text' => $this->faker->text(),
            'subject_id' => $this->faker->randomElement(Subject::query()->pluck('id')),
        ];
    }
}
