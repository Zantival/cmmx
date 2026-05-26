<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CMMX') }} — @yield('title', 'Panel')</title>
    <meta name="description" content="Sistema de Gestión de Mantenimiento Industrial">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" integrity="sha384-XGjxtQfXaH2tnPFa9x+ruJTuLE3Aa6LhHSWRr1XeTyhezb4abCG4ccI5AkVDxqC+" crossorigin="anonymous">

    <style nonce="{{ $cspNonce }}">
        /* ─── Design Tokens ─── */
        :root {
            --navy:          #0A192F;
            --navy-mid:      #112240;
            --navy-light:    #1E3A5F;
            --accent:        #38BDF8;
            --accent-glow:   rgba(56,189,248,0.15);
            --success:       #10B981;
            --warning:       #F59E0B;
            --danger:        #EF4444;
            --info:          #6366F1;
            --surface:       #F1F5F9;
            --card-bg:       #FFFFFF;
            --border:        #E2E8F0;
            --text-primary:  #0F172A;
            --text-muted:    #64748B;
            --text-light:    #94A3B8;
            --nav-h:         60px;
            --radius-sm:     8px;
            --radius-md:     12px;
            --radius-lg:     16px;
            --shadow-sm:     0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:     0 4px 12px rgba(0,0,0,0.08), 0 2px 4px rgba(0,0,0,0.04);
            --shadow-lg:     0 10px 30px rgba(0,0,0,0.12), 0 4px 8px rgba(0,0,0,0.06);
        }

        /* ─── Reset ─── */
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #EFF6FF 0%, #F0F9FF 40%, #F0FDF4 100%);
            color: var(--text-primary);
            margin: 0;
            padding-top: var(--nav-h);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        /* ─── TOP NAVBAR ─── */
        .top-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--nav-h);
            background: var(--navy);
            z-index: 1040;
            display: flex;
            align-items: center;
            padding: 0 1rem;
            gap: 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }

        /* Logo */
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            margin-right: 1.5rem;
            flex-shrink: 0;
        }
        .nav-logo .logo-icon {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--accent), #0EA5E9);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; color: #fff;
        }
        .nav-logo .logo-text {
            font-size: 1rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
        }
        .nav-logo .logo-text span { color: var(--accent); }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2px;
            flex: 1;
            overflow-x: hidden;
        }

        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            border-radius: var(--radius-sm);
            color: #94A3B8;
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .nav-link-item i { font-size: 0.95rem; }
        .nav-link-item:hover {
            color: #fff;
            background: rgba(255,255,255,0.08);
        }
        .nav-link-item.active {
            color: var(--accent);
            background: var(--accent-glow);
        }
        .nav-link-item.active i { color: var(--accent); }

        /* Nav Right */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
            margin-left: auto;
        }

        .nav-divider {
            width: 1px;
            height: 24px;
            background: rgba(255,255,255,0.1);
            margin: 0 4px;
        }

        /* Mobile Hamburger */
        .nav-hamburger {
            display: none;
            background: transparent;
            border: none;
            color: #fff;
            font-size: 1.4rem;
            padding: 6px;
            cursor: pointer;
            margin-left: auto;
        }

        @media(max-width: 767.98px) {
            .nav-hamburger { display: flex; align-items: center; }
            .nav-links-wrapper { display: none !important; }
            .nav-right { display: none !important; }
        }

        /* Mobile Drawer */
        .mobile-drawer {
            position: fixed;
            top: 0; right: 0; bottom: 0;
            width: 280px;
            background: var(--navy);
            z-index: 2000;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            box-shadow: -8px 0 30px rgba(0,0,0,0.4);
        }
        .mobile-drawer.open { transform: translateX(0); }
        .mobile-drawer-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 1999;
            display: none;
            backdrop-filter: blur(2px);
        }
        .mobile-drawer-overlay.open { display: block; }

        .drawer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .drawer-close {
            background: rgba(255,255,255,0.08);
            border: none;
            color: #fff;
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            cursor: pointer;
        }
        .drawer-user {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .drawer-avatar {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--accent), #0EA5E9);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .drawer-user-name { font-size: 0.9rem; font-weight: 700; color: #fff; }
        .drawer-user-role { font-size: 0.75rem; color: #94A3B8; }

        .drawer-nav {
            padding: 0.75rem;
            flex: 1;
        }
        .drawer-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 14px;
            border-radius: 10px;
            color: #94A3B8;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.18s;
            margin-bottom: 3px;
        }
        .drawer-link i { font-size: 1.1rem; width: 20px; text-align: center; }
        .drawer-link:hover { color: #fff; background: rgba(255,255,255,0.08); }
        .drawer-link.active { color: var(--accent); background: var(--accent-glow); }

        .drawer-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .nav-user-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 10px;
            border-radius: var(--radius-md);
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            cursor: pointer;
            transition: all 0.2s;
        }
        .nav-user-btn:hover { background: rgba(255,255,255,0.12); }
        .nav-user-avatar {
            width: 26px; height: 26px;
            background: linear-gradient(135deg, var(--accent), #0EA5E9);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .nav-user-name {
            font-size: 0.78rem;
            font-weight: 500;
            max-width: 90px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .nav-role-badge {
            font-size: 0.62rem;
            padding: 2px 6px;
            border-radius: 20px;
            background: var(--accent-glow);
            color: var(--accent);
            font-weight: 600;
            border: 1px solid rgba(56,189,248,0.3);
        }

        /* ─── CARDS ─── */
        .card {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(226,232,240,0.8);
            border-radius: var(--radius-lg);
            box-shadow: 0 2px 12px rgba(0,0,0,0.06), 0 1px 3px rgba(0,0,0,0.04);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.06);
        }
        .card.no-hover:hover { box-shadow: 0 2px 12px rgba(0,0,0,0.06); transform: none; }

        /* KPI Cards */
        .kpi-card {
            border-radius: var(--radius-lg);
            padding: 1.35rem;
            position: relative;
            overflow: hidden;
            border: none;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .kpi-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,0.18); }
        .kpi-card .kpi-icon {
            width: 44px; height: 44px;
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 0.75rem;
        }
        .kpi-card .kpi-value {
            font-size: 2.1rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 0.25rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.15);
        }
        .kpi-card .kpi-label {
            font-size: 0.76rem;
            font-weight: 600;
            opacity: 0.85;
            letter-spacing: 0.3px;
        }
        .kpi-card .kpi-decoration {
            position: absolute;
            right: -20px; bottom: -20px;
            width: 90px; height: 90px;
            border-radius: 50%;
            opacity: 0.12;
        }

        .kpi-success { background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: #fff; box-shadow: 0 4px 20px rgba(16,185,129,0.3); }
        .kpi-success .kpi-icon { background: rgba(255,255,255,0.25); color: #fff; }
        .kpi-success .kpi-decoration { background: #fff; }

        .kpi-warning { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: #fff; box-shadow: 0 4px 20px rgba(245,158,11,0.3); }
        .kpi-warning .kpi-icon { background: rgba(255,255,255,0.25); color: #fff; }
        .kpi-warning .kpi-decoration { background: #fff; }

        .kpi-danger  { background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%); color: #fff; box-shadow: 0 4px 20px rgba(239,68,68,0.3); }
        .kpi-danger .kpi-icon  { background: rgba(255,255,255,0.25); color: #fff; }
        .kpi-danger .kpi-decoration { background: #fff; }

        .kpi-info    { background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%); color: #fff; box-shadow: 0 4px 20px rgba(99,102,241,0.3); }
        .kpi-info .kpi-icon    { background: rgba(255,255,255,0.25); color: #fff; }
        .kpi-info .kpi-decoration { background: #fff; }

        /* ─── Status Badges ─── */
        .badge-status {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
        }
        .badge-op  { background: #D1FAE5; color: #065F46; }
        .badge-rep { background: #FEF3C7; color: #78350F; }
        .badge-oos { background: #FEE2E2; color: #991B1B; }
        .badge-prev { background: #DBEAFE; color: #1E40AF; }
        .badge-corr { background: #FEE2E2; color: #991B1B; }

        /* ─── Buttons ─── */
        .btn-navy {
            background: var(--navy);
            color: #fff;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.85rem;
            padding: 9px 18px;
            transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-navy:hover { background: var(--navy-mid); color: #fff; transform: translateY(-1px); box-shadow: var(--shadow-md); }

        .btn-ghost {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            font-weight: 500;
            font-size: 0.85rem;
            padding: 8px 16px;
            transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-ghost:hover { background: var(--surface); color: var(--text-primary); border-color: #CBD5E1; }

        /* ─── Tables ─── */
        .table-card { border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--border); }
        .table thead th {
            background: #F8FAFC;
            color: var(--text-muted);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            padding: 11px 14px;
            border-bottom: 1px solid var(--border);
        }
        .table tbody td {
            padding: 13px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #F1F5F9;
            font-size: 0.875rem;
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr { transition: background 0.15s; }
        .table tbody tr:hover { background: #F8FAFC; }

        /* ─── Forms ─── */
        .form-label { font-size: 0.78rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .form-control, .form-select {
            border-radius: var(--radius-md);
            border: 1.5px solid var(--border);
            padding: 10px 14px;
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(56,189,248,0.15);
            outline: none;
        }

        /* ─── Alert Toast ─── */
        .alert {
            border: none;
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            padding: 12px 16px;
        }
        .alert-success { background: #D1FAE5; color: #065F46; }
        .alert-danger  { background: #FEE2E2; color: #991B1B; }

        /* ─── Dropdown ─── */
        .dropdown-menu {
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            padding: 6px;
            min-width: 190px;
        }
        .dropdown-item {
            border-radius: var(--radius-sm);
            font-size: 0.84rem;
            padding: 8px 12px;
        }
        .dropdown-item:hover { background: var(--surface); }
        .dropdown-divider { border-color: var(--border); margin: 4px 0; }

        /* ─── Page Header ─── */
        .page-header {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226,232,240,0.7);
            padding: 1.1rem 1.5rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            box-shadow: 0 1px 8px rgba(0,0,0,0.05);
        }
        .page-header h1 {
            font-size: 1.2rem;
            font-weight: 800;
            margin: 0;
            color: var(--navy);
            letter-spacing: -0.3px;
        }
        .page-breadcrumb {
            font-size: 0.73rem;
            color: var(--text-muted);
            margin-top: 3px;
        }
        .page-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: center;
        }

        /* ─── Content Area ─── */
        .content-area { padding: 1.5rem; }
        @media(max-width: 575.98px) {
            .content-area { padding: 1rem; }
        }

        /* ─── Micro-animations ─── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes flashFadeOut {
            0%   { opacity: 1; transform: translateY(0); }
            85%  { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-8px); }
        }
        .animate-in { animation: fadeInUp 0.3s ease both; }
        .delay-1 { animation-delay: 0.05s; }
        .delay-2 { animation-delay: 0.10s; }
        .delay-3 { animation-delay: 0.15s; }
        .delay-4 { animation-delay: 0.20s; }

        /* Flash alert auto-dismiss */
        .flash-alert {
            position: fixed;
            top: calc(var(--nav-h) + 12px);
            right: 16px;
            z-index: 2100;
            min-width: 280px;
            max-width: 420px;
            border-radius: var(--radius-md);
            padding: 13px 18px;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: var(--shadow-lg);
            animation: fadeInUp 0.3s ease both;
        }
        .flash-alert.auto-dismiss {
            animation: flashFadeOut 4.5s ease forwards;
        }
        .flash-alert-success { background: #D1FAE5; color: #065F46; border-left: 4px solid #10B981; }
        .flash-alert-error   { background: #FEE2E2; color: #991B1B; border-left: 4px solid #EF4444; }
        .flash-alert-close {
            margin-left: auto;
            background: transparent;
            border: none;
            color: inherit;
            opacity: 0.6;
            cursor: pointer;
            font-size: 1rem;
            line-height: 1;
            padding: 0 2px;
        }
        .flash-alert-close:hover { opacity: 1; }

        /* ─── Scrollbar ─── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }

        /* ─── Custom Language Switcher ─── */
        .lang-switcher {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 100px;
            padding: 3px;
            gap: 2px;
            margin-right: 8px;
        }
        .lang-btn {
            display: inline-block;
            text-decoration: none;
            background: transparent;
            color: #94A3B8;
            font-size: 0.65rem;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 100px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.5;
        }
        .lang-btn:hover { color: #fff; text-decoration: none; }
        .lang-btn.active {
            background: var(--accent);
            color: var(--navy);
            box-shadow: 0 2px 8px rgba(56, 189, 248, 0.4);
        }
        .lang-btn-mobile {
            display: flex; align-items: center; justify-content: center;
            text-decoration: none;
            flex: 1; padding: 10px 0;
            border-radius: 100px;
            font-size: 0.8rem; font-weight: 700;
            color: #94A3B8;
            transition: all 0.2s;
        }
        .lang-btn-mobile.active {
            background: var(--accent);
            color: var(--navy);
        }

        /* ─── Mobile touch refinements ─── */
        @media(max-width: 575.98px) {
            .btn-navy, .btn-ghost { padding: 10px 14px; font-size: 0.875rem; }
            .kpi-card .kpi-value { font-size: 1.6rem; }
            .table tbody td { padding: 11px 10px; font-size: 0.82rem; }
            .table thead th { padding: 9px 10px; }
        }

        /* ─── Bottom Tab Bar (Mobile) ─── */
        .bottom-tab-bar {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: var(--navy);
            z-index: 1030;
            padding: 0;
            border-top: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 -4px 20px rgba(0,0,0,0.25);
        }
        .btb-inner {
            display: flex;
            align-items: stretch;
        }
        .btb-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 3px;
            padding: 8px 4px 10px;
            text-decoration: none;
            color: #64748B;
            font-size: 0.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            transition: color 0.2s;
            border: none;
            background: transparent;
            cursor: pointer;
        }
        .btb-item i { font-size: 1.3rem; line-height: 1; }
        .btb-item:hover { color: #94A3B8; }
        .btb-item.active { color: var(--accent); }
        .btb-item.active i { color: var(--accent); }

        @media(max-width: 767.98px) {
            .bottom-tab-bar { display: block; }
            body { padding-bottom: 60px; }
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- ─── TOP NAVIGATION ─── -->
    <nav class="top-nav">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="nav-logo">
            <div class="logo-icon"><i class="bi bi-cpu-fill"></i></div>
            <span class="logo-text">Mec<span>App</span></span>
        </a>

        <!-- Desktop Nav Links -->
        <div class="nav-links nav-links-wrapper d-none d-md-flex">
            @php $role = optional(auth()->user())->role; @endphp

            @if($role === 'Admin')
                <a href="{{ route('dashboard') }}" class="nav-link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i><span>{{ __('Dashboard') }}</span>
                </a>
                <a href="{{ route('equipment.index') }}" class="nav-link-item {{ request()->routeIs('equipment.*') ? 'active' : '' }}">
                    <i class="bi bi-cpu"></i><span>{{ __('Activos') }}</span>
                </a>
                <a href="{{ route('maintenances.index') }}" class="nav-link-item {{ request()->routeIs('maintenances.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-data-fill"></i><span>{{ __('OT') }}</span>
                </a>
                <a href="{{ route('analyst.dashboard') }}" class="nav-link-item {{ request()->routeIs('analyst.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-fill"></i><span>{{ __('Métricas') }}</span>
                </a>
                <a href="{{ route('inventory.index') }}" class="nav-link-item {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam-fill"></i><span>{{ __('Inventario') }}</span>
                </a>
                <a href="{{ route('users.index') }}" class="nav-link-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i><span>{{ __('Usuarios') }}</span>
                </a>

            @elseif($role === 'Technician')
                <a href="{{ route('technician.dashboard') }}" class="nav-link-item {{ request()->routeIs('technician.*') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i><span>{{ __('Mi Panel') }}</span>
                </a>
                <a href="{{ route('equipment.index') }}" class="nav-link-item {{ request()->routeIs('equipment.*') ? 'active' : '' }}">
                    <i class="bi bi-cpu"></i><span>{{ __('Equipos') }}</span>
                </a>
                <a href="{{ route('maintenances.index') }}" class="nav-link-item {{ request()->routeIs('maintenances.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-data-fill"></i><span>{{ __('Mis OTs') }}</span>
                </a>
                <a href="{{ route('inventory.index') }}" class="nav-link-item {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam-fill"></i><span>{{ __('Inventario') }}</span>
                </a>

            @elseif($role === 'Analyst')
                <a href="{{ route('analyst.dashboard') }}" class="nav-link-item {{ request()->routeIs('analyst.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-fill"></i><span>{{ __('Métricas') }}</span>
                </a>

            @else
                <a href="{{ route('dashboard') }}" class="nav-link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
                </a>
            @endif
        </div>

        <!-- Desktop Right Side -->
        <div class="nav-right nav-links-wrapper d-none d-md-flex">
            @if(auth()->check())
            @php
                $roleColors = [
                    'Admin'      => 'background:rgba(56,189,248,0.15);color:#38BDF8;border-color:rgba(56,189,248,0.3);',
                    'Technician' => 'background:rgba(245,158,11,0.15);color:#F59E0B;border-color:rgba(245,158,11,0.3);',
                    'Analyst'    => 'background:rgba(99,102,241,0.15);color:#818CF8;border-color:rgba(99,102,241,0.3);',
                ];
                $roleStyle = $roleColors[optional(auth()->user())->role] ?? $roleColors['Admin'];
            @endphp

            @php $currentLocale = app()->getLocale(); @endphp
            <!-- Premium Language Switcher -->
            <div class="lang-switcher">
                <a href="{{ route('locale.switch', 'es') }}" 
                   class="lang-btn {{ app()->getLocale() === 'es' ? 'active' : '' }}"
                   title="Español">
                   <span class="d-none d-md-inline">ES</span>
                   <i class="bi bi-translate d-md-none"></i>
                </a>
                <a href="{{ route('locale.switch', 'en') }}" 
                   class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                   title="English">
                   <span class="d-none d-md-inline">EN</span>
                   <i class="bi bi-translate d-md-none"></i>
                </a>
            </div>

            <div class="nav-divider"></div>

            <span class="nav-role-badge d-inline-flex justify-content-center align-items-center" style="{{ $roleStyle }}">{{ __(optional(auth()->user())->role ?? 'Admin') }}</span>

            <div class="dropdown">
                <div class="nav-user-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="cursor:pointer;">
                    <div class="nav-user-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <span class="nav-user-name">{{ auth()->user()->name ?? 'Usuario' }}</span>
                    <i class="bi bi-chevron-down" style="font-size:0.6rem; color:#94A3B8;"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end mt-2">
                    <li>
                        <div class="px-3 py-2">
                            <div style="font-size:0.85rem;font-weight:600;color:var(--navy);">{{ auth()->user()->name }}</div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">{{ auth()->user()->email }}</div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger fw-semibold">
                                <i class="bi bi-box-arrow-right me-2"></i>{{ __('Cerrar Sesión') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @endif
        </div>

        <!-- Mobile Hamburger -->
        @if(auth()->check())
        <button class="nav-hamburger" id="drawerToggle" aria-label="Menú">
            <i class="bi bi-list"></i>
        </button>
        @endif
    </nav>

    <!-- ─── MOBILE DRAWER ─── -->
    @if(auth()->check())
    <div class="mobile-drawer-overlay" id="drawerOverlay"></div>
    <div class="mobile-drawer" id="mobileDrawer">
        <div class="drawer-header">
            <div class="nav-logo" style="margin:0;">
                <div class="logo-icon"><i class="bi bi-cpu-fill"></i></div>
                <span class="logo-text">Mec<span>App</span></span>
            </div>
            <button class="drawer-close" id="drawerClose"><i class="bi bi-x-lg"></i></button>
        </div>

        <div class="drawer-user">
            <div class="drawer-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
            <div>
                <div class="drawer-user-name">{{ auth()->user()->name }}</div>
                <div class="drawer-user-role">{{ __(optional(auth()->user())->role ?? 'Admin') }}</div>
            </div>
        </div>

        <nav class="drawer-nav">
            @php $role = optional(auth()->user())->role; @endphp

            @if($role === 'Admin')
                <a href="{{ route('dashboard') }}" class="drawer-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>{{ __('Dashboard') }}
                </a>
                <a href="{{ route('equipment.index') }}" class="drawer-link {{ request()->routeIs('equipment.*') ? 'active' : '' }}">
                    <i class="bi bi-cpu"></i>{{ __('Activos') }}
                </a>
                <a href="{{ route('maintenances.index') }}" class="drawer-link {{ request()->routeIs('maintenances.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-data-fill"></i>{{ __('Órdenes de Trabajo') }}
                </a>
                <a href="{{ route('analyst.dashboard') }}" class="drawer-link {{ request()->routeIs('analyst.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-fill"></i>{{ __('Métricas') }}
                </a>
                <a href="{{ route('inventory.index') }}" class="drawer-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam-fill"></i>{{ __('Inventario') }}
                </a>
                <a href="{{ route('users.index') }}" class="drawer-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>{{ __('Usuarios') }}
                </a>

            @elseif($role === 'Technician')
                <a href="{{ route('technician.dashboard') }}" class="drawer-link {{ request()->routeIs('technician.*') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>{{ __('Mi Panel') }}
                </a>
                <a href="{{ route('equipment.index') }}" class="drawer-link {{ request()->routeIs('equipment.*') ? 'active' : '' }}">
                    <i class="bi bi-cpu"></i>{{ __('Equipos') }}
                </a>
                <a href="{{ route('maintenances.index') }}" class="drawer-link {{ request()->routeIs('maintenances.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-data-fill"></i>{{ __('Mis OTs') }}
                </a>
                <a href="{{ route('inventory.index') }}" class="drawer-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam-fill"></i>{{ __('Inventario') }}
                </a>

            @elseif($role === 'Analyst')
                <a href="{{ route('analyst.dashboard') }}" class="drawer-link {{ request()->routeIs('analyst.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-fill"></i>{{ __('Métricas') }}
                </a>
            @endif

            <div style="margin-top:0.75rem; padding-top:0.75rem; border-top:1px solid rgba(255,255,255,0.08);">
                <div class="px-3 mb-4">
                    <div class="lang-switcher w-100 p-1" style="background: rgba(255,255,255,0.05);">
                        <a href="{{ route('locale.switch', 'es') }}" class="lang-btn-mobile {{ app()->getLocale() === 'es' ? 'active' : '' }}">
                            <span>🌐</span>&nbsp;Español
                        </a>
                        <a href="{{ route('locale.switch', 'en') }}" class="lang-btn-mobile {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                            <span>🌐</span>&nbsp;English
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="drawer-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="drawer-link w-100 text-danger fw-semibold" style="background:rgba(239,68,68,0.08);">
                    <i class="bi bi-box-arrow-right"></i>{{ __('Cerrar Sesión') }}
                </button>
            </form>
        </div>
    </div>

    <!-- ─── BOTTOM TAB BAR (Mobile) ─── -->
    <div class="bottom-tab-bar">
        <div class="btb-inner">
            @php $role2 = optional(auth()->user())->role; @endphp
            @if($role2 === 'Admin')
                <a href="{{ route('dashboard') }}" class="btb-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i><span>{{ __('Inicio') }}</span>
                </a>
                <a href="{{ route('maintenances.index') }}" class="btb-item {{ request()->routeIs('maintenances.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-data-fill"></i><span>{{ __('OTs') }}</span>
                </a>
                <a href="{{ route('equipment.index') }}" class="btb-item {{ request()->routeIs('equipment.*') ? 'active' : '' }}">
                    <i class="bi bi-cpu"></i><span>{{ __('Activos') }}</span>
                </a>
                <a href="{{ route('inventory.index') }}" class="btb-item {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam-fill"></i><span>{{ __('Inventario') }}</span>
                </a>
                <button class="btb-item" onclick="document.getElementById('drawerToggle').click()">
                    <i class="bi bi-three-dots"></i><span>{{ __('Más') }}</span>
                </button>
            @elseif($role2 === 'Technician')
                <a href="{{ route('technician.dashboard') }}" class="btb-item {{ request()->routeIs('technician.*') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i><span>{{ __('Panel') }}</span>
                </a>
                <a href="{{ route('maintenances.index') }}" class="btb-item {{ request()->routeIs('maintenances.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-data-fill"></i><span>{{ __('Mis OTs') }}</span>
                </a>
                <a href="{{ route('equipment.index') }}" class="btb-item {{ request()->routeIs('equipment.*') ? 'active' : '' }}">
                    <i class="bi bi-cpu"></i><span>{{ __('Equipos') }}</span>
                </a>
                <a href="{{ route('inventory.index') }}" class="btb-item {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam-fill"></i><span>{{ __('Inventario') }}</span>
                </a>
                <button class="btb-item" onclick="document.getElementById('drawerToggle').click()">
                    <i class="bi bi-person-circle"></i><span>{{ __('Cuenta') }}</span>
                </button>
            @endif
        </div>
    </div>
    @endif

    <!-- ─── FLASH ALERTS ─── -->
    @if(session('success'))
    <div class="flash-alert flash-alert-success auto-dismiss" id="flash-success" role="alert">
        <i class="bi bi-check-circle-fill" style="font-size:1.1rem;"></i>
        <span>{{ session('success') }}</span>
        <button class="flash-alert-close" onclick="this.parentElement.remove()" aria-label="Cerrar">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    @endif
    @if(session('error'))
    <div class="flash-alert flash-alert-error" id="flash-error" role="alert">
        <i class="bi bi-exclamation-triangle-fill" style="font-size:1.1rem;"></i>
        <span>{{ session('error') }}</span>
        <button class="flash-alert-close" onclick="this.parentElement.remove()" aria-label="Cerrar">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    @endif

    <!-- ─── MAIN CONTENT ─── -->
    <main>
        @yield('content')
    </main>

    @stack('modals')

    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Mobile Drawer JS -->
    <script nonce="{{ $cspNonce }}">
        (function() {
            const toggle  = document.getElementById('drawerToggle');
            const drawer  = document.getElementById('mobileDrawer');
            const overlay = document.getElementById('drawerOverlay');
            const close   = document.getElementById('drawerClose');
            if (!toggle) return;

            function openDrawer()  { drawer.classList.add('open'); overlay.classList.add('open'); document.body.style.overflow='hidden'; }
            function closeDrawer() { drawer.classList.remove('open'); overlay.classList.remove('open'); document.body.style.overflow=''; }

            toggle.addEventListener('click', openDrawer);
            close.addEventListener('click', closeDrawer);
            overlay.addEventListener('click', closeDrawer);

            // Close on resize to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) closeDrawer();
            });
        })();
    </script>

    <!-- Auto-remove success flash after animation ends -->
    <script nonce="{{ $cspNonce }}">
        (function() {
            const flash = document.getElementById('flash-success');
            if (!flash) return;
            // Remove from DOM after CSS animation completes (4.5s)
            flash.addEventListener('animationend', function() {
                flash.remove();
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
