<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}?v={{ @filemtime(public_path('css/admin.css')) ?: time() }}" rel="stylesheet">
    @stack('styles')
</head>
@php $authUser = auth()->user(); @endphp
<body>
<div class="overlay" id="sidebarOverlay"></div>
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo">M</div>
            <div>
                <h1>{{ config('app.name') }}</h1>
                <small>{{ $authUser->roleLabel() }} Panel</small>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-label">Main</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>

            <div class="nav-label">Manage</div>
            <a href="{{ route('admin.sessions.index') }}" class="nav-link {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> Session Management
            </a>
            <a href="{{ route('admin.prompts.index') }}" class="nav-link {{ request()->routeIs('admin.prompts.*') ? 'active' : '' }}">
                <i class="bi bi-chat-square-text"></i> Session Prompts
            </a>
            <a href="{{ route('admin.members.index') }}" class="nav-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Member List
            </a>
            <a href="{{ route('admin.material.index') }}" class="nav-link {{ request()->routeIs('admin.material.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-code"></i> Material
            </a>
            @if($authUser->isAdmin())
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i> Users
                </a>
            @endif

            <div class="nav-label">Account</div>
            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-person"></i> Profile
            </a>
            <a href="{{ route('password.change') }}" class="nav-link {{ request()->routeIs('password.change*') ? 'active' : '' }}">
                <i class="bi bi-shield-lock"></i> Change Password
            </a>
            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="nav-link w-100 border-0 bg-transparent text-start" data-confirm="You will be signed out of the admin panel." data-confirm-title="Logout?" data-confirm-btn="Yes, logout" data-confirm-icon="question">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </nav>
    </aside>

    <div class="main-wrap">
        <header class="topbar">
            <div class="d-flex align-items-center gap-2">
                <button class="sidebar-toggle" id="sidebarToggle" type="button"><i class="bi bi-list"></i></button>
                <div>
                    <strong>@yield('page_title', 'Dashboard')</strong>
                </div>
            </div>
            <div class="dropdown">
                <button class="btn btn-soft dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                    <span class="avatar">{{ strtoupper(substr($authUser->name, 0, 1)) }}</span>
                    <span class="d-none d-sm-inline">{{ $authUser->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('password.change') }}"><i class="bi bi-key me-2"></i>Change Password</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger" data-confirm="You will be signed out of the admin panel." data-confirm-title="Logout?" data-confirm-btn="Yes, logout" data-confirm-icon="question">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <main class="page-content">
            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@if(session('success'))
    <div id="flashToast" data-message="{{ session('success') }}" data-type="success"></div>
@endif
@if(session('error'))
    <div id="flashToast" data-message="{{ session('error') }}" data-type="error"></div>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/admin.js') }}?v={{ @filemtime(public_path('js/admin.js')) ?: time() }}"></script>
<script src="{{ asset('js/password-toggle.js') }}?v={{ @filemtime(public_path('js/password-toggle.js')) ?: time() }}"></script>
@stack('scripts')
</body>
</html>
