@extends('layouts.admin')

@section('title', 'Pengaturan Sistem & Konten Portal - Kumala Admin')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Top Alert Notification -->
    @if(session('success'))
        <div style="background: #E6FFFA; border: 1px solid #B2F5EA; border-left: 4px solid #00838F; padding: 14px 18px; border-radius: 8px; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#00838F" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span style="font-size: 0.88rem; font-weight: 700; color: #004D40;">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.style.display='none'" style="background: none; border: none; font-size: 1.2rem; color: #004D40; cursor: pointer; line-height: 1;">&times;</button>
        </div>
    @endif

    <!-- Breadcrumb & Status Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div style="font-size: 0.76rem; font-weight: 700; color: #64748B; letter-spacing: 0.5px; text-transform: uppercase;">
            <span>KONFIGURASI</span> &gt; <span style="color: #00838F;">CMS KONTEN PORTAL</span> <span style="color: #CBD5E1; margin: 0 4px;">&bull;</span> <span style="color: #94A3B8; font-weight: 500;">LIVE SYNCED</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <div style="display: inline-flex; align-items: center; gap: 6px; background: #FFFFFF; border: 1px solid #E2E8F0; padding: 4px 12px; border-radius: 20px; font-size: 0.74rem; font-weight: 600; color: #0E1E2E;">
                <span style="width: 7px; height: 7px; border-radius: 50%; background: #059669; display: inline-block;"></span>
                <span>Sinkronisasi Otomatis Real-time</span>
            </div>
        </div>
    </div>

    <!-- Header Section (1 Type dengan Halaman Admin Lainnya) -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 18px;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 6px; background: #E0F7FA; color: #00838F; padding: 3px 10px; border-radius: 4px; font-size: 0.7rem; font-weight: 800; letter-spacing: 0.5px; margin-bottom: 8px; text-transform: uppercase;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                <span>CMS &amp; KENDALI KONTEN PORTAL</span>
            </div>
            <h1 style="font-size: 1.75rem; color: #0E1E2E; margin: 0 0 6px 0; font-weight: 800; letter-spacing: -0.01em;">
                Pengaturan Sistem &amp; Konten Portal
            </h1>
            <p style="font-size: 0.86rem; color: #64748B; margin: 0; max-width: 740px; line-height: 1.5;">
                Kelola logo, identitas brand, narasi, dan seluruh teks tampilan pada setiap halaman publik sesuai tata letak aslinya di portal web.
            </p>
        </div>

        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <a href="{{ route('home') }}" target="_blank" class="btn-clean-outline">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                <span>Lihat Web Publik</span>
            </a>
            <button type="submit" form="cmsSettingsForm" class="btn-clean-teal">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </div>

    <!-- Navigation Tabs Bar (Minimalis & Rapi) -->
    <div class="cms-tabs-nav">
        <button type="button" class="cms-tab-pill active" onclick="switchCmsTab('tab-branding', this)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            <span>Logo &amp; Brand</span>
        </button>
        <button type="button" class="cms-tab-pill" onclick="switchCmsTab('tab-home', this)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            <span>Halaman Beranda</span>
        </button>
        <button type="button" class="cms-tab-pill" onclick="switchCmsTab('tab-explore', this)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <span>Jelajah &amp; Detail</span>
        </button>
        <button type="button" class="cms-tab-pill" onclick="switchCmsTab('tab-titip-fav', this)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <span>Titip Properti &amp; Favorit</span>
        </button>
        <button type="button" class="cms-tab-pill" onclick="switchCmsTab('tab-about-contact', this)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            <span>Tentang Kami &amp; Kontak</span>
        </button>
        <button type="button" class="cms-tab-pill" onclick="switchCmsTab('tab-master', this)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/></svg>
            <span>Master Kategori &amp; Wilayah</span>
        </button>
    </div>

    <!-- MAIN FORM -->
    <form id="cmsSettingsForm" action="{{ route('admin.settings.save') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- ================================================================= -->
        <!-- TAB 1: LOGO & IDENTITAS BRANDING -->
        <!-- ================================================================= -->
        <div id="tab-branding" class="cms-tab-content active">
            
            <!-- Navbar Header Simulation Box -->
            <div class="cms-panel-card">
                <div class="cms-panel-header">
                    <div>
                        <h3 class="cms-panel-title">Header Navigasi &amp; Logo Utama</h3>
                        <p class="cms-panel-desc">Tampilan ini mencerminkan navbar bagian atas web publik Anda.</p>
                    </div>
                </div>

                <!-- Live Visual Simulation -->
                <div style="background: #0E1E2E; border-radius: 10px; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div id="logoPreviewWrap" style="min-width: 44px; min-height: 44px; max-height: 52px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.06); border: 1px dashed rgba(255,255,255,0.25); border-radius: 8px; padding: 6px;">
                            @if(!empty($settings['brand_logo_url']))
                                <img id="logoPreviewImg" src="{{ $settings['brand_logo_url'] }}" alt="Logo" style="max-height: 38px; width: auto; object-fit: contain;">
                            @else
                                <div id="logoPreviewFallback" style="width: 32px; height: 32px; background: #00838F; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 900; color: #FFFFFF; font-size: 1rem;">K</div>
                                <img id="logoPreviewImg" src="" alt="Logo" style="display: none; max-height: 38px; width: auto; object-fit: contain;">
                            @endif
                        </div>
                        <div>
                            <div style="color: #FFFFFF; font-weight: 800; font-size: 1rem; letter-spacing: 0.6px;" id="previewBrandNameText">{{ $settings['brand_name'] ?? 'KUMALA PROPERTY' }}</div>
                            <div style="color: #00838F; font-size: 0.68rem; font-weight: 700; letter-spacing: 0.6px;" id="previewBrandSubText">{{ $settings['brand_subtitle'] ?? 'JUAL & SEWA PROPERTI' }}</div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px; align-items: center;">
                        <label for="brand_logo_file" class="btn-clean-teal" style="cursor: pointer; padding: 8px 14px; font-size: 0.76rem;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            <span>Unggah File Logo</span>
                        </label>
                        <input type="file" id="brand_logo_file" name="brand_logo_file" accept="image/*" style="display: none;" onchange="previewSelectedLogo(event)">
                        <span style="font-size: 0.72rem; color: #94A3B8;">PNG / SVG / JPG</span>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="cms-form-group">
                        <label class="cms-label">Nama Brand Utama <span style="color: #E53E3E;">*</span></label>
                        <input type="text" name="brand_name" value="{{ $settings['brand_name'] ?? 'KUMALA PROPERTY' }}" required class="cms-input" oninput="document.getElementById('previewBrandNameText').innerText = this.value">
                        <span class="cms-hint">Tampil sebagai teks utama logo header &amp; footer.</span>
                    </div>

                    <div class="cms-form-group">
                        <label class="cms-label">Sub-Judul Brand</label>
                        <input type="text" name="brand_subtitle" value="{{ $settings['brand_subtitle'] ?? 'JUAL & SEWA PROPERTI' }}" class="cms-input" oninput="document.getElementById('previewBrandSubText').innerText = this.value">
                        <span class="cms-hint">Tampil di bawah nama brand pada navbar.</span>
                    </div>

                    <div class="cms-form-group" style="grid-column: span 2;">
                        <label class="cms-label">Logo Image URL (Opsional jika menggunakan link gambar eksternal)</label>
                        <input type="url" name="brand_logo_url" id="brand_logo_url" value="{{ $settings['brand_logo_url'] ?? '' }}" placeholder="https://domain.com/path-to-logo.png" class="cms-input" oninput="previewLogoUrl(this.value)">
                        <span class="cms-hint">Jika file logo diunggah di atas, kolom ini otomatis terisi.</span>
                    </div>

                    <div class="cms-form-group" style="grid-column: span 2;">
                        <label class="cms-label">Slogan Portal (Tagline)</label>
                        <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? 'Luxury Real Estate Atelier & Integrated Property Management Platform' }}" class="cms-input">
                    </div>
                </div>
            </div>

            <!-- Footer Bio & Copyright Card -->
            <div class="cms-panel-card" style="margin-top: 24px;">
                <div class="cms-panel-header">
                    <div>
                        <h3 class="cms-panel-title">Profil Perusahaan &amp; Hak Cipta di Footer</h3>
                        <p class="cms-panel-desc">Bagian bawah halaman publik yang merangkum kredibilitas Kumala Property.</p>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div class="cms-form-group">
                        <label class="cms-label">Deskripsi Profil Singkat Perusahaan (Footer Bio)</label>
                        <textarea name="footer_bio" rows="3" class="cms-textarea">{{ $settings['footer_bio'] ?? 'Kumala Property adalah platform real estate mewah terdepan di Indonesia. Portofolio hunian prestisius, villa tropis, penthouse, dan ruang komersial prima dengan kurasi arsitektural ketat dan legalitas 100% terverifikasi notaris.' }}</textarea>
                    </div>

                    <div class="cms-form-group">
                        <label class="cms-label">Teks Hak Cipta (Footer Copyright)</label>
                        <input type="text" name="footer_copyright" value="{{ $settings['footer_copyright'] ?? '© 2026 Kumala Property. Seluruh hak cipta dilindungi undang-undang.' }}" class="cms-input">
                    </div>
                </div>
            </div>

        </div>

        <!-- ================================================================= -->
        <!-- TAB 2: HALAMAN BERANDA (HOME) -->
        <!-- ================================================================= -->
        <div id="tab-home" class="cms-tab-content">
            
            <!-- 1. Hero Section Box (Sesuai Layout Beranda Aslinya) -->
            <div class="cms-panel-card">
                <div class="cms-panel-header">
                    <div>
                        <h3 class="cms-panel-title">1. Banner Hero Beranda (Tampilan Utama)</h3>
                        <p class="cms-panel-desc">Seksi paling atas yang pertama kali dilihat saat membuka situs.</p>
                    </div>
                </div>

                <!-- Visual Mockup Hero -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 26px 24px; margin-bottom: 24px; text-align: center;">
                    
                    <!-- Pill Tag Hero -->
                    <div style="max-width: 440px; margin: 0 auto 16px;">
                        <label class="cms-label" style="text-align: center; margin-bottom: 4px; display: block;">Label Badge Pill Atas</label>
                        <div style="display: inline-flex; align-items: center; gap: 8px; background: #E6FBF5; border: 1px solid #99EEDB; padding: 4px 14px; border-radius: 20px; width: 100%; box-sizing: border-box;">
                            <span style="width: 7px; height: 7px; border-radius: 50%; background: #00B488; flex-shrink: 0;"></span>
                            <input type="text" name="home_hero_badge" value="{{ $settings['home_hero_badge'] ?? 'ATELIER PROPERTI MEWAH & INVESTASI TERKURASI' }}" class="cms-input-subtle" style="color: #00838F; font-weight: 800; font-size: 0.74rem; letter-spacing: 0.05em; text-transform: uppercase;">
                        </div>
                    </div>

                    <!-- Headline Judul Utama Hero -->
                    <div style="max-width: 720px; margin: 0 auto 16px;">
                        <label class="cms-label" style="text-align: center; margin-bottom: 4px; display: block;">Headline Judul Utama (Besar)</label>
                        <input type="text" name="home_hero_title" value="{{ $settings['home_hero_title'] ?? 'Temukan Hunian Eksklusif & Aset Bernilai Abadi di Indonesia' }}" class="cms-input" style="font-size: 1.15rem; font-weight: 800; color: #0E1E2E; text-align: center;">
                    </div>

                    <!-- Deskripsi Hero -->
                    <div style="max-width: 680px; margin: 0 auto;">
                        <label class="cms-label" style="text-align: center; margin-bottom: 4px; display: block;">Deskripsi Paragraf Pengantar</label>
                        <textarea name="home_hero_desc" rows="3" class="cms-textarea" style="text-align: center;">{{ $settings['home_hero_desc'] ?? 'Menghubungkan pemilik properti bernilai tinggi dengan investor & pembeli eksklusif. Setiap unit diaudit dengan verifikasi dokumen legalitas notarial dan kurasi arsitektural menyeluruh.' }}</textarea>
                    </div>

                </div>

                <!-- 4 Trust Badges (Di Bawah Search Bar) -->
                <div>
                    <div style="margin-bottom: 12px;">
                        <h4 style="font-size: 0.92rem; font-weight: 800; color: #0E1E2E; margin: 0 0 2px 0;">4 Poin Kepercayaan (Trust Badges di Bawah Form Pencarian)</h4>
                        <span style="font-size: 0.76rem; color: #64748B;">4 kartu jaminan kredibilitas yang tampil sejajar di beranda.</span>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px;" id="cms-trust-grid">
                        <div class="cms-badge-card">
                            <span class="cms-badge-index">Poin 1</span>
                            <input type="text" name="home_trust_badge_1" value="{{ $settings['home_trust_badge_1'] ?? '100% SHM/HGB Terverifikasi Notaris' }}" class="cms-input" style="font-size: 0.8rem; font-weight: 600;">
                        </div>
                        <div class="cms-badge-card">
                            <span class="cms-badge-index">Poin 2</span>
                            <input type="text" name="home_trust_badge_2" value="{{ $settings['home_trust_badge_2'] ?? '< 15 Menit Respon Private Concierge' }}" class="cms-input" style="font-size: 0.8rem; font-weight: 600;">
                        </div>
                        <div class="cms-badge-card">
                            <span class="cms-badge-index">Poin 3</span>
                            <input type="text" name="home_trust_badge_3" value="{{ $settings['home_trust_badge_3'] ?? 'Zero Hidden Mark-up Fee' }}" class="cms-input" style="font-size: 0.8rem; font-weight: 600;">
                        </div>
                        <div class="cms-badge-card">
                            <span class="cms-badge-index">Poin 4</span>
                            <input type="text" name="home_trust_badge_4" value="{{ $settings['home_trust_badge_4'] ?? 'Discretionary Off-Market Listing' }}" class="cms-input" style="font-size: 0.8rem; font-weight: 600;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Seksi Koleksi Pilihan Kurator -->
            <div class="cms-panel-card" style="margin-top: 24px;">
                <div class="cms-panel-header">
                    <div>
                        <h3 class="cms-panel-title">2. Seksi "Koleksi Pilihan Kurator"</h3>
                        <p class="cms-panel-desc">Judul dan sub-judul pengantar katalog listing pilihan di beranda.</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="cms-form-group">
                        <label class="cms-label">Judul Seksi Kurator</label>
                        <input type="text" name="home_curated_title" value="{{ $settings['home_curated_title'] ?? 'Koleksi Pilihan Kurator' }}" class="cms-input">
                    </div>

                    <div class="cms-form-group">
                        <label class="cms-label">Sub-Judul / Keterangan Seksi</label>
                        <input type="text" name="home_curated_subtitle" value="{{ $settings['home_curated_subtitle'] ?? 'Portofolio hunian residensial & komersial dengan standar arsitektural tertinggi' }}" class="cms-input">
                    </div>
                </div>
            </div>

            <!-- 3. Banner Promosi Titip Properti di Beranda (Sesuai Aslinya) -->
            <div class="cms-panel-card" style="margin-top: 24px;">
                <div class="cms-panel-header">
                    <div>
                        <h3 class="cms-panel-title">3. Banner Promosi "Titip Properti" di Beranda</h3>
                        <p class="cms-panel-desc">Card ajakan bagi pemilik properti yang muncul di bagian bawah beranda.</p>
                    </div>
                </div>

                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 22px 24px;">
                    <div style="display: grid; grid-template-columns: 280px 1fr; gap: 16px; margin-bottom: 16px;">
                        <div class="cms-form-group">
                            <label class="cms-label">Badge Label Promosi</label>
                            <input type="text" name="home_consignment_badge" value="{{ $settings['home_consignment_badge'] ?? 'LAYANAN KONSINYASI & PEMASARAN RESMI' }}" class="cms-input">
                        </div>
                        <div class="cms-form-group">
                            <label class="cms-label">Judul Banner Konsinyasi</label>
                            <input type="text" name="home_consignment_title" value="{{ $settings['home_consignment_title'] ?? 'Titipkan Properti Anda, Jangkau Pembeli & Penyewa Berkualitas' }}" class="cms-input">
                        </div>
                    </div>

                    <div class="cms-form-group" style="margin-bottom: 16px;">
                        <label class="cms-label">Deskripsi Narasi Konsinyasi</label>
                        <textarea name="home_consignment_desc" rows="2" class="cms-textarea">{{ $settings['home_consignment_desc'] ?? 'Tim kurator Kumala Property siap mendampingi proses valuasi presisi, produksi visual arsitektural berkelas, hingga finalisasi legalitas tuntas tanpa biaya di muka.' }}</textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="cms-form-group">
                            <label class="cms-label">Teks Tombol Formulir Titip</label>
                            <input type="text" name="home_consignment_cta" value="{{ $settings['home_consignment_cta'] ?? 'Titip Properti Sekarang' }}" class="cms-input">
                        </div>
                        <div class="cms-form-group">
                            <label class="cms-label">Teks Tombol WhatsApp Advisor</label>
                            <input type="text" name="home_consignment_wa_cta" value="{{ $settings['home_consignment_wa_cta'] ?? 'Tanya Advisor via WA' }}" class="cms-input">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Box Konsultasi Privat / Off-Market -->
            <div class="cms-panel-card" style="margin-top: 24px;">
                <div class="cms-panel-header">
                    <div>
                        <h3 class="cms-panel-title">4. Box Konsultasi Privat / Off-Market</h3>
                        <p class="cms-panel-desc">Kotak bantuan bagi pengunjung yang mencari unit yang belum dipublikasikan.</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="cms-form-group">
                        <label class="cms-label">Judul Ajakan Konsultasi</label>
                        <input type="text" name="home_consult_title" value="{{ $settings['home_consult_title'] ?? 'Tidak Menemukan Kriteria yang Dicari?' }}" class="cms-input">
                    </div>

                    <div class="cms-form-group">
                        <label class="cms-label">Teks Tombol WhatsApp</label>
                        <input type="text" name="home_consult_cta" value="{{ $settings['home_consult_cta'] ?? 'Konsultasi via WhatsApp' }}" class="cms-input">
                    </div>

                    <div class="cms-form-group" style="grid-column: span 2;">
                        <label class="cms-label">Deskripsi Konsultasi</label>
                        <textarea name="home_consult_desc" rows="2" class="cms-textarea">{{ $settings['home_consult_desc'] ?? 'Konsultasikan preferensi properti impian Anda secara privat bersama Property Advisor kami. Kami siap mengurasi unit off-market sesuai ekspektasi Anda.' }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        <!-- ================================================================= -->
        <!-- TAB 3: JELAJAH & DETAIL PROPERTI -->
        <!-- ================================================================= -->
        <div id="tab-explore" class="cms-tab-content">
            
            <!-- Header Explore (Sesuai Aslinya) -->
            <div class="cms-panel-card">
                <div class="cms-panel-header">
                    <div>
                        <h3 class="cms-panel-title">Header Halaman Jelajah Katalog (/explore)</h3>
                        <p class="cms-panel-desc">Teks judul dan deskripsi filter katalog publik.</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 280px 1fr; gap: 20px; margin-bottom: 20px;">
                    <div class="cms-form-group">
                        <label class="cms-label">Badge Header Explore</label>
                        <input type="text" name="explore_badge" value="{{ $settings['explore_badge'] ?? 'KOLEKSI PILIHAN KUMALA' }}" class="cms-input">
                    </div>

                    <div class="cms-form-group">
                        <label class="cms-label">Judul Utama Halaman Explore</label>
                        <input type="text" name="explore_title" value="{{ $settings['explore_title'] ?? 'Eksplorasi Properti' }}" class="cms-input">
                    </div>
                </div>

                <div class="cms-form-group">
                    <label class="cms-label">Deskripsi Pengantar Jelajah</label>
                    <textarea name="explore_desc" rows="2" class="cms-textarea">{{ $settings['explore_desc'] ?? 'Temukan hunian eksklusif, villa tropis, apartemen modern, dan lahan strategis di kawasan prima.' }}</textarea>
                </div>
            </div>

            <!-- Banner Off-Market Explore (Bagian Bawah Halaman Explore) -->
            <div class="cms-panel-card" style="margin-top: 24px;">
                <div class="cms-panel-header">
                    <div>
                        <h3 class="cms-panel-title">Banner Bawah "Koleksi Off-Market" di Explore</h3>
                        <p class="cms-panel-desc">Kotak penawaran portofolio tertutup yang tampil di bagian bawah katalog jelajah.</p>
                    </div>
                </div>

                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 22px 24px;">
                    <div class="cms-form-group" style="margin-bottom: 16px;">
                        <label class="cms-label">Judul Penawaran Off-Market</label>
                        <input type="text" name="explore_offmarket_title" value="{{ $settings['explore_offmarket_title'] ?? 'Mencari Properti Off-Market / Private Collection?' }}" class="cms-input">
                    </div>

                    <div class="cms-form-group">
                        <label class="cms-label">Deskripsi Penjelasan Off-Market</label>
                        <textarea name="explore_offmarket_desc" rows="2" class="cms-textarea">{{ $settings['explore_offmarket_desc'] ?? 'Sebagian besar aset paling bergengsi tidak dipublikasikan secara umum demi menjaga privasi pemilik. Hubungi Private Client Concierge kami untuk portofolio tertutup.' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Halaman Detail Properti Concierge Box -->
            <div class="cms-panel-card" style="margin-top: 24px;">
                <div class="cms-panel-header">
                    <div>
                        <h3 class="cms-panel-title">Sidebar Concierge di Halaman Detail (/properti/{slug})</h3>
                        <p class="cms-panel-desc">Box inquiry privat di sidebar kanan saat pengunjung membuka spesifikasi properti.</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="cms-form-group">
                        <label class="cms-label">Judul Box Inquiry Concierge</label>
                        <input type="text" name="detail_concierge_title" value="{{ $settings['detail_concierge_title'] ?? 'Tertarik dengan Properti Ini?' }}" class="cms-input">
                    </div>

                    <div class="cms-form-group">
                        <label class="cms-label">Teks Jaminan SLA Respon</label>
                        <input type="text" name="detail_concierge_sla" value="{{ $settings['detail_concierge_sla'] ?? 'SLA Respon < 15 Menit • 7 Hari Seminggu' }}" class="cms-input">
                    </div>

                    <div class="cms-form-group" style="grid-column: span 2;">
                        <label class="cms-label">Deskripsi Layanan Concierge</label>
                        <textarea name="detail_concierge_desc" rows="2" class="cms-textarea">{{ $settings['detail_concierge_desc'] ?? 'Hubungi Private Client Concierge kami untuk jadwal survei privat atau dokumen legalitas lengkap.' }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        <!-- ================================================================= -->
        <!-- TAB 4: TITIP PROPERTI & FAVORIT -->
        <!-- ================================================================= -->
        <div id="tab-titip-fav" class="cms-tab-content">
            
            <!-- Halaman Titip Properti (Sesuai Aslinya) -->
            <div class="cms-panel-card">
                <div class="cms-panel-header">
                    <div>
                        <h3 class="cms-panel-title">Header Halaman Titip Properti (/titip-properti)</h3>
                        <p class="cms-panel-desc">Badge, judul, dan deskripsi di bagian atas formulir pendaftaran aset.</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 280px 1fr; gap: 20px; margin-bottom: 20px;">
                    <div class="cms-form-group">
                        <label class="cms-label">Badge Header Titip</label>
                        <input type="text" name="titip_badge" value="{{ $settings['titip_badge'] ?? 'LAYANAN KONSINYASI & PEMASARAN RESMI' }}" class="cms-input">
                    </div>

                    <div class="cms-form-group">
                        <label class="cms-label">Judul Utama Halaman Titip</label>
                        <input type="text" name="titip_title" value="{{ $settings['titip_title'] ?? 'Titipkan Properti Anda, Jangkau Pembeli & Penyewa Berkualitas' }}" class="cms-input">
                    </div>
                </div>

                <div class="cms-form-group" style="margin-bottom: 24px;">
                    <label class="cms-label">Deskripsi Pengantar Formulir</label>
                    <textarea name="titip_desc" rows="2" class="cms-textarea">{{ $settings['titip_desc'] ?? 'Tim kurator Kumala Property siap mendampingi proses valuasi presisi, produksi visual arsitektural berkelas, hingga finalisasi legalitas transaksi tuntas tanpa biaya di muka.' }}</textarea>
                </div>

                <!-- 3 Kartu Keunggulan Konsinyasi (Sesuai Aslinya di Userpage) -->
                <div>
                    <h4 style="font-size: 0.92rem; font-weight: 800; color: #0E1E2E; margin: 0 0 10px 0;">3 Nilai Garansi Konsinyasi (Pill Kepercayaan)</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px;">
                        <div class="cms-badge-card">
                            <span class="cms-badge-index">Garansi 1</span>
                            <input type="text" name="titip_benefit_1" value="{{ $settings['titip_benefit_1'] ?? 'Valuasi & Konsultasi Gratis' }}" class="cms-input" style="font-weight: 600;">
                        </div>
                        <div class="cms-badge-card">
                            <span class="cms-badge-index">Garansi 2</span>
                            <input type="text" name="titip_benefit_2" value="{{ $settings['titip_benefit_2'] ?? 'Jejaring Investor Terverifikasi' }}" class="cms-input" style="font-weight: 600;">
                        </div>
                        <div class="cms-badge-card">
                            <span class="cms-badge-index">Garansi 3</span>
                            <input type="text" name="titip_benefit_3" value="{{ $settings['titip_benefit_3'] ?? 'Transparan & Legalitas Aman BPN' }}" class="cms-input" style="font-weight: 600;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Halaman Favorit Pengunjung -->
            <div class="cms-panel-card" style="margin-top: 24px;">
                <div class="cms-panel-header">
                    <div>
                        <h3 class="cms-panel-title">Halaman Favorit Pengunjung (/favorit)</h3>
                        <p class="cms-panel-desc">Teks panduan untuk koleksi properti yang disimpan di browser pengunjung.</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 280px 1fr; gap: 20px; margin-bottom: 20px;">
                    <div class="cms-form-group">
                        <label class="cms-label">Badge Header Favorit</label>
                        <input type="text" name="favorites_badge" value="{{ $settings['favorites_badge'] ?? 'KOLEKSI PRIBADI TERSIMPAN' }}" class="cms-input">
                    </div>

                    <div class="cms-form-group">
                        <label class="cms-label">Judul Halaman Favorit</label>
                        <input type="text" name="favorites_title" value="{{ $settings['favorites_title'] ?? 'Properti Favorit Saya' }}" class="cms-input">
                    </div>
                </div>

                <div class="cms-form-group" style="margin-bottom: 20px;">
                    <label class="cms-label">Deskripsi Panduan Favorit</label>
                    <textarea name="favorites_desc" rows="2" class="cms-textarea">{{ $settings['favorites_desc'] ?? 'Daftar unit properti pilihan yang Anda simpan di perangkat ini. Anda dapat membandingkan spesifikasi teknis atau mengajukan jadwal survei privat sekaligus.' }}</textarea>
                </div>

                <div class="cms-form-group">
                    <label class="cms-label">Teks Tombol Aksi Permohonan Survei via WhatsApp</label>
                    <input type="text" name="favorites_cta" value="{{ $settings['favorites_cta'] ?? 'Konsultasi Semua Favorit via WA' }}" class="cms-input">
                </div>
            </div>

        </div>

        <!-- ================================================================= -->
        <!-- TAB 5: TENTANG KAMI & KONTAK -->
        <!-- ================================================================= -->
        <div id="tab-about-contact" class="cms-tab-content">
            
            <!-- Filosofi & Narasi Tentang Kami (Sesuai Aslinya) -->
            <div class="cms-panel-card">
                <div class="cms-panel-header">
                    <div>
                        <h3 class="cms-panel-title">1. Filosofi &amp; Narasi Cerita (/tentang-kami)</h3>
                        <p class="cms-panel-desc">Prinsip kurasi dan etika kerja yang membedakan Kumala Realty.</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 280px 1fr; gap: 20px; margin-bottom: 20px;">
                    <div class="cms-form-group">
                        <label class="cms-label">Badge Filosofi</label>
                        <input type="text" name="about_badge" value="{{ $settings['about_badge'] ?? 'ATELIER REAL ESTATE INDEPENDEN' }}" class="cms-input">
                    </div>

                    <div class="cms-form-group">
                        <label class="cms-label">Tagline Filosofis Utama</label>
                        <input type="text" name="about_hero_title" value="{{ $settings['about_hero_title'] ?? 'Kurasi Presisi untuk Koleksi Aset Bernilai Abadi' }}" class="cms-input">
                    </div>
                </div>

                <div class="cms-form-group" style="margin-bottom: 24px;">
                    <label class="cms-label">Narasi Cerita Lengkap Perusahaan</label>
                    <textarea name="about_hero_desc" rows="4" class="cms-textarea">{{ $settings['about_hero_desc'] ?? 'Kumala Property lahir dari keyakinan mendasar bahwa transaksi properti prestisius bukan sekadar urusan nominal penawaran, melainkan tentang apresiasi terhadap integritas arsitektural, transparansi legalitas tanpa celah, serta komitmen mutlak terhadap privasi klien terhormat.' }}</textarea>
                </div>

                <!-- 3 Pilar Layanan (Sesuai Grid di Halaman About) -->
                <div>
                    <h4 style="font-size: 0.92rem; font-weight: 800; color: #0E1E2E; margin: 0 0 12px 0;">Tiga Pilar Layanan Utama Kumala</h4>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                        <!-- Pilar 1 -->
                        <div class="cms-pilar-card">
                            <div class="cms-form-group" style="margin-bottom: 8px;">
                                <label class="cms-label">Judul Pilar 1</label>
                                <input type="text" name="about_p1_title" value="{{ $settings['about_p1_title'] ?? '1. Transparansi Tanpa Kompromi' }}" class="cms-input">
                            </div>
                            <div class="cms-form-group">
                                <label class="cms-label">Penjelasan Pilar 1</label>
                                <textarea name="about_p1_desc" rows="3" class="cms-textarea">{{ $settings['about_p1_desc'] ?? 'Tidak ada harga titipan atau manipulasi margin. Pembeli dan penjual mendapatkan keterbukaan valuasi objektif berbasis Comparative Market Analysis (CMA).' }}</textarea>
                            </div>
                        </div>

                        <!-- Pilar 2 -->
                        <div class="cms-pilar-card">
                            <div class="cms-form-group" style="margin-bottom: 8px;">
                                <label class="cms-label">Judul Pilar 2</label>
                                <input type="text" name="about_p2_title" value="{{ $settings['about_p2_title'] ?? '2. Kurasi Estetika & Keandalan Struktur' }}" class="cms-input">
                            </div>
                            <div class="cms-form-group">
                                <label class="cms-label">Penjelasan Pilar 2</label>
                                <textarea name="about_p2_desc" rows="3" class="cms-textarea">{{ $settings['about_p2_desc'] ?? 'Setiap unit melalui inspeksi fisik mendalam terhadap material bangunan, pencahayaan alami, zonasi lingkungan sekitar, dan potensi apresiasi investasi jangka panjang.' }}</textarea>
                            </div>
                        </div>

                        <!-- Pilar 3 -->
                        <div class="cms-pilar-card">
                            <div class="cms-form-group" style="margin-bottom: 8px;">
                                <label class="cms-label">Judul Pilar 3</label>
                                <input type="text" name="about_p3_title" value="{{ $settings['about_p3_title'] ?? '3. Pendampingan Privat & Obyektif' }}" class="cms-input">
                            </div>
                            <div class="cms-form-group">
                                <label class="cms-label">Penjelasan Pilar 3</label>
                                <textarea name="about_p3_desc" rows="3" class="cms-textarea">{{ $settings['about_p3_desc'] ?? 'Private Client Concierge kami tidak bertindak sebagai sales agresif, melainkan penasihat aset yang berdedikasi menjaga kepentingan hukum dan kenyamanan Anda hingga penandatanganan AJB.' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kontak Resmi & Kantor -->
            <div class="cms-panel-card" style="margin-top: 24px;">
                <div class="cms-panel-header">
                    <div>
                        <h3 class="cms-panel-title">2. Kantor Resmi, Hotline WhatsApp &amp; Media Sosial</h3>
                        <p class="cms-panel-desc">Perubahan nomor WhatsApp otomatis tersambung ke seluruh tombol chat di web.</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- Kontak -->
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div class="cms-form-group">
                            <label class="cms-label">Nomor WhatsApp Hotline Admin <span style="color: #E53E3E;">*</span></label>
                            <input type="text" name="hotline_wa" value="{{ $settings['hotline_wa'] ?? '+62 811-9988-7711' }}" required class="cms-input" style="font-weight: 700; color: #00838F;">
                            <span class="cms-hint">Format bebas (cth: +62 811-9988-7711), sistem otomatis membersihkan angka untuk API WhatsApp.</span>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div class="cms-form-group">
                                <label class="cms-label">Email Customer Service</label>
                                <input type="email" name="email_cs" value="{{ $settings['email_cs'] ?? 'cs@kumalaproperty.id' }}" class="cms-input">
                            </div>
                            <div class="cms-form-group">
                                <label class="cms-label">Email Kemitraan VIP</label>
                                <input type="email" name="email_business" value="{{ $settings['email_business'] ?? 'vip@kumalaproperty.co.id' }}" class="cms-input">
                            </div>
                        </div>

                        <div class="cms-form-group">
                            <label class="cms-label">Alamat Kantor Pusat Jakarta (HQ)</label>
                            <textarea name="address_hq" rows="2" class="cms-textarea">{{ $settings['address_hq'] ?? 'Kumala Heritage Suite, Jl. Senopati No. 88, Kebayoran Baru, Jakarta Selatan 12190' }}</textarea>
                        </div>

                        <div class="cms-form-group">
                            <label class="cms-label">Alamat Kantor Bali</label>
                            <textarea name="address_bali" rows="2" class="cms-textarea">{{ $settings['address_bali'] ?? 'Jl. Pantai Suluban No. 8, Pecatu, Uluwatu, Badung, Bali 80361' }}</textarea>
                        </div>
                    </div>

                    <!-- Jam & Medsos -->
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div class="cms-form-group">
                            <label class="cms-label">Jam Layanan Operasional Kantor</label>
                            <input type="text" name="office_hours" value="{{ $settings['office_hours'] ?? 'Setiap Hari: 08:00 - 20:00 WIB' }}" class="cms-input">
                        </div>

                        <div class="cms-form-group">
                            <label class="cms-label">Jam Operasional Hari Kerja (Senin - Jumat)</label>
                            <input type="text" name="hours_weekday" value="{{ $settings['hours_weekday'] ?? '08.30 - 18.00 WIB' }}" class="cms-input">
                        </div>

                        <div class="cms-form-group">
                            <label class="cms-label">Akun Instagram Resmi</label>
                            <input type="text" name="social_instagram" value="{{ $settings['social_instagram'] ?? '@kumala.realty' }}" class="cms-input">
                        </div>

                        <div class="cms-form-group">
                            <label class="cms-label">Kanal YouTube</label>
                            <input type="text" name="social_youtube" value="{{ $settings['social_youtube'] ?? 'Kumala Property Official' }}" class="cms-input">
                        </div>

                        <div class="cms-form-group">
                            <label class="cms-label">Halaman Facebook</label>
                            <input type="text" name="social_facebook" value="{{ $settings['social_facebook'] ?? 'Kumala Realty Indonesia' }}" class="cms-input">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ================================================================= -->
        <!-- TAB 6: MASTER KATEGORI & WILAYAH -->
        <!-- ================================================================= -->
        <div id="tab-master" class="cms-tab-content">
            
            <!-- Parameter Komisi -->
            <div class="cms-panel-card">
                <div class="cms-panel-header">
                    <div>
                        <h3 class="cms-panel-title">Parameter Komisi Transaksi Standar</h3>
                        <p class="cms-panel-desc">Persentase standar untuk transaksi jual dan sewa properti.</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="cms-form-group">
                        <label class="cms-label">Komisi Transaksi Jual-Beli (%)</label>
                        <input type="text" name="standard_commission_pct" value="{{ $settings['standard_commission_pct'] ?? '2.5' }}" class="cms-input">
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label">Komisi Transaksi Sewa (%)</label>
                        <input type="text" name="standard_commission_rent_pct" value="{{ $settings['standard_commission_rent_pct'] ?? '5.0' }}" class="cms-input">
                    </div>
                </div>
            </div>

            <!-- Master Kategori Properti Aktif -->
            <div class="cms-panel-card" style="margin-top: 24px;">
                <div class="cms-panel-header">
                    <div>
                        <h3 class="cms-panel-title">Master Kategori Properti Aktif</h3>
                        <p class="cms-panel-desc">Kategori ini otomatis muncul di form input properti, filter explore, dan form titip properti.</p>
                    </div>
                    <button type="button" class="btn-clean-outline" onclick="openCategoryModal()">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        <span>+ Tambah Kategori</span>
                    </button>
                </div>

                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        <thead>
                            <tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; color: #475569; font-size: 0.72rem; font-weight: 800; text-transform: uppercase;">
                                <th style="padding: 12px 16px; text-align: left;">NAMA KATEGORI</th>
                                <th style="padding: 12px 16px; text-align: left;">SUB-TIPE TERKAIT</th>
                                <th style="padding: 12px 16px; text-align: center;">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $cat)
                                <tr style="border-bottom: 1px solid #F1F5F9;">
                                    <td style="padding: 14px 16px;">
                                        <div style="font-weight: 700; color: #0E1E2E;">{{ is_array($cat) ? ($cat['name'] ?? 'Kategori') : $cat }}</div>
                                    </td>
                                    <td style="padding: 14px 16px;">
                                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                            @php
                                                $rawSubtypes = is_array($cat) ? ($cat['subtypes'] ?? []) : [];
                                                if (is_string($rawSubtypes)) {
                                                    $subtypesList = array_filter(array_map('trim', explode(',', $rawSubtypes)));
                                                } elseif (is_array($rawSubtypes)) {
                                                    $subtypesList = $rawSubtypes;
                                                } else {
                                                    $subtypesList = [];
                                                }
                                                if (empty($subtypesList)) {
                                                    $subtypesList = ['Standar'];
                                                }
                                            @endphp
                                            @foreach($subtypesList as $st)
                                                <span class="subtipe-pill">{{ $st }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td style="padding: 14px 16px; text-align: center;">
                                        <span style="background: #E0F7FA; color: #00838F; font-size: 0.72rem; font-weight: 700; padding: 3px 8px; border-radius: 4px;">● Aktif</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="padding: 24px; text-align: center; color: #94A3B8;">Belum ada kategori tersimpan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Wilayah Operasional -->
            <div class="cms-panel-card" style="margin-top: 24px;">
                <div class="cms-panel-header">
                    <div>
                        <h3 class="cms-panel-title">Wilayah Operasional Terdaftar</h3>
                        <p class="cms-panel-desc">Area geolokasi yang dicakup oleh kurator Kumala Property.</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 14px;">
                    @foreach($regions as $reg)
                        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 14px 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                <strong style="color: #0E1E2E; font-size: 0.88rem;">{{ is_array($reg) ? ($reg['name'] ?? 'Wilayah') : $reg }}</strong>
                                <span style="background: #E0F7FA; color: #00838F; font-size: 0.68rem; font-weight: 700; padding: 2px 6px; border-radius: 4px;">● Aktif</span>
                            </div>
                            <span style="font-size: 0.74rem; color: #64748B;">Cakupan Area Strategis &amp; Komersial</span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Sticky Bottom Bar (Simple & Elegan) -->
        <div class="cms-sticky-bar">
            <div style="display: flex; align-items: center; gap: 8px; color: #64748B; font-size: 0.78rem;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#00838F" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>Perubahan yang disimpan langsung tampil seketika di seluruh halaman publik portal.</span>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="reset" class="btn-clean-outline" style="padding: 9px 18px;">Reset Form</button>
                <button type="submit" class="btn-clean-teal" style="padding: 9px 20px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </div>

    </form>

    <!-- Modal Tambah Kategori -->
    <div id="modal-category" style="display: none; position: fixed; inset: 0; background: rgba(14, 30, 46, 0.45); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: #FFFFFF; border-radius: 12px; padding: 26px; width: 100%; max-width: 460px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                <h3 style="margin: 0; font-size: 1.1rem; color: #0E1E2E; font-weight: 800;">Tambah Kategori Properti</h3>
                <button type="button" onclick="closeCategoryModal()" style="background: none; border: none; font-size: 1.3rem; color: #94A3B8; cursor: pointer; line-height: 1;">&times;</button>
            </div>
            <form action="{{ route('admin.settings.category.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
                @csrf
                <div class="cms-form-group">
                    <label class="cms-label">Nama Kategori Baru <span style="color: #E53E3E;">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Penthouse Eksklusif" class="cms-input">
                </div>
                <div class="cms-form-group">
                    <label class="cms-label">Sub-tipe Deskriptif (Pisahkan dengan koma)</label>
                    <input type="text" name="subtypes" placeholder="Contoh: Sky Villa, Duplex, Luxury Tower" class="cms-input">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="button" class="btn-clean-outline" onclick="closeCategoryModal()">Batal</button>
                    <button type="submit" class="btn-clean-teal" style="padding: 8px 18px;">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@section('styles')
<style>
/* Navigation Tabs Bar (Matching the admin theme) */
.cms-tabs-nav {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    padding: 6px;
    border-radius: 10px;
}
.cms-tab-pill {
    padding: 8px 16px;
    background: transparent;
    border: none;
    border-radius: 7px;
    font-size: 0.82rem;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    transition: all 0.15s ease;
}
.cms-tab-pill:hover {
    color: #0E1E2E;
    background: #F1F5F9;
}
.cms-tab-pill.active {
    background: #0E1E2E;
    color: #FFFFFF;
    font-weight: 700;
}

/* Tab Content Panes */
.cms-tab-content {
    display: none;
}
.cms-tab-content.active {
    display: block;
}

/* Panels / Cards */
.cms-panel-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 24px 28px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}
.cms-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 22px;
    flex-wrap: wrap;
    gap: 12px;
}
.cms-panel-title {
    font-size: 1.05rem;
    font-weight: 800;
    color: #0E1E2E;
    margin: 0;
    letter-spacing: -0.01em;
}
.cms-panel-desc {
    font-size: 0.8rem;
    color: #64748B;
    margin: 4px 0 0 0;
}

/* Form Groups & Inputs */
.cms-form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.cms-label {
    font-size: 0.77rem;
    font-weight: 700;
    color: #334155;
    letter-spacing: 0.2px;
}
.cms-input {
    width: 100%;
    box-sizing: border-box;
    padding: 10px 14px;
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    font-size: 0.86rem;
    color: #0E1E2E;
    font-family: inherit;
    transition: all 0.15s;
}
.cms-input:focus {
    outline: none;
    border-color: #00838F;
    box-shadow: 0 0 0 3px rgba(0, 131, 143, 0.12);
}
.cms-input-subtle {
    width: 100%;
    background: transparent;
    border: none;
    outline: none;
    font-family: inherit;
}
.cms-textarea {
    width: 100%;
    box-sizing: border-box;
    padding: 10px 14px;
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    font-size: 0.86rem;
    color: #0E1E2E;
    font-family: inherit;
    line-height: 1.55;
    transition: all 0.15s;
    resize: vertical;
}
.cms-textarea:focus {
    outline: none;
    border-color: #00838F;
    box-shadow: 0 0 0 3px rgba(0, 131, 143, 0.12);
}
.cms-hint {
    font-size: 0.71rem;
    color: #94A3B8;
}

/* Badge & Pilar Sub-cards */
.cms-badge-card {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.cms-badge-index {
    font-size: 0.68rem;
    font-weight: 800;
    color: #00838F;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.cms-pilar-card {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* Subtipe Pill */
.subtipe-pill {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    color: #334155;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 4px;
}

/* Action Buttons (Consistent with Admin Layout) */
.btn-clean-outline {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    color: #475569;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.15s;
}
.btn-clean-outline:hover {
    background: #F8FAFC;
    color: #0E1E2E;
    border-color: #94A3B8;
}

.btn-clean-teal {
    background: #00838F;
    border: none;
    color: #FFFFFF;
    padding: 8px 18px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(0, 131, 143, 0.2);
    transition: background 0.15s;
}
.btn-clean-teal:hover {
    background: #0097A7;
}

/* Sticky Save Bar */
.cms-sticky-bar {
    position: sticky;
    bottom: 16px;
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
    border-radius: 10px;
    padding: 14px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 24px;
    z-index: 100;
    flex-wrap: wrap;
    gap: 12px;
}

@media (max-width: 900px) {
    #cms-trust-grid {
        grid-template-columns: 1fr 1fr !important;
    }
}
</style>
@endsection

@section('scripts')
<script>
function switchCmsTab(tabId, btn) {
    document.querySelectorAll('.cms-tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.cms-tab-pill').forEach(b => b.classList.remove('active'));

    const target = document.getElementById(tabId);
    if (target) target.classList.add('active');
    if (btn) btn.classList.add('active');
}

function previewSelectedLogo(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById('logoPreviewImg');
            const fallback = document.getElementById('logoPreviewFallback');
            if (previewImg) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
            }
            if (fallback) {
                fallback.style.display = 'none';
            }
        };
        reader.readAsDataURL(file);
    }
}

function previewLogoUrl(url) {
    const previewImg = document.getElementById('logoPreviewImg');
    const fallback = document.getElementById('logoPreviewFallback');
    if (url && url.trim().length > 0) {
        if (previewImg) {
            previewImg.src = url.trim();
            previewImg.style.display = 'block';
        }
        if (fallback) {
            fallback.style.display = 'none';
        }
    } else {
        if (previewImg) {
            previewImg.style.display = 'none';
        }
        if (fallback) {
            fallback.style.display = 'flex';
        }
    }
}

function openCategoryModal() {
    const modal = document.getElementById('modal-category');
    if (modal) modal.style.display = 'flex';
}

function closeCategoryModal() {
    const modal = document.getElementById('modal-category');
    if (modal) modal.style.display = 'none';
}
</script>
@endsection
