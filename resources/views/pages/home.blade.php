@extends('layouts.app')

@section('title', 'Kumala Property - Jual & Sewa Properti Eksklusif')
@section('meta_description', 'Platform real estate mewah terdepan di Indonesia. Portofolio hunian prestisius, villa tropis, penthouse, dan ruang komersial prima dengan kurasi arsitektural ketat.')

@section('content')
<!-- ======================================================================
     1. HERO SECTION (Light Coastal Aesthetic - Clean & Architectural)
     ====================================================================== -->
<section style="padding: 48px 0 36px; background-color: var(--bg-coastal);">
    <div class="container">
        <!-- Hero Header -->
        <div style="max-width: 860px; margin: 0 auto 36px; text-align: center;">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: #E6FBF5; border: 1px solid #99EEDB; padding: 5px 16px; border-radius: var(--radius-pill); margin-bottom: 20px;">
                <span style="width: 7px; height: 7px; border-radius: 50%; background: #00B488;"></span>
                <span style="font-size: 0.74rem; font-weight: 800; letter-spacing: 0.06em; text-transform: uppercase; color: #00838F;">{{ $globalSettings['home_hero_badge'] ?? 'ATELIER PROPERTI MEWAH & INVESTASI TERKURASI' }}</span>
            </div>

            <h1 style="font-size: clamp(2.3rem, 4.8vw, 3.6rem); font-weight: 800; line-height: 1.15; color: #0E1E2E; margin-bottom: 18px; letter-spacing: -0.02em;">
                {{ $globalSettings['home_hero_title'] ?? 'Temukan Hunian Eksklusif & Aset Bernilai Abadi di Indonesia' }}
            </h1>

            <p style="font-size: 1.05rem; color: #526071; line-height: 1.65; max-width: 700px; margin: 0 auto;">
                {{ $globalSettings['home_hero_desc'] ?? 'Menghubungkan pemilik properti bernilai tinggi dengan investor & pembeli eksklusif. Setiap unit diaudit dengan verifikasi dokumen legalitas notarial dan kurasi arsitektural menyeluruh.' }}
            </p>
        </div>

        <!-- 4-Parameter Search Card (Matching Explore Mockup Exactly) -->
        <div style="background-color: #FFFFFF; border-radius: 14px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(14, 34, 56, 0.05); max-width: 1100px; margin: 0 auto; border: 1px solid #DDE3EA;">
            <form action="{{ route('explore') }}" method="GET" id="hero-search-form">
                <input type="hidden" name="type" id="hero-type-input" value="Semua">

                <div style="display: grid; grid-template-columns: 1.15fr 1fr 1fr 1fr; gap: 14px;" class="explore-filter-grid">
                    
                    <!-- Parameter 1: Transaksi Segmented Switcher -->
                    <div>
                        <label style="display: block; font-size: 0.72rem; font-weight: 800; color: #64748B; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 8px;">
                            1. JENIS TRANSAKSI
                        </label>
                        <div style="background-color: #EBEFF4; border-radius: 8px; padding: 4px; display: flex; gap: 4px; height: 44px; box-sizing: border-box;">
                            <button type="button" class="hero-type-btn active" onclick="setHeroType('Semua', this)" style="flex: 1; border: none; border-radius: 6px; font-size: 0.84rem; font-weight: 700; cursor: pointer; transition: all 0.15s; background: #0E1E2E; color: #FFFFFF;">
                                Semua
                            </button>
                            <button type="button" class="hero-type-btn" onclick="setHeroType('Jual', this)" style="flex: 1; border: none; border-radius: 6px; font-size: 0.84rem; font-weight: 600; cursor: pointer; transition: all 0.15s; background: transparent; color: #475569;">
                                Jual
                            </button>
                            <button type="button" class="hero-type-btn" onclick="setHeroType('Sewa', this)" style="flex: 1; border: none; border-radius: 6px; font-size: 0.84rem; font-weight: 600; cursor: pointer; transition: all 0.15s; background: transparent; color: #475569;">
                                Sewa
                            </button>
                        </div>
                    </div>

                    <!-- Parameter 2: Kategori Properti -->
                    <div>
                        <label style="display: block; font-size: 0.72rem; font-weight: 800; color: #64748B; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 8px;">
                            2. KATEGORI PROPERTI
                        </label>
                        <div style="position: relative;">
                            <select name="category" style="width: 100%; height: 44px; background-color: #EBEFF4; border: 1px solid transparent; border-radius: 8px; padding: 0 36px 0 14px; font-size: 0.88rem; color: #1E293B; font-weight: 600; outline: none; appearance: none; cursor: pointer;">
                                <option value="Semua Tipe Properti">Semua Tipe Properti</option>
                                <option value="Villa Mewah">Villa Tropis / Mewah</option>
                                <option value="Rumah Tinggal">Rumah Mewah</option>
                                <option value="Apartemen / Penthouse">Apartemen / Penthouse</option>
                                <option value="Tanah Komersial">Tanah Komersial</option>
                                <option value="Ruko / Shophouse">Ruko / Shophouse</option>
                            </select>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2.5" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); pointer-events: none;"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                    </div>

                    <!-- Parameter 3: Lokasi Properti -->
                    <div>
                        <label style="display: block; font-size: 0.72rem; font-weight: 800; color: #64748B; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 8px;">
                            3. LOKASI PROPERTI
                        </label>
                        <div style="position: relative;">
                            <select name="location" style="width: 100%; height: 44px; background-color: #EBEFF4; border: 1px solid transparent; border-radius: 8px; padding: 0 36px 0 14px; font-size: 0.88rem; color: #1E293B; font-weight: 600; outline: none; appearance: none; cursor: pointer;">
                                <option value="Semua Wilayah">Semua Wilayah</option>
                                <option value="Bali">Bali (Uluwatu, Canggu, Seminyak)</option>
                                <option value="Jakarta Selatan">Jakarta Selatan (Kemang, Pondok Indah)</option>
                                <option value="BSD City">BSD City - Tangerang</option>
                                <option value="Surabaya">Surabaya Barat (CitraLand)</option>
                                <option value="Bandung">Bandung &amp; Sekitarnya</option>
                            </select>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2.5" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); pointer-events: none;"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                    </div>

                    <!-- Parameter 4: Rentang Harga -->
                    <div>
                        <label style="display: block; font-size: 0.72rem; font-weight: 800; color: #64748B; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 8px;">
                            4. RENTANG HARGA
                        </label>
                        <div style="position: relative;">
                            <select name="price_range" style="width: 100%; height: 44px; background-color: #EBEFF4; border: 1px solid transparent; border-radius: 8px; padding: 0 36px 0 14px; font-size: 0.88rem; color: #1E293B; font-weight: 600; outline: none; appearance: none; cursor: pointer;">
                                <option value="Semua Rentang Harga">Semua Rentang Harga</option>
                                <option value="under_5b">&lt; Rp 5 Miliar</option>
                                <option value="5b_15b">Rp 5 Miliar – 15 Miliar</option>
                                <option value="15b_30b">Rp 15 Miliar – 30 Miliar</option>
                                <option value="above_30b">&gt; Rp 30 Miliar</option>
                            </select>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2.2" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); pointer-events: none;"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                        </div>
                    </div>

                </div>

                <!-- Footer Bar Card Filter -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px; padding-top: 14px; border-top: 1px solid #EBEFF4; flex-wrap: wrap; gap: 12px;">
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 0.82rem; color: #64748B;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                        <span>Konfigurasi 4 parameter pencarian eksklusif</span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 16px;">
                        <button type="reset" onclick="resetHeroFilter()" style="background: none; border: none; color: #64748B; font-size: 0.84rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                            <span>Reset Filter</span>
                        </button>

                        <button type="submit" style="background-color: #0E1E2E; color: #FFFFFF; border: none; border-radius: 8px; padding: 10px 22px; font-size: 0.88rem; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; transition: background 0.15s;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            <span>Terapkan Filter</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 3 Performance Credibility Badges (Matching PRD Section 5.6 & About Us Mockup) -->
        <div style="max-width: 920px; margin: 36px auto 0; background: #FFFFFF; border: 1px solid #DDE3EA; border-radius: 12px; padding: 18px 24px; box-shadow: 0 1px 3px rgba(14, 34, 56, 0.04);">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; text-align: center;" id="trust-metrics-grid">
                <div style="border-right: 1px solid #EBEFF4; padding: 0 10px;">
                    <div style="font-size: 1.85rem; font-weight: 800; color: #0E1E2E; font-family: var(--font-heading); line-height: 1;">15%</div>
                    <div style="font-size: 0.78rem; font-weight: 600; color: #64748B; margin-top: 4px;">Tingkat Lolos Kurasi</div>
                </div>
                <div style="border-right: 1px solid #EBEFF4; padding: 0 10px;">
                    <div style="font-size: 1.85rem; font-weight: 800; color: #0E1E2E; font-family: var(--font-heading); line-height: 1;">100%</div>
                    <div style="font-size: 0.78rem; font-weight: 600; color: #64748B; margin-top: 4px;">Verifikasi Notarial &amp; BPN</div>
                </div>
                <div style="padding: 0 10px;">
                    <div style="font-size: 1.85rem; font-weight: 800; color: #0E1E2E; font-family: var(--font-heading); line-height: 1;">Rp 2.4T+</div>
                    <div style="font-size: 0.78rem; font-weight: 600; color: #64748B; margin-top: 4px;">Aset Terfasilitasi</div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ======================================================================
     2. FEATURED LISTINGS (Koleksi Pilihan Kumala - Explore Grid Matching)
     ====================================================================== -->
