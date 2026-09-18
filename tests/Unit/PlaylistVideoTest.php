<?php

namespace Tests\Unit;

use App\Models\PlaylistVideo;
use PHPUnit\Framework\TestCase;

class PlaylistVideoTest extends TestCase
{
    public function test_it_reads_youtube_ids_from_common_links(): void
    {
        $this->assertSame('dQw4w9WgXcQ', PlaylistVideo::videoIdFromUrl('https://www.youtube.com/watch?v=dQw4w9WgXcQ'));
        $this->assertSame('dQw4w9WgXcQ', PlaylistVideo::videoIdFromUrl('https://youtu.be/dQw4w9WgXcQ'));
        $this->assertSame('dQw4w9WgXcQ', PlaylistVideo::videoIdFromUrl('https://www.youtube.com/embed/dQw4w9WgXcQ'));
        $this->assertSame('dQw4w9WgXcQ', PlaylistVideo::videoIdFromUrl('https://www.youtube.com/shorts/dQw4w9WgXcQ'));
        $this->assertSame('dQw4w9WgXcQ', PlaylistVideo::videoIdFromUrl('dQw4w9WgXcQ'));
        $this->assertNull(PlaylistVideo::videoIdFromUrl('https://example.com/watch?v=dQw4w9WgXcQ'));

        $channel = PlaylistVideo::parseUrl('https://youtube.com/@willstarfilms?si=DlS8D9VdYg_u0T16');
        $this->assertSame('Channel', $channel['content_type'] ?? null);
        $this->assertSame('willstarfilms', $channel['youtube_id'] ?? null);

        $short = PlaylistVideo::parseUrl('https://youtube.com/shorts/3ifCSm8gZjg?si=IlwsdDU4afgbNwmt');
        $this->assertSame('Short', $short['content_type'] ?? null);
        $this->assertSame('3ifCSm8gZjg', $short['youtube_id'] ?? null);
    }
}
