<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaylistVideo extends Model
{
    use HasFactory;

    public const TYPE_VIDEO = 'Video';
    public const TYPE_CHANNEL = 'Channel';
    public const TYPE_SHORT = 'Short';

    public const TYPES = [
        self::TYPE_VIDEO,
        self::TYPE_CHANNEL,
        self::TYPE_SHORT,
    ];

    protected $fillable = [
        'playlist_category_id',
        'title',
        'content_type',
        'youtube_url',
        'youtube_id',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PlaylistCategory::class, 'playlist_category_id');
    }

    /**
     * @return array{content_type: string, youtube_id: ?string, youtube_url: string}|null
     */
    public static function parseUrl(string $url): ?array
    {
        $url = trim($url);
        if ($url === '') {
            return null;
        }

        if (preg_match('/youtube\.com\/shorts\/([A-Za-z0-9_-]{11})/i', $url, $matches) === 1) {
            return [
                'content_type' => self::TYPE_SHORT,
                'youtube_id' => $matches[1],
                'youtube_url' => 'https://www.youtube.com/shorts/'.$matches[1],
            ];
        }

        if (preg_match('/(?:youtube(?:-nocookie)?\.com\/(?:watch\?(?:.*&)?v=|embed\/|live\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/i', $url, $matches) === 1) {
            return [
                'content_type' => self::TYPE_VIDEO,
                'youtube_id' => $matches[1],
                'youtube_url' => 'https://www.youtube.com/watch?v='.$matches[1],
            ];
        }

        if (preg_match('/^[A-Za-z0-9_-]{11}$/', $url) === 1) {
            return [
                'content_type' => self::TYPE_VIDEO,
                'youtube_id' => $url,
                'youtube_url' => 'https://www.youtube.com/watch?v='.$url,
            ];
        }

        if (preg_match('/youtube\.com\/@([A-Za-z0-9._-]+)/i', $url, $matches) === 1) {
            return [
                'content_type' => self::TYPE_CHANNEL,
                'youtube_id' => $matches[1],
                'youtube_url' => 'https://www.youtube.com/@'.$matches[1],
            ];
        }

        return null;
    }

    public static function videoIdFromUrl(string $url): ?string
    {
        $parsed = self::parseUrl($url);
        if ($parsed === null || $parsed['content_type'] === self::TYPE_CHANNEL) {
            return null;
        }

        return $parsed['youtube_id'];
    }

    public function isChannel(): bool
    {
        return $this->content_type === self::TYPE_CHANNEL;
    }

    public function canEmbed(): bool
    {
        return ! $this->isChannel() && filled($this->youtube_id);
    }

    public function thumbnailUrl(): ?string
    {
        if ($this->isChannel() || ! filled($this->youtube_id)) {
            return null;
        }

        return 'https://img.youtube.com/vi/'.$this->youtube_id.'/hqdefault.jpg';
    }

    public function embedUrl(): ?string
    {
        if (! $this->canEmbed()) {
            return null;
        }

        return 'https://www.youtube-nocookie.com/embed/'.$this->youtube_id;
    }

    public function watchUrl(): string
    {
        return match ($this->content_type) {
            self::TYPE_CHANNEL => 'https://www.youtube.com/@'.$this->youtube_id,
            self::TYPE_SHORT => 'https://www.youtube.com/shorts/'.$this->youtube_id,
            default => 'https://www.youtube.com/watch?v='.$this->youtube_id,
        };
    }
}
