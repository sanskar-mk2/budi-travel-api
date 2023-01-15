<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Support>
 */
class SupportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = \App\Models\User::factory()->create();
        $user->assignRole('agent');

        return [
            'user_id' => $user->id,
            'topic' => $this->faker->word,
            'subject' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'resolved' => $this->faker->boolean,
        ];
    }
}
