@extends('layouts.app')
@section('title', 'Reporting')
@section('page_title', 'Reporting')

@php
    $reportQuery = array_filter([
        'q' => $q !== '' ? $q : null,
        'session_id' => $sessionId > 0 ? $sessionId : null,
        'group' => $group !== 'member' ? $group : null,
    ], fn ($value) => $value !== null && $value !== '');
@endphp

@section('content')
<div class="page-header">
    <div>
        <h2>Reporting</h2>
        <p>Filter by member or session, then click Show. Pending is selected by default.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.reports.index', $reportQuery + ['status' => 'pending']) }}" class="btn {{ $status === 'pending' ? 'btn-brand' : 'btn-soft' }}">
            <i class="bi bi-hourglass-split"></i> Pending
            <span class="material-filter-count">{{ $pendingCount }}</span>
        </a>
        <a href="{{ route('admin.reports.index', $reportQuery + ['status' => 'complete']) }}" class="btn {{ $status === 'complete' ? 'btn-brand' : 'btn-soft' }}">
            <i class="bi bi-check2-circle"></i> Complete
            <span class="material-filter-count">{{ $completeCount }}</span>
        </a>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="label">Members with material</div>
        <div class="value">{{ $stats['members'] }}</div>
        <div class="icon"><i class="bi bi-people"></i></div>
    </div>
    <div class="stat-card">
        <div class="label">Sessions</div>
        <div class="value">{{ $stats['sessions'] }}</div>
        <div class="icon"><i class="bi bi-journal-text"></i></div>
    </div>
    <div class="stat-card">
        <div class="label">Views / Clicks</div>
        <div class="value">{{ $stats['views'] }}</div>
        <div class="icon"><i class="bi bi-eye"></i></div>
    </div>
    <div class="stat-card">
        <div class="label">Reads</div>
        <div class="value">{{ $stats['reads'] }}</div>
        <div class="icon"><i class="bi bi-book"></i></div>
    </div>
</div>

