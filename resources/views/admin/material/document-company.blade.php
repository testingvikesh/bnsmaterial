<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $facts['business_name'] ?? 'Business' }} — PVT LTD Session</title>
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
        html[lang="gu"] .kicker, html[lang="gu"] .label, html[lang="gu"] .no, html[lang="gu"] .formula small, html[lang="gu"] thead th,
        html[lang="hi"] .kicker, html[lang="hi"] .label, html[lang="hi"] .no, html[lang="hi"] .formula small, html[lang="hi"] thead th,
        html[lang="mr"] .kicker, html[lang="mr"] .label, html[lang="mr"] .no, html[lang="mr"] .formula small, html[lang="mr"] thead th {
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
        .language-buttons { display: flex; gap: 6px; flex-wrap: nowrap; justify-content: flex-end; margin-bottom: 8px; align-items: center; }
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
        tr[id], .entity, .q-list li { scroll-margin-top: 96px; }
        tr.is-jump td { outline: 2px solid #ff6b00; outline-offset: -2px; background: #fff3e0 !important; }
        .entity.is-jump, .q-list li.is-jump { outline: 2px solid #ff6b00; outline-offset: 2px; }
        .chip {
            display: inline-flex; align-items: center; justify-content: center;
            width: 28px; height: 28px; min-width: 28px; padding: 0;
            border-radius: 50%; font-weight: 800; line-height: 1;
        }
        .chip svg { width: 14px; height: 14px; display: block; }
        .chip-yes { background: #16a34a; color: #fff; }
        .chip-no { background: #dc2626; color: #fff; }
        .chip-dash {
            width: auto; min-width: 28px; height: 22px; padding: 0 8px;
            border-radius: 999px; background: #e2e8f0; color: #64748b; font-size: 12px;
        }
        .summary-btn {
            border: 1px solid rgba(255,255,255,.28);
            background: #ff6b00; color: #fff; padding: 6px 12px; border-radius: 999px;
            cursor: pointer; font-weight: 800; font-size: 12px; white-space: nowrap;
        }
        .summary-btn:hover { background: #fff; color: #071422; }
        .summary-overlay {
            position: fixed; inset: 0; z-index: 80; display: none;
            background: rgba(7, 20, 34, .48);
        }
        .summary-overlay.is-open { display: block; }
        .summary-card {
            position: absolute; top: 78px; right: 18px;
            width: min(440px, calc(100vw - 36px));
            max-height: calc(100vh - 110px);
            overflow: auto;
            background: #fffdf9; color: #10243d; border-radius: 22px;
            box-shadow: 0 24px 60px rgba(7, 20, 34, .28);
            padding: 16px 16px 20px;
        }
        .summary-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 10px; }
        .summary-head h3 { margin: 0; font-size: 18px; color: #071422; }
        .summary-close {
            width: 34px; height: 34px; border-radius: 10px; border: 1px solid var(--line);
            background: #fff7ed; color: #c2410c; font-size: 22px; line-height: 1; cursor: pointer;
        }
        .summary-group { margin-top: 12px; }
        .summary-group h4 { margin: 0 0 8px; color: #c2410c; font-size: 12px; letter-spacing: .04em; }
        .summary-list { display: grid; gap: 6px; }
        .summary-link {
            display: flex; gap: 10px; align-items: flex-start; text-decoration: none; color: #0a1d37;
            background: #fff; border: 1px solid var(--line); border-radius: 12px; padding: 8px 10px;
            font-size: 13px; font-weight: 700; line-height: 1.4;
        }
        .summary-link:hover { border-color: #ff6b00; background: #fff7ed; }
        .summary-link b { color: #ff6b00; min-width: 28px; }
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
        .hero strong, .formula strong, .brand-mark strong { color: inherit; }
        td strong, .point-list strong, .note strong, .q-list strong, .entity .reveal-body strong { color: #c2410c; font-weight: 800; }
        @media (max-width: 860px) {
            .hero { padding: 28px 20px; }
            .grid { grid-template-columns: 1fr; }
            .topbar { flex-direction: column; align-items: flex-start; }
        }
        @media print {
            .topbar, .nav { position: static; }
            .language-buttons, .eye-btn, .summary-btn, .summary-overlay { display: none !important; }
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
    $languagePacks = $languages ?: ($plan['languages'] ?? []);
    $phrases = array_values(array_filter(array_merge([
        $biz,
        $member,
        'LLP', 'MOA', 'AOA', 'IPO', 'GST', 'ROC', 'MCA',
        'Private Limited', 'Public Limited', 'Proprietorship', 'Partnership',
        'Pvt Ltd', 'Public Ltd', 'Shareholders', 'Directors', 'Liability',
        'Compliance', 'Funding', 'Capital Market', 'Private Limited Company', 'Public Limited Company',
    ], $headers), fn ($value) => $value !== '' && $value !== '—'));
    $em = fn ($text) => \App\Support\MaterialEmphasis::html((string) $text, $phrases);
    $yesIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7"/></svg>';
    $noIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6l12 12M18 6L6 18"/></svg>';
    $mark = function ($value) use ($em, $yesIcon, $noIcon) {
        $raw = trim((string) $value);
        $low = strtolower($raw);
        if (in_array($low, ['yes', 'y', 'true'], true)) {
            return '<span class="chip chip-yes" title="Yes" aria-label="Yes">'.$yesIcon.'</span>';
        }
        if (in_array($low, ['no', 'n', 'false'], true)) {
            return '<span class="chip chip-no" title="No" aria-label="No">'.$noIcon.'</span>';
        }
        if ($raw === '—' || $raw === '-') {
            return '<span class="chip chip-dash">—</span>';
        }

        return $em($raw);
    };
@endphp
<div class="shell">
    <div class="topbar">
        <div class="brand-mark">
            <div class="logo-dot">{{ $initials }}</div>
            <div>
                <small data-i18n="kicker">BNS PVT LTD Session</small>
                <strong>{{ $biz }}</strong>
            </div>
        </div>
        <div class="top-meta">
            <div class="language-buttons">
                <button type="button" class="summary-btn" id="summaryOpen" data-i18n="summaryBtn">Summary</button>
                <button class="active" onclick="changeLanguage('en', this)">ENGLISH</button>
                <button onclick="changeLanguage('gu', this)">ગુજરાતી</button>
                <button onclick="changeLanguage('hi', this)">हिन्दी</button>
                <button onclick="changeLanguage('mr', this)">मराठी</button>
            </div>
            <span data-i18n="kicker">{{ $plan['kicker'] ?? 'BNS PVT LTD Session' }}</span><br>
            @if($member !== ''){{ $member }} · @endif<span data-i18n="studentsOwners">Students &amp; Business Owners</span>
        </div>
    </div>

    <section class="hero">
        <div class="kicker" data-i18n="kicker">{{ $plan['kicker'] ?? 'BNS PVT LTD Session' }}</div>
        <h1 data-i18n="title">{{ $plan['title'] ?? 'Company Structure Comparison' }}</h1>
        <p data-i18n="subtitle">{{ $plan['subtitle'] ?? 'Complete Comparison for Students & Business Owners' }}</p>
    </section>

    <nav class="nav">
        <a href="#compare" data-i18n="navCompare">50-Point Comparison</a>
        <a href="#entities" data-i18n="navEntities">5 Structures</a>
        <a href="#expenses" data-i18n="navExpenses">Business Expenses</a>
        <a href="#owners" data-i18n="navOwners">Owner Comparison</a>
        <a href="#funding" data-i18n="navFunding">Funding</a>
        <a href="#stages" data-i18n="navStages">Empire Journey</a>
        <a href="#questions" data-i18n="navQuestions">10 Questions</a>
    </nav>

    <section class="panel reveal is-open" id="compare">
        <div class="title-row">
            <h2 data-i18n="compareTitle">50-Point Complete Comparison</h2>
            <button type="button" class="eye-btn" aria-label="Hide details" aria-expanded="true"></button>
        </div>
        <div class="reveal-body">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th class="srno" data-i18n="thNo">#</th>
                            <th class="sticky" data-i18n="thPoint">Point</th>
                            @foreach($headers as $headerIndex => $header)
                                <th data-copy-path="headers.{{ $headerIndex }}">{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($points as $rowIndex => $row)
                            <tr id="point-{{ $row['no'] }}">
                                <td class="srno">{{ $row['no'] }}</td>
                                <td class="sticky" data-copy-path="points.{{ $rowIndex }}.point">{!! $em($row['point'] ?? '') !!}</td>
                                @foreach(($row['cells'] ?? []) as $cellIndex => $cell)
                                    <td data-copy-path="points.{{ $rowIndex }}.cells.{{ $cellIndex }}">{!! $mark($cell) !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="panel" id="entities">
        <h2 style="margin-bottom:14px" data-i18n="entitiesTitle">The 5 Business Structures</h2>
        <div class="grid">
            @foreach($entities as $entityIndex => $entity)
                <article class="entity reveal" id="entity-{{ $entity['code'] ?? $entity['no'] }}">
                    <div class="no">{{ $entity['no'] }}</div>
                    <div class="title-row">
                        <h3 data-copy-path="entities.{{ $entityIndex }}.title">{!! $em($entity['title'] ?? '') !!}</h3>
                        <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
                    </div>
                    <div class="reveal-body">
                        <span class="label" data-i18n="labelMeaning">Meaning</span>
                        <div data-copy-path="entities.{{ $entityIndex }}.meaning">{!! $em($entity['meaning'] ?? '') !!}</div>
                        @if(!empty($entity['example']))
                            <span class="label" data-i18n="labelExample">Example</span>
                            <div data-copy-path="entities.{{ $entityIndex }}.example">{!! $em($entity['example']) !!}</div>
                        @endif
                        @if(!empty($entity['features']))
                            <span class="label" data-i18n="labelFeatures">Key Features</span>
                            <ul class="point-list">
                                @foreach($entity['features'] as $itemIndex => $feature)
                                    <li data-copy-path="entities.{{ $entityIndex }}.features.{{ $itemIndex }}">{!! $em($feature) !!}</li>
                                @endforeach
                            </ul>
                        @endif
                        @if(!empty($entity['examples']))
                            <span class="label" data-i18n="labelExamples">Example Businesses</span>
                            <ul class="point-list">
                                @foreach($entity['examples'] as $itemIndex => $item)
                                    <li data-copy-path="entities.{{ $entityIndex }}.examples.{{ $itemIndex }}">{!! $em($item) !!}</li>
                                @endforeach
                            </ul>
                        @endif
                        @if(!empty($entity['documents']))
                            <span class="label" data-i18n="labelDocuments">Important Documents</span>
                            <ul class="point-list">
                                @foreach($entity['documents'] as $itemIndex => $item)
                                    <li data-copy-path="entities.{{ $entityIndex }}.documents.{{ $itemIndex }}">{!! $em($item) !!}</li>
                                @endforeach
                            </ul>
                        @endif
                        @if(!empty($entity['suitable']))
                            <span class="label" data-i18n="labelSuitable">Suitable For</span>
                            <ul class="point-list">
                                @foreach($entity['suitable'] as $itemIndex => $item)
                                    <li data-copy-path="entities.{{ $entityIndex }}.suitable.{{ $itemIndex }}">{!! $em($item) !!}</li>
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
            <h2 data-i18n="expensesTitle">Business Expense Comparison</h2>
            <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
        </div>
        <div class="reveal-body">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            @foreach(($expenses['headers'] ?? []) as $headerIndex => $header)
                                <th data-copy-path="expenses.headers.{{ $headerIndex }}">{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($expenses['rows'] ?? []) as $rowIndex => $row)
                            <tr id="expense-{{ $rowIndex + 1 }}">
                                <td class="sticky" data-copy-path="expenses.rows.{{ $rowIndex }}.item">{!! $em($row['item'] ?? '') !!}</td>
                                @foreach(($row['cells'] ?? []) as $cellIndex => $cell)
                                    <td data-copy-path="expenses.rows.{{ $rowIndex }}.cells.{{ $cellIndex }}">{!! $mark($cell) !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="note" data-copy-path="expense_note">{!! $em($plan['expense_note'] ?? '') !!}</div>
        </div>
    </section>

    <section class="panel reveal" id="owners">
        <div class="title-row">
            <h2 data-i18n="ownersTitle">Owner / Partner / Shareholder Comparison</h2>
            <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
        </div>
        <div class="reveal-body">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            @foreach(($owners['headers'] ?? []) as $headerIndex => $header)
                                <th data-copy-path="owners.headers.{{ $headerIndex }}">{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($owners['rows'] ?? []) as $rowIndex => $row)
                            <tr id="owner-{{ $rowIndex + 1 }}">
                                <td class="sticky" data-copy-path="owners.rows.{{ $rowIndex }}.point">{!! $em($row['point'] ?? '') !!}</td>
                                @foreach(($row['cells'] ?? []) as $cellIndex => $cell)
                                    <td data-copy-path="owners.rows.{{ $rowIndex }}.cells.{{ $cellIndex }}">{!! $mark($cell) !!}</td>
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
            <h2 data-i18n="fundingTitle">Funding Comparison</h2>
            <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
        </div>
        <div class="reveal-body">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            @foreach(($funding['headers'] ?? []) as $headerIndex => $header)
                                <th data-copy-path="funding.headers.{{ $headerIndex }}">{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($funding['rows'] ?? []) as $rowIndex => $row)
                            <tr id="funding-{{ $rowIndex + 1 }}">
                                <td class="sticky" data-copy-path="funding.rows.{{ $rowIndex }}.source">{!! $em($row['source'] ?? '') !!}</td>
                                @foreach(($row['cells'] ?? []) as $cellIndex => $cell)
                                    <td data-copy-path="funding.rows.{{ $rowIndex }}.cells.{{ $cellIndex }}">{!! $mark($cell) !!}</td>
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
            <h2 data-i18n="stagesTitle">From Business House to Business Empire</h2>
            <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
        </div>
        <div class="reveal-body">
            <div class="table-wrap">
                <table style="min-width:640px">
                    <thead>
                        <tr>
                            <th class="srno" data-i18n="thStage">Stage</th>
                            <th data-i18n="thSituation">Business Situation</th>
                            <th data-i18n="thStructure">Possible Structure</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stages as $stageIndex => $stage)
                            <tr id="stage-{{ $stage['no'] }}">
                                <td class="srno">{{ $stage['no'] }}</td>
                                <td data-copy-path="stages.{{ $stageIndex }}.situation">{!! $em($stage['situation'] ?? '') !!}</td>
                                <td data-copy-path="stages.{{ $stageIndex }}.structure">{!! $em($stage['structure'] ?? '') !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="note"><strong data-i18n="stageNoteLabel">Important Learning:</strong> <span data-copy-path="stage_note">{!! $em($plan['stage_note'] ?? '') !!}</span></div>
        </div>
    </section>

    <section class="panel reveal" id="questions">
        <div class="title-row">
            <h2 data-i18n="questionsTitle">10 Questions Before Selecting a Business Structure</h2>
            <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
        </div>
        <div class="reveal-body">
            <ol class="q-list">
                @foreach($questions as $index => $question)
                    <li id="question-{{ $index + 1 }}"><b>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</b><span data-copy-path="questions.{{ $index }}">{!! $em($question) !!}</span></li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="formula">
        <small data-i18n="formulaKicker">BNS Key Learning</small>
        <strong data-copy-path="formula">{{ $plan['formula'] ?? '' }}</strong>
        <div data-copy-path="disclaimer">{!! $em($plan['disclaimer'] ?? '') !!}</div>
    </section>
    <div class="footer" data-i18n="footer">Business Navachar School™ · PVT LTD Session</div>
</div>
<div class="summary-overlay" id="summaryPanel">
    <div class="summary-card">
        <div class="summary-head">
            <h3 data-i18n="summaryTitle">All Points</h3>
            <button type="button" class="summary-close" id="summaryClose" aria-label="Close">×</button>
        </div>
        <div class="summary-group">
            <h4 data-i18n="compareTitle">50-Point Complete Comparison</h4>
            <div class="summary-list">
                @foreach($points as $rowIndex => $row)
                    <a class="summary-link" href="#point-{{ $row['no'] }}" data-jump="#point-{{ $row['no'] }}"><b>{{ str_pad((string) $row['no'], 2, '0', STR_PAD_LEFT) }}</b><span data-copy-path="points.{{ $rowIndex }}.point">{{ $row['point'] }}</span></a>
                @endforeach
            </div>
        </div>
        <div class="summary-group">
            <h4 data-i18n="entitiesTitle">The 5 Business Structures</h4>
            <div class="summary-list">
                @foreach($entities as $entityIndex => $entity)
                    <a class="summary-link" href="#entity-{{ $entity['code'] ?? $entity['no'] }}" data-jump="#entity-{{ $entity['code'] ?? $entity['no'] }}"><b>{{ $entity['no'] }}</b><span data-copy-path="entities.{{ $entityIndex }}.title">{{ $entity['title'] }}</span></a>
                @endforeach
            </div>
        </div>
        <div class="summary-group">
            <h4 data-i18n="expensesTitle">Business Expense Comparison</h4>
            <div class="summary-list">
                @foreach(($expenses['rows'] ?? []) as $rowIndex => $row)
                    <a class="summary-link" href="#expense-{{ $rowIndex + 1 }}" data-jump="#expense-{{ $rowIndex + 1 }}"><b>{{ str_pad((string) ($rowIndex + 1), 2, '0', STR_PAD_LEFT) }}</b><span data-copy-path="expenses.rows.{{ $rowIndex }}.item">{{ $row['item'] }}</span></a>
                @endforeach
            </div>
        </div>
        <div class="summary-group">
            <h4 data-i18n="ownersTitle">Owner / Partner / Shareholder Comparison</h4>
            <div class="summary-list">
                @foreach(($owners['rows'] ?? []) as $rowIndex => $row)
                    <a class="summary-link" href="#owner-{{ $rowIndex + 1 }}" data-jump="#owner-{{ $rowIndex + 1 }}"><b>{{ str_pad((string) ($rowIndex + 1), 2, '0', STR_PAD_LEFT) }}</b><span data-copy-path="owners.rows.{{ $rowIndex }}.point">{{ $row['point'] }}</span></a>
                @endforeach
            </div>
        </div>
        <div class="summary-group">
            <h4 data-i18n="fundingTitle">Funding Comparison</h4>
            <div class="summary-list">
                @foreach(($funding['rows'] ?? []) as $rowIndex => $row)
                    <a class="summary-link" href="#funding-{{ $rowIndex + 1 }}" data-jump="#funding-{{ $rowIndex + 1 }}"><b>{{ str_pad((string) ($rowIndex + 1), 2, '0', STR_PAD_LEFT) }}</b><span data-copy-path="funding.rows.{{ $rowIndex }}.source">{{ $row['source'] }}</span></a>
                @endforeach
            </div>
        </div>
        <div class="summary-group">
            <h4 data-i18n="stagesTitle">From Business House to Business Empire</h4>
            <div class="summary-list">
                @foreach($stages as $stageIndex => $stage)
                    <a class="summary-link" href="#stage-{{ $stage['no'] }}" data-jump="#stage-{{ $stage['no'] }}"><b>{{ str_pad((string) $stage['no'], 2, '0', STR_PAD_LEFT) }}</b><span data-copy-path="stages.{{ $stageIndex }}.situation">{{ $stage['situation'] }}</span></a>
                @endforeach
            </div>
        </div>
        <div class="summary-group">
            <h4 data-i18n="questionsTitle">10 Questions Before Selecting a Business Structure</h4>
            <div class="summary-list">
                @foreach($questions as $index => $question)
                    <a class="summary-link" href="#question-{{ $index + 1 }}" data-jump="#question-{{ $index + 1 }}"><b>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</b><span data-copy-path="questions.{{ $index }}">{{ $question }}</span></a>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script>
(function () {
    const languagePacks = @json($languagePacks ?: [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    const businessName = @json($biz, JSON_UNESCAPED_UNICODE);
    const memberName = @json($member, JSON_UNESCAPED_UNICODE);
    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
    function emphasize(text, phrases) {
        const raw = String(text || '').trim();
        if (!raw) return '';
        if (raw.length <= 48 && !/[.!?…।]/.test(raw.replace(/:$/, ''))) {
            return '<strong>' + escapeHtml(raw) + '</strong>';
        }
        let html = escapeHtml(raw);
        (phrases || []).slice().sort(function (a, b) { return String(b).length - String(a).length; }).forEach(function (phrase) {
            phrase = String(phrase || '').trim();
            if (!phrase || phrase === '—') return;
            const safe = escapeHtml(phrase).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            if (!safe) return;
            html = html.replace(new RegExp(safe, 'gi'), function (match) {
                return '<strong>' + match + '</strong>';
            });
        });
        return html;
    }
    function mark(value, ui, phrases) {
        const raw = String(value || '').trim();
        const low = raw.toLowerCase();
        const yesLabel = ui.chipYes || 'Yes';
        const noLabel = ui.chipNo || 'No';
        if (['yes', 'y', 'true'].indexOf(low) !== -1) {
            return '<span class="chip chip-yes" title="' + escapeHtml(yesLabel) + '" aria-label="' + escapeHtml(yesLabel) + '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7"/></svg></span>';
        }
        if (['no', 'n', 'false'].indexOf(low) !== -1) {
            return '<span class="chip chip-no" title="' + escapeHtml(noLabel) + '" aria-label="' + escapeHtml(noLabel) + '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6l12 12M18 6L6 18"/></svg></span>';
        }
        if (raw === '—' || raw === '-') {
            return '<span class="chip chip-dash">—</span>';
        }
        return emphasize(raw, phrases);
    }
    function getPath(obj, path) {
        return String(path || '').split('.').reduce(function (current, key) {
            if (current == null) return null;
            return current[key];
        }, obj);
    }
    function applyCopy(copy, ui) {
        const phrases = [businessName, memberName].concat(copy.headers || []).concat([
            'LLP', 'MOA', 'AOA', 'IPO', 'GST', 'ROC', 'MCA'
        ]).filter(Boolean);
        document.querySelectorAll('[data-copy-path]').forEach(function (el) {
            const value = getPath(copy, el.getAttribute('data-copy-path'));
            if (value == null || typeof value === 'object') return;
            if (el.closest('.summary-link')) {
                el.textContent = value;
                return;
            }
            if (el.tagName === 'TH' || (el.tagName === 'STRONG' && el.closest('.formula'))) {
                el.textContent = value;
                return;
            }
            el.innerHTML = mark(value, ui, phrases);
        });
    }
    function applyLanguage(language) {
        const pack = languagePacks[language] || languagePacks.en || {};
        const ui = pack.ui || {};
        document.documentElement.lang = language;
        document.querySelectorAll('[data-i18n]').forEach(function (el) {
            const key = el.getAttribute('data-i18n');
            if (key && ui[key]) el.textContent = ui[key];
        });
        applyCopy(pack.copy || {}, ui);
        paintEyes();
    }
    window.changeLanguage = function (language, button) {
        document.querySelectorAll('.language-buttons button').forEach(function (btn) { btn.classList.remove('active'); });
        button.classList.add('active');
        applyLanguage(language);
    };
    const eyeOpen = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
    const eyeShut = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
    function paintEyes() {
        const pack = languagePacks[document.documentElement.lang] || languagePacks.en || {};
        const ui = pack.ui || {};
        document.querySelectorAll('.eye-btn').forEach(function (btn) {
            const box = btn.closest('.reveal');
            const open = box && box.classList.contains('is-open');
            btn.innerHTML = open ? eyeShut : eyeOpen;
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            btn.setAttribute('aria-label', open ? (ui.hideDetails || 'Hide details') : (ui.showDetails || 'Show details'));
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
    applyLanguage('en');

    const summaryPanel = document.getElementById('summaryPanel');
    function closeSummary() {
        if (summaryPanel) summaryPanel.classList.remove('is-open');
    }
    function openSummary() {
        if (summaryPanel) summaryPanel.classList.add('is-open');
    }
    function jumpTo(selector) {
        const el = document.querySelector(selector);
        if (!el) return;
        const box = el.closest('.reveal');
        if (box) box.classList.add('is-open');
        paintEyes();
        closeSummary();
        document.querySelectorAll('.is-jump').forEach(function (node) { node.classList.remove('is-jump'); });
        el.classList.add('is-jump');
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    const summaryOpen = document.getElementById('summaryOpen');
    const summaryClose = document.getElementById('summaryClose');
    if (summaryOpen) summaryOpen.addEventListener('click', function (event) {
        event.preventDefault();
        openSummary();
    });
    if (summaryClose) summaryClose.addEventListener('click', function (event) {
        event.preventDefault();
        closeSummary();
    });
    if (summaryPanel) {
        summaryPanel.addEventListener('click', function (event) {
            if (event.target === summaryPanel) closeSummary();
        });
    }
    document.querySelectorAll('[data-jump]').forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            jumpTo(link.getAttribute('data-jump'));
        });
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closeSummary();
    });
})();
</script>
</body>
</html>
