<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'          => fake()->sentence(4),
            'description'   => fake()->sentence(6),
            'init_date'     => Carbon::now(),
            'end_date'      => fake()->dateTimeBetween('-1 day','+30 days'),
            'active'        => true,
            'slug'          => fake()->slug(4),
        ];
    }
}
