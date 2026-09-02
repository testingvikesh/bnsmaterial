@extends('layouts.app')
@section('title', $adminUser ? 'Edit User' : 'Add User')
@section('page_title', $adminUser ? 'Edit User' : 'Add User')

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $adminUser ? 'Edit User' : 'Add User' }}</h2>
        <p>Set login details, role and account status.</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-soft">Back</a>
</div>

<form method="POST" action="{{ $adminUser ? route('admin.users.update', $adminUser) : route('admin.users.store') }}">
    @csrf
    @if($adminUser)
        @method('PUT')
    @endif

    <div class="card-panel mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $adminUser?->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $adminUser?->email) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Mobile</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $adminUser?->phone) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ $adminUser ? 'New password (optional)' : 'Password *' }}</label>
                    <div class="password-wrap">
                        <input type="password" name="password" class="form-control" {{ $adminUser ? '' : 'required' }} autocomplete="new-password">
                        <button type="button" class="password-toggle" aria-label="Show password"><i class="bi bi-eye"></i></button>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Confirm password</label>
                    <div class="password-wrap">
                        <input type="password" name="password_confirmation" class="form-control" {{ $adminUser ? '' : 'required' }} autocomplete="new-password">
                        <button type="button" class="password-toggle" aria-label="Show password"><i class="bi bi-eye"></i></button>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Role *</label>
                    <select name="role" class="form-select" required>
                        <option value="admin" @selected(old('role', $adminUser?->role) === 'admin')>Admin</option>
                        <option value="staff" @selected(old('role', $adminUser?->role ?? 'staff') === 'staff')>Staff</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="active" @selected(old('status', $adminUser?->status ?? 'active') === 'active')>Active</option>
                        <option value="inactive" @selected(old('status', $adminUser?->status) === 'inactive')>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <button class="btn btn-brand" type="submit">
        <i class="bi bi-check2"></i> {{ $adminUser ? 'Save user' : 'Create user' }}
    </button>
</form>
@endsection
