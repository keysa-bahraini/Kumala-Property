@extends('layouts.app')

@section('title', 'Eksplorasi Properti Eksklusif - Kumala Property')

@section('content')
<div class="container" style="padding-top: 36px; padding-bottom: 72px;">
    
    <!-- Top Header Area -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
        <div>
            <div class="badge-pill-header">KOLEKSI PILIHAN KUMALA</div>
            <h1 style="font-size: 2.2rem; color: var(--primary-navy); margin-bottom: 6px;">Eksplorasi Properti</h1>
            <p style="color: var(--text-muted); font-size: 0.95rem;">Temukan hunian eksklusif, villa tropis, apartemen modern, dan lahan strategis di kawasan prima.</p>
        </div>

        <!-- Status Katalog Widget -->
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 10px 18px; display: flex; align-items: center; gap: 12px; box-shadow: var(--shadow-card);">
            <div style="width: 36px; height: 36px; border-radius: var(--radius-sm); background: var(--bg-coastal); display: flex; align-items: center; justify-content: center; color: var(--accent-teal);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/></svg>
            </div>
            <div>
                <span style="display: block; font-size: 0.68rem; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.05em;">STATUS KATALOG</span>
                <strong style="display: block; font-size: 0.95rem; color: var(--primary-navy);">Menampilkan {{ count($properties) }} Properti</strong>
            </div>
        </div>
    </div>

    <!-- Multi-Parameter Search Box -->
    <div style="background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: 22px; box-shadow: var(--shadow-card); margin-bottom: 22px;">
        <form action="{{ route('explore') }}" method="GET" id="filter-form">
            <div style="display: grid; grid-template-columns: 1.2fr 1fr 1fr 1fr; gap: 14px; margin-bottom: 18px;">
                
                <!-- Parameter 1: Jenis Transaksi -->
                <div>
                    <label class="form-label-field">1. JENIS TRANSAKSI</label>
                    <div style="display: flex; background: var(--bg-muted); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 3px; gap: 4px;">
                        <input type="hidden" name="type" id="input-type" value="{{ $type }}">
                        <button type="button" class="btn-filter-toggle {{ $type === 'Semua' ? 'active' : '' }}" onclick="setTypeFilter('Semua')">Semua</button>
                        <button type="button" class="btn-filter-toggle {{ $type === 'Jual' ? 'active' : '' }}" onclick="setTypeFilter('Jual')">Jual</button>
                        <button type="button" class="btn-filter-toggle {{ $type === 'Sewa' ? 'active' : '' }}" onclick="setTypeFilter('Sewa')">Sewa</button>
                    </div>
                </div>

                <!-- Parameter 2: Kategori Properti -->
                <div>
                    <label class="form-label-field">2. KATEGORI PROPERTI</label>
                    <select name="category" class="filter-select" onchange="document.getElementById('filter-form').submit()">
                        <option value="Semua Tipe Properti" {{ $category === 'Semua Tipe Properti' ? 'selected' : '' }}>Semua Tipe Properti</option>
                        <option value="Rumah Mewah" {{ $category === 'Rumah Mewah' ? 'selected' : '' }}>Rumah Mewah</option>
                        <option value="Apartemen / Penthouse" {{ $category === 'Apartemen / Penthouse' ? 'selected' : '' }}>Apartemen / Penthouse</option>
                        <option value="Villa" {{ $category === 'Villa' ? 'selected' : '' }}>Villa Tropis</option>
                        <option value="Tanah Komersial" {{ $category === 'Tanah Komersial' ? 'selected' : '' }}>Tanah Komersial</option>
                        <option value="Ruko / Shophouse" {{ $category === 'Ruko / Shophouse' ? 'selected' : '' }}>Ruko / Shophouse</option>
                    </select>
                </div>

                <!-- Parameter 3: Lokasi Properti -->
                <div>
                    <label class="form-label-field">3. LOKASI PROPERTI</label>
                    <select name="location" class="filter-select" onchange="document.getElementById('filter-form').submit()">
                        <option value="Semua Wilayah" {{ $location === 'Semua Wilayah' ? 'selected' : '' }}>Semua Wilayah</option>
                        <option value="Bali" {{ $location === 'Bali' ? 'selected' : '' }}>Bali - Badung / Canggu / Uluwatu</option>
                        <option value="Jakarta Selatan" {{ $location === 'Jakarta Selatan' ? 'selected' : '' }}>Jakarta Selatan (Kemang, Pondok Indah)</option>
                        <option value="BSD City" {{ $location === 'BSD City' ? 'selected' : '' }}>BSD City, Tangerang Selatan</option>
                        <option value="Surabaya" {{ $location === 'Surabaya' ? 'selected' : '' }}>Surabaya Barat</option>
                    </select>
                </div>

                <!-- Parameter 4: Rentang Harga -->
                <div>
                    <label class="form-label-field">4. RENTANG HARGA</label>
                    <select name="price_range" class="filter-select" onchange="document.getElementById('filter-form').submit()">
                        <option value="Semua Rentang Harga" {{ $priceRange === 'Semua Rentang Harga' ? 'selected' : '' }}>Semua Rentang Harga</option>
                        <option value="< 5 Miliar" {{ $priceRange === '< 5 Miliar' ? 'selected' : '' }}>Di bawah Rp 5 Miliar</option>
                        <option value="5 - 15 Miliar" {{ $priceRange === '5 - 15 Miliar' ? 'selected' : '' }}>Rp 5 Miliar - 15 Miliar</option>
                        <option value="15 - 30 Miliar" {{ $priceRange === '15 - 30 Miliar' ? 'selected' : '' }}>Rp 15 Miliar - 30 Miliar</option>
                        <option value="> 30 Miliar" {{ $priceRange === '> 30 Miliar' ? 'selected' : '' }}>Di atas Rp 30 Miliar</option>
                    </select>
                </div>

            </div>

            <!-- Filter Controls Sub-bar -->
            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 14px; border-top: 1px solid var(--border-light); flex-wrap: wrap; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 6px; color: var(--text-light); font-size: 0.82rem;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" x2="20" y1="21" y2="21"/><line x1="4" x2="20" y1="3" y2="3"/><line x1="4" x2="20" y1="12" y2="12"/><circle cx="8" cy="12" r="2"/><circle cx="16" cy="3" r="2"/><circle cx="12" cy="21" r="2"/></svg>
                    <span>Konfigurasi 4 parameter pencarian eksklusif</span>
                </div>

                <div style="display: flex; align-items: center; gap: 10px;">
                    <a href="{{ route('explore') }}" style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted); display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                        <span>Reset Filter</span>
                    </a>
                    <button type="submit" class="btn-teal" style="padding: 8px 18px; font-size: 0.88rem; border-radius: var(--radius-sm);">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <span>Terapkan Filter</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Active Chips & Sorting Toolbar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; flex-wrap: wrap; gap: 14px;">
        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <span style="font-size: 0.75rem; font-weight: 700; color: var(--text-light); text-transform: uppercase;">FILTER AKTIF:</span>
            
            <span class="active-chip">
                {{ $category !== 'Semua Tipe Properti' ? $category : 'Semua Properti' }}
                <a href="{{ route('explore') }}" style="color: var(--text-muted); font-weight: 700;">✕</a>
            </span>

            @if($location !== 'Semua Wilayah')
            <span class="active-chip">
                {{ $location }}
                <a href="{{ route('explore') }}" style="color: var(--text-muted); font-weight: 700;">✕</a>
            </span>
            @else
            <span class="active-chip">
                Wilayah Strategis
                <a href="{{ route('explore') }}" style="color: var(--text-muted); font-weight: 700;">✕</a>
            </span>
            @endif

            <a href="{{ route('explore') }}" style="font-size: 0.8rem; color: var(--text-muted); text-decoration: underline; margin-left: 4px;">Hapus Semua</a>
        </div>

        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 6px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2"><path d="m3 16 4 4 4-4"/><path d="M7 20V4"/><path d="m21 8-4-4-4 4"/><path d="M17 4v16"/></svg>
                <select style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 6px 12px; font-size: 0.85rem; font-weight: 600; color: var(--primary-navy); cursor: pointer;">
                    <option>Urutkan: Rekomendasi Terbaru</option>
                    <option>Harga Terendah</option>
                    <option>Harga Tertinggi</option>
                    <option>Luas Bangunan</option>
                </select>
            </div>

            <!-- View Switcher -->
            <div style="display: flex; background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-sm); overflow: hidden;">
                <button type="button" style="padding: 6px 10px; background: #E2E8F0; color: var(--primary-navy);" title="Grid View">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                </button>
                <button type="button" style="padding: 6px 10px; color: var(--text-light);" title="List View">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Properties Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 24px; margin-bottom: 48px;">
        @forelse($properties as $prop)
        <div class="property-card" id="explore-card-{{ $loop->iteration }}">
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
        @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 50px 20px; background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px dashed var(--border-color);">
            <div style="width: 48px; height: 48px; border-radius: var(--radius-sm); background: var(--bg-muted); display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; color: var(--text-light);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </div>
            <h3 style="font-size: 1.15rem; color: var(--primary-navy); margin-bottom: 6px;">Tidak Ada Properti yang Sesuai</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 16px;">Ubah kombinasi filter pencarian Anda atau reset filter.</p>
            <a href="{{ route('explore') }}" class="btn-primary" style="padding: 8px 18px; font-size: 0.88rem;">Reset Filter Pencarian</a>
        </div>
        @endforelse
    </div>

    <!-- Pagination Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 50px; flex-wrap: wrap; gap: 14px;">
        <span style="font-size: 0.85rem; color: var(--text-muted);">Halaman 1 dari 8 (Total 48 properti terverifikasi)</span>
        
        <div style="display: flex; align-items: center; gap: 6px;">
            <button type="button" class="btn-pagination-nav" disabled>&lt; Sebelumnya</button>
            <button type="button" class="btn-pagination-num active">1</button>
            <button type="button" class="btn-pagination-num">2</button>
            <button type="button" class="btn-pagination-num">3</button>
            <span style="padding: 0 4px; color: var(--text-light);">...</span>
            <button type="button" class="btn-pagination-num">8</button>
            <button type="button" class="btn-pagination-nav">Selanjutnya &gt;</button>
        </div>
    </div>

    <!-- Consultation Banner Card (Honest, clean layout) -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 32px 36px; display: flex; align-items: center; justify-content: space-between; gap: 24px; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: var(--radius-sm); background: var(--bg-coastal); display: flex; align-items: center; justify-content: center; color: var(--accent-teal); flex-shrink: 0;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5Zm0 0a9 9 0 1 1 18 0m0 0v5a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3Z"/></svg>
            </div>
            <div>
                <h3 style="font-size: 1.2rem; color: var(--primary-navy); margin-bottom: 4px;">Tidak Menemukan Kriteria yang Dicari?</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.5; max-width: 580px;">
                    Konsultasikan preferensi properti impian Anda secara privat bersama Property Advisor kami. Kami siap mengurasi unit off-market sesuai kriteria Anda.
                </p>
            </div>
        </div>

        <a href="https://wa.me/6281288992345?text=Halo%20Property%20Advisor%20Kumala,%20saya%20mencari%20properti%20dengan%20kriteria%20khusus." target="_blank" class="btn-cta-whatsapp" style="padding: 11px 22px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <span>Konsultasi via WhatsApp</span>
        </a>
    </div>

