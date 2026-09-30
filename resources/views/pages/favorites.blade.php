@extends('layouts.app')

@section('title', 'Properti Favorit Saya - Kumala Property')

@section('content')
<div class="container" style="padding-top: 24px; padding-bottom: 90px;">

    <!-- Breadcrumbs -->
    <div class="breadcrumbs-bar" style="margin-bottom: 20px;">
        <ul class="breadcrumbs-list">
            <li class="breadcrumbs-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumbs-separator">&gt;</li>
            <li class="breadcrumbs-item active">Properti Favorit Saya</li>
        </ul>
    </div>

    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px; margin-bottom: 32px;">
        <div>
            <span class="badge-tag badge-tag-navy" style="margin-bottom: 12px;">KOLEKSI PRIBADI TERSIMPAN</span>
            <h1 style="font-size: clamp(2rem, 3.5vw, 2.75rem); color: var(--primary-navy); margin-bottom: 8px; line-height: 1.2; font-weight: 800;">
                Properti Favorit Saya
            </h1>
            <p style="font-size: 1rem; color: var(--text-muted); line-height: 1.5; max-width: 680px;">
                Daftar unit properti pilihan yang Anda simpan di perangkat ini. Anda dapat membandingkan spesifikasi teknis atau mengajukan jadwal survei privat sekaligus.
            </p>
        </div>

        <!-- Concierge Quick Action -->
        <a href="https://wa.me/6281288992345?text=Halo%20Concierge%20Kumala,%20saya%20ingin%20berkonsultasi%20mengenai%20unit%20favorit%20saya." target="_blank" class="btn-teal" style="padding: 12px 20px; font-size: 0.9rem; border-radius: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <span>Konsultasi Semua Favorit via WA</span>
        </a>
    </div>

    <!-- Control Toolbar (Filter Tabs + Bulk Actions) -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; padding: 12px 20px; margin-bottom: 30px; box-shadow: var(--shadow-sm);">
        
        <!-- Filter Tabs -->
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <button type="button" class="btn-filter-fav active" data-filter="all" onclick="filterFavs('all', this)">
                <span>Semua</span>
                <span class="count-pill" id="badge-count-all">0</span>
            </button>
            <button type="button" class="btn-filter-fav" data-filter="jual" onclick="filterFavs('Jual', this)">
                <span>Dijual</span>
                <span class="count-pill" id="badge-count-jual">0</span>
            </button>
            <button type="button" class="btn-filter-fav" data-filter="sewa" onclick="filterFavs('Sewa', this)">
                <span>Disewakan</span>
                <span class="count-pill" id="badge-count-sewa">0</span>
            </button>
        </div>

        <!-- Action Tools -->
        <div style="display: flex; align-items: center; gap: 10px;">
            <button type="button" class="btn-fav-tool" onclick="openComparisonModal()" id="btn-compare-trigger">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="8" height="18" x="3" y="3" rx="1"/><rect width="8" height="18" x="13" y="3" rx="1"/></svg>
                <span>Bandingkan Spek (<span id="compare-count">0</span>)</span>
            </button>
            <button type="button" class="btn-fav-tool btn-fav-danger" onclick="clearAllFavorites()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                <span>Hapus Semua</span>
            </button>
        </div>
    </div>

    <!-- Favorite Cards Grid Container -->
    <div id="favorites-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 28px; margin-bottom: 50px;">
        @foreach($allProperties as $prop)
        <div class="property-card fav-item-card" data-prop-id="{{ $prop['id'] }}" data-prop-trx="{{ $prop['transaction_type'] }}" style="display: none;">
            <div class="card-media-wrapper">
                <img src="{{ $prop['image_main'] }}" alt="{{ $prop['title'] }}" class="card-media-img" loading="lazy">
                
                <div class="card-badges-top">
                    <span class="badge-tag {{ $prop['transaction_type'] === 'Jual' ? 'badge-tag-navy' : 'badge-tag-teal' }}">
                        {{ $prop['transaction_type'] === 'Jual' ? 'DIJUAL' : 'DISEWAKAN' }}
                    </span>
                    <span class="badge-tag badge-tag-white">{{ $prop['legal_status'] }}</span>
                </div>

                <!-- Remove Favorite Quick Button -->
                <button type="button" class="btn-remove-fav" onclick="removeFavoriteItem('{{ $prop['id'] }}', '{{ $prop['title'] }}')" title="Hapus dari Favorit">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#E11D48" stroke="#E11D48" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                </button>
            </div>

            <div class="card-content">
                <div class="card-location">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span>{{ $prop['location_area'] }}</span>
                </div>

                <h3 class="card-title">
                    <a href="{{ route('property.detail', $prop['slug']) }}">{{ $prop['title'] }}</a>
                </h3>

                <div class="card-specs-row">
                    @foreach($prop['specs_tags'] as $tag)
                    <span class="spec-chip">{{ $tag }}</span>
                    @endforeach
                </div>

                <div class="card-footer" style="padding-top: 14px;">
                    <div class="card-price-block">
                        <span class="card-price-label">{{ $prop['price_label'] }}</span>
                        <span class="card-price-val">{{ $prop['price_short'] }}</span>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <a href="https://wa.me/6281288992345?text=Halo%20Concierge%20Kumala,%20saya%20tertarik%20dengan%20unit%20{{ urlencode($prop['title']) }}%20({{ $prop['id'] }})." target="_blank" class="card-btn-wa" title="Tanya via WhatsApp">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </a>
                        <a href="{{ route('property.detail', $prop['slug']) }}" class="card-btn-detail">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Zero-State Empty Container -->
    <div id="favorites-empty-state" style="display: none; text-align: center; padding: 70px 20px; background: #FFFFFF; border-radius: 16px; border: 1.5px dashed var(--border-color); margin-bottom: 50px;">
        <div style="width: 64px; height: 64px; border-radius: 50%; background: #F1F5F9; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; color: #94A3B8;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
        </div>
        <h3 style="font-size: 1.4rem; color: var(--primary-navy); margin-bottom: 8px;">Belum Ada Properti yang Disimpan</h3>
        <p style="font-size: 0.95rem; color: var(--text-muted); max-width: 480px; margin: 0 auto 24px; line-height: 1.5;">
            Jelajahi portofolio properti kurasi kami dan klik ikon hati pada unit yang Anda minati untuk menyimpannya di sini.
        </p>
        <a href="{{ route('explore') }}" class="btn-primary" style="padding: 12px 26px; border-radius: 8px; font-size: 0.92rem;">
            <span>Jelajahi Portofolio Properti</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
    </div>

    <!-- Concierge Consultation CTA Banner -->
    <div style="background: linear-gradient(135deg, #0E2238 0%, #162E4A 100%); border-radius: 16px; padding: 40px; color: #FFFFFF; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 24px; border: 1px solid rgba(255, 255, 255, 0.1);">
        <div style="max-width: 640px;">
            <span style="font-size: 0.75rem; font-weight: 700; color: var(--accent-teal); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">LAYANAN SURVEI MULTI-UNIT</span>
            <h2 style="font-size: 1.8rem; margin-bottom: 10px; color: #FFFFFF;">Ingin Survei Beberapa Unit Favorit Sekaligus?</h2>
            <p style="font-size: 0.92rem; color: #CBD5E1; line-height: 1.6;">
                Tim Private Client Concierge Kumala siap mengoordinasikan rute kunjungan terpadu, penyediaan berkas legalitas resmi, dan penjemputan eksekutif tanpa biaya awal.
            </p>
        </div>

        <a href="https://wa.me/6281288992345?text=Halo%20Concierge%20Kumala,%20saya%20ingin%20menjadwalkan%20survei%20untuk%20unit%20favorit%20saya." target="_blank" class="btn-teal" style="padding: 14px 28px; font-size: 0.95rem; border-radius: 8px; white-space: nowrap;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <span>Jadwalkan Private Tour Terpadu</span>
        </a>
    </div>

