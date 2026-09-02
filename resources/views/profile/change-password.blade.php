@extends('layouts.app')

@section('title', 'Change Password')
@section('page_title', 'Change Password')

@section('content')
<div class="page-header">
    <div>
        <h2>Change Password</h2>
        <p>Keep your account secure with a strong password.</p>
    </div>
</div>

<div class="card-panel" style="max-width:560px">
    <div class="card-body">
        <form method="POST" action="{{ route('password.change.update') }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Current Password</label>
                <div class="password-wrap">
                    <input type="password" name="current_password" class="form-control" required>
                    <button type="button" class="password-toggle" aria-label="Show password"><i class="bi bi-eye"></i></button>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <div class="password-wrap">
                    <input type="password" name="password" class="form-control" required>
                    <button type="button" class="password-toggle" aria-label="Show password"><i class="bi bi-eye"></i></button>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <div class="password-wrap">
                    <input type="password" name="password_confirmation" class="form-control" required>
                    <button type="button" class="password-toggle" aria-label="Show password"><i class="bi bi-eye"></i></button>
                </div>
            </div>
            <button type="submit" class="btn btn-brand" data-confirm="Update your password now?" data-confirm-title="Change Password" data-confirm-btn="Yes, update" data-confirm-icon="question">
                <i class="bi bi-shield-lock"></i> Update Password
            </button>
        </form>
    </div>
</div>
@endsection
