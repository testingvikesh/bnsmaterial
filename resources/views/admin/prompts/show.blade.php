@extends('layouts.app')
@section('title', 'Prompt Details')
@section('page_title', 'Prompt Details')

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $promptItem->title }}</h2>
        <p>
            Session
            @if($promptItem->session)
                <a href="{{ route('admin.sessions.show', $promptItem->session) }}">{{ $promptItem->session->name }}</a>
            @else
                —
            @endif
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.prompts.edit', $promptItem) }}" class="btn btn-edit-soft"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('admin.prompts.index', ['session_id' => $promptItem->manage_session_id]) }}" class="btn btn-soft">Back</a>
    </div>
</div>

<div class="card-panel">
    <div class="card-body">
        <div class="mb-3">
            <div class="form-label text-muted">Status</div>
            <span class="badge-pill {{ $promptItem->is_active ? 'badge-active' : 'badge-inactive' }}">
                {{ $promptItem->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <div>
            <div class="form-label text-muted">Prompt</div>
            <pre class="mb-0" style="white-space:pre-wrap;font-family:inherit">{{ $promptItem->body }}</pre>
        </div>
    </div>
</div>
@endsection