<section style="padding: 60px 0; background-color: var(--bg-coastal);">
    <div class="container">
        
        <!-- Section Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 6px; background: #FFFFFF; border: 1px solid #DDE3EA; color: #00838F; padding: 4px 12px; border-radius: var(--radius-pill); font-size: 0.72rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 8px;">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #00B488;"></span>
                    <span>{{ $globalSettings['home_curated_badge'] ?? 'KOLEKSI PILIHAN KUMALA' }}</span>
                </div>
                <h2 style="font-size: 2.1rem; color: #0E1E2E; font-weight: 800; margin: 0 0 6px 0; letter-spacing: -0.01em;">
                    {{ $globalSettings['home_curated_title'] ?? 'Koleksi Properti Unggulan' }}
                </h2>
                <p style="color: #64748B; font-size: 0.94rem; margin: 0;">
                    {{ $globalSettings['home_curated_subtitle'] ?? 'Temukan hunian eksklusif, villa tropis, apartemen modern, dan lahan strategis di kawasan prima.' }}
                </p>
            </div>

            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <div style="background: #FFFFFF; border: 1px solid #DDE3EA; border-radius: 8px; padding: 7px 14px; font-size: 0.78rem; font-weight: 700; color: #0E1E2E; display: inline-flex; align-items: center; gap: 8px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#00838F" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><line x1="3" x2="21" y1="9" y2="9"/><line x1="9" x2="9" y1="21" y2="9"/></svg>
                    <span>STATUS KATALOG: Menampilkan 6 Properti Pilihan</span>
                </div>
                <a href="{{ route('explore') }}" style="background: #0E1E2E; color: #FFFFFF; border-radius: 8px; padding: 9px 18px; font-size: 0.84rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: background 0.15s;">
                    <span>Lihat Seluruh 48 Properti</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            </div>
        </div>

        <!-- 6 Properties Grid (Sesuai Data PRD) -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;" id="featured-props-grid">
            @foreach($featuredProperties as $prop)
            <div class="property-card" id="card-prop-{{ $loop->iteration }}">
                <div class="card-media-wrapper">
                    <img src="{{ $prop['image_main'] }}" alt="{{ $prop['title'] }}" class="card-media-img" loading="lazy">
                    
                    <!-- Badges Top Left -->
                    <div class="card-badges-top">
                        <span class="badge-tag {{ $prop['transaction_type'] === 'Jual' ? 'badge-tag-navy' : 'badge-tag-teal' }}">
                            {{ $prop['transaction_type'] === 'Jual' ? 'DIJUAL' : 'DISEWAKAN' }}
                        </span>
                        <span class="badge-tag badge-tag-white">
                            {{ $prop['legal_status'] }}
                        </span>
                    </div>

                    <!-- Heart Favorite Button -->
                    <button type="button" class="card-btn-favorite" data-fav-id="{{ $prop['id'] }}" onclick="toggleFavorite('{{ $prop['id'] }}', '{{ $prop['title'] }}')" title="Simpan ke Favorit" style="border-radius: 50%;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                    </button>

                    @if(!empty($prop['highlight_badge']))
                    <div class="card-highlight-pill">
                        {{ $prop['highlight_badge'] }}
                    </div>
                    @endif
                </div>

                <div class="card-content">
                    <div class="card-location">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>{{ $prop['location_area'] }}, {{ $prop['location_city'] }}</span>
                    </div>

                    <h3 class="card-title">
                        <a href="{{ route('property.detail', $prop['slug']) }}">{{ $prop['title'] }}</a>
                    </h3>

                    <div class="card-specs-row">
                        @foreach($prop['specs_tags'] as $tag)
                        <span class="spec-chip">{{ $tag }}</span>
                        @endforeach
                    </div>

                    <div class="card-footer">
                        <div class="card-price-block">
                            <span class="card-price-label">{{ ($prop['transaction_type'] ?? 'Jual') === 'Jual' ? 'HARGA PENAWARAN' : 'BIAYA SEWA' }}</span>
                            <span class="card-price-val">{{ ($prop['transaction_type'] ?? 'Jual') === 'Sewa' ? str_replace(' / tahun', '/thn', $prop['price_short']) : $prop['price_short'] }}</span>
                        </div>
                        <div class="card-actions-group">
                            <a href="https://wa.me/6281288992345?text=Halo%20Concierge%20Kumala,%20saya%20tertarik%20dengan%20unit%20{{ urlencode($prop['title']) }}%20({{ $prop['id'] }})." target="_blank" class="card-btn-wa" title="Tanya via WhatsApp">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            </a>
                            <a href="{{ route('property.detail', $prop['slug']) }}" class="card-btn-detail">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

