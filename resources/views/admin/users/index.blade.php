@extends('layouts.app')
@section('title', 'Users')
@section('page_title', 'Users')

@section('content')
<div class="page-header">
    <div>
        <h2>Users</h2>
        <p>Create admin and staff accounts, then set their status.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-brand"><i class="bi bi-plus-lg"></i> Add User</a>
</div>

<div class="card-panel">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-5">
                <div class="search-box" style="max-width:none">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search name / email / mobile">
                </div>
            </div>
            <div class="col-md-2">
                <select name="role" class="form-select">
                    <option value="">All roles</option>
                    <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                    <option value="staff" @selected(request('role') === 'staff')>Staff</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All status</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-brand w-100" type="submit">Search</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $item)
                        <tr>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->phone ?: '—' }}</td>
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
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.users.edit', $item) }}" class="btn btn-edit-soft btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $item) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger-soft btn-sm" type="submit" data-confirm="Delete this user?" data-confirm-title="Delete user?" data-confirm-btn="Yes, delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state">No users found. Click Add User.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    </div>
</div>
@endsection
