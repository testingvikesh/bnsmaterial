@extends('layouts.app')
@section('title', 'Material')
@section('page_title', 'Material')

@section('content')
<div class="material-page">
    @if($session)
    <div class="stat-grid">
        <div class="stat-card">
            <div class="label">Members</div>
            <div class="value">{{ $memberTotal }}</div>
            <div class="icon"><i class="bi bi-people"></i></div>
        </div>
        <div class="stat-card">
            <div class="label">Generated</div>
            <div class="value">{{ $generatedCount }}</div>
            <div class="icon"><i class="bi bi-check2-circle"></i></div>
        </div>
        <div class="stat-card">
            <div class="label">Pending</div>
            <div class="value">{{ $pendingCount }}</div>
            <div class="icon"><i class="bi bi-hourglass-split"></i></div>
        </div>
        <div class="stat-card">
            <div class="label">Active session</div>
            <div class="value material-stat-session">{{ $session->name }}</div>
            <div class="icon"><i class="bi bi-journal-richtext"></i></div>
        </div>
    </div>
    @endif

    <form method="GET" class="card-panel material-toolbar">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-5">
                    <label class="form-label">Session</label>
                    <select name="session_id" id="materialSessionSelect" class="form-select" autocomplete="off" required>
                        @if($sessions->isEmpty())
                            <option value="" disabled selected>No sessions found</option>
                        @else
                            <option value="">Select session</option>
                            @foreach($sessions as $item)
                                <option value="{{ $item->id }}" @selected((string) $sessionId === (string) $item->id)>
                                    {{ $item->id }} — {{ $item->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-lg-5">
                    <label class="form-label">Search member</label>
                    <div class="search-box" style="max-width:none">
                        <i class="bi bi-search"></i>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search name / mobile / business">
                    </div>
                </div>
                <div class="col-lg-2">
                    @if($status)
                        <input type="hidden" name="status" value="{{ $status }}">
                    @endif
                    <button class="btn btn-brand w-100" type="submit">
                        <i class="bi bi-funnel"></i> Search
                    </button>
                </div>
            </div>
        </div>
    </form>

    @unless($session)
        <div class="card-panel">
            <div class="card-body">
                <div class="empty-state">Select a session and click Search to load the prompt and member list.</div>
            </div>
        </div>
    @endunless

    @if($session)
    <div class="material-workspace">
        <section class="card-panel material-list-card">
            <div class="card-body">
                <div class="material-card-head">
                    <div>
                        <div class="material-kicker">Members</div>
                        <h5 class="mb-0">Member List</h5>
                    </div>
                    <div class="material-bulk-bar">
                        @php
                            $materialQuery = array_filter([
                                'session_id' => $session->id,
                                'q' => request('q') ?: null,
                            ], fn ($value) => $value !== null && $value !== '');
                        @endphp
                        <a href="{{ route('admin.material.index', $status === 'generated' ? $materialQuery : $materialQuery + ['status' => 'generated']) }}" class="btn btn-sm {{ $status === 'generated' ? 'btn-brand' : 'btn-soft' }}">
                            <i class="bi bi-check2-circle"></i> Generate
                            <span class="material-filter-count">{{ $generatedCount }}</span>
                        </a>
                        <a href="{{ route('admin.material.index', $status === 'pending' ? $materialQuery : $materialQuery + ['status' => 'pending']) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-brand' : 'btn-soft' }}">
                            <i class="bi bi-hourglass-split"></i> Not Generate
                            <span class="material-filter-count">{{ $pendingCount }}</span>
                        </a>
                        <button class="btn btn-soft btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#materialPromptModal">
                            <i class="bi bi-chat-square-text"></i> Prompt
                        </button>
                        @if($isReverse)
                            <a href="{{ route('admin.material.dashboard', ['session_id' => $session->id]) }}" class="btn btn-sm material-dashboard-btn">
                                <i class="bi bi-bar-chart-line"></i> Dashboard
                            </a>
                        @endif
                        <span class="material-count">{{ $members->total() }} shown</span>
                        @if($prompt && $session && $members->isNotEmpty())
                            <form method="POST" action="{{ route('admin.material.generate') }}" id="materialBulkForm" class="material-bulk-form">
                                @csrf
                                <input type="hidden" name="manage_session_id" value="{{ $session->id }}">
                                @if(request('q'))
                                    <input type="hidden" name="q" value="{{ request('q') }}">
                                @endif
                                @if($status)
                                    <input type="hidden" name="status" value="{{ $status }}">
                                @endif
                                <span class="material-selected-count" id="materialSelectedCount">0 selected</span>
                                <button class="btn btn-brand btn-sm" type="submit" id="materialBulkGenerate" disabled>
                                    <i class="bi bi-files"></i> Generate selected
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div id="materialJobPanel" class="material-job-panel" hidden>
                    <div class="material-job-head">
                        <div>
                            <div class="material-kicker">Parallel generate</div>
                            <strong id="materialJobTitle">Generating members</strong>
                        </div>
                        <span id="materialJobSummary">0 / 0</span>
                    </div>
                    <div class="progress material-job-progress">
                        <div class="progress-bar" id="materialJobBar" style="width:0%"></div>
                    </div>
                    <ul id="materialJobList" class="material-job-list"></ul>
                </div>

                <div class="table-responsive table-sticky-wrap">
                    <table class="table-modern material-table">
                        <thead>
                            <tr>
                                <th class="col-check">
                                    @if($prompt && $session && $members->isNotEmpty())
                                        <input type="checkbox" class="material-check" id="materialSelectAll" title="Select all on this page" aria-label="Select all members on this page">
                                    @endif
                                </th>
                                <th>Member Name</th>
                                <th>Type</th>
                                <th>Mobile No</th>
                                <th>Business</th>
                                <th>Status</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $item)
                                @php $file = $files->get($item->id); @endphp
                                <tr data-user-id="{{ $item->id }}" data-user-name="{{ $item->name }}">
                                    <td class="col-check">
                                        @if($prompt && $session)
                                            <input type="checkbox" class="material-check material-member-check" form="materialBulkForm" name="user_ids[]" value="{{ $item->id }}" aria-label="Select {{ $item->name }}">
                                        @endif
                                    </td>
                                    <td>
                                        <div class="material-member">
                                            <span class="material-avatar">{{ strtoupper(substr($item->name, 0, 1)) }}</span>
                                            <div>
                                                <strong>{{ $item->name }}</strong>
                                                @if($item->business_category)
                                                    <small>{{ $item->business_category }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->isGuest())
                                            <span class="badge-pill badge-staff">Guest</span>
                                        @else
                                            <span class="badge-pill badge-active">Member</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->phone ?: ($item->whatsapp ?: '—') }}</td>
                                    <td>{{ $item->business_name ?: '—' }}</td>
                                    <td class="material-status-cell">
                                        @if($file)
                                            <span class="badge-pill badge-active">Generated</span>
                                        @else
                                            <span class="badge-pill badge-inactive">Pending</span>
                                        @endif
                                    </td>
                                    <td class="material-time-cell">
                                        <span class="material-time">{{ $file?->durationLabel() ?: '—' }}</span>
                                    </td>
                                    <td class="material-action-cell">
                                        <div class="table-actions">
                                            @if($prompt && $session)
                                                <form method="POST" action="{{ route('admin.material.generate') }}" class="material-generate-form">
                                                    @csrf
                                                    <input type="hidden" name="manage_session_id" value="{{ $session->id }}">
                                                    <input type="hidden" name="user_id" value="{{ $item->id }}">
                                                    <input type="hidden" name="force" value="{{ $file ? '1' : '0' }}">
                                                    @if(request('q'))
                                                        <input type="hidden" name="q" value="{{ request('q') }}">
                                                    @endif
                                                    @if($status)
                                                        <input type="hidden" name="status" value="{{ $status }}">
                                                    @endif
                                                    <button class="btn btn-brand btn-sm material-row-generate" type="submit">
                                                        <i class="bi bi-file-earmark-code"></i>
                                                        {{ $file ? 'Regenerate' : 'Generate' }}
                                                    </button>
                                                </form>
                                            @endif
                                            <span class="material-file-actions">
                                                @if($file)
                                                    <a href="{{ route('admin.material.show', $file) }}" class="btn btn-soft btn-sm material-view-link" target="_blank" rel="noopener">
                                                        <i class="bi bi-eye"></i> View
                                                    </a>
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8"><div class="empty-state">No members found.</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="material-pager">
                    {{ $members->links() }}
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="materialPromptModal" tabindex="-1" aria-labelledby="materialPromptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content member-modal">
                <div class="modal-header">
                    <div>
                        <div class="material-kicker">Ready prompt</div>
                        <h5 class="modal-title" id="materialPromptModalLabel">Session Prompt</h5>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($prompt)
                            <span class="badge-pill badge-active">Active</span>
                        @endif
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    @if($prompt)
                        <div class="material-meta">
                            <div>
                                <span>Session</span>
                                <strong>{{ $session->name }}</strong>
                            </div>
                            <div>
                                <span>Title</span>
                                <strong>{{ $prompt->title }}</strong>
                            </div>
                        </div>
                        <div class="form-label text-muted mb-1">Prompt</div>
                        <div class="material-prompt-box">{{ $prompt->body }}</div>
                    @else
                        <div class="empty-state">No active prompt for this session. Activate a prompt first.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@if($session)
