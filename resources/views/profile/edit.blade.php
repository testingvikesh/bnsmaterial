@extends('layouts.app')

@section('title', 'Profile')
@section('page_title', 'My Profile')

@section('content')
<div class="page-header">
    <div>
        <h2>My Profile</h2>
        <p>Update your personal information.</p>
    </div>
</div>

<div class="card-panel" style="max-width:720px">
    <div class="card-body">
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <input type="text" class="form-control" value="{{ $user->roleLabel() }}" disabled>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-brand"><i class="bi bi-check2"></i> Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
