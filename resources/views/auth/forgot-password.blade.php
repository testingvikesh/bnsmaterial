@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
    <h3>Forgot password</h3>
    <p class="subtitle">Enter your email and we’ll send a reset link.</p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
        </div>
        <button type="submit" class="btn btn-brand w-100 justify-content-center">
            <i class="bi bi-envelope"></i> Send Reset Link
        </button>
    </form>

    <p class="text-center mt-3 mb-0">
        <a href="{{ route('login') }}" class="small" style="color:var(--brand)">Back to login</a>
    </p>
@endsection