<!-- ======================================================================
     3. MENGAPA MEMPERCAYAKAN ASET (6 Pilar Keunggulan - Matching About Us Mockup)
     ====================================================================== -->
<section style="padding: 60px 0; background-color: var(--bg-coastal); border-top: 1px solid #DDE3EA;">
    <div class="container">
        <!-- Section Header -->
        <div style="max-width: 760px; margin-bottom: 32px;">
            <div style="font-size: 0.72rem; font-weight: 800; color: #00838F; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 8px;">
                STANDAR KEUNGGULAN
            </div>
            <h2 style="font-size: 2.1rem; color: #0E1E2E; font-weight: 800; line-height: 1.25; margin: 0 0 10px 0; letter-spacing: -0.01em;">
                Mengapa Pemilik &amp; Pembeli Mempercayakan Aset Bersama Kami
            </h2>
            <p style="font-size: 0.94rem; color: #64748B; line-height: 1.6; margin: 0;">
                Kami menolak pendekatan mass-market. Setiap interaksi dan properti ditangani dengan ketelitian artisan, menjunjung tinggi privasi serta akurasi hukum.
            </p>
        </div>

        <!-- 6 Feature Cards (3 Columns x 2 Rows) -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;" id="pillars-grid">
            
            <!-- Card 1 -->
            <div style="background: #FFFFFF; border: 1px solid #DDE3EA; border-radius: 12px; padding: 24px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 1px 3px rgba(14, 34, 56, 0.03);">
                <div>
                    <div style="width: 40px; height: 40px; border-radius: 8px; background: #EBF1F5; color: #0E1E2E; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m2 22 1-1h3l9-9"/><path d="M3 21v-3l9-9"/><circle cx="16" cy="6" r="4"/></svg>
                    </div>
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: #0E1E2E; margin: 0 0 8px 0;">Kurasi Arsitektural Ketat</h3>
                    <p style="font-size: 0.85rem; color: #64748B; line-height: 1.55; margin: 0 0 16px 0;">
                        Hanya sekitar 15% properti yang lolos seleksi kelayakan desain, tata sirkulasi udara, kualitas material bangunan, dan daya tahan jangka panjang.
                    </p>
                </div>
                <div style="background: #F8FAFC; border-radius: 6px; padding: 7px 12px; font-size: 0.74rem; font-weight: 600; color: #475569; border: 1px solid #EBEFF4;">
                    Standar Kurasi Desain &amp; Struktur
                </div>
            </div>

            <!-- Card 2 -->
            <div style="background: #FFFFFF; border: 1px solid #DDE3EA; border-radius: 12px; padding: 24px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 1px 3px rgba(14, 34, 56, 0.03);">
                <div>
                    <div style="width: 40px; height: 40px; border-radius: 8px; background: #EBF1F5; color: #0E1E2E; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: #0E1E2E; margin: 0 0 8px 0;">Verifikasi Legalitas Menyeluruh</h3>
                    <p style="font-size: 0.85rem; color: #64748B; line-height: 1.55; margin: 0 0 16px 0;">
                        Pengecekan BPN resmi, validasi SHM/HGB murni, PBG/IMB, izin tata ruang zonasi, hingga pelunasan PBB untuk jaminan transaksi clean and clear.
                    </p>
                </div>
                <div style="background: #F8FAFC; border-radius: 6px; padding: 7px 12px; font-size: 0.74rem; font-weight: 600; color: #475569; border: 1px solid #EBEFF4;">
                    Due Diligence Notaris &amp; PPAT
                </div>
            </div>

            <!-- Card 3 -->
            <div style="background: #FFFFFF; border: 1px solid #DDE3EA; border-radius: 12px; padding: 24px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 1px 3px rgba(14, 34, 56, 0.03);">
                <div>
                    <div style="width: 40px; height: 40px; border-radius: 8px; background: #EBF1F5; color: #0E1E2E; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: #0E1E2E; margin: 0 0 8px 0;">Privasi &amp; Kerahasiaan Klien</h3>
                    <p style="font-size: 0.85rem; color: #64748B; line-height: 1.55; margin: 0 0 16px 0;">
                        Identitas pemilik dan profil finansial calon investor dilindungi dengan standar protokol Non-Disclosure Agreement (NDA) yang ketat.
                    </p>
                </div>
                <div style="background: #F8FAFC; border-radius: 6px; padding: 7px 12px; font-size: 0.74rem; font-weight: 600; color: #475569; border: 1px solid #EBEFF4;">
                    Protokol NDA Eksklusif
                </div>
            </div>

            <!-- Card 4 -->
            <div style="background: #FFFFFF; border: 1px solid #DDE3EA; border-radius: 12px; padding: 24px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 1px 3px rgba(14, 34, 56, 0.03);">
                <div>
                    <div style="width: 40px; height: 40px; border-radius: 8px; background: #EBF1F5; color: #0E1E2E; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                    </div>
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: #0E1E2E; margin: 0 0 8px 0;">Tanpa Biaya Tersembunyi</h3>
                    <p style="font-size: 0.85rem; color: #64748B; line-height: 1.55; margin: 0 0 16px 0;">
                        Struktur komisi transparan sesuai regulasi resmi. Tanpa permainan markup harga sepihak, biaya manipulasi appraisal, maupun retribusi siluman.
                    </p>
                </div>
                <div style="background: #F8FAFC; border-radius: 6px; padding: 7px 12px; font-size: 0.74rem; font-weight: 600; color: #475569; border: 1px solid #EBEFF4;">
                    100% Struktur Harga Otentik
                </div>
            </div>

            <!-- Card 5 -->
            <div style="background: #FFFFFF; border: 1px solid #DDE3EA; border-radius: 12px; padding: 24px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 1px 3px rgba(14, 34, 56, 0.03);">
                <div>
                    <div style="width: 40px; height: 40px; border-radius: 8px; background: #EBF1F5; color: #0E1E2E; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: #0E1E2E; margin: 0 0 8px 0;">Pendampingan End-to-End</h3>
                    <p style="font-size: 0.85rem; color: #64748B; line-height: 1.55; margin: 0 0 16px 0;">
                        Mulai dari penyusunan private showing, negosiasi komersial, penyusunan draft PPJB, hingga penandatanganan AJB sah di depan Notaris.
                    </p>
                </div>
                <div style="background: #F8FAFC; border-radius: 6px; padding: 7px 12px; font-size: 0.74rem; font-weight: 600; color: #475569; border: 1px solid #EBEFF4;">
                    Konsep Concierge Terintegrasi
                </div>
            </div>

            <!-- Card 6 -->
            <div style="background: #FFFFFF; border: 1px solid #DDE3EA; border-radius: 12px; padding: 24px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 1px 3px rgba(14, 34, 56, 0.03);">
                <div>
                    <div style="width: 40px; height: 40px; border-radius: 8px; background: #EBF1F5; color: #0E1E2E; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2l-2 2m-1-1l-3 3m-2-2l-4 4a2 2 0 0 0 0 2.83l3.17 3.17a2 2 0 0 0 2.83 0l4-4-2-2m-3 3l-1.5-1.5"/></svg>
                    </div>
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: #0E1E2E; margin: 0 0 8px 0;">Jejaring Off-Market Eksklusif</h3>
                    <p style="font-size: 0.85rem; color: #64748B; line-height: 1.55; margin: 0 0 16px 0;">
                        Akses istimewa ke puluhan portofolio private listings bernilai tinggi yang pemiliknya memilih untuk tidak mempublikasikan secara publik ke internet.
                    </p>
                </div>
                <div style="background: #F8FAFC; border-radius: 6px; padding: 7px 12px; font-size: 0.74rem; font-weight: 600; color: #475569; border: 1px solid #EBEFF4;">
                    Private Vault Invitation
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ======================================================================
     4. PROSES KONSINYASI KAMI (Matching Titip Properti Mockup Sidebar)
     ====================================================================== -->
