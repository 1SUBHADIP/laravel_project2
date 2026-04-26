<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="google-site-verification" content="1vt7G1ESZjsGEIHDy7jJbuyBrWh6NqoLuuzuMKsSDuQ" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CCLMS Library | Smart Circulation Hub</title>
    <meta name="description" content="CCLMS Library Management System keeps collections organized, members active, and lending workflows fast.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;500;600;700&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --paper: #fff9ef;
            --ink: #1b2838;
            --ink-soft: #33495f;
            --teal: #007b83;
            --sun: #ff9f1c;
            --clay: #f25f4c;
            --mint: #6dd3ce;
            --card: rgba(255, 255, 255, 0.78);
            --line: rgba(22, 44, 66, 0.13);
            --shadow: 0 25px 55px rgba(20, 39, 59, 0.15);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Sora", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 12% 18%, rgba(255, 159, 28, 0.22), transparent 35%),
                radial-gradient(circle at 85% 12%, rgba(0, 123, 131, 0.23), transparent 38%),
                radial-gradient(circle at 78% 80%, rgba(242, 95, 76, 0.16), transparent 42%),
                linear-gradient(160deg, #fffdf8, var(--paper));
            min-height: 100vh;
            overflow-x: hidden;
        }

        .grain {
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: 0.18;
            background-image: radial-gradient(rgba(20, 40, 60, 0.12) 0.6px, transparent 0.6px);
            background-size: 3px 3px;
            z-index: 0;
        }

        .shell {
            position: relative;
            z-index: 1;
            width: min(1160px, 92vw);
            margin: 0 auto;
            padding: 28px 0 54px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            margin-bottom: 34px;
        }

        .brand {
            display: inline-flex;
            gap: 12px;
            align-items: center;
            text-decoration: none;
            color: inherit;
        }

        .brand-badge {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--teal), #00a7b0);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 800;
            letter-spacing: 0.02em;
            box-shadow: 0 12px 24px rgba(0, 123, 131, 0.24);
            font-family: "Chakra Petch", sans-serif;
        }

        .brand-name {
            font-family: "Chakra Petch", sans-serif;
            font-size: 1.12rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .login-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #fff;
            padding: 11px 18px;
            border-radius: 999px;
            background: linear-gradient(120deg, var(--clay), #ff7a49);
            font-weight: 600;
            transition: transform 0.22s ease, box-shadow 0.22s ease;
            box-shadow: 0 14px 28px rgba(242, 95, 76, 0.28);
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 34px rgba(242, 95, 76, 0.35);
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 24px;
            align-items: stretch;
        }

        .hero {
            border: 1px solid var(--line);
            border-radius: 30px;
            padding: 34px;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.86), rgba(255, 250, 239, 0.78));
            box-shadow: var(--shadow);
            animation: riseIn 0.75s ease both;
        }

        .tag {
            display: inline-flex;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(0, 123, 131, 0.1);
            border: 1px solid rgba(0, 123, 131, 0.25);
            color: var(--teal);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            font-size: 0.74rem;
            font-weight: 700;
            margin-bottom: 16px;
            font-family: "Chakra Petch", sans-serif;
        }

        h1 {
            margin: 0;
            font-family: "Chakra Petch", sans-serif;
            font-size: clamp(2rem, 5.2vw, 3.9rem);
            letter-spacing: 0.02em;
            line-height: 1.05;
            max-width: 16ch;
        }

        .highlight {
            color: var(--teal);
            position: relative;
            white-space: nowrap;
        }

        .highlight::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0.06em;
            height: 0.2em;
            z-index: -1;
            border-radius: 30px;
            background: rgba(109, 211, 206, 0.72);
        }

        .subtitle {
            margin: 20px 0 24px;
            max-width: 48ch;
            color: var(--ink-soft);
            line-height: 1.72;
            font-size: 0.98rem;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .btn-primary,
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            border-radius: 14px;
            padding: 12px 16px;
            font-weight: 600;
            transition: transform 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(120deg, var(--teal), #00a2ab);
            color: #fff;
            box-shadow: 0 10px 24px rgba(0, 123, 131, 0.22);
        }

        .btn-secondary {
            border: 1px solid rgba(27, 40, 56, 0.2);
            color: var(--ink);
            background: rgba(255, 255, 255, 0.78);
        }

        .btn-primary:hover,
        .btn-secondary:hover {
            transform: translateY(-2px);
        }

        .catalog-card {
            border-radius: 30px;
            border: 1px solid var(--line);
            background: linear-gradient(150deg, rgba(255, 255, 255, 0.86), rgba(255, 245, 225, 0.88));
            box-shadow: var(--shadow);
            padding: 22px;
            overflow: hidden;
            position: relative;
            animation: riseIn 0.85s ease both;
        }

        .catalog-card::before {
            content: "";
            position: absolute;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            right: -30px;
            top: -40px;
            background: radial-gradient(circle at center, rgba(255, 159, 28, 0.34), rgba(255, 159, 28, 0));
        }

        .shelf {
            margin-top: 14px;
            border: 1px solid rgba(29, 47, 67, 0.14);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.78);
            padding: 14px;
        }

        .shelf-item {
            display: grid;
            grid-template-columns: 42px 1fr auto;
            gap: 12px;
            align-items: center;
            padding: 11px 0;
            border-bottom: 1px dashed rgba(29, 47, 67, 0.14);
            animation: reveal 0.65s ease both;
        }

        .shelf-item:last-child {
            border-bottom: 0;
        }

        .shelf-item:nth-child(2) { animation-delay: 120ms; }
        .shelf-item:nth-child(3) { animation-delay: 210ms; }

        .spine {
            width: 42px;
            height: 56px;
            border-radius: 11px;
            color: #fff;
            font-family: "Chakra Petch", sans-serif;
            font-size: 0.66rem;
            letter-spacing: 0.08em;
            display: flex;
            align-items: end;
            justify-content: center;
            padding-bottom: 7px;
        }

        .spine-teal { background: linear-gradient(160deg, #008a93, #00656c); }
        .spine-sun { background: linear-gradient(160deg, #ff9f1c, #d97f00); }
        .spine-clay { background: linear-gradient(160deg, #f25f4c, #cb4634); }

        .shelf-title {
            margin: 0;
            font-size: 0.92rem;
            font-weight: 600;
        }

        .shelf-meta {
            margin: 3px 0 0;
            font-size: 0.78rem;
            color: var(--ink-soft);
        }

        .pill {
            font-size: 0.72rem;
            padding: 5px 8px;
            border-radius: 999px;
            border: 1px solid;
            white-space: nowrap;
            font-weight: 600;
            font-family: "Chakra Petch", sans-serif;
        }

        .pill-good {
            color: #066b62;
            border-color: rgba(6, 107, 98, 0.3);
            background: rgba(109, 211, 206, 0.22);
        }

        .pill-alert {
            color: #9e4e00;
            border-color: rgba(158, 78, 0, 0.34);
            background: rgba(255, 159, 28, 0.2);
        }

        .stats {
            margin-top: 22px;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .stat {
            border: 1px solid var(--line);
            border-radius: 18px;
            background: var(--card);
            padding: 18px;
            box-shadow: 0 8px 24px rgba(26, 43, 61, 0.08);
            animation: reveal 0.7s ease both;
        }

        .stat:nth-child(2) { animation-delay: 90ms; }
        .stat:nth-child(3) { animation-delay: 180ms; }
        .stat:nth-child(4) { animation-delay: 270ms; }

        .stat-label {
            margin: 0;
            font-size: 0.8rem;
            color: var(--ink-soft);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            font-family: "Chakra Petch", sans-serif;
        }

        .stat-value {
            margin: 8px 0 0;
            font-size: 1.75rem;
            font-weight: 800;
            font-family: "Chakra Petch", sans-serif;
        }

        .panel {
            margin-top: 22px;
            border-radius: 24px;
            border: 1px solid var(--line);
            background: linear-gradient(140deg, rgba(255, 255, 255, 0.76), rgba(255, 244, 214, 0.55));
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .panel-card {
            border-radius: 16px;
            border: 1px solid rgba(24, 45, 63, 0.14);
            background: rgba(255, 255, 255, 0.72);
            padding: 15px;
            backdrop-filter: blur(2px);
        }

        .panel-card h3 {
            margin: 0 0 6px;
            font-size: 0.95rem;
            font-family: "Chakra Petch", sans-serif;
            letter-spacing: 0.03em;
        }

        .panel-card p {
            margin: 0;
            color: var(--ink-soft);
            font-size: 0.85rem;
            line-height: 1.6;
        }

        .footer {
            margin-top: 26px;
            text-align: center;
            color: rgba(32, 50, 68, 0.72);
            font-size: 0.86rem;
        }

        .orbit {
            position: absolute;
            border-radius: 50%;
            z-index: -1;
            filter: blur(1px);
            animation: drift 6.5s ease-in-out infinite;
        }

        .orbit-a {
            width: 18px;
            height: 18px;
            background: var(--sun);
            right: 15%;
            top: 16%;
        }

        .orbit-b {
            width: 14px;
            height: 14px;
            background: var(--teal);
            left: 12%;
            bottom: 20%;
            animation-delay: 800ms;
        }

        @keyframes drift {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        @keyframes riseIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes reveal {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1040px) {
            .hero-grid {
                grid-template-columns: 1fr;
            }

            .catalog-card {
                order: 2;
            }

            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .panel {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 680px) {
            .shell {
                width: min(1160px, 94vw);
                padding-top: 20px;
            }

            .topbar {
                margin-bottom: 20px;
            }

            .hero,
            .catalog-card {
                border-radius: 22px;
                padding: 20px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            h1 {
                font-size: clamp(1.9rem, 11vw, 2.55rem);
            }

            .subtitle {
                font-size: 0.92rem;
                line-height: 1.65;
            }
        }
    </style>
</head>
<body>
    <h1>Library Management System in Laravel</h1>

<p>
This Library Management System is a college project developed using Laravel and PostgreSQL. 
It helps manage books, members, issue-return system and admin dashboard efficiently.
</p>
    <div class="grain"></div>
    <span class="orbit orbit-a"></span>
    <span class="orbit orbit-b"></span>

    <div class="shell">
        <header class="topbar">
            <a href="{{ url('/') }}" class="brand" aria-label="CCLMS Home">
                <span class="brand-badge">CL</span>
                <span class="brand-name">CCLMS Library</span>
            </a>
            <a href="{{ route('admin.login') }}" class="login-btn">Admin Login</a>
        </header>

        <section class="hero-grid" aria-label="Landing hero">
            <article class="hero">
                <span class="tag">Campus Circulation Command</span>
                <h1>
                    Your <span class="highlight">library flow</span>,
                    measured and managed in one place.
                </h1>
                <p class="subtitle">
                    CCLMS blends catalog control, member activity, and borrowing intelligence into a single workspace.
                    Track every book movement, reduce overdue risk, and keep your team focused on service instead of spreadsheets.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('admin.login') }}" class="btn-primary">Open Admin Console</a>
                    <a href="{{ route('admin.password.request') }}" class="btn-secondary">Forgot Password</a>
                </div>
            </article>

            <aside class="catalog-card" aria-label="Live lending snapshot">
                <h2 style="margin:0;font-family:'Chakra Petch',sans-serif;letter-spacing:.04em;">Live Lending Snapshot</h2>
                <p style="margin:6px 0 0;color:var(--ink-soft);font-size:.85rem;">Real-time activity pulse from your circulation desk.</p>

                <div class="shelf">
                    <div class="shelf-item">
                        <div class="spine spine-teal">QA</div>
                        <div>
                            <p class="shelf-title">Data Structures in Practice</p>
                            <p class="shelf-meta">Returning in 2 days</p>
                        </div>
                        <span class="pill pill-good">On Time</span>
                    </div>
                    <div class="shelf-item">
                        <div class="spine spine-sun">HIS</div>
                        <div>
                            <p class="shelf-title">History of Modern States</p>
                            <p class="shelf-meta">Returned yesterday</p>
                        </div>
                        <span class="pill pill-good">Closed</span>
                    </div>
                    <div class="shelf-item">
                        <div class="spine spine-clay">LIT</div>
                        <div>
                            <p class="shelf-title">Contemporary Fiction Atlas</p>
                            <p class="shelf-meta">Due today</p>
                        </div>
                        <span class="pill pill-alert">Watch</span>
                    </div>
                </div>
            </aside>
        </section>

        <section class="stats" aria-label="Library statistics">
            <article class="stat">
                <p class="stat-label">Total Books</p>
                <p class="stat-value">{{ number_format($totalBooks) }}</p>
            </article>
            <article class="stat">
                <p class="stat-label">Registered Members</p>
                <p class="stat-value">{{ number_format($totalMembers) }}</p>
            </article>
            <article class="stat">
                <p class="stat-label">Active Loans</p>
                <p class="stat-value">{{ number_format($activeLoans) }}</p>
            </article>
            <article class="stat">
                <p class="stat-label">Overdue Loans</p>
                <p class="stat-value">{{ number_format($overdueLoans) }}</p>
            </article>
        </section>

        <section class="panel" aria-label="Core capabilities">
            <article class="panel-card">
                <h3>Catalog Clarity</h3>
                <p>Keep metadata, categories, and availability synchronized across your collection lifecycle.</p>
            </article>
            <article class="panel-card">
                <h3>Member Rhythm</h3>
                <p>Support responsible borrowing with transparent due tracking and instant profile lookup.</p>
            </article>
            <article class="panel-card">
                <h3>Actionable Reports</h3>
                <p>Read overdue trends and circulation pressure points before they become bottlenecks.</p>
            </article>
        </section>

        <p class="footer">CCLMS Library Management System</p>
    </div>
</body>
</html>
