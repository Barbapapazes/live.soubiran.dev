<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'twitch_id' => (string) (fake()->unique()->numberBetween(1, 1000000)),
            'has_access' => false,
            'remember_token' => Str::random(10),
        ];
    }

    public function hasAccess(): static
    {
        return $this->state([
            'has_access' => true,
        ]);
    }
}
