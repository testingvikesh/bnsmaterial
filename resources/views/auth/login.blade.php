@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <h3>Welcome back</h3>
    <p class="subtitle">Sign in to continue to the admin panel.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus placeholder="you@example.com">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="password-wrap">
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
                <button type="button" class="password-toggle" aria-label="Show password"><i class="bi bi-eye"></i></button>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <a href="{{ route('password.request') }}" class="small" style="color:var(--brand)">Forgot password?</a>
        </div>
        <button type="submit" class="btn btn-brand w-100 justify-content-center">
            <i class="bi bi-box-arrow-in-right"></i> Login
        </button>
    </form>
@endsection
