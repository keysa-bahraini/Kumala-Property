@extends('layouts.admin')

@section('title', 'Manajemen Listing Masuk (Gatekeeper) - Kumala Admin')

@section('content')
<div>

    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                <span class="badge-tag badge-tag-teal" style="font-size: 0.72rem;">SLA GATEKEEPER VERIFICATION</span>
                <span style="font-size: 0.78rem; color: var(--text-muted);">Sistem Kurasi Konsinyasi & Audit Legalitas BPN</span>
            </div>
            <h1 style="font-size: 1.65rem; color: var(--primary-navy); margin-bottom: 4px; font-weight: 800;">
                Manajemen Listing Masuk
            </h1>
            <p style="font-size: 0.88rem; color: var(--text-muted);">
                Verifikasi keabsahan dokumen, kualitas fotografi, dan penaksiran valuasi objektif dengan target SLA &lt; 4 jam.
            </p>
        </div>

        <!-- SLA Status Pill -->
        <div style="background: #E6FBF5; border: 1px solid rgba(0, 180, 136, 0.3); border-radius: 8px; padding: 10px 16px; display: flex; align-items: center; gap: 10px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <div>
                <span style="display: block; font-size: 0.72rem; color: #065F46; font-weight: 700; text-transform: uppercase;">Rata-rata Respon Verifikasi</span>
                <strong style="color: #059669; font-size: 0.95rem;">2 Jam 15 Menit (SLA Hijau Aman)</strong>
            </div>
        </div>
    </div>

    <!-- Queue Counters Bar (Tabs) -->
    <div style="display: flex; gap: 10px; margin-bottom: 24px; flex-wrap: wrap;">
        <button type="button" class="queue-tab-btn" onclick="filterQueue('all', this)">
            <span>Semua Pengajuan</span>
            <span class="queue-count">{{ $queueCounts['all'] }}</span>
        </button>
        <button type="button" class="queue-tab-btn active" onclick="filterQueue('pending', this)">
            <span>Menunggu Review</span>
            <span class="queue-count" style="background: #D97706; color: #FFFFFF;">{{ $queueCounts['pending'] }}</span>
        </button>
        <button type="button" class="queue-tab-btn" onclick="filterQueue('revision', this)">
            <span>Perlu Revisi Pemilik</span>
            <span class="queue-count">{{ $queueCounts['revision'] }}</span>
        </button>
        <button type="button" class="queue-tab-btn" onclick="filterQueue('approved', this)">
            <span>Disetujui & Diterbitkan</span>
            <span class="queue-count" style="background: #059669; color: #FFFFFF;">{{ $queueCounts['approved'] }}</span>
        </button>
        <button type="button" class="queue-tab-btn" onclick="filterQueue('rejected', this)">
            <span>Ditolak</span>
            <span class="queue-count">{{ $queueCounts['rejected'] }}</span>
        </button>
    </div>

    <!-- Split-Screen Gatekeeper Workspace (2 Columns) -->
    <div style="display: grid; grid-template-columns: 360px 1fr; gap: 24px; align-items: flex-start;">
        
        <!-- Left Column: Queue List -->
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <span style="font-size: 0.75rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Antrean Berkas Masuk (3)</span>

            @foreach($submissions as $sub)
            <div class="submission-queue-card {{ $loop->first ? 'active' : '' }}" onclick="selectSubmission(this, '{{ $sub['id'] }}')">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px;">
                    <span class="badge-tag badge-tag-navy" style="font-size: 0.7rem; padding: 2px 6px;">{{ $sub['id'] }}</span>
                    <span style="font-size: 0.72rem; color: #D97706; font-weight: 700; background: #FEF3C7; padding: 2px 6px; border-radius: 4px;">{{ $sub['priority'] }}</span>
                </div>

                <strong style="display: block; font-size: 0.95rem; color: var(--primary-navy); margin-bottom: 4px; line-height: 1.3;">
                    {{ $sub['title'] }}
                </strong>

                <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 8px;">
                    <span>Oleh: <strong>{{ $sub['owner'] }}</strong></span> • <span>{{ $sub['location'] }}</span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 8px; border-top: 1px solid var(--border-light); font-size: 0.78rem;">
                    <strong style="color: var(--primary-navy);">{{ $sub['price'] }}</strong>
                    <span style="color: #94A3B8;">{{ $sub['time_ago'] }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Right Column: Interactive Inspection Deck -->
        <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 14px; padding: 30px; box-shadow: var(--shadow-sm);">
            
            <!-- Deck Header -->
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; padding-bottom: 18px; border-bottom: 1px solid var(--border-color); flex-wrap: wrap; gap: 14px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                        <span class="badge-tag badge-tag-navy" id="deck-id">{{ $selectedSubmission['id'] }}</span>
                        <span class="badge-tag" style="background: #FEF3C7; color: #D97706;">{{ $selectedSubmission['priority'] }}</span>
                        <span class="badge-tag badge-tag-teal">Menunggu Review Kurasi</span>
                    </div>
                    <h2 style="font-size: 1.5rem; color: var(--primary-navy); margin-bottom: 4px;" id="deck-title">
                        {{ $selectedSubmission['title'] }}
                    </h2>
                    <span style="font-size: 0.85rem; color: var(--text-muted);" id="deck-address">{{ $selectedSubmission['address'] }}</span>
                </div>

                <div style="text-align: right;">
                    <span style="font-size: 0.72rem; color: #64748B; text-transform: uppercase; font-weight: 700; display: block;">Harga Pengajuan Pemilik</span>
                    <div style="font-family: var(--font-heading); font-size: 1.6rem; font-weight: 800; color: var(--primary-navy);" id="deck-price">
                        {{ $selectedSubmission['price'] }}
                    </div>
                    <span style="font-size: 0.78rem; color: #059669; font-weight: 600;">Est. Valuasi Kumala: {{ $selectedSubmission['valuation_range'] }}</span>
                </div>
            </div>

            <!-- Section 1: Data Pemilik & Status Hukum -->
            <div style="margin-bottom: 24px;">
                <h3 style="font-size: 0.95rem; color: var(--primary-navy); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; font-weight: 700;">
                    1. Data Pemilik & Kepemilikan Sah
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; background: #F8FAFC; border: 1px solid var(--border-light); border-radius: 10px; padding: 16px;">
                    <div>
                        <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">Nama Lengkap</span>
                        <strong style="color: var(--primary-navy); font-size: 0.9rem;" id="deck-owner">{{ $selectedSubmission['owner'] }}</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">WhatsApp Aktif</span>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <strong style="color: var(--primary-navy); font-size: 0.9rem;" id="deck-phone">{{ $selectedSubmission['phone'] }}</strong>
                            <a href="https://wa.me/6281288992345" target="_blank" style="padding: 2px 6px; background: #E6FBF5; color: var(--accent-teal); border-radius: 4px; font-size: 0.72rem; font-weight: 700;">Chat WA</a>
                        </div>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">Email Terdaftar</span>
                        <strong style="color: var(--primary-navy); font-size: 0.9rem;" id="deck-email">{{ $selectedSubmission['email'] }}</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">Status Kuasa</span>
                        <strong style="color: #059669; font-size: 0.9rem;">{{ $selectedSubmission['ownership'] }}</strong>
                    </div>
                </div>
            </div>

            <!-- Section 2: Spesifikasi Teknis & Dimensi -->
            <div style="margin-bottom: 24px;">
                <h3 style="font-size: 0.95rem; color: var(--primary-navy); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; font-weight: 700;">
                    2. Parameter Dimensi & Spesifikasi Aset
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 10px;">
                    <div class="spec-grid-item" style="padding: 10px 14px;">
                        <span class="spec-grid-label">Luas Tanah (LT)</span>
                        <strong class="spec-grid-val">{{ $selectedSubmission['land_size'] }} m²</strong>
                    </div>
                    <div class="spec-grid-item" style="padding: 10px 14px;">
                        <span class="spec-grid-label">Luas Bangunan (LB)</span>
                        <strong class="spec-grid-val">{{ $selectedSubmission['building_size'] }} m²</strong>
                    </div>
                    <div class="spec-grid-item" style="padding: 10px 14px;">
                        <span class="spec-grid-label">Kamar Tidur</span>
                        <strong class="spec-grid-val">{{ $selectedSubmission['bedrooms'] }}</strong>
                    </div>
                    <div class="spec-grid-item" style="padding: 10px 14px;">
                        <span class="spec-grid-label">Kamar Mandi</span>
                        <strong class="spec-grid-val">{{ $selectedSubmission['bathrooms'] }}</strong>
                    </div>
                    <div class="spec-grid-item" style="padding: 10px 14px;">
                        <span class="spec-grid-label">Kapasitas Listrik</span>
                        <strong class="spec-grid-val">{{ $selectedSubmission['electricity'] }}</strong>
                    </div>
                    <div class="spec-grid-item" style="padding: 10px 14px;">
                        <span class="spec-grid-label">Status Sertifikat</span>
                        <strong class="spec-grid-val" style="color: #059669;">{{ $selectedSubmission['legal'] }}</strong>
                    </div>
                </div>
            </div>

            <!-- Section 3: Foto & Dokumen Legalitas (6 Berkas) -->
            <div style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <h3 style="font-size: 0.95rem; color: var(--primary-navy); text-transform: uppercase; letter-spacing: 0.5px; margin: 0; font-weight: 700;">
                        3. Berkas Foto & Scan Sertifikat BPN (6 Berkas)
                    </h3>
                    <span style="font-size: 0.78rem; color: var(--accent-teal); font-weight: 600; cursor: pointer;" onclick="showToast('Membuka Galeri Berkas High-Res...')">Lihat Resolusi Asli</span>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 10px;">
                    <div style="position: relative; height: 80px; border-radius: 6px; overflow: hidden; background: #0E2238;">
                        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=300&q=80" style="width: 100%; height: 100%; object-fit: cover;">
                        <span style="position: absolute; bottom: 4px; left: 4px; background: rgba(0,0,0,0.7); color: #fff; font-size: 0.65rem; padding: 2px 4px; border-radius: 3px;">Fasad Depan</span>
                    </div>
                    <div style="position: relative; height: 80px; border-radius: 6px; overflow: hidden; background: #0E2238;">
                        <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=300&q=80" style="width: 100%; height: 100%; object-fit: cover;">
                        <span style="position: absolute; bottom: 4px; left: 4px; background: rgba(0,0,0,0.7); color: #fff; font-size: 0.65rem; padding: 2px 4px; border-radius: 3px;">Living Room</span>
                    </div>
                    <div style="position: relative; height: 80px; border-radius: 6px; overflow: hidden; background: #0E2238;">
                        <img src="https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=300&q=80" style="width: 100%; height: 100%; object-fit: cover;">
                        <span style="position: absolute; bottom: 4px; left: 4px; background: rgba(0,0,0,0.7); color: #fff; font-size: 0.65rem; padding: 2px 4px; border-radius: 3px;">Master Suite</span>
                    </div>
                    <div style="position: relative; height: 80px; border-radius: 6px; overflow: hidden; background: #0E2238;">
                        <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=300&q=80" style="width: 100%; height: 100%; object-fit: cover;">
                        <span style="position: absolute; bottom: 4px; left: 4px; background: rgba(0,0,0,0.7); color: #fff; font-size: 0.65rem; padding: 2px 4px; border-radius: 3px;">Kitchen Set</span>
                    </div>
                    <div style="position: relative; height: 80px; border-radius: 6px; overflow: hidden; background: #0E2238;">
                        <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=300&q=80" style="width: 100%; height: 100%; object-fit: cover;">
                        <span style="position: absolute; bottom: 4px; left: 4px; background: rgba(0,0,0,0.7); color: #fff; font-size: 0.65rem; padding: 2px 4px; border-radius: 3px;">Carport Area</span>
                    </div>
                    <div style="position: relative; height: 80px; border-radius: 6px; overflow: hidden; background: #E2E8F0; display: flex; flex-direction: column; align-items: center; justify-content: center; border: 1.5px dashed #94A3B8;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <span style="font-size: 0.68rem; font-weight: 700; color: #0E2238; margin-top: 2px;">Scan SHM BPN</span>
                    </div>
                </div>
            </div>

            <!-- Section 4: Checklist Kurasi Standar Tim Kumala -->
            <div style="margin-bottom: 24px;">
                <h3 style="font-size: 0.95rem; color: var(--primary-navy); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; font-weight: 700;">
                    4. Checklist Verifikasi Tim Kurator
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px; font-size: 0.82rem;">
                    <label style="display: flex; align-items: center; gap: 8px; padding: 10px; background: #F8FAFC; border-radius: 6px; border: 1px solid var(--border-light); cursor: pointer;">
                        <input type="checkbox" checked style="accent-color: #00B488;">
                        <span>Identitas pemilik & no WA terverifikasi</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; padding: 10px; background: #F8FAFC; border-radius: 6px; border: 1px solid var(--border-light); cursor: pointer;">
                        <input type="checkbox" checked style="accent-color: #00B488;">
                        <span>Koordinat Maps & zonasi PBG akurat</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; padding: 10px; background: #F8FAFC; border-radius: 6px; border: 1px solid var(--border-light); cursor: pointer;">
                        <input type="checkbox" checked style="accent-color: #00B488;">
                        <span>Sertifikat SHM cocok data BPN resmi</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; padding: 10px; background: #FFF1F2; border-radius: 6px; border: 1px solid #FECDD3; cursor: pointer; color: #9F1239;">
                        <input type="checkbox" style="accent-color: #00B488;">
                        <span>Foto memenuhi standar estetika HD Kumala</span>
                    </label>
                </div>
            </div>

            <!-- Section 5: Catatan Internal Kurator -->
            <div style="margin-bottom: 26px;">
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 6px;">Catatan Privat Tim Kurator</label>
                <textarea rows="2" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; color: var(--primary-navy);" placeholder="Tuliskan catatan privat untuk kurator lain...">Aset lokasi sangat prime dekat Echo Beach. Foto interior dapur perlu peningkatan pencahayaan standar Kumala sebelum diterbitkan.</textarea>
            </div>

            <!-- Section 6: Action Footer Toolbar -->
            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 20px; border-top: 1px solid var(--border-color); flex-wrap: wrap; gap: 12px;">
                <div style="display: flex; gap: 8px;">
                    <button type="button" class="btn-fav-danger" style="padding: 10px 16px; font-size: 0.85rem; border-radius: 6px;" onclick="showToast('Pengajuan ditolak. Notifikasi email terkirim ke pemilik.')">
                        Tolak Pengajuan
                    </button>
                    <button type="button" class="btn-fav-tool" style="padding: 10px 16px; font-size: 0.85rem; border-radius: 6px; color: #D97706; border-color: #FDE68A; background: #FFFBEB;" onclick="showToast('Permintaan revisi dokumen telah dikirim via WhatsApp.')">
                        Minta Revisi
                    </button>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="button" class="btn-fav-tool" style="padding: 10px 16px; font-size: 0.85rem; border-radius: 6px;" onclick="showToast('Membuka form penyesuaian data teknis...')">
                        Edit Data Standar
                    </button>
                    <button type="button" class="btn-teal" style="padding: 10px 22px; font-size: 0.85rem; border-radius: 6px;" onclick="showToast('Listing #TP-204 berhasil disetujui & langsung diterbitkan ke katalog publik!')">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>Setujui & Publikasikan</span>
                    </button>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@section('styles')
<style>
.queue-tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: #FFFFFF;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--primary-navy);
    cursor: pointer;
    transition: var(--transition-fast);
}
.queue-tab-btn:hover {
    background: #F8FAFC;
    border-color: #94A3B8;
}
.queue-tab-btn.active {
    background: var(--primary-navy);
    color: #FFFFFF;
    border-color: var(--primary-navy);
}
.queue-tab-btn .queue-count {
    background: #F1F5F9;
    color: #475569;
    padding: 2px 7px;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
}
.queue-tab-btn.active .queue-count {
    background: rgba(255, 255, 255, 0.2);
    color: #FFFFFF;
}

.submission-queue-card {
    background: #FFFFFF;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 16px;
    cursor: pointer;
    transition: var(--transition-fast);
    box-shadow: var(--shadow-sm);
}
.submission-queue-card:hover {
    border-color: #94A3B8;
}
.submission-queue-card.active {
    border-color: var(--accent-teal);
    background: #F0FDF4;
    border-left: 4px solid var(--accent-teal);
}

@media (max-width: 1024px) {
    div[style*="grid-template-columns: 360px 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection

@section('scripts')
<script>
function selectSubmission(card, id) {
    document.querySelectorAll('.submission-queue-card').forEach(c => c.classList.remove('active'));
    card.classList.add('active');
    document.getElementById('deck-id').textContent = id;
    showToast(`Memuat data inspeksi berkas ${id}...`);
}

function filterQueue(type, btn) {
    document.querySelectorAll('.queue-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    showToast(`Memfilter antrean: ${type}`);
}
</script>
@endsection
