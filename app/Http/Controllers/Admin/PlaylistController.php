<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlaylistCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlaylistController extends Controller
{
    public function index(): View
    {
        $categories = PlaylistCategory::query()
            ->withCount('videos')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.playlist.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.playlist.category-form', ['category' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        PlaylistCategory::create($this->validatedCategory($request));

        return redirect()->route('admin.playlist.index')
            ->with('success', 'Category added to Business Playlist.');
    }

    public function show(PlaylistCategory $playlistCategory): View
    {
        $playlistCategory->load(['videos' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')]);

        return view('admin.playlist.show', ['category' => $playlistCategory]);
    }

    public function edit(PlaylistCategory $playlistCategory): View
    {
        return view('admin.playlist.category-form', ['category' => $playlistCategory]);
    }

    public function update(Request $request, PlaylistCategory $playlistCategory): RedirectResponse
    {
        $playlistCategory->update($this->validatedCategory($request, $playlistCategory));

        return redirect()->route('admin.playlist.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(PlaylistCategory $playlistCategory): RedirectResponse
    {
        $playlistCategory->delete();

        return redirect()->route('admin.playlist.index')
            ->with('success', 'Category deleted successfully.');
    }

    private function validatedCategory(Request $request, ?PlaylistCategory $category = null): array
    {
        $max = (int) PlaylistCategory::query()->max('sort_order');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string'],
        ]);

        $data['sort_order'] = $category?->sort_order ?? ($max + 1);

        return $data;
    }
}
