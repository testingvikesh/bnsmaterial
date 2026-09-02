@extends('layouts.app')
@section('title', 'Member List')
@section('page_title', 'Member List')

@section('content')
<div class="page-header">
    <div>
        <h2>Member List</h2>
        <p>Members from users joined with member profiles.</p>
    </div>
</div>

<div class="card-panel">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-6">
                <div class="search-box" style="max-width:none">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search name / mobile">
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
                        <th>Member Name</th>
                        <th>Type</th>
                        <th>Mobile No</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $item)
                        <tr>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>
                                @if($item->isGuest())
                                    <span class="badge-pill badge-staff">Guest</span>
                                @else
                                    <span class="badge-pill badge-active">Member</span>
                                @endif
                            </td>
                            <td>{{ $item->phone ?: ($item->whatsapp ?: '—') }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.members.show', $item->id) }}" class="btn btn-soft btn-sm">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="empty-state">No members found.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $members->links() }}
    </div>
</div>
@endsection
