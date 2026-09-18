<?php

namespace Tests\Feature;

use App\Models\MemberProfile;
use App\Models\PlaylistCategory;
use App\Models\PlaylistVideo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessPlaylistTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_view_playlist(): void
    {
        $this->get(route('admin.playlist.index'))->assertRedirect(route('login'));
    }

    public function test_staff_can_create_a_category_and_open_it(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)->post(route('admin.playlist.categories.store'), [
            'name' => 'Sales Training',
            'details' => 'Customer closing videos',
        ])->assertRedirect(route('admin.playlist.index'));

        $this->assertDatabaseHas('playlist_categories', [
            'name' => 'Sales Training',
            'details' => 'Customer closing videos',
        ]);

        $category = PlaylistCategory::query()->first();
        $this->actingAs($staff)
            ->get(route('admin.playlist.index'))
            ->assertOk()
            ->assertSee('Business Playlist')
            ->assertSee('Sales Training');

        $this->actingAs($staff)
            ->get(route('admin.playlist.show', $category))
            ->assertOk()
            ->assertSee('Sales Training')
            ->assertSee('Add YouTube video');
    }

    public function test_youtube_video_is_saved_and_shown_under_its_category(): void
    {
        $admin = User::factory()->admin()->create();
        $sales = PlaylistCategory::factory()->create(['name' => 'Sales Training']);
        $finance = PlaylistCategory::factory()->create(['name' => 'Finance']);

        $this->actingAs($admin)->post(route('admin.playlist.videos.store', $sales), [
            'title' => 'Closing Masterclass',
            'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ',
        ])->assertRedirect(route('admin.playlist.show', $sales));

        $this->assertDatabaseHas('playlist_videos', [
            'playlist_category_id' => $sales->id,
            'title' => 'Closing Masterclass',
            'youtube_id' => 'dQw4w9WgXcQ',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.playlist.show', $sales))
            ->assertOk()
            ->assertSee('Closing Masterclass')
            ->assertSee('https://www.youtube.com/watch?v=dQw4w9WgXcQ');

        $this->actingAs($admin)
            ->get(route('admin.playlist.show', $finance))
            ->assertOk()
            ->assertDontSee('Closing Masterclass');
    }

    public function test_invalid_youtube_link_is_rejected(): void
    {
        $staff = User::factory()->create();
        $category = PlaylistCategory::factory()->create();

        $this->actingAs($staff)->post(route('admin.playlist.videos.store', $category), [
            'title' => 'Broken link',
            'youtube_url' => 'https://example.com/video',
        ])->assertSessionHasErrors('youtube_url');

        $this->assertDatabaseCount('playlist_videos', 0);
    }

    public function test_deleting_a_category_removes_its_videos(): void
    {
        $admin = User::factory()->admin()->create();
        $category = PlaylistCategory::factory()->create();
        PlaylistVideo::factory()->create(['playlist_category_id' => $category->id]);

        $this->actingAs($admin)
            ->delete(route('admin.playlist.categories.destroy', $category))
            ->assertRedirect(route('admin.playlist.index'));

        $this->assertDatabaseMissing('playlist_categories', ['id' => $category->id]);
        $this->assertDatabaseCount('playlist_videos', 0);
    }

    public function test_member_page_shows_videos_grouped_by_category(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->create(['name' => 'Alice Student']);
        MemberProfile::factory()->create(['user_id' => $member->id]);
        $sales = PlaylistCategory::factory()->create(['name' => 'Sales Training']);
        PlaylistCategory::factory()->create(['name' => 'Finance']);
        PlaylistVideo::factory()->create([
            'playlist_category_id' => $sales->id,
            'title' => 'Closing Masterclass',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.members.show', $member))
            ->assertOk()
            ->assertSee('Business Playlist')
            ->assertSee('Sales Training')
            ->assertSee('Finance')
            ->assertSee('Closing Masterclass')
            ->assertSee('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
    }

    public function test_channel_and_short_links_are_saved_by_type(): void
    {
        $staff = User::factory()->create();
        $category = PlaylistCategory::factory()->create(['name' => 'Playlists & Channels']);

        $this->actingAs($staff)->post(route('admin.playlist.videos.store', $category), [
            'title' => 'Play List',
            'content_type' => 'Channel',
            'youtube_url' => 'https://youtube.com/@willstarfilms?si=DlS8D9VdYg_u0T16',
        ])->assertRedirect(route('admin.playlist.show', $category));

        $this->assertDatabaseHas('playlist_videos', [
            'title' => 'Play List',
            'content_type' => 'Channel',
            'youtube_id' => 'willstarfilms',
            'youtube_url' => 'https://www.youtube.com/@willstarfilms',
        ]);

        $this->actingAs($staff)->post(route('admin.playlist.videos.store', $category), [
            'title' => '5 Animal Teach',
            'content_type' => 'Short',
            'youtube_url' => 'https://youtube.com/shorts/3ifCSm8gZjg?si=IlwsdDU4afgbNwmt',
        ])->assertRedirect(route('admin.playlist.show', $category));

        $this->assertDatabaseHas('playlist_videos', [
            'title' => '5 Animal Teach',
            'content_type' => 'Short',
            'youtube_id' => '3ifCSm8gZjg',
        ]);
    }

    public function test_sample_playlist_inserts_are_grouped_by_category(): void
    {
        $this->seed(\Database\Seeders\BusinessPlaylistSeeder::class);

        $this->assertDatabaseCount('playlist_categories', 4);
        $this->assertDatabaseCount('playlist_videos', 6);
        $this->assertDatabaseHas('playlist_categories', ['name' => 'Inspirational People']);
        $this->assertDatabaseHas('playlist_videos', ['title' => 'Ratan Tata', 'content_type' => 'Video', 'youtube_id' => 'uDT3p_tlu3Y']);
        $this->assertDatabaseHas('playlist_videos', ['title' => 'Play List', 'content_type' => 'Channel', 'youtube_id' => 'willstarfilms']);
        $this->assertDatabaseHas('playlist_videos', ['title' => '5 Animal Teach', 'content_type' => 'Short', 'youtube_id' => '3ifCSm8gZjg']);
    }
}
