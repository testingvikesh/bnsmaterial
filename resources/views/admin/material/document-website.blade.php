<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $facts['business_name'] }} — 32 Gun Website Draft</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Noto+Sans+Gujarati:wght@400;600;700;800&family=Noto+Sans+Devanagari:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #071422;
            --ink: #10243d;
            --brand: #ff6b00;
            --gold: #ffb800;
            --cream: #f6f0e8;
            --paper: #fffdf9;
            --line: #eadfce;
            --muted: #6b7280;
            --verified: #047857;
            --inferred: #1d4ed8;
            --suggested: #6d28d9;
            --required: #be123c;
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
        html[lang="gu"] .kicker, html[lang="gu"] .label, html[lang="gu"] .no,
        html[lang="hi"] .kicker, html[lang="hi"] .label, html[lang="hi"] .no,
        html[lang="mr"] .kicker, html[lang="mr"] .label, html[lang="mr"] .no {
            letter-spacing: 0;
            text-transform: none;
        }
        body {
            margin: 0;
            font-family: var(--font-ui);
            background:
                radial-gradient(1200px 500px at 10% -10%, rgba(255,107,0,.18), transparent 55%),
                radial-gradient(900px 420px at 100% 0%, rgba(255,184,0,.16), transparent 50%),
                #f3eee6;
            color: var(--ink);
            font-size: 16px;
        }
        .shell { max-width: 1240px; margin: 0 auto; padding: 18px 18px 28px; }
        .topbar {
            position: sticky; top: 12px; z-index: 40;
            display: flex; justify-content: space-between; align-items: center; gap: 16px;
            background: rgba(7, 20, 34, .92);
            color: #fff; padding: 14px 18px;
            border-radius: 22px;
            backdrop-filter: blur(16px);
            box-shadow: 0 18px 40px rgba(7, 20, 34, .22);
        }
        .brand-mark {
            display: flex; align-items: center; gap: 12px; min-width: 0;
        }
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
        .site-frame {
            margin-top: 18px;
            background: var(--paper);
            border: 1px solid rgba(255,255,255,.7);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 30px 70px rgba(16, 36, 61, .12);
        }
        .browser-dots { display: flex; gap: 8px; padding: 14px 18px; background: #fff7ed; border-bottom: 1px solid var(--line); }
        .browser-dots span { width: 10px; height: 10px; border-radius: 50%; background: #fdba74; }
        .browser-dots span:first-child { background: #fb7185; }
        .browser-dots span:last-child { background: #4ade80; }
        .hero-banner {
            position: relative;
            padding: 56px 48px 48px;
            color: #fff;
            background:
                linear-gradient(135deg, rgba(7,20,34,.2), rgba(7,20,34,.55)),
                linear-gradient(135deg, #081526 0%, #123056 58%, #c2410c 160%);
            overflow: hidden;
        }
        .hero-banner::after {
            content: "";
            position: absolute; right: -80px; top: -80px;
            width: 340px; height: 340px; border-radius: 50%;
            background: radial-gradient(circle, rgba(255,184,0,.35), transparent 68%);
        }
        .kicker { color: #ffb800; letter-spacing: .16em; text-transform: uppercase; font-size: 12px; font-weight: 800; }
        .hero-banner h1 {
            font-family: var(--font-ui);
            font-size: clamp(36px, 5vw, 64px);
            line-height: 1.05; margin: 10px 0 12px; max-width: 16ch;
        }
        .hero-sub { font-size: 20px; max-width: 46ch; color: #ffe7cc; margin: 0 0 18px; }
        .hero-cta {
            display: inline-flex; align-items: center; gap: 8px;
            background: #ff6b00; color: #fff; text-decoration: none;
            padding: 14px 22px; border-radius: 999px; font-weight: 800;
            box-shadow: 0 12px 24px rgba(255,107,0,.28);
        }
        .hero-chips { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 22px; }
        .chip {
            background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.16);
            padding: 8px 12px; border-radius: 999px; font-size: 13px;
        }
        .nav-strip {
            display: flex; gap: 8px; overflow-x: auto; padding: 14px 18px;
            background: #fff; border-bottom: 1px solid var(--line);
        }
        .nav-strip a {
            flex: 0 0 auto; text-decoration: none; color: var(--ink);
            background: #f8f1e7; border: 1px solid #f0e2cd;
            padding: 8px 12px; border-radius: 999px; font-size: 12px; font-weight: 800;
        }
        .nav-strip a:hover { background: #ff6b00; color: #fff; border-color: #ff6b00; }
        .content { padding: 28px; }
        .section-block { margin: 0 0 22px; }
        .section-block h2, .panel h2 {
            font-family: var(--font-ui);
            font-size: 28px; margin: 0; color: var(--navy);
        }
        .title-row, .section-kicker {
            display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;
            margin-bottom: 14px;
        }
        .title-row h2, .section-kicker .no { flex: 1; min-width: 0; }
        .eye-btn {
            flex: 0 0 auto; width: 38px; height: 38px; border-radius: 12px;
            border: 1px solid var(--line); background: #fff7ed; color: #c2410c;
            display: grid; place-items: center; cursor: pointer; padding: 0;
        }
        .eye-btn:hover, .reveal.is-open > .title-row .eye-btn, .reveal.is-open > .section-kicker .eye-btn {
            background: #ff6b00; color: #fff; border-color: #ff6b00;
        }
        .eye-btn svg { width: 18px; height: 18px; display: block; }
        .reveal-body { display: none; }
        .reveal.is-open > .reveal-body { display: block; }
        .no { color: #ff6b00; font-weight: 800; letter-spacing: .12em; font-size: 12px; text-transform: uppercase; }
        .panel > .no { display: block; margin-bottom: 12px; }
        .panel {
            background: #fff; border: 1px solid var(--line); border-radius: 24px;
            padding: 24px; box-shadow: 0 16px 40px rgba(16, 36, 61, .05);
        }
        .profile-grid, .seo-grid, .report, .block-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;
        }
        .tile {
            background: linear-gradient(180deg, #fff, #fffaf4);
            border: 1px solid var(--line); border-radius: 18px; padding: 16px;
        }
        .tile.wide { grid-column: span 3; }
        .label { display: block; color: #c2410c; font-size: 11px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 6px; }
        .badge,
        [data-status] { display: none !important; }
        .block-card .text { white-space: pre-wrap; line-height: 1.65; }
        .point-list { margin: 8px 0 0; padding-left: 20px; }
        .point-list li { margin: 0 0 8px; line-height: 1.6; }
        .point-list li:last-child { margin-bottom: 0; }
        .text strong, .tile strong, .hero-sub strong, .chip strong, .step .text strong {
            color: #c2410c;
            font-weight: 800;
        }
        .hero-banner .hero-sub strong, .cta-box .text strong, .step .text strong {
            color: #ffb800;
        }
        .steps { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
        .step {
            background: #071422; color: #fff; border-radius: 20px; padding: 18px;
            min-height: 160px;
        }
        .step b { display: block; color: #ffb800; margin-bottom: 8px; }
        .faq .block-card, .journey li {
            background: #fffaf4; border: 1px solid var(--line); border-radius: 16px; padding: 14px 16px;
        }
        .journey { list-style: none; margin: 0; padding: 0; display: grid; gap: 10px; }
        .journey li { display: flex; justify-content: space-between; gap: 12px; align-items: center; flex-wrap: wrap; }
        .report .tile { background: linear-gradient(180deg, #fff7ed, #fff); text-align: center; }
        .report strong { display: block; font-size: 22px; margin-top: 4px; }
        .contact-split { display: grid; grid-template-columns: 1.1fr .9fr; gap: 14px; }
        .cta-box {
            background: linear-gradient(135deg, #ff6b00, #c2410c); color: #fff;
            border-radius: 22px; padding: 28px;
        }
        .cta-box h3 { font-family: var(--font-ui); font-size: 32px; margin: 8px 0 10px; }
        @media (max-width: 900px) {
            .hero-banner { padding: 36px 22px; }
            .content { padding: 16px; }
            .profile-grid, .seo-grid, .report, .block-grid, .steps, .contact-split { grid-template-columns: 1fr; }
            .tile.wide { grid-column: auto; }
            .topbar { flex-direction: column; align-items: flex-start; }
        }
        @media print {
            .topbar, .nav-strip { position: static; transform: none; }
            .language-buttons, .eye-btn { display: none !important; }
            .reveal-body { display: block !important; }
            body { margin: 0; background: #fff; }
        }
    </style>
</head>
<body>
@php
    $plan = is_array($website ?? null) ? $website : [];
    $sections = is_array($plan['sections'] ?? null) ? $plan['sections'] : [];
    $profile = is_array($plan['profile'] ?? null) ? $plan['profile'] : [];
    $seo = is_array($plan['seo'] ?? null) ? $plan['seo'] : [];
    $journey = is_array($plan['journey'] ?? null) ? $plan['journey'] : [];
    $report = is_array($plan['report'] ?? null) ? $plan['report'] : [];
    $biz = $plan['business_name'] ?? $facts['business_name'] ?? 'Business';
    $hero = collect($sections)->firstWhere('code', 'hero') ?: ($sections[0] ?? []);
    $heroMap = [];
    foreach (($hero['blocks'] ?? []) as $block) {
        $heroMap[strtolower((string) ($block['label'] ?? ''))] = (string) ($block['text'] ?? '');
    }
    $headline = $heroMap['headline'] ?? $biz;
    $subheadline = $heroMap['subheadline'] ?? ($facts['category'] ?? '');
    $tagline = $heroMap['tagline'] ?? '';
    $usp = $heroMap['usp'] ?? '';
    $cta = $heroMap['cta'] ?? 'Enquire Now';
    if (mb_strlen($subheadline) > 80) {
        $place = trim((string) ($facts['location'] ?? $facts['city'] ?? ''));
        $subheadline = trim(($facts['category'] ?? 'Business').($place !== '' && $place !== '—' ? ' · '.$place : ''));
    }
    if (mb_strlen($usp) > 110) {
        $usp = \App\Support\MaterialWebsiteDraft::shortLine((string) ($facts['intro'] ?? $usp), 90);
    }
    $initials = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', (string) $biz) ?: 'B', 0, 2));
    $phrases = array_values(array_filter([
        (string) $biz,
        (string) ($plan['member_name'] ?? $facts['member_name'] ?? ''),
        (string) ($facts['category'] ?? ''),
        (string) ($facts['location'] ?? ''),
        (string) ($facts['city'] ?? ''),
        \App\Support\MaterialWebsiteDraft::shortLine((string) ($facts['products'] ?? ''), 40),
    ], fn ($value) => $value !== '' && $value !== '—'));
    $em = fn ($text) => \App\Support\MaterialEmphasis::html((string) $text, $phrases);
    $copyHtml = function ($item) use ($em) {
        $item = is_array($item) ? $item : ['text' => (string) $item];
        $text = (string) ($item['text'] ?? '');
        $points = is_array($item['points'] ?? null) ? $item['points'] : [];
        if ($points === []) {
            $points = \App\Support\MaterialCopyPoints::from($text);
        }
        if (count($points) > 1) {
            $html = '<ul class="point-list">';
            foreach ($points as $point) {
                $html .= '<li>'.$em($point).'</li>';
            }

            return $html.'</ul>';
        }

        return '<div class="text">'.$em($text).'</div>';
    };
    $detailKeys = ['member_name', 'member_id', 'phone', 'address', 'website', 'instagram', 'facebook', 'linkedin', 'youtube', 'google_business', 'city'];
    foreach ($detailKeys as $key) {
        $value = trim((string) ($facts[$key] ?? ''));
        if ($value === '' || $value === '—' || isset($profile[$key])) {
            continue;
        }
        $profile = [$key => ['text' => $value, 'status' => 'verified', 'points' => []]] + $profile;
    }
    $wideKeys = ['business_description', 'main_product', 'main_services', 'customer_need', 'market_context', 'address'];
    $languagePacks = $languages ?: ($plan['languages'] ?? []);
@endphp
<div class="shell">
    <header class="topbar">
        <div class="brand-mark">
            <div class="logo-dot">{{ $initials }}</div>
            <div>
                <small data-i18n="kicker">BNS 32 Gun — Complete Business Website Draft</small>
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
            <div data-i18n="topMeta">3 inputs → 32 sections → member approval → publish</div>
        </div>
    </header>

    <div class="site-frame">
        <div class="browser-dots"><span></span><span></span><span></span></div>
        <section class="hero-banner" id="sec-hero">
            <div class="kicker" data-copy-text="kicker">{{ $facts['category'] ?: 'Website preview' }}</div>
            <h1 data-copy-text="headline">{{ $headline }}</h1>
            <p class="hero-sub" data-copy-text="tagline">{!! $em($tagline ?: $subheadline) !!}</p>
            <a class="hero-cta" href="#sec-cta" data-copy-text="cta">{{ $cta }}</a>
            <div class="hero-chips">
                @if($subheadline)<span class="chip" data-copy-text="subheadline">{!! $em($subheadline) !!}</span>@endif
                @if($usp)<span class="chip" data-copy-text="usp">{!! $em(\Illuminate\Support\Str::limit($usp, 88)) !!}</span>@endif
            </div>
        </section>

        <nav class="nav-strip">
            <a href="#profile" data-i18n="navProfile">Profile</a>
            @foreach($sections as $section)
                <a href="#sec-{{ $section['code'] }}" data-nav-section="{{ $section['code'] }}" data-section-no="{{ $section['no'] }}">{{ $section['no'] }} {{ $section['title'] }}</a>
            @endforeach
            <a href="#seo" data-i18n="navSeo">SEO</a>
            <a href="#journey" data-i18n="navJourney">Journey</a>
            <a href="#report" data-i18n="navReport">Report</a>
        </nav>

        <div class="content">
            <section class="section-block reveal" id="profile">
                <div class="title-row">
                    <h2 data-i18n="profileTitle">Profile</h2>
                    <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
                </div>
                <div class="reveal-body">
                <div class="panel">
                    <span class="no" data-i18n="profileKicker">Business identity</span>
                    <div class="profile-grid">
                        @foreach($profile as $key => $item)
                            @php $item = is_array($item) ? $item : ['text' => $item, 'status' => 'inferred']; @endphp
                            <div class="tile {{ in_array($key, $wideKeys, true) ? 'wide' : '' }}">
                                <span class="label" data-profile-label="{{ $key }}">{{ str_replace('_', ' ', $key) }}</span>
                                <div data-copy-profile="{{ $key }}">{!! $copyHtml($item) !!}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                </div>
            </section>

            @foreach($sections as $section)
                @php
                    $code = $section['code'] ?? '';
                    $blocks = $section['blocks'] ?? [];
                    $layout = match ($code) {
                        'hero' => 'hero-repeat',
                        'process' => 'steps',
                        'faq', 'knowledge' => 'faq',
                        'cta' => 'cta',
                        'contact', 'digital' => 'contact',
                        default => 'cards',
                    };
                @endphp
                <section class="section-block reveal" id="sec-{{ $code }}">
                    <div class="section-kicker title-row">
                        <span class="no" data-section-title="{{ $code }}" data-section-no="{{ $section['no'] }}">{{ $section['no'] }}. {{ $section['title'] }}</span>
                        <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
                    </div>
                    <div class="reveal-body">

                    @if($layout === 'hero-repeat')
                        <div class="panel">
                            <h2 data-section-heading="{{ $code }}">Hero Banner</h2>
                            <div class="block-grid">
                                @foreach($blocks as $blockIndex => $block)
                                    <div class="tile block-card">
                                        @if(($block['label'] ?? '') !== '')
                                            <span class="label" data-block-label="{{ $block['label'] }}">{{ $block['label'] }}</span>
                                        @endif
                                        <div data-copy-section="{{ $code }}" data-copy-index="{{ $blockIndex }}">{!! $copyHtml($block) !!}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @elseif($layout === 'steps')
                        <div class="steps">
                            @foreach($blocks as $blockIndex => $block)
                                <div class="step">
                                    <b data-block-label="{{ $block['label'] ?? '' }}">{{ $block['label'] ?? '' }}</b>
                                    <div class="text" data-copy-section="{{ $code }}" data-copy-index="{{ $blockIndex }}">{!! $em($block['text'] ?? '') !!}</div>
                                </div>
                            @endforeach
                        </div>
                    @elseif($layout === 'cta')
                        <div class="cta-box">
                            <span class="kicker" data-i18n="readyCta">Ready to talk</span>
                            <h3 data-copy-text="cta">{{ $blocks[0]['text'] ?? $cta }}</h3>
                            @foreach($blocks as $blockIndex => $block)
                                <p class="text" style="margin:0 0 8px" data-copy-section="{{ $code }}" data-copy-index="{{ $blockIndex }}">@if($block['label'] ?? '')<strong data-block-label="{{ $block['label'] }}">{{ $block['label'] }}</strong>: @endif{!! $em($block['text'] ?? '') !!}</p>
                            @endforeach
                        </div>
                    @elseif($layout === 'contact')
                        <div class="contact-split">
                            @foreach($blocks as $blockIndex => $block)
                                <div class="tile block-card">
                                    @if(($block['label'] ?? '') !== '')                                        <span class="label" data-block-label="{{ $block['label'] }}">{{ $block['label'] }}</span>@endif
                                        <div data-copy-section="{{ $code }}" data-copy-index="{{ $blockIndex }}">{!! $copyHtml($block) !!}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="panel {{ $layout }}">
                            <h2 data-section-heading="{{ $code }}">{{ $section['title'] }}</h2>
                            <div class="{{ $layout === 'faq' ? 'faq' : 'block-grid' }}">
                                @foreach($blocks as $blockIndex => $block)
                                    <div class="tile block-card">
                                        @if(($block['label'] ?? '') !== '')
                                            <span class="label" data-block-label="{{ $block['label'] }}">{{ $block['label'] }}</span>
                                        @endif
                                        <div data-copy-section="{{ $code }}" data-copy-index="{{ $blockIndex }}">{!! $copyHtml($block) !!}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    </div>
                </section>
            @endforeach

            <section class="section-block reveal" id="seo">
                <div class="title-row">
                    <h2 data-i18n="seoTitle">SEO Auto-Generation</h2>
                    <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
                </div>
                <div class="reveal-body">
                <div class="panel">
                    <span class="no" data-i18n="seoKicker">Search</span>
                    <div class="seo-grid">
                        @foreach($seo as $key => $value)
                            <div class="tile">
                                <span class="label" data-block-label="{{ str_replace('_', ' ', $key) }}" data-seo-key="{{ $key }}">{{ str_replace('_', ' ', $key) }}</span>
                                <div class="text" data-copy-seo="{{ $key }}">{!! $em((string) $value) !!}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                </div>
            </section>

            <section class="section-block reveal" id="journey">
                <div class="title-row">
                    <h2 data-i18n="journeyTitle">Customer Journey Audit</h2>
                    <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
                </div>
                <div class="reveal-body">
                <div class="panel">
                    <span class="no" data-i18n="journeyKicker">Audit</span>
                    <ol class="journey">
                        @foreach($journey as $journeyIndex => $item)
                            <li>
                                <div><strong data-copy-journey-q="{{ $journeyIndex }}">{{ $item['question'] }}</strong><div class="text" data-copy-journey-a="{{ $journeyIndex }}">{!! $em($item['answer'] ?? '') !!}</div></div>
                            </li>
                        @endforeach
                    </ol>
                </div>
                </div>
            </section>

            <section class="section-block reveal" id="report">
                <div class="title-row">
                    <h2 data-i18n="reportTitle">Website Completion Report</h2>
                    <button type="button" class="eye-btn" aria-label="Show details" aria-expanded="false"></button>
                </div>
                <div class="reveal-body">
                <div class="panel">
                    <span class="no" data-i18n="reportKicker">Readiness</span>
                    <div class="report">
                        <div class="tile"><span class="label" data-i18n="reportBusiness">Business Profile</span><strong data-copy-report="business_profile">{{ $report['business_profile'] ?? 'Pending' }}</strong></div>
                        <div class="tile"><span class="label" data-i18n="reportContent">Content</span><strong data-copy-report="content">{{ $report['content'] ?? '0/32 Sections' }}</strong></div>
                        <div class="tile"><span class="label" data-i18n="reportUsp">USP</span><strong data-copy-report="usp">{{ $report['usp'] ?? 'Pending' }}</strong></div>
                        <div class="tile"><span class="label" data-i18n="reportProduct">Product</span><strong data-copy-report="product">{{ $report['product'] ?? 'Pending' }}</strong></div>
                        <div class="tile"><span class="label" data-i18n="reportTrust">Trust</span><strong data-copy-report="trust">{{ $report['trust'] ?? 'Pending' }}</strong></div>
                        <div class="tile"><span class="label" data-i18n="reportSeo">SEO</span><strong data-copy-report="seo">{{ $report['seo'] ?? 'Pending' }}</strong></div>
                        <div class="tile"><span class="label" data-i18n="reportContact">Contact</span><strong data-copy-report="contact">{{ $report['contact'] ?? 'Pending' }}</strong></div>
                        <div class="tile"><span class="label" data-i18n="reportOverall">Overall Readiness</span><strong data-copy-report="overall">{{ $report['overall'] ?? '0%' }}</strong></div>
                    </div>
                </div>
                </div>
            </section>
        </div>
    </div>
</div>
<script>
(function () {
    const languagePacks = @json($languagePacks ?: [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    function applyLanguage(language) {
        const pack = languagePacks[language] || languagePacks.en || {};
        const ui = pack.ui || {};
        const sections = pack.sections || {};
        const status = pack.status || {};
        const profile = pack.profile || {};
        const blocks = pack.blocks || {};
        document.documentElement.lang = language;
        document.querySelectorAll('[data-i18n]').forEach(function (el) {
            const key = el.getAttribute('data-i18n');
            if (key && ui[key]) el.textContent = ui[key];
        });
        document.querySelectorAll('[data-section-title]').forEach(function (el) {
            const code = el.getAttribute('data-section-title');
            const no = el.getAttribute('data-section-no') || '';
            if (code && sections[code]) el.textContent = (no ? no + '. ' : '') + sections[code];
        });
        document.querySelectorAll('[data-section-heading]').forEach(function (el) {
            const code = el.getAttribute('data-section-heading');
            if (code && sections[code]) el.textContent = sections[code];
        });
        document.querySelectorAll('[data-nav-section]').forEach(function (el) {
            const code = el.getAttribute('data-nav-section');
            const no = el.getAttribute('data-section-no') || '';
            if (code && sections[code]) el.textContent = (no ? no + ' ' : '') + sections[code];
        });
        document.querySelectorAll('[data-status]').forEach(function (el) {
            const key = el.getAttribute('data-status');
            if (key && status[key]) el.textContent = status[key];
        });
        document.querySelectorAll('[data-profile-label]').forEach(function (el) {
            const key = el.getAttribute('data-profile-label');
            if (key && profile[key]) el.textContent = profile[key];
        });
        document.querySelectorAll('[data-block-label]').forEach(function (el) {
            const key = el.getAttribute('data-block-label');
            if (key && blocks[key]) el.textContent = blocks[key];
        });
        applyCopy(pack.copy || {});
    }
    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
    function renderItem(el, item, asText) {
        if (!el || !item) return;
        const text = item.text || (typeof item === 'string' ? item : '');
        const points = item.points || [];
        if (asText) {
            el.textContent = text;
            return;
        }
        if (el.tagName === 'P') {
            const label = el.querySelector('[data-block-label]');
            const prefix = label ? label.outerHTML + ': ' : '';
            el.innerHTML = prefix + escapeHtml(text).replace(/\n/g, '<br>');
            return;
        }
        if (points.length > 1) {
            el.innerHTML = '<ul class="point-list">' + points.map(function (point) {
                return '<li>' + escapeHtml(point) + '</li>';
            }).join('') + '</ul>';
            return;
        }
        el.innerHTML = '<div class="text">' + escapeHtml(text).replace(/\n/g, '<br>') + '</div>';
    }
    function applyCopy(copy) {
        const set = function (key, value) {
            document.querySelectorAll('[data-copy-text="' + key + '"]').forEach(function (el) {
                if (value) el.textContent = value;
            });
        };
        set('kicker', copy.kicker);
        set('headline', copy.headline);
        set('tagline', copy.tagline);
        set('cta', copy.cta);
        set('subheadline', copy.subheadline);
        set('usp', copy.usp);
        document.querySelectorAll('[data-copy-profile]').forEach(function (el) {
            const key = el.getAttribute('data-copy-profile');
            renderItem(el, (copy.profile || {})[key]);
        });
        document.querySelectorAll('[data-copy-section]').forEach(function (el) {
            const code = el.getAttribute('data-copy-section');
            const index = parseInt(el.getAttribute('data-copy-index') || '0', 10);
            const item = ((copy.sections || {})[code] || [])[index];
            if (!item) return;
            if (el.tagName === 'P') {
                const label = el.querySelector('[data-block-label]');
                el.innerHTML = (label ? label.outerHTML + ': ' : '') + escapeHtml(item.text || '').replace(/\n/g, '<br>');
                return;
            }
            if (el.classList.contains('text') && el.tagName === 'DIV') {
                el.textContent = item.text || '';
                return;
            }
            renderItem(el, item);
        });
        document.querySelectorAll('[data-copy-seo]').forEach(function (el) {
            const key = el.getAttribute('data-copy-seo');
            const value = (copy.seo || {})[key];
            if (typeof value === 'string') el.textContent = value;
        });
        document.querySelectorAll('[data-copy-journey-q]').forEach(function (el) {
            const index = parseInt(el.getAttribute('data-copy-journey-q') || '0', 10);
            const item = (copy.journey || [])[index];
            if (item && item.question) el.textContent = item.question;
        });
        document.querySelectorAll('[data-copy-journey-a]').forEach(function (el) {
            const index = parseInt(el.getAttribute('data-copy-journey-a') || '0', 10);
            const item = (copy.journey || [])[index];
            if (item && item.answer) el.textContent = item.answer;
        });
        document.querySelectorAll('[data-copy-report]').forEach(function (el) {
            const key = el.getAttribute('data-copy-report');
            const value = (copy.report || {})[key];
            if (typeof value === 'string') el.textContent = value;
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
