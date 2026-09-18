<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlaylistCategory;
use App\Models\PlaylistVideo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PlaylistVideoController extends Controller
{
    public function create(PlaylistCategory $playlistCategory): View
    {
        return view('admin.playlist.video-form', [
            'category' => $playlistCategory,
            'video' => null,
        ]);
    }

    public function store(Request $request, PlaylistCategory $playlistCategory): RedirectResponse
    {
        $data = $this->validatedVideo($request);
        $max = (int) $playlistCategory->videos()->max('sort_order');

        $playlistCategory->videos()->create([
            ...$data,
            'sort_order' => $max + 1,
        ]);

        return redirect()->route('admin.playlist.show', $playlistCategory)
            ->with('success', 'YouTube video added to this category.');
    }

    public function edit(PlaylistVideo $playlistVideo): View
    {
        $playlistVideo->load('category');

        return view('admin.playlist.video-form', [
            'category' => $playlistVideo->category,
            'video' => $playlistVideo,
        ]);
    }

    public function update(Request $request, PlaylistVideo $playlistVideo): RedirectResponse
    {
        $playlistVideo->update($this->validatedVideo($request));

        return redirect()->route('admin.playlist.show', $playlistVideo->playlist_category_id)
            ->with('success', 'YouTube video updated successfully.');
    }

    public function destroy(PlaylistVideo $playlistVideo): RedirectResponse
    {
        $categoryId = $playlistVideo->playlist_category_id;
        $playlistVideo->delete();

        return redirect()->route('admin.playlist.show', $categoryId)
            ->with('success', 'YouTube video removed.');
    }

    /**
     * @return array{title: string, content_type: string, youtube_url: string, youtube_id: string}
     */
    private function validatedVideo(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content_type' => ['nullable', 'in:'.implode(',', PlaylistVideo::TYPES)],
            'youtube_url' => ['required', 'string', 'max:500'],
        ]);

        $parsed = PlaylistVideo::parseUrl((string) $data['youtube_url']);
        if ($parsed === null) {
            throw ValidationException::withMessages([
                'youtube_url' => 'Enter a valid YouTube video, Shorts, or channel link.',
            ]);
        }

        $contentType = $data['content_type'] ?? $parsed['content_type'];
        if ($contentType === PlaylistVideo::TYPE_CHANNEL && $parsed['content_type'] !== PlaylistVideo::TYPE_CHANNEL) {
            throw ValidationException::withMessages([
                'youtube_url' => 'This type needs a YouTube channel link such as youtube.com/@name.',
            ]);
        }
        if ($contentType !== PlaylistVideo::TYPE_CHANNEL && $parsed['content_type'] === PlaylistVideo::TYPE_CHANNEL) {
            throw ValidationException::withMessages([
                'youtube_url' => 'This type needs a YouTube video or Shorts link.',
            ]);
        }

        return [
            'title' => $data['title'],
            'content_type' => $contentType === PlaylistVideo::TYPE_CHANNEL
                ? PlaylistVideo::TYPE_CHANNEL
                : $parsed['content_type'],
            'youtube_url' => $parsed['youtube_url'],
            'youtube_id' => (string) $parsed['youtube_id'],
        ];
    }
}
