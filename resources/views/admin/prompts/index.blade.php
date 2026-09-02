@extends('layouts.app')
@section('title', 'Session Prompts')
@section('page_title', 'Session Prompts')

@section('content')
<div class="page-header">
    <div>
        <h2>Session Prompts</h2>
        <p>Manage prompts session-wise. Only one prompt can stay active per session.</p>
    </div>
    <a href="{{ route('admin.prompts.create', array_filter(['session_id' => $sessionId])) }}" class="btn btn-brand">
        <i class="bi bi-plus-lg"></i> Add Prompt
    </a>
</div>

<div class="card-panel">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <select name="session_id" class="form-select">
                    <option value="">All sessions</option>
                    @foreach($sessions as $session)
                        <option value="{{ $session->id }}" @selected((int) $sessionId === (int) $session->id)>
                            {{ $session->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <div class="search-box" style="max-width:none">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search title / prompt / session">
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
                        <th>Session</th>
                        <th>Title</th>
                        <th>Prompt</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prompts as $item)
                        <tr>
                            <td class="col-no">{{ $prompts->firstItem() + $loop->index }}</td>
                            <td>
                                @if($item->session)
                                    <a href="{{ route('admin.sessions.show', $item->session) }}">
                                        {{ $item->session->name }}
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                            <td><strong>{{ $item->title }}</strong></td>
                            <td>{{ \Illuminate\Support\Str::limit($item->body, 70) }}</td>
                            <td>
                                <span class="badge-pill {{ $item->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.prompts.show', $item) }}" class="btn btn-soft btn-sm">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.prompts.edit', $item) }}" class="btn btn-edit-soft btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.prompts.destroy', $item) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger-soft btn-sm" type="submit" data-confirm="Delete this prompt?" data-confirm-title="Delete prompt?" data-confirm-btn="Yes, delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state">No prompts found. Click Add Prompt.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $prompts->links() }}
    </div>
</div>
@endsection
