<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Kumala Property')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Master CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        :root {
            --admin-sidebar-width: 260px;
            --admin-header-height: 68px;
        }

        body {
            background-color: #F4F6F9;
            color: var(--text-dark);
            margin: 0;
            display: flex;
            min-height: 100vh;
            font-family: var(--font-body);
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: var(--admin-sidebar-width);
            background: #0E2238;
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            transition: var(--transition-normal);
        }

        .admin-sidebar-header {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .admin-brand-icon {
            width: 38px;
            height: 38px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
        }
        .admin-brand-text {
            display: flex;
            flex-direction: column;
        }
        .admin-brand-title {
            font-family: var(--font-heading);
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            color: #FFFFFF;
            line-height: 1.1;
        }
        .admin-brand-badge {
            font-size: 0.68rem;
            font-weight: 700;
            color: var(--accent-teal);
            letter-spacing: 0.8px;
        }

        .admin-nav {
            padding: 20px 16px;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .admin-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            color: #94A3B8;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition-fast);
        }
        .admin-nav-item:hover {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.06);
        }
        .admin-nav-item.active {
            color: #FFFFFF;
            background: rgba(0, 180, 136, 0.18);
            border-left: 3px solid var(--accent-teal);
            font-weight: 700;
        }
        .admin-nav-item svg {
            flex-shrink: 0;
        }

        .admin-sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.15);
        }
        .admin-user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }
        .admin-avatar {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: var(--accent-teal);
            color: #0E2238;
            font-weight: 800;
            font-size: 0.82rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Main Area */
        .admin-main-wrap {
            margin-left: var(--admin-sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .admin-topbar {
            height: var(--admin-header-height);
            background: #FFFFFF;
            border-bottom: 1px solid var(--border-color);
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
            width: 380px;
        }
        .admin-search-box input {
            width: 100%;
            background: #F8FAFC;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 8px 12px 8px 36px;
            font-size: 0.85rem;
            color: var(--primary-navy);
            transition: var(--transition-fast);
        }
        .admin-search-box input:focus {
            border-color: var(--accent-teal);
            background: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(0, 180, 136, 0.15);
        }
        .admin-search-icon {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
        }

        .admin-content {
            padding: 32px;
            flex: 1;
        }

        @media (max-width: 992px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.open {
                transform: translateX(0);
            }
            .admin-main-wrap {
                margin-left: 0;
            }
            .admin-search-box {
                width: 220px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="admin-sidebar">
        <div class="admin-sidebar-header">
            <div class="admin-brand-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <div class="admin-brand-text">
                <span class="admin-brand-title">KUMALA ADMIN</span>
                <span class="admin-brand-badge">CONTROL SUITE v2.4</span>
            </div>
        </div>

        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                <span>Dashboard</span>
            </a>

            <a href="javascript:void(0)" onclick="showToast('Halaman Manajemen Properti tersedia di modul berikutnya.')" class="admin-nav-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <span>Manajemen Properti</span>
            </a>

            <a href="javascript:void(0)" onclick="showToast('Halaman Listing Masuk tersedia di modul berikutnya.')" class="admin-nav-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/></svg>
                <span>Listing Masuk</span>
            </a>

            <a href="javascript:void(0)" onclick="showToast('Halaman Riwayat Transaksi tersedia di modul berikutnya.')" class="admin-nav-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 14 14"/></svg>
                <span>Riwayat Transaksi</span>
            </a>

            <a href="javascript:void(0)" onclick="showToast('Halaman Inquiry Leads tersedia di modul berikutnya.')" class="admin-nav-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <span>Inquiry Leads</span>
            </a>

            <a href="javascript:void(0)" onclick="showToast('Halaman Pengaturan Sistem tersedia di modul berikutnya.')" class="admin-nav-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                <span>Pengaturan Sistem</span>
            </a>
        </nav>

        <div class="admin-sidebar-footer">
            <div class="admin-user-profile">
                <div class="admin-avatar">AP</div>
                <div style="flex: 1; min-width: 0;">
                    <strong style="display: block; font-size: 0.85rem; color: #FFFFFF; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Aditya Pratama</strong>
                    <span style="font-size: 0.72rem; color: #94A3B8;">Super Admin</span>
                </div>
            </div>

            <div style="display: flex; gap: 8px;">
                <a href="{{ route('home') }}" target="_blank" style="flex: 1; padding: 6px 10px; background: rgba(255, 255, 255, 0.08); border-radius: 6px; font-size: 0.75rem; color: #CBD5E1; text-align: center; text-decoration: none;">
                    Web Publik
                </a>
                <a href="{{ route('admin.logout') }}" style="padding: 6px 10px; background: rgba(225, 29, 72, 0.15); border-radius: 6px; font-size: 0.75rem; color: #FDA4AF; text-decoration: none;">
                    Keluar
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="admin-main-wrap">
        <!-- Topbar -->
        <header class="admin-topbar">
            <div style="display: flex; align-items: center; gap: 16px;">
                <button type="button" class="btn-icon-action" style="display: none;" id="mobile-toggle" onclick="document.getElementById('admin-sidebar').classList.toggle('open')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>

                <div class="admin-search-box">
                    <span class="admin-search-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </span>
                    <input type="text" placeholder="Cari ID #KP-xxx, nama klien, atau lokasi...">
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="display: flex; align-items: center; gap: 6px; font-size: 0.78rem; color: #059669; font-weight: 600; background: #E6FBF5; padding: 6px 12px; border-radius: 6px;">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #059669; display: inline-block;"></span>
                    <span>SLA Hijau (Normal)</span>
                </div>

                <a href="{{ route('titip-properti') }}" target="_blank" class="btn-primary" style="padding: 8px 16px; font-size: 0.82rem; border-radius: 6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    <span>+ Input Properti</span>
                </a>
            </div>
        </header>

        <!-- Content Body -->
        <main class="admin-content">
            @yield('content')
        </main>
    </div>

    <!-- Global Toast Notification -->
    <div id="toast-notification" style="position: fixed; bottom: 24px; right: 24px; z-index: 9999; background: #0E2238; color: #FFFFFF; padding: 14px 22px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.25); display: none; align-items: center; gap: 12px; border-left: 4px solid #00B488;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#00B488" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <span id="toast-message" style="font-size: 0.92rem; font-weight: 500;"></span>
    </div>

    <script>
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