</div>

<!-- Comparison Modal -->
<div id="comparison-modal" style="display: none; position: fixed; inset: 0; z-index: 99999; background: rgba(14, 34, 56, 0.75); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #FFFFFF; border-radius: 16px; width: 100%; max-width: 960px; max-height: 90vh; overflow-y: auto; padding: 30px; box-shadow: var(--shadow-lg); position: relative;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid var(--border-color);">
            <div>
                <h3 style="font-size: 1.4rem; color: var(--primary-navy); margin-bottom: 4px;">Perbandingan Spesifikasi Properti Favorit</h3>
                <p style="font-size: 0.82rem; color: var(--text-muted);">Menganalisis parameter teknis, dimensi, dan legalitas unit favorit Anda.</p>
            </div>
            <button type="button" onclick="closeComparisonModal()" style="width: 36px; height: 36px; border-radius: 8px; background: #F1F5F9; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--primary-navy);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem; text-align: left;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 2px solid var(--border-color);">
                        <th style="padding: 12px 16px; color: var(--text-muted); font-size: 0.78rem; text-transform: uppercase;">Parameter</th>
                        <th style="padding: 12px 16px; color: var(--primary-navy); font-weight: 700;">Villa Cliffside Uluwatu</th>
                        <th style="padding: 12px 16px; color: var(--primary-navy); font-weight: 700;">Menteng Heritage Mansion</th>
                        <th style="padding: 12px 16px; color: var(--primary-navy); font-weight: 700;">Modern Sanctuary Canggu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid var(--border-light);">
                        <td style="padding: 12px 16px; color: var(--text-muted); font-weight: 600;">Status Transaksi</td>
                        <td style="padding: 12px 16px;"><span class="badge-tag badge-tag-navy">DIJUAL</span></td>
                        <td style="padding: 12px 16px;"><span class="badge-tag badge-tag-navy">DIJUAL</span></td>
                        <td style="padding: 12px 16px;"><span class="badge-tag badge-tag-teal">DISEWAKAN</span></td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-light);">
                        <td style="padding: 12px 16px; color: var(--text-muted); font-weight: 600;">Harga Penawaran</td>
                        <td style="padding: 12px 16px; font-weight: 700; color: var(--primary-navy);">Rp 42 Miliar</td>
                        <td style="padding: 12px 16px; font-weight: 700; color: var(--primary-navy);">Rp 85 Miliar</td>
                        <td style="padding: 12px 16px; font-weight: 700; color: var(--primary-navy);">Rp 750 Jt / Thn</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-light);">
                        <td style="padding: 12px 16px; color: var(--text-muted); font-weight: 600;">Luas Tanah (LT)</td>
                        <td style="padding: 12px 16px;">850 m²</td>
                        <td style="padding: 12px 16px;">1.200 m²</td>
                        <td style="padding: 12px 16px;">450 m²</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-light);">
                        <td style="padding: 12px 16px; color: var(--text-muted); font-weight: 600;">Luas Bangunan (LB)</td>
                        <td style="padding: 12px 16px;">620 m²</td>
                        <td style="padding: 12px 16px;">950 m²</td>
                        <td style="padding: 12px 16px;">380 m²</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-light);">
                        <td style="padding: 12px 16px; color: var(--text-muted); font-weight: 600;">Kamar Tidur / Mandi</td>
                        <td style="padding: 12px 16px;">5 KT / 6 KM</td>
                        <td style="padding: 12px 16px;">6 KT / 7 KM</td>
                        <td style="padding: 12px 16px;">4 KT / 4 KM</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-light);">
                        <td style="padding: 12px 16px; color: var(--text-muted); font-weight: 600;">Legalitas Dokumen</td>
                        <td style="padding: 12px 16px; color: #059669; font-weight: 600;">SHM + PBG</td>
                        <td style="padding: 12px 16px; color: #059669; font-weight: 600;">SHM Clear</td>
                        <td style="padding: 12px 16px; color: #059669; font-weight: 600;">Leasehold 25 Thn</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 16px; color: var(--text-muted); font-weight: 600;">Aksi Tindak Lanjut</td>
                        <td style="padding: 12px 16px;"><a href="{{ route('property.detail', 'villa-cliffside-horizon-uluwatu') }}" class="btn-primary" style="padding: 8px 14px; font-size: 0.8rem; border-radius: 6px;">Detail Unit</a></td>
                        <td style="padding: 12px 16px;"><a href="{{ route('property.detail', 'menteng-heritage-colonial-mansion') }}" class="btn-primary" style="padding: 8px 14px; font-size: 0.8rem; border-radius: 6px;">Detail Unit</a></td>
                        <td style="padding: 12px 16px;"><a href="{{ route('property.detail', 'tropical-modern-sanctuary-canggu') }}" class="btn-primary" style="padding: 8px 14px; font-size: 0.8rem; border-radius: 6px;">Detail Unit</a></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection

