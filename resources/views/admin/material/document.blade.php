<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $facts['member_name'] }} — {{ $session->name }}</title>
    <style>
        :root {
            --brand: #ff6b00;
            --navy: #0a1d37;
            --gold: #ffb800;
            --muted: #64748b;
            --border: #e8edf3;
            --bg: #eef3f8;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            color: var(--navy);
            background: var(--bg);
            line-height: 1.6;
            font-size: 17px;
        }
        .wrap { max-width: 1080px; margin: 0 auto; padding: 28px 20px 48px; }
        .hero {
            background: linear-gradient(135deg, #050b14 0%, #0a1d37 72%, #123056 100%);
            color: #fff;
            border-radius: 20px;
            padding: 30px 32px;
            margin-bottom: 20px;
            border-bottom: 3px solid #ff6b00;
            box-shadow: 0 16px 40px rgba(10, 29, 55, .18);
        }
        .hero small { color: #ffb800; letter-spacing: .08em; text-transform: uppercase; font-weight: 800; font-size: .9rem; }
        .hero h1 { margin: 10px 0 8px; font-size: 2.15rem; font-weight: 800; }
        .hero p { margin: 0; opacity: .88; font-size: 1.05rem; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }
        .card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px 22px;
            box-shadow: 0 10px 28px rgba(10, 29, 55, .06);
            position: relative;
            overflow: hidden;
            min-height: min-content;
        }
        .card::before {
            content: "";
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, #ffb800, #ff6b00);
        }
        .card h2 {
            margin: 0 0 14px;
            font-size: 1.2rem;
            color: #ff6b00;
            font-weight: 800;
        }
        .row { margin-bottom: 12px; }
        .row:last-child { margin-bottom: 0; }
        .label { display: block; color: #ff6b00; font-size: .85rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 4px; }
        .value { white-space: pre-wrap; word-break: break-word; font-size: 1.08rem; color: #0a1d37; }
        .prompt {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 12px;
            padding: 16px 18px;
            white-space: pre-wrap;
            word-break: break-word;
            font-size: 1.02rem;
            max-height: 420px;
            overflow: auto;
        }
        .wide { grid-column: 1 / -1; }
        .idea-grid { display: grid; grid-template-columns: 1fr; gap: 14px; }
        .idea-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px 18px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(10, 29, 55, .06);
        }
        .idea-card span {
            display: inline-block;
            background: #ff6b00;
            color: #fff;
            font-size: .82rem;
            font-weight: 800;
            letter-spacing: .04em;
            text-transform: uppercase;
            border-radius: 999px;
            padding: .28rem .75rem;
            margin-bottom: 10px;
        }
        .idea-card h3 { margin: 0 0 10px; font-size: 1.2rem; color: #0a1d37; }
        .idea-card p { margin: 0 0 12px; font-size: 1.05rem; }
        .idea-card ol { margin: 0; padding-left: 1.3rem; }
        .idea-card li { margin-bottom: 6px; font-size: 1.02rem; }
        footer { margin-top: 18px; color: var(--muted); font-size: .95rem; }
        @media (max-width: 700px) {
            .grid { grid-template-columns: 1fr; }
            .wide { grid-column: auto; }
        }
        @media print {
            body { background: #fff; }
            .hero { break-inside: avoid; }
        }
    </style>
</head>
<body>
<div class="wrap">
    <header class="hero">
        <small>Session Material</small>
        <h1>{{ $session->name }}</h1>
        <p>{{ $prompt->title }} · {{ $facts['member_name'] }}</p>
    </header>

    <div class="grid">
        <section class="card">
            <h2>Member</h2>
            <div class="row"><span class="label">Name</span><div class="value">{{ $facts['member_name'] }}</div></div>
            <div class="row"><span class="label">Mobile</span><div class="value">{{ $facts['phone'] }}</div></div>
            <div class="row"><span class="label">Member Id</span><div class="value">{{ $facts['member_id'] }}</div></div>
        </section>
        <section class="card">
            <h2>Business</h2>
            <div class="row"><span class="label">Business Name</span><div class="value">{{ $facts['business_name'] }}</div></div>
            <div class="row"><span class="label">Business Category</span><div class="value">{{ $facts['category'] }}</div></div>
            <div class="row"><span class="label">Location</span><div class="value">{{ $facts['location'] }}</div></div>
        </section>
        <section class="card wide">
            <h2>Business Details</h2>
            <div class="row"><span class="label">Address</span><div class="value">{{ $facts['address'] }}</div></div>
            <div class="row"><span class="label">Business Introduction</span><div class="value">{{ $facts['intro'] }}</div></div>
            <div class="row"><span class="label">Business Main Product</span><div class="value">{{ $facts['products'] }}</div></div>
        </section>
        @php
            $sessionDetails = trim((string) ($facts['session_details'] ?? ''));
            $showSessionDetails = $sessionDetails !== ''
                && $sessionDetails !== '—'
                && strcasecmp($sessionDetails, (string) $session->name) !== 0;
        @endphp
        @if($showSessionDetails)
        <section class="card wide">
            <h2>Session Details</h2>
            <div class="value">{{ $sessionDetails }}</div>
        </section>
        @endif
        @if(!empty($ideas))
        <section class="card wide">
            <h2>Generated Material</h2>
            <div class="idea-grid">
                @foreach(range(1, 25) as $n)
                    @php $idea = is_array($ideas[$n] ?? null) ? $ideas[$n] : []; @endphp
                    <article class="idea-card">
                        <span>Idea {{ str_pad((string) $n, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3>{{ $idea['name'] ?? ('Business idea '.$n) }}</h3>
                        <p>{{ $idea['concept'] ?? '' }}</p>
                        <ol>
                            @foreach(array_slice($idea['product_ideas'] ?? [], 0, 10) as $offer)
                                <li>{{ $offer }}</li>
                            @endforeach
                        </ol>
                    </article>
                @endforeach
            </div>
        </section>
        @endif
    </div>

    <footer>Generated on {{ $generatedAt->format('d M Y H:i') }} · {{ config('app.name') }}</footer>
</div>
</body>
</html>
