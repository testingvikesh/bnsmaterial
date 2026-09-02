@extends('layouts.app')
@section('title', 'Session Details')
@section('page_title', 'Session Details')

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $sessionItem->name }}</h2>
        <p>Session details</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.prompts.create', ['session_id' => $sessionItem->id]) }}" class="btn btn-brand"><i class="bi bi-plus-lg"></i> Add Prompt</a>
        <a href="{{ route('admin.sessions.edit', $sessionItem) }}" class="btn btn-edit-soft"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('admin.sessions.index') }}" class="btn btn-soft">Back</a>
    </div>
</div>

<div class="card-panel" style="max-width:820px">
    <div class="card-body">
        <div class="mb-3">
            <div class="form-label text-muted">Name</div>
            <div><strong>{{ $sessionItem->name }}</strong></div>
        </div>
        <div>
            <div class="form-label text-muted">Details</div>
            <div>{!! $sessionItem->details ? nl2br(e($sessionItem->details)) : '—' !!}</div>
        </div>
    </div>
</div>

<div class="card-panel mt-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Session prompts</h5>
            <a href="{{ route('admin.prompts.index', ['session_id' => $sessionItem->id]) }}" class="btn btn-soft btn-sm">View all</a>
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th class="col-no">No.</th>
                        <th>Title</th>
                        <th>Prompt</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessionItem->prompts as $item)
                        <tr>
                            <td class="col-no">{{ $loop->iteration }}</td>
                            <td><strong>{{ $item->title }}</strong></td>
                            <td>{{ \Illuminate\Support\Str::limit($item->body, 70) }}</td>
                            <td>
                                <span class="badge-pill {{ $item->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.prompts.edit', $item) }}" class="btn btn-edit-soft btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="empty-state">No prompts for this session yet.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
