@extends('layouts.app')
@section('title', $member->name)
@section('page_title', $member->name)

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $member->name }}</h2>
        <p>
            {{ $member->mobileNumber() }}
            @if($member->memberProfile?->business_name)
                · {{ $member->memberProfile->business_name }}
            @endif
        </p>
    </div>
    <a href="{{ route('admin.members.index') }}" class="btn btn-soft">Back</a>
</div>

<div class="member-meta-grid">
    <div class="member-meta-item">
        <span>Business Name</span>
        <strong>{{ $member->memberProfile?->business_name ?: '—' }}</strong>
    </div>
    <div class="member-meta-item">
        <span>Business Category</span>
        <strong>{{ $member->memberProfile?->business_category ?: '—' }}</strong>
    </div>
    <div class="member-meta-item">
        <span>Business Introduction</span>
        <strong>{{ $member->memberProfile?->business_description ?: '—' }}</strong>
    </div>
    <div class="member-meta-item">
        <span>Business Main Product</span>
        <strong>{{ $member->memberProfile?->main_products_services ?: '—' }}</strong>
    </div>
</div>

<h5 class="session-box-heading">Sessions</h5>
<div class="session-box-grid">
    @forelse($sessions as $index => $session)
        <article class="session-box">
            <div class="session-box-top">
                <span class="session-box-no">Session {{ $index + 1 }}</span>
                <span class="badge-pill badge-staff">{{ $session->prompts_count }} prompt{{ $session->prompts_count === 1 ? '' : 's' }}</span>
            </div>
            <h3>{{ $session->name }}</h3>
            <p>{{ $session->details ? \Illuminate\Support\Str::limit($session->details, 140) : 'No session details.' }}</p>
            <div class="session-box-foot">
                <span>{{ $session->activePrompt?->title ?: 'No active prompt' }}</span>
            </div>
        </article>
    @empty
        <div class="card-panel">
            <div class="card-body">
                <div class="empty-state">No sessions found. Add sessions first.</div>
            </div>
        </div>
    @endforelse
</div>

<h5 class="session-box-heading mt-4">Business Playlist</h5>
@forelse($playlist as $category)
    <section class="playlist-member-block">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h3 class="mb-1">{{ $category->name }}</h3>
                <p class="mb-0 text-muted">{{ $category->details ?: $category->videos_count.' video'.($category->videos_count === 1 ? '' : 's') }}</p>
            </div>
            <a href="{{ route('admin.playlist.show', $category) }}" class="btn btn-soft btn-sm">Open category</a>
        </div>
        @if($category->videos->isEmpty())
            <div class="card-panel mb-3">
                <div class="card-body">
                    <div class="empty-state">No videos in this category yet.</div>
                </div>
            </div>
        @else
            <div class="playlist-video-grid mb-4">
                @foreach($category->videos as $video)
                    <article class="playlist-video-card">
                        <a href="{{ $video->watchUrl() }}" class="playlist-thumb {{ $video->canEmbed() ? '' : 'playlist-thumb-channel' }}" target="_blank" rel="noopener">
                            @if($video->thumbnailUrl())
                                <img src="{{ $video->thumbnailUrl() }}" alt="{{ $video->title }}">
                            @endif
                            <span class="playlist-play"><i class="bi bi-play-fill"></i></span>
                        </a>
                        <div class="playlist-video-body">
                            <span class="badge-pill badge-staff mb-2">{{ $video->content_type }}</span>
                            <h3>{{ $video->title }}</h3>
                            <a href="{{ $video->watchUrl() }}" class="btn btn-soft btn-sm" target="_blank" rel="noopener">
                                <i class="bi bi-youtube"></i> YouTube
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@empty
    <div class="card-panel">
        <div class="card-body">
            <div class="empty-state">No Business Playlist categories yet. Add them from Business Playlist.</div>
        </div>
    </div>
@endforelse
@endsection
