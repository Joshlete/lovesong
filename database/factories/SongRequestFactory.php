<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SongRequest>
 */
class SongRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'recipient_name' => fake()->name(),
            'style' => fake()->randomElement(['rock', 'pop', 'country', 'jazz', 'blues', 'classical', 'hip-hop', 'folk']),
            'mood' => fake()->randomElement(['happy', 'romantic', 'sad', 'energetic', 'calm', 'nostalgic', 'uplifting']),
            'lyrics_idea' => fake()->paragraph(3),
            'price_usd' => fake()->randomFloat(2, 50, 500),
            'currency' => fake()->randomElement(['USD', 'EUR', 'GBP']),
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed', 'cancelled']),
            'payment_reference' => fake()->optional()->regexify('[A-Z0-9]{10}'),
            'file_url' => fake()->optional()->url(),
            'delivered_at' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
