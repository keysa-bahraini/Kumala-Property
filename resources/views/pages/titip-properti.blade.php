@extends('layouts.app')

@section('title', 'Titip Properti - Kumala Property')

@section('content')
<div class="container" style="padding-top: 24px; padding-bottom: 90px;">

    <!-- Breadcrumbs -->
    <div class="breadcrumbs-bar" style="margin-bottom: 20px;">
        <ul class="breadcrumbs-list">
            <li class="breadcrumbs-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumbs-separator">&gt;</li>
            <li class="breadcrumbs-item active">Titip Properti</li>
        </ul>
    </div>

    <!-- Header Section -->
    <div class="titip-header-section">
        <span class="titip-eyebrow">{{ $globalSettings['titip_badge'] ?? 'LAYANAN KONSINYASI & PEMASARAN RESMI' }}</span>
        <h1 class="titip-main-title">
            {{ $globalSettings['titip_title'] ?? 'Titipkan Properti Anda, Jangkau Pembeli & Penyewa Berkualitas' }}
        </h1>
        <p class="titip-main-desc">
            {{ $globalSettings['titip_desc'] ?? 'Tim kurator Kumala Property siap mendampingi proses valuasi presisi, produksi visual arsitektural berkelas, hingga finalisasi legalitas transaksi tuntas tanpa biaya di muka.' }}
        </p>

        <!-- Feature Badges -->
        <div class="titip-badges-wrap">
            <div class="titip-badge-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ $globalSettings['titip_benefit_1'] ?? 'Valuasi & Konsultasi Gratis' }}</span>
            </div>
            <div class="titip-badge-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span>{{ $globalSettings['titip_benefit_2'] ?? 'Jejaring Investor Terverifikasi' }}</span>
            </div>
            <div class="titip-badge-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>{{ $globalSettings['titip_benefit_3'] ?? 'Transparan & Legalitas Aman BPN' }}</span>
            </div>
        </div>
    </div>

    <!-- Main Layout: Form Left, Guidance Sidebar Right -->
    <div class="titip-main-layout">
        
        <!-- Left: Unified Clean Form Container -->
        <div class="titip-form-card">
            <form id="form-titip-properti" onsubmit="handleTitipSubmit(event)">
                @csrf

                <!-- ================= BAGIAN 1: DATA PEMILIK ================= -->
                <div class="form-section-block">
                    <div class="form-section-heading">
                        <span class="section-step-pill">1</span>
                        <div>
                            <h2 class="form-section-title">Data Pemilik Properti</h2>
                            <p class="form-section-sub">Informasi identitas dan kontak untuk verifikasi kepemilikan dan koordinasi survei aset.</p>
                        </div>
                    </div>

                    <div class="form-grid-2col" style="margin-bottom: 16px;">
                        <div>
                            <label class="form-field-label">Nama Lengkap Pemilik / Kuasa <span class="required-star">*</span></label>
                            <input type="text" name="owner_name" class="form-field-control" placeholder="Contoh: Hendra Gunawan" required>
                        </div>
                        <div>
                            <label class="form-field-label">Status Kepemilikan <span class="required-star">*</span></label>
                            <div class="ownership-toggle-group">
                                <label class="ownership-toggle-label">
                                    <input type="radio" name="ownership_status" value="direct_owner" checked>
                                    <span class="ownership-toggle-btn">Pemilik Langsung</span>
                                </label>
                                <label class="ownership-toggle-label">
                                    <input type="radio" name="ownership_status" value="agent_official">
                                    <span class="ownership-toggle-btn">Kuasa Jual Resmi</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-grid-2col">
                        <div>
                            <label class="form-field-label">Nomor WhatsApp Aktif <span class="required-star">*</span></label>
                            <div class="phone-input-wrap">
                                <span class="phone-prefix-tag">+62</span>
                                <input type="tel" name="owner_phone" class="phone-input-control" placeholder="812-8899-2345" required>
                            </div>
                            <span class="form-field-hint">Untuk konfirmasi jadwal survei oleh tim konsinyasi</span>
                        </div>
                        <div>
                            <label class="form-field-label">Alamat Email Aktif <span class="required-star">*</span></label>
                            <input type="email" name="owner_email" class="form-field-control" placeholder="hendra@email.com" required>
                            <span class="form-field-hint">Untuk pengiriman laporan valuasi dan dokumen resmi</span>
                        </div>
                    </div>
                </div>

                <!-- ================= BAGIAN 2: IDENTITAS & LOKASI PROPERTI ================= -->
                <div class="form-section-block">
                    <div class="form-section-heading">
                        <span class="section-step-pill">2</span>
                        <div>
                            <h2 class="form-section-title">Identitas &amp; Lokasi Properti</h2>
                            <p class="form-section-sub">Karakteristik unit dan wilayah administratif untuk pemetaan calon pembeli.</p>
                        </div>
                    </div>

                    <div style="margin-bottom: 18px;">
                        <label class="form-field-label">Jenis Transaksi <span class="required-star">*</span></label>
                        <div class="segmented-trx-group">
                            <button type="button" class="segmented-trx-btn active" onclick="setTitipTrx(this, 'Jual')">Dijual</button>
                            <button type="button" class="segmented-trx-btn" onclick="setTitipTrx(this, 'Sewa')">Disewakan</button>
                            <button type="button" class="segmented-trx-btn" onclick="setTitipTrx(this, 'Keduanya')">Bisa Jual / Sewa</button>
                        </div>
                        <input type="hidden" name="transaction_type" id="titip-trx-input" value="Jual">
                    </div>

                    <div style="margin-bottom: 18px;">
                        <label class="form-field-label">Kategori Properti <span class="required-star">*</span></label>
                        <div class="category-pills-wrap">
                            @php
                                $presetCats = ['Villa Mewah', 'Rumah Tinggal', 'Apartemen / Penthouse', 'Tanah Komersial', 'Ruko / Shophouse'];
                                $firstCatName = 'Villa Mewah';
                            @endphp
                            @if(!empty($categories) && count($categories) > 0)
                                @foreach($categories as $cat)
                                    @php
                                        $catName = is_array($cat) ? ($cat['name'] ?? '') : ($cat->name ?? '');
                                        if ($loop->first) { $firstCatName = $catName; }
                                    @endphp
                                    <button type="button" class="category-pill-btn {{ $loop->first ? 'active' : '' }}" onclick="selectCategory(this, '{{ $catName }}')">{{ $catName }}</button>
                                @endforeach
                            @else
                                @foreach($presetCats as $index => $cname)
                                    <button type="button" class="category-pill-btn {{ $index === 0 ? 'active' : '' }}" onclick="selectCategory(this, '{{ $cname }}')">{{ $cname }}</button>
                                @endforeach
                            @endif
                        </div>
                        <input type="hidden" name="property_category" id="titip-cat-input" value="{{ $firstCatName }}">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label class="form-field-label">Nama / Judul Properti <span class="required-star">*</span></label>
                        <input type="text" name="property_title" class="form-field-control" placeholder="Contoh: Modern Tropical Villa di Canggu" required>
                    </div>

                    <div class="form-grid-2col" style="margin-bottom: 16px;">
                        <div>
                            <label class="form-field-label">Provinsi &amp; Kota / Kabupaten <span class="required-star">*</span></label>
                            <select name="city_region" class="form-field-control" required>
                                @if(!empty($regions) && count($regions) > 0)
                                    @foreach($regions as $reg)
                                        @php
                                            $regName = is_array($reg) ? ($reg['name'] ?? '') : ($reg->name ?? '');
                                            $regCity = is_array($reg) ? (!empty($reg['city']) ? $reg['city'] : $regName) : (!empty($reg->city) ? $reg->city : $regName);
                                        @endphp
                                        <option value="{{ $regCity }}">{{ $regName }}</option>
                                    @endforeach
                                @else
                                    <option value="Bali - Badung">Bali - Badung (Canggu, Seminyak, Kuta)</option>
                                    <option value="Jakarta Selatan">DKI Jakarta - Jakarta Selatan</option>
                                    <option value="Tangerang Selatan">Banten - Tangerang Selatan (BSD City)</option>
                                    <option value="Surabaya">Jawa Timur - Surabaya Barat</option>
                                    <option value="Bandung">Jawa Barat - Bandung Utara</option>
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="form-field-label">Area / Kawasan Spesifik <span class="required-star">*</span></label>
                            <input type="text" name="specific_area" class="form-field-control" placeholder="Contoh: Berawa, Canggu atau Kemang Timur" required>
                        </div>
                    </div>

                    <div>
                        <label class="form-field-label">Alamat Lengkap Properti <span class="required-star">*</span></label>
                        <textarea name="full_address" rows="2" class="form-field-control" placeholder="Nama jalan, nomor rumah/kavling, kompleks perumahan" required></textarea>
                        <span class="form-field-hint">Alamat detail dirahasiakan dan hanya digunakan tim Kumala untuk survei langsung.</span>
                    </div>
                </div>

                <!-- ================= BAGIAN 3: NILAI PENAWARAN & SPESIFIKASI ================= -->
                <div class="form-section-block">
                    <div class="form-section-heading">
                        <span class="section-step-pill">3</span>
                        <div>
                            <h2 class="form-section-title">Nilai Penawaran &amp; Spesifikasi</h2>
                            <p class="form-section-sub">Ekspektasi nilai aset dan kelengkapan fasilitas ruang.</p>
                        </div>
                    </div>

                    <!-- Box Harga Bersih -->
                    <div class="pricing-card-compact">
                        <div class="form-grid-2col" style="align-items: center; gap: 16px;">
                            <div>
                                <label class="form-field-label" style="margin-bottom: 6px;">Nilai Penawaran / Harga yang Diharapkan <span class="required-star">*</span></label>
                                <div class="price-input-wrap">
                                    <span class="price-currency-tag">Rp</span>
                                    <input type="text" id="input-price-raw" name="asking_price" class="price-input-control" placeholder="15.000.000.000" oninput="updateTerbilang(this.value)" required>
                                </div>
                                <div id="price-terbilang" class="price-terbilang-text">Terbilang: Rp 15 Miliar</div>
                            </div>
                            <div>
                                <label class="form-field-label" style="margin-bottom: 6px;">Ketentuan Penawaran</label>
                                <div class="pricing-terms-wrap">
                                    <label class="clean-check-label">
                                        <input type="checkbox" name="is_negotiable" checked>
                                        <span>Bisa Nego</span>
                                    </label>
                                    <label class="clean-check-label">
                                        <input type="radio" name="term_rate" value="yearly">
                                        <span>Per Tahun</span>
                                    </label>
                                    <label class="clean-check-label">
                                        <input type="radio" name="term_rate" value="monthly">
                                        <span>Per Bulan</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Spesifikasi Teknis 4 Kolom -->
                    <div class="specs-grid-4col" style="margin-bottom: 20px;">
                        <div>
                            <label class="form-field-label">Luas Tanah</label>
                            <div class="unit-input-wrap">
                                <input type="number" name="land_size" value="450" class="unit-input-field" placeholder="0">
                                <span class="unit-tag">m²</span>
                            </div>
                        </div>
                        <div>
                            <label class="form-field-label">Luas Bangunan</label>
                            <div class="unit-input-wrap">
                                <input type="number" name="building_size" value="380" class="unit-input-field" placeholder="0">
                                <span class="unit-tag">m²</span>
                            </div>
                        </div>
                        <div>
                            <label class="form-field-label">Kamar Tidur</label>
                            <select name="bedrooms" class="form-field-control">
                                <option>1 KT</option>
                                <option>2 KT</option>
                                <option>3 KT</option>
                                <option selected>4 KT</option>
                                <option>5 KT</option>
                                <option>6+ KT</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-field-label">Kamar Mandi</label>
                            <select name="bathrooms" class="form-field-control">
                                <option>1 KM</option>
                                <option>2 KM</option>
                                <option>3 KM</option>
                                <option selected>4 KM</option>
                                <option>5+ KM</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fasilitas & Dokumen -->
                    <div>
                        <label class="form-field-label" style="margin-bottom: 10px;">Fasilitas Utama &amp; Legalitas Dokumen</label>
                        <div class="facilities-grid">
                            <label class="clean-facility-item">
                                <input type="checkbox" name="facilities[]" value="pool" checked>
                                <span>Kolam Renang Privat</span>
                            </label>
                            <label class="clean-facility-item">
                                <input type="checkbox" name="facilities[]" value="furnished" checked>
                                <span>Fully Furnished</span>
                            </label>
                            <label class="clean-facility-item">
                                <input type="checkbox" name="facilities[]" value="carport" checked>
                                <span>Carport / Garasi</span>
                            </label>
                            <label class="clean-facility-item">
                                <input type="checkbox" name="facilities[]" value="ocean_view">
                                <span>Ocean View / Lepas</span>
                            </label>
                            <label class="clean-facility-item">
                                <input type="checkbox" name="facilities[]" value="shm" checked>
                                <span>SHM (Hak Milik)</span>
                            </label>
                            <label class="clean-facility-item">
                                <input type="checkbox" name="facilities[]" value="pbg" checked>
                                <span>PBG / IMB Lengkap</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- ================= BAGIAN 4: DESKRIPSI & FOTO PROPERTI ================= -->
                <div class="form-section-block">
                    <div class="form-section-heading">
                        <span class="section-step-pill">4</span>
                        <div>
                            <h2 class="form-section-title">Deskripsi &amp; Dokumentasi Foto</h2>
                            <p class="form-section-sub">Tuliskan narasi keunggulan unit dan unggah foto pratinjau untuk kurasi.</p>
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label class="form-field-label">Deskripsi &amp; Nilai Tambah Properti <span class="required-star">*</span></label>
                        <textarea id="titip-description" name="description" rows="4" class="form-field-control" placeholder="Jelaskan konsep arsitektur, kualitas material, kondisi bangunan, aksesibilitas jalan, atau potensi nilai investasi properti Anda..." oninput="updateWordCount(this.value)" required></textarea>
                        <div class="word-counter-bar">
                            <span>Deskripsi informatif mempercepat keputusan calon pembeli</span>
                            <span id="word-counter-display" class="word-counter-val">0 kata</span>
                        </div>
                    </div>

                    <!-- Dropzone Upload Foto -->
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <label class="form-field-label" style="margin-bottom: 0;">Foto Dokumentasi Properti</label>
                            <span class="photo-optional-badge">Format JPG, PNG, WEBP (Maks. 10 MB)</span>
                        </div>

                        <div class="clean-dropzone" onclick="document.getElementById('file-upload-input').click()">
                            <input type="file" id="file-upload-input" name="photos[]" multiple accept="image/png, image/jpeg, image/webp" style="display: none;" onchange="handleFileSelect(this)">
                            <div class="dropzone-icon-circle">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                            </div>
                            <div class="dropzone-text-main">Klik untuk memilih foto atau seret berkas ke sini</div>
                            <div class="dropzone-text-sub">Unggah tampak fasad, ruang tamu, kamar, atau denah bangunan</div>
                        </div>

                        <!-- Dynamic Photo Preview Container -->
                        <div id="photos-preview-wrap" style="margin-top: 14px;">
                            <div id="photos-count-label" class="photos-count-label" style="display: none;">Foto Terpilih:</div>
                            <div id="photos-preview-grid" class="photos-preview-grid">
                                <div class="photos-empty-state" id="photos-empty-placeholder">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                                    <span>Belum ada foto yang dipilih. Foto yang Anda unggah akan ditampilkan di sini sebagai pratinjau.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= PERSETUJUAN & SUBMIT ================= -->
                <div class="form-submit-section">
                    <label class="legal-consent-box">
                        <input type="checkbox" name="legal_consent" required class="legal-consent-checkbox">
                        <span class="legal-consent-text">
                            Saya menyatakan seluruh informasi yang diisi adalah benar, berstatus sah secara hukum, dan saya memiliki kewenangan penuh atau kuasa resmi untuk menitipkan unit properti ini tanpa sengketa hak milik.
                        </span>
                    </label>

                    <div class="free-fee-notice">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span><strong>Bebas biaya pendaftaran.</strong> Komisi profesional standar (2%–3%) hanya berlaku saat transaksi tuntas ditandatangani di hadapan PPAT/Notaris.</span>
                    </div>

                    <div class="submit-buttons-row">
                        <button type="submit" class="btn-primary btn-submit-action" id="btn-submit-titip">
                            <span>Kirim Data Properti untuk Review</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </button>
                        <a href="https://wa.me/6281288992345?text=Halo%20Advisor%20Kumala,%20saya%20ingin%20konsultasi%20titip%20properti." target="_blank" class="btn-outline-navy btn-wa-action">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            <span>Tanya Advisor via WA</span>
                        </a>
                    </div>
                </div>

            </form>
        </div>

        <!-- Right: Sleek Sticky Guidance Sidebar -->
        <div class="titip-sidebar">
            
            <!-- Card 1: 4-Step Process -->
            <div class="sidebar-info-card">
                <div class="sidebar-card-header">
                    <h3 class="sidebar-card-title">Alur Konsinyasi Kumala</h3>
                    <div class="sidebar-icon-badge">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                </div>

                <div class="timeline-step-item">
                    <span class="timeline-step-badge">1</span>
                    <div>
                        <strong class="timeline-title">Pendaftaran Data</strong>
                        <p class="timeline-desc">Isi rincian unit dan foto pratinjau melalui formulir ini.</p>
                    </div>
                </div>

                <div class="timeline-step-item">
                    <span class="timeline-step-badge">2</span>
                    <div>
                        <strong class="timeline-title">Kurasi &amp; Valuasi</strong>
                        <p class="timeline-desc">Tim appraiser menganalisis nilai pasar wajar (CMA) dan validasi legalitas.</p>
                    </div>
                </div>

                <div class="timeline-step-item">
                    <span class="timeline-step-badge">3</span>
                    <div>
                        <strong class="timeline-title">Produksi &amp; Pemasaran</strong>
                        <p class="timeline-desc">Dokumentasi fotografi profesional dan penayangan ke jaringan investor eksklusif.</p>
                    </div>
                </div>

                <div class="timeline-step-item">
                    <span class="timeline-step-badge">4</span>
                    <div>
                        <strong class="timeline-title">Transaksi Legal</strong>
                        <p class="timeline-desc">Pendampingan negosiasi hingga penandatanganan akta resmi PPAT.</p>
                    </div>
                </div>
            </div>

            <!-- Card 2: Standar Kurasi -->
            <div class="sidebar-info-card sidebar-card-warm">
                <div class="sidebar-header-warm">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <strong style="font-size: 0.95rem;">Standar Verifikasi Kumala</strong>
                </div>

                <p style="font-size: 0.82rem; color: #475569; line-height: 1.5; margin-bottom: 14px;">
                    Untuk menjaga reputasi portofolio terpercaya, setiap unit ditelaah secara cermat terhadap:
                </p>

                <ul class="verification-list">
                    <li>
                        <span class="check-icon">✓</span>
                        <span>Sertifikat Hak Milik (SHM) / HGB murni bebas sengketa</span>
                    </li>
                    <li>
                        <span class="check-icon">✓</span>
                        <span>Bebas status sita lelang bank atau sengketa waris</span>
                    </li>
                    <li>
                        <span class="check-icon">✓</span>
                        <span>Aksesibilitas jalan memadai dan perizinan zonasi (PBG)</span>
                    </li>
                </ul>
            </div>

        </div>

    </div>