<section style="padding: 60px 0; background-color: var(--bg-coastal); border-top: 1px solid #DDE3EA;">
    <div class="container">
        <!-- Section Header -->
        <div style="text-align: center; max-width: 720px; margin: 0 auto 36px;">
            <div style="display: inline-flex; align-items: center; gap: 6px; background: #E6FBF5; border: 1px solid #99EEDB; color: #00838F; padding: 4px 14px; border-radius: var(--radius-pill); font-size: 0.72rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 10px;">
                <span style="width: 6px; height: 6px; border-radius: 50%; background: #00B488;"></span>
                <span>ALUR KONSINYASI TERSTRUKTUR</span>
            </div>
            <h2 style="font-size: 2.1rem; color: #0E1E2E; font-weight: 800; margin: 0 0 8px 0; letter-spacing: -0.01em;">
                Proses Konsinyasi Kami
            </h2>
            <p style="font-size: 0.94rem; color: #64748B; line-height: 1.6; margin: 0;">
                Empat tahapan sistematis untuk memfasilitasi penjualan &amp; penyewaan properti bernilai tinggi secara akurat, aman, dan profesional.
            </p>
        </div>

        <!-- 4 Step Cards -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;" id="steps-grid">
            
            <!-- Step 1 -->
            <div style="background: #FFFFFF; border: 1px solid #DDE3EA; border-radius: 12px; padding: 22px 20px; position: relative;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: #0E1E2E; color: #FFFFFF; font-weight: 800; font-size: 0.92rem; display: flex; align-items: center; justify-content: center; margin-bottom: 14px;">
                    1
                </div>
                <h3 style="font-size: 0.96rem; font-weight: 700; color: #0E1E2E; margin: 0 0 6px 0;">Submit Data Properti</h3>
                <p style="font-size: 0.82rem; color: #64748B; line-height: 1.5; margin: 0;">
                    Isi formulir ringkas spesifikasi unit dan unggah foto dokumentasi awal aset Anda.
                </p>
            </div>

            <!-- Step 2 -->
            <div style="background: #FFFFFF; border: 1px solid #DDE3EA; border-radius: 12px; padding: 22px 20px; position: relative;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: #0E1E2E; color: #FFFFFF; font-weight: 800; font-size: 0.92rem; display: flex; align-items: center; justify-content: center; margin-bottom: 14px;">
                    2
                </div>
                <h3 style="font-size: 0.96rem; font-weight: 700; color: #0E1E2E; margin: 0 0 6px 0;">Kurasi &amp; Valuasi Objektif</h3>
                <p style="font-size: 0.82rem; color: #64748B; line-height: 1.5; margin: 0;">
                    Tim appraiser kami menganalisis Nilai Pasar Terkini (CMA) dan kelayakan legalitas BPN.
                </p>
            </div>

            <!-- Step 3 -->
            <div style="background: #FFFFFF; border: 1px solid #DDE3EA; border-radius: 12px; padding: 22px 20px; position: relative;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: #0E1E2E; color: #FFFFFF; font-weight: 800; font-size: 0.92rem; display: flex; align-items: center; justify-content: center; margin-bottom: 14px;">
                    3
                </div>
                <h3 style="font-size: 0.96rem; font-weight: 700; color: #0E1E2E; margin: 0 0 6px 0;">Pemotretan &amp; Listing Eksklusif</h3>
                <p style="font-size: 0.82rem; color: #64748B; line-height: 1.5; margin: 0;">
                    Produksi fotografi arsitektur, video tur, serta promosi terarah ke investor premium.
                </p>
            </div>

            <!-- Step 4 -->
            <div style="background: #FFFFFF; border: 1px solid #DDE3EA; border-radius: 12px; padding: 22px 20px; position: relative;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: #0E1E2E; color: #FFFFFF; font-weight: 800; font-size: 0.92rem; display: flex; align-items: center; justify-content: center; margin-bottom: 14px;">
                    4
                </div>
                <h3 style="font-size: 0.96rem; font-weight: 700; color: #0E1E2E; margin: 0 0 6px 0;">Closing &amp; Pendampingan Legal</h3>
                <p style="font-size: 0.82rem; color: #64748B; line-height: 1.5; margin: 0;">
                    Fasilitasi negosiasi, penerbitan PPJB, hingga penandatanganan AJB resmi PPAT.
                </p>
            </div>

        </div>
    </div>
