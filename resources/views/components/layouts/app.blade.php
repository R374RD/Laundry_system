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

            /* Layout */
            --sidebar-width: 282px;
            --sidebar-collapsed-width: 92px;

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

        html {
            overflow-x: hidden;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Inter, system-ui, -apple-system, BlinkMacSystemFont, Arial, sans-serif;
            font-size: var(--text-base);
            background: linear-gradient(135deg, var(--color-canvas), var(--color-accent-soft));
            color: var(--color-text-primary);
            letter-spacing: 0;
            overflow-x: hidden;
        }

        a { color: inherit; text-decoration: none; }

        .shell {
            position: relative;
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 40;
            width: var(--sidebar-width);
            height: 100vh;
            overflow: hidden;
            color: var(--color-on-brand);
            padding: 24px 18px;
            background: linear-gradient(180deg, var(--color-brand), var(--color-sidebar));
            border-right: 1px solid color-mix(in srgb, var(--color-accent) 28%, transparent);
            box-shadow: var(--shadow-md);
            scrollbar-width: none;
            -ms-overflow-style: none;
            transition:
                transform var(--transition-slow),
                width var(--transition-slow),
                padding var(--transition-slow),
                opacity var(--transition-base);
        }

        .sidebar::-webkit-scrollbar {
            width: 0;
            height: 0;
            display: none;
        }

        .sidebar-inner {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            z-index: 35;
            border: 0;
            background: rgba(10, 18, 14, 0.5);
            opacity: 0;
            pointer-events: none;
            transition: opacity var(--transition-base);
        }

        .brand-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
            min-width: 0;
        }

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

        .brand-logo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .brand-copy {
            min-width: 0;
            transition: opacity var(--transition-base), visibility var(--transition-base);
        }

        .brand {
            font-size: var(--text-lg);
            font-weight: 800;
            line-height: 1.05;
        }

        .brand-subtitle {
            color: var(--color-brand-soft);
            font-size: var(--text-xs);
            margin-top: 3px;
        }

        .muted { color: var(--color-text-secondary); font-size: var(--text-sm); }

        .sidebar-role {
            color: var(--color-brand-soft);
            margin-bottom: 28px;
            transition: opacity var(--transition-base), visibility var(--transition-base);
        }

        .nav {
            margin-top: 24px;
            display: grid;
            gap: 7px;
        }

        .nav a,
        .logout {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            min-height: 48px;
            padding: 12px 14px;
            border-radius: var(--radius-sm);
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

        .nav a:hover,
        .logout:hover,
        .nav a:focus-visible,
        .logout:focus-visible {
            background: color-mix(in srgb, var(--color-on-brand) 12%, transparent);
            border-color: color-mix(in srgb, var(--color-on-brand) 24%, transparent);
            transform: translateX(2px);
            outline: none;
        }

        .nav a.active {
            background: color-mix(in srgb, var(--color-on-brand) 16%, transparent);
            border-color: color-mix(in srgb, var(--color-on-brand) 28%, transparent);
            box-shadow: inset 0 0 0 1px color-mix(in srgb, var(--color-on-brand) 12%, transparent);
        }

        .nav-label {
            display: inline-flex;
            align-items: center;
            min-width: 0;
            transition: opacity var(--transition-base), visibility var(--transition-base);
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            flex: 0 0 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .nav-icon svg {
            width: 20px;
            height: 20px;
            display: block;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .logout-form {
            margin: 0;
        }

        .main {
            min-width: 0;
            margin-left: var(--sidebar-width);
            padding: 30px;
            transition: margin-left var(--transition-slow), padding var(--transition-base);
        }

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
            min-width: 0;
        }

        .topbar-start {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
            flex: 1 1 auto;
        }

        .topbar-copy {
            min-width: 0;
        }

        .topbar-end {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 0 0 auto;
        }

        .menu-toggle {
            width: 46px;
            height: 46px;
            padding: 0;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            background: var(--color-surface);
            color: var(--color-text-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 4px;
            cursor: pointer;
            box-shadow: var(--shadow-xs);
            transition:
                background-color var(--transition-base),
                border-color var(--transition-base),
                box-shadow var(--transition-base),
                transform var(--transition-base);
        }

        .menu-toggle:hover,
        .menu-toggle:focus-visible {
            background: var(--color-brand-light);
            border-color: var(--color-border-strong);
            box-shadow: var(--shadow-sm);
            transform: translateY(-1px);
            outline: none;
        }

        .menu-toggle-bar {
            width: 18px;
            height: 2px;
            border-radius: var(--radius-full);
            background: currentColor;
            display: block;
        }

        .user-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 10px 14px;
            border-radius: var(--radius-full);
            background: var(--color-brand-light);
            border: 1px solid var(--color-border);
            color: var(--color-brand-dark);
            font-size: var(--text-sm);
            font-weight: 800;
            white-space: nowrap;
        }

        h1 {
            margin: 0;
            font-size: var(--text-2xl);
            font-weight: 800;
            color: var(--color-text-primary);
        }

        h2 {
            margin: 0 0 16px;
            font-size: var(--text-lg);
            color: var(--color-text-primary);
        }

        .grid { display: grid; gap: 16px; }
        .grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }

        .card {
            min-width: 0;
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

        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .grid-4 .card { border-top: 3px solid var(--color-accent); }
        .metric { font-size: var(--text-2xl); font-weight: 850; margin-top: 8px; color: var(--color-text-primary); }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--color-surface);
            border-radius: var(--radius-md);
            overflow: hidden;
            border: 1px solid var(--color-border);
        }

        th,
        td {
            padding: 13px 14px;
            border-bottom: 1px solid var(--color-border);
            text-align: left;
            vertical-align: top;
        }

        tr:last-child td { border-bottom: 0; }
        tbody tr:hover { background: var(--color-brand-light); }

        th {
            background: var(--color-overlay);
            font-size: var(--text-xs);
            color: var(--color-text-secondary);
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        input,
        select,
        textarea {
            width: 100%;
            max-width: 100%;
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

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--color-brand);
            box-shadow: 0 0 0 4px color-mix(in srgb, var(--color-brand) 18%, transparent);
            background: var(--color-surface);
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 750;
            font-size: var(--text-sm);
            color: var(--color-text-primary);
        }

        .field { margin-bottom: 14px; }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
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

        .btn:hover {
            background: var(--color-brand-dark);
            box-shadow: var(--shadow-xs);
            transform: scale(1.01);
        }

        .btn.secondary { background: var(--color-text-secondary); box-shadow: none; }
        .btn.light { background: var(--color-brand-light); color: var(--color-brand-dark); box-shadow: none; border: 1px solid var(--color-border-strong); }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }

        .badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: var(--radius-full);
            background: var(--color-brand-light);
            color: var(--color-brand-dark);
            font-size: var(--text-xs);
            font-weight: 800;
            border: 1px solid var(--color-border-strong);
            transition: background-color var(--transition-base);
        }

        .badge.good { background: var(--color-success-bg); color: var(--color-success-text); border-color: color-mix(in srgb, var(--color-success-text) 32%, transparent); }
        .badge.warn { background: var(--color-warning-bg); color: var(--color-warning-text); border-color: color-mix(in srgb, var(--color-warning-text) 32%, transparent); }
        .badge.bad { background: var(--color-danger-bg); color: var(--color-danger-text); border-color: color-mix(in srgb, var(--color-danger-text) 32%, transparent); }

        .alert {
            padding: 12px 14px;
            border-radius: var(--radius-md);
            margin-bottom: 16px;
            border: 1px solid var(--color-border-strong);
        }

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

        body.sidebar-collapsed .sidebar {
            width: var(--sidebar-collapsed-width);
            padding-left: 14px;
            padding-right: 14px;
        }

        body.sidebar-collapsed .main {
            margin-left: var(--sidebar-collapsed-width);
        }

        body.sidebar-collapsed .brand-wrap {
            justify-content: center;
        }

        body.sidebar-collapsed .brand-copy,
        body.sidebar-collapsed .sidebar-role,
        body.sidebar-collapsed .nav-label {
            opacity: 0;
            visibility: hidden;
            width: 0;
            height: 0;
            overflow: hidden;
        }

        body.sidebar-collapsed .nav a,
        body.sidebar-collapsed .logout {
            justify-content: center;
            padding-left: 10px;
            padding-right: 10px;
        }

        body.sidebar-collapsed .nav a::after,
        body.sidebar-collapsed .logout::after {
            content: attr(data-title);
            position: absolute;
            left: calc(100% + 12px);
            top: 50%;
            transform: translateY(-50%);
            padding: 8px 10px;
            border-radius: var(--radius-sm);
            background: var(--color-text-primary);
            color: var(--color-on-brand);
            font-size: var(--text-sm);
            font-weight: 700;
            white-space: nowrap;
            box-shadow: var(--shadow-md);
            opacity: 0;
            pointer-events: none;
            transition: opacity var(--transition-fast), transform var(--transition-fast);
        }

        body.sidebar-collapsed .nav a:hover::after,
        body.sidebar-collapsed .nav a:focus-visible::after,
        body.sidebar-collapsed .logout:hover::after,
        body.sidebar-collapsed .logout:focus-visible::after {
            opacity: 1;
            transform: translateY(-50%) translateX(4px);
        }

        @media (max-width: 900px) {
            body.sidebar-open {
                overflow: hidden;
            }

            .shell {
                min-height: 100vh;
            }

            .sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                width: min(86vw, 320px);
                transform: translateX(-100%);
                box-shadow: var(--shadow-lg);
            }

            body.sidebar-open .sidebar {
                transform: translateX(0);
            }

            body.sidebar-open .sidebar-backdrop {
                opacity: 1;
                pointer-events: auto;
            }

            .grid-4,
            .grid-2 {
                grid-template-columns: 1fr;
            }

            .main {
                margin-left: 0;
                padding: 18px;
            }

            .topbar {
                gap: 14px;
            }
        }

        @media (max-width: 640px) {
            body {
                font-size: 14px;
            }

            .main {
                padding: 12px;
            }

            .topbar {
                flex-direction: column;
                align-items: stretch;
                padding: 16px;
                margin-bottom: 16px;
            }

            .topbar-start,
            .topbar-end {
                width: 100%;
            }

            .user-pill {
                width: 100%;
                justify-content: flex-start;
                white-space: normal;
            }

            h1 {
                font-size: clamp(1.45rem, 6vw, var(--text-2xl));
            }

            .card {
                padding: 16px;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
            }

            th,
            td {
                padding: 12px 10px;
            }

            input,
            select,
            textarea,
            .btn,
            .menu-toggle {
                font-size: 16px;
            }

            .btn {
                min-height: 44px;
            }

            .search-field,
            .filter-field {
                max-width: none;
            }

            .actions {
                align-items: stretch;
            }
        }
    </style>