</div>
@endsection

@section('styles')
<style>
/* Header Styling */
.titip-header-section {
    margin-bottom: 32px;
    max-width: 860px;
}
.titip-eyebrow {
    display: inline-block;
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.08em;
    color: #00838F;
    background: #E6FBF5;
    border: 1px solid #99EEDB;
    padding: 4px 12px;
    border-radius: 20px;
    margin-bottom: 14px;
    text-transform: uppercase;
}
.titip-main-title {
    font-size: clamp(1.85rem, 3.8vw, 2.6rem);
    color: #0E1E2E;
    margin-bottom: 12px;
    line-height: 1.22;
    font-weight: 800;
    letter-spacing: -0.02em;
}
.titip-main-desc {
    font-size: 0.98rem;
    color: #526071;
    line-height: 1.6;
    margin-bottom: 20px;
}
.titip-badges-wrap {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.titip-badge-item {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 600;
    color: #0E1E2E;
    box-shadow: 0 1px 3px rgba(14, 30, 46, 0.04);
}
.titip-badge-item svg {
    color: #00B488;
    flex-shrink: 0;
}

/* Main Layout Grid */
.titip-main-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 340px;
    gap: 32px;
    align-items: start;
}

/* Unified Form Card */
.titip-form-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 4px 20px rgba(14, 30, 46, 0.04);
}