@section('styles')
<style>
.btn-filter-fav {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: #F1F5F9;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--primary-navy);
    cursor: pointer;
    transition: var(--transition-fast);
}
.btn-filter-fav:hover {
    border-color: #94A3B8;
}
.btn-filter-fav.active {
    background: var(--primary-navy);
    color: #FFFFFF;
    border-color: var(--primary-navy);
}
.btn-filter-fav .count-pill {
    background: rgba(0, 0, 0, 0.08);
    color: inherit;
    padding: 2px 7px;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
}
.btn-filter-fav.active .count-pill {
    background: rgba(255, 255, 255, 0.2);
}

.btn-fav-tool {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    background: #FFFFFF;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--primary-navy);
    cursor: pointer;
    transition: var(--transition-fast);
}
.btn-fav-tool:hover {
    background: #F8FAFC;
    border-color: #94A3B8;
}
.btn-fav-danger:hover {
    color: #E11D48;
    border-color: #FECDD3;
    background: #FFF1F2;
}

.btn-remove-fav {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #FFFFFF;
    border: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: var(--shadow-sm);
    transition: var(--transition-fast);
}
.btn-remove-fav:hover {
    transform: scale(1.08);
    background: #FFF1F2;
}

.card-btn-wa {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #E6FBF5;
    color: var(--accent-teal);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(0, 180, 136, 0.3);
    transition: var(--transition-fast);
}
.card-btn-wa:hover {
    background: var(--accent-teal);
    color: #FFFFFF;
}
</style>
@endsection

