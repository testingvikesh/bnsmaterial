@extends('layouts.app')
@section('title', 'Session Management')
@section('page_title', 'Session Management')

@section('content')
<div class="page-header">
    <div>
        <h2>Session Management</h2>
        <p>Add and maintain sessions with name and details.</p>
    </div>
    <a href="{{ route('admin.sessions.create') }}" class="btn btn-brand"><i class="bi bi-plus-lg"></i> Add Session</a>
</div>

<div class="card-panel">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-6">
                <div class="search-box" style="max-width:none">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search name / details">
                </div>
            </div>
            <div class="col-md-2">
                <button class="btn btn-brand w-100" type="submit">Search</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th class="col-no">No.</th>
                        <th>Name</th>
                        <th>Details</th>
                        <th>Prompts</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $item)
                        <tr>
                            <td class="col-no">{{ $sessions->firstItem() + $loop->index }}</td>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>{{ $item->details ? \Illuminate\Support\Str::limit($item->details, 80) : '—' }}</td>
                            <td>
                                <a href="{{ route('admin.prompts.index', ['session_id' => $item->id]) }}">
                                    {{ $item->prompts_count }}
                                </a>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.prompts.index', ['session_id' => $item->id]) }}" class="btn btn-soft btn-sm">
                                        <i class="bi bi-chat-square-text"></i> Prompts
                                    </a>
                                    <a href="{{ route('admin.sessions.show', $item) }}" class="btn btn-soft btn-sm">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.sessions.edit', $item) }}" class="btn btn-edit-soft btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.sessions.destroy', $item) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger-soft btn-sm" type="submit" data-confirm="Delete this session?" data-confirm-title="Delete session?" data-confirm-btn="Yes, delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="empty-state">No sessions found. Click Add Session.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $sessions->links() }}
    </div>
</div>
@endsection
