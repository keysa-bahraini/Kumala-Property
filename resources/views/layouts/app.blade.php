<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kumala Property - Jual & Sewa Properti Eksklusif')</title>
    <meta name="description" content="@yield('meta_description', 'Platform real estate mewah terdepan di Indonesia. Portofolio hunian prestisius, villa tropis, penthouse, dan ruang komersial prima dengan kurasi arsitektural ketat.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Teal K Favicon (Sama persis seperti foto userpage) -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='%2300838F'/%3E%3Ctext x='32' y='47' font-family='sans-serif' font-weight='900' font-size='42' fill='%23FFFFFF' text-anchor='middle'%3EK%3C/text%3E%3C/svg%3E">
    <link rel="alternate icon" type="image/png" href="{{ asset('favicon.png') }}?v=4">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=4">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Master CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ file_exists(public_path('css/style.css')) ? filemtime(public_path('css/style.css')) : time() }}">
    @yield('styles')
</head>
<body>

    <!-- ======================================================================
         Navigation Header
         ====================================================================== -->
    <header class="site-header">
        <div class="container nav-container">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="brand-logo" id="header-brand-logo">
                <div class="brand-icon-box">
                    @if(!empty($globalSettings['brand_logo_url']))
                        <img src="{{ $globalSettings['brand_logo_url'] }}" alt="{{ $globalSettings['brand_name'] ?? 'KUMALA PROPERTY' }}" style="max-height: 28px; width: auto; object-fit: contain; border-radius: 4px;">
                    @else
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                    @endif
                </div>
                <div class="brand-text">
                    <span class="brand-title">{{ $globalSettings['brand_name'] ?? 'KUMALA PROPERTY' }}</span>
                    <span class="brand-subtitle">{{ $globalSettings['brand_subtitle'] ?? 'JUAL & SEWA PROPERTI' }}</span>
                </div>
            </a>

            <!-- Nav Links -->
            <nav class="nav-links">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" id="nav-link-beranda">Beranda</a>
                <a href="{{ route('explore') }}" class="nav-link {{ request()->routeIs('explore') ? 'active' : '' }}" id="nav-link-explore">Explore Property</a>
                <a href="{{ route('titip-properti') }}" class="nav-link {{ request()->routeIs('titip-properti*') ? 'active' : '' }}" id="nav-link-titip">Titip Property</a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" id="nav-link-kontak">Tentang Kami & Kontak</a>
            </nav>

            <!-- Nav Actions -->
            <div class="nav-actions">
                <!-- Favorite Counter Button -->
                <a href="{{ route('favorites') }}" class="btn-icon-action {{ request()->routeIs('favorites') ? 'active' : '' }}" id="btn-favorite-counter" title="Properti Favorit Saya">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    </svg>
                    <span class="action-badge-counter" id="fav-count-badge">3</span>
                </a>

                <!-- Notification Bell -->
                <button type="button" class="btn-icon-action" id="btn-notifications" title="Notifikasi Properti">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                    </svg>
                </button>

                <!-- WhatsApp CTA -->
                @php
                    $hotlineRaw = $globalSettings['hotline_wa'] ?? '0812-8888-2900';
                    $hotlineClean = preg_replace('/[^0-9]/', '', $hotlineRaw);
                    if (substr($hotlineClean, 0, 1) === '0') {
                        $hotlineClean = '62' . substr($hotlineClean, 1);
                    }
                @endphp
                <a href="https://wa.me/{{ $hotlineClean }}?text=Halo%20Kumala%20Property,%20saya%20tertarik%20dengan%20properti%20eksklusif%20Anda." target="_blank" class="btn-cta-whatsapp" id="btn-header-wa">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <span>Hubungi Kami</span>
                </a>
                <!-- Mobile Nav Toggle -->
                <button type="button" class="mobile-nav-toggle" onclick="openMobileNav()" title="Buka Menu Navigasi">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation Drawer -->
    <div class="mobile-nav-backdrop" id="mobile-nav-backdrop" onclick="closeMobileNav()"></div>
    <div class="mobile-nav-drawer" id="mobile-nav-drawer">
        <div class="mobile-nav-header">
            <div class="brand-logo">
                <div class="brand-icon-box">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <div class="brand-text">
                    <span class="brand-title">KUMALA PROPERTY</span>
                    <span class="brand-subtitle">JUAL & SEWA PROPERTI</span>
                </div>
            </div>
            <button type="button" class="mobile-nav-close" onclick="closeMobileNav()" title="Tutup Menu">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <div class="mobile-nav-links">
            <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}" onclick="closeMobileNav()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                <span>Beranda</span>
            </a>
            <a href="{{ route('explore') }}" class="mobile-nav-link {{ request()->routeIs('explore') ? 'active' : '' }}" onclick="closeMobileNav()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/></svg>
                <span>Explore Property</span>
            </a>
            <a href="{{ route('titip-properti') }}" class="mobile-nav-link {{ request()->routeIs('titip-properti*') ? 'active' : '' }}" onclick="closeMobileNav()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/></svg>
                <span>Titip Property</span>
            </a>
            <a href="{{ route('favorites') }}" class="mobile-nav-link {{ request()->routeIs('favorites') ? 'active' : '' }}" onclick="closeMobileNav()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                <span>Favorit Saya</span>
            </a>
            <a href="{{ route('about') }}" class="mobile-nav-link {{ request()->routeIs('about') ? 'active' : '' }}" onclick="closeMobileNav()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>Tentang Kami & Kontak</span>
            </a>
            <a href="{{ route('admin.login') }}" class="mobile-nav-link" onclick="closeMobileNav()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <span>Portal Admin</span>
            </a>
        </div>

        <div class="mobile-nav-footer">
            <a href="https://wa.me/{{ $hotlineClean }}?text=Halo%20Kumala%20Property,%20saya%20tertarik%20dengan%20properti%20eksklusif%20Anda." target="_blank" class="btn-mobile-nav-wa">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <span>Hubungi WhatsApp Concierge</span>
            </a>
        </div>
    </div>

    <!-- ======================================================================
         Main Content Body
         ====================================================================== -->
    <main>
        @yield('content')
    </main>

    <!-- ======================================================================
         Site Footer
         ====================================================================== -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-top-grid">
                <!-- Col 1: Brand Info -->
                <div class="footer-col-brand">
                    <div class="brand-logo">
                        <div class="brand-icon-box">
                            @if(!empty($globalSettings['brand_logo_url']))
                                <img src="{{ $globalSettings['brand_logo_url'] }}" alt="{{ $globalSettings['brand_name'] ?? 'KUMALA PROPERTY' }}" style="max-height: 28px; width: auto; object-fit: contain; border-radius: 4px;">
                            @else
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                    <polyline points="9 22 9 12 15 12 15 22"/>
                                </svg>
                            @endif
                        </div>
                        <div class="brand-text">
                            <span class="brand-title" style="color: #FFFFFF;">{{ $globalSettings['brand_name'] ?? 'KUMALA PROPERTY' }}</span>
                            <span class="brand-subtitle" style="color: #94A3B8;">{{ $globalSettings['brand_subtitle'] ?? 'JUAL & SEWA PROPERTI' }}</span>
                        </div>
                    </div>
                    <p class="footer-brand-desc">
                        {{ $globalSettings['footer_bio'] ?? 'Jual & Sewa Properti Eksklusif. Menghadirkan portofolio hunian prestisius dan ruang komersial prima dengan pendekatan kuratorial berstandar tinggi.' }}
                    </p>
                    <div class="footer-tags">
                        <span class="footer-tag">JUAL</span>
                        <span class="footer-tag">SEWA</span>
                        <span class="footer-tag">KONSULTASI</span>
                    </div>
                </div>

                <!-- Col 2: Navigasi Cepat -->
                <div class="footer-col-nav">
                    <h4 class="footer-col-title">Navigasi Cepat</h4>
                    <ul class="footer-nav-list">
                        <li><a href="{{ route('home') }}">Beranda Utama</a></li>
                        <li><a href="{{ route('explore') }}">Eksplorasi Listing Properti</a></li>
                        <li><a href="{{ route('titip-properti') }}">Titip Jual & Sewa Unit</a></li>
                        <li><a href="{{ route('explore') }}">Daftar Properti Favorit</a></li>
                        <li><a href="{{ route('explore') }}#terms">Syarat & Ketentuan Layanan</a></li>
                    </ul>
                </div>

                <!-- Col 3: Kontak & Kantor -->
                <div class="footer-col-contact">
                    <h4 class="footer-col-title">Kontak &amp; Kantor</h4>
                    <div class="footer-contact-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>{{ $globalSettings['address'] ?? 'Kumala Tower Lt. 5, Jl. TB Simatupang No. 18, Jakarta Selatan 12430' }}</span>
                    </div>
                    <div class="footer-contact-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>{{ $globalSettings['hotline_wa'] ?? '+62 812-8888-2900' }} (WhatsApp Active)</span>
                    </div>
                    <div class="footer-contact-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        <span>{{ $globalSettings['email_cs'] ?? 'cs@kumalaproperty.co.id' }}</span>
                    </div>
                </div>

                <!-- Col 4: Jam Operasional -->
                <div class="footer-col-hours">
                    <h4 class="footer-col-title">Jam Operasional</h4>
                    <div style="font-size: 0.88rem; color: #CBD5E1; margin-bottom: 8px;">
                        <span>Hari Kerja &amp; Pelayanan:</span>
                        <strong style="color: #FFFFFF; display: block; margin-top: 2px;">{{ $globalSettings['office_hours'] ?? 'Senin - Sabtu: 08:30 - 18:00 WIB' }}</strong>
                    </div>
                    <div style="font-size: 0.88rem; margin-bottom: 16px;">
                        <span style="color: var(--accent-teal);">Minggu / Libur: Dengan Janji Temu</span>
                    </div>

                    <a href="https://wa.me/{{ $hotlineClean }}?text=Halo%20Kumala%20Property,%20saya%20ingin%20menjadwalkan%20Private%20Tour." target="_blank" class="footer-btn-tour">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                        <span>Jadwalkan Private Tour</span>
                    </a>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="footer-bottom-bar">
                <p>{{ $globalSettings['footer_copyright'] ?? '© ' . date('Y') . ' Kumala Property. Seluruh hak cipta dilindungi undang-undang.' }}</p>
                <div class="footer-bottom-links">
                    <a href="#">Privasi & Syarat</a>
                    <span>•</span>
                    <a href="{{ route('admin.login') }}" class="footer-admin-link" title="Akses Tim Internal Kumala">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <span>Portal Admin</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Global Toast Notification -->
    <style>
        #toast-notification {
            position: fixed;
            bottom: calc(24px + env(safe-area-inset-bottom, 0px));
            right: 24px;
            z-index: 99999;
            background: #0E2238;
            color: #FFFFFF;
            padding: 14px 20px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
            display: none;
            align-items: center;
            gap: 12px;
            border-left: 4px solid #00B488;
            max-width: 440px;
            word-break: break-word;
            box-sizing: border-box;
            line-height: 1.4;
        }
        @media (max-width: 640px) {
            #toast-notification {
                left: 16px !important;
                right: 16px !important;
                bottom: calc(16px + env(safe-area-inset-bottom, 0px)) !important;
                max-width: calc(100% - 32px) !important;
                font-size: 0.85rem !important;
                padding: 12px 16px !important;
            }
        }
    </style>
    <div id="toast-notification">
        <svg id="app-toast-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#00B488" stroke-width="2.5" style="flex-shrink: 0;"><polyline points="20 6 9 17 4 12"/></svg>
        <span id="toast-message" style="font-size: 0.9rem; font-weight: 500;"></span>
    </div>

    <!-- Client Script for Favorites and Global Interactions -->
    <script>
        function openMobileNav() {
            const drawer = document.getElementById('mobile-nav-drawer');
            const backdrop = document.getElementById('mobile-nav-backdrop');
            if (drawer) drawer.classList.add('open');
            if (backdrop) backdrop.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileNav() {
            const drawer = document.getElementById('mobile-nav-drawer');
            const backdrop = document.getElementById('mobile-nav-backdrop');
            if (drawer) drawer.classList.remove('open');
            if (backdrop) backdrop.classList.remove('open');
            document.body.style.overflow = '';
        }

        // Favorite Management using LocalStorage
        function getFavorites() {
            try {
                return JSON.parse(localStorage.getItem('kumala_favorites')) || ['#KP-8821', '#KP-8822', '#KP-8823'];
            } catch(e) {
                return ['#KP-8821', '#KP-8822', '#KP-8823'];
            }
        }

        function saveFavorites(favs) {
            localStorage.setItem('kumala_favorites', JSON.stringify(favs));
            updateFavoriteCounter();
        }

        function toggleFavorite(id, title) {
            let favs = getFavorites();
            const index = favs.indexOf(id);
            let added = false;
            if (index > -1) {
                favs.splice(index, 1);
                showToast(`"${title}" dihapus dari favorit.`);
            } else {
                favs.push(id);
                added = true;
                showToast(`"${title}" berhasil disimpan ke favorit!`);
            }
            saveFavorites(favs);

            // Update button styles on page
            document.querySelectorAll(`[data-fav-id="${id}"]`).forEach(btn => {
                if (added) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        function updateFavoriteCounter() {
            const favs = getFavorites();
            const badge = document.getElementById('fav-count-badge');
            const mobileBadge = document.getElementById('mobile-fav-count-badge');
            if (badge) badge.textContent = favs.length;
            if (mobileBadge) mobileBadge.textContent = favs.length;

            // Sync all heart buttons on page
            document.querySelectorAll('[data-fav-id]').forEach(btn => {
                const id = btn.getAttribute('data-fav-id');
                if (favs.includes(id)) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        function showToast(message, isError = false) {
            const toast = document.getElementById('toast-notification');
            const msgEl = document.getElementById('toast-message');
            const iconSvg = document.getElementById('app-toast-icon');
            if (toast && msgEl) {
                msgEl.textContent = message;
                if (isError) {
                    toast.style.borderLeftColor = '#EF4444';
                    if (iconSvg) {
                        iconSvg.style.stroke = '#EF4444';
                        iconSvg.innerHTML = '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>';
                    }
                } else {
                    toast.style.borderLeftColor = '#00B488';
                    if (iconSvg) {
                        iconSvg.style.stroke = '#00B488';
                        iconSvg.innerHTML = '<polyline points="20 6 9 17 4 12"/>';
                    }
                }
                toast.style.display = 'flex';
                clearTimeout(window.toastTimer);
                window.toastTimer = setTimeout(() => {
                    toast.style.display = 'none';
                }, 3500);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateFavoriteCounter();
            @if(session('success'))
                showToast(@json(session('success')), false);
            @elseif(session('error'))
                showToast(@json(session('error')), true);
            @endif
        });
    </script>

    @yield('scripts')
</body>
</html>