</section>

<!-- ======================================================================
     5. CONSIGNMENT CALLOUT BANNER (Matching Titip Properti Header Card)
     ====================================================================== -->
<section style="padding: 40px 0 60px; background-color: var(--bg-coastal);">
    <div class="container">
        <div style="background: #FFFFFF; border: 1px solid #DDE3EA; border-radius: 14px; padding: 36px 40px; box-shadow: 0 4px 16px rgba(14, 34, 56, 0.04); display: flex; align-items: center; justify-content: space-between; gap: 32px; flex-wrap: wrap;">
            
            <div style="max-width: 640px;">
                <div style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.74rem; font-weight: 800; color: #00838F; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 10px;">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #00B488;"></span>
                    <span>{{ $globalSettings['home_consignment_badge'] ?? 'LAYANAN KONSINYASI & PEMASARAN RESMI' }}</span>
                </div>
                
                <h2 style="font-size: 1.95rem; font-weight: 800; color: #0E1E2E; line-height: 1.25; margin: 0 0 10px 0; letter-spacing: -0.01em;">
                    {{ $globalSettings['home_consignment_title'] ?? 'Titipkan Properti Anda, Jangkau Pembeli & Penyewa Berkualitas' }}
                </h2>
                
                <p style="font-size: 0.92rem; color: #64748B; line-height: 1.6; margin: 0 0 18px 0;">
                    {{ $globalSettings['home_consignment_desc'] ?? 'Tim kurator Kumala Property siap mendampingi proses valuasi presisi, produksi visual arsitektural berkelas, hingga finalisasi legalitas tuntas tanpa biaya di muka.' }}
                </p>

                <!-- 3 Benefits Pills -->
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <div style="background: #F8FAFC; border: 1px solid #DDE3EA; border-radius: 6px; padding: 5px 12px; font-size: 0.78rem; font-weight: 600; color: #1E293B; display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#00B488" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>{{ $globalSettings['titip_benefit_1'] ?? 'Valuasi & Konsultasi Gratis' }}</span>
                    </div>
                    <div style="background: #F8FAFC; border: 1px solid #DDE3EA; border-radius: 6px; padding: 5px 12px; font-size: 0.78rem; font-weight: 600; color: #1E293B; display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#00B488" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>{{ $globalSettings['titip_benefit_2'] ?? 'Jejaring Investor Terverifikasi' }}</span>
                    </div>
                    <div style="background: #F8FAFC; border: 1px solid #DDE3EA; border-radius: 6px; padding: 5px 12px; font-size: 0.78rem; font-weight: 600; color: #1E293B; display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#00B488" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>{{ $globalSettings['titip_benefit_3'] ?? 'Transparan & Legalitas Aman BPN' }}</span>
                    </div>
                </div>
            </div>

            <!-- CTA Actions -->
            <div style="display: flex; flex-direction: column; gap: 10px; min-width: 240px;">
                <a href="{{ route('titip-properti') }}" style="background-color: #0E1E2E; color: #FFFFFF; border-radius: 8px; padding: 12px 24px; font-size: 0.88rem; font-weight: 700; text-decoration: none; text-align: center; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.15s;">
                    <span>{{ $globalSettings['home_consignment_cta'] ?? 'Titip Properti Sekarang' }}</span>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
                <a href="https://wa.me/{{ $globalSettings['hotline_wa_numeric'] ?? '6281199887711' }}?text=Halo%20Kumala%20Property,%20saya%20ingin%20konsultasi%20titip%20properti." target="_blank" style="background-color: #FFFFFF; color: #0E1E2E; border: 1.5px solid #DDE3EA; border-radius: 8px; padding: 11px 20px; font-size: 0.88rem; font-weight: 700; text-decoration: none; text-align: center; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.15s;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#00B488" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span>{{ $globalSettings['home_consignment_wa_cta'] ?? 'Tanya Advisor via WA' }}</span>
                </a>
            </div>

        </div>
    </div>
