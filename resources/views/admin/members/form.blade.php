@extends('layouts.app')
@section('title', $memberItem ? 'Edit Member' : 'Add Member')
@section('page_title', $memberItem ? 'Edit Member' : 'Add Member')

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $memberItem ? 'Edit Member' : 'Add Member' }}</h2>
        <p>Complete member profile. Member Id is auto-generated if left blank.</p>
    </div>
    <a href="{{ route('admin.members.index') }}" class="btn btn-soft">Back</a>
</div>

<form method="POST" action="{{ $memberItem ? route('admin.members.update', $memberItem) : route('admin.members.store') }}">
    @csrf
    @if($memberItem)
        @method('PUT')
    @endif

    @foreach($groups as $group => $fields)
        <div class="card-panel mb-3">
            <div class="card-body">
                <h5 class="mb-3">{{ $group }}</h5>
                <div class="row g-3">
                    @foreach($fields as $name => $meta)
                        @php $type = $meta['type'] ?? 'text'; @endphp
                        <div class="col-md-{{ $type === 'textarea' ? '12' : '6' }}">
                            <label class="form-label">{{ $meta['label'] }}{{ $name === 'name' || $name === 'status' ? ' *' : '' }}</label>
                            @if($type === 'textarea')
                                <textarea name="{{ $name }}" rows="4" class="form-control">{{ old($name, $memberItem?->{$name}) }}</textarea>
                            @elseif($type === 'select')
                                <select name="{{ $name }}" class="form-select" @required($name === 'status')>
                                    @if($name !== 'status')
                                        <option value="">Select</option>
                                    @endif
                                    @foreach($meta['options'] as $value => $label)
                                        <option value="{{ $value }}" @selected((string) old($name, $memberItem?->{$name} ?? ($name === 'status' ? 'active' : '')) === (string) $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            @elseif($type === 'number')
                                <input type="number" name="{{ $name }}" class="form-control" value="{{ old($name, $memberItem?->{$name}) }}" min="1" max="120">
                            @elseif($type === 'email')
                                <input type="email" name="{{ $name }}" class="form-control" value="{{ old($name, $memberItem?->{$name}) }}">
                            @else
                                <input type="text" name="{{ $name }}" class="form-control" value="{{ old($name, $memberItem?->{$name}) }}" @required($name === 'name') @if($name === 'member_id') placeholder="Auto if empty" @endif>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    <button class="btn btn-brand" type="submit">
        <i class="bi bi-check2"></i> {{ $memberItem ? 'Save member' : 'Create member' }}
    </button>
</form>
@endsection
