<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Badeth Laundry Shop' }}</title>
    <style>
        /* ============================================================
           DESIGN TOKENS - Edit here to restyle the entire product.
           ============================================================ */
        :root {
            /* Brand */
            --color-brand: hsl(124, 74%, 25%);
            --color-brand-light: hsl(116, 45%, 95%);
            --color-brand-dark: hsl(125, 78%, 18%);
            --color-brand-soft: hsl(110, 38%, 86%);
            --color-accent: hsl(47, 96%, 52%);
            --color-accent-soft: hsl(48, 100%, 93%);

            /* Surfaces */
            --color-canvas: hsl(96, 30%, 98%);
            --color-surface: hsl(0, 0%, 100%);
            --color-overlay: hsl(105, 28%, 95%);
            --color-sidebar: hsl(126, 70%, 16%);

            /* Text */
            --color-text-primary: hsl(214, 32%, 14%);
            --color-text-secondary: hsl(213, 18%, 42%);
            --color-text-muted: hsl(214, 12%, 58%);
            --color-on-brand: hsl(0, 0%, 100%);

            /* Borders */
            --color-border: hsl(104, 18%, 80%);
            --color-border-strong: hsl(103, 18%, 62%);

            /* Semantic */
            --color-success-bg: hsl(145, 55%, 93%);
            --color-success-text: hsl(148, 60%, 25%);
            --color-danger-bg: hsl(6, 82%, 95%);
            --color-danger-text: hsl(5, 72%, 34%);
            --color-warning-bg: var(--color-accent-soft);
            --color-warning-text: hsl(43, 92%, 25%);

            /* Shadows */
            --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.03);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.07), 0 2px 4px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.09), 0 4px 8px rgba(0, 0, 0, 0.05);

            /* Radius */
            --radius-xs: 4px;
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 18px;
            --radius-full: 9999px;

            /* Transitions */
            --transition-fast: 100ms ease;
            --transition-base: 180ms ease;
            --transition-slow: 320ms ease;

            /* Type scale */
            --text-xs: 11px;
            --text-sm: 13px;
            --text-base: 15px;
            --text-md: 17px;
            --text-lg: 20px;
            --text-xl: 24px;
            --text-2xl: 30px;
            --text-3xl: 40px;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Segoe UI", Inter, system-ui, -apple-system, BlinkMacSystemFont, Arial, sans-serif;
            font-size: var(--text-base);
            background: linear-gradient(135deg, var(--color-canvas), var(--color-accent-soft));
            color: var(--color-text-primary);
            letter-spacing: 0;
        }
        a { color: inherit; text-decoration: none; }
        .shell { display: grid; grid-template-columns: 282px 1fr; min-height: 100vh; }
        .sidebar {
            color: var(--color-on-brand);
            padding: 24px 18px;
            background: linear-gradient(180deg, var(--color-brand), var(--color-sidebar));
            border-right: 1px solid color-mix(in srgb, var(--color-accent) 28%, transparent);
            box-shadow: var(--shadow-md);
        }
        .brand-wrap { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-lg);
            display: grid;
            place-items: center;
            background: var(--color-surface);
            color: var(--color-brand-dark);
            font-weight: 900;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            flex: 0 0 auto;
        }
        .brand-logo { width: 100%; height: 100%; object-fit: cover; display: block; }
        .brand { font-size: var(--text-lg); font-weight: 800; line-height: 1.05; }
        .brand-subtitle { color: var(--color-brand-soft); font-size: var(--text-xs); margin-top: 3px; }
        .muted { color: var(--color-text-secondary); font-size: var(--text-sm); }
        .sidebar .muted { color: var(--color-brand-soft); margin-bottom: 28px; }
        .nav { margin-top: 24px; }
        .nav a, .logout {
            display: block;
            width: 100%;
            padding: 12px 14px;
            border-radius: var(--radius-sm);
            margin-bottom: 7px;
            color: var(--color-on-brand);
            background: transparent;
            border: 1px solid transparent;
            text-align: left;
            font: inherit;
            font-weight: 650;
            cursor: pointer;
            transition:
                color var(--transition-base),
                background-color var(--transition-base),
                border-color var(--transition-base),
                box-shadow var(--transition-base),
                transform var(--transition-base),
                opacity var(--transition-base);
        }
        .nav a:hover, .logout:hover {
            background: color-mix(in srgb, var(--color-on-brand) 12%, transparent);
            border-color: color-mix(in srgb, var(--color-on-brand) 24%, transparent);
            transform: translateX(2px);
        }
        .main { padding: 30px; }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            gap: 16px;
            padding: 20px 22px;
            background: color-mix(in srgb, var(--color-surface) 88%, transparent);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            backdrop-filter: blur(10px);
        }
        h1 { margin: 0; font-size: var(--text-2xl); font-weight: 800; color: var(--color-text-primary); }
        h2 { margin: 0 0 16px; font-size: var(--text-lg); color: var(--color-text-primary); }
        .grid { display: grid; gap: 16px; }
        .grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            padding: 20px;
            box-shadow: var(--shadow-sm);
            transition:
                border-color var(--transition-base),
                box-shadow var(--transition-base),
                transform var(--transition-base);
        }
        .card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
        .grid-4 .card { border-top: 3px solid var(--color-accent); }
        .metric { font-size: var(--text-2xl); font-weight: 850; margin-top: 8px; color: var(--color-text-primary); }
        table { width: 100%; border-collapse: collapse; background: var(--color-surface); border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--color-border); }
        th, td { padding: 13px 14px; border-bottom: 1px solid var(--color-border); text-align: left; vertical-align: top; }
        tr:last-child td { border-bottom: 0; }
        tbody tr:hover { background: var(--color-brand-light); }
        th { background: var(--color-overlay); font-size: var(--text-xs); color: var(--color-text-secondary); text-transform: uppercase; letter-spacing: .04em; }
        input, select, textarea {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-sm);
            font: inherit;
            background: var(--color-canvas);
            color: var(--color-text-primary);
            outline: none;
            transition:
                border-color var(--transition-base),
                box-shadow var(--transition-base),
                background-color var(--transition-base);
        }
        input:focus, select:focus, textarea:focus { border-color: var(--color-brand); box-shadow: 0 0 0 4px color-mix(in srgb, var(--color-brand) 18%, transparent); background: var(--color-surface); }
        label { display: block; margin-bottom: 6px; font-weight: 750; font-size: var(--text-sm); color: var(--color-text-primary); }
        .field { margin-bottom: 14px; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 15px;
            border: 0;
            border-radius: var(--radius-sm);
            background: var(--color-brand);
            color: var(--color-on-brand);
            font-weight: 800;
            cursor: pointer;
            box-shadow: none;
            transition:
                color var(--transition-fast),
                background-color var(--transition-fast),
                border-color var(--transition-fast),
                box-shadow var(--transition-fast),
                transform var(--transition-fast),
                opacity var(--transition-fast);
        }
        .btn:hover { background: var(--color-brand-dark); box-shadow: var(--shadow-xs); transform: scale(1.01); }
        .btn.secondary { background: var(--color-text-secondary); box-shadow: none; }
        .btn.light { background: var(--color-brand-light); color: var(--color-brand-dark); box-shadow: none; border: 1px solid var(--color-border-strong); }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
        .badge { display: inline-block; padding: 5px 9px; border-radius: var(--radius-full); background: var(--color-brand-light); color: var(--color-brand-dark); font-size: var(--text-xs); font-weight: 800; border: 1px solid var(--color-border-strong); transition: background-color var(--transition-base); }
        .badge.good { background: var(--color-success-bg); color: var(--color-success-text); border-color: color-mix(in srgb, var(--color-success-text) 32%, transparent); }
        .badge.warn { background: var(--color-warning-bg); color: var(--color-warning-text); border-color: color-mix(in srgb, var(--color-warning-text) 32%, transparent); }
        .badge.bad { background: var(--color-danger-bg); color: var(--color-danger-text); border-color: color-mix(in srgb, var(--color-danger-text) 32%, transparent); }
        .alert { padding: 12px 14px; border-radius: var(--radius-md); margin-bottom: 16px; border: 1px solid var(--color-border-strong); }
        .alert.success { background: var(--color-success-bg); color: var(--color-success-text); }
        .alert.error { background: var(--color-danger-bg); color: var(--color-danger-text); }
        .login {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 20px;
            background: linear-gradient(135deg, var(--color-brand), var(--color-sidebar));
        }
        .login .card { width: min(440px, 100%); padding: 26px; }
        .login h1 { font-size: var(--text-2xl); }
        .info-panel { background: var(--color-brand-light); border: 1px solid var(--color-border-strong); border-radius: var(--radius-md); padding: 14px; }
        .auth-brand { color: var(--color-text-primary); margin-bottom: 18px; }
        .flush-title { margin: 0; }
        .flush-copy { margin: 4px 0 0; }
        .copy-line { margin: 0; }
        .copy-line + .copy-line { margin-top: 6px; }
        .top-space { margin-top: 16px; }
        .bottom-space { margin-bottom: 16px; }
        .section-title { margin-top: 22px; }
        .narrow-panel { max-width: 520px; }
        .search-field { max-width: 280px; }
        .filter-field { max-width: 220px; }
        .field-gap { margin-bottom: 6px; }
        .field-gap-top { margin-top: 6px; }
        .checkbox-row { display: flex; gap: 10px; align-items: center; margin-bottom: 10px; }
        .checkbox-row input { width: auto; }
        @media (max-width: 900px) {
            .shell { grid-template-columns: 1fr; }
            .sidebar { position: static; }
            .grid-4, .grid-2 { grid-template-columns: 1fr; }
            .main { padding: 18px; }
        }
    </style>