</section>

<!-- ======================================================================
     6. CONSULTATION CARD (Matching Explore Mockup Bottom Box)
     ====================================================================== -->
<section style="padding: 0 0 60px; background-color: var(--bg-coastal);">
    <div class="container">
        <div style="background-color: #EBEFF4; border: 1px solid #DDE3EA; border-radius: 14px; padding: 28px 36px; display: flex; align-items: center; justify-content: space-between; gap: 24px; flex-wrap: wrap;">
            
            <div style="display: flex; align-items: center; gap: 18px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #FFFFFF; border: 1px solid #DDE3EA; display: flex; align-items: center; justify-content: center; color: #00838F; flex-shrink: 0;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0E1E2E; margin: 0 0 4px 0;">
                        {{ $globalSettings['home_consult_title'] ?? 'Tidak Menemukan Kriteria yang Dicari?' }}
                    </h3>
                    <p style="font-size: 0.88rem; color: #64748B; margin: 0; max-width: 620px;">
                        {{ $globalSettings['home_consult_desc'] ?? 'Konsultasikan preferensi properti impian Anda secara privat bersama Property Advisor kami. Kami siap mengurasi unit off-market sesuai ekspektasi Anda.' }}
                    </p>
                </div>
            </div>

            <a href="https://wa.me/{{ $globalSettings['hotline_wa_numeric'] ?? '6281199887711' }}?text=Halo%20Kumala%20Property,%20saya%20ingin%20konsultasi%20kriteria%20properti%20khusus." target="_blank" style="background-color: #00B488; color: #FFFFFF; border-radius: 8px; padding: 11px 22px; font-weight: 700; font-size: 0.88rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; flex-shrink: 0; transition: background 0.15s;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <span>{{ $globalSettings['home_consult_cta'] ?? 'Konsultasi via WhatsApp' }}</span>
            </a>

        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
function setHeroType(type, btn) {
    document.getElementById('hero-type-input').value = type;
    document.querySelectorAll('.hero-type-btn').forEach(b => {
        b.style.background = 'transparent';
        b.style.color = '#475569';
        b.style.fontWeight = '600';
    });
    btn.style.background = '#0E1E2E';
    btn.style.color = '#FFFFFF';
    btn.style.fontWeight = '700';
}

function resetHeroFilter() {
    document.getElementById('hero-type-input').value = 'Semua';
    const firstBtn = document.querySelector('.hero-type-btn');
    if (firstBtn) setHeroType('Semua', firstBtn);
}
</script>
@endsection
