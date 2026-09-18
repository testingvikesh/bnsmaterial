<?php

namespace Database\Factories;

use App\Models\PlaylistCategory;
use App\Models\PlaylistVideo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlaylistVideo>
 */
class PlaylistVideoFactory extends Factory
{
    protected $model = PlaylistVideo::class;

    public function definition(): array
    {
        $id = 'dQw4w9WgXcQ';

        return [
            'playlist_category_id' => PlaylistCategory::factory(),
            'title' => fake()->sentence(4),
            'youtube_url' => 'https://www.youtube.com/watch?v='.$id,
            'youtube_id' => $id,
            'content_type' => PlaylistVideo::TYPE_VIDEO,
            'sort_order' => 0,
        ];
    }
}
