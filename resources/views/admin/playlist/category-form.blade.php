@extends('layouts.app')
@section('title', $category ? 'Edit Category' : 'Add Category')
@section('page_title', $category ? 'Edit Category' : 'Add Category')

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $category ? 'Edit Category' : 'Add Category' }}</h2>
        <p>Business Playlist categories group YouTube videos for members.</p>
    </div>
    <a href="{{ route('admin.playlist.index') }}" class="btn btn-soft">Back</a>
</div>

<form method="POST" action="{{ $category ? route('admin.playlist.categories.update', $category) : route('admin.playlist.categories.store') }}">
    @csrf
    @if($category)
        @method('PUT')
    @endif

    <div class="card-panel mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Category name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $category?->name) }}" required maxlength="255">
                </div>
                <div class="col-12">
                    <label class="form-label">Details</label>
                    <textarea name="details" rows="4" class="form-control">{{ old('details', $category?->details) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <button class="btn btn-brand" type="submit">
        <i class="bi bi-check2"></i> {{ $category ? 'Save category' : 'Create category' }}
    </button>
</form>
@endsection
