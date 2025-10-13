<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Answer>
 */
class AnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title'             => fake()->sentence(),
            'featured_image'    => '/storage/assets/images/faker/' . fake()->image('./storage/app/public/assets/images/faker', 500, 300, 'animals', false, true, 'cats', true, 'jpg'),
            'is_correct'        => false
        ];
    }
}
