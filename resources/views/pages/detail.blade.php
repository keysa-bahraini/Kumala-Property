@extends('layouts.app')

@section('title', $property['title'] . ' - Kumala Property')

@section('content')
<div class="container" style="padding-top: 20px; padding-bottom: 80px;">

    <!-- Breadcrumb Bar & Header Actions -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 20px;">
        <div class="breadcrumbs-bar" style="padding: 0;">
            <ul class="breadcrumbs-list">
                <li class="breadcrumbs-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumbs-separator">&gt;</li>
                <li class="breadcrumbs-item"><a href="{{ route('explore') }}">Listing Properti</a></li>
                <li class="breadcrumbs-separator">&gt;</li>
                <li class="breadcrumbs-item active">{{ $property['title'] }}</li>
            </ul>
        </div>

        <div style="display: flex; align-items: center; gap: 10px;">
            <a href="{{ route('explore') }}" class="btn-detail-nav">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                <span>Kembali ke Listing</span>
            </a>
            <button type="button" class="btn-detail-nav" onclick="navigator.clipboard.writeText(window.location.href); showToast('Link properti berhasil disalin ke clipboard!');">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" x2="12" y1="2" y2="15"/></svg>
                <span>Bagikan</span>
            </button>
            <button type="button" class="btn-detail-nav" data-fav-id="{{ $property['id'] }}" onclick="toggleFavorite('{{ $property['id'] }}', '{{ $property['title'] }}')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                <span>Tersimpan (Favorit)</span>
            </button>
        </div>
    </div>

    <!-- Title & Pricing Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; flex-wrap: wrap; gap: 20px;">
        <div style="max-width: 780px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px; flex-wrap: wrap;">
                <span class="badge-tag {{ $property['transaction_type'] === 'Jual' ? 'badge-tag-navy' : 'badge-tag-teal' }}">{{ $property['transaction_type'] === 'Jual' ? 'DIJUAL' : 'DISEWAKAN' }}</span>
                <span class="badge-tag" style="background: #E2E8F0; color: #0E2238;">{{ $property['legal_status'] }}</span>
                <span class="badge-tag badge-tag-teal">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Verifikasi Tim Kumala</span>
                </span>
            </div>
            <h1 style="font-size: clamp(2rem, 3.5vw, 2.75rem); color: var(--primary-navy); margin-bottom: 8px; line-height: 1.25; font-weight: 800;">
                {{ $property['title'] }}
            </h1>
            <p style="display: flex; align-items: center; gap: 6px; color: var(--text-muted); font-size: 0.95rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--accent-teal)" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>{{ $property['location_address'] }}</span>
            </p>
        </div>

        <div style="text-align: right; background: #FFFFFF; padding: 18px 24px; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
            <span style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">{{ $property['price_label'] }}</span>
            <div style="font-family: var(--font-heading); font-size: 2.2rem; font-weight: 800; color: var(--primary-navy); line-height: 1.15; letter-spacing: -0.02em;">
                {{ $property['price_formatted'] }}
            </div>
            <span style="font-size: 0.82rem; color: #059669; font-weight: 600;">{{ $property['price_notes'] }}</span>
        </div>
    </div>

    <!-- Interactive Media Gallery Grid -->
    <div class="gallery-grid" style="margin-bottom: 32px; border-radius: 14px; overflow: hidden; border: 1px solid var(--border-color);">
        <!-- Main Image -->
        <div style="position: relative; overflow: hidden; background: #0E2238;">
            <img src="{{ $property['images'][0] ?? $property['image_main'] }}" alt="{{ $property['title'] }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;" class="gallery-hover-img">
            <div style="position: absolute; bottom: 16px; left: 16px; background: rgba(14, 34, 56, 0.9); padding: 8px 14px; border-radius: 6px; color: #FFFFFF; font-size: 0.82rem; font-weight: 600; border: 1px solid rgba(255,255,255,0.15);">
                {{ $property['location_area'] }} • Dokumentasi Asli Tim Kumala
            </div>
        </div>

        <!-- Sub 1 -->
        <div style="position: relative; overflow: hidden; background: #0E2238;">
            <img src="{{ $property['images'][1] ?? $property['image_main'] }}" alt="Area Utama" style="width: 100%; height: 100%; object-fit: cover;" class="gallery-hover-img">
            <div style="position: absolute; bottom: 10px; left: 10px; background: rgba(14, 34, 56, 0.85); padding: 4px 10px; border-radius: 4px; color: #FFFFFF; font-size: 0.75rem; font-weight: 600;">Area Ruang Tamu</div>
        </div>

        <!-- Sub 2 -->
        <div style="position: relative; overflow: hidden; background: #0E2238;">
            <img src="{{ $property['images'][2] ?? $property['image_main'] }}" alt="Kamar Utama" style="width: 100%; height: 100%; object-fit: cover;" class="gallery-hover-img">
            <div style="position: absolute; bottom: 10px; left: 10px; background: rgba(14, 34, 56, 0.85); padding: 4px 10px; border-radius: 4px; color: #FFFFFF; font-size: 0.75rem; font-weight: 600;">Kamar Utama (Ensuite)</div>
        </div>

        <!-- Sub 3 -->
        <div style="position: relative; overflow: hidden; background: #0E2238;">
            <img src="{{ $property['images'][3] ?? $property['image_main'] }}" alt="Interior & Sanitasi" style="width: 100%; height: 100%; object-fit: cover;" class="gallery-hover-img">
            <div style="position: absolute; bottom: 10px; left: 10px; background: rgba(14, 34, 56, 0.85); padding: 4px 10px; border-radius: 4px; color: #FFFFFF; font-size: 0.75rem; font-weight: 600;">Kamar Mandi / Sanitasi</div>
        </div>

        <!-- Sub 4 / Gallery Action Overlay -->
        <div style="position: relative; overflow: hidden; background: #0E2238; cursor: pointer;" onclick="showToast('Membuka Galeri Foto Lengkap...')">
            <img src="{{ $property['images'][4] ?? $property['image_main'] }}" alt="Eksterior Lingkungan" style="width: 100%; height: 100%; object-fit: cover; filter: brightness(0.65);" class="gallery-hover-img">
            <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(14, 34, 56, 0.45);">
                <div style="background: #FFFFFF; color: var(--primary-navy); padding: 10px 18px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 8px; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                    <span>Lihat Semua Foto ({{ count($property['images'] ?? [1,2,3,4,5]) }})</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Specs Summary Bar -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 40px;">
        <div class="key-spec-box">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--accent-teal)" stroke-width="2"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
            <div>
                <strong style="display: block; font-size: 1.15rem; color: var(--primary-navy);">{{ $property['bedrooms'] }}</strong>
                <span style="font-size: 0.82rem; color: var(--text-muted);">Kamar Tidur (Ensuite)</span>
            </div>
        </div>

        <div class="key-spec-box">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--accent-teal)" stroke-width="2"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-2.12 0 1.5 1.5 0 0 0 0 2.12L7 8.24"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M14 2.5v15"/><path d="M7 19h10a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2Z"/></svg>
            <div>
                <strong style="display: block; font-size: 1.15rem; color: var(--primary-navy);">{{ $property['bathrooms'] }}</strong>
                <span style="font-size: 0.82rem; color: var(--text-muted);">Kamar Mandi Mewah</span>
            </div>
        </div>

        <div class="key-spec-box">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--accent-teal)" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
            <div>
                <strong style="display: block; font-size: 1.15rem; color: var(--primary-navy);">{{ $property['land_size'] }} m²</strong>
                <span style="font-size: 0.82rem; color: var(--text-muted);">Luas Tanah (LT)</span>
            </div>
        </div>

        <div class="key-spec-box">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--accent-teal)" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <div>
                <strong style="display: block; font-size: 1.15rem; color: var(--primary-navy);">{{ $property['building_size'] }} m²</strong>
                <span style="font-size: 0.82rem; color: var(--text-muted);">Luas Bangunan (LB)</span>
            </div>
        </div>

        <div class="key-spec-box">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--accent-teal)" stroke-width="2"><rect width="20" height="12" x="2" y="8" rx="2"/><path d="M6 8v-2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2"/><path d="M4 18v2"/><path d="M20 18v2"/></svg>
            <div>
                <strong style="display: block; font-size: 1.15rem; color: var(--primary-navy);">Furnished</strong>
                <span style="font-size: 0.82rem; color: var(--text-muted);">Bespoke Full Interior</span>
            </div>
        </div>
    </div>

    <!-- Main Two-Column Layout -->
    <div class="detail-main-layout">
        
        <!-- Left Column: Specs, Description, Amenities, Maps -->
        <div>
            <!-- Deskripsi Properti -->
            <div class="detail-section-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h2 style="font-size: 1.5rem; color: var(--primary-navy);">Deskripsi Properti</h2>
                    <span style="background: #EBF1F5; color: var(--primary-navy); padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 700;">ID: {{ $property['id'] }}</span>
                </div>
                
                <div style="color: #475569; font-size: 1rem; line-height: 1.8; white-space: pre-line; margin-bottom: 28px;">
                    {{ $property['description'] }}
                </div>

                <!-- Factsheet Download Center Box -->
                <div style="background: #F8FAFC; border: 1.5px solid var(--border-color); border-radius: 12px; padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 44px; height: 44px; border-radius: 10px; background: #E2E8F0; display: flex; align-items: center; justify-content: center; color: var(--primary-navy);">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        </div>
                        <div>
                            <strong style="display: block; font-size: 0.98rem; color: var(--primary-navy);">E-Brosur & Factsheet Properti</strong>
                            <span style="font-size: 0.82rem; color: var(--text-muted);">PDF resmi (3.4 MB) mencakup denah arsitektur & rekam legalitas</span>
                        </div>
                    </div>
                    <button type="button" class="btn-primary" style="padding: 10px 20px; font-size: 0.88rem; border-radius: 8px;" onclick="showToast('Mengunduh Factsheet PDF...')">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        <span>Unduh Gratis</span>
                    </button>
                </div>
            </div>

            <!-- Spesifikasi Arsitektural & Teknis -->
            <div class="detail-section-card">
                <h2 style="font-size: 1.5rem; color: var(--primary-navy); margin-bottom: 20px;">Spesifikasi Arsitektural & Teknis</h2>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 14px;">
                    <div class="spec-grid-item">
                        <span class="spec-grid-label">Sertifikat / Legalitas</span>
                        <strong class="spec-grid-val">{{ $property['legal_status'] }} & PBG</strong>
                    </div>
                    <div class="spec-grid-item">
                        <span class="spec-grid-label">Luas Tanah (LT)</span>
                        <strong class="spec-grid-val">{{ $property['land_size'] }} m²</strong>
                    </div>
                    <div class="spec-grid-item">
                        <span class="spec-grid-label">Luas Bangunan (LB)</span>
                        <strong class="spec-grid-val">{{ $property['building_size'] }} m² ({{ $property['floors'] }} Lantai)</strong>
                    </div>
                    <div class="spec-grid-item">
                        <span class="spec-grid-label">Arah Hadap</span>
                        <strong class="spec-grid-val">{{ $property['orientation'] }}</strong>
                    </div>
                    <div class="spec-grid-item">
                        <span class="spec-grid-label">Kapasitas Listrik</span>
                        <strong class="spec-grid-val">{{ $property['electricity'] }}</strong>
                    </div>
                    <div class="spec-grid-item">
                        <span class="spec-grid-label">Sumber Air</span>
                        <strong class="spec-grid-val">{{ $property['water_source'] }}</strong>
                    </div>
                    <div class="spec-grid-item">
                        <span class="spec-grid-label">Garasi & Carport</span>
                        <strong class="spec-grid-val">{{ $property['garage'] }}</strong>
                    </div>
                    <div class="spec-grid-item">
                        <span class="spec-grid-label">Tahun Pembangunan</span>
                        <strong class="spec-grid-val">{{ $property['year_built'] }}</strong>
                    </div>
                </div>
            </div>

            <!-- Fasilitas Unggulan Properti -->
            @if(!empty($property['features']))
            <div class="detail-section-card">
                <h2 style="font-size: 1.5rem; color: var(--primary-navy); margin-bottom: 20px;">Fasilitas Unggulan Properti</h2>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px;">
                    @foreach($property['features'] as $feat)
                    <div style="display: flex; gap: 14px; padding: 16px; background: #F8FAFC; border-radius: 12px; border: 1px solid var(--border-light);">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: #E6FBF5; display: flex; align-items: center; justify-content: center; color: var(--accent-teal); flex-shrink: 0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div>
                            <strong style="display: block; font-size: 0.95rem; color: var(--primary-navy); margin-bottom: 4px;">{{ $feat['title'] }}</strong>
                            <p style="font-size: 0.82rem; color: var(--text-muted); line-height: 1.4;">{{ $feat['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Peta & Aksesibilitas Lokasi -->
            <div class="detail-section-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h2 style="font-size: 1.5rem; color: var(--primary-navy);">Peta & Aksesibilitas Lokasi</h2>
                    <span style="color: var(--accent-teal-dark); font-size: 0.82rem; font-weight: 700;">Uluwatu Prime Coastal Area</span>
                </div>

                <!-- Simulated Interactive Map Preview Box -->
                <div style="height: 280px; width: 100%; border-radius: 14px; overflow: hidden; position: relative; background: #0E2238; margin-bottom: 20px;">
                    <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=1200&q=80" alt="Map View" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.85;">
                    <div style="position: absolute; top: 20px; left: 20px; background: #FFFFFF; padding: 10px 16px; border-radius: 10px; box-shadow: var(--shadow-md);">
                        <strong style="display: block; font-size: 0.88rem; color: var(--primary-navy);">Kumala Verified Location</strong>
                        <span style="font-size: 0.75rem; color: #059669; font-weight: 600;">✓ Akses jalan aspal lebar 6 meter</span>
                    </div>
                    <a href="https://maps.google.com" target="_blank" style="position: absolute; bottom: 20px; right: 20px; background: #0E2238; color: #FFFFFF; padding: 8px 16px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        <span>Buka di Google Maps</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                    </a>
                </div>

                <!-- Distance Cards -->
                @if(!empty($property['distance_points']))
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px;">
                    @foreach($property['distance_points'] as $dist)
                    <div style="background: #F8FAFC; border: 1px solid var(--border-light); padding: 14px 18px; border-radius: 10px; display: flex; align-items: center; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: #E6FBF5; display: flex; align-items: center; justify-content: center; color: var(--accent-teal);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div>
                            <strong style="display: block; font-size: 0.92rem; color: var(--primary-navy);">{{ $dist['time'] }}</strong>
                            <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $dist['place'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>

        <!-- Right Column: Sticky Concierge Form -->
        <div style="position: sticky; top: 100px;">
            <div style="background: #FFFFFF; border-radius: 20px; border: 1px solid var(--border-color); padding: 28px; box-shadow: var(--shadow-md);">
                
                <div style="display: inline-flex; align-items: center; gap: 6px; color: var(--accent-teal-dark); font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    <span>LAYANAN PRIVATE CONCIERGE</span>
                </div>

                <h3 style="font-size: 1.35rem; color: var(--primary-navy); margin-bottom: 8px;">{{ $globalSettings['detail_concierge_title'] ?? 'Tertarik dengan Properti Ini?' }}</h3>
                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px;">
                    {{ $globalSettings['detail_concierge_desc'] ?? 'Tim penasihat Kumala Property siap mendampingi survei lokasi privat, uji kelayakan legalitas notarial, dan negosiasi langsung tanpa biaya awal.' }}
                </p>

                <!-- Inquiry Tab Selector -->
                <div style="display: flex; background: #F1F5F9; border-radius: 8px; padding: 4px; margin-bottom: 20px; gap: 4px;">
                    <button type="button" class="btn-inquiry-tab active" id="tab-beli" onclick="setInquiryTab('beli')">Minat Beli</button>
                    <button type="button" class="btn-inquiry-tab" id="tab-sewa" onclick="setInquiryTab('sewa')">Minat Sewa</button>
                </div>

                <!-- Inquiry Form -->
                <form id="inquiry-form" onsubmit="handleInquirySubmit(event)">
                    @csrf
                    <input type="hidden" name="property_id" value="{{ $property['id'] }}">
                    <input type="hidden" name="property_title" value="{{ $property['title'] }}">
                    <input type="hidden" name="property_price" value="{{ $property['price_short'] }}">
                    <input type="hidden" name="property_slug" value="{{ $property['slug'] }}">
                    <input type="hidden" name="buyer_type" id="inquiry-buyer-type" value="Calon Pembeli Eksklusif (HNWIs)">

                    <div style="margin-bottom: 14px;">
                        <label class="form-label-field">Nama Lengkap *</label>
                        <input type="text" name="name" class="form-input-field" placeholder="Contoh: Bpk. Hendra Gunawan" value="{{ old('name') }}" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 14px;">
                        <div>
                            <label class="form-label-field">WhatsApp / Telepon *</label>
                            <input type="tel" name="phone" class="form-input-field" placeholder="+62 812-xxxx-xxxx" value="{{ old('phone') }}" required>
                        </div>
                        <div>
                            <label class="form-label-field">Email Aktif *</label>
                            <input type="email" name="email" class="form-input-field" placeholder="hendra@perusahaan.com" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label class="form-label-field">Rencana Skema Pembelian</label>
                        <select name="payment_scheme" class="form-input-field">
                            <option {{ old('payment_scheme') == 'Cash Keras (Diskon Khusus Nego)' ? 'selected' : '' }}>Cash Keras (Diskon Khusus Nego)</option>
                            <option {{ old('payment_scheme') == 'KPR Bank Rekanan (BCA / Mandiri)' ? 'selected' : '' }}>KPR Bank Rekanan (BCA / Mandiri)</option>
                            <option {{ old('payment_scheme') == 'Termin Bertahap' ? 'selected' : '' }}>Termin Bertahap</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label class="form-label-field">Preferensi Waktu Survei Lokasi</label>
                        <input type="date" name="survey_date" class="form-input-field" value="{{ old('survey_date') }}">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label class="form-label-field">Catatan Tambahan / Pertanyaan</label>
                        <textarea name="notes" rows="3" class="form-input-field" placeholder="Tuliskan jika ingin meminta video tour terkini atau jadwal penjemputan bandara...">{{ old('notes') }}</textarea>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px; font-size: 0.76rem; color: #64748B; margin-bottom: 18px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Bebas biaya administrasi. Tidak ada pembayaran online dalam formulir ini.</span>
                    </div>

                    <button type="submit" class="btn-primary" style="width: 100%; border-radius: 10px; padding: 12px; font-size: 0.95rem; margin-bottom: 12px;" id="btn-submit-inquiry">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        <span>Kirim Pengajuan Minat Beli</span>
                    </button>

                    <div style="text-align: center; margin-bottom: 12px; font-size: 0.75rem; color: #94A3B8; font-weight: 700; letter-spacing: 0.5px;">
                        ATAU HUBUNGI LANGSUNG
                    </div>

                    <a href="https://wa.me/{{ $globalSettings['hotline_wa_numeric'] ?? '6281199887711' }}?text=Halo%20Concierge%20Kumala,%20saya%20tertarik%20dengan%20listing%20{{ urlencode($property['title']) }}%20({{ $property['id'] }})." target="_blank" class="btn-teal" style="width: 100%; border-radius: 10px; padding: 12px; font-size: 0.95rem;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span>Tanya Cepat via WhatsApp</span>
                    </a>
                </form>

                <!-- Jaminan Transaksi Box -->
                <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border-light);">
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 0.82rem; font-weight: 700; color: var(--primary-navy); margin-bottom: 10px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--accent-teal)" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Jaminan Transaksi Kumala</span>
                    </div>
                    <ul style="display: flex; flex-direction: column; gap: 6px; font-size: 0.78rem; color: #64748B;">
                        <li style="display: flex; align-items: flex-start; gap: 6px;">
                            <span style="color: #059669; font-weight: 700;">✓</span>
                            <span>Pengecekan BPN & keaslian sertifikat tanah resmi</span>
                        </li>
                        <li style="display: flex; align-items: flex-start; gap: 6px;">
                            <span style="color: #059669; font-weight: 700;">✓</span>
                            <span>Bebas sengketa & kepemilikan terverifikasi notaris</span>
                        </li>
                        <li style="display: flex; align-items: flex-start; gap: 6px;">
                            <span style="color: #059669; font-weight: 700;">✓</span>
                            <span>Privasi identitas pembeli terjaga secara konfidensial</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

    </div>

    <!-- Rekomendasi Properti Serupa -->
    <div style="margin-top: 80px; padding-top: 40px; border-top: 1px solid var(--border-color);">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px;">
            <div>
                <span style="font-size: 0.78rem; font-weight: 700; color: #64748B; text-transform: uppercase;">KOLEKSI TERKAIT</span>
                <h2 style="font-size: 1.8rem; color: var(--primary-navy);">Rekomendasi Properti Serupa di Bali</h2>
            </div>
            <a href="{{ route('explore', ['location' => 'Bali']) }}" style="font-size: 0.9rem; font-weight: 700; color: var(--primary-navy); display: inline-flex; align-items: center; gap: 6px;">
                <span>Jelajahi Semua Listing Bali</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>

        <div class="property-grid-cards" style="margin-top: 24px;">
            @foreach($relatedProperties as $rel)
            <div class="property-card">
                <div class="card-media-wrapper" style="height: 220px;">
                    <img src="{{ $rel['image_main'] }}" alt="{{ $rel['title'] }}" class="card-media-img" loading="lazy">
                    <div class="card-badges-top">
                        <span class="badge-tag {{ $rel['transaction_type'] === 'Jual' ? 'badge-tag-navy' : 'badge-tag-teal' }}">
                            {{ $rel['transaction_type'] === 'Jual' ? 'DIJUAL' : 'DISEWAKAN' }}
                        </span>
                        <span class="badge-tag badge-tag-white">{{ $rel['legal_status'] }}</span>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-location">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>{{ $rel['location_area'] }}</span>
                    </div>
                    <h3 class="card-title" style="font-size: 1.05rem;">
                        <a href="{{ route('property.detail', $rel['slug']) }}">{{ $rel['title'] }}</a>
                    </h3>
                    <div class="card-specs-row">
                        @foreach($rel['specs_tags'] as $tag)
                        <span class="spec-chip">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <div class="card-price-block">
                            <span class="card-price-label">{{ ($rel['transaction_type'] ?? 'Jual') === 'Jual' ? 'HARGA PENAWARAN' : 'BIAYA SEWA' }}</span>
                            <span class="card-price-val">{{ ($rel['transaction_type'] ?? 'Jual') === 'Sewa' ? str_replace(' / tahun', '/thn', $rel['price_short']) : $rel['price_short'] }}</span>
                        </div>
                        <div class="card-actions-group">
                            <a href="https://wa.me/6281288992345?text=Halo%20Concierge%20Kumala,%20saya%20tertarik%20dengan%20unit%20{{ urlencode($rel['title']) }}%20({{ $rel['id'] }})." target="_blank" class="card-btn-wa" title="Tanya via WhatsApp">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            </a>
                            <a href="{{ route('property.detail', $rel['slug']) }}" class="card-btn-detail">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection

@section('styles')
<style>
.btn-detail-nav {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #FFFFFF;
    border: 1px solid var(--border-color);
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--primary-navy);
    transition: var(--transition-fast);
}
.btn-detail-nav:hover {
    background: #F1F5F9;
}
.btn-detail-nav[data-fav-id].active {
    color: #E11D48;
    border-color: #FECDD3;
    background: #FFF1F2;
}

.gallery-hover-img:hover {
    transform: scale(1.04);
}

.key-spec-box {
    background: #FFFFFF;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: var(--shadow-sm);
}

.detail-section-card {
    background: #FFFFFF;
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 32px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
}

.spec-grid-item {
    background: #F8FAFC;
    border: 1px solid var(--border-light);
    border-radius: 10px;
    padding: 14px 18px;
    display: flex;
    flex-direction: column;
}
.spec-grid-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 4px;
}
.spec-grid-val {
    font-size: 0.95rem;
    color: var(--primary-navy);
}