@push('scripts')
<script>
(function () {
    const generateUrl = @json(route('admin.material.generate'));
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const sessionId = @json($session?->id);
    const searchQ = @json(request('q'));
    const concurrency = 3;

    const form = document.getElementById('materialBulkForm');
    const selectAll = document.getElementById('materialSelectAll');
    const checks = Array.from(document.querySelectorAll('.material-member-check'));
    const countEl = document.getElementById('materialSelectedCount');
    const button = document.getElementById('materialBulkGenerate');
    const panel = document.getElementById('materialJobPanel');
    const jobList = document.getElementById('materialJobList');
    const jobSummary = document.getElementById('materialJobSummary');
    const jobBar = document.getElementById('materialJobBar');
    const jobTitle = document.getElementById('materialJobTitle');

    function selected() {
        return checks.filter((box) => box.checked);
    }

    function sync() {
        const n = selected().length;
        if (countEl) countEl.textContent = n + ' selected';
        if (button && !button.dataset.busy) button.disabled = n === 0;
        if (selectAll) {
            selectAll.checked = checks.length > 0 && n === checks.length;
            selectAll.indeterminate = n > 0 && n < checks.length;
        }
    }

    function formatDuration(ms) {
        const n = Number(ms) || 0;
        if (n < 1000) return n + ' ms';
        const seconds = n / 1000;
        if (seconds < 60) return seconds.toFixed(1) + ' s';
        const total = Math.round(seconds);
        return Math.floor(total / 60) + 'm ' + (total % 60) + 's';
    }

    function rowFor(userId) {
        return document.querySelector('tr[data-user-id="' + userId + '"]');
    }

    function setRowBusy(userId, busy) {
        const row = rowFor(userId);
        if (!row) return;
        const status = row.querySelector('.material-status-cell');
        const time = row.querySelector('.material-time');
        const btn = row.querySelector('.material-row-generate');
        if (busy) {
            if (status) status.innerHTML = '<span class="badge-pill badge-inactive"><span class="spinner-border spinner-border-sm"></span> Generating</span>';
            if (time) time.textContent = '0.0 s';
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Generating';
            }
        }
    }

    function setRowResult(userId, payload) {
        const row = rowFor(userId);
        if (!row) return;
        const status = row.querySelector('.material-status-cell');
        const time = row.querySelector('.material-time');
        const btn = row.querySelector('.material-row-generate');
        const actions = row.querySelector('.material-file-actions');
        if (status) {
            status.innerHTML = payload.cached
                ? '<span class="badge-pill badge-active">Cached</span>'
                : '<span class="badge-pill badge-active">Generated</span>';
        }
        if (time) time.textContent = payload.duration_label || formatDuration(payload.duration_ms);
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-file-earmark-code"></i> Regenerate';
            const forceInput = row.querySelector('.material-generate-form [name="force"]');
            if (forceInput) forceInput.value = '1';
        }
        if (actions && payload.show_url) {
            actions.innerHTML =
                '<a href="' + payload.show_url + '" class="btn btn-soft btn-sm material-view-link" target="_blank" rel="noopener"><i class="bi bi-eye"></i> View</a>';
        }
    }

    function setRowError(userId, message) {
        const row = rowFor(userId);
        if (!row) return;
        const status = row.querySelector('.material-status-cell');
        const btn = row.querySelector('.material-row-generate');
        if (status) status.innerHTML = '<span class="badge-pill badge-inactive" title="' + String(message || 'Failed') + '">Failed</span>';
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-file-earmark-code"></i> Generate';
        }
    }

    async function generateOne(userId, onTick, force) {
        const started = Date.now();
        const timer = setInterval(function () {
            if (typeof onTick === 'function') onTick(Date.now() - started);
        }, 250);
        try {
            const body = new URLSearchParams();
            body.set('_token', csrf);
            body.set('manage_session_id', String(sessionId || ''));
            body.set('user_id', String(userId));
            body.set('force', force ? '1' : '0');
            if (searchQ) body.set('q', searchQ);

            const response = await fetch(generateUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf
                },
                body
            });
            const payload = await response.json().catch(() => ({}));
            if (!response.ok || payload.ok === false) {
                throw new Error(payload.message || 'Generate failed');
            }
            payload.duration_ms = payload.duration_ms || (Date.now() - started);
            payload.duration_label = payload.duration_label || formatDuration(payload.duration_ms);
            return payload;
        } finally {
            clearInterval(timer);
        }
    }

    async function runPool(items, limit, worker) {
        const queue = items.slice();
        const runners = [];
        const n = Math.max(1, Math.min(limit, queue.length || 1));
        for (let i = 0; i < n; i++) {
            runners.push((async function () {
                while (queue.length) {
                    const item = queue.shift();
                    await worker(item);
                }
            })());
        }
        await Promise.all(runners);
    }

    function showPanel(jobs) {
        if (!panel || !jobList) return;
        panel.hidden = false;
        jobList.innerHTML = jobs.map(function (job) {
            return '<li data-job-user="' + job.id + '"><span class="material-job-name">' + job.name + '</span><span class="material-job-state">Queued</span><span class="material-job-time">—</span></li>';
        }).join('');
        updatePanel(jobs);
    }

    function updatePanel(jobs) {
        if (!jobSummary || !jobBar || !jobTitle) return;
        const done = jobs.filter((j) => j.status === 'done' || j.status === 'failed').length;
        const running = jobs.filter((j) => j.status === 'running').length;
        jobSummary.textContent = done + ' / ' + jobs.length + ' · ' + running + ' running';
        jobTitle.textContent = done === jobs.length ? 'Generate complete' : 'Parallel generate · ' + concurrency + ' at a time';
        jobBar.style.width = jobs.length ? Math.round((done / jobs.length) * 100) + '%' : '0%';
        jobs.forEach(function (job) {
            const li = jobList?.querySelector('[data-job-user="' + job.id + '"]');
            if (!li) return;
            const state = li.querySelector('.material-job-state');
            const time = li.querySelector('.material-job-time');
            if (state) {
                state.textContent = job.status === 'running'
                    ? 'Generating'
                    : (job.status === 'done'
                        ? (job.cached ? 'Cached' : 'Done')
                        : (job.status === 'failed' ? 'Failed' : 'Queued'));
            }
            if (time) time.textContent = job.timeLabel || '—';
            li.className = 'is-' + job.status;
        });
    }

    async function generateMembers(userIds, force) {
        const jobs = userIds.map(function (id) {
            const row = rowFor(id);
            return {
                id: id,
                name: row ? (row.getAttribute('data-user-name') || ('Member ' + id)) : ('Member ' + id),
                status: 'queued',
                timeLabel: '—'
            };
        });
        showPanel(jobs);
        if (button) {
            button.dataset.busy = '1';
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Generating';
        }

        await runPool(jobs, concurrency, async function (job) {
            job.status = 'running';
            job.timeLabel = '0.0 s';
            updatePanel(jobs);
            setRowBusy(job.id, true);
            try {
                const payload = await generateOne(job.id, function (elapsed) {
                    job.timeLabel = formatDuration(elapsed);
                    const rowTime = rowFor(job.id)?.querySelector('.material-time');
                    if (rowTime) rowTime.textContent = job.timeLabel;
                    updatePanel(jobs);
                }, force);
                job.status = 'done';
                job.cached = !!payload.cached;
                job.timeLabel = payload.cached
                    ? (formatDuration(payload.duration_ms) + ' cache')
                    : payload.duration_label;
                setRowResult(job.id, payload);
            } catch (err) {
                job.status = 'failed';
                job.timeLabel = '—';
                setRowError(job.id, err.message || 'Failed');
            }
            updatePanel(jobs);
        });

        if (button) {
            delete button.dataset.busy;
            button.innerHTML = '<i class="bi bi-files"></i> Generate selected';
            sync();
        }

        const failed = jobs.filter((j) => j.status === 'failed').length;
        const ok = jobs.filter((j) => j.status === 'done').length;
        const cachedN = jobs.filter((j) => j.cached).length;
        if (window.Swal) {
            let title = ok + ' generated';
            if (cachedN && cachedN === ok) title = ok + ' reused from cache';
            else if (cachedN) title = ok + ' generated (' + cachedN + ' cache)';
            if (failed) title += ', ' + failed + ' failed';
            title += ' in parallel';
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: failed && !ok ? 'error' : (failed ? 'warning' : 'success'),
                title: title,
                showConfirmButton: false,
                timer: 3600
            });
        }
    }

    checks.forEach((box) => box.addEventListener('change', sync));
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checks.forEach((box) => { box.checked = selectAll.checked; });
            sync();
        });
    }

    document.querySelectorAll('.material-generate-form').forEach(function (rowForm) {
        rowForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const userId = rowForm.querySelector('[name="user_id"]')?.value;
            if (!userId || !sessionId) return;
            const forceValue = rowForm.querySelector('[name="force"]')?.value;
            const alreadyGenerated = forceValue === '1' || !!rowForm.closest('tr')?.querySelector('.material-view-link');
            generateMembers([Number(userId)], alreadyGenerated);
        });
    });

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const ids = selected().map((box) => Number(box.value)).filter(Boolean);
            if (ids.length === 0) {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Select members',
                        text: 'Tick at least one member to generate material.',
                        confirmButtonColor: '#ff6b00'
                    });
                }
                return;
            }
            const label = ids.length === 1 ? '1 member' : ids.length + ' members';
            const start = function () { generateMembers(ids, false); };
            if (!window.Swal) {
                if (window.confirm('Generate material for ' + label + ' in parallel? Already generated files are reused instantly.')) start();
                return;
            }
            Swal.fire({
                title: 'Generate selected?',
                text: 'Generate material for ' + label + ' in parallel (' + concurrency + ' at a time). Already generated files are reused instantly.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ff6b00',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, generate',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: { popup: 'rounded-4' }
            }).then(function (result) {
                if (result.isConfirmed) start();
            });
        });
    }

    sync();
})();
</script>
@endpush
@endif
