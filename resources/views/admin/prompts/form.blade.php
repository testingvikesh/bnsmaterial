@extends('layouts.app')
@section('title', $promptItem ? 'Edit Prompt' : 'Add Prompt')
@section('page_title', $promptItem ? 'Edit Prompt' : 'Add Prompt')

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $promptItem ? 'Edit Prompt' : 'Add Prompt' }}</h2>
        <p>Save a prompt against a session. Tick Active to use it as the live prompt for that session.</p>
    </div>
    <a href="{{ route('admin.prompts.index', array_filter(['session_id' => $selectedSessionId])) }}" class="btn btn-soft">Back</a>
</div>

@if($sessions->isEmpty())
    <div class="alert alert-danger">Create a session first, then add prompts.</div>
    <a href="{{ route('admin.sessions.create') }}" class="btn btn-brand">Add Session</a>
@else
<form method="POST" action="{{ $promptItem ? route('admin.prompts.update', $promptItem) : route('admin.prompts.store') }}">
    @csrf
    @if($promptItem)
        @method('PUT')
    @endif

    <div class="card-panel mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Session *</label>
                    <select name="manage_session_id" class="form-select" required>
                        <option value="">Select session</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}" @selected((int) old('manage_session_id', $selectedSessionId) === (int) $session->id)>
                                {{ $session->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $promptItem?->title) }}" required maxlength="255">
                </div>
                <div class="col-12">
                    <label class="form-label">Prompt *</label>
                    <textarea name="body" rows="14" class="form-control" required placeholder="Write the session prompt…">{{ old('body', $promptItem?->body) }}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $promptItem?->is_active))>
                        <label class="form-check-label" for="is_active">Active prompt for this session</label>
                    </div>
                    <div class="small text-muted mt-1">Saving as active turns off other prompts in the same session.</div>
                </div>
            </div>
        </div>
    </div>

    <button class="btn btn-brand" type="submit">
        <i class="bi bi-check2"></i> {{ $promptItem ? 'Save prompt' : 'Create prompt' }}
    </button>
</form>
@endif
@endsection
