@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
    <h3>Reset password</h3>
    <p class="subtitle">Choose a new password for your account.</p>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $email) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">New Password</label>
            <div class="password-wrap">
                <input type="password" name="password" class="form-control" required>
                <button type="button" class="password-toggle" aria-label="Show password"><i class="bi bi-eye"></i></button>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <div class="password-wrap">
                <input type="password" name="password_confirmation" class="form-control" required>
                <button type="button" class="password-toggle" aria-label="Show password"><i class="bi bi-eye"></i></button>
            </div>
        </div>
        <button type="submit" class="btn btn-brand w-100 justify-content-center">
            <i class="bi bi-shield-check"></i> Reset Password
        </button>
    </form>
@endsection
