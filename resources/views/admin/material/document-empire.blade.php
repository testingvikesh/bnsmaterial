<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $facts['business_name'] }} - Business Empire Vision</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Gujarati:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
            font-family: "Plus Jakarta Sans", "Noto Sans Gujarati", Arial, sans-serif;
            background: #eef3f8;
            margin: 0;
            color: #0a1d37;
            font-size: 17px;
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
        .hero h1 { margin: 0 0 6px; font-size: 34px; font-weight: 800; }
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
        .fact-card span {
            display: block;
            color: #ff6b00;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .fact-card strong {
            display: block;
            font-size: 18px;
            line-height: 1.45;
            color: #0a1d37;
        }
        .sheet {
            margin: 12px 24px 28px;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: 0 12px 32px rgba(10, 29, 55, .08);
            overflow: hidden;
        }
        .table-wrapper {
            max-height: calc(100vh - 92px);
            overflow: auto;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 1380px;
        }
        thead th {
            position: sticky;
            top: 0;
            z-index: 8;
            background: linear-gradient(90deg, #0a1d37, #16325a);
            color: #fff;
            padding: 16px 14px;
            text-align: left;
            font-size: 14px;
            letter-spacing: .04em;
            text-transform: uppercase;
            border-bottom: 3px solid #ff6b00;
            box-shadow: 0 2px 0 #0a1d37;
        }
        td {
            padding: 16px 14px;
            border-bottom: 1px solid var(--line);
            vertical-align: top;
            line-height: 1.65;
            background: #fff;
            font-size: 16px;
        }
        tbody tr:nth-child(even) td { background: #f8fafc; }
        tbody tr:hover td { background: #fff7ed; }
        .number { font-weight: 800; text-align: center; width: 64px; color: #ff6b00; font-size: 17px; }
        .point-title { font-weight: 800; color: #0a1d37; min-width: 180px; font-size: 16px; }
        .section { margin: 0 24px 24px; }
        .section h2 {
            background: linear-gradient(90deg, #0a1d37, #16325a);
            color: white;
            padding: 16px 20px;
            border-radius: 12px;
            margin: 0 0 12px;
            font-size: 21px;
        }
        .vision-box {
            background: #fff;
            border: 1px solid var(--line);
            border-left: 5px solid #ff6b00;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 14px;
            line-height: 1.7;
            box-shadow: 0 8px 20px rgba(10, 29, 55, .04);
            font-size: 17px;
        }
        .point-list {
            margin: 0;
            padding-left: 22px;
        }
        .point-list li {
            margin: 0 0 10px;
            line-height: 1.6;
            font-size: 17px;
        }
        .point-list li:last-child { margin-bottom: 0; }
        .fact-card .point-list {
            font-size: 16px;
            font-weight: 500;
            color: #0a1d37;
        }
        td strong,
        .point-list strong,
        .vision-box strong {
            font-weight: 800;
            color: #c2410c;
        }
        .journey-flow { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .journey-item {
            background: #fff7ed;
            color: #c2410c;
            border: 1px solid #fdba74;
            padding: 9px 14px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 15px;
        }
        .arrow { color: #ff6b00; font-weight: bold; }
        .timeline-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }
        .footer { text-align: center; color: var(--muted); padding: 10px 24px 28px; font-size: 15px; }
        @media (max-width: 1100px) {
            .timeline-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 700px) {
            .facts, .timeline-grid { grid-template-columns: 1fr; }
            .hero h1 { font-size: 26px; }
            .sheet, .section { margin-left: 12px; margin-right: 12px; }
        }
        @media print {
            body { background: #fff; }
            .hero { position: static; }
            .language-buttons { display: none; }
            .table-wrapper { max-height: none; overflow: visible; }
            thead th { position: static; }
            table { font-size: 10px; }
        }
    </style>
</head>
<body>
<div class="page">
    <header class="hero">
        <div class="hero-top">
            <div>
                <h1 id="pageTitle">{{ $facts['business_name'] }}</h1>
                <p id="pageSubtitle">Business House → Business Empire Vision</p>
            </div>
            <div class="language-buttons">
                <button class="active" onclick="changeLanguage('en', this)">ENGLISH</button>
                <button onclick="changeLanguage('gu', this)">ગુજરાતી</button>
                <button onclick="changeLanguage('hi', this)">हिन्दी</button>
                <button onclick="changeLanguage('mr', this)">मराठी</button>
            </div>
        </div>
    </header>

    <section class="facts">
        <div class="fact-card">
            <span id="labelBusinessName">Business Name</span>
            <strong>{{ $facts['business_name'] }}</strong>
        </div>
        <div class="fact-card">
            <span id="labelCategory">Business Category</span>
            <strong>{{ $facts['category'] }}</strong>
        </div>
        <div class="fact-card wide">
            <span id="labelIntroduction">Business Introduction</span>
            <div id="introPoints"></div>
        </div>
        <div class="fact-card wide">
            <span id="labelProducts">Business Main Product</span>
            <div id="productPoints"></div>
        </div>
    </section>

    <div class="sheet">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th id="thNo">No.</th>
                        <th id="thVision">Vision Point</th>
                        <th id="thExplanation">Simple Explanation</th>
                        <th id="thInnovation">Innovation Idea</th>
                        <th id="thExample">Easy Practical Example</th>
                        <th id="thAction">Action Step</th>
                        <th id="thBenefit">Business Benefit</th>
                    </tr>
                </thead>
                <tbody id="visionTable"></tbody>
            </table>
        </div>
    </div>

    <div class="section">
        <h2 id="journeyTitle">Business Empire Journey</h2>
        <div class="vision-box">
            <div class="journey-flow" id="journeyFlow"></div>
        </div>
        <div class="vision-box" id="journeyDescription"></div>
    </div>

    <div class="section">
        <div class="timeline-grid">
            <div>
                <h2 id="threeYearTitle">3-Year Vision</h2>
                <div class="vision-box" id="threeYearList"></div>
            </div>
            <div>
                <h2 id="fiveYearTitle">5-Year Vision</h2>
                <div class="vision-box" id="fiveYearList"></div>
            </div>
            <div>
                <h2 id="tenYearTitle">10-Year Vision</h2>
                <div class="vision-box" id="tenYearList"></div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2 id="finalTitle">{{ $final['title'] ?? 'How can this Business House become a Business Empire?' }}</h2>
        <div class="vision-box" id="finalText"></div>
    </div>

    <div class="footer" id="pageFooter"></div>
</div>

<script>
const languagePacks = @json($languages ?: []);
const businessFacts = {
    intro: @json($facts['intro'] ?? ''),
    products: @json($facts['products'] ?? ''),
    businessName: @json($facts['business_name'] ?? ''),
    memberName: @json($facts['member_name'] ?? ''),
    category: @json($facts['category'] ?? '')
};

function text(value) {
    const div = document.createElement("div");
    div.textContent = value == null ? "" : String(value);
    return div.innerHTML;
}

function setText(id, value) {
    const el = document.getElementById(id);
    if (!el || value == null || String(value) === "") {
        return;
    }
    el.textContent = value;
}

function escapeRegExp(value) {
    return String(value).replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
}

function namePhrases() {
    return [businessFacts.businessName, businessFacts.memberName, businessFacts.category]
        .map((value) => String(value || "").trim())
        .filter((value) => value && value !== "—")
        .sort((a, b) => b.length - a.length);
}

function isHeadingLine(value) {
    return value.length <= 48 && !/[.!?…]/.test(value.replace(/:$/, ""));
}

function addMark(marks, start, end) {
    if (start < 0 || end <= start) {
        return;
    }
    if (marks.some((mark) => start < mark.end && end > mark.start)) {
        return;
    }
    marks.push({ start, end });
}

function sentenceRanges(value) {
    const ranges = [];
    const re = /[^.!?…]+(?:[.!?…]+|$)/g;
    let match;
    while ((match = re.exec(value))) {
        const textValue = match[0];
        const lead = textValue.match(/^\s*/)[0].length;
        const start = match.index + lead;
        const end = match.index + textValue.length;
        if (end > start) {
            ranges.push({ start, end });
        }
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
        const word = match[1].replace(/^[“"'(\[]+|[”"')\].,;:!?]+$/g, "");
        if (!word) {
            continue;
        }
        if (taken === 0 && skip.test(word)) {
            continue;
        }
        if (start < 0) {
            start = match.index;
        }
        end = match.index + match[0].length;
        taken += 1;
        if (taken >= 2) {
            break;
        }
    }
    return start >= 0 ? { start, end } : null;
}

function emphasize(value) {
    const raw = String(value == null ? "" : value).trim();
    if (!raw) {
        return "";
    }
    if (isHeadingLine(raw)) {
        return `<strong>${text(raw)}</strong>`;
    }

    const marks = [];
    namePhrases().forEach((phrase) => {
        const re = new RegExp(escapeRegExp(phrase), "gi");
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
    if (label) {
        addMark(marks, 0, label[1].length + 1);
    }

    const titled = /\b[A-Z][A-Za-z0-9’']+(?:\s+(?:[A-Z][A-Za-z0-9’']+|\/|&)){1,6}\b/g;
    let titleMatch;
    while ((titleMatch = titled.exec(raw))) {
        addMark(marks, titleMatch.index, titleMatch.index + titleMatch[0].length);
    }

    sentenceRanges(raw).forEach((range) => {
        const hasMark = marks.some((mark) => mark.start >= range.start && mark.end <= range.end);
        if (hasMark) {
            return;
        }
        const first = firstImportantSpan(raw.slice(range.start, range.end));
        if (first) {
            addMark(marks, range.start + first.start, range.start + first.end);
        }
    });

    marks.sort((a, b) => a.start - b.start);
    let html = "";
    let cursor = 0;
    marks.forEach((mark) => {
        html += text(raw.slice(cursor, mark.start));
        html += `<strong>${text(raw.slice(mark.start, mark.end))}</strong>`;
        cursor = mark.end;
    });
    html += text(raw.slice(cursor));
    return html;
}

function toPoints(value) {
    if (Array.isArray(value)) {
        return value.flatMap(toPoints).filter(Boolean);
    }
    const raw = String(value == null ? "" : value).replace(/\r/g, "").trim();
    if (!raw || raw === "—") {
        return [];
    }

    let lines = raw.split(/\n+/)
        .map((line) => line.replace(/^\s*(?:[-*•●]|\d+[.)])\s*/, "").trim())
        .filter(Boolean);

    if (lines.length === 1) {
        const one = lines[0];
        const clause = one.match(/^(.*?\b(?:can|will|should)\s+(?:establish|become|operate|grow|build|develop|create|introduce|expand|achieve|aim to (?:achieve|become))\s+)(.+)$/i);
        if (clause && (clause[2].match(/,/g) || []).length >= 2) {
            const parts = clause[2]
                .replace(/\.$/, "")
                .split(/,\s*(?:and\s+)?|\s+and\s+/)
                .map((part) => part.trim())
                .filter((part) => part.length > 2);
            if (parts.length >= 3) {
                return parts.map((part) => part.charAt(0).toUpperCase() + part.slice(1));
            }
        }
        if (one.length > 140) {
            const sentences = one.split(/(?<=[.!?])\s+(?=[A-Z“"'])/).map((s) => s.trim()).filter(Boolean);
            if (sentences.length > 1) {
                return sentences;
            }
        }
    }

    return lines;
}

function renderPoints(id, value) {
    const el = document.getElementById(id);
    if (!el) return;
    const points = toPoints(value);
    el.innerHTML = points.length
        ? `<ul class="point-list">${points.map((point) => `<li>${emphasize(point)}</li>`).join("")}</ul>`
        : "";
}

function renderVision(visions) {
    const rows = Array.isArray(visions) ? visions : [];
    document.getElementById("visionTable").innerHTML = rows.map((item, index) => {
        const number = String(index + 1).padStart(2, "0");
        return `<tr>
            <td class="number">${number}</td>
            <td class="point-title">${emphasize(item.title)}</td>
            <td>${emphasize(item.explanation)}</td>
            <td>${emphasize(item.innovation)}</td>
            <td>${emphasize(item.example)}</td>
            <td>${emphasize(item.action)}</td>
            <td>${emphasize(item.benefit)}</td>
        </tr>`;
    }).join("");
}

function renderJourney(steps) {
    const el = document.getElementById("journeyFlow");
    if (!el) return;
    el.innerHTML = (steps || []).map((step, index) => {
        const arrow = index > 0 ? '<span class="arrow"> → </span>' : "";
        return `${arrow}<span class="journey-item">${emphasize(step)}</span>`;
    }).join("");
}

function applyLanguage(language) {
    const pack = languagePacks[language] || languagePacks.en || {};
    const ui = pack.ui || {};

    setText("pageSubtitle", ui.pageSubtitle);
    setText("labelBusinessName", ui.labelBusinessName);
    setText("labelCategory", ui.labelCategory);
    setText("labelIntroduction", ui.labelIntroduction);
    setText("labelProducts", ui.labelProducts);
    setText("thNo", ui.thNo);
    setText("thVision", ui.thVision);
    setText("thExplanation", ui.thExplanation);
    setText("thInnovation", ui.thInnovation);
    setText("thExample", ui.thExample);
    setText("thAction", ui.thAction);
    setText("thBenefit", ui.thBenefit);
    setText("journeyTitle", ui.journeyTitle);
    setText("threeYearTitle", ui.threeYearTitle);
    setText("fiveYearTitle", ui.fiveYearTitle);
    setText("tenYearTitle", ui.tenYearTitle);
    setText("finalTitle", (pack.final || {}).title);
    setText("pageFooter", ui.footer || "");

    renderPoints("introPoints", businessFacts.intro);
    renderPoints("productPoints", businessFacts.products);
    renderVision(pack.visions || []);
    renderJourney((pack.journey || {}).steps || []);
    renderPoints("journeyDescription", (pack.journey || {}).description);
    renderPoints("threeYearList", (pack.timeline || {}).three_year || []);
    renderPoints("fiveYearList", (pack.timeline || {}).five_year || []);
    renderPoints("tenYearList", (pack.timeline || {}).ten_year || []);
    renderPoints("finalText", (pack.final || {}).text);
}

function changeLanguage(language, button) {
    document.querySelectorAll(".language-buttons button").forEach(btn => btn.classList.remove("active"));
    button.classList.add("active");
    applyLanguage(language);
}

document.addEventListener("DOMContentLoaded", function () {
    applyLanguage("en");
});
</script>
</body>
</html>