/* Form Section Blocks */
.form-section-block {
    padding-bottom: 28px;
    margin-bottom: 28px;
    border-bottom: 1px solid #F1F5F9;
}
.form-section-block:last-of-type {
    border-bottom: none;
    padding-bottom: 12px;
    margin-bottom: 12px;
}
.form-section-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}
.section-step-pill {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #E6FBF5;
    color: #00838F;
    border: 1px solid #99EEDB;
    font-weight: 800;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.form-section-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #0E1E2E;
    line-height: 1.25;
    margin: 0;
}
.form-section-sub {
    font-size: 0.8rem;
    color: #64748B;
    margin: 2px 0 0;
}

/* Form Inputs & Controls */
.form-field-label {
    display: block;
    font-size: 0.84rem;
    font-weight: 600;
    color: #1E293B;
    margin-bottom: 6px;
}
.required-star {
    color: #E11D48;
}
.form-field-control {
    width: 100%;
    padding: 10px 14px;
    font-size: 0.9rem;
    color: #0E1E2E;
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    box-sizing: border-box;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    font-family: inherit;
}
.form-field-control:focus {
    outline: none;
    border-color: #00B488;
    box-shadow: 0 0 0 3px rgba(0, 180, 136, 0.12);
}
.form-field-hint {
    display: block;
    font-size: 0.72rem;
    color: #64748B;
    margin-top: 5px;
}