</div>
@endsection

@section('styles')
<style>
.btn-filter-toggle {
    flex: 1;
    padding: 7px 10px;
    border-radius: var(--radius-xs);
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--text-muted);
    transition: var(--transition-fast);
}
.btn-filter-toggle:hover {
    color: var(--primary-navy);
}
.btn-filter-toggle.active {
    background-color: var(--primary-navy);
    color: #FFFFFF;
}

.filter-select {
    width: 100%;
    background-color: var(--bg-muted);
    border: 1px solid var(--border-color);
    padding: 9px 12px;
    border-radius: var(--radius-sm);
    font-size: 0.88rem;
    font-weight: 500;
    color: var(--primary-navy);
    cursor: pointer;
}
.filter-select:focus {
    border-color: var(--accent-teal);
    background-color: #FFFFFF;
}

.active-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background-color: #E2E8F0;
    color: var(--primary-navy);
    font-size: 0.8rem;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: var(--radius-xs);
}

.btn-pagination-num {
    width: 36px;
    height: 36px;
    border-radius: var(--radius-sm);
    background-color: var(--bg-surface);
    border: 1px solid var(--border-color);
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--primary-navy);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition-fast);
}
.btn-pagination-num:hover {
    background-color: var(--bg-muted);
}
.btn-pagination-num.active {
    background-color: var(--primary-navy);
    color: #FFFFFF;
    border-color: var(--primary-navy);
}

.btn-pagination-nav {
    padding: 7px 14px;
    border-radius: var(--radius-sm);
    background-color: var(--bg-surface);
    border: 1px solid var(--border-color);
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--primary-navy);
    transition: var(--transition-fast);
}
.btn-pagination-nav:hover:not(:disabled) {
    background-color: var(--bg-muted);
}
.btn-pagination-nav:disabled {
    opacity: 0.45;
    cursor: not-allowed;
}

@media (max-width: 900px) {
    #filter-form > div {
        grid-template-columns: 1fr 1fr;
    }
}
@media (max-width: 600px) {
    #filter-form > div {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('scripts')
<script>
function setTypeFilter(val) {
    document.getElementById('input-type').value = val;
    document.getElementById('filter-form').submit();
}
</script>
@endsection
