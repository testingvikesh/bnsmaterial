<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $facts['business_name'] }} — 16 Sanskar Relationship Plan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Noto+Sans+Gujarati:wght@400;600;700;800&family=Noto+Sans+Devanagari:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #071422;
            --ink: #10243d;
            --brand: #ff6b00;
            --gold: #ffb800;
            --line: #eadfce;
            --paper: #fffdf9;
            --font-ui: Poppins, "Noto Sans Gujarati", "Noto Sans Devanagari", Arial, sans-serif;
            --font-gu: "Noto Sans Gujarati", "Nirmala UI", Poppins, Arial, sans-serif;
            --font-mr: "Noto Sans Devanagari", "Nirmala UI", Mangal, Poppins, Arial, sans-serif;
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        html[lang="gu"] { --font-ui: var(--font-gu); }
        html[lang="hi"],
        html[lang="mr"] { --font-ui: var(--font-mr); }
        html[lang="gu"] h1, html[lang="gu"] h2, html[lang="gu"] h3,
        html[lang="hi"] h1, html[lang="hi"] h2, html[lang="hi"] h3,
        html[lang="mr"] h1, html[lang="mr"] h2, html[lang="mr"] h3 {
            letter-spacing: 0;
            font-kerning: normal;
        }
        html[lang="gu"] .kicker, html[lang="gu"] .label, html[lang="gu"] .activity h4, html[lang="gu"] .formula small,
        html[lang="hi"] .kicker, html[lang="hi"] .label, html[lang="hi"] .activity h4, html[lang="hi"] .formula small,
        html[lang="mr"] .kicker, html[lang="mr"] .label, html[lang="mr"] .activity h4, html[lang="mr"] .formula small {
            letter-spacing: 0;
            text-transform: none;
        }
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
        .language-buttons { display: flex; gap: 6px; flex-wrap: nowrap; justify-content: flex-end; margin-bottom: 8px; }
        .language-buttons button {
            border: 1px solid rgba(255,255,255,.28);
            background: rgba(255,255,255,.08);
            color: #fff; padding: 6px 10px; border-radius: 999px;
            cursor: pointer; font-weight: 700; font-size: 12px; white-space: nowrap; flex-shrink: 0;
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
        .hero h1 { font-family: var(--font-ui); font-size: clamp(32px, 5vw, 56px); margin: 8px 0 10px; line-height: 1.08; }
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
            font-family: var(--font-ui);
            font-size: 28px; margin: 0; color: var(--navy);
        }
        .title-row {
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
            margin: 0 0 14px;
        }
        .title-row h2, .title-row h3 { margin: 0; flex: 1; min-width: 0; }
        .badge,
        [data-status] { display: none !important; }
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
        .activity h3 { font-family: var(--font-ui); margin: 0; font-size: 22px; }
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
        .formula strong { display: block; font-family: var(--font-ui); font-size: 26px; margin: 8px 0 10px; }
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
            <span data-i18n="topMeta">Customer Relationship Plan</span><br><span data-copy-text="business_type">{{ $plan['business_type'] ?? $facts['category'] }}</span> · <span data-copy-text="model">{{ $plan['model'] ?? 'B2B + B2C' }}</span>
        </div>
    </div>

    <section class="hero">
        <div class="kicker" data-i18n="heroKicker">Customer 16 Sanskar Relationship Plan</div>
        <h1>{{ $biz }}</h1>
        <p><span data-copy-text="business_type">{{ $plan['business_type'] ?? $facts['category'] }}</span> · <span data-copy-text="model">{{ $plan['model'] ?? 'B2B + B2C' }}</span> · <span data-i18n="heroLine">16 meaningful experiences a year.</span></p>
        <div class="chips" id="customerChips">
            @foreach($customers as $customerIndex => $customer)
                <span class="chip" data-copy-customer="{{ $customerIndex }}">{{ $customer }}</span>
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
                <strong data-copy-text="business_type">{{ $plan['business_type'] ?? $facts['category'] }}</strong>
                <div style="margin-top:8px;color:#64748b;font-weight:700;" data-copy-text="model">{{ $plan['model'] ?? '' }}</div>
            </div>
            <div class="tile wide">
                <span class="label" data-i18n="labelIntroduction">Business Introduction</span>
                @if($introPoints !== [])
                    <ul class="point-list" data-copy-intro>
                        @foreach($introPoints as $point)
                            <li>{!! $em($point) !!}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="tile wide">
                <span class="label" data-i18n="labelProducts">Main Products / Services</span>
                @if($productPoints !== [])
                    <ul class="point-list" data-copy-products>
                        @foreach($productPoints as $point)
                            <li>{!! $em($point) !!}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="tile wide">
                <span class="label" data-i18n="labelCustomers">Target Customers</span>
                <ul class="point-list" id="customerList">
                    @foreach($customers as $customerIndex => $customer)
                        <li data-copy-customer="{{ $customerIndex }}">{!! $em($customer) !!}</li>
                    @endforeach
                </ul>
            </div>
            <div class="tile wide">
                <span class="label" data-i18n="labelGoal">Annual Goal</span>
                <div class="goal" id="annualGoal" data-copy-text="annual_goal">{!! $em((string) ($plan['annual_goal'] ?? '')) !!}</div>
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
                            <td data-copy-calendar="{{ $index }}">{!! $em((string) ($row['activity'] ?? '')) !!}</td>
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
            @foreach($activities as $activityIndex => $activity)
                <article class="activity reveal" id="activity-{{ $activity['no'] ?? '' }}">
                    <div class="no">{{ str_pad((string) ($activity['no'] ?? ''), 2, '0', STR_PAD_LEFT) }} · <span data-sanskar="{{ $activity['sanskar'] ?? '' }}">{{ $activity['sanskar'] ?? '' }}</span></div>
                    <div class="title-row">
                        <h3 data-copy-activity-title="{{ $activityIndex }}">{{ $activity['title'] ?? '' }}</h3>
                        <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
                    </div>
                    <div class="reveal-body">
                    @if(!empty($activity['objective']))
                        <p class="meta" data-copy-activity-objective="{{ $activityIndex }}">{!! $em((string) $activity['objective']) !!}</p>
                    @endif
                    @foreach(($activity['blocks'] ?? []) as $blockIndex => $block)
                        <h4 data-copy-activity-label="{{ $activityIndex }}-{{ $blockIndex }}">{{ $block['label'] ?? '' }}</h4>
                        <ul class="point-list" data-copy-activity-items="{{ $activityIndex }}-{{ $blockIndex }}">
                            @foreach(($block['items'] ?? []) as $item)
                                <li>{!! $em((string) $item) !!}</li>
                            @endforeach
                        </ul>
                    @endforeach
                    @if(!empty($activity['memory']))
                        <h4 data-i18n="memory">Memory</h4>
                        <div data-copy-activity-memory="{{ $activityIndex }}">{!! $em((string) $activity['memory']) !!}</div>
                    @endif
                    @if(!empty($activity['budget']))
                        <h4 data-i18n="budget">Budget</h4>
                        <div>{{ $activity['budget'] }}</div>
                    @endif
                    @if(!empty($activity['certificate']))
                        <h4 data-i18n="certificate">Certificate</h4>
                        <div data-copy-activity-certificate="{{ $activityIndex }}">Yes</div>
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
            <p data-copy-invite="greeting">{{ $invitation['greeting'] ?? '' }}</p>
            <p data-copy-invite="welcome">{{ $invitation['welcome'] ?? '' }}</p>
            <p data-copy-invite="invite">{{ $invitation['invite'] ?? '' }}</p>
            <p data-copy-invite="place">{{ $invitation['place'] ?? '' }}</p>
            <p data-copy-invite="date">{{ $invitation['date'] ?? '' }}</p>
            <p data-copy-invite="time">{{ $invitation['time'] ?? '' }}</p>
            <p data-copy-invite="benefit">{{ $invitation['benefit'] ?? '' }}</p>
            <p data-copy-invite="close">{{ $invitation['close'] ?? '' }}</p>
            <p data-copy-invite="signoff">{{ $invitation['signoff'] ?? '' }}</p>
        </div>
        </div>
    </section>

    <section class="formula">
        <small data-i18n="formulaKicker">Final Relationship Formula</small>
        <strong data-i18n="formulaText">{{ $plan['formula'] ?? '16 Activities → 16 Experiences → 16 Memories → Lifetime Customer' }}</strong>
        <p style="margin:0;opacity:.95;" id="closingText" data-copy-text="closing">{!! $em((string) ($plan['closing'] ?? '')) !!}</p>
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
        applyCopy(pack.copy || {});
    }
    function applyCopy(copy) {
        const set = function (key, value) {
            document.querySelectorAll('[data-copy-text="' + key + '"]').forEach(function (el) {
                if (value) el.textContent = value;
            });
        };
        set('business_type', copy.business_type);
        set('model', copy.model);
        set('annual_goal', copy.annual_goal);
        set('closing', copy.closing);
        const fillList = function (selector, items) {
            const el = document.querySelector(selector);
            if (!el || !Array.isArray(items) || items.length === 0) return;
            el.innerHTML = items.map(function (item) {
                return '<li>' + String(item).replace(/&/g, '&amp;').replace(/</g, '&lt;') + '</li>';
            }).join('');
        };
        fillList('[data-copy-intro]', copy.intro_points);
        fillList('[data-copy-products]', copy.product_points);
        (copy.customers || []).forEach(function (name, index) {
            document.querySelectorAll('[data-copy-customer="' + index + '"]').forEach(function (el) {
                el.textContent = name;
            });
        });
        (copy.calendar || []).forEach(function (row, index) {
            const el = document.querySelector('[data-copy-calendar="' + index + '"]');
            if (el && row.activity) el.textContent = row.activity;
        });
        (copy.activities || []).forEach(function (activity, index) {
            const title = document.querySelector('[data-copy-activity-title="' + index + '"]');
            if (title && activity.title) title.textContent = activity.title;
            const objective = document.querySelector('[data-copy-activity-objective="' + index + '"]');
            if (objective && activity.objective) objective.textContent = activity.objective;
            const memory = document.querySelector('[data-copy-activity-memory="' + index + '"]');
            if (memory && activity.memory) memory.textContent = activity.memory;
            const cert = document.querySelector('[data-copy-activity-certificate="' + index + '"]');
            if (cert && activity.certificate) cert.textContent = activity.certificate;
            (activity.blocks || []).forEach(function (block, blockIndex) {
                const label = document.querySelector('[data-copy-activity-label="' + index + '-' + blockIndex + '"]');
                if (label && block.label) label.textContent = block.label;
                const list = document.querySelector('[data-copy-activity-items="' + index + '-' + blockIndex + '"]');
                if (list && Array.isArray(block.items)) {
                    list.innerHTML = block.items.map(function (item) {
                        return '<li>' + String(item).replace(/&/g, '&amp;').replace(/</g, '&lt;') + '</li>';
                    }).join('');
                }
            });
        });
        const invite = copy.invitation || {};
        Object.keys(invite).forEach(function (key) {
            document.querySelectorAll('[data-copy-invite="' + key + '"]').forEach(function (el) {
                el.textContent = invite[key];
            });
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