/* Grids */
.form-grid-2col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.specs-grid-4col {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
}

/* Phone input with +62 badge */
.phone-input-wrap {
    display: flex;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    overflow: hidden;
    background: #FFFFFF;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.phone-input-wrap:focus-within {
    border-color: #00B488;
    box-shadow: 0 0 0 3px rgba(0, 180, 136, 0.12);
}
.phone-prefix-tag {
    background: #F1F5F9;
    color: #475569;
    font-weight: 700;
    font-size: 0.86rem;
    padding: 10px 14px;
    display: flex;
    align-items: center;
    border-right: 1px solid #CBD5E1;
}
.phone-input-control {
    width: 100%;
    padding: 10px 14px;
    border: none;
    font-size: 0.9rem;
    color: #0E1E2E;
    outline: none;
    font-weight: 500;
    background: transparent;
}

/* Ownership Toggle Pills */
.ownership-toggle-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}
.ownership-toggle-label {
    cursor: pointer;
    margin: 0;
}
.ownership-toggle-label input {
    display: none;
}
.ownership-toggle-btn {
    display: block;
    text-align: center;
    padding: 9px 12px;
    font-size: 0.84rem;
    font-weight: 600;
    color: #475569;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    transition: all 0.15s ease;
}
.ownership-toggle-label input:checked + .ownership-toggle-btn {
    background: #E6FBF5;
    border-color: #00B488;
    color: #00838F;
    font-weight: 700;
}

