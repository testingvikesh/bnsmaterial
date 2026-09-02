@extends('layouts.app')
@section('title', 'Reverse Management Dashboard')
@section('page_title', 'Reverse Management Dashboard')

@php
    $k = $report['kpis'];
    $home = $report['home'];
    $filters = $report['filters'];
    $options = $report['filterOptions'];
    $detail = $report['detail'];
    $prod = $report['productivity'];
    $sr = $report['sessionReport'];
    $qs = array_filter(['session_id' => $session->id] + $filters, fn ($v) => $v !== null && $v !== '');
@endphp

@push('styles')
<style>
    .rmd { --navy:#0a1d37; --brand:#ff6b00; --gold:#ffb800; --line:#e8edf3; }
    .rmd-hero {
        background: linear-gradient(135deg, #050b14 0%, #0a1d37 72%, #123056 100%);
        color: #fff;
        border-radius: 18px;
        padding: 22px 24px 18px;
        border-bottom: 3px solid #ff6b00;
        margin-bottom: 18px;
    }
    .rmd-hero small { color: #ffb800; letter-spacing: .08em; text-transform: uppercase; font-weight: 800; }
    .rmd-hero h1 { margin: 6px 0 4px; font-size: 1.7rem; font-weight: 800; }
    .rmd-hero p { margin: 0; color: #cbd5e1; }
    .rmd-card {
        background: #fff;
        border: 1px solid var(--line, #e8edf3);
        border-top: 4px solid #ff6b00;
        border-radius: 16px;
        padding: 18px 20px;
        margin-bottom: 16px;
        box-shadow: 0 10px 28px rgba(10,29,55,.06);
    }
    .rmd-card h2 { margin: 0 0 14px; color: #ff6b00; font-size: 1.05rem; font-weight: 800; }
    .rmd-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 720px; }
    .rmd-sheet { overflow: auto; border-radius: 12px; border: 1px solid #e8edf3; }
    .rmd-table thead th {
        background: linear-gradient(90deg, #0a1d37, #16325a);
        color: #fff;
        padding: 11px 10px;
        font-size: 12px;
        letter-spacing: .04em;
        text-transform: uppercase;
        border-bottom: 3px solid #ff6b00;
        white-space: nowrap;
    }
    .rmd-table td { padding: 11px 10px; border-bottom: 1px solid #e8edf3; background: #fff; font-size: 14px; }
    .rmd-table tbody tr:nth-child(even) td { background: #f8fafc; }
    .rmd-num { color: #ff6b00; font-weight: 800; }
    .rmd-gap { color: #c2410c; font-weight: 800; }
    .rmd-filters { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; }
    .rmd-filters label { display: block; font-size: 11px; font-weight: 800; color: #ff6b00; text-transform: uppercase; margin-bottom: 4px; }
    .rmd-actions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 14px; }
    .rmd-btn { border: 0; border-radius: 999px; padding: 9px 14px; font-weight: 800; font-size: .82rem; }
    .rmd-btn-brand { background: #ff6b00; color: #fff; }
    .rmd-btn-navy { background: #0a1d37; color: #fff; }
    .rmd-btn-soft { background: #fff; color: #0a1d37; border: 1px solid #e8edf3; }
    .rmd-home { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .rmd-stat { background: #f8fafc; border: 1px solid #e8edf3; border-radius: 14px; padding: 12px 14px; position: relative; overflow: hidden; }
    .rmd-stat::before { content: ""; position: absolute; left: 0; top: 0; right: 0; height: 3px; background: linear-gradient(90deg, #ffb800, #ff6b00); }
    .rmd-stat span { display: block; color: #ff6b00; font-size: 11px; font-weight: 800; text-transform: uppercase; }
    .rmd-stat strong { font-size: 1.15rem; }
    .rmd-chart { display: flex; align-items: flex-end; gap: 18px; min-height: 180px; padding: 8px 6px 0; }
    .rmd-col { flex: 1; text-align: center; }
    .rmd-bars { display: flex; justify-content: center; align-items: flex-end; gap: 6px; height: 140px; }
    .rmd-bar { width: 14px; border-radius: 6px 6px 0 0; min-height: 4px; }
    .rmd-bar.target { background: #0a1d37; }
    .rmd-bar.planned { background: #ff6b00; }
    .rmd-col small { display: block; margin-top: 8px; font-weight: 700; color: #0a1d37; }
    .rmd-legend { display: flex; gap: 14px; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 8px; }
    .rmd-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-right: 4px; }
    .rmd-flow { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; }
    .rmd-pill { background: #fff7ed; color: #c2410c; border: 1px solid #fdba74; padding: 8px 12px; border-radius: 999px; font-weight: 700; font-size: 13px; }
    .rmd-note { color: #64748b; font-size: .9rem; margin: 0 0 12px; }
    .rmd-more-row { display: none; }
    #rmdMemberTable.is-expanded .rmd-more-row { display: table-row; }
    .rmd-more-wrap { margin-top: 12px; }
    @media (max-width: 1100px) {
        .rmd-filters, .rmd-home { grid-template-columns: 1fr 1fr; }
    }
    @media print {
        .sidebar, .topbar, .rmd-actions, .rmd-filters { display: none !important; }
        .main-wrap { margin: 0 !important; }
            .rmd-card { break-inside: avoid; }
            .rmd-more-row { display: table-row !important; }
            .rmd-more-wrap { display: none !important; }
    }
</style>
@endpush

@section('content')
<div class="rmd">
    <div class="rmd-hero">
        <small>BNS ERP — Super Admin</small>
        <h1>Global Reverse Management Command Center</h1>
        <p>{{ $session->name }} · Academic / Business Year {{ $report['year'] }}</p>
    </div>

    <form method="GET" action="{{ route('admin.material.dashboard') }}" class="rmd-card">
        <h2>Top Control Panel</h2>
        <input type="hidden" name="session_id" value="{{ $session->id }}">
        <div class="rmd-filters">
            <div>
                <label>Region</label>
                <select name="region" class="form-select form-select-sm">
                    <option value="global" @selected($filters['region']==='global')>Global</option>
                    <option value="india" @selected($filters['region']==='india')>India</option>
                    <option value="state" @selected($filters['region']==='state')>State</option>
                    <option value="country" @selected($filters['region']==='country')>Country</option>
                </select>
            </div>
            <div>
                <label>Country</label>
                <select name="country" class="form-select form-select-sm">
                    <option value="">All Countries</option>
                    @foreach($options['countries'] as $item)
                        <option value="{{ $item }}" @selected($filters['country']===$item)>{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>State</label>
                <select name="state" class="form-select form-select-sm">
                    <option value="">All States</option>
                    @foreach($options['states'] as $item)
                        <option value="{{ $item }}" @selected($filters['state']===$item)>{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>BNS Institute</label>
                <select name="institute" class="form-select form-select-sm">
                    <option value="">All Institutes</option>
                    <option value="BNS Institute">BNS Institute</option>
                </select>
            </div>
            <div>
                <label>Business Coach</label>
                <select name="coach" class="form-select form-select-sm">
                    <option value="">All Coaches</option>
                    <option value="Business Coach">Business Coach</option>
                </select>
            </div>
            <div>
                <label>Business Category</label>
                <select name="category" class="form-select form-select-sm">
                    <option value="">All Categories</option>
                    @foreach($options['categories'] as $item)
                        <option value="{{ $item }}" @selected($filters['category']===$item)>{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Member</label>
                <input type="text" name="member" value="{{ $filters['member'] }}" class="form-control form-control-sm" placeholder="All Members">
            </div>
            <div>
                <label>Academic / Business Year</label>
                <input type="text" name="year" value="{{ $filters['year'] }}" class="form-control form-control-sm">
            </div>
            <div>
                <label>Session</label>
                <input class="form-control form-control-sm" value="{{ $session->name }}" readonly>
            </div>
            <div>
                <label>Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="completed" @selected($filters['status']==='completed')>Completed</option>
                    <option value="pending" @selected($filters['status']==='pending')>Pending</option>
                </select>
            </div>
            <div>
                <label>Target Achievement</label>
                <select name="achievement" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="0-50" @selected($filters['achievement']==='0-50')>0–50%</option>
                    <option value="50-80" @selected($filters['achievement']==='50-80')>50–80%</option>
                    <option value="80-100" @selected($filters['achievement']==='80-100')>80–100%</option>
                    <option value="100+" @selected($filters['achievement']==='100+')>100%+</option>
                </select>
            </div>
        </div>
        <div class="rmd-actions">
            <button class="rmd-btn rmd-btn-brand" type="submit">Apply Filter</button>
            <a class="rmd-btn rmd-btn-soft" href="{{ route('admin.material.dashboard', ['session_id' => $session->id]) }}">Reset</a>
            <a class="rmd-btn rmd-btn-navy" href="{{ route('admin.material.dashboard', $qs + ['export' => 'csv']) }}">Export</a>
            <button class="rmd-btn rmd-btn-soft" type="button" onclick="window.print()">Print</button>
            <a class="rmd-btn rmd-btn-soft" href="{{ route('admin.material.index', ['session_id' => $session->id]) }}">Back to Material</a>
        </div>
    </form>

    <div class="rmd-card" id="section-01">
        <h2>01. Global Executive Summary</h2>
        <div class="rmd-sheet">
            <table class="rmd-table">
                <thead><tr><th>KPI</th><th>Global Total</th><th>India</th><th>Other Countries</th></tr></thead>
                <tbody>
                    @foreach([
                        ['BNS Institutes', $k['institutes']],
                        ['Business Coaches', $k['coaches']],
                        ['Registered Members', $k['members']],
                        ['Reverse Sessions', $k['sessions']],
                        ['Completed Sessions', $k['completed']],
                        ['Businesses Analysed', $k['businesses']],
                        ['Markets Identified', $k['markets']],
                        ['Offers Created', $k['offers']],
                        ['Target Customers', $k['customers']],
                        ['Revenue Target', $k['target']],
                        ['Planned Revenue', $k['planned']],
                        ['Revenue Gap', $k['gap']],
                        ['Target Achievement', $k['achievement']],
                    ] as $row)
                        <tr>
                            <td>{{ $row[0] }}</td>
                            <td class="rmd-num">{{ $row[1]['global_label'] }}</td>
                            <td>{{ $row[1]['india_label'] }}</td>
                            <td>{{ $row[1]['other_label'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="rmd-card" id="section-02">
        <h2>02. Global Reverse Management Performance</h2>
        <p class="rmd-note">Revenue target vs planned revenue by region for this Reverse Management session.</p>
        <div class="rmd-legend"><span><i class="rmd-dot" style="background:#0a1d37"></i> Target</span><span><i class="rmd-dot" style="background:#ff6b00"></i> Planned</span></div>
        <div class="rmd-chart">
            @foreach($report['regions'] as $bar)
                <div class="rmd-col">
                    <div class="rmd-bars">
                        <div class="rmd-bar target" style="height: {{ max(4, $bar['target_h']) }}%"></div>
                        <div class="rmd-bar planned" style="height: {{ max(4, $bar['planned_h']) }}%"></div>
                    </div>
                    <small>{{ $bar['label'] }}</small>
                </div>
            @endforeach
        </div>
    </div>

    <div class="rmd-card" id="section-03">
        <h2>03. Institute-wise Performance</h2>
        <div class="rmd-sheet">
            <table class="rmd-table">
                <thead><tr><th>Rank</th><th>Institute</th><th>Country</th><th>Members</th><th>Sessions</th><th>Target</th><th>Planned Revenue</th><th>Achievement</th></tr></thead>
                <tbody>
                    @foreach($report['institutes'] as $row)
                        <tr>
                            <td class="rmd-num">{{ $row['rank'] }}</td>
                            <td>{{ $row['name'] }}</td>
                            <td>{{ $row['country'] }}</td>
                            <td>{{ $row['members'] }}</td>
                            <td>{{ $row['sessions'] }}</td>
                            <td>{{ $row['target'] }}</td>
                            <td>{{ $row['planned'] }}</td>
                            <td class="rmd-num">{{ $row['achievement'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="rmd-card" id="section-04">
        <h2>04. Business Coach Performance</h2>
        <div class="rmd-sheet">
            <table class="rmd-table">
                <thead><tr><th>Coach</th><th>Institute</th><th>Members</th><th>Sessions</th><th>Businesses</th><th>Target Revenue</th><th>Planned Revenue</th><th>Achievement</th></tr></thead>
                <tbody>
                    @foreach($report['coaches'] as $row)
                        <tr>
                            <td>{{ $row['name'] }}</td>
                            <td>{{ $row['institute'] }}</td>
                            <td>{{ $row['members'] }}</td>
                            <td>{{ $row['sessions'] }}</td>
                            <td>{{ $row['businesses'] }}</td>
                            <td>{{ $row['target'] }}</td>
                            <td>{{ $row['planned'] }}</td>
                            <td class="rmd-num">{{ $row['achievement'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="rmd-card" id="section-05">
        <h2>05. Member-wise Reverse Management</h2>
        <p class="rmd-note">Operational table for this Reverse Management session. Use View to open the member plan.</p>
        <div class="rmd-sheet">
            <table class="rmd-table" id="rmdMemberTable">
                <thead>
                    <tr>
                        <th>Member</th><th>Business</th><th>Category</th><th>Session</th>
                        <th>Desired Turnover</th><th>7 Offers</th><th>Customer Target</th>
                        <th>Planned Revenue</th><th>Gap</th><th>Achievement</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($report['members'] as $index => $row)
                        <tr @if($index >= 5) class="rmd-more-row" @endif>
                            <td>{{ $row['member'] }}</td>
                            <td>{{ $row['business'] }}</td>
                            <td>{{ $row['category'] }}</td>
                            <td>{{ $session->name }}</td>
                            <td>{{ $row['desired_label'] }}</td>
                            <td>{{ $row['offers'] }}</td>
                            <td>{{ $row['customers_label'] }}</td>
                            <td>{{ $row['planned_label'] }}</td>
                            <td class="rmd-gap">{{ $row['gap_label'] }}</td>
                            <td class="rmd-num">{{ $row['achievement_label'] }}</td>
                            <td>
                                @if($row['view_url'])
                                    <a class="rmd-btn rmd-btn-navy" href="{{ $row['view_url'] }}" target="_blank" rel="noopener">View Member</a>
                                @else
                                    <span class="badge-pill badge-inactive">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="11">No members found for this session.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(count($report['members']) > 5)
            <div class="rmd-more-wrap">
                <button type="button" class="rmd-btn rmd-btn-brand" id="rmdMoreMembers" data-remaining="{{ count($report['members']) - 5 }}">
                    More Members ({{ count($report['members']) - 5 }})
                </button>
            </div>
        @endif
    </div>

    <div class="rmd-card" id="section-06">
        <h2>06. Session-wise Outcome</h2>
        <div class="rmd-sheet">
            <table class="rmd-table">
                <thead><tr><th>Session</th><th>Institute</th><th>Coach</th><th>Members</th><th>Completed</th><th>Target Revenue</th><th>Planned Revenue</th><th>Gap</th><th>Avg. Achievement</th></tr></thead>
                <tbody>
                    @foreach($report['sessionOutcomes'] as $row)
                        <tr>
                            <td>{{ $row['code'] }}</td>
                            <td>{{ $row['institute'] }}</td>
                            <td>{{ $row['coach'] }}</td>
                            <td>{{ $row['members'] }}</td>
                            <td>{{ $row['completed'] }}</td>
                            <td>{{ $row['target'] }}</td>
                            <td>{{ $row['planned'] }}</td>
                            <td class="rmd-gap">{{ $row['gap'] }}</td>
                            <td class="rmd-num">{{ $row['achievement'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="rmd-card" id="section-07">
        <h2>07. 7 Market Analysis</h2>
        <div class="rmd-sheet">
            <table class="rmd-table">
                <thead><tr><th>Market</th><th>Businesses Using</th><th>Offers Created</th><th>Customer Target</th><th>Revenue Target</th><th>Revenue Contribution</th></tr></thead>
                <tbody>
                    @foreach($report['markets'] as $row)
                        <tr>
                            <td>{{ $row['name'] }}</td>
                            <td>{{ $row['businesses'] }}</td>
                            <td>{{ $row['offers'] }}</td>
                            <td>{{ $row['customers_label'] }}</td>
                            <td>{{ $row['revenue_label'] }}</td>
                            <td class="rmd-num">{{ $row['share'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="rmd-card" id="section-08">
        <h2>08. 7 Offer Analytics</h2>
        <div class="rmd-sheet">
            <table class="rmd-table">
                <thead><tr><th>Offer No.</th><th>Offer Type</th><th>Businesses</th><th>Avg. Price</th><th>Customers</th><th>Revenue</th><th>Success Rate</th></tr></thead>
                <tbody>
                    @foreach($report['offers'] as $row)
                        <tr>
                            <td class="rmd-num">{{ $row['no'] }}</td>
                            <td>{{ $row['type'] }}</td>
                            <td>{{ $row['businesses'] }}</td>
                            <td>{{ $row['avg_price'] }}</td>
                            <td>{{ $row['customers'] }}</td>
                            <td>{{ $row['revenue'] }}</td>
                            <td>{{ $row['success'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="rmd-card" id="section-09">
        <h2>09. Revenue Target vs Planned Revenue</h2>
        <div class="rmd-legend"><span><i class="rmd-dot" style="background:#0a1d37"></i> Target revenue</span><span><i class="rmd-dot" style="background:#ff6b00"></i> Planned revenue</span></div>
        <div class="rmd-chart">
            @foreach($report['categoryBars'] as $bar)
                <div class="rmd-col">
                    <div class="rmd-bars">
                        <div class="rmd-bar target" style="height: {{ max(4, $bar['target_h']) }}%"></div>
                        <div class="rmd-bar planned" style="height: {{ max(4, $bar['planned_h']) }}%"></div>
                    </div>
                    <small>{{ $bar['label'] }}</small>
                </div>
            @endforeach
        </div>
    </div>

    <div class="rmd-card" id="section-10">
        <h2>10. Achievement Category</h2>
        <div class="rmd-sheet">
            <table class="rmd-table">
                <thead><tr><th>Achievement Level</th><th>Members</th><th>% of Members</th><th>Revenue</th></tr></thead>
                <tbody>
                    @foreach($report['achievementBands'] as $row)
                        <tr>
                            <td>{{ $row['label'] }}</td>
                            <td>{{ $row['members'] }}</td>
                            <td>{{ $row['share'] }}</td>
                            <td>{{ $row['revenue'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="rmd-card" id="section-11">
        <h2>11. Top 20 Business Performers</h2>
        <div class="rmd-sheet">
            <table class="rmd-table">
                <thead><tr><th>Rank</th><th>Member</th><th>Business</th><th>Institute</th><th>Target</th><th>Planned</th><th>Achievement</th></tr></thead>
                <tbody>
                    @forelse($report['top20'] as $i => $row)
                        <tr>
                            <td class="rmd-num">{{ $i + 1 }}</td>
                            <td>{{ $row['member'] }}</td>
                            <td>{{ $row['business'] }}</td>
                            <td>{{ $row['institute'] }}</td>
                            <td>{{ $row['desired_label'] }}</td>
                            <td>{{ $row['planned_label'] }}</td>
                            <td class="rmd-num">{{ $row['achievement_label'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7">No completed reverse plans yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rmd-card" id="section-12">
        <h2>12. Reverse Management Alert Center</h2>
        <div class="rmd-sheet">
            <table class="rmd-table">
                <thead><tr><th>Alert</th><th>Count</th><th>Action</th></tr></thead>
                <tbody>
                    @foreach($report['alerts'] as $row)
                        <tr>
                            <td>{{ $row['alert'] }}</td>
                            <td class="rmd-num">{{ $row['count'] }}</td>
                            <td>
                                @if($row['action'] === 'pending')
                                    <a class="rmd-btn rmd-btn-navy" href="{{ route('admin.material.dashboard', ['session_id' => $session->id, 'status' => 'pending']) }}">View</a>
                                @elseif($row['action'] === 'below')
                                    <a class="rmd-btn rmd-btn-navy" href="{{ route('admin.material.dashboard', ['session_id' => $session->id, 'status' => 'completed', 'achievement' => '80-100']) }}">View</a>
                                @elseif($row['action'] === 'star')
                                    <a class="rmd-btn rmd-btn-navy" href="{{ route('admin.material.dashboard', ['session_id' => $session->id, 'achievement' => '100+']) }}">View</a>
                                @else
                                    <a class="rmd-btn rmd-btn-soft" href="#section-05">View</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="rmd-card" id="section-13">
        <h2>13. Country-wise Dashboard</h2>
        <div class="rmd-sheet">
            <table class="rmd-table">
                <thead><tr><th>Country</th><th>Institutes</th><th>Coaches</th><th>Members</th><th>Sessions</th><th>Businesses</th><th>Revenue Target</th><th>Achievement</th></tr></thead>
                <tbody>
                    @foreach($report['countries'] as $row)
                        <tr>
                            <td>{{ $row['name'] }}</td>
                            <td>{{ $row['institutes'] }}</td>
                            <td>{{ $row['coaches'] }}</td>
                            <td>{{ $row['members'] }}</td>
                            <td>{{ $row['sessions'] }}</td>
                            <td>{{ $row['businesses'] }}</td>
                            <td>{{ $row['target'] }}</td>
                            <td class="rmd-num">{{ $row['achievement'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="rmd-card" id="section-14">
        <h2>14. India State-wise Dashboard</h2>
        <div class="rmd-sheet">
            <table class="rmd-table">
                <thead><tr><th>State</th><th>BNS Institutes</th><th>Members</th><th>Sessions</th><th>Businesses</th><th>Revenue Target</th><th>Planned Revenue</th><th>Achievement</th></tr></thead>
                <tbody>
                    @foreach($report['states'] as $row)
                        <tr>
                            <td>{{ $row['name'] }}</td>
                            <td>{{ $row['institutes'] }}</td>
                            <td>{{ $row['members'] }}</td>
                            <td>{{ $row['sessions'] }}</td>
                            <td>{{ $row['businesses'] }}</td>
                            <td>{{ $row['target'] }}</td>
                            <td>{{ $row['planned'] }}</td>
                            <td class="rmd-num">{{ $row['achievement'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="rmd-card" id="section-15">
        <h2>15. Business Category Analysis</h2>
        <div class="rmd-sheet">
            <table class="rmd-table">
                <thead><tr><th>Category</th><th>Businesses</th><th>Members</th><th>Target Revenue</th><th>Planned Revenue</th><th>Gap</th><th>Achievement</th></tr></thead>
                <tbody>
                    @foreach($report['categories'] as $row)
                        <tr>
                            <td>{{ $row['name'] }}</td>
                            <td>{{ $row['businesses'] }}</td>
                            <td>{{ $row['members'] }}</td>
                            <td>{{ $row['target'] }}</td>
                            <td>{{ $row['planned'] }}</td>
                            <td class="rmd-gap">{{ $row['gap'] }}</td>
                            <td class="rmd-num">{{ $row['achievement'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="rmd-card" id="section-16">
        <h2>16. Monthly Reverse Management Trend</h2>
        <div class="rmd-chart">
            @foreach($report['monthly'] as $bar)
                <div class="rmd-col">
                    <div class="rmd-bars">
                        <div class="rmd-bar planned" style="height: {{ max(4, $bar['h']) }}%"></div>
                    </div>
                    <small>{{ $bar['label'] }}</small>
                </div>
            @endforeach
        </div>
    </div>

    <div class="rmd-card" id="section-17">
        <h2>17. Session Productivity</h2>
        <div class="rmd-sheet">
            <table class="rmd-table">
                <thead><tr><th>Metric</th><th>Today</th><th>This Week</th><th>This Month</th><th>This Year</th></tr></thead>
                <tbody>
                    @foreach([
                        ['Sessions Planned', 'planned'],
                        ['Sessions Completed', 'completed'],
                        ['Members Covered', 'members'],
                        ['Businesses Analysed', 'businesses'],
                        ['Offers Generated', 'offers'],
                        ['Revenue Target Created', 'target'],
                        ['Revenue Planned', 'revenue'],
                    ] as $row)
                        <tr>
                            <td>{{ $row[0] }}</td>
                            <td>{{ $prod['today'][$row[1]] }}</td>
                            <td>{{ $prod['week'][$row[1]] }}</td>
                            <td>{{ $prod['month'][$row[1]] }}</td>
                            <td>{{ $prod['year'][$row[1]] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="rmd-card" id="section-18">
        <h2>18. Business Innovation Index</h2>
        <div class="rmd-sheet">
            <table class="rmd-table">
                <thead><tr><th>Parameter</th><th>Weight</th></tr></thead>
                <tbody>
                    @foreach($report['innovation'] as $row)
                        <tr><td>{{ $row['parameter'] }}</td><td class="rmd-num">{{ $row['weight'] }}</td></tr>
                    @endforeach
                    <tr><td><strong>TOTAL</strong></td><td class="rmd-num">100</td></tr>
                </tbody>
            </table>
        </div>
        <p class="rmd-note" style="margin-top:12px">90–100 = ⭐ Excellent · 75–89 = 🟢 Strong · 60–74 = 🟡 Needs Improvement · Below 60 = 🔴 Intervention Required</p>
    </div>

    <div class="rmd-card" id="section-19">
        <h2>19. Drill-down System</h2>
        <div class="rmd-flow">
            @foreach(['GLOBAL','COUNTRY','STATE','BNS INSTITUTE','BUSINESS COACH','MEMBER','BUSINESS','REVERSE MANAGEMENT SESSION','DESIRED TURNOVER','7 MARKETS','7 OFFERS','CUSTOMER TARGET','REVENUE','FINAL OUTCOME'] as $i => $step)
                @if($i > 0)<span style="color:#ff6b00;font-weight:800">↓</span>@endif
                <span class="rmd-pill">{{ $step }}</span>
            @endforeach
        </div>
    </div>

    <div class="rmd-card" id="section-20">
        <h2>20. Member Detail — Super Admin View</h2>
        @if($detail)
            <div class="rmd-sheet">
                <table class="rmd-table">
                    <thead><tr><th>Section</th><th>Data</th></tr></thead>
                    <tbody>
                        <tr><td>Member</td><td>{{ $detail['member'] }}</td></tr>
                        <tr><td>Business</td><td>{{ $detail['business'] }}</td></tr>
                        <tr><td>Category</td><td>{{ $detail['category'] }}</td></tr>
                        <tr><td>Institute</td><td>{{ $detail['institute'] }}</td></tr>
                        <tr><td>Coach</td><td>{{ $detail['coach'] }}</td></tr>
                        <tr><td>Session</td><td>{{ $session->name }}</td></tr>
                        <tr><td>Desired Turnover</td><td>{{ $detail['desired_label'] }}</td></tr>
                        <tr><td>7 Markets</td><td>{{ $detail['completed'] ? 'Completed' : 'Pending' }}</td></tr>
                        <tr><td>7 Offers</td><td>{{ $detail['completed'] ? 'Completed' : 'Pending' }}</td></tr>
                        <tr><td>Customer Target</td><td>{{ $detail['customers_label'] }}</td></tr>
                        <tr><td>Planned Revenue</td><td>{{ $detail['planned_label'] }}</td></tr>
                        <tr><td>Revenue Gap</td><td class="rmd-gap">{{ $detail['gap_label'] }}</td></tr>
                        <tr><td>Achievement</td><td class="rmd-num">{{ $detail['achievement_label'] }}</td></tr>
                        <tr><td>Innovation Score</td><td>{{ $detail['score'] }} · {{ $detail['rating'] }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="rmd-actions">
                @if($detail['view_url'])
                    <a class="rmd-btn rmd-btn-navy" href="{{ $detail['view_url'] }}" target="_blank" rel="noopener">View 7 Markets</a>
                    <a class="rmd-btn rmd-btn-brand" href="{{ $detail['view_url'] }}" target="_blank" rel="noopener">View Revenue</a>
                    <a class="rmd-btn rmd-btn-soft" href="{{ $detail['view_url'] }}" target="_blank" rel="noopener">Print</a>
                @endif
            </div>
        @else
            <p class="rmd-note">Generate Reverse Management for a member to see Super Admin detail.</p>
        @endif
    </div>

    <div class="rmd-card" id="section-21">
        <h2>21. Coach → Super Admin Session Report</h2>
        <div class="rmd-sheet">
            <table class="rmd-table">
                <thead><tr><th>Report Parameter</th><th>Result</th></tr></thead>
                <tbody>
                    <tr><td>Institute</td><td>{{ $sr['institute'] }}</td></tr>
                    <tr><td>Coach</td><td>{{ $sr['coach'] }}</td></tr>
                    <tr><td>Session Date</td><td>{{ $sr['date'] }}</td></tr>
                    <tr><td>Total Members</td><td>{{ $sr['members'] }}</td></tr>
                    <tr><td>Present</td><td>{{ $sr['present'] }}</td></tr>
                    <tr><td>Completed</td><td>{{ $sr['completed'] }}</td></tr>
                    <tr><td>Incomplete</td><td>{{ $sr['incomplete'] }}</td></tr>
                    <tr><td>Total Desired Turnover</td><td>{{ $sr['desired'] }}</td></tr>
                    <tr><td>Total Planned Revenue</td><td>{{ $sr['planned'] }}</td></tr>
                    <tr><td>Total Revenue Gap</td><td class="rmd-gap">{{ $sr['gap'] }}</td></tr>
                    <tr><td>Average Achievement</td><td class="rmd-num">{{ $sr['achievement'] }}</td></tr>
                    <tr><td>Top Performer</td><td>{{ $sr['top'] }}</td></tr>
                    <tr><td>Lowest Performer</td><td>{{ $sr['low'] }}</td></tr>
                    <tr><td>Most Popular Market</td><td>{{ $sr['market'] }}</td></tr>
                    <tr><td>Most Successful Offer</td><td>{{ $sr['offer'] }}</td></tr>
                    <tr><td>Coach Recommendation</td><td>Review pending members and close revenue gaps.</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="rmd-card" id="section-22">
        <h2>22. Super Admin Action Center</h2>
        <div class="rmd-sheet">
            <table class="rmd-table">
                <thead><tr><th>Situation</th><th>Automatic Action</th></tr></thead>
                <tbody>
                    <tr><td>Revenue Gap &gt; 30%</td><td>🔴 Intervention</td></tr>
                    <tr><td>7 Offers incomplete</td><td>🟠 Coach Review</td></tr>
                    <tr><td>Customer target missing</td><td>🟠 Member Review</td></tr>
                    <tr><td>Session incomplete</td><td>🟠 Follow-up</td></tr>
                    <tr><td>Achievement 80%+</td><td>🟢 Good</td></tr>
                    <tr><td>Achievement 100%+</td><td>⭐ Recognition</td></tr>
                    <tr><td>Repeated low performance</td><td>🔴 Business Coaching Required</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="rmd-card" id="section-23">
        <h2>23. Export & Reporting</h2>
        <div class="rmd-actions">
            <a class="rmd-btn rmd-btn-navy" href="{{ route('admin.material.dashboard', $qs + ['export' => 'csv']) }}">Export Global Report</a>
            <a class="rmd-btn rmd-btn-navy" href="{{ route('admin.material.dashboard', $qs + ['country' => 'India', 'export' => 'csv']) }}">Export India Report</a>
            <a class="rmd-btn rmd-btn-navy" href="{{ route('admin.material.dashboard', $qs + ['export' => 'csv']) }}">Export Member Report</a>
            <a class="rmd-btn rmd-btn-navy" href="{{ route('admin.material.dashboard', $qs + ['export' => 'csv']) }}">Export Session Report</a>
            <button class="rmd-btn rmd-btn-brand" type="button" onclick="window.print()">Print / PDF</button>
        </div>
        <p class="rmd-note" style="margin-top:10px">Formats: Excel CSV · PDF (Print) · Print</p>
    </div>

    <div class="rmd-card" id="section-24">
        <h2>24. Super Admin Home — Final Screen</h2>
        <div class="rmd-home">
            <div class="rmd-stat"><span>Institutes</span><strong>{{ $home['institutes'] }}</strong></div>
            <div class="rmd-stat"><span>Countries</span><strong>{{ $home['countries'] }}</strong></div>
            <div class="rmd-stat"><span>States</span><strong>{{ $home['states'] }}</strong></div>
            <div class="rmd-stat"><span>Coaches</span><strong>{{ $home['coaches'] }}</strong></div>
            <div class="rmd-stat"><span>Members</span><strong>{{ $home['members'] }}</strong></div>
            <div class="rmd-stat"><span>Businesses</span><strong>{{ $home['businesses'] }}</strong></div>
            <div class="rmd-stat"><span>Reverse Sessions</span><strong>{{ $home['sessions'] }}</strong></div>
            <div class="rmd-stat"><span>Total Desired Turnover</span><strong>{{ $home['desired'] }}</strong></div>
            <div class="rmd-stat"><span>Total Planned Revenue</span><strong>{{ $home['planned'] }}</strong></div>
            <div class="rmd-stat"><span>Total Revenue Gap</span><strong class="rmd-gap">{{ $home['gap'] }}</strong></div>
            <div class="rmd-stat"><span>Average Achievement</span><strong class="rmd-num">{{ $home['achievement'] }}</strong></div>
            <div class="rmd-stat"><span>7 Markets Identified</span><strong>{{ $home['markets'] }}</strong></div>
            <div class="rmd-stat"><span>7 Offers Created</span><strong>{{ $home['offers'] }}</strong></div>
            <div class="rmd-stat"><span>Target Customers</span><strong>{{ $home['customers'] }}</strong></div>
        </div>
        <p class="rmd-note" style="margin-top:14px">
            Super Admin → Global / India → Country → State → BNS Institute → Business Coach → Member → Business → Reverse Management Session → Desired Turnover → 7 Markets → 7 Offers → Offer Price → Target Customers → Expected Revenue → Revenue Gap → Achievement % → Coach Review → Super Admin Analytics → Global Business Impact
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const table = document.getElementById('rmdMemberTable');
    const button = document.getElementById('rmdMoreMembers');
    if (!table || !button) return;
    const remaining = button.getAttribute('data-remaining') || '';
    button.addEventListener('click', function () {
        const open = table.classList.toggle('is-expanded');
        button.textContent = open ? 'Show less' : ('More Members (' + remaining + ')');
    });
})();
</script>
@endpush
