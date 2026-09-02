@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h2>Dashboard</h2>
        <p>Overview of users and recent activity.</p>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="label">Total users</div>
        <div class="value">{{ $stats['users'] }}</div>
        <div class="icon"><i class="bi bi-people"></i></div>
    </div>
    <div class="stat-card">
        <div class="label">Members</div>
        <div class="value">{{ $stats['members'] }}</div>
        <div class="icon"><i class="bi bi-people"></i></div>
    </div>
    <div class="stat-card">
        <div class="label">Prompts</div>
        <div class="value">{{ $stats['prompts'] }}</div>
        <div class="icon"><i class="bi bi-chat-square-text"></i></div>
    </div>
    <div class="stat-card">
        <div class="label">Sessions</div>
        <div class="value">{{ $stats['sessions'] }}</div>
        <div class="icon"><i class="bi bi-journal-text"></i></div>
    </div>
</div>

<div class="card-panel">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Recent users</h5>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.users.index') }}" class="btn btn-soft btn-sm">View all</a>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers as $item)
                        <tr>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>{{ $item->email }}</td>
                            <td>
                                <span class="badge-pill {{ $item->isAdmin() ? 'badge-admin' : 'badge-staff' }}">
                                    {{ $item->roleLabel() }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-pill {{ $item->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td>{{ $item->created_at?->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="empty-state">No users yet.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card-panel mt-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Recent sessions</h5>
            <a href="{{ route('admin.sessions.index') }}" class="btn btn-soft btn-sm">View all</a>
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSessions as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td><a href="{{ route('admin.sessions.show', $item) }}"><strong>{{ $item->name }}</strong></a></td>
                            <td>{{ $item->details ? \Illuminate\Support\Str::limit($item->details, 80) : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3"><div class="empty-state">No sessions yet.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card-panel mt-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Recent members</h5>
            <a href="{{ route('admin.members.index') }}" class="btn btn-soft btn-sm">View all</a>
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Member Name</th>
                        <th>Mobile No</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentMembers as $item)
                        <tr>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>{{ $item->mobileNumber() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2"><div class="empty-state">No members yet.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