/* Segmented Trx Tabs */
.segmented-trx-group {
    display: inline-flex;
    background: #F1F5F9;
    border-radius: 8px;
    padding: 3px;
    gap: 4px;
    width: 100%;
    max-width: 420px;
    box-sizing: border-box;
}
.segmented-trx-btn {
    flex: 1;
    padding: 8px 12px;
    border-radius: 6px;
    border: none;
    font-size: 0.85rem;
    font-weight: 600;
    color: #475569;
    background: transparent;
    cursor: pointer;
    transition: all 0.15s ease;
    text-align: center;
}
.segmented-trx-btn.active {
    background: #0E1E2E;
    color: #FFFFFF;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* Category Pills */
.category-pills-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.category-pill-btn {
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 600;
    color: #334155;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    cursor: pointer;
    transition: all 0.15s ease;
}
.category-pill-btn:hover {
    border-color: #CBD5E1;
    background: #F1F5F9;
}
.category-pill-btn.active {
    background: #0E1E2E;
    color: #FFFFFF;
    border-color: #0E1E2E;
}

/* Pricing Card Compact */
.pricing-card-compact {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 16px 18px;
    margin-bottom: 20px;
}
.price-input-wrap {
    display: flex;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    overflow: hidden;
    background: #FFFFFF;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.price-input-wrap:focus-within {
    border-color: #00B488;
    box-shadow: 0 0 0 3px rgba(0, 180, 136, 0.12);
}
.price-currency-tag {
    background: #F1F5F9;
    color: #334155;
    font-weight: 700;
    padding: 9px 13px;
    font-size: 0.9rem;
    border-right: 1px solid #CBD5E1;
    display: flex;
    align-items: center;
}
.price-input-control {
    width: 100%;
    padding: 9px 12px;
    border: none;
    font-size: 1rem;
    font-weight: 700;
    color: #0E1E2E;
    outline: none;
    background: transparent;
}
.price-terbilang-text {
    font-size: 0.74rem;
    color: #059669;
    font-weight: 600;
    margin-top: 5px;
}
.pricing-terms-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    padding-top: 6px;
}
.clean-check-label {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 0.84rem;
    color: #334155;
    cursor: pointer;
    font-weight: 500;
}
.clean-check-label input {
    accent-color: #00B488;
    width: 16px;
    height: 16px;
    cursor: pointer;
}

