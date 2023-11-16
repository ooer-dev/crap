<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reaction>
 */
class ReactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'guild_id' => config('services.discord.guild_id'),

            'trigger' => fake()->words(2, true),
            'response' => fake()->sentence(),

            'delete_trigger' => fake()->boolean(),
            'dm_response' => fake()->boolean(),
            'contains_anywhere' => fake()->boolean(),
        ];
    }
}
