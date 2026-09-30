@extends('layouts.app')

@section('title', 'Kumala Property - Jual & Sewa Properti Eksklusif')

@section('content')
<!-- ======================================================================
     HERO SECTION
     ====================================================================== -->
<section class="hero-section" style="position: relative; background: linear-gradient(180deg, #0E2238 0%, #162E4A 100%); color: #FFFFFF; padding: 90px 0 110px; overflow: hidden;">
    <!-- Background Architectural Glow & Grid Lines -->
    <div style="position: absolute; top: -100px; right: -100px; width: 600px; height: 600px; background: radial-gradient(circle, rgba(0, 180, 136, 0.15) 0%, transparent 70%); pointer-events: none;"></div>
    <div style="position: absolute; bottom: -150px; left: -100px; width: 500px; height: 500px; background: radial-gradient(circle, rgba(13, 148, 136, 0.12) 0%, transparent 70%); pointer-events: none;"></div>

    <div class="container" style="position: relative; z-index: 2;">
        <div style="max-width: 860px; margin: 0 auto; text-align: center;">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.16); padding: 8px 18px; border-radius: 999px; margin-bottom: 24px; backdrop-filter: blur(8px);">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #00B488;"></span>
                <span style="font-size: 0.8rem; font-weight: 700; letter-spacing: 0.6px; text-transform: uppercase; color: #E2E8F0;">ATELIER PROPERTI MEWAH & INVESTASI TERKURASI</span>
            </div>

            <h1 style="font-size: clamp(2.4rem, 5vw, 3.8rem); font-weight: 800; line-height: 1.15; color: #FFFFFF; margin-bottom: 20px; letter-spacing: -1px;">
                Temukan Hunian Eksklusif & Aset Bernilai Abadi di Indonesia
            </h1>

            <p style="font-size: 1.15rem; color: #CBD5E1; line-height: 1.6; max-width: 720px; margin: 0 auto 40px; font-weight: 400;">
                Menghubungkan pemilik properti bernilai tinggi dengan investor & pembeli eksklusif. Didukung 100% verifikasi legalitas notarial dan kurasi arsitektural ketat.
            </p>
        </div>

        <!-- Hero Multi-Parameter Search Card -->
        <div style="background: rgba(255, 255, 255, 0.96); backdrop-filter: blur(16px); border-radius: 20px; padding: 28px 32px; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35); max-width: 1080px; margin: 0 auto; border: 1px solid rgba(255, 255, 255, 0.3);">
            <form action="{{ route('explore') }}" method="GET">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) 180px; gap: 18px; align-items: flex-end;">
                    
                    <!-- Parameter 1: Transaksi -->
                    <div>
                        <label style="display: block; font-size: 0.76rem; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.4px;">
                            1. Jenis Transaksi
                        </label>
                        <select name="type" style="width: 100%; background: #F8FAFC; border: 1.5px solid #E2E8F0; padding: 13px 16px; border-radius: 10px; font-size: 0.95rem; font-weight: 600; color: #0E2238; cursor: pointer;">
                            <option value="Semua">Semua Transaksi</option>
                            <option value="Jual">Dijual (Buy)</option>
                            <option value="Sewa">Disewakan (Rent)</option>
                        </select>
                    </div>

                    <!-- Parameter 2: Tipe Properti -->
                    <div>
                        <label style="display: block; font-size: 0.76rem; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.4px;">
                            2. Kategori Properti
                        </label>
                        <select name="category" style="width: 100%; background: #F8FAFC; border: 1.5px solid #E2E8F0; padding: 13px 16px; border-radius: 10px; font-size: 0.95rem; font-weight: 600; color: #0E2238; cursor: pointer;">
                            <option value="Semua Tipe Properti">Semua Kategori</option>
                            <option value="Rumah Mewah">Rumah Mewah</option>
                            <option value="Villa">Villa Tropis</option>
                            <option value="Apartemen / Penthouse">Apartemen & Penthouse</option>
                            <option value="Komersial">Ruko & Komersial</option>
                        </select>
                    </div>

                    <!-- Parameter 3: Lokasi -->
                    <div>
                        <label style="display: block; font-size: 0.76rem; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.4px;">
                            3. Wilayah Strategis
                        </label>
                        <select name="location" style="width: 100%; background: #F8FAFC; border: 1.5px solid #E2E8F0; padding: 13px 16px; border-radius: 10px; font-size: 0.95rem; font-weight: 600; color: #0E2238; cursor: pointer;">
                            <option value="Semua Wilayah">Semua Wilayah</option>
                            <option value="Bali">Bali (Uluwatu, Canggu, Nusa Dua)</option>
                            <option value="Jakarta Selatan">Jakarta Selatan (Kemang, Pondok Indah)</option>
                            <option value="BSD City">BSD City Tangerang</option>
                            <option value="Surabaya">Surabaya Barat</option>
                        </select>
                    </div>

                    <!-- Button Submit -->
                    <div>
                        <button type="submit" class="btn-teal" style="width: 100%; height: 50px; font-weight: 700; font-size: 1rem; border-radius: 10px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            <span>Cari Unit</span>
                        </button>
                    </div>

                </div>
            </form>
        </div>

        <!-- Trust Badges Bar -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; margin-top: 50px; max-width: 1080px; margin-left: auto; margin-right: auto; padding-top: 30px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(0, 180, 136, 0.15); display: flex; align-items: center; justify-content: center; color: #00B488;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 1.15rem; color: #FFFFFF; font-family: var(--font-heading);">100% Notaris</strong>
                    <span style="font-size: 0.8rem; color: #94A3B8;">Legalitas BPN Bersih & Aman</span>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(0, 180, 136, 0.15); display: flex; align-items: center; justify-content: center; color: #00B488;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 1.15rem; color: #FFFFFF; font-family: var(--font-heading);">15% Kurasi Lolos</strong>
                    <span style="font-size: 0.8rem; color: #94A3B8;">Hanya Aset Kualitas Terbaik</span>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(0, 180, 136, 0.15); display: flex; align-items: center; justify-content: center; color: #00B488;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 1.15rem; color: #FFFFFF; font-family: var(--font-heading);">Rp 2.4T+</strong>
                    <span style="font-size: 0.8rem; color: #94A3B8;">Nilai Transaksi Terfasilitasi</span>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(0, 180, 136, 0.15); display: flex; align-items: center; justify-content: center; color: #00B488;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 1.15rem; color: #FFFFFF; font-family: var(--font-heading);">&lt; 4 Jam SLA</strong>
                    <span style="font-size: 0.8rem; color: #94A3B8;">Respon & Peninjauan Konsinyasi</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     FEATURED LISTINGS SECTION
     ====================================================================== -->
