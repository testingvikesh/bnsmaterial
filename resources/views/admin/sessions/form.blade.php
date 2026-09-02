@extends('layouts.app')
@section('title', $sessionItem ? 'Edit Session' : 'Add Session')
@section('page_title', $sessionItem ? 'Edit Session' : 'Add Session')

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $sessionItem ? 'Edit Session' : 'Add Session' }}</h2>
        <p>Enter the session name and details.</p>
    </div>
    <a href="{{ route('admin.sessions.index') }}" class="btn btn-soft">Back</a>
</div>

<form method="POST" action="{{ $sessionItem ? route('admin.sessions.update', $sessionItem) : route('admin.sessions.store') }}">
    @csrf
    @if($sessionItem)
        @method('PUT')
    @endif

    <div class="card-panel mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $sessionItem?->name) }}" required maxlength="255">
                </div>
                <div class="col-12">
                    <label class="form-label">Details</label>
                    <textarea name="details" rows="16" class="form-control">{{ old('details', $sessionItem?->details) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <button class="btn btn-brand" type="submit">
        <i class="bi bi-check2"></i> {{ $sessionItem ? 'Save session' : 'Create session' }}
    </button>
</form>
@endsection
