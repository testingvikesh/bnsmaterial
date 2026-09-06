<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $facts['business_name'] }} — BNS Tagline Masterclass</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;600;700&family=Noto+Sans+Gujarati:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0a1d37;
            --brand: #ff6b00;
            --gold: #ffb800;
            --line: #e8edf3;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Plus Jakarta Sans", "Noto Sans Gujarati", "Noto Sans Devanagari", Arial, sans-serif;
            background: #eef3f8;
            color: #0a1d37;
            font-size: 16px;
        }
        .page { max-width: 1680px; margin: 0 auto; min-height: 100vh; }
        .hero {
            position: sticky; top: 0; z-index: 40;
            background: linear-gradient(135deg, #050b14 0%, #0a1d37 72%, #123056 100%);
            color: #fff;
            padding: 20px 28px 18px;
            border-bottom: 3px solid #ff6b00;
            box-shadow: 0 16px 40px rgba(10, 29, 55, .18);
        }
        .hero-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap; }
        .hero small { display: block; color: #ffb800; letter-spacing: .08em; text-transform: uppercase; font-weight: 800; font-size: 12px; }
        .hero h1 { margin: 6px 0; font-size: 32px; font-weight: 800; }
        #pageSubtitle { margin: 0; color: #ffb800; font-size: 17px; font-weight: 700; }
        .language-buttons { display: flex; gap: 8px; flex-wrap: wrap; }
        .language-buttons button {
            border: 1px solid rgba(255,255,255,.28);
            background: rgba(255,255,255,.08);
            color: #fff; padding: 10px 16px; border-radius: 999px;
            cursor: pointer; font-weight: 700; font-size: 14px;
        }
        .language-buttons button:hover,
        .language-buttons button.active { background: #fff; color: #0a1d37; }
        .bar { display: flex; flex-wrap: wrap; gap: 8px; padding: 16px 24px 0; }
        .bar a {
            background: #fff; color: #0a1d37; padding: 10px 14px; border-radius: 999px;
            font-size: .82rem; font-weight: 800; text-decoration: none;
            box-shadow: 0 8px 20px rgba(10, 29, 55, .08);
        }
        .facts { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; padding: 20px 24px 8px; }
        .fact-card {
            background: #fff; border: 1px solid var(--line); border-radius: 16px;
            padding: 16px 18px; box-shadow: 0 10px 28px rgba(10, 29, 55, .06);
            position: relative; overflow: hidden;
        }
        .fact-card::before {
            content: ""; position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
            background: linear-gradient(180deg, #ffb800, #ff6b00);
        }
        .fact-card.wide { grid-column: 1 / -1; }
        .fact-card > span { display: block; color: #ff6b00; font-size: 13px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 8px; }
        .fact-card > strong { display: block; font-size: 18px; line-height: 1.45; }
        .point-list { margin: 0; padding-left: 22px; }
        .point-list li {
            margin: 0 0 10px;
            line-height: 1.65;
            font-size: 16px;
            font-weight: 500;
            color: #0a1d37;
            list-style: disc;
        }
        .point-list li:last-child { margin-bottom: 0; }
        .point-list strong {
            display: inline;
            font-weight: 800;
            color: #c2410c;
            font-size: inherit;
        }
        .section { padding: 16px 24px 0; }
        .card {
            background: #fff; border: 1px solid var(--line); border-top: 4px solid #ff6b00;
            border-radius: 16px; padding: 20px 22px; box-shadow: 0 10px 28px rgba(10, 29, 55, .06);
        }
        .card h2 { margin: 0 0 8px; color: #ff6b00; font-size: 1.1rem; font-weight: 800; }
        .note { margin: 0 0 14px; color: #64748b; }
        .sheet { overflow: auto; border-radius: 12px; border: 1px solid var(--line); }
        table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 980px; }
        thead th {
            background: linear-gradient(90deg, #0a1d37, #16325a); color: #fff;
            padding: 12px 10px; font-size: 12px; letter-spacing: .04em; text-transform: uppercase;
            border-bottom: 3px solid #ff6b00; text-align: left;
        }
        td { padding: 12px 10px; border-bottom: 1px solid var(--line); background: #fff; vertical-align: top; }
        tbody tr:nth-child(even) td { background: #f8fafc; }
        .number { color: #ff6b00; font-weight: 800; width: 54px; }
        .cat { font-weight: 800; color: #0a1d37; white-space: nowrap; }
        .tag { font-weight: 700; color: #c2410c; }
        .example {
            border: 1px solid var(--line); border-radius: 16px; padding: 20px 22px;
            margin-bottom: 16px; box-shadow: 0 10px 28px rgba(10, 29, 55, .05);
        }
        .example h2 { margin: 0 0 6px; font-size: 1.2rem; font-weight: 800; }
        .example .meta { margin: 0 0 14px; font-weight: 600; }
        .formula {
            display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-top: 10px;
        }
        .pill { background: #fff7ed; color: #c2410c; border: 1px solid #fdba74; padding: 8px 12px; border-radius: 999px; font-weight: 700; font-size: 13px; }
        .footer { text-align: center; color: #64748b; padding: 18px 24px 28px; }
        @media (max-width: 700px) {
            .facts { grid-template-columns: 1fr; }
            .hero h1 { font-size: 24px; }
        }
        @media print {
            .hero { position: static; }
            .language-buttons, .bar { display: none !important; }
        }
    </style>
</head>
<body>
@php
    $plan = is_array($tagline ?? null) ? $tagline : [];
    $languagePacks = $languages ?: ($plan['languages'] ?? []);
    $introText = (string) ($plan['intro'] ?? $facts['intro'] ?? '');
    $productText = (string) ($plan['product'] ?? $facts['products'] ?? '');
    $introPoints = is_array($plan['intro_points'] ?? null) && $plan['intro_points'] !== []
        ? $plan['intro_points']
        : \App\Support\MaterialCopyPoints::from($introText);
    $productPoints = is_array($plan['product_points'] ?? null) && $plan['product_points'] !== []
        ? $plan['product_points']
        : \App\Support\MaterialCopyPoints::from($productText);
@endphp
<div class="page">
    <header class="hero">
        <div class="hero-top">
            <div>
                <small id="pageKicker">BNS Tagline Masterclass</small>
                <h1 id="pageTitle">{{ $facts['business_name'] }}</h1>
                <p id="pageSubtitle">What does the business do? → What do people remember?</p>
            </div>
            <div class="language-buttons">
                <button class="active" onclick="changeLanguage('en', this)">ENGLISH</button>
                <button onclick="changeLanguage('gu', this)">ગુજરાતી</button>
                <button onclick="changeLanguage('hi', this)">हिन्दी</button>
                <button onclick="changeLanguage('mr', this)">मराठी</button>
            </div>
        </div>
    </header>

    <div class="bar">
        <a href="#your-taglines" id="navYours">Your 15 Business Taglines</a>
        <a href="#view-01" id="nav01">VIEW 01 Real Estate</a>
        <a href="#view-02" id="nav02">VIEW 02 Education</a>
        <a href="#view-03" id="nav03">VIEW 03 IT</a>
        <a href="#view-04" id="nav04">VIEW 04 Sweets</a>
        <a href="#view-05" id="nav05">VIEW 05 Jewellery</a>
        <a href="#takeaway" id="navTake">Classroom Takeaway</a>
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
            <div class="member-intro">
                @if($introPoints !== [])
                    <ul class="point-list">
                        @foreach($introPoints as $point)
                            <li>{{ $point }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        <div class="fact-card wide">
            <span id="labelProducts">Business Main Product:</span>
            <div class="member-product">
                @if($productPoints !== [])
                    <ul class="point-list">
                        @foreach($productPoints as $point)
                            <li>{{ $point }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </section>

    <section class="section" id="your-taglines">
        <div class="card">
            <h2 id="yourTitle">Your 15 Business Taglines</h2>
            <p class="note" id="yourNote">Generated from this member’s business. Identity + Customer Value + Trust + Innovation + Experience + Future Vision.</p>
            <div class="sheet">
                <table>
                    <thead>
                        <tr>
                            <th id="thNo">No.</th>
                            <th id="thCategory">Category</th>
                            <th id="thEn">English</th>
                            <th id="thGu">ગુજરાતી</th>
                            <th id="thHi">हिन्दी</th>
                            <th id="thMr">मराठी</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($plan['rows'] ?? []) as $row)
                            <tr>
                                <td class="number">{{ $row['no'] }}</td>
                                <td class="cat">{{ $row['category'] }}</td>
                                <td class="tag">{{ $row['en'] }}</td>
                                <td>{{ $row['gu'] }}</td>
                                <td>{{ $row['hi'] }}</td>
                                <td>{{ $row['mr'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="section">
        <h2 id="examplesTitle" style="color:#ff6b00;font-size:1.1rem;font-weight:800;margin:8px 0 12px">Classroom examples — shown for every member</h2>
        @foreach(($plan['examples'] ?? []) as $example)
            <article class="example" id="{{ $example['code'] }}" style="background: {{ $example['theme'] }}; border-top: 4px solid {{ $example['accent'] }};">
                <h2 style="color: {{ $example['accent'] }}">{{ $example['emoji'] }} {{ $example['no'] }} — {{ $example['title'] }}</h2>
                <p class="meta">
                    <span class="biz-label">Business:</span> {{ $example['business'] }}<br>
                    <span class="service-label">Main Service:</span> {{ $example['service'] }}
                </p>
                <div class="sheet">
                    <table>
                        <thead>
                            <tr>
                                <th class="ex-th-no">No.</th>
                                <th class="ex-th-cat">Category</th>
                                <th class="ex-th-en">English</th>
                                <th class="ex-th-gu">ગુજરાતી</th>
                                <th class="ex-th-hi">हिन्दी</th>
                                <th class="ex-th-mr">मराठी</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($example['rows'] as $row)
                                <tr>
                                    <td class="number">{{ $row['no'] }}</td>
                                    <td class="cat">{{ $row['category'] }}</td>
                                    <td class="tag">{{ $row['en'] }}</td>
                                    <td>{{ $row['gu'] }}</td>
                                    <td>{{ $row['hi'] }}</td>
                                    <td>{{ $row['mr'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article>
        @endforeach
    </section>

    <section class="section" id="takeaway">
        <div class="card">
            <h2 id="takeawayTitle">BNS Coach — Classroom Takeaway</h2>
            <p class="note" id="takeaway">A tagline is not only a beautiful sentence. It must show Identity + Customer Value + Trust + Innovation + Experience + Future Vision.</p>
            <h2 id="formulaTitle">BNS Tagline Formula</h2>
            <div class="formula">
                <span class="pill">WHO WE ARE</span>
                <span>+</span>
                <span class="pill">WHAT VALUE WE CREATE</span>
                <span>+</span>
                <span class="pill">WHAT MAKES US DIFFERENT</span>
                <span>+</span>
                <span class="pill">WHERE WE WANT TO GO</span>
                <span style="color:#ff6b00;font-weight:800">→</span>
                <span class="pill">POWERFUL BUSINESS TAGLINE</span>
            </div>
            <p class="note" id="formula" style="margin-top:12px"></p>
        </div>
    </section>

    <div class="footer" id="pageFooter">{{ $plan['business_name'] ?? $facts['business_name'] }} — BNS Tagline Masterclass</div>
</div>
<script>
(function () {
    const languagePacks = @json($languagePacks ?: []);
    const businessFacts = {
        intro: @json($introText),
        products: @json($productText),
        introPoints: @json($introPoints),
        productPoints: @json($productPoints),
        businessName: @json($plan['business_name'] ?? $facts['business_name'] ?? ''),
        memberName: @json($facts['member_name'] ?? ''),
        category: @json($plan['category'] ?? $facts['category'] ?? '')
    };
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
        const protectedValue = raw.replace(/\b(Mr|Mrs|Ms|Dr|Prof|Sr|Jr|St|No|vs|etc|Inc|Ltd|Pvt|Co)\./gi, '$1<prd>');
        let parts = protectedValue.split(/(?<=[.!?…।])\s+(?=\S)/).map(function (s) {
            return s.replace(/<prd>/g, '.').trim();
        }).filter(Boolean);
        if (parts.length <= 1) {
            parts = protectedValue.split(/(?<=[.!?…।])(?=[A-Z“"'])/).map(function (s) {
                return s.replace(/<prd>/g, '.').trim();
            }).filter(Boolean);
        }
        return parts.length ? parts : [raw];
    }
    function splitNumbered(value) {
        if (!/(?:^|\s)\d+[.)]\s+\S/.test(value)) return [value];
        const parts = value.split(/(?=(?:^|\s)\d+[.)]\s+\S)/).map(function (line) {
            return line.replace(/^\s*\d+[.)]\s+/, '').trim();
        }).filter(Boolean);
        return parts.length > 1 ? parts : [value];
    }
    function splitLabeled(value) {
        const re = /(?:^|(?<=[.!?…।]\s)|(?<=[a-z0-9,;]\s))((?:For\s+[A-Z][^:]{1,48}|(?:[A-Z][A-Za-z0-9’'\/+-]+(?:\s+(?:&|and|\/|[A-Z][A-Za-z0-9’'\/+-]+)){0,6})):)/g;
        const starts = [];
        let match;
        while ((match = re.exec(value))) {
            if (starts.indexOf(match.index) === -1) starts.push(match.index);
        }
        if (!starts.length || (starts.length === 1 && starts[0] === 0)) return [value];
        const parts = [];
        let cursor = 0;
        starts.forEach(function (start) {
            if (start > cursor) {
                const before = value.slice(cursor, start).trim();
                if (before) parts.push(before);
            }
            cursor = start;
        });
        const tail = value.slice(cursor).trim();
        if (tail) parts.push(tail);
        return parts.length > 1 ? parts : [value];
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
        if (chunks.length <= 1) {
            const numbered = splitNumbered(raw);
            if (numbered.length > 1) chunks = numbered;
        }
        if (chunks.length <= 1) {
            const labeled = splitLabeled(raw);
            if (labeled.length > 1) chunks = labeled;
        }
        const points = [];
        chunks.forEach(function (chunk) {
            const numbered = splitNumbered(chunk);
            if (numbered.length > 1) {
                numbered.forEach(function (item) {
                    const sentences = splitSentences(item);
                    (sentences.length > 1 ? sentences : [item]).forEach(function (sentence) { points.push(sentence); });
                });
                return;
            }
            const sentences = splitSentences(chunk);
            if (sentences.length > 1) {
                sentences.forEach(function (sentence) { points.push(sentence); });
                return;
            }
            const labeled = splitLabeled(chunk);
            if (labeled.length > 1) {
                labeled.forEach(function (item) { points.push(item); });
                return;
            }
            points.push(chunk);
        });
        return points;
    }
    function renderFactPoints() {
        const pointsHtml = function (ready, fallback) {
            const points = (Array.isArray(ready) && ready.length) ? ready : toPoints(fallback);
            return points.length
                ? '<ul class="point-list">' + points.map(function (point) {
                    return '<li>' + emphasize(point) + '</li>';
                }).join('') + '</ul>'
                : '';
        };
        document.querySelectorAll('.member-intro').forEach(function (el) {
            el.innerHTML = pointsHtml(businessFacts.introPoints, businessFacts.intro);
        });
        document.querySelectorAll('.member-product').forEach(function (el) {
            el.innerHTML = pointsHtml(businessFacts.productPoints, businessFacts.products);
        });
    }
    function applyLanguage(language) {
        const pack = languagePacks[language] || languagePacks.en || {};
        const ui = pack.ui || {};
        document.documentElement.lang = language;
        setText('pageKicker', ui.kicker);
        setText('pageSubtitle', ui.pageSubtitle);
        setText('labelBusinessName', ui.labelBusinessName);
        setText('labelCategory', ui.labelCategory);
        setText('labelIntroduction', ui.labelIntroduction);
        setText('labelProducts', ui.labelProducts);
        setText('yourTitle', ui.yourTitle);
        setText('yourNote', ui.yourNote);
        setText('examplesTitle', ui.examplesTitle);
        setText('thNo', ui.thNo);
        setText('thCategory', ui.thCategory);
        setText('thEn', ui.thEn);
        setText('thGu', ui.thGu);
        setText('thHi', ui.thHi);
        setText('thMr', ui.thMr);
        setAll('.ex-th-no', ui.thNo);
        setAll('.ex-th-cat', ui.thCategory);
        setAll('.ex-th-en', ui.thEn);
        setAll('.ex-th-gu', ui.thGu);
        setAll('.ex-th-hi', ui.thHi);
        setAll('.ex-th-mr', ui.thMr);
        setAll('.biz-label', ui.bizLabel);
        setAll('.service-label', ui.serviceLabel);
        setText('takeawayTitle', ui.takeawayTitle);
        setText('takeaway', ui.takeaway);
        setText('formulaTitle', ui.formulaTitle);
        setText('formula', ui.formula);
        setText('nav01', ui.nav01);
        setText('nav02', ui.nav02);
        setText('nav03', ui.nav03);
        setText('nav04', ui.nav04);
        setText('nav05', ui.nav05);
        setText('navYours', ui.yourTitle);
        setText('navTake', ui.takeawayTitle);
        setText('pageFooter', ui.footer || '');
        renderFactPoints();
    }
    window.changeLanguage = function (language, button) {
        document.querySelectorAll('.language-buttons button').forEach(function (btn) { btn.classList.remove('active'); });
        button.classList.add('active');
        applyLanguage(language);
    };
    applyLanguage('en');
})();
</script>
</body>
</html>