/* Units Input (m²) */
.unit-input-wrap {
    display: flex;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    overflow: hidden;
    background: #FFFFFF;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.unit-input-wrap:focus-within {
    border-color: #00B488;
    box-shadow: 0 0 0 3px rgba(0, 180, 136, 0.12);
}
.unit-input-field {
    width: 100%;
    padding: 10px 12px;
    border: none;
    font-size: 0.9rem;
    color: #0E1E2E;
    outline: none;
    background: transparent;
}
.unit-tag {
    background: #F1F5F9;
    padding: 10px 12px;
    font-size: 0.8rem;
    color: #475569;
    font-weight: 600;
    border-left: 1px solid #CBD5E1;
    display: flex;
    align-items: center;
}

/* Facilities Checkbox Grid */
.facilities-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}
.clean-facility-item {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 9px 12px;
    font-size: 0.83rem;
    font-weight: 600;
    color: #1E293B;
    cursor: pointer;
    transition: border-color 0.15s ease, background-color 0.15s ease;
}
.clean-facility-item:hover {
    border-color: #99EEDB;
    background: #F0FDF9;
}
.clean-facility-item input {
    accent-color: #00B488;
    width: 16px;
    height: 16px;
}

/* Word Counter */
.word-counter-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.74rem;
    color: #64748B;
    margin-top: 5px;
}
.word-counter-val {
    font-weight: 700;
    color: #0E1E2E;
}

/* Photo Dropzone */
.photo-optional-badge {
    font-size: 0.74rem;
    color: #64748B;
}
.clean-dropzone {
    border: 1.5px dashed #CBD5E1;
    border-radius: 12px;
    padding: 24px 20px;
    text-align: center;
    background: #F8FAFC;
    cursor: pointer;
    transition: all 0.15s ease;
}
.clean-dropzone:hover {
    border-color: #00B488;
    background: #F0FDF9;
}
.dropzone-icon-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #E6FBF5;
    color: #00838F;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
}
.dropzone-text-main {
    font-size: 0.92rem;
    font-weight: 700;
    color: #0E1E2E;
    margin-bottom: 2px;
}
.dropzone-text-sub {
    font-size: 0.78rem;
    color: #64748B;
}

/* Photos Preview Container */
.photos-count-label {
    font-size: 0.8rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 8px;
}
.photos-preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 10px;
}
.photos-empty-state {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    gap: 10px;
    background: #F8FAFC;
    border: 1px dashed #E2E8F0;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 0.78rem;
    color: #64748B;
}
.photos-empty-state svg {
    color: #94A3B8;
    flex-shrink: 0;
}
.photo-thumb-card {
    position: relative;
    height: 96px;
    border-radius: 8px;
    overflow: hidden;
    background: #0E1E2E;
    border: 1px solid #CBD5E1;
}
.photo-thumb-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.photo-thumb-badge {
    position: absolute;
    top: 5px;
    left: 5px;
    background: #0E1E2E;
    color: #FFFFFF;
    font-size: 0.6rem;
    font-weight: 700;
    padding: 2px 5px;
    border-radius: 4px;
}
.photo-thumb-name {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.85), transparent);
    padding: 4px 6px;
    font-size: 0.66rem;
    color: #FFF;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.photo-thumb-remove {
    position: absolute;
    top: 4px;
    right: 4px;
    background: rgba(225, 29, 72, 0.85);
    color: #FFFFFF;
    border: none;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    font-size: 11px;
    line-height: 1;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Submission Action Row */
.form-submit-section {
    padding-top: 10px;
}
.legal-consent-box {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    cursor: pointer;
    margin-bottom: 16px;
}
.legal-consent-checkbox {
    width: 18px;
    height: 18px;
    margin-top: 2px;
    accent-color: #00B488;
    flex-shrink: 0;
    cursor: pointer;
}
.legal-consent-text {
    font-size: 0.84rem;
    color: #334155;
    line-height: 1.5;
}
.free-fee-notice {
    background: #F1F5F9;
    border-radius: 8px;
    padding: 10px 14px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.8rem;
    color: #1E293B;
    margin-bottom: 22px;
}
.free-fee-notice svg {
    color: #00B488;
    flex-shrink: 0;
}
.submit-buttons-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.btn-submit-action {
    padding: 12px 28px;
    font-size: 0.95rem;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}
