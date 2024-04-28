<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdsFacebook>
 */
class CampaignFacebookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 2,
            'name' => fake('en')->company(),
            'status' => true,
            'type' => 1,
            'result' => fake()->numberBetween(10, 100),
            'reach' => fake()->numberBetween(10, 100),
            'impression' => 290,
            'amount_spent' => fake()->numberBetween(100000, 1000000),
            'started_at' => fake()->dateTimeBetween('-1 months'),
            'ended_at' => '2024-04-17',
        ];
    }
}
