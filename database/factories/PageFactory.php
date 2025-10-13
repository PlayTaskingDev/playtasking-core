<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title'         => fake()->words(3, true),
            'description'   => fake()->sentence(),
            'content'       => fake()->text(500),
            'slug'          => fake()->slug(3, false)
        ];
    }
}