</head>
<body class="@auth app-shell-body @endauth">
@auth
    <div class="shell">
        <button class="sidebar-backdrop" type="button" data-sidebar-backdrop aria-label="Close navigation"></button>

        <aside class="sidebar" id="app-sidebar" data-app-sidebar>
            <div class="sidebar-inner">
                <div class="brand-wrap">
                    <div class="brand-mark">
                        <img class="brand-logo" src="{{ asset('images/badeth-laundry-logo.png') }}" alt="Badeth Laundry Shop logo">
                    </div>
                    <div class="brand-copy">
                        <div class="brand">Badeth Laundry Shop</div>
                        <div class="brand-subtitle">Laundry branch operations</div>
                    </div>
                </div>

                <div class="muted sidebar-role">
                    @if(auth()->user()->isAdmin())
                        Owner/Admin
                    @else
                        {{ optional(auth()->user()->branch)->name }}
                    @endif
                </div>

                <nav class="nav">
                    <a href="{{ route('dashboard') }}"
                       class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       data-title="Dashboard"
                       data-sidebar-link
                       title="Dashboard">
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <path d="M3 12.5 12 4l9 8.5"></path>
                                <path d="M5 10.5V20h14v-9.5"></path>
                                <path d="M9 20v-5h6v5"></path>
                            </svg>
                        </span>
                        <span class="nav-label">Dashboard</span>
                    </a>

                    <a href="{{ route('orders.create') }}"
                       class="{{ request()->routeIs('orders.create') ? 'active' : '' }}"
                       data-title="Accept Laundry"
                       data-sidebar-link
                       title="Accept Laundry">
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <rect x="5" y="4" width="14" height="16" rx="2"></rect>
                                <path d="M9 2v4"></path>
                                <path d="M15 2v4"></path>
                                <path d="M8 10h8"></path>
                                <path d="M12 13v4"></path>
                                <path d="M10 15h4"></path>
                            </svg>
                        </span>
                        <span class="nav-label">Accept Laundry</span>
                    </a>

                    <a href="{{ route('orders.index') }}"
                       class="{{ request()->routeIs('orders.index', 'orders.show', 'orders.receipt') ? 'active' : '' }}"
                       data-title="Order Tracking"
                       data-sidebar-link
                       title="Order Tracking">
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <rect x="5" y="4" width="14" height="16" rx="2"></rect>
                                <path d="M9 9h6"></path>
                                <path d="M9 13h6"></path>
                                <path d="M9 17h4"></path>
                            </svg>
                        </span>
                        <span class="nav-label">Order Tracking</span>
                    </a>

                    <a href="{{ route('reports.sales') }}"
                       class="{{ request()->routeIs('reports.sales') ? 'active' : '' }}"
                       data-title="Sales Report"
                       data-sidebar-link
                       title="Sales Report">
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <path d="M4 19h16"></path>
                                <path d="M7 16V9"></path>
                                <path d="M12 16V5"></path>
                                <path d="M17 16v-7"></path>
                            </svg>
                        </span>
                        <span class="nav-label">Sales Report</span>
                    </a>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.branches.index') }}"
                           class="{{ request()->routeIs('admin.branches.*') ? 'active' : '' }}"
                           data-title="Branches"
                           data-sidebar-link
                           title="Branches">
                            <span class="nav-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 21s6-5.4 6-11a6 6 0 1 0-12 0c0 5.6 6 11 6 11Z"></path>
                                    <circle cx="12" cy="10" r="2.5"></circle>
                                </svg>
                            </span>
                            <span class="nav-label">Branches</span>
                        </a>

                        <a href="{{ route('admin.pricing.index') }}"
                           class="{{ request()->routeIs('admin.pricing.*') ? 'active' : '' }}"
                           data-title="Global Pricing"
                           data-sidebar-link
                           title="Global Pricing">
                            <span class="nav-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 3v18"></path>
                                    <path d="M16.5 7.5c0-1.9-1.9-3.5-4.5-3.5S7.5 5.6 7.5 7.5 9 10.3 12 11s4.5 1.8 4.5 4-1.9 4-4.5 4-4.5-1.8-4.5-4"></path>
                                </svg>
                            </span>
                            <span class="nav-label">Global Pricing</span>
                        </a>

                        <a href="{{ route('admin.addons.index') }}"
                           class="{{ request()->routeIs('admin.addons.*') ? 'active' : '' }}"
                           data-title="Add-On Services"
                           data-sidebar-link
                           title="Add-On Services">
                            <span class="nav-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 5v14"></path>
                                    <path d="M5 12h14"></path>
                                    <rect x="3.5" y="3.5" width="17" height="17" rx="3"></rect>
                                </svg>
                            </span>
                            <span class="nav-label">Add-On Services</span>
                        </a>

                        <a href="{{ route('admin.users.index') }}"
                           class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                           data-title="User Accounts"
                           data-sidebar-link
                           title="User Accounts">
                            <span class="nav-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path d="M16 19v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1"></path>
                                    <circle cx="10" cy="8" r="3"></circle>
                                    <path d="M20 19v-1a4 4 0 0 0-3-3.87"></path>
                                    <path d="M14 5.13a3 3 0 0 1 0 5.74"></path>
                                </svg>
                            </span>
                            <span class="nav-label">User Accounts</span>
                        </a>
                    @endif

                    <a href="{{ route('password.change') }}"
                       class="{{ request()->routeIs('password.change') ? 'active' : '' }}"
                       data-title="Change Password"
                       data-sidebar-link
                       title="Change Password">
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                                <path d="M8 11V8a4 4 0 1 1 8 0v3"></path>
                                <path d="M12 15v2"></path>
                            </svg>
                        </span>
                        <span class="nav-label">Change Password</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="logout-form" onsubmit="return confirm('Are you sure you want to log out? Unsaved progress will be discarded.');">
                        @csrf
                        <button class="logout" data-title="Logout" title="Logout" type="submit">
                            <span class="nav-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path d="M15 17l5-5-5-5"></path>
                                    <path d="M20 12H9"></path>
                                    <path d="M13 4H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8"></path>
                                </svg>
                            </span>
                            <span class="nav-label">Logout</span>
                        </button>
                    </form>
                </nav>
            </div>
        </aside>

        <main class="main">
            <div class="topbar">
                <div class="topbar-start">
                    <button class="menu-toggle"
                            type="button"
                            data-sidebar-toggle
                            aria-controls="app-sidebar"
                            aria-expanded="true"
                            aria-label="Toggle navigation">
                        <span class="menu-toggle-bar"></span>
                        <span class="menu-toggle-bar"></span>
                        <span class="menu-toggle-bar"></span>
                    </button>

                    <div class="topbar-copy">
                        <h1>{{ $heading ?? 'Badeth Laundry Shop' }}</h1>
                        <div class="muted">{{ $subheading ?? 'POS, order tracking, payments, and reports' }}</div>
                    </div>
                </div>

                <div class="topbar-end">
                    <div class="user-pill">{{ auth()->user()->name }}</div>
                </div>
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

    document.addEventListener('DOMContentLoaded', function () {
        const body = document.body;
        const sidebar = document.querySelector('[data-app-sidebar]');
        const toggleButton = document.querySelector('[data-sidebar-toggle]');
        const backdrop = document.querySelector('[data-sidebar-backdrop]');
        const navLinks = document.querySelectorAll('[data-sidebar-link]');

        if (!sidebar || !toggleButton) {
            return;
        }

        const mobileBreakpoint = window.matchMedia('(max-width: 900px)');
        const storageKey = 'badeth-sidebar-collapsed';
        let desktopCollapsed = localStorage.getItem(storageKey) === 'true';

        function setExpandedState(isExpanded) {
            toggleButton.setAttribute('aria-expanded', String(isExpanded));
            sidebar.setAttribute('aria-hidden', mobileBreakpoint.matches ? String(!isExpanded) : 'false');

            if (backdrop) {
                backdrop.setAttribute('aria-hidden', String(!body.classList.contains('sidebar-open')));
            }
        }

        function closeMobileSidebar() {
            body.classList.remove('sidebar-open');
            setExpandedState(false);
        }

        function applySidebarState() {
            if (mobileBreakpoint.matches) {
                body.classList.remove('sidebar-collapsed');
                setExpandedState(body.classList.contains('sidebar-open'));
                return;
            }

            body.classList.remove('sidebar-open');
            body.classList.toggle('sidebar-collapsed', desktopCollapsed);
            setExpandedState(!desktopCollapsed);
        }

        toggleButton.addEventListener('click', function () {
            if (mobileBreakpoint.matches) {
                body.classList.toggle('sidebar-open');
                setExpandedState(body.classList.contains('sidebar-open'));
                return;
            }

            desktopCollapsed = !desktopCollapsed;
            localStorage.setItem(storageKey, String(desktopCollapsed));
            applySidebarState();
        });

        if (backdrop) {
            backdrop.addEventListener('click', closeMobileSidebar);
        }

        navLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                if (mobileBreakpoint.matches) {
                    closeMobileSidebar();
                }
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && mobileBreakpoint.matches && body.classList.contains('sidebar-open')) {
                closeMobileSidebar();
            }
        });

        mobileBreakpoint.addEventListener('change', applySidebarState);
        applySidebarState();
    });
</script>
</body>
</html>
