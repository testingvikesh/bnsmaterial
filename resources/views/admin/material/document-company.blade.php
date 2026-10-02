<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $facts['business_name'] ?? 'Business' }} — PVT LTD Session</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #071422;
            --ink: #10243d;
            --brand: #ff6b00;
            --gold: #ffb800;
            --line: #eadfce;
            --paper: #fffdf9;
            --font-ui: Poppins, Arial, sans-serif;
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: var(--font-ui);
            background:
                radial-gradient(1100px 480px at 8% -8%, rgba(255,107,0,.16), transparent 55%),
                radial-gradient(900px 420px at 100% 0%, rgba(255,184,0,.14), transparent 50%),
                #f3eee6;
            color: var(--ink);
            font-size: 16px;
        }
        .shell { max-width: 1280px; margin: 0 auto; padding: 18px 18px 36px; }
        .topbar {
            position: sticky; top: 12px; z-index: 40;
            display: flex; justify-content: space-between; align-items: center; gap: 16px;
            background: rgba(7, 20, 34, .92);
            color: #fff; padding: 14px 18px; border-radius: 22px;
            box-shadow: 0 18px 40px rgba(7, 20, 34, .22);
        }
        .brand-mark { display: flex; align-items: center; gap: 12px; min-width: 0; }
        .logo-dot {
            width: 42px; height: 42px; border-radius: 14px; flex-shrink: 0;
            display: grid; place-items: center;
            background: linear-gradient(135deg, #ffb800, #ff6b00);
            color: #071422; font-weight: 800;
        }
        .brand-mark small { display: block; color: #ffb800; letter-spacing: .12em; text-transform: uppercase; font-size: 10px; font-weight: 800; }
        .brand-mark strong { display: block; font-size: 18px; line-height: 1.2; }
        .top-meta { color: #cbd5e1; font-size: 13px; font-weight: 600; text-align: right; }
        .hero {
            margin-top: 18px; color: #fff; overflow: hidden; border-radius: 28px;
            padding: 42px 40px 36px;
            background: linear-gradient(135deg, #081526 0%, #123056 58%, #c2410c 160%);
            box-shadow: 0 30px 70px rgba(16, 36, 61, .12);
        }
        .kicker { color: #ffb800; letter-spacing: .16em; text-transform: uppercase; font-size: 12px; font-weight: 800; }
        .hero h1 { font-family: var(--font-ui); font-size: clamp(26px, 4vw, 40px); line-height: 1.15; margin: 10px 0 12px; }
        .hero p { margin: 0; color: #ffe7cc; max-width: 62ch; font-weight: 600; }
        .nav {
            display: flex; flex-wrap: wrap; gap: 8px; margin-top: 16px;
        }
        .nav a {
            background: #fff; color: #0a1d37; padding: 10px 14px; border-radius: 999px;
            font-size: .82rem; font-weight: 800; text-decoration: none;
            box-shadow: 0 8px 20px rgba(10, 29, 55, .08);
        }
        .panel {
            background: var(--paper); border: 1px solid var(--line); border-radius: 24px;
            padding: 24px; margin-top: 16px; box-shadow: 0 16px 40px rgba(16, 36, 61, .05);
        }
        .panel h2 { font-family: var(--font-ui); font-size: 26px; margin: 0; color: var(--navy); }
        .title-row {
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
            margin: 0 0 14px;
        }
        .title-row h2, .title-row h3 { margin: 0; flex: 1; min-width: 0; }
        .badge, [data-status] { display: none !important; }
        .eye-btn {
            flex: 0 0 auto; width: 38px; height: 38px; border-radius: 12px;
            border: 1px solid var(--line); background: #fff7ed; color: #c2410c;
            display: grid; place-items: center; cursor: pointer; padding: 0;
        }
        .eye-btn:hover, .reveal.is-open > .title-row .eye-btn {
            background: #ff6b00; color: #fff; border-color: #ff6b00;
        }
        .eye-btn svg { width: 18px; height: 18px; display: block; }
        .reveal-body { display: none; }
        .reveal.is-open > .reveal-body { display: block; }
        .table-wrap { overflow-x: auto; border-radius: 16px; border: 1px solid var(--line); }
        table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 920px; }
        thead th {
            background: linear-gradient(90deg, #0a1d37, #16325a); color: #fff;
            padding: 12px; font-size: 11px; letter-spacing: .04em; text-transform: uppercase; text-align: left;
            position: sticky; top: 0;
        }
        td { padding: 11px 12px; border-bottom: 1px solid var(--line); background: #fff; vertical-align: top; font-size: 14px; line-height: 1.5; }
        tbody tr:nth-child(even) td { background: #fffaf4; }
        td.sticky, th.sticky { position: sticky; left: 0; z-index: 1; }
        th.sticky { z-index: 2; }
        td.sticky { background: #fff7ed; font-weight: 800; color: #0a1d37; min-width: 160px; }
        .srno { width: 54px; font-weight: 800; color: #c2410c; text-align: center; }
        .chip {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 34px; padding: 4px 10px; border-radius: 999px; font-weight: 800; font-size: 12px;
        }
        .chip-yes { background: #dcfce7; color: #15803d; }
        .chip-no { background: #fee2e2; color: #b91c1c; }
        .chip-dash { background: #f1f5f9; color: #64748b; }
        .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
        .entity {
            background: #fff; border: 1px solid var(--line); border-radius: 20px; padding: 18px;
            box-shadow: 0 10px 24px rgba(16, 36, 61, .04);
        }
        .entity .no { color: #ff6b00; font-weight: 800; letter-spacing: .12em; font-size: 11px; text-transform: uppercase; }
        .entity h3 { margin: 0; font-size: 22px; }
        .entity .title-row { margin: 6px 0 0; }
        .entity .reveal-body { margin-top: 10px; }
        .label { display: block; color: #c2410c; font-size: 11px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; margin: 12px 0 6px; }
        .point-list { margin: 0; padding-left: 20px; }
        .point-list li { margin: 0 0 7px; line-height: 1.55; }
        .note {
            background: #fff7ed; border: 1px solid #fdba74; border-radius: 16px;
            padding: 14px 16px; font-weight: 600; line-height: 1.6; margin-top: 14px;
        }
        .q-list { list-style: none; margin: 0; padding: 0; display: grid; gap: 8px; }
        .q-list li {
            display: flex; gap: 12px; align-items: flex-start;
            background: #fff; border: 1px solid var(--line); border-radius: 14px; padding: 12px 14px;
        }
        .q-list b { color: #ff6b00; min-width: 28px; }
        .formula {
            margin-top: 16px; color: #fff; text-align: center; border-radius: 24px; padding: 28px 22px;
            background: linear-gradient(135deg, #ff6b00, #c2410c);
        }
        .formula small { display: block; letter-spacing: .14em; text-transform: uppercase; font-weight: 800; opacity: .85; }
        .formula strong { display: block; font-family: var(--font-ui); font-size: 20px; margin: 10px 0 12px; line-height: 1.45; }
        .footer { text-align: center; color: #64748b; padding: 18px 8px 0; font-size: 13px; line-height: 1.6; }
        strong { color: #c2410c; font-weight: 800; }
        .hero strong, .formula strong { color: inherit; }
        @media (max-width: 860px) {
            .hero { padding: 28px 20px; }
            .grid { grid-template-columns: 1fr; }
            .topbar { flex-direction: column; align-items: flex-start; }
        }
        @media print {
            .topbar, .nav { position: static; }
            .eye-btn { display: none !important; }
            .reveal-body { display: block !important; }
            body { background: #fff; }
        }
    </style>
</head>
<body>
@php
    $plan = is_array($company ?? null) ? $company : [];
    $biz = (string) ($plan['business_name'] ?? $facts['business_name'] ?? 'Business');
    $member = (string) ($plan['member_name'] ?? $facts['member_name'] ?? '');
    $headers = is_array($plan['headers'] ?? null) ? $plan['headers'] : [];
    $points = is_array($plan['points'] ?? null) ? $plan['points'] : [];
    $entities = is_array($plan['entities'] ?? null) ? $plan['entities'] : [];
    $expenses = is_array($plan['expenses'] ?? null) ? $plan['expenses'] : [];
    $owners = is_array($plan['owners'] ?? null) ? $plan['owners'] : [];
    $funding = is_array($plan['funding'] ?? null) ? $plan['funding'] : [];
    $stages = is_array($plan['stages'] ?? null) ? $plan['stages'] : [];
    $questions = is_array($plan['questions'] ?? null) ? $plan['questions'] : [];
    $initials = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $biz) ?: 'B', 0, 2));
    $mark = function ($value) {
        $raw = trim((string) $value);
        $low = strtolower($raw);
        if (in_array($low, ['yes', 'y', 'true'], true)) {
            return '<span class="chip chip-yes">Yes</span>';
        }
        if (in_array($low, ['no', 'n', 'false'], true)) {
            return '<span class="chip chip-no">No</span>';
        }
        if ($raw === '—' || $raw === '-') {
            return '<span class="chip chip-dash">—</span>';
        }

        return e($raw);
    };
@endphp
<div class="shell">
    <div class="topbar">
        <div class="brand-mark">
            <div class="logo-dot">{{ $initials }}</div>
            <div>
                <small>Business Navachar School™</small>
                <strong>{{ $biz }}</strong>
            </div>
        </div>
        <div class="top-meta">
            {{ $plan['kicker'] ?? 'BNS PVT LTD Session' }}<br>
            @if($member !== ''){{ $member }} · @endif Students &amp; Business Owners
        </div>
    </div>

    <section class="hero">
        <div class="kicker">{{ $plan['kicker'] ?? 'BNS PVT LTD Session' }}</div>
        <h1>{{ $plan['title'] ?? 'Company Structure Comparison' }}</h1>
        <p>{{ $plan['subtitle'] ?? 'Complete Comparison for Students & Business Owners' }}</p>
    </section>

    <nav class="nav">
        <a href="#compare">50-Point Comparison</a>
        <a href="#entities">5 Structures</a>
        <a href="#expenses">Business Expenses</a>
        <a href="#owners">Owner Comparison</a>
        <a href="#funding">Funding</a>
        <a href="#stages">Empire Journey</a>
        <a href="#questions">10 Questions</a>
    </nav>

    <section class="panel reveal is-open" id="compare">
        <div class="title-row">
            <h2>50-Point Complete Comparison</h2>
            <button type="button" class="eye-btn" aria-label="Hide details" aria-expanded="true"></button>
        </div>
        <div class="reveal-body">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th class="srno">#</th>
                            <th class="sticky">Point</th>
                            @foreach($headers as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($points as $row)
                            <tr>
                                <td class="srno">{{ $row['no'] }}</td>
                                <td class="sticky">{{ $row['point'] }}</td>
                                @foreach(($row['cells'] ?? []) as $cell)
                                    <td>{!! $mark($cell) !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="panel" id="entities">
        <h2 style="margin-bottom:14px">The 5 Business Structures</h2>
        <div class="grid">
            @foreach($entities as $entity)
                <article class="entity reveal" id="entity-{{ $entity['code'] ?? $entity['no'] }}">
                    <div class="no">{{ $entity['no'] }}</div>
                    <div class="title-row">
                        <h3>{{ $entity['title'] }}</h3>
                        <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
                    </div>
                    <div class="reveal-body">
                        <span class="label">Meaning</span>
                        <div>{{ $entity['meaning'] }}</div>
                        @if(!empty($entity['example']))
                            <span class="label">Example</span>
                            <div>{{ $entity['example'] }}</div>
                        @endif
                        @if(!empty($entity['features']))
                            <span class="label">Key Features</span>
                            <ul class="point-list">
                                @foreach($entity['features'] as $feature)
                                    <li>{{ $feature }}</li>
                                @endforeach
                            </ul>
                        @endif
                        @if(!empty($entity['examples']))
                            <span class="label">Example Businesses</span>
                            <ul class="point-list">
                                @foreach($entity['examples'] as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @endif
                        @if(!empty($entity['documents']))
                            <span class="label">Important Documents</span>
                            <ul class="point-list">
                                @foreach($entity['documents'] as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @endif
                        @if(!empty($entity['suitable']))
                            <span class="label">Suitable For</span>
                            <ul class="point-list">
                                @foreach($entity['suitable'] as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="panel reveal" id="expenses">
        <div class="title-row">
            <h2>Business Expense Comparison</h2>
            <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
        </div>
        <div class="reveal-body">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            @foreach(($expenses['headers'] ?? []) as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($expenses['rows'] ?? []) as $row)
                            <tr>
                                <td class="sticky">{{ $row['item'] }}</td>
                                @foreach(($row['cells'] ?? []) as $cell)
                                    <td>{!! $mark($cell) !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="note">{{ $plan['expense_note'] ?? '' }}</div>
        </div>
    </section>

    <section class="panel reveal" id="owners">
        <div class="title-row">
            <h2>Owner / Partner / Shareholder Comparison</h2>
            <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
        </div>
        <div class="reveal-body">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            @foreach(($owners['headers'] ?? []) as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($owners['rows'] ?? []) as $row)
                            <tr>
                                <td class="sticky">{{ $row['point'] }}</td>
                                @foreach(($row['cells'] ?? []) as $cell)
                                    <td>{!! $mark($cell) !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="panel reveal" id="funding">
        <div class="title-row">
            <h2>Funding Comparison</h2>
            <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
        </div>
        <div class="reveal-body">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            @foreach(($funding['headers'] ?? []) as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($funding['rows'] ?? []) as $row)
                            <tr>
                                <td class="sticky">{{ $row['source'] }}</td>
                                @foreach(($row['cells'] ?? []) as $cell)
                                    <td>{!! $mark($cell) !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="panel reveal" id="stages">
        <div class="title-row">
            <h2>From Business House to Business Empire</h2>
            <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
        </div>
        <div class="reveal-body">
            <div class="table-wrap">
                <table style="min-width:640px">
                    <thead>
                        <tr>
                            <th class="srno">Stage</th>
                            <th>Business Situation</th>
                            <th>Possible Structure</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stages as $stage)
                            <tr>
                                <td class="srno">{{ $stage['no'] }}</td>
                                <td>{{ $stage['situation'] }}</td>
                                <td><strong>{{ $stage['structure'] }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="note"><strong>Important Learning:</strong> {{ $plan['stage_note'] ?? '' }}</div>
        </div>
    </section>

    <section class="panel reveal" id="questions">
        <div class="title-row">
            <h2>10 Questions Before Selecting a Business Structure</h2>
            <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
        </div>
        <div class="reveal-body">
            <ol class="q-list">
                @foreach($questions as $index => $question)
                    <li><b>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</b><span>{{ $question }}</span></li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="formula">
        <small>BNS Key Learning</small>
        <strong>{{ $plan['formula'] ?? '' }}</strong>
        <div>{{ $plan['disclaimer'] ?? '' }}</div>
    </section>
    <div class="footer">Business Navachar School™ · PVT LTD Session</div>
</div>
<script>
(function () {
    const eyeOpen = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8 11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
    const eyeShut = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
    function paintEyes() {
        document.querySelectorAll('.eye-btn').forEach(function (btn) {
            const box = btn.closest('.reveal');
            const open = box && box.classList.contains('is-open');
            btn.innerHTML = open ? eyeShut : eyeOpen;
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            btn.setAttribute('aria-label', open ? 'Hide details' : 'Show details');
        });
    }
    document.querySelectorAll('.eye-btn').forEach(function (btn) {
        btn.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            const box = btn.closest('.reveal');
            if (!box) return;
            box.classList.toggle('is-open');
            paintEyes();
        });
    });
    paintEyes();
})();
</script>
</body>
</html>
