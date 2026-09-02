<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $facts['business_name'] }} — Reverse Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;600;700&family=Noto+Sans+Gujarati:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0a1d37;
            --ink: #0a1d37;
            --brand: #ff6b00;
            --gold: #ffb800;
            --line: #e8edf3;
            --soft: #f4f7fb;
            --muted: #64748b;
        }
        * { box-sizing: border-box; }
        body {
            font-family: "Plus Jakarta Sans", "Noto Sans Gujarati", "Noto Sans Devanagari", Arial, sans-serif;
            background: #eef3f8;
            margin: 0;
            color: #0a1d37;
            font-size: 16px;
        }
        .page {
            max-width: 1680px;
            margin: 0 auto;
            min-height: 100vh;
        }
        .hero {
            position: sticky;
            top: 0;
            z-index: 40;
            background: linear-gradient(135deg, #050b14 0%, #0a1d37 72%, #123056 100%);
            color: #fff;
            padding: 20px 28px 18px;
            border-bottom: 3px solid #ff6b00;
            box-shadow: 0 16px 40px rgba(10, 29, 55, .18);
        }
        .hero-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            flex-wrap: wrap;
        }
        .hero small {
            display: block;
            color: #ffb800;
            letter-spacing: .08em;
            text-transform: uppercase;
            font-weight: 800;
            font-size: 12px;
        }
        .hero h1 { margin: 6px 0 6px; font-size: 34px; font-weight: 800; }
        #pageSubtitle { margin: 0; color: #ffb800; font-size: 17px; font-weight: 700; }
        .language-buttons { display: flex; gap: 8px; flex-wrap: wrap; }
        .language-buttons button {
            border: 1px solid rgba(255,255,255,.28);
            background: rgba(255,255,255,.08);
            color: #fff;
            padding: 10px 16px;
            border-radius: 999px;
            cursor: pointer;
            font-weight: 700;
            font-size: 14px;
        }
        .language-buttons button:hover,
        .language-buttons button.active { background: #fff; color: #0a1d37; }
        .facts {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding: 20px 24px 8px;
        }
        .fact-card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 16px 18px;
            box-shadow: 0 10px 28px rgba(10, 29, 55, .06);
            position: relative;
            overflow: hidden;
        }
        .fact-card::before {
            content: "";
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, #ffb800, #ff6b00);
        }
        .fact-card.wide { grid-column: 1 / -1; }
        .fact-card > span,
        .fact-card .label {
            display: block;
            color: #ff6b00;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .fact-card > strong,
        .fact-card .value {
            display: block;
            font-size: 18px;
            line-height: 1.45;
            color: #0a1d37;
            font-weight: 700;
        }
        .point-list {
            margin: 0;
            padding-left: 22px;
        }
        .point-list li {
            margin: 0 0 10px;
            line-height: 1.65;
            font-size: 16px;
            font-weight: 500;
            color: #0a1d37;
        }
        .point-list li:last-child { margin-bottom: 0; }
        .point-list strong {
            display: inline;
            font-weight: 800;
            color: #c2410c;
            font-size: inherit;
        }
        .bar {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 16px 24px 0;
        }
        .bar button, .bar a {
            border: 1px solid var(--line);
            background: #fff;
            color: var(--navy);
            padding: 10px 14px;
            border-radius: 999px;
            font-size: .82rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(10, 29, 55, .08);
            text-decoration: none;
        }
        .bar .primary { background: var(--brand); color: #fff; border-color: var(--brand); }
        .bar .navy { background: var(--navy); color: #fff; border-color: var(--navy); }
        .section { padding: 18px 24px 0; }
        .card {
            background: #fff;
            border: 1px solid var(--line);
            border-top: 4px solid #ff6b00;
            border-radius: 16px;
            padding: 20px 22px;
            box-shadow: 0 10px 28px rgba(10, 29, 55, .06);
        }
        .card h2 {
            margin: 0 0 14px;
            color: #ff6b00;
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: .02em;
        }
        .kicker {
            color: var(--muted);
            font-size: .78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 8px;
        }
        .kicker.auto { color: #ff6b00; }
        .turnover {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 8px;
            max-width: 420px;
        }
        .turnover span { font-size: 1.4rem; font-weight: 800; color: var(--brand); }
        .turnover input, .price-input {
            width: 100%;
            border: 1px solid #fdba74;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 1rem;
            font-weight: 700;
            color: var(--navy);
            font-family: inherit;
        }
        .price-input { width: 140px; text-align: right; }
        .note { margin: 10px 0 0; color: var(--muted); font-size: .9rem; }
        .note strong { color: #c2410c; font-weight: 800; }
        .sheet {
            overflow: auto;
            border: 1px solid var(--line);
            border-radius: 12px;
        }
        table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 880px; }
        thead th {
            background: linear-gradient(90deg, #0a1d37, #16325a);
            color: #fff;
            padding: 14px 12px;
            text-align: left;
            font-size: 13px;
            letter-spacing: .04em;
            text-transform: uppercase;
            border-bottom: 3px solid #ff6b00;
        }
        td {
            padding: 14px 12px;
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
            background: #fff;
            line-height: 1.55;
        }
        tbody tr:nth-child(even) td { background: #f8fafc; }
        tbody tr:hover td { background: #fff7ed; }
        .number { font-weight: 800; text-align: center; width: 64px; color: #ff6b00; font-size: 17px; }
        .offer-cell { font-weight: 800; color: #c2410c; }
        tfoot td { font-weight: 800; background: #fff7ed !important; color: #0a1d37; }
        .right { text-align: right; }
        .auto { color: #c2410c; font-weight: 800; }
        .dash { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
        .stat {
            background: #f8fafc;
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 14px 16px;
            position: relative;
            overflow: hidden;
        }
        .stat::before {
            content: "";
            position: absolute;
            left: 0; top: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #ffb800, #ff6b00);
        }
        .stat span {
            display: block;
            color: #ff6b00;
            font-size: .72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .stat strong { display: block; margin-top: 6px; font-size: 1.12rem; color: #0a1d37; }
        .stat.gap strong { color: #c2410c; }
        .locked .price-input, .locked #desiredInput { background: #f8fafc; pointer-events: none; }
        [hidden] { display: none !important; }
        .footer { text-align: center; color: var(--muted); padding: 18px 24px 28px; font-size: 15px; }
        @media (max-width: 1100px) {
            .dash { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 700px) {
            .facts, .dash { grid-template-columns: 1fr; }
            .hero h1 { font-size: 26px; }
            .section, .bar, .facts { padding-left: 12px; padding-right: 12px; }
            .price-input { width: 110px; }
        }
        @media print {
            body { background: #fff; }
            .hero { position: static; }
            .language-buttons, .bar { display: none !important; }
            #screenView { display: block !important; }
            .hero { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            thead th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
@php
    $plan = is_array($reverse ?? null) ? $reverse : [];
    $languagePacks = $languages ?: ($plan['languages'] ?? []);
    unset($plan['languages']);
@endphp
<div class="page">
    <header class="hero">
        <div class="hero-top">
            <div>
                <small id="pageKicker">Business Navachar School · BNS ERP</small>
                <h1 id="pageTitle">{{ $facts['business_name'] }}</h1>
                <p id="pageSubtitle">Desired Turnover → 7 Markets → 7 Offers → Customers → Revenue</p>
            </div>
            <div class="language-buttons">
                <button class="active" onclick="changeLanguage('en', this)">ENGLISH</button>
                <button onclick="changeLanguage('gu', this)">ગુજરાતી</button>
                <button onclick="changeLanguage('hi', this)">हिन्दी</button>
                <button onclick="changeLanguage('mr', this)">मराठी</button>
            </div>
        </div>
    </header>

    <div id="screenForm">
        <div class="bar">
            <button type="button" id="btnSave">Save</button>
            <button type="button" id="btnEdit">Edit</button>
            <button type="button" class="primary" id="btnGenerate">Generate Plan</button>
            <button type="button" class="navy" id="btnView">View Reverse Management</button>
            <a href="#view-01" id="nav01">VIEW 01 Jewellery</a>
            <a href="#view-02" id="nav02">VIEW 02 Real Estate</a>
            <a href="#view-03" id="nav03">VIEW 03 Manufacturing</a>
            <a href="#view-04" id="nav04">VIEW 04 Lamination</a>
            <a href="#view-05" id="nav05">VIEW 05 Sweets</a>
        </div>

        <section class="facts">
            <div class="fact-card">
                <span id="labelBusinessName">Business Name:</span>
                <strong>{{ $plan['business_name'] ?? $facts['business_name'] }}</strong>
            </div>
            <div class="fact-card">
                <span id="labelCategory">Business Category:</span>
                <strong>{{ $plan['category'] ?? $facts['category'] }}</strong>
            </div>
            <div class="fact-card wide">
                <span id="labelIntroduction">Business Introduction:</span>
                <div class="member-intro"></div>
            </div>
            <div class="fact-card wide">
                <span id="labelProducts">Business Main Product:</span>
                <div class="member-product"></div>
            </div>
        </section>

        <section class="section">
            <div class="card">
                <h2 id="turnoverTitle">02 — Desired Annual Turnover</h2>
                <p class="note" id="turnoverNote">Member input only. ERP uses this target for reverse calculation.</p>
                <label class="kicker auto" id="turnoverLabel" for="desiredInput">My Desired Annual Turnover</label>
                <div class="turnover">
                    <span>₹</span>
                    <input id="desiredInput" type="text" inputmode="numeric" value="{{ $plan['desired_turnover'] ?? '' }}" placeholder="__________________">
                </div>
            </div>
        </section>

        <section class="section">
            <div class="card" id="plannerCard">
                <h2 id="plannerTitle">03 — 7 Market Offer Planner</h2>
                <p class="note" id="plannerNote">Market and suggested offer are auto-generated. Member fills offer price only. Target customers/deals and revenue are auto.</p>
                <div class="sheet">
                    <table>
                        <thead>
                            <tr>
                                <th id="thNo">No.</th>
                                <th id="thMarket">Market</th>
                                <th id="thOffer">ERP Suggested Offer</th>
                                <th class="right" id="thPrice">Member Price</th>
                                <th class="right" id="thCustomers">Target Customers / Deals</th>
                                <th class="right" id="thRevenue">Expected Revenue</th>
                            </tr>
                        </thead>
                        <tbody id="formRows"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" id="formTotalLabel">TOTAL</td>
                                <td class="right auto" id="formTotalCustomers">AUTO</td>
                                <td class="right auto" id="formTotalRevenue">AUTO</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <p class="note" id="formGapNote">Fill turnover and 7 prices, then click Generate Plan.</p>
            </div>
        </section>
    </div>

    <div id="screenView" hidden>
        <div class="bar">
            <button type="button" id="btnBack">Edit Input</button>
            <a href="#viewSummary" id="navSummary">Business Summary</a>
            <a href="#viewMarkets" id="navMarkets">7 Market Opportunities</a>
            <a href="#viewOffers" id="navOffers">7 Offer Plan</a>
            <a href="#viewTargets" id="navTargets">Customer / Deal Target</a>
            <a href="#viewRevenue" id="navRevenue">Revenue Plan</a>
            <a href="#viewGap" id="navGap">Revenue Gap</a>
            <a href="#view-01" class="nav-ex" data-nav="nav01">VIEW 01 Jewellery</a>
            <a href="#view-02" class="nav-ex" data-nav="nav02">VIEW 02 Real Estate</a>
            <a href="#view-03" class="nav-ex" data-nav="nav03">VIEW 03 Manufacturing</a>
            <a href="#view-04" class="nav-ex" data-nav="nav04">VIEW 04 Lamination</a>
            <a href="#view-05" class="nav-ex" data-nav="nav05">VIEW 05 Sweets</a>
            <button type="button" class="primary" id="btnPrint" onclick="window.print()">Print / Download</button>
        </div>

        <section class="facts" id="viewSummary">
            <div class="fact-card">
                <span class="label-name">Business Name:</span>
                <strong>{{ $plan['business_name'] ?? $facts['business_name'] }}</strong>
            </div>
            <div class="fact-card">
                <span class="label-cat">Business Category:</span>
                <strong>{{ $plan['category'] ?? $facts['category'] }}</strong>
            </div>
            <div class="fact-card wide">
                <span class="label-intro">Business Introduction:</span>
                <div class="member-intro"></div>
            </div>
            <div class="fact-card wide">
                <span class="label-prod">Business Main Product:</span>
                <div class="member-product"></div>
            </div>
        </section>

        <section class="section">
            <div class="card">
                <h2 id="viewBizTitle">Business Summary</h2>
                <div class="dash" style="margin-top:4px">
                    <div class="stat"><span id="statDesired">Desired Turnover</span><strong id="viewDesired">—</strong></div>
                    <div class="stat"><span id="statPlanned">Planned Revenue</span><strong id="viewPlanned">—</strong></div>
                    <div class="stat"><span id="statCustomers">Total Customers / Deals</span><strong id="viewCustomers">—</strong></div>
                    <div class="stat"><span id="statAchieve">Target Achievement</span><strong id="viewAchieve">—</strong></div>
                    <div class="stat"><span id="statOpps">Market Opportunities</span><strong>7</strong></div>
                    <div class="stat"><span id="statOffers">Total Offers</span><strong>7</strong></div>
                    <div class="stat gap" id="viewGap"><span id="statGap">Revenue Gap</span><strong id="viewGapValue">—</strong></div>
                    <div class="stat"><span id="statMember">Member</span><strong>{{ $facts['member_name'] }}</strong></div>
                </div>
            </div>
        </section>

        <section class="section" id="viewMarkets">
            <div class="card">
                <h2 id="viewOffers">7 Market Offer Plan</h2>
                <p class="note" id="viewTargets">Revenue = Price × Target Customers.</p>
                <div class="sheet" id="viewRevenue">
                    <table>
                        <thead>
                            <tr>
                                <th id="vThNo">No.</th>
                                <th id="vThMarket">Market</th>
                                <th id="vThOffer">Offer</th>
                                <th class="right" id="vThPrice">{{ $plan['price_label'] ?? 'Price' }}</th>
                                <th class="right" id="vThCustomers">{{ $plan['unit_label'] ?? 'Customers' }}</th>
                                <th class="right" id="vThRevenue">Revenue</th>
                            </tr>
                        </thead>
                        <tbody id="viewRows"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" id="viewTotalLabel">TOTAL</td>
                                <td class="right" id="viewTotalCustomers"></td>
                                <td class="right" id="viewTotalRevenue"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <p class="note" id="viewGapLine">Revenue Gap: <strong id="viewGapText">—</strong> · Achievement: <strong id="viewAchieveText">—</strong></p>
            </div>
        </section>
    </div>

    <div id="exampleViews">
        @foreach(($plan['examples'] ?? []) as $exIndex => $example)
            <section class="section" id="{{ $example['code'] }}">
                <div class="card">
                    <div class="kicker example-kicker">Complete example · shown for every member</div>
                    <h2 class="example-title" data-ex="{{ $exIndex }}">{{ $example['title'] }}</h2>
                    <div class="facts" style="padding: 8px 0 0">
                        <div class="fact-card">
                            <span class="label-name">Business Name:</span>
                            <strong>{{ $example['business_name'] }}</strong>
                        </div>
                        <div class="fact-card">
                            <span class="label-cat">Business Category:</span>
                            <strong>{{ $example['category'] }}</strong>
                        </div>
                        <div class="fact-card wide">
                            <span class="label-intro">Business Introduction:</span>
                            <div class="example-intro" data-ex="{{ $exIndex }}"></div>
                        </div>
                        <div class="fact-card wide">
                            <span class="label-prod">Business Main Product:</span>
                            <div class="example-product" data-ex="{{ $exIndex }}"></div>
                        </div>
                    </div>
                    <div class="dash" style="margin-top:16px">
                        <div class="stat"><span class="stat-desired">Desired Turnover</span><strong>{{ $example['desired_label'] }}</strong></div>
                        <div class="stat"><span class="stat-planned">Planned Revenue</span><strong>{{ $example['planned_label'] }}</strong></div>
                        <div class="stat"><span class="stat-customers">Total {{ $example['unit_label'] }}</span><strong>{{ $example['customers_label'] }}</strong></div>
                        <div class="stat"><span class="stat-achieve">Target Achievement</span><strong>{{ $example['achievement'] }}%</strong></div>
                        <div class="stat"><span class="stat-opps">Market Opportunities</span><strong>7</strong></div>
                        <div class="stat"><span class="stat-offers">Total Offers</span><strong>7</strong></div>
                        <div class="stat gap"><span class="stat-gap">Revenue Gap</span><strong>{{ $example['gap_label'] }}</strong></div>
                        <div class="stat"><span class="stat-unit">{{ $example['unit_label'] }}</span><strong>{{ $example['customers_label'] }}</strong></div>
                    </div>
                    <div class="sheet" style="margin-top:16px">
                        <table>
                            <thead>
                                <tr>
                                    <th class="ex-th-no">No.</th>
                                    <th class="ex-th-market">Market</th>
                                    <th class="ex-th-offer">Offer</th>
                                    <th class="right ex-th-price">{{ $example['price_label'] }}</th>
                                    <th class="right ex-th-unit">{{ $example['unit_label'] }}</th>
                                    <th class="right ex-th-revenue">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($example['markets'] as $rowIndex => $row)
                                    <tr>
                                        <td class="number">{{ $row['no'] }}</td>
                                        <td class="ex-market" data-ex="{{ $exIndex }}" data-row="{{ $rowIndex }}">{{ $row['short'] }}</td>
                                        <td class="offer-cell ex-offer" data-ex="{{ $exIndex }}" data-row="{{ $rowIndex }}">{{ $row['offer'] }}</td>
                                        <td class="right">{{ $row['price_label'] }}</td>
                                        <td class="right">{{ $row['customers_label'] }}</td>
                                        <td class="right">{{ $row['revenue_label'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="ex-total">TOTAL</td>
                                    <td class="right">{{ $example['customers_label'] }}</td>
                                    <td class="right">{{ $example['planned_label'] }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <p class="note example-gap-line">Revenue Gap: <strong>{{ $example['gap_label'] }}</strong> · Achievement: <strong>{{ $example['achievement'] }}%</strong>@if(!empty($example['note']))<br><span class="example-note">{{ $example['note'] }}</span>@endif</p>
                </div>
            </section>
        @endforeach
    </div>

    <div class="footer" id="pageFooter">{{ $plan['business_name'] ?? $facts['business_name'] }} — Reverse Management · Member fills Target and Price · ERP auto-calculates Market, Customers and Revenue · {{ $generatedAt->format('d M Y H:i') }}</div>
</div>
<script>
(function () {
    const plan = @json($plan);
    const languagePacks = @json($languagePacks ?: []);
    const markets = Array.isArray(plan.markets) ? plan.markets : [];
    const storageKey = 'bns-reverse-' + String(plan.business_name || 'business');
    const formRows = document.getElementById('formRows');
    const viewRows = document.getElementById('viewRows');
    const desiredInput = document.getElementById('desiredInput');
    const plannerCard = document.getElementById('plannerCard');
    const screenForm = document.getElementById('screenForm');
    const screenView = document.getElementById('screenView');
    const generatedAt = @json($generatedAt->format('d M Y H:i'));
    const familyIndex = { jewellery: 0, real_estate: 1, manufacturing: 2, lamination: 3, sweets: 4 };
    const businessFacts = {
        intro: plan.intro || '',
        products: plan.product || '',
        businessName: plan.business_name || '',
        memberName: @json($facts['member_name'] ?? ''),
        category: plan.category || ''
    };
    let currentLang = 'en';

    function pack() {
        return languagePacks[currentLang] || languagePacks.en || { ui: {}, markets: [], examples: [] };
    }
    function ui() {
        return pack().ui || {};
    }
    function setText(id, value) {
        const el = document.getElementById(id);
        if (!el || value == null || String(value) === '') return;
        el.textContent = value;
    }
    function setAll(selector, value) {
        if (value == null) return;
        document.querySelectorAll(selector).forEach(function (el) { el.textContent = value; });
    }
    function text(value) {
        const div = document.createElement('div');
        div.textContent = value == null ? '' : String(value);
        return div.innerHTML;
    }
    function escapeRegExp(value) {
        return String(value).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
    function namePhrases() {
        return [businessFacts.businessName, businessFacts.memberName, businessFacts.category]
            .map(function (value) { return String(value || '').trim(); })
            .filter(function (value) { return value && value !== '—'; })
            .sort(function (a, b) { return b.length - a.length; });
    }
    function isHeadingLine(value) {
        return value.length <= 48 && !/[.!?…।]/.test(value.replace(/:$/, ''));
    }
    function addMark(marks, start, end) {
        if (start < 0 || end <= start) return;
        if (marks.some(function (mark) { return start < mark.end && end > mark.start; })) return;
        marks.push({ start: start, end: end });
    }
    function sentenceRanges(value) {
        const ranges = [];
        const re = /[^.!?…।]+(?:[.!?…।]+|$)/g;
        let match;
        while ((match = re.exec(value))) {
            const textValue = match[0];
            const lead = (textValue.match(/^\s*/) || [''])[0].length;
            const start = match.index + lead;
            const end = match.index + textValue.length;
            if (end > start) ranges.push({ start: start, end: end });
        }
        return ranges.length ? ranges : [{ start: 0, end: value.length }];
    }
    function firstImportantSpan(sentence) {
        const skip = /^(a|an|the|and|or|of|to|in|on|for|with|from|by|as|at|is|are|be|this|that|into|over|its|their|can|will|should|every|each|one|our|we|create|launch|offer|introduce|build|start|develop|establish|appoint|use|add|prepare|write|map|reserve|give|train|convert|check|document|standardise|standardize|invite|make|grow|send|discuss|provide|design|keep|take|help)$/i;
        const re = /(\S+)/g;
        let match;
        let start = -1;
        let end = -1;
        let taken = 0;
        while ((match = re.exec(sentence))) {
            const word = match[1].replace(/^[“"'(\[]+|[”"')\].,;:!?।]+$/g, '');
            if (!word) continue;
            if (taken === 0 && skip.test(word)) continue;
            if (start < 0) start = match.index;
            end = match.index + match[0].length;
            taken += 1;
            if (taken >= 2) break;
        }
        return start >= 0 ? { start: start, end: end } : null;
    }
    function emphasize(value) {
        const raw = String(value == null ? '' : value).trim();
        if (!raw) return '';
        if (isHeadingLine(raw)) return '<strong>' + text(raw) + '</strong>';

        const marks = [];
        namePhrases().forEach(function (phrase) {
            const re = new RegExp(escapeRegExp(phrase), 'gi');
            let match;
            while ((match = re.exec(raw))) {
                addMark(marks, match.index, match.index + match[0].length);
            }
        });

        const quoted = /[“"']([^”"']{2,80})[”"']/g;
        let quoteMatch;
        while ((quoteMatch = quoted.exec(raw))) {
            addMark(marks, quoteMatch.index, quoteMatch.index + quoteMatch[0].length);
        }

        const label = raw.match(/^([^:]{2,48}):/);
        if (label) addMark(marks, 0, label[1].length + 1);

        const titled = /\b[A-Z][A-Za-z0-9’']+(?:\s+(?:[A-Z][A-Za-z0-9’']+|\/|&)){1,6}\b/g;
        let titleMatch;
        while ((titleMatch = titled.exec(raw))) {
            addMark(marks, titleMatch.index, titleMatch.index + titleMatch[0].length);
        }

        sentenceRanges(raw).forEach(function (range) {
            const hasMark = marks.some(function (mark) {
                return mark.start >= range.start && mark.end <= range.end;
            });
            if (hasMark) return;
            const first = firstImportantSpan(raw.slice(range.start, range.end));
            if (first) addMark(marks, range.start + first.start, range.start + first.end);
        });

        marks.sort(function (a, b) { return a.start - b.start; });
        let html = '';
        let cursor = 0;
        marks.forEach(function (mark) {
            html += text(raw.slice(cursor, mark.start));
            html += '<strong>' + text(raw.slice(mark.start, mark.end)) + '</strong>';
            cursor = mark.end;
        });
        html += text(raw.slice(cursor));
        return html;
    }
    function splitSentences(value) {
        const raw = String(value).trim();
        if (!raw) return [];
        const parts = raw.split(/(?<=[.!?…।])\s+/).map(function (s) { return s.trim(); }).filter(Boolean);
        return parts.length ? parts : [raw];
    }
    function toPoints(value) {
        if (Array.isArray(value)) {
            return value.flatMap(toPoints).filter(Boolean);
        }
        const raw = String(value == null ? '' : value).replace(/\r/g, '').trim();
        if (!raw || raw === '—') return [];

        let chunks = raw.split(/\n+|•|●/).map(function (line) {
            return line.replace(/^\s*(?:[-*•●]|\d+[.)])\s*/, '').trim();
        }).filter(Boolean);

        if (chunks.length <= 1 && /\d+[.)]\s+\S/.test(raw)) {
            chunks = raw.split(/(?=\s*\d+[.)]\s+)/).map(function (line) {
                return line.replace(/^\s*(?:[-*•●]|\d+[.)])\s*/, '').trim();
            }).filter(Boolean);
        }

        const points = [];
        chunks.forEach(function (chunk) {
            const sentences = splitSentences(chunk);
            if (sentences.length > 1) {
                sentences.forEach(function (sentence) { points.push(sentence); });
            } else {
                points.push(chunk);
            }
        });
        return points;
    }
    function renderPointsAll(selector, value) {
        const points = toPoints(value);
        const html = points.length
            ? '<ul class="point-list">' + points.map(function (point) {
                return '<li>' + emphasize(point) + '</li>';
            }).join('') + '</ul>'
            : '';
        document.querySelectorAll(selector).forEach(function (el) { el.innerHTML = html; });
    }
    function renderFactPoints() {
        renderPointsAll('.member-intro', businessFacts.intro);
        renderPointsAll('.member-product', businessFacts.products);
        (plan.examples || []).forEach(function (ex, i) {
            renderPointsAll('.example-intro[data-ex="' + i + '"]', ex.intro || '');
            renderPointsAll('.example-product[data-ex="' + i + '"]', ex.product || '');
        });
    }
    function unitLabel() {
        const labels = ui();
        return plan.family === 'real_estate'
            ? (labels.dealsUnit || 'Deals')
            : (labels.customersUnit || 'Customers');
    }
    function priceLabel() {
        const labels = ui();
        return plan.family === 'real_estate'
            ? (labels.dealValueUnit || 'Average Deal Value')
            : (labels.priceUnit || 'Price');
    }
    function marketName(i, fallback) {
        const names = pack().markets || [];
        return names[i] || fallback;
    }
    function memberOffer(i, fallback) {
        const idx = familyIndex[plan.family];
        if (idx == null) return fallback;
        const examples = pack().examples || [];
        const offers = (examples[idx] && examples[idx].offers) || [];
        return offers[i] || fallback;
    }
    function rupees(amount) {
        const n = Math.round(Number(amount) || 0);
        const sign = n < 0 ? '-' : '';
        const digits = String(Math.abs(n));
        if (digits.length <= 3) return '₹' + sign + digits;
        const last3 = digits.slice(-3);
        const rest = digits.slice(0, -3).replace(/\B(?=(\d{2})+(?!\d))/g, ',');
        return '₹' + sign + rest + ',' + last3;
    }
    function compact(amount) {
        const n = Math.round(Number(amount) || 0);
        const sign = n < 0 ? '-' : '';
        const abs = Math.abs(n);
        if (abs >= 10000000) {
            const cr = abs / 10000000;
            const label = Number.isInteger(cr) ? String(cr) : String(Math.round(cr * 100) / 100);
            return '₹' + sign + label + ' Cr';
        }
        if (abs >= 100000) {
            const lakh = abs / 100000;
            const label = Number.isInteger(lakh) ? String(lakh) : String(Math.round(lakh * 100) / 100);
            return '₹' + sign + label + ' L';
        }
        return rupees(n);
    }
    function gapLabel(gap) {
        if (!gap) return '₹0';
        const abs = Math.abs(gap);
        if (abs >= 100000 && abs % 100000 === 0) return (gap < 0 ? '-' : '') + '₹' + (abs / 100000) + ' Lakh';
        return compact(gap);
    }
    function parseMoney(value) {
        return Math.max(0, parseInt(String(value || '').replace(/[^\d]/g, ''), 10) || 0);
    }
    function currentPrices() {
        return markets.map(function (row, i) {
            const input = document.querySelector('[data-price="' + i + '"]');
            return input ? parseMoney(input.value) : Number(row.price || 0);
        });
    }
    function calculate() {
        const desired = parseMoney(desiredInput.value);
        const prices = currentPrices();
        let customersTotal = 0;
        let planned = 0;
        const rows = markets.map(function (row, i) {
            const price = Math.max(0, prices[i] || 0);
            const share = Number(row.share || 0);
            const targetRevenue = Math.round(desired * share);
            const customers = price > 0 ? Math.max(1, Math.round(targetRevenue / price)) : 0;
            const revenue = price * customers;
            customersTotal += customers;
            planned += revenue;
            return {
                no: row.no,
                market: marketName(i, row.short || row.market),
                offer: memberOffer(i, row.offer),
                price: price,
                customers: customers,
                revenue: revenue
            };
        });
        const gap = desired - planned;
        const achievement = desired > 0 ? Math.round((planned / desired) * 100) : 0;
        return { desired: desired, rows: rows, customers: customersTotal, planned: planned, gap: gap, achievement: achievement };
    }
    function paintForm(result) {
        result.rows.forEach(function (row, i) {
            const m = document.getElementById('formMarket' + i);
            const o = document.getElementById('formOffer' + i);
            const c = document.getElementById('autoC' + i);
            const r = document.getElementById('autoR' + i);
            if (m) m.textContent = row.market;
            if (o) o.textContent = row.offer;
            if (c) c.textContent = row.customers ? row.customers.toLocaleString('en-IN') : 'AUTO';
            if (r) r.textContent = row.revenue ? compact(row.revenue) : 'AUTO';
        });
        document.getElementById('formTotalCustomers').textContent = result.customers ? result.customers.toLocaleString('en-IN') : 'AUTO';
        document.getElementById('formTotalRevenue').textContent = result.planned ? compact(result.planned) : 'AUTO';
        const labels = ui();
        document.getElementById('formGapNote').textContent = result.desired
            ? ((labels.revenueGapLabel || 'Revenue Gap') + ': ' + gapLabel(result.gap) + ' · ' + (labels.achievementLabel || 'Achievement') + ': ' + result.achievement + '%')
            : (labels.formGapEmpty || 'Fill turnover and 7 prices, then click Generate Plan.');
    }
    function paintView(result) {
        viewRows.innerHTML = result.rows.map(function (row) {
            const no = String(row.no || '').padStart(2, '0');
            return '<tr><td class="number">' + no + '</td><td>' + row.market + '</td><td class="offer-cell">' + row.offer + '</td><td class="right">' + rupees(row.price) + '</td><td class="right">' + row.customers.toLocaleString('en-IN') + '</td><td class="right">' + compact(row.revenue) + '</td></tr>';
        }).join('');
        document.getElementById('viewDesired').textContent = rupees(result.desired);
        document.getElementById('viewPlanned').textContent = compact(result.planned);
        document.getElementById('viewCustomers').textContent = result.customers.toLocaleString('en-IN');
        document.getElementById('viewAchieve').textContent = result.achievement + '%';
        document.getElementById('viewGapValue').textContent = gapLabel(result.gap);
        document.getElementById('viewTotalCustomers').textContent = result.customers.toLocaleString('en-IN');
        document.getElementById('viewTotalRevenue').textContent = compact(result.planned);
        document.getElementById('viewGapText').textContent = gapLabel(result.gap);
        document.getElementById('viewAchieveText').textContent = result.achievement + '%';
    }
    function showForm() {
        screenForm.hidden = false;
        screenView.hidden = true;
    }
    function showView() {
        const result = calculate();
        paintForm(result);
        paintView(result);
        screenForm.hidden = true;
        screenView.hidden = false;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    function lock(locked) {
        plannerCard.classList.toggle('locked', locked);
        desiredInput.readOnly = locked;
        document.querySelectorAll('.price-input').forEach(function (el) { el.readOnly = locked; });
    }
    function persist() {
        localStorage.setItem(storageKey, JSON.stringify({
            desired: parseMoney(desiredInput.value),
            prices: currentPrices()
        }));
    }
    function applyLanguage(language) {
        currentLang = language;
        const labels = ui();
        const examples = pack().examples || [];
        document.documentElement.lang = language;

        setText('pageKicker', labels.kicker);
        setText('pageTitle', plan.business_name || labels.pageTitle);
        setText('pageSubtitle', labels.pageSubtitle);
        setText('labelBusinessName', labels.labelBusinessName);
        setText('labelCategory', labels.labelCategory);
        setText('labelIntroduction', labels.labelIntroduction);
        setText('labelProducts', labels.labelProducts);
        setAll('.label-name', labels.labelBusinessName);
        setAll('.label-cat', labels.labelCategory);
        setAll('.label-intro', labels.labelIntroduction);
        setAll('.label-prod', labels.labelProducts);
        setText('turnoverTitle', labels.turnoverTitle);
        setText('turnoverNote', labels.turnoverNote);
        setText('turnoverLabel', labels.turnoverLabel);
        setText('plannerTitle', labels.plannerTitle);
        setText('plannerNote', labels.plannerNote);
        setText('thNo', labels.thNo);
        setText('thMarket', labels.thMarket);
        setText('thOffer', labels.thOffer);
        setText('thPrice', labels.thPrice);
        setText('thCustomers', labels.thCustomers);
        setText('thRevenue', labels.thRevenue);
        setText('formTotalLabel', labels.total);
        setText('btnSave', labels.btnSave);
        setText('btnEdit', labels.btnEdit);
        setText('btnGenerate', labels.btnGenerate);
        setText('btnView', labels.btnView);
        setText('btnBack', labels.btnBack);
        setText('btnPrint', labels.btnPrint);
        setText('nav01', labels.nav01);
        setText('nav02', labels.nav02);
        setText('nav03', labels.nav03);
        setText('nav04', labels.nav04);
        setText('nav05', labels.nav05);
        document.querySelectorAll('.nav-ex').forEach(function (el) {
            const key = el.getAttribute('data-nav');
            if (key && labels[key]) el.textContent = labels[key];
        });
        setText('navSummary', labels.businessSummary);
        setText('navMarkets', labels.sevenMarkets);
        setText('navOffers', labels.sevenOffers);
        setText('navTargets', labels.customerTarget);
        setText('navRevenue', labels.revenuePlan);
        setText('navGap', labels.revenueGap);
        setText('viewBizTitle', labels.businessSummary);
        setText('viewOffers', labels.sevenOffers);
        setText('statDesired', labels.desiredTurnover);
        setText('statPlanned', labels.plannedRevenue);
        setText('statCustomers', labels.totalCustomers);
        setText('statAchieve', labels.achievement);
        setText('statOpps', labels.marketOpps);
        setText('statOffers', labels.totalOffers);
        setText('statGap', labels.revenueGap);
        setText('statMember', labels.member);
        setAll('.stat-desired', labels.desiredTurnover);
        setAll('.stat-planned', labels.plannedRevenue);
        setAll('.stat-customers', labels.totalCustomers);
        setAll('.stat-achieve', labels.achievement);
        setAll('.stat-opps', labels.marketOpps);
        setAll('.stat-offers', labels.totalOffers);
        setAll('.stat-gap', labels.revenueGap);
        setAll('.example-kicker', labels.exampleKicker);
        setAll('.ex-th-no', labels.thNo);
        setAll('.ex-th-market', labels.thMarket);
        setAll('.ex-th-offer', labels.thOffer);
        setAll('.ex-th-revenue', labels.thRevenue);
        setAll('.ex-total', labels.total);
        setText('vThNo', labels.thNo);
        setText('vThMarket', labels.thMarket);
        setText('vThOffer', labels.thOffer);
        setText('vThPrice', priceLabel());
        setText('vThCustomers', unitLabel());
        setText('vThRevenue', labels.thRevenue);
        setText('viewTotalLabel', labels.total);
        document.querySelectorAll('.ex-th-price').forEach(function (el, i) {
            const isDeals = i === 1;
            el.textContent = isDeals ? (labels.dealValueUnit || el.textContent) : (labels.priceUnit || el.textContent);
        });
        document.querySelectorAll('.ex-th-unit').forEach(function (el, i) {
            el.textContent = i === 1 ? (labels.dealsUnit || el.textContent) : (labels.customersUnit || el.textContent);
        });
        document.querySelectorAll('.stat-unit').forEach(function (el, i) {
            el.textContent = i === 1 ? (labels.dealsUnit || el.textContent) : (labels.customersUnit || el.textContent);
        });
        setText('viewTargets', (labels.thRevenue || 'Revenue') + ' = ' + priceLabel() + ' × ' + unitLabel() + '.');
        document.querySelectorAll('.example-title').forEach(function (el) {
            const idx = Number(el.getAttribute('data-ex'));
            if (examples[idx] && examples[idx].title) el.textContent = examples[idx].title;
        });
        document.querySelectorAll('.ex-market').forEach(function (el) {
            const row = Number(el.getAttribute('data-row'));
            el.textContent = marketName(row, el.textContent);
        });
        document.querySelectorAll('.ex-offer').forEach(function (el) {
            const idx = Number(el.getAttribute('data-ex'));
            const row = Number(el.getAttribute('data-row'));
            const offers = (examples[idx] && examples[idx].offers) || [];
            if (offers[row]) el.textContent = offers[row];
        });
        document.querySelectorAll('.example-gap-line').forEach(function (el) {
            const strongs = el.querySelectorAll('strong');
            const gap = strongs[0] ? strongs[0].textContent : '—';
            const ach = strongs[1] ? strongs[1].textContent : '—';
            const note = el.querySelector('.example-note');
            const noteHtml = note ? '<br><span class="example-note">' + note.textContent + '</span>' : '';
            el.innerHTML = (labels.revenueGapLabel || 'Revenue Gap') + ': <strong>' + gap + '</strong> · ' + (labels.achievementLabel || 'Achievement') + ': <strong>' + ach + '</strong>' + noteHtml;
        });
        document.querySelectorAll('.example-note').forEach(function (el) {
            if (labels.realEstateNote) el.textContent = labels.realEstateNote;
        });
        const footerBase = labels.footer || '';
        setText('pageFooter', footerBase ? (footerBase + ' · ' + generatedAt) : footerBase);
        renderFactPoints();
        const result = calculate();
        paintForm(result);
        if (!screenView.hidden) paintView(result);
    }
    window.changeLanguage = function (language, button) {
        document.querySelectorAll('.language-buttons button').forEach(function (btn) { btn.classList.remove('active'); });
        button.classList.add('active');
        applyLanguage(language);
    };

    formRows.innerHTML = markets.map(function (row, i) {
        const no = String(row.no || (i + 1)).padStart(2, '0');
        return '<tr><td class="number">' + no + '</td><td id="formMarket' + i + '">' + (row.short || row.market) + '</td><td class="offer-cell" id="formOffer' + i + '">' + row.offer + '</td><td class="right"><input class="price-input" data-price="' + i + '" type="text" inputmode="numeric" value="' + (row.price || '') + '" placeholder="₹ ____"></td><td class="right auto" id="autoC' + i + '">AUTO</td><td class="right auto" id="autoR' + i + '">AUTO</td></tr>';
    }).join('');

    try {
        const saved = JSON.parse(localStorage.getItem(storageKey) || 'null');
        if (saved && saved.desired) desiredInput.value = saved.desired;
        if (saved && Array.isArray(saved.prices)) {
            saved.prices.forEach(function (price, i) {
                const input = document.querySelector('[data-price="' + i + '"]');
                if (input && price) input.value = price;
            });
        }
    } catch (e) {}

    document.getElementById('btnGenerate').addEventListener('click', function () {
        paintForm(calculate());
    });
    document.getElementById('btnView').addEventListener('click', showView);
    document.getElementById('btnBack').addEventListener('click', showForm);
    document.getElementById('btnSave').addEventListener('click', function () {
        persist();
        lock(true);
    });
    document.getElementById('btnEdit').addEventListener('click', function () {
        lock(false);
        showForm();
    });
    desiredInput.addEventListener('input', function () { paintForm(calculate()); });
    document.querySelectorAll('.price-input').forEach(function (el) {
        el.addEventListener('input', function () { paintForm(calculate()); });
    });

    applyLanguage('en');
})();
</script>
</body>
</html>
