<?php

namespace Database\Seeders;

use App\Models\PlaylistCategory;
use App\Models\PlaylistVideo;
use Illuminate\Database\Seeder;

class BusinessPlaylistSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['Inspirational People', 'Ratan Tata', 'https://youtu.be/uDT3p_tlu3Y?si=f-j9JPGjh7I56a20', 'Video'],
            ['Inspirational People', 'Elon Musk', 'https://youtu.be/MWr0DWFe_DM?si=Jr0eoixrn3RnNt28', 'Video'],
            ['Playlists & Channels', 'Play List', 'https://youtube.com/@willstarfilms?si=DlS8D9VdYg_u0T16', 'Channel'],
            ['Playlists & Channels', 'Scratch Story', 'https://youtube.com/@deeplifelesons?si=9bX0iTrIW794wG7s', 'Channel'],
            ['Learning & Education', 'IDC', 'https://youtu.be/7HZBZIunXhc?si=kfJUpZNxmuqq_cWG', 'Video'],
            ['Animal Lessons', '5 Animal Teach', 'https://youtube.com/shorts/3ifCSm8gZjg?si=IlwsdDU4afgbNwmt', 'Short'],
        ];

        $sort = [];
        foreach ($rows as $row) {
            [$categoryName, $title, $url, $type] = $row;
            $parsed = PlaylistVideo::parseUrl($url);
            if ($parsed === null) {
                continue;
            }

            $category = PlaylistCategory::query()->firstOrCreate(
                ['name' => $categoryName],
                [
                    'details' => null,
                    'sort_order' => count($sort) + 1,
                ]
            );
            $sort[$category->id] = ($sort[$category->id] ?? 0) + 1;

            PlaylistVideo::query()->updateOrCreate(
                [
                    'playlist_category_id' => $category->id,
                    'title' => $title,
                ],
                [
                    'content_type' => $type,
                    'youtube_url' => $parsed['youtube_url'],
                    'youtube_id' => $parsed['youtube_id'],
                    'sort_order' => $sort[$category->id],
                ]
            );
        }
    }
}