.btn-wa-action {
    padding: 12px 20px;
    font-size: 0.92rem;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

/* Sidebar Styling */
.titip-sidebar {
    position: sticky;
    top: 90px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.sidebar-info-card {
    background: #FFFFFF;
    border-radius: 14px;
    border: 1px solid #E2E8F0;
    padding: 22px;
    box-shadow: 0 2px 12px rgba(14, 30, 46, 0.04);
}
.sidebar-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.sidebar-card-title {
    font-size: 1.1rem;
    color: #0E1E2E;
    font-weight: 700;
    margin: 0;
}
.sidebar-icon-badge {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #E6FBF5;
    color: #00838F;
    display: flex;
    align-items: center;
    justify-content: center;
}
.timeline-step-item {
    display: flex;
    gap: 12px;
    margin-bottom: 18px;
    position: relative;
}
.timeline-step-item:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 26px;
    left: 11px;
    width: 2px;
    height: calc(100% - 8px);
    background: #E2E8F0;
}
.timeline-step-badge {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #0E1E2E;
    color: #FFFFFF;
    font-size: 0.75rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.timeline-title {
    display: block;
    font-size: 0.88rem;
    color: #0E1E2E;
    margin-bottom: 2px;
}
.timeline-desc {
    font-size: 0.78rem;
    color: #64748B;
    line-height: 1.45;
    margin: 0;
}

/* Warm Card */
.sidebar-card-warm {
    background: #FAF8F5;
    border-color: #E2D9CE;
}
.sidebar-header-warm {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
    color: #92400E;
}
.verification-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
    font-size: 0.8rem;
    color: #334155;
}
.verification-list li {
    display: flex;
    align-items: flex-start;
    gap: 8px;
}
.check-icon {
    color: #059669;
    font-weight: 800;
    flex-shrink: 0;
}

/* ================= RESPONSIVE BREAKPOINTS ================= */
@media (max-width: 960px) {
    .titip-main-layout {
        grid-template-columns: 1fr;
        gap: 28px;
    }
    .titip-sidebar {
        position: static;
    }
}

@media (max-width: 640px) {
    .titip-form-card {
        padding: 20px 16px;
        border-radius: 12px;
    }
    .form-grid-2col {
        grid-template-columns: 1fr !important;
        gap: 14px;
    }
    .specs-grid-4col {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 12px;
    }
    .facilities-grid {
        grid-template-columns: 1fr !important;
        gap: 8px;
    }
    .segmented-trx-group {
        max-width: 100%;
    }
    .submit-buttons-row {
        flex-direction: column;
    }
    .btn-submit-action,
    .btn-wa-action {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection

@section('scripts')
<script>
function setTitipTrx(btn, val) {
    document.querySelectorAll('.segmented-trx-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('titip-trx-input').value = val;
}

function selectCategory(btn, val) {
    document.querySelectorAll('.category-pill-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('titip-cat-input').value = val;
}

function updateTerbilang(val) {
    const display = document.getElementById('price-terbilang');
    if (!val) {
        display.textContent = 'Terbilang: -';
        return;
    }
    const clean = val.replace(/[^0-9]/g, '');
    const num = parseInt(clean, 10);
    if (!isNaN(num)) {
        if (num >= 1000000000000) {
            const t = (num / 1000000000000).toLocaleString('id-ID', { maximumFractionDigits: 2 });
            display.textContent = `Terbilang: Rp ${t} Triliun`;
        } else if (num >= 1000000000) {
            const m = (num / 1000000000).toLocaleString('id-ID', { maximumFractionDigits: 2 });
            display.textContent = `Terbilang: Rp ${m} Miliar`;
        } else if (num >= 1000000) {
            const jt = (num / 1000000).toLocaleString('id-ID', { maximumFractionDigits: 1 });
            display.textContent = `Terbilang: Rp ${jt} Juta`;
        } else {
            display.textContent = `Terbilang: Rp ${num.toLocaleString('id-ID')}`;
        }
    }
}

function updateWordCount(text) {
    const words = text.trim().split(/\s+/).filter(w => w.length > 0).length;
    document.getElementById('word-counter-display').textContent = `${words} kata`;
}

let selectedFilesList = [];

function handleFileSelect(input) {
    if (input.files && input.files.length > 0) {
        // Append new files
        const newFiles = Array.from(input.files);
        selectedFilesList = selectedFilesList.concat(newFiles);
        renderPhotoPreviews();
        if (typeof showToast === 'function') {
            showToast(`${newFiles.length} berkas foto berhasil ditambahkan.`);
        }
    }
}

function removeFile(index) {
    selectedFilesList.splice(index, 1);
    renderPhotoPreviews();
}

function renderPhotoPreviews() {
    const grid = document.getElementById('photos-preview-grid');
    const label = document.getElementById('photos-count-label');
    if (!grid) return;

    if (selectedFilesList.length === 0) {
        if (label) label.style.display = 'none';
        grid.innerHTML = `
            <div class="photos-empty-state" id="photos-empty-placeholder">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                <span>Belum ada foto yang dipilih. Foto yang Anda unggah akan ditampilkan di sini sebagai pratinjau.</span>
            </div>
        `;
        return;
    }

    if (label) {
        label.style.display = 'block';
        label.textContent = `Foto Terpilih (${selectedFilesList.length} Foto Siap Diunggah):`;
    }
    grid.innerHTML = '';

    selectedFilesList.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const card = document.createElement('div');
            card.className = 'photo-thumb-card';
            card.innerHTML = `
                <img src="${e.target.result}" alt="${file.name}">
                ${index === 0 ? '<div class="photo-thumb-badge">UTAMA</div>' : ''}
                <div class="photo-thumb-name">${file.name}</div>
                <button type="button" class="photo-thumb-remove" onclick="removeFile(${index})" title="Hapus foto">&times;</button>
            `;
            grid.appendChild(card);
        };
        reader.readAsDataURL(file);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const dropzone = document.querySelector('.clean-dropzone');
    if (dropzone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.style.borderColor = '#00B488';
                dropzone.style.background = '#E6FBF5';
            }, false);
        });
        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.style.borderColor = '#CBD5E1';
                dropzone.style.background = '#F8FAFC';
            }, false);
        });
        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                const input = document.getElementById('file-upload-input');
                input.files = files;
                handleFileSelect(input);
            }
        });
    }
});

