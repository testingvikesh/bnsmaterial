@extends('layouts.app')
@section('title', $member->name)
@section('page_title', $member->name)

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $member->name }}</h2>
        <p>
            {{ $member->mobileNumber() }}
            @if($member->memberProfile?->business_name)
                · {{ $member->memberProfile->business_name }}
            @endif
        </p>
    </div>
    <a href="{{ route('admin.members.index') }}" class="btn btn-soft">Back</a>
</div>

<div class="member-meta-grid">
    <div class="member-meta-item">
        <span>Business Name</span>
        <strong>{{ $member->memberProfile?->business_name ?: '—' }}</strong>
    </div>
    <div class="member-meta-item">
        <span>Business Category</span>
        <strong>{{ $member->memberProfile?->business_category ?: '—' }}</strong>
    </div>
    <div class="member-meta-item">
        <span>Business Introduction</span>
        <strong>{{ $member->memberProfile?->business_description ?: '—' }}</strong>
    </div>
    <div class="member-meta-item">
        <span>Business Main Product</span>
        <strong>{{ $member->memberProfile?->main_products_services ?: '—' }}</strong>
    </div>
</div>

<h5 class="session-box-heading">Sessions</h5>
<div class="session-box-grid">
    @forelse($sessions as $index => $session)
        <article class="session-box">
            <div class="session-box-top">
                <span class="session-box-no">Session {{ $index + 1 }}</span>
                <span class="badge-pill badge-staff">{{ $session->prompts_count }} prompt{{ $session->prompts_count === 1 ? '' : 's' }}</span>
            </div>
            <h3>{{ $session->name }}</h3>
            <p>{{ $session->details ? \Illuminate\Support\Str::limit($session->details, 140) : 'No session details.' }}</p>
            <div class="session-box-foot">
                <span>{{ $session->activePrompt?->title ?: 'No active prompt' }}</span>
            </div>
        </article>
    @empty
        <div class="card-panel">
            <div class="card-body">
                <div class="empty-state">No sessions found. Add sessions first.</div>
            </div>
        </div>
    @endforelse
</div>
@endsection
