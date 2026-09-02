<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MaterialFile extends Model
{
    protected $fillable = [
        'user_id',
        'manage_session_id',
        'session_prompt_id',
        'file_path',
        'generated_at',
        'duration_ms',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'duration_ms' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(ManageSession::class, 'manage_session_id');
    }

    public function prompt(): BelongsTo
    {
        return $this->belongsTo(SessionPrompt::class, 'session_prompt_id');
    }

    public function downloadName(): string
    {
        $member = str($this->user?->name ?: 'member')->slug('_');
        $session = str($this->session?->name ?: 'session')->slug('_');

        return "{$member}_{$session}.html";
    }

    public function jsonPath(): string
    {
        $path = preg_replace('/\.html$/', '.json', (string) $this->file_path);

        return is_string($path) ? $path : '';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function cachedPayload(): ?array
    {
        $jsonPath = $this->jsonPath();
        if ($jsonPath === '' || ! Storage::disk('local')->exists($jsonPath)) {
            return null;
        }

        $decoded = json_decode((string) Storage::disk('local')->get($jsonPath), true);

        return is_array($decoded) ? $decoded : null;
    }

    public function hasUsableCache(): bool
    {
        return filled($this->file_path)
            && Storage::disk('local')->exists($this->file_path)
            && $this->cachedPayload() !== null;
    }

    public function durationLabel(): string
    {
        $ms = (int) ($this->duration_ms ?? 0);
        if ($ms > 0) {
            if ($ms < 1000) {
                return $ms.' ms';
            }

            $seconds = $ms / 1000;
            if ($seconds < 60) {
                return number_format($seconds, 1).' s';
            }

            $total = (int) round($seconds);

            return intdiv($total, 60).'m '.($total % 60).'s';
        }

        if ($this->generated_at) {
            return $this->generated_at->timezone((string) config('app.timezone'))->format('d M, h:i A');
        }

        return '—';
    }
}