function handleTitipSubmit(e) {
    e.preventDefault();
    const form = document.getElementById('form-titip-properti');
    const btn = document.getElementById('btn-submit-titip');
    btn.disabled = true;
    btn.innerHTML = '<span>Memverifikasi & Menerbitkan Data...</span>';

    const formData = new FormData(form);

    // If custom files list has items, ensure they are sent
    if (selectedFilesList.length > 0) {
        formData.delete('photos[]');
        selectedFilesList.forEach(file => {
            formData.append('photos[]', file);
        });
    }

    fetch('{{ route('titip-properti.submit') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) {
            let errorMsg = data.message || 'Terjadi kesalahan validasi data.';
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
            if (typeof showToast === 'function') {
                showToast(`Pengajuan ${data.submission_id} berhasil diterima dan menunggu review admin sebelum tayang.`);
            }
            
            // Populate Modal
            document.getElementById('modal-sub-id').textContent = data.submission_id || '#TP-New';
            document.getElementById('modal-success-title').textContent = data.title || 'Pengajuan Berhasil Diterima';
            
            document.getElementById('modal-titip-success').style.display = 'flex';
            form.reset();
            selectedFilesList = [];
            renderPhotoPreviews();
            
            btn.disabled = false;
            btn.innerHTML = `<span>Pengajuan Terkirim (${data.submission_id}) ✓</span>`;
            setTimeout(() => {
                btn.innerHTML = `<span>Kirim Data Properti untuk Review</span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>`;
            }, 6000);
        } else {
            if (typeof showToast === 'function') {
                showToast('Terjadi kendala saat mengirim pengajuan. Silakan coba lagi.');
            }
            btn.disabled = false;
            btn.innerHTML = `<span>Kirim Ulang Data Properti</span>`;
        }
    })
    .catch(err => {
        console.error(err);
        if (typeof showToast === 'function') {
            showToast(err.message || 'Kendala koneksi. Silakan periksa kelengkapan formulir Anda.');
        } else {
            alert(err.message || 'Kendala koneksi. Silakan periksa kelengkapan formulir Anda.');
        }
        btn.disabled = false;
        btn.innerHTML = `<span>Kirim Data Properti untuk Review</span>`;
    });
}

function closeSuccessModal() {
    document.getElementById('modal-titip-success').style.display = 'none';
}
</script>

<!-- Modal Konfirmasi Sukses Titip Properti -->
<div id="modal-titip-success" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(14, 30, 46, 0.75); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 16px; box-sizing: border-box;">
    <div style="background: #FFFFFF; border-radius: 16px; max-width: 460px; width: 100%; max-height: calc(100vh - 32px); overflow-y: auto; padding: 28px 22px; box-shadow: 0 20px 40px rgba(0,0,0,0.25); text-align: center; border: 1px solid #E2E8F0; position: relative; box-sizing: border-box; animation: fadeInScale 0.3s ease;">
        <div style="width: 52px; height: 52px; border-radius: 50%; background: #E6FBF5; color: #00B488; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px;">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <span style="font-size: 0.72rem; font-weight: 800; color: #00838F; text-transform: uppercase; letter-spacing: 0.06em; display: block; margin-bottom: 6px;">DATA PENGAJUAN DITERIMA</span>
        <h3 style="font-size: 1.25rem; color: #0E1E2E; margin-bottom: 8px; font-weight: 800;" id="modal-success-title">Pengajuan Berhasil Diterima</h3>
        <p style="font-size: 0.86rem; color: #64748B; line-height: 1.5; margin-bottom: 18px;">
            Pengajuan properti Anda telah berhasil diterima dan menunggu review tim kurator kami sebelum ditayangkan di katalog publik.
        </p>
        
        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 14px 16px; margin-bottom: 20px; text-align: left; font-size: 0.84rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="color: #64748B;">Nomor Referensi Pengajuan:</span>
                <strong style="color: #0E1E2E; font-size: 0.95rem;" id="modal-sub-id">#TP-205</strong>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #64748B;">Status Pengajuan:</span>
                <span style="background: #FEF3C7; color: #D97706; font-weight: 700; font-size: 0.74rem; padding: 3px 8px; border-radius: 4px;">MENUNGGU REVIEW ADMIN</span>
            </div>
        </div>

        <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
            <button type="button" onclick="closeSuccessModal()" class="btn-primary" style="padding: 11px 20px; border-radius: 8px; font-size: 0.88rem; min-height: 44px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; flex: 1; min-width: 140px;">
                <span>Selesai & Tutup</span>
            </button>
            <a href="{{ route('explore') }}" class="btn-outline-navy" style="padding: 11px 18px; border-radius: 8px; font-size: 0.88rem; min-height: 44px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; flex: 1; min-width: 140px;">
                <span>Jelajahi Properti</span>
            </a>
        </div>
    </div>
</div>
@endsection
