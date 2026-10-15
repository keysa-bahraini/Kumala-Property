<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Kumala Property')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Teal K Favicon (Sama persis seperti foto userpage) -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='%2300838F'/%3E%3Ctext x='32' y='47' font-family='sans-serif' font-weight='900' font-size='42' fill='%23FFFFFF' text-anchor='middle'%3EK%3C/text%3E%3C/svg%3E">
    <link rel="alternate icon" type="image/png" href="{{ asset('favicon.png') }}?v=4">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=4">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Master CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        :root {
            --admin-sidebar-width: 250px;
            --admin-header-height: 64px;
            --admin-navy-dark: #0D1B2A;
            --admin-navy-sidebar: #0C1929;
            --admin-teal-active: #0B5C66;
            --admin-teal-accent: #00838F;
            --admin-cyan-sub: #38BDF8;
            --admin-bg-body: #F4F7FB;
            --admin-border-subtle: #E2E8F0;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }

        body {
            background-color: var(--admin-bg-body);
            color: #0F172A;
            margin: 0;
            display: flex;
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: var(--admin-sidebar-width);
            background: var(--admin-navy-sidebar);
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            transition: transform 0.25s ease;
        }

        .admin-sidebar-header {
            padding: 24px 22px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .admin-brand-icon {
            width: 32px;
            height: 32px;
            background: rgba(56, 189, 248, 0.12);
            border: 1px solid rgba(56, 189, 248, 0.3);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #38BDF8;
            flex-shrink: 0;
        }
        .admin-brand-text {
            display: flex;
            flex-direction: column;
        }
        .admin-brand-title {
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            color: #FFFFFF;
            line-height: 1.15;
        }
        .admin-brand-badge {
            font-size: 0.65rem;
            font-weight: 700;
            color: #38BDF8;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .admin-nav {
            padding: 14px 14px;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .admin-nav-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            border-radius: 8px;
            color: #8FA3BF;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
        }
        .admin-nav-item-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .admin-nav-item:hover {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.05);
        }
        .admin-nav-item.active {
            color: #FFFFFF;
            background: var(--admin-teal-active);
            font-weight: 700;
        }
        .admin-nav-item.active svg {
            color: #FFFFFF;
        }
        .admin-nav-badge {
            background: #00838F;
            color: #FFFFFF;
            font-size: 0.72rem;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 12px;
            line-height: 1.2;
        }

        .admin-sidebar-footer {
            padding: 16px 14px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
        .btn-keluar-portal {
            width: 100%;
            padding: 11px 14px;
            background: #08121D;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 8px;
            color: #E2E8F0;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-keluar-portal:hover {
            background: #102135;
            color: #FFFFFF;
            border-color: rgba(255, 255, 255, 0.25);
        }

        /* Main Area */
        .admin-main-wrap {
            margin-left: var(--admin-sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
        }

        .admin-topbar {
            height: var(--admin-header-height);
            background: #FFFFFF;
            border-bottom: 1px solid var(--admin-border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .admin-search-box {
            position: relative;
            width: 440px;
        }
        .admin-search-box input {
            width: 100%;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 9px 36px 9px 36px;
            font-size: 0.84rem;
            color: #0F172A;
            transition: all 0.2s;
            outline: none;
        }
        .admin-search-box input:focus {
            border-color: #00838F;
            background: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(0, 131, 143, 0.12);
        }
        .admin-search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            display: flex;
            pointer-events: none;
        }
        .admin-search-shortcut {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #00838F;
        }

        .admin-topbar-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .btn-admin-add {
            background: #0E1E2E;
            color: #FFFFFF;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.15s;
        }
        .btn-admin-add:hover {
            background: #172D45;
            color: #FFFFFF;
        }

        .admin-notification-btn {
            position: relative;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 6px;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }
        .admin-notification-btn:hover {
            color: #0F172A;
            background: #F1F5F9;
        }
        .notification-badge-dot {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 7px;
            height: 7px;
            background: #EF4444;
            border-radius: 50%;
            border: 1.5px solid #FFFFFF;
        }

        .admin-content {
            padding: 24px 32px 48px;
            flex: 1;
            min-width: 0;
            max-width: 100%;
        }

        .mobile-hamburger {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            color: #334155;
            margin-right: 8px;
            border-radius: 6px;
            align-items: center;
            justify-content: center;
            min-width: 44px;
            min-height: 44px;
        }
        .mobile-hamburger:hover {
            background: #F1F5F9;
        }

        .admin-sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(14, 30, 46, 0.6);
            backdrop-filter: blur(2px);
            z-index: 999;
        }
        .admin-sidebar-backdrop.open {
            display: block;
        }

        .admin-sidebar-close {
            display: none;
            margin-left: auto;
            background: none;
            border: none;
            color: #94A3B8;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            align-items: center;
            justify-content: center;
            min-width: 44px;
            min-height: 44px;
        }
        .admin-sidebar-close:hover {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 1024px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.open {
                transform: translateX(0);
                box-shadow: 0 10px 40px rgba(0,0,0,0.4);
            }
            .admin-sidebar-close {
                display: flex;
            }
            .admin-main-wrap {
                margin-left: 0;
            }
            .mobile-hamburger {
                display: flex;
            }
            .admin-search-box {
                width: 240px;
            }
        }
        @media (max-width: 768px) {
            .admin-search-box {
                display: none;
            }
        }
        @media (max-width: 640px) {
            .admin-topbar {
                padding: 0 14px;
                height: 58px;
            }
            .admin-content {
                padding: 16px 14px 40px;
            }
            .admin-topbar-actions {
                gap: 8px;
            }
            .btn-admin-add {
                padding: 6px 10px;
                font-size: 0.75rem;
            }
            .btn-admin-add .btn-admin-add-text-full {
                display: none;
            }
            .btn-admin-add .btn-admin-add-text-short {
                display: inline;
            }
        }
        @media (min-width: 641px) {
            .btn-admin-add .btn-admin-add-text-short {
                display: none;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Mobile Sidebar Backdrop -->
    <div class="admin-sidebar-backdrop" id="admin-sidebar-backdrop" onclick="closeAdminSidebar()"></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="admin-sidebar">
        <div class="admin-sidebar-header">
            <div class="admin-brand-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <div class="admin-brand-text">
                <span class="admin-brand-title">Kumala Property</span>
                <span class="admin-brand-badge">KUMALA ADMIN SUITE</span>
            </div>
            <button type="button" class="admin-sidebar-close" onclick="closeAdminSidebar()" title="Tutup Menu">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" onclick="closeAdminSidebar()">
                <div class="admin-nav-item-left">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                    <span>Dashboard</span>
                </div>
            </a>

            <a href="{{ route('admin.properties') }}" class="admin-nav-item {{ request()->routeIs('admin.properties') ? 'active' : '' }}" onclick="closeAdminSidebar()">
                <div class="admin-nav-item-left">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    <span>Manajemen Properti</span>
                </div>
            </a>

            <a href="{{ route('admin.submissions') }}" class="admin-nav-item {{ request()->routeIs('admin.submissions') ? 'active' : '' }}" onclick="closeAdminSidebar()">
                <div class="admin-nav-item-left">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    <span>Listing Masuk</span>
                </div>
                <span class="admin-nav-badge">{{ $pendingSubmissionsCount ?? 0 }}</span>
            </a>

            <a href="{{ route('admin.transactions') }}" class="admin-nav-item {{ request()->routeIs('admin.transactions') ? 'active' : '' }}" onclick="closeAdminSidebar()">
                <div class="admin-nav-item-left">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                    <span>Riwayat Closing</span>
                </div>
            </a>

            <a href="{{ route('admin.inquiries') }}" class="admin-nav-item {{ request()->routeIs('admin.inquiries') ? 'active' : '' }}" onclick="closeAdminSidebar()">
                <div class="admin-nav-item-left">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span>Data Inquiry</span>
                </div>
                <span class="admin-nav-badge">{{ $activeInquiriesCount ?? 0 }}</span>
            </a>

            <a href="{{ route('admin.settings') }}" class="admin-nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}" onclick="closeAdminSidebar()">
                <div class="admin-nav-item-left">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" x2="20" y1="21" y2="21"/><line x1="4" x2="20" y1="14" y2="14"/><line x1="4" x2="20" y1="7" y2="7"/><circle cx="8" cy="7" r="2"/><circle cx="16" cy="14" r="2"/><circle cx="10" cy="21" r="2"/></svg>
                    <span>Pengaturan Master</span>
                </div>
            </a>
        </nav>

        <div class="admin-sidebar-footer">
            <a href="{{ route('admin.logout') }}" class="btn-keluar-portal">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                <span>Keluar Portal</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="admin-main-wrap">
        <!-- Topbar -->
        <header class="admin-topbar">
            <div style="display: flex; align-items: center;">
                <button type="button" class="mobile-hamburger" onclick="openAdminSidebar()" title="Buka Menu">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>

                <form action="{{ route('admin.properties') }}" method="GET" class="admin-search-box" style="margin: 0;">
                    <span class="admin-search-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID properti, nama properti, atau lokasi...">
                    <span class="admin-search-shortcut"></span>
                </form>
            </div>

            <div class="admin-topbar-actions">
                @yield('topbar-extra')

                <a href="{{ route('admin.properties', ['action' => 'add']) }}" class="btn-admin-add">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    <span class="btn-admin-add-text-full">Tambah Properti</span>
                    <span class="btn-admin-add-text-short">Tambah</span>
                </a>

                <button type="button" class="admin-notification-btn" title="Notifikasi" onclick="showToast('Sistem sinkronisasi real-time beroperasi normal.')">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    <span class="notification-badge-dot"></span>
                </button>
            </div>
        </header>

        <!-- Content Body -->
        <main class="admin-content">
            @yield('content')
        </main>
    </div>

    <!-- Global Toast Notification -->
    <div id="toast-notification" style="position: fixed; bottom: 24px; right: 24px; z-index: 9999; background: #0E1E2E; color: #FFFFFF; padding: 12px 20px; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); display: none; align-items: center; gap: 10px; border-left: 4px solid #00838F;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#00838F" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <span id="toast-message" style="font-size: 0.88rem; font-weight: 500;"></span>
    </div>

    <script>
        function openAdminSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('admin-sidebar-backdrop');
            if (sidebar) sidebar.classList.add('open');
            if (backdrop) backdrop.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeAdminSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('admin-sidebar-backdrop');
            if (sidebar) sidebar.classList.remove('open');
            if (backdrop) backdrop.classList.remove('open');
            document.body.style.overflow = '';
        }

        function showToast(msg) {
            const toast = document.getElementById('toast-notification');
            const messageEl = document.getElementById('toast-message');
            if (toast && messageEl) {
                messageEl.textContent = msg;
                toast.style.display = 'flex';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 3500);
            }
        }
    </script>
    @yield('scripts')
</body>
</html>
