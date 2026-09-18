@extends('layouts.app')
@section('title', $category->name)
@section('page_title', $category->name)

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $category->name }}</h2>
        <p>{{ $category->details ?: 'Watch YouTube videos in this category.' }}</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.playlist.index') }}" class="btn btn-soft">All categories</a>
        <a href="{{ route('admin.playlist.videos.create', $category) }}" class="btn btn-brand"><i class="bi bi-youtube"></i> Add YouTube video</a>
    </div>
</div>

@if($category->videos->isEmpty())
    <div class="card-panel">
        <div class="card-body">
            <div class="empty-state">No videos in this category yet. Click Add YouTube video.</div>
        </div>
    </div>
@else
    <div class="playlist-video-grid">
        @foreach($category->videos as $video)
            <article class="playlist-video-card">
                @if($video->canEmbed())
                    <button type="button" class="playlist-thumb" data-bs-toggle="modal" data-bs-target="#playlistVideoModal" data-embed="{{ $video->embedUrl() }}" data-title="{{ $video->title }}">
                        <img src="{{ $video->thumbnailUrl() }}" alt="{{ $video->title }}">
                        <span class="playlist-play"><i class="bi bi-play-fill"></i></span>
                    </button>
                @else
                    <a href="{{ $video->watchUrl() }}" class="playlist-thumb playlist-thumb-channel" target="_blank" rel="noopener">
                        <span class="playlist-play"><i class="bi bi-youtube"></i></span>
                    </a>
                @endif
                <div class="playlist-video-body">
                    <span class="badge-pill badge-staff mb-2">{{ $video->content_type }}</span>
                    <h3>{{ $video->title }}</h3>
                    <div class="playlist-video-actions">
                        <a href="{{ $video->watchUrl() }}" class="btn btn-soft btn-sm" target="_blank" rel="noopener">
                            <i class="bi bi-box-arrow-up-right"></i> YouTube
                        </a>
                        <a href="{{ route('admin.playlist.videos.edit', $video) }}" class="btn btn-edit-soft btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.playlist.videos.destroy', $video) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger-soft btn-sm" type="submit" data-confirm="Remove this video from the category?" data-confirm-title="Remove video?" data-confirm-btn="Yes, remove">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
@endif

<div class="modal fade" id="playlistVideoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="playlistVideoTitle">Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="playlist-embed">
                    <iframe id="playlistVideoFrame" src="" title="YouTube video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const modal = document.getElementById('playlistVideoModal');
    const frame = document.getElementById('playlistVideoFrame');
    const title = document.getElementById('playlistVideoTitle');
    if (!modal || !frame) return;
    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;
        title.textContent = button.getAttribute('data-title') || 'Video';
        frame.src = button.getAttribute('data-embed') + '?autoplay=1';
    });
    modal.addEventListener('hidden.bs.modal', function () {
        frame.src = '';
    });
})();
</script>
@endpush
