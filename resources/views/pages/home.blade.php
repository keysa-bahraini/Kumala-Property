@extends('layouts.app')

@section('title', 'Kumala Property - Jual & Sewa Properti Eksklusif')

@section('content')
<!-- ======================================================================
     HERO SECTION (Clean Architectural Luxury - No AI Glow Orbs)
     ====================================================================== -->
<section style="background-color: var(--primary-navy); color: #FFFFFF; padding: 72px 0 90px; border-bottom: 1px solid var(--navy-border);">
    <div class="container">
        <div style="max-width: 820px; margin: 0 auto; text-align: center;">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); padding: 6px 16px; border-radius: var(--radius-sm); margin-bottom: 20px;">
                <span style="width: 7px; height: 7px; border-radius: 50%; background: var(--accent-teal);"></span>
                <span style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; color: #E2E8F0;">ATELIER PROPERTI MEWAH & INVESTASI TERKURASI</span>
            </div>

            <h1 style="font-size: clamp(2.2rem, 4.5vw, 3.4rem); font-weight: 800; line-height: 1.18; color: #FFFFFF; margin-bottom: 16px;">
                Temukan Hunian Eksklusif & Aset Bernilai Abadi di Indonesia
            </h1>

            <p style="font-size: 1.05rem; color: #CBD5E1; line-height: 1.6; max-width: 680px; margin: 0 auto 36px;">
                Menghubungkan pemilik properti bernilai tinggi dengan investor & pembeli eksklusif. Setiap unit diaudit dengan verifikasi dokumen legalitas notarial dan kurasi arsitektural menyeluruh.
            </p>
        </div>

        <!-- Quick Multi-Parameter Search Card -->
        <div style="background-color: #FFFFFF; border-radius: var(--radius-lg); padding: 24px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25); max-width: 1040px; margin: 0 auto; border: 1px solid #CBD5E1;">
            <form action="{{ route('explore') }}" method="GET">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) 180px; gap: 16px; align-items: flex-end;">
                    
                    <!-- Parameter 1: Transaksi -->
                    <div>
                        <label class="form-label-field">1. Jenis Transaksi</label>
                        <select name="type" class="form-input-field" style="height: 44px; cursor: pointer;">
                            <option value="Semua">Semua Transaksi</option>
                            <option value="Jual">Dijual (Buy)</option>
                            <option value="Sewa">Disewakan (Rent)</option>
                        </select>
                    </div>

                    <!-- Parameter 2: Tipe Properti -->
                    <div>
                        <label class="form-label-field">2. Kategori Properti</label>
                        <select name="category" class="form-input-field" style="height: 44px; cursor: pointer;">
                            <option value="Semua Tipe Properti">Semua Kategori</option>
                            @if(!empty($activeCategories) && count($activeCategories) > 0)
                                @foreach($activeCategories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            @else
                                <option value="Rumah Mewah">Rumah Mewah</option>
                                <option value="Villa">Villa Tropis</option>
                                <option value="Apartemen / Penthouse">Apartemen & Penthouse</option>
                                <option value="Komersial">Ruko & Komersial</option>
                            @endif
                        </select>
                    </div>

                    <!-- Parameter 3: Lokasi -->
                    <div>
                        <label class="form-label-field">3. Wilayah Strategis</label>
                        <select name="location" class="form-input-field" style="height: 44px; cursor: pointer;">
                            <option value="Semua Wilayah">Semua Wilayah</option>
                            @if(!empty($activeRegions) && count($activeRegions) > 0)
                                @foreach($activeRegions as $reg)
                                    <option value="{{ $reg->city ?: $reg->name }}">{{ $reg->name }}</option>
                                @endforeach
                            @else
                                <option value="Bali">Bali (Uluwatu, Canggu, Nusa Dua)</option>
                                <option value="Jakarta Selatan">Jakarta Selatan (Kemang, Pondok Indah)</option>
                                <option value="BSD City">BSD City Tangerang</option>
                                <option value="Surabaya">Surabaya Barat</option>
                            @endif
                        </select>
                    </div>

                    <!-- Button Submit -->
                    <div>
                        <button type="submit" class="btn-teal" style="width: 100%; height: 44px; border-radius: var(--radius-sm); font-size: 0.95rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            <span>Cari Unit</span>
                        </button>
                    </div>

                </div>
            </form>
        </div>

        <!-- Trust Badges Bar (Honest Grounded Data from PRD) -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 44px; max-width: 1040px; margin-left: auto; margin-right: auto; padding-top: 28px; border-top: 1px solid rgba(255, 255, 255, 0.12);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: rgba(0, 180, 136, 0.15); display: flex; align-items: center; justify-content: center; color: var(--accent-teal);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 1.05rem; color: #FFFFFF; font-family: var(--font-heading);">100% Notaris</strong>
                    <span style="font-size: 0.78rem; color: #94A3B8;">Legalitas BPN Bersih & Aman</span>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: rgba(0, 180, 136, 0.15); display: flex; align-items: center; justify-content: center; color: var(--accent-teal);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 1.05rem; color: #FFFFFF; font-family: var(--font-heading);">15% Kurasi Lolos</strong>
                    <span style="font-size: 0.78rem; color: #94A3B8;">Standar Seleksi Material & Tata Ruang</span>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: rgba(0, 180, 136, 0.15); display: flex; align-items: center; justify-content: center; color: var(--accent-teal);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 1.05rem; color: #FFFFFF; font-family: var(--font-heading);">Rp 2.4T+</strong>
                    <span style="font-size: 0.78rem; color: #94A3B8;">Nilai Aset Terfasilitasi</span>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: rgba(0, 180, 136, 0.15); display: flex; align-items: center; justify-content: center; color: var(--accent-teal);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 1.05rem; color: #FFFFFF; font-family: var(--font-heading);">&lt; 4 Jam SLA</strong>
                    <span style="font-size: 0.78rem; color: #94A3B8;">Respon Verifikasi Konsinyasi</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     FEATURED LISTINGS SECTION
     ====================================================================== -->
<section style="padding: 72px 0; background-color: var(--bg-coastal);">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 36px; flex-wrap: wrap; gap: 16px;">
            <div>
                <div class="badge-pill-header">KOLEKSI PILIHAN KURATOR</div>
                <h2 style="font-size: 2rem; color: var(--primary-navy); margin-bottom: 6px;">Koleksi Properti Unggulan</h2>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Portofolio unit siap huni dengan legalitas bersih dan potensi apresiasi investasi.</p>
            </div>
            <a href="{{ route('explore') }}" class="btn-outline-navy">
                <span>Lihat Seluruh 48 Listing</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>

        <!-- Property Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 24px;">
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
     CONSIGNMENT CALLOUT BANNER (Clean Navy Container)
     ====================================================================== -->
<section style="padding: 60px 0; background-color: var(--primary-navy); border-top: 1px solid var(--navy-border);">
    <div class="container">
        <div style="background-color: var(--navy-surface); border: 1px solid var(--navy-border); border-radius: var(--radius-lg); padding: 40px 48px; display: flex; align-items: center; justify-content: space-between; gap: 32px; flex-wrap: wrap;">
            <div style="max-width: 600px;">
                <div style="display: inline-flex; align-items: center; gap: 6px; color: var(--accent-teal); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 10px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span>LAYANAN KONSINYASI & PEMASARAN RESMI</span>
                </div>
                <h2 style="font-size: 1.9rem; color: #FFFFFF; margin-bottom: 10px; line-height: 1.25;">
                    Punya Properti untuk Dijual atau Disewakan?
                </h2>
                <p style="color: #94A3B8; font-size: 0.95rem; line-height: 1.6;">
                    Titipkan properti Anda bersama Kumala Property. Kami mendampingi proses valuasi presisi, dokumentasi visual arsitektural, dan pemasaran langsung ke calon pembeli terverifikasi tanpa biaya di muka.
                </p>
            </div>

            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('titip-properti') }}" class="btn-teal" style="padding: 12px 24px;">
                    <span>Titip Properti Sekarang</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
                <a href="https://wa.me/6281288992345?text=Halo%20Kumala%20Property,%20saya%20ingin%20konsultasi%20titip%20properti." target="_blank" class="btn-outline-white" style="padding: 12px 20px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span>Konsultasi via WhatsApp</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
