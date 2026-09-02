<?php

namespace Database\Factories;

use App\Models\ManageSession;
use App\Models\SessionPrompt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SessionPrompt>
 */
class SessionPromptFactory extends Factory
{
    protected $model = SessionPrompt::class;

    public function definition(): array
    {
        return [
            'manage_session_id' => ManageSession::factory(),
            'title' => fake()->sentence(4),
            'body' => fake()->paragraphs(3, true),
            'is_active' => false,
            'sort_order' => 0,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }
}
