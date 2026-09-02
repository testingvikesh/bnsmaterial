<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Auth') — {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}?v={{ @filemtime(public_path('css/admin.css')) ?: time() }}" rel="stylesheet">
</head>
<body>
<div class="auth-page">
    <section class="auth-hero">
        <div class="auth-brand">
            <div class="brand-mark">M</div>
            <div class="auth-brand-meta">
                <h1>{{ config('app.name') }}</h1>
                <small>Laravel 10 control panel</small>
            </div>
        </div>
        <p class="auth-tagline">Secure admin workspace</p>
        <h2>Manage users, roles and settings from one dashboard.</h2>
        <p>Sign in with your admin or staff account. Inactive users are blocked automatically.</p>
        <div class="role-pills">
            <span>Admin</span>
            <span>Staff</span>
            <span>Users</span>
            <span>Profile</span>
        </div>
    </section>
    <section class="auth-panel">
        <div class="auth-card">
            <div class="d-flex align-items-center gap-2 mb-3 d-lg-none">
                <div class="brand-mark" style="width:42px;height:42px;font-size:1.1rem">M</div>
                <strong>{{ config('app.name') }}</strong>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success mb-3">{{ session('success') }}</div>
            @endif
            @yield('content')
        </div>
    </section>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/password-toggle.js') }}?v={{ @filemtime(public_path('js/password-toggle.js')) ?: time() }}"></script>
</body>
</html>
