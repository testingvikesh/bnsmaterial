@extends('layouts.app')
@section('title', 'Business Playlist')
@section('page_title', 'Business Playlist')

@section('content')
<div class="page-header">
    <div>
        <h2>Business Playlist</h2>
        <p>Click a category to watch its YouTube videos.</p>
    </div>
    <a href="{{ route('admin.playlist.categories.create') }}" class="btn btn-brand"><i class="bi bi-plus-lg"></i> Add Category</a>
</div>

@if($categories->isEmpty())
    <div class="card-panel">
        <div class="card-body">
            <div class="empty-state">No categories yet. Click Add Category.</div>
        </div>
    </div>
@else
    <div class="playlist-cat-grid">
        @foreach($categories as $item)
            <article class="playlist-cat-card">
                <a href="{{ route('admin.playlist.show', $item) }}" class="playlist-cat-main">
                    <span class="playlist-cat-icon"><i class="bi bi-collection-play"></i></span>
                    <h3>{{ $item->name }}</h3>
                    <p>{{ $item->details ? \Illuminate\Support\Str::limit($item->details, 90) : 'Open this category to watch videos.' }}</p>
                    <span class="badge-pill badge-staff">{{ $item->videos_count }} video{{ $item->videos_count === 1 ? '' : 's' }}</span>
                </a>
                <div class="playlist-cat-actions">
                    <a href="{{ route('admin.playlist.categories.edit', $item) }}" class="btn btn-edit-soft btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('admin.playlist.categories.destroy', $item) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger-soft btn-sm" type="submit" data-confirm="Delete this category and its videos?" data-confirm-title="Delete category?" data-confirm-btn="Yes, delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </article>
        @endforeach
    </div>
@endif
@endsection