@section('scripts')
<script>
let currentFavFilter = 'all';

function renderFavoritesView() {
    const favs = getFavorites();
    const cards = document.querySelectorAll('.fav-item-card');
    let visibleCount = 0;
    let jualCount = 0;
    let sewaCount = 0;

    cards.forEach(card => {
        const id = card.getAttribute('data-prop-id');
        const trx = card.getAttribute('data-prop-trx');
        const isFav = favs.includes(id);

        if (isFav) {
            visibleCount++;
            if (trx === 'Jual') jualCount++;
            if (trx === 'Sewa') sewaCount++;

            if (currentFavFilter === 'all' || currentFavFilter === trx) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        } else {
            card.style.display = 'none';
        }
    });

    document.getElementById('badge-count-all').textContent = visibleCount;
    document.getElementById('badge-count-jual').textContent = jualCount;
    document.getElementById('badge-count-sewa').textContent = sewaCount;
    document.getElementById('compare-count').textContent = visibleCount;

    const emptyState = document.getElementById('favorites-empty-state');
    const grid = document.getElementById('favorites-grid');

    if (visibleCount === 0) {
        emptyState.style.display = 'block';
        grid.style.display = 'none';
    } else {
        emptyState.style.display = 'none';
        grid.style.display = 'grid';
    }
}

function filterFavs(type, btn) {
    currentFavFilter = type;
    document.querySelectorAll('.btn-filter-fav').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    renderFavoritesView();
}

function removeFavoriteItem(id, title) {
    toggleFavorite(id, title);
    renderFavoritesView();
}

function clearAllFavorites() {
    if (confirm('Apakah Anda yakin ingin menghapus seluruh properti dari daftar favorit?')) {
        saveFavorites([]);
        renderFavoritesView();
        showToast('Seluruh daftar favorit telah dibersihkan.');
    }
}

function openComparisonModal() {
    const modal = document.getElementById('comparison-modal');
    modal.style.display = 'flex';
}

function closeComparisonModal() {
    const modal = document.getElementById('comparison-modal');
    modal.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    // If user's first visit has empty favorites, seed 3 initial ones for immediate preview
    let favs = getFavorites();
    if (!favs || favs.length === 0) {
        favs = ['#KP-8821', '#KP-8822', '#KP-8823'];
        saveFavorites(favs);
    }
    renderFavoritesView();
});
</script>
@endsection
