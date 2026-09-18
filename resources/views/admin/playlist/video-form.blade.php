@extends('layouts.app')
@section('title', $video ? 'Edit Video' : 'Add YouTube Video')
@section('page_title', $video ? 'Edit Video' : 'Add YouTube Video')

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $video ? 'Edit Video' : 'Add YouTube Video' }}</h2>
        <p>Category: <strong>{{ $category->name }}</strong></p>
    </div>
    <a href="{{ route('admin.playlist.show', $category) }}" class="btn btn-soft">Back</a>
</div>

<form method="POST" action="{{ $video ? route('admin.playlist.videos.update', $video) : route('admin.playlist.videos.store', $category) }}">
    @csrf
    @if($video)
        @method('PUT')
    @endif

    <div class="card-panel mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Video title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $video?->title) }}" required maxlength="255">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Type *</label>
                    <select name="content_type" class="form-select">
                        @foreach(\App\Models\PlaylistVideo::TYPES as $type)
                            <option value="{{ $type }}" @selected(old('content_type', $video?->content_type ?? 'Video') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">YouTube link *</label>
                    <input type="text" name="youtube_url" class="form-control" value="{{ old('youtube_url', $video?->youtube_url) }}" required maxlength="500" placeholder="https://www.youtube.com/watch?v=... or youtube.com/@channel">
                    <div class="form-text">Paste a YouTube video, Shorts, or channel link.</div>
                </div>
            </div>
        </div>
    </div>

    <button class="btn btn-brand" type="submit">
        <i class="bi bi-check2"></i> {{ $video ? 'Save video' : 'Add video' }}
    </button>
</form>
@endsection