</head>
<body>
@auth
    <div class="shell">
        <aside class="sidebar">
            <div class="brand-wrap">
                <div class="brand-mark">
                    <img class="brand-logo" src="{{ asset('images/badeth-laundry-logo.png') }}" alt="Badeth Laundry Shop logo">
                </div>
                <div>
                    <div class="brand">Badeth Laundry Shop</div>
                    <div class="brand-subtitle">Laundry branch operations</div>
                </div>
            </div>
            <div class="muted">
                @if(auth()->user()->isAdmin())
                    Owner/Admin
                @else
                    {{ optional(auth()->user()->branch)->name }}
                @endif
            </div>
            <nav class="nav">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('orders.create') }}">Accept Laundry</a>
                <a href="{{ route('orders.index') }}">Order Tracking</a>
                <a href="{{ route('reports.sales') }}">Sales Report</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.branches.index') }}">Branches</a>
                    <a href="{{ route('admin.pricing.index') }}">Global Pricing</a>
                    <a href="{{ route('admin.addons.index') }}">Add-On Services</a>
                @endif
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.users.index') }}">User Accounts</a>
                @endif
                <a href="{{ route('password.change') }}">Change Password</a>
                <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Are you sure you want to log out? Unsaved progress will be discarded.');">
                    @csrf
                    <button class="logout">Logout</button>
                </form>
            </nav>
        </aside>
        <main class="main">
            <div class="topbar">
                <div>
                    <h1>{{ $heading ?? 'Badeth Laundry Shop' }}</h1>
                    <div class="muted">{{ $subheading ?? 'POS, order tracking, payments, and reports' }}</div>
                </div>
                <div class="muted">{{ auth()->user()->name }}</div>
            </div>
            @include('layouts.flash')
            {{ $slot }}
        </main>
    </div>
@else
    {{ $slot }}
@endauth
<script>
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            window.location.reload();
        }
    });
</script>
</body>
</html>
