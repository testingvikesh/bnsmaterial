<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $facts['business_name'] }} — 32 Gun Website Draft</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0a1d37;
            --brand: #ff6b00;
            --gold: #ffb800;
            --line: #e8edf3;
            --verified: #15803d;
            --inferred: #1d4ed8;
            --suggested: #7c3aed;
            --required: #b91c1c;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0 0 88px;
            font-family: "Plus Jakarta Sans", Arial, sans-serif;
            background: #eef3f8;
            color: #0a1d37;
            font-size: 16px;
        }
        .page { max-width: 1180px; margin: 0 auto; }
        .hero {
            position: sticky; top: 0; z-index: 40;
            background: linear-gradient(135deg, #050b14 0%, #0a1d37 72%, #123056 100%);
            color: #fff; padding: 20px 28px 16px;
            border-bottom: 3px solid #ff6b00;
        }
        .hero small { color: #ffb800; letter-spacing: .08em; text-transform: uppercase; font-weight: 800; font-size: 12px; }
        .hero h1 { margin: 6px 0 4px; font-size: 30px; }
        .hero p { margin: 0; color: #ffb800; font-weight: 700; }
        .bar { display: flex; flex-wrap: wrap; gap: 8px; padding: 16px 24px 0; }
        .bar a {
            background: #fff; color: #0a1d37; padding: 8px 12px; border-radius: 999px;
            font-size: 12px; font-weight: 800; text-decoration: none;
            box-shadow: 0 8px 20px rgba(10, 29, 55, .08);
        }
        .facts, .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; padding: 16px 24px 0; }
        .card {
            background: #fff; border: 1px solid var(--line); border-radius: 16px;
            padding: 16px 18px; box-shadow: 0 10px 28px rgba(10, 29, 55, .06);
            position: relative; overflow: hidden;
        }
        .card::before {
            content: ""; position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
            background: linear-gradient(180deg, #ffb800, #ff6b00);
        }
        .wide { grid-column: 1 / -1; }
        .label { display: block; color: #ff6b00; font-size: 12px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 6px; }
        .section { padding: 12px 24px 0; }
        .section-card { border-top: 4px solid #ff6b00; }
        .section-head { display: flex; justify-content: space-between; gap: 12px; align-items: flex-start; flex-wrap: wrap; }
        .section-head h2 { margin: 0; color: #ff6b00; font-size: 1.15rem; }
        .badge {
            display: inline-flex; align-items: center; border-radius: 999px;
            padding: 4px 10px; font-size: 11px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase;
        }
        .badge.verified { background: #dcfce7; color: var(--verified); }
        .badge.inferred { background: #dbeafe; color: var(--inferred); }
        .badge.suggested { background: #ede9fe; color: var(--suggested); }
        .badge.required { background: #fee2e2; color: var(--required); }
        .block { margin: 12px 0 0; padding-top: 10px; border-top: 1px solid #f1f5f9; }
        .block:first-of-type { border-top: 0; }
        .block strong { display: block; color: #c2410c; font-size: 13px; margin-bottom: 4px; }
        .block .text { white-space: pre-wrap; line-height: 1.6; }
        .report { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
        .report div { background: #fff7ed; border: 1px solid #fdba74; border-radius: 12px; padding: 12px; }
        .journey { margin: 0; padding-left: 18px; }
        .journey li { margin: 0 0 8px; }
        .actions {
            position: fixed; left: 0; right: 0; bottom: 0; z-index: 50;
            display: flex; justify-content: center; gap: 8px; flex-wrap: wrap;
            background: #0a1d37; padding: 12px 16px;
            border-top: 3px solid #ff6b00;
        }
        .actions button {
            border: 0; border-radius: 999px; padding: 10px 14px; font-weight: 800; cursor: pointer;
            background: #fff; color: #0a1d37;
        }
        .actions button.active { background: #ff6b00; color: #fff; }
        .notice, .missing {
            display: none; margin: 12px 24px 0; padding: 14px 16px;
            border-radius: 12px; background: #fff7ed; border: 1px solid #fdba74;
        }
        .missing input, .missing textarea {
            width: 100%; margin: 6px 0 10px; padding: 8px 10px; border: 1px solid #fed7aa; border-radius: 8px;
            font-family: inherit;
        }
        .status-line { padding: 8px 24px 0; font-weight: 700; color: #15803d; display: none; }
        @media (max-width: 800px) {
            .facts, .grid, .report { grid-template-columns: 1fr 1fr; }
            .hero h1 { font-size: 22px; }
        }
        @media print {
            .hero, .actions, .bar { position: static; }
            .actions { display: none !important; }
            body { margin: 0; }
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
    $statusLabel = [
        'verified' => 'Verified',
        'inferred' => 'AI-Inferred',
        'suggested' => 'AI-Suggested',
        'required' => 'Information Required',
    ];
@endphp
<div class="page">
    <header class="hero">
        <small>BNS 32 Gun — Complete Business Website Draft</small>
        <h1>{{ $plan['business_name'] ?? $facts['business_name'] }}</h1>
        <p>3 inputs → 32 sections → member approval → publish</p>
    </header>

    <p class="status-line" id="workflowStatus"></p>
    <div class="notice" id="regenNotice">Use Material Admin → <strong>Regenerate</strong> after new member information is saved. This draft will rebuild from Business Name + Category + Main Product.</div>
    <form class="missing" id="missingForm">
        <strong>Add information that was not invented</strong>
        <input name="founder" placeholder="Founder background">
        <input name="story" placeholder="Business story / year started">
        <input name="email" placeholder="Email">
        <input name="hours" placeholder="Business hours">
        <textarea name="proof" rows="3" placeholder="Verified reviews, awards, portfolio or prices (only real facts)"></textarea>
        <button type="submit">Save on this draft</button>
    </form>

    <div class="bar">
        <a href="#profile">Profile</a>
        @foreach($sections as $section)
            <a href="#sec-{{ $section['code'] }}">{{ $section['no'] }}</a>
        @endforeach
        <a href="#seo">SEO</a>
        <a href="#journey">Journey</a>
        <a href="#report">Report</a>
    </div>

    <section class="facts" id="profile">
        @foreach($profile as $key => $item)
            @php $item = is_array($item) ? $item : ['text' => $item, 'status' => 'inferred']; @endphp
            <div class="card {{ in_array($key, ['business_description','customer_need','market_context'], true) ? 'wide' : '' }}">
                <span class="label">{{ str_replace('_', ' ', $key) }}</span>
                <div>{{ $item['text'] ?? '' }}</div>
                <div style="margin-top:8px"><span class="badge {{ $item['status'] ?? 'inferred' }}">{{ $statusLabel[$item['status'] ?? 'inferred'] ?? $item['status'] }}</span></div>
            </div>
        @endforeach
    </section>

    @foreach($sections as $section)
        <section class="section" id="sec-{{ $section['code'] }}">
            <article class="card section-card">
                <div class="section-head">
                    <h2>{{ $section['no'] }}. {{ $section['title'] }}</h2>
                    <span class="badge {{ $section['status'] }}">{{ $statusLabel[$section['status']] ?? $section['status'] }}</span>
                </div>
                @foreach(($section['blocks'] ?? []) as $block)
                    <div class="block">
                        @if(($block['label'] ?? '') !== '')
                            <strong>{{ $block['label'] }}</strong>
                        @endif
                        <div class="text" data-editable="1">{{ $block['text'] ?? '' }}</div>
                        <span class="badge {{ $block['status'] ?? 'inferred' }}">{{ $statusLabel[$block['status'] ?? 'inferred'] ?? ($block['status'] ?? '') }}</span>
                    </div>
                @endforeach
            </article>
        </section>
    @endforeach

    <section class="section" id="seo">
        <article class="card section-card">
            <h2>SEO Auto-Generation</h2>
            <div class="grid" style="padding:12px 0 0">
                @foreach($seo as $key => $value)
                    <div class="card">
                        <span class="label">{{ str_replace('_', ' ', $key) }}</span>
                        <div>{{ $value }}</div>
                    </div>
                @endforeach
            </div>
        </article>
    </section>

    <section class="section" id="journey">
        <article class="card section-card">
            <h2>Customer Journey Audit</h2>
            <ol class="journey">
                @foreach($journey as $item)
                    <li>
                        <strong>{{ $item['question'] }}</strong>
                        — {{ $item['answer'] }}
                        <span class="badge {{ $item['status'] }}">{{ $statusLabel[$item['status']] ?? $item['status'] }}</span>
                    </li>
                @endforeach
            </ol>
        </article>
    </section>

    <section class="section" id="report" style="padding-bottom:28px">
        <article class="card section-card">
            <h2>Website Completion Report</h2>
            <div class="report">
                <div><span class="label">Business Profile</span><strong>{{ $report['business_profile'] ?? 'Pending' }}</strong></div>
                <div><span class="label">Content</span><strong>{{ $report['content'] ?? '0/32 Sections' }}</strong></div>
                <div><span class="label">USP</span><strong>{{ $report['usp'] ?? 'Pending' }}</strong></div>
                <div><span class="label">Product</span><strong>{{ $report['product'] ?? 'Pending' }}</strong></div>
                <div><span class="label">Trust</span><strong>{{ $report['trust'] ?? 'Pending' }}</strong></div>
                <div><span class="label">SEO</span><strong>{{ $report['seo'] ?? 'Pending' }}</strong></div>
                <div><span class="label">Contact</span><strong>{{ $report['contact'] ?? 'Pending' }}</strong></div>
                <div><span class="label">Overall Readiness</span><strong>{{ $report['overall'] ?? '0%' }}</strong></div>
            </div>
        </article>
    </section>
</div>

<div class="actions">
    <button type="button" data-action="approve">✓ Approve</button>
    <button type="button" data-action="edit">✏️ Edit</button>
    <button type="button" data-action="regenerate">🔄 Regenerate</button>
    <button type="button" data-action="add">📌 Add Information</button>
    <button type="button" data-action="publish">🚀 Publish</button>
</div>
<script>
(function () {
    const status = document.getElementById('workflowStatus');
    const regen = document.getElementById('regenNotice');
    const missing = document.getElementById('missingForm');
    let approved = false;
    let editing = false;
    function setStatus(text, ok) {
        status.style.display = 'block';
        status.style.color = ok ? '#15803d' : '#b91c1c';
        status.textContent = text;
    }
    document.querySelectorAll('.actions button').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const action = btn.getAttribute('data-action');
            document.querySelectorAll('.actions button').forEach(function (el) { el.classList.remove('active'); });
            btn.classList.add('active');
            regen.style.display = action === 'regenerate' ? 'block' : 'none';
            missing.style.display = action === 'add' ? 'block' : 'none';
            if (action === 'approve') {
                approved = true;
                setStatus('Draft approved. Member can still edit or add information, then publish.', true);
            }
            if (action === 'edit') {
                editing = !editing;
                document.querySelectorAll('[data-editable]').forEach(function (el) {
                    el.contentEditable = editing ? 'true' : 'false';
                    el.style.outline = editing ? '1px dashed #ff6b00' : 'none';
                });
                setStatus(editing ? 'Edit mode on. Change any block, then Approve.' : 'Edit mode off.', true);
            }
            if (action === 'regenerate') {
                setStatus('Regenerate from Material Admin after saving new member facts. Do not invent missing facts here.', false);
            }
            if (action === 'add') {
                setStatus('Add only real information. This draft will not invent reviews, prices or awards.', true);
            }
            if (action === 'publish') {
                if (!approved) {
                    setStatus('Approve the draft before publish.', false);
                    return;
                }
                setStatus('Website draft marked ready to publish.', true);
            }
        });
    });
    missing.addEventListener('submit', function (e) {
        e.preventDefault();
        const data = new FormData(missing);
        const lines = [];
        data.forEach(function (value, key) {
            if (String(value).trim()) lines.push(key + ': ' + value);
        });
        setStatus(lines.length ? 'Added to this local draft: ' + lines.join(' | ') : 'No extra information entered.', true);
    });
})();
</script>
</body>
</html>