<form method="GET" class="card-panel material-toolbar mb-3">
    <div class="card-body row g-2 align-items-end">
        <input type="hidden" name="status" value="{{ $status }}">
        <div class="col-md-4">
            <label class="form-label">Search member</label>
            <div class="search-box" style="max-width:none">
                <i class="bi bi-search"></i>
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Name / mobile / business">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label">Session</label>
            <select name="session_id" class="form-select">
                <option value="">All sessions</option>
                @foreach($sessions as $session)
                    <option value="{{ $session->id }}" @selected((int) $sessionId === (int) $session->id)>{{ $session->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">View</label>
            <select name="group" class="form-select">
                <option value="member" @selected($group === 'member')>Member wise</option>
                <option value="session" @selected($group === 'session')>Session wise</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-brand w-100" type="submit">Show</button>
        </div>
    </div>
</form>

<div class="card-panel mb-3">
    <div class="card-body">
        <h5 class="mb-3" id="sessionChartTitle">{{ $chart['title'] }}</h5>
        @if(($chart['labels'] ?? []) === [])
            <div class="empty-state">No session data yet for the graph.</div>
        @else
            <div style="position:relative;height:340px">
                <canvas id="sessionChart"></canvas>
            </div>
        @endif
    </div>
</div>

<div class="card-panel">
    <div class="card-body">
        @unless($filtered)
            <div class="empty-state">Select a session or search a member, then click Show.</div>
        @elseif($group === 'session')
            <div class="material-card-head">
                <div>
                    <div class="material-kicker">Session wise</div>
                    <h5 class="mb-0">{{ $status === 'complete' ? 'Complete members' : 'Pending members' }}</h5>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="btn btn-soft btn-sm pe-none">Pending <span class="material-filter-count">{{ $pendingCount }}</span></span>
                    <span class="btn btn-soft btn-sm pe-none">Complete <span class="material-filter-count">{{ $completeCount }}</span></span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Session</th>
                            <th>Member</th>
                            <th>Business</th>
                            <th>Status</th>
                            <th>Views / Clicks</th>
                            <th>Reads</th>
                            <th>Last viewed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessionGroups as $block)
                            @foreach($block['members'] as $index => $row)
                                <tr>
                                    @if($index === 0)
                                        <td rowspan="{{ $block['members']->count() }}">
                                            <strong>{{ $block['session'] }}</strong>
                                            <div class="text-muted small">{{ $block['views'] }} views · {{ $block['reads'] }} reads</div>
                                        </td>
                                    @endif
                                    <td><strong>{{ $row['member'] }}</strong></td>
                                    <td>{{ $row['business'] }}</td>
                                    <td>
                                        @if($row['complete'])
                                            <span class="badge-pill badge-active">Complete</span>
                                        @else
                                            <span class="badge-pill badge-admin">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $row['views'] }}</td>
                                    <td>{{ $row['reads'] }}</td>
                                    <td>{{ $row['last_viewed'] ? \Illuminate\Support\Carbon::parse($row['last_viewed'])->timezone(config('app.timezone'))->format('d M, h:i A') : '—' }}</td>
                                    <td>
                                        <a href="{{ $row['show_url'] }}" class="btn btn-soft btn-sm" target="_blank" rel="noopener">
                                            <i class="bi bi-eye"></i> Open
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr><td colspan="8"><div class="empty-state">No {{ $status }} members for this filter.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="material-card-head">
                <div>
                    <div class="material-kicker">Member wise</div>
                    <h5 class="mb-0">{{ $status === 'complete' ? 'Complete members' : 'Pending members' }}</h5>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="btn btn-soft btn-sm pe-none">Pending <span class="material-filter-count">{{ $pendingCount }}</span></span>
                    <span class="btn btn-soft btn-sm pe-none">Complete <span class="material-filter-count">{{ $completeCount }}</span></span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Business</th>
                            <th>Session</th>
                            <th>Status</th>
                            <th>Views / Clicks</th>
                            <th>Reads</th>
                            <th>Last viewed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($memberGroups as $block)
                            @foreach($block['sessions'] as $index => $row)
                                <tr>
                                    @if($index === 0)
                                        <td rowspan="{{ $block['sessions']->count() }}">
                                            <strong>{{ $block['member'] }}</strong>
                                            <div class="text-muted small">{{ $block['views'] }} views · {{ $block['reads'] }} reads</div>
                                        </td>
                                        <td rowspan="{{ $block['sessions']->count() }}">{{ $block['business'] }}</td>
                                    @endif
                                    <td>{{ $row['session'] }}</td>
                                    <td>
                                        @if($row['complete'])
                                            <span class="badge-pill badge-active">Complete</span>
                                        @else
                                            <span class="badge-pill badge-admin">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $row['views'] }}</td>
                                    <td>{{ $row['reads'] }}</td>
                                    <td>{{ $row['last_viewed'] ? \Illuminate\Support\Carbon::parse($row['last_viewed'])->timezone(config('app.timezone'))->format('d M, h:i A') : '—' }}</td>
                                    <td>
                                        <a href="{{ $row['show_url'] }}" class="btn btn-soft btn-sm" target="_blank" rel="noopener">
                                            <i class="bi bi-eye"></i> Open
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr><td colspan="8"><div class="empty-state">No {{ $status }} members for this filter.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const canvas = document.getElementById('sessionChart');
    if (!canvas || typeof Chart === 'undefined') return;
    const data = @json($chart, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: data.labels || [],
            datasets: [
                {
                    label: 'Pending',
                    data: data.pending || [],
                    backgroundColor: '#ff6b00',
                    borderRadius: 8,
                    maxBarThickness: 42
                },
                {
                    label: 'Complete',
                    data: data.complete || [],
                    backgroundColor: '#16a34a',
                    borderRadius: 8,
                    maxBarThickness: 42
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' },
                title: { display: false }
            },
            scales: {
                x: {
                    ticks: { maxRotation: 40, minRotation: 0, color: '#64748b' },
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, color: '#64748b' },
                    grid: { color: '#e8edf3' }
                }
            }
        }
    });
})();
</script>
@endpush
