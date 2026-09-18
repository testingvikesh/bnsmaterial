<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $facts['business_name'] }} — 16 Sanskar Relationship Plan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Noto+Sans+Gujarati:wght@400;600;700&family=Noto+Sans+Devanagari:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #071422;
            --ink: #10243d;
            --brand: #ff6b00;
            --gold: #ffb800;
            --line: #eadfce;
            --paper: #fffdf9;
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: Poppins, "Noto Sans Gujarati", "Noto Sans Devanagari", Arial, sans-serif;
            background:
                radial-gradient(1100px 480px at 8% -8%, rgba(255,107,0,.16), transparent 55%),
                radial-gradient(900px 420px at 100% 0%, rgba(255,184,0,.14), transparent 50%),
                #f3eee6;
            color: var(--ink);
            font-size: 16px;
        }
        .shell { max-width: 1180px; margin: 0 auto; padding: 18px 18px 36px; }
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
        .language-buttons { display: flex; gap: 8px; flex-wrap: wrap; justify-content: flex-end; margin-bottom: 8px; }
        .language-buttons button {
            border: 1px solid rgba(255,255,255,.28);
            background: rgba(255,255,255,.08);
            color: #fff; padding: 8px 14px; border-radius: 999px;
            cursor: pointer; font-weight: 700; font-size: 13px;
        }
        .language-buttons button:hover,
        .language-buttons button.active { background: #fff; color: #071422; }
        .hero {
            margin-top: 18px; color: #fff; overflow: hidden; border-radius: 28px;
            padding: 42px 40px 36px;
            background: linear-gradient(135deg, #081526 0%, #123056 58%, #c2410c 160%);
            box-shadow: 0 30px 70px rgba(16, 36, 61, .12);
        }
        .kicker { color: #ffb800; letter-spacing: .16em; text-transform: uppercase; font-size: 12px; font-weight: 800; }
        .hero h1 { font-family: Poppins, "Noto Sans Gujarati", "Noto Sans Devanagari", Arial, sans-serif; font-size: clamp(32px, 5vw, 56px); margin: 8px 0 10px; line-height: 1.08; }
        .hero p { margin: 0; color: #ffe7cc; font-size: 18px; max-width: 54ch; }
        .chips { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 18px; }
        .chip { background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.16); padding: 8px 12px; border-radius: 999px; font-size: 13px; }
        .nav { display: flex; gap: 8px; overflow-x: auto; padding: 14px 0 0; }
        .nav a {
            flex: 0 0 auto; text-decoration: none; color: var(--ink);
            background: #fff; border: 1px solid var(--line);
            padding: 8px 12px; border-radius: 999px; font-size: 12px; font-weight: 800;
        }
        .panel {
            background: var(--paper); border: 1px solid var(--line); border-radius: 24px;
            padding: 24px; margin-top: 16px; box-shadow: 0 16px 40px rgba(16, 36, 61, .05);
        }
        .panel h2 {
            font-family: Poppins, "Noto Sans Gujarati", "Noto Sans Devanagari", Arial, sans-serif;
            font-size: 28px; margin: 0; color: var(--navy);
        }
        .title-row {
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
            margin: 0 0 14px;
        }
        .title-row h2, .title-row h3 { margin: 0; flex: 1; min-width: 0; }
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
        .activity.reveal.is-open > .reveal-body { display: block; }
        .facts { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .tile {
            background: linear-gradient(180deg, #fff, #fffaf4);
            border: 1px solid var(--line); border-radius: 18px; padding: 16px;
        }
        .tile.wide { grid-column: 1 / -1; }
        .label { display: block; color: #c2410c; font-size: 11px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 8px; }
        .point-list { margin: 0; padding-left: 20px; }
        .point-list li { margin: 0 0 8px; line-height: 1.6; }
        .point-list li:last-child { margin-bottom: 0; }
        .goal {
            background: #fff7ed; border: 1px solid #fdba74; border-radius: 18px;
            padding: 16px 18px; font-weight: 700; line-height: 1.55;
        }
        table { width: 100%; border-collapse: separate; border-spacing: 0; }
        thead th {
            background: linear-gradient(90deg, #0a1d37, #16325a); color: #fff;
            padding: 12px 12px; font-size: 12px; letter-spacing: .04em; text-transform: uppercase; text-align: left;
        }
        td { padding: 12px; border-bottom: 1px solid var(--line); background: #fff; }
        tbody tr:nth-child(even) td { background: #fffaf4; }
        .srno { width: 72px; font-weight: 800; color: #0a1d37; }
        .sanskar { color: #c2410c; font-weight: 800; }
        .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
        .activity {
            background: #fff; border: 1px solid var(--line); border-radius: 20px; padding: 18px;
            box-shadow: 0 10px 24px rgba(16, 36, 61, .04);
        }
        .activity .no { color: #ff6b00; font-weight: 800; letter-spacing: .12em; font-size: 11px; text-transform: uppercase; }
        .activity h3 { font-family: Poppins, "Noto Sans Gujarati", "Noto Sans Devanagari", Arial, sans-serif; margin: 0; font-size: 22px; }
        .activity .title-row { margin: 6px 0 0; }
        .activity .reveal-body { margin-top: 10px; }
        .meta { color: #64748b; font-size: 13px; font-weight: 700; margin: 0 0 10px; }
        .activity h4 { margin: 12px 0 6px; font-size: 13px; color: #c2410c; text-transform: uppercase; letter-spacing: .06em; }
        .invite {
            background: linear-gradient(180deg, #fff7ed, #fff);
            border: 1px dashed #fdba74; border-radius: 22px; padding: 24px;
            line-height: 1.7;
        }
        .invite p { margin: 0 0 12px; }
        .invite p:last-child { margin-bottom: 0; font-weight: 800; }
        .formula {
            margin-top: 16px; color: #fff; text-align: center; border-radius: 24px; padding: 28px 22px;
            background: linear-gradient(135deg, #ff6b00, #c2410c);
        }
        .formula small { display: block; letter-spacing: .14em; text-transform: uppercase; font-weight: 800; opacity: .85; }
        .formula strong { display: block; font-family: Poppins, "Noto Sans Gujarati", "Noto Sans Devanagari", Arial, sans-serif; font-size: 26px; margin: 8px 0 10px; }
        .footer { text-align: center; color: #64748b; padding: 18px 8px 0; }
        strong { color: #c2410c; font-weight: 800; }
        .hero strong, .formula strong, .chip strong { color: inherit; }
        @media (max-width: 860px) {
            .hero { padding: 28px 20px; }
            .facts, .grid { grid-template-columns: 1fr; }
            .topbar { flex-direction: column; align-items: flex-start; }
        }
        @media print {
            .topbar, .nav { position: static; }
            .language-buttons, .eye-btn { display: none !important; }
            .reveal-body { display: block !important; }
            body { background: #fff; }
        }
    </style>
</head>
<body>
@php
    $plan = is_array($sanskar ?? null) ? $sanskar : [];
    $biz = (string) ($plan['business_name'] ?? $facts['business_name'] ?? '');
    $introPoints = is_array($plan['intro_points'] ?? null) && $plan['intro_points'] !== []
        ? $plan['intro_points']
        : \App\Support\MaterialCopyPoints::from((string) ($plan['intro'] ?? $facts['intro'] ?? ''));
    $productPoints = is_array($plan['product_points'] ?? null) && $plan['product_points'] !== []
        ? $plan['product_points']
        : \App\Support\MaterialCopyPoints::from((string) ($plan['product'] ?? $facts['products'] ?? ''));
    $customers = is_array($plan['target_customers'] ?? null) ? $plan['target_customers'] : [];
    $calendar = is_array($plan['calendar'] ?? null) ? $plan['calendar'] : [];
    $activities = is_array($plan['activities'] ?? null) ? $plan['activities'] : [];
    $invitation = is_array($plan['invitation'] ?? null) ? $plan['invitation'] : [];
    $phrases = array_values(array_filter(array_merge([
        $biz,
        (string) ($plan['member_name'] ?? $facts['member_name'] ?? ''),
        (string) ($plan['category'] ?? $facts['category'] ?? ''),
        (string) ($facts['city'] ?? ''),
        \App\Support\MaterialSanskarCalendar::shortLine((string) ($plan['product'] ?? $facts['products'] ?? ''), 40),
    ], $customers), fn ($value) => $value !== '' && $value !== '—'));
    $em = fn ($text) => \App\Support\MaterialEmphasis::html((string) $text, $phrases);
    $initials = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $biz) ?: 'B', 0, 2));
    $languagePacks = $languages ?: ($plan['languages'] ?? []);
@endphp
<div class="shell">
    <div class="topbar">
        <div class="brand-mark">
            <div class="logo-dot">{{ $initials }}</div>
            <div>
                <small data-i18n="kicker">16 Sanskar Calendar</small>
                <strong>{{ $biz }}</strong>
            </div>
        </div>
        <div class="top-meta">
            <div class="language-buttons">
                <button class="active" onclick="changeLanguage('en', this)">ENGLISH</button>
                <button onclick="changeLanguage('gu', this)">ગુજરાતી</button>
                <button onclick="changeLanguage('hi', this)">हिन्दी</button>
                <button onclick="changeLanguage('mr', this)">मराठी</button>
            </div>
            Customer Relationship Plan<br>{{ $plan['business_type'] ?? $facts['category'] }} · {{ $plan['model'] ?? 'B2B + B2C' }}
        </div>
    </div>

    <section class="hero">
        <div class="kicker" data-i18n="heroKicker">Customer 16 Sanskar Relationship Plan</div>
        <h1>{{ $biz }}</h1>
        <p>{{ $plan['business_type'] ?? $facts['category'] }} · {{ $plan['model'] ?? 'B2B + B2C' }} · <span data-i18n="heroLine">16 meaningful experiences a year.</span></p>
        <div class="chips" id="customerChips">
            @foreach($customers as $customer)
                <span class="chip">{{ $customer }}</span>
            @endforeach
        </div>
    </section>

    <nav class="nav">
        <a href="#intro" data-i18n="navIntro">Introduction</a>
        <a href="#calendar" data-i18n="navCalendar">Yearly Calendar</a>
        <a href="#activities" data-i18n="navActivities">16 Activities</a>
        <a href="#invite" data-i18n="navInvite">Invitation</a>
    </nav>

    <section class="panel reveal" id="intro">
        <div class="title-row">
            <h2 data-i18n="snapshotTitle">Business Snapshot</h2>
            <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
        </div>
        <div class="reveal-body">
        <div class="facts">
            <div class="tile">
                <span class="label" data-i18n="labelBusinessName">Business Name</span>
                <strong>{{ $biz }}</strong>
            </div>
            <div class="tile">
                <span class="label" data-i18n="labelBusinessType">Business Type</span>
                <strong>{{ $plan['business_type'] ?? $facts['category'] }}</strong>
                <div style="margin-top:8px;color:#64748b;font-weight:700;">{{ $plan['model'] ?? '' }}</div>
            </div>
            <div class="tile wide">
                <span class="label" data-i18n="labelIntroduction">Business Introduction</span>
                @if($introPoints !== [])
                    <ul class="point-list">
                        @foreach($introPoints as $point)
                            <li>{!! $em($point) !!}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="tile wide">
                <span class="label" data-i18n="labelProducts">Main Products / Services</span>
                @if($productPoints !== [])
                    <ul class="point-list">
                        @foreach($productPoints as $point)
                            <li>{!! $em($point) !!}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="tile wide">
                <span class="label" data-i18n="labelCustomers">Target Customers</span>
                <ul class="point-list" id="customerList">
                    @foreach($customers as $customer)
                        <li>{!! $em($customer) !!}</li>
                    @endforeach
                </ul>
            </div>
            <div class="tile wide">
                <span class="label" data-i18n="labelGoal">Annual Goal</span>
                <div class="goal" id="annualGoal">{!! $em((string) ($plan['annual_goal'] ?? '')) !!}</div>
            </div>
        </div>
        </div>
    </section>

    <section class="panel reveal" id="calendar">
        <div class="title-row">
            <h2 data-i18n="calendarTitle">Yearly 16 Sanskar Calendar</h2>
            <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
        </div>
        <div class="reveal-body">
        <div style="overflow:auto;border-radius:16px;border:1px solid var(--line);">
            <table>
                <thead>
                    <tr>
                        <th data-i18n="thNo">Sr No</th>
                        <th data-i18n="thSanskar">Sanskar</th>
                        <th data-i18n="thActivity">Activity</th>
                    </tr>
                </thead>
                <tbody id="calendarBody">
                    @foreach($calendar as $index => $row)
                        <tr>
                            <td class="srno">{{ (int) ($row['no'] ?? ($index + 1)) }}</td>
                            <td class="sanskar" data-sanskar="{{ $row['sanskar'] ?? '' }}">{{ $row['sanskar'] ?? '' }}</td>
                            <td>{!! $em((string) ($row['activity'] ?? '')) !!}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>
    </section>

    <section class="panel reveal" id="activities">
        <div class="title-row">
            <h2 data-i18n="activitiesTitle">Complete Activity Details</h2>
            <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
        </div>
        <div class="reveal-body">
        <div class="grid" id="activityGrid">
            @foreach($activities as $activity)
                <article class="activity reveal" id="activity-{{ $activity['no'] ?? '' }}">
                    <div class="no">{{ str_pad((string) ($activity['no'] ?? ''), 2, '0', STR_PAD_LEFT) }} · <span data-sanskar="{{ $activity['sanskar'] ?? '' }}">{{ $activity['sanskar'] ?? '' }}</span></div>
                    <div class="title-row">
                        <h3>{{ $activity['title'] ?? '' }}</h3>
                        <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
                    </div>
                    <div class="reveal-body">
                    @if(!empty($activity['objective']))
                        <p class="meta">{!! $em((string) $activity['objective']) !!}</p>
                    @endif
                    @foreach(($activity['blocks'] ?? []) as $block)
                        <h4>{{ $block['label'] ?? '' }}</h4>
                        <ul class="point-list">
                            @foreach(($block['items'] ?? []) as $item)
                                <li>{!! $em((string) $item) !!}</li>
                            @endforeach
                        </ul>
                    @endforeach
                    @if(!empty($activity['memory']))
                        <h4 data-i18n="memory">Memory</h4>
                        <div>{!! $em((string) $activity['memory']) !!}</div>
                    @endif
                    @if(!empty($activity['budget']))
                        <h4 data-i18n="budget">Budget</h4>
                        <div>{{ $activity['budget'] }}</div>
                    @endif
                    @if(!empty($activity['certificate']))
                        <h4 data-i18n="certificate">Certificate</h4>
                        <div>Yes</div>
                    @endif
                    </div>
                </article>
            @endforeach
        </div>
        </div>
    </section>

    <section class="panel reveal" id="invite">
        <div class="title-row">
            <h2 data-i18n="inviteTitle">Customer Invitation</h2>
            <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
        </div>
        <div class="reveal-body">
        <div class="invite" id="inviteBox">
            <p>{{ $invitation['greeting'] ?? '' }}</p>
            <p>{{ $invitation['welcome'] ?? '' }}</p>
            <p>{{ $invitation['invite'] ?? '' }}</p>
            <p>{{ $invitation['place'] ?? '' }}<br>{{ $invitation['date'] ?? '' }}<br>{{ $invitation['time'] ?? '' }}</p>
            <p>{{ $invitation['benefit'] ?? '' }}</p>
            <p>{{ $invitation['close'] ?? '' }}</p>
            <p>{{ $invitation['signoff'] ?? '' }}</p>
        </div>
        </div>
    </section>

    <section class="formula">
        <small data-i18n="formulaKicker">Final Relationship Formula</small>
        <strong data-i18n="formulaText">{{ $plan['formula'] ?? '16 Activities → 16 Experiences → 16 Memories → Lifetime Customer' }}</strong>
        <p style="margin:0;opacity:.95;" id="closingText">{!! $em((string) ($plan['closing'] ?? '')) !!}</p>
    </section>

    <div class="footer" data-i18n="footer">{{ $biz }} — 16 Sanskar Customer Relationship Plan · {{ $generatedAt->format('d M Y H:i') }}</div>
</div>
<script>
(function () {
    const languagePacks = @json($languagePacks ?: [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    function applyLanguage(language) {
        const pack = languagePacks[language] || languagePacks.en || {};
        const ui = pack.ui || {};
        const sanskars = pack.sanskars || {};
        document.documentElement.lang = language;
        document.querySelectorAll('[data-i18n]').forEach(function (el) {
            const key = el.getAttribute('data-i18n');
            if (key && ui[key]) el.textContent = ui[key];
        });
        document.querySelectorAll('[data-sanskar]').forEach(function (el) {
            const key = el.getAttribute('data-sanskar');
            if (key && sanskars[key]) el.textContent = sanskars[key];
        });
    }
    window.changeLanguage = function (language, button) {
        document.querySelectorAll('.language-buttons button').forEach(function (btn) { btn.classList.remove('active'); });
        button.classList.add('active');
        applyLanguage(language);
    };
    applyLanguage('en');

    const eyeOpen = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
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