<section style="padding: 90px 0; background: #F4F6F9;">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; flex-wrap: wrap; gap: 20px;">
            <div>
                <div class="badge-pill-header">KOLEKSI PILIHAN KURATOR</div>
                <h2 style="font-size: 2.2rem; color: var(--primary-navy); margin-bottom: 8px;">Koleksi Properti Unggulan</h2>
                <p style="color: var(--text-muted); font-size: 1rem;">Portofolio unit prestisius terverifikasi dengan legalitas bersih dan potensi apresiasi investasi.</p>
            </div>
            <a href="{{ route('explore') }}" class="btn-outline-navy" style="border-radius: 999px;">
                <span>Lihat Seluruh 48 Listing</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>

        <!-- Property Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 30px;">
            @foreach($featuredProperties as $prop)
            <div class="property-card" id="card-prop-{{ $loop->iteration }}">
                <div class="card-media-wrapper">
                    <img src="{{ $prop['image_main'] }}" alt="{{ $prop['title'] }}" class="card-media-img" loading="lazy">
                    
                    <div class="card-badges-top">
                        <span class="badge-tag {{ $prop['transaction_type'] === 'Jual' ? 'badge-tag-navy' : 'badge-tag-teal' }}">
                            {{ $prop['transaction_type'] === 'Jual' ? 'DIJUAL' : 'DISEWAKAN' }}
                        </span>
                        <span class="badge-tag badge-tag-white">
                            {{ $prop['legal_status'] }}
                        </span>
                    </div>

                    <button type="button" class="card-btn-favorite" data-fav-id="{{ $prop['id'] }}" onclick="toggleFavorite('{{ $prop['id'] }}', '{{ $prop['title'] }}')" title="Simpan ke Favorit">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </button>

                    @if(!empty($prop['highlight_badge']))
                    <div class="card-highlight-pill">
                        {{ $prop['highlight_badge'] }}
                    </div>
                    @endif
                </div>

                <div class="card-content">
                    <div class="card-location">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
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
                            <span class="card-price-label">{{ $prop['price_label'] }}</span>
                            <span class="card-price-val">{{ $prop['price_short'] }}</span>
                        </div>
                        <a href="{{ route('property.detail', $prop['slug']) }}" class="card-btn-detail">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ======================================================================
     CONSIGNMENT CALLOUT BANNER ("TITIP PROPERTI")
     ====================================================================== -->
<section style="padding: 70px 0; background: #0E2238;">
    <div class="container">
        <div style="background: linear-gradient(135deg, #162E4A 0%, #0E2238 100%); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 24px; padding: 50px 60px; display: flex; align-items: center; justify-content: space-between; gap: 40px; flex-wrap: wrap;">
            <div style="max-width: 640px;">
                <div style="display: inline-flex; align-items: center; gap: 6px; color: #00B488; font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span>LAYANAN KONSINYASI & PEMASARAN RESMI</span>
                </div>
                <h2 style="font-size: 2.2rem; color: #FFFFFF; margin-bottom: 14px; line-height: 1.25;">
                    Punya Properti untuk Dijual atau Disewakan?
                </h2>
                <p style="color: #94A3B8; font-size: 1.05rem; line-height: 1.6;">
                    Titipkan properti Anda bersama Kumala Property. Kami bantu proses valuasi presisi, produksi foto arsitektural berkualitas tinggi, dan pemasaran langsung ke calon pembeli terverifikasi tanpa biaya di muka.
                </p>
            </div>

            <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                <a href="{{ route('titip-properti') }}" class="btn-teal" style="font-size: 1rem; padding: 14px 30px;">
                    <span>Titip Properti Sekarang</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
                <a href="https://wa.me/6281288992345?text=Halo%20Kumala%20Property,%20saya%20ingin%20konsultasi%20titip%20properti." target="_blank" class="btn-outline-white" style="font-size: 1rem; padding: 14px 26px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span>Konsultasi via WhatsApp</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
