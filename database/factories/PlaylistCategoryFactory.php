<?php

namespace Database\Factories;

use App\Models\PlaylistCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlaylistCategory>
 */
class PlaylistCategoryFactory extends Factory
{
    protected $model = PlaylistCategory::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'details' => fake()->sentence(),
            'sort_order' => 0,
        ];
    }
}