.btn-inquiry-tab {
    flex: 1;
    padding: 8px;
    border-radius: 6px;
    font-size: 0.82rem;
    font-weight: 600;
    color: #64748B;
    transition: var(--transition-fast);
}
.btn-inquiry-tab.active {
    background: #0E2238;
    color: #FFFFFF;
}

.form-label-field {
    display: block;
    font-size: 0.76rem;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    margin-bottom: 6px;
}
.form-input-field {
    width: 100%;
    background: #F8FAFC;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 0.9rem;
    color: var(--primary-navy);
    font-weight: 500;
}
.form-input-field:focus {
    border-color: var(--accent-teal);
    background: #FFFFFF;
}

@media (max-width: 1024px) {
    .gallery-grid {
        grid-template-columns: 1fr 1fr !important;
        grid-template-rows: 240px 160px !important;
    }
    div[style*="grid-template-columns: 1fr 380px"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection

@section('scripts')
<script>
function setInquiryTab(type) {
    const tabBeli = document.getElementById('tab-beli');
    const tabSewa = document.getElementById('tab-sewa');
    const btnSubmit = document.getElementById('btn-submit-inquiry');
    const buyerTypeInput = document.getElementById('inquiry-buyer-type');

    if (type === 'beli') {
        tabBeli.classList.add('active');
        tabSewa.classList.remove('active');
        if (buyerTypeInput) buyerTypeInput.value = 'Calon Pembeli Eksklusif (HNWIs)';
        btnSubmit.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg><span>Kirim Pengajuan Minat Beli</span>`;
    } else {
        tabSewa.classList.add('active');
        tabBeli.classList.remove('active');
        if (buyerTypeInput) buyerTypeInput.value = 'Calon Penyewa Eksklusif';
        btnSubmit.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg><span>Kirim Pengajuan Minat Sewa</span>`;
    }
}

function handleInquirySubmit(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('btn-submit-inquiry');
    btn.disabled = true;
    btn.innerHTML = '<span>Mengirim permohonan...</span>';

    const formData = new FormData(form);

    fetch('{{ route('inquiry.submit') }}', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) {
            let errorMsg = data.message || 'Terjadi kesalahan pada data formulir.';
            if (data.errors) {
                const firstKey = Object.keys(data.errors)[0];
                errorMsg = data.errors[firstKey][0];
            }
            throw new Error(errorMsg);
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            showToast(`Pengajuan berhasil dikirim dengan ID ${data.lead_id}! Concierge kami akan menghubungi via WhatsApp dalam < 15 menit.`, false);
            form.reset();
            btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg><span>Pengajuan Terkirim (${data.lead_id}) ✓</span>`;
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg><span>Kirim Pengajuan Minat Beli</span>`;
            }, 5000);
        } else {
            showToast(data.message || 'Gagal mengirim pengajuan. Silakan periksa formulir Anda.', true);
            btn.disabled = false;
            btn.innerHTML = '<span>Kirim Ulang Pengajuan</span>';
        }
    })
    .catch(err => {
        console.error(err);
        showToast(err.message || 'Terjadi gangguan jaringan. Silakan hubungi via WhatsApp hotline kami.', true);
        btn.disabled = false;
        btn.innerHTML = '<span>Kirim Ulang Pengajuan</span>';
    });
}
</script>
@endsection
