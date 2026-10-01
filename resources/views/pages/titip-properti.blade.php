@extends('layouts.app')

@section('title', 'Titip Properti - Kumala Property')

@section('content')
<div class="container" style="padding-top: 24px; padding-bottom: 90px;">

    <!-- Breadcrumbs -->
    <div class="breadcrumbs-bar" style="margin-bottom: 16px;">
        <ul class="breadcrumbs-list">
            <li class="breadcrumbs-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumbs-separator">&gt;</li>
            <li class="breadcrumbs-item active">Titip Properti</li>
        </ul>
    </div>

    <!-- Header Section -->
    <div style="margin-bottom: 40px; max-width: 880px;">
        <span class="badge-tag badge-tag-teal" style="margin-bottom: 16px;">LAYANAN KONSINYASI & PEMASARAN RESMI</span>
        <h1 style="font-size: clamp(2.2rem, 4vw, 3rem); color: var(--primary-navy); margin-bottom: 14px; line-height: 1.2; font-weight: 800;">
            Titipkan Properti Anda, Jangkau Pembeli & Penyewa Berkualitas
        </h1>
        <p style="font-size: 1.05rem; color: var(--text-muted); line-height: 1.6; margin-bottom: 24px;">
            Tim kurator Kumala Property siap mendampingi proses valuasi presisi, produksi visual arsitektural berkelas, hingga finalisasi legalitas transaksi tuntas tanpa biaya di muka.
        </p>

        <!-- Feature Badges -->
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <span class="titip-hero-badge">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>Valuasi & Konsultasi Gratis</span>
            </span>
            <span class="titip-hero-badge">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span>Jejaring Investor Terverifikasi</span>
            </span>
            <span class="titip-hero-badge">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>Transparan & Legalitas Aman BPN</span>
            </span>
        </div>
    </div>

    <!-- Main Layout: 2 Columns (Form Left, Guides Right) -->
    <div style="display: grid; grid-template-columns: 1fr 380px; gap: 40px; align-items: flex-start;">
        
        <!-- Left: Form Steps -->
        <form id="form-titip-properti" onsubmit="handleTitipSubmit(event)">
            @csrf

            <!-- ================= STEP 01 ================= -->
            <div class="step-card" id="step-01">
                <div class="step-header">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <span class="step-number">01</span>
                        <div>
                            <h3 class="step-title">Data Pemilik Properti</h3>
                            <p class="step-desc">Informasi identitas untuk verifikasi kepemilikan dan jadwal survei.</p>
                        </div>
                    </div>
                    <span class="step-badge-required">WAJIB DIISI</span>
                </div>

                <div style="margin-bottom: 18px;">
                    <label class="form-label-field">Nama Lengkap Pemilik / Kuasa Hukum *</label>
                    <input type="text" name="owner_name" class="form-input-field" placeholder="Contoh: Hendra Gunawan" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <div>
                        <label class="form-label-field">Nomor WhatsApp Aktif *</label>
                        <div style="display: flex; border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; background: #F8FAFC;">
                            <span style="background: #E2E8F0; color: #475569; font-weight: 700; font-size: 0.9rem; padding: 10px 14px; display: flex; align-items: center;">+62</span>
                            <input type="tel" name="owner_phone" style="width: 100%; padding: 10px 14px; background: transparent; font-size: 0.9rem; color: var(--primary-navy); font-weight: 500;" placeholder="812-8899-2345" required>
                        </div>
                        <span style="font-size: 0.72rem; color: var(--text-muted); margin-top: 4px; display: block;">Untuk konfirmasi & koordinasi survei tim</span>
                    </div>

                    <div>
                        <label class="form-label-field">Alamat Email Aktif *</label>
                        <input type="email" name="owner_email" class="form-input-field" placeholder="hendra@perusahaan.com" required>
                        <span style="font-size: 0.72rem; color: var(--text-muted); margin-top: 4px; display: block;">Untuk pengiriman laporan valuasi</span>
                    </div>
                </div>

                <div>
                    <label class="form-label-field">Status Kepemilikan *</label>
                    <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                        <label class="radio-card active">
                            <input type="radio" name="ownership_status" value="direct_owner" checked style="accent-color: #00B488;">
                            <span>Pemilik Langsung</span>
                        </label>
                        <label class="radio-card">
                            <input type="radio" name="ownership_status" value="agent_official" style="accent-color: #00B488;">
                            <span>Kuasa Jual Resmi</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- ================= STEP 02 ================= -->
            <div class="step-card" id="step-02">
                <div class="step-header">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <span class="step-number">02</span>
                        <div>
                            <h3 class="step-title">Detail & Status Properti</h3>
                            <p class="step-desc">Karakteristik pemasaran dan estimasi nilai aset.</p>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="form-label-field">Jenis Transaksi *</label>
                    <div style="display: flex; background: #F1F5F9; border-radius: 8px; padding: 4px; gap: 6px; max-width: 480px;">
                        <button type="button" class="btn-trx-tab active" onclick="setTitipTrx(this, 'Jual')">Dijual</button>
                        <button type="button" class="btn-trx-tab" onclick="setTitipTrx(this, 'Sewa')">Disewakan</button>
                        <button type="button" class="btn-trx-tab" onclick="setTitipTrx(this, 'Keduanya')">Bisa Jual / Sewa</button>
                    </div>
                    <input type="hidden" name="transaction_type" id="titip-trx-input" value="Jual">
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="form-label-field">Kategori Properti *</label>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <button type="button" class="category-chip active" onclick="selectCategory(this, 'Villa Mewah')">Villa Mewah</button>
                        <button type="button" class="category-chip" onclick="selectCategory(this, 'Rumah Tinggal')">Rumah Tinggal</button>
                        <button type="button" class="category-chip" onclick="selectCategory(this, 'Apartemen / Penthouse')">Apartemen / Penthouse</button>
                        <button type="button" class="category-chip" onclick="selectCategory(this, 'Tanah Komersial')">Tanah Komersial</button>
                        <button type="button" class="category-chip" onclick="selectCategory(this, 'Ruko / Shophouse')">Ruko / Shophouse</button>
                    </div>
                    <input type="hidden" name="property_category" id="titip-cat-input" value="Villa Mewah">
                </div>

                <div style="margin-bottom: 18px;">
                    <label class="form-label-field">Nama / Judul Properti *</label>
                    <input type="text" name="property_title" class="form-input-field" placeholder="Misal: Modern Tropical Villa di Canggu" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px;">
                    <div>
                        <label class="form-label-field">Provinsi & Kota / Kabupaten *</label>
                        <select name="city_region" class="form-input-field" required>
                            @if(!empty($activeRegions) && count($activeRegions) > 0)
                                @foreach($activeRegions as $reg)
                                    <option value="{{ $reg->city ?: $reg->name }}">{{ $reg->name }}</option>
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
                        <label class="form-label-field">Area / Kawasan Spesifik *</label>
                        <input type="text" name="specific_area" class="form-input-field" placeholder="Contoh: Berawa, Canggu atau Kemang Timur" required>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="form-label-field">Alamat Lengkap Properti *</label>
                    <textarea name="full_address" rows="2" class="form-input-field" placeholder="Nama jalan, nomor rumah/kavling, kompleks perumahan (dijaga kerahasiaannya oleh tim kami)" required></textarea>
                </div>

                <div style="background: #F8FAFC; border: 1.5px solid var(--border-color); border-radius: 12px; padding: 20px;">
                    <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 20px; align-items: flex-start;">
                        <div>
                            <label class="form-label-field">Nilai Penawaran / Harga yang Diharapkan *</label>
                            <div style="display: flex; border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; background: #FFFFFF;">
                                <span style="background: #E2E8F0; color: #475569; font-weight: 700; padding: 10px 14px; font-size: 0.95rem;">Rp</span>
                                <input type="text" id="input-price-raw" name="asking_price" style="width: 100%; padding: 10px 14px; font-size: 1.05rem; font-weight: 700; color: var(--primary-navy);" placeholder="15.000.000.000" oninput="updateTerbilang(this.value)" required>
                            </div>
                            <span id="price-terbilang" style="font-size: 0.75rem; color: #00B488; font-weight: 600; margin-top: 6px; display: block;">Terbilang: Lima Belas Miliar Rupiah</span>
                        </div>

                        <div>
                            <label class="form-label-field">Ketentuan Penawaran</label>
                            <div style="display: flex; flex-direction: column; gap: 8px; font-size: 0.85rem; color: #334155; margin-top: 6px;">
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="checkbox" name="is_negotiable" checked style="accent-color: #00B488;">
                                    <span>Bisa Nego</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="radio" name="term_rate" value="yearly" style="accent-color: #00B488;">
                                    <span>Per Tahun</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="radio" name="term_rate" value="monthly" style="accent-color: #00B488;">
                                    <span>Per Bulan</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= STEP 03 ================= -->
            <div class="step-card" id="step-03">
                <div class="step-header">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <span class="step-number">03</span>
                        <div>
                            <h3 class="step-title">Spesifikasi Teknis</h3>
                            <p class="step-desc">Dimensi ruang dan fasilitas unggulan aset.</p>
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 14px; margin-bottom: 22px;">
                    <div>
                        <label class="form-label-field">Luas Tanah</label>
                        <div style="display: flex; border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; background: #F8FAFC;">
                            <input type="number" name="land_size" value="450" class="form-input-field" style="border: none;" required>
                            <span style="padding: 10px 12px; background: #E2E8F0; font-size: 0.85rem; color: #475569; font-weight: 600;">m²</span>
                        </div>
                    </div>

                    <div>
                        <label class="form-label-field">Luas Bangunan</label>
                        <div style="display: flex; border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; background: #F8FAFC;">
                            <input type="number" name="building_size" value="380" class="form-input-field" style="border: none;" required>
                            <span style="padding: 10px 12px; background: #E2E8F0; font-size: 0.85rem; color: #475569; font-weight: 600;">m²</span>
                        </div>
                    </div>

                    <div>
                        <label class="form-label-field">Kamar Tidur</label>
                        <select name="bedrooms" class="form-input-field">
                            <option>1 KT</option>
                            <option>2 KT</option>
                            <option>3 KT</option>
                            <option selected>4 KT</option>
                            <option>5 KT</option>
                            <option>6+ KT</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label-field">Kamar Mandi</label>
                        <select name="bathrooms" class="form-input-field">
                            <option>1 KM</option>
                            <option>2 KM</option>
                            <option>3 KM</option>
                            <option selected>4 KM</option>
                            <option>5+ KM</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label-field" style="margin-bottom: 12px;">Fasilitas & Kelengkapan Dokumen</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                        <label class="checkbox-feature-item">
                            <input type="checkbox" name="facilities[]" value="pool" checked>
                            <span>Kolam Renang Privat</span>
                        </label>
                        <label class="checkbox-feature-item">
                            <input type="checkbox" name="facilities[]" value="furnished" checked>
                            <span>Fully Furnished</span>
                        </label>
                        <label class="checkbox-feature-item">
                            <input type="checkbox" name="facilities[]" value="carport" checked>
                            <span>Carport / Garasi</span>
                        </label>
                        <label class="checkbox-feature-item">
                            <input type="checkbox" name="facilities[]" value="ocean_view">
                            <span>Ocean View / View Lepas</span>
                        </label>
                        <label class="checkbox-feature-item">
                            <input type="checkbox" name="facilities[]" value="shm" checked>
                            <span>SHM (Hak Milik)</span>
                        </label>
                        <label class="checkbox-feature-item">
                            <input type="checkbox" name="facilities[]" value="pbg" checked>
                            <span>PBG / IMB Lengkap</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- ================= STEP 04 ================= -->
            <div class="step-card" id="step-04">
                <div class="step-header">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <span class="step-number">04</span>
                        <div>
                            <h3 class="step-title">Deskripsi & Nilai Tambah Properti</h3>
                            <p class="step-desc">Ceritakan keunikan arsitektur, lingkungan, atau potensi investasi properti Anda.</p>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 10px;">
                    <textarea id="titip-description" name="description" rows="5" class="form-input-field" placeholder="Jelaskan konsep hunian, kondisi bangunan, material unggulan, potensi investasi/ROI, atau fasilitas lingkungan terdekat (misal: 5 menit ke pantai, akses jalan 8 meter, pencahayaan alami optimal)..." oninput="updateWordCount(this.value)" style="line-height: 1.6;" required></textarea>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.78rem; color: var(--text-muted);">
                    <span>Minimal 50 kata untuk mempercepat proses kurasi calon pembeli.</span>
                    <strong id="word-counter-display">0 / 800 kata</strong>
                </div>
            </div>

            <!-- ================= STEP 05 ================= -->
            <div class="step-card" id="step-05">
                <div class="step-header">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <span class="step-number">05</span>
                        <div>
                            <h3 class="step-title">Dokumentasi Foto & Denah Properti</h3>
                            <p class="step-desc">Foto awal untuk tahap verifikasi kuratorial kami.</p>
                        </div>
                    </div>
                    <span class="step-badge-required">Min. 4 Foto</span>
                </div>

                <!-- Drag & Drop Uploader Box -->
                <div class="upload-dropzone" onclick="document.getElementById('file-upload-input').click()">
                    <input type="file" id="file-upload-input" name="photos[]" multiple accept="image/png, image/jpeg, image/webp" style="display: none;" onchange="handleFileSelect(this)">
                    <div style="width: 52px; height: 52px; border-radius: 50%; background: #E6FBF5; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; color: #00B488;">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                    </div>
                    <strong style="display: block; font-size: 1rem; color: var(--primary-navy); margin-bottom: 4px;">Tarik & lepas foto properti ke sini</strong>
                    <span style="font-size: 0.88rem; color: var(--accent-teal); font-weight: 600; text-decoration: underline; display: block; margin-bottom: 6px;">atau Klik untuk Jelajahi File dari Perangkat</span>
                    <span style="font-size: 0.78rem; color: #94A3B8;">Mendukung format JPG, PNG, WEBP (Maksimal 10 MB per berkas foto).</span>
                </div>

                <!-- Preview Thumbnail Grid -->
                <div style="margin-top: 20px;">
                    <span style="display: block; font-size: 0.82rem; font-weight: 700; color: #475569; margin-bottom: 12px;">Pratinjau Berkas Terpilih (3 Foto)</span>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 14px;">
                        
                        <div style="position: relative; height: 110px; border-radius: 10px; overflow: hidden; background: #0E2238;">
                            <img src="https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=400&q=80" alt="Foto Utama" style="width: 100%; height: 100%; object-fit: cover;">
                            <div style="position: absolute; top: 8px; left: 8px; background: #0E2238; color: #FFFFFF; font-size: 0.65rem; font-weight: 700; padding: 2px 6px; border-radius: 4px; letter-spacing: 0.4px;">
                                FOTO UTAMA
                            </div>
                        </div>

                        <div style="position: relative; height: 110px; border-radius: 10px; overflow: hidden; background: #0E2238;">
                            <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=400&q=80" alt="Living Room" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>

                        <div style="position: relative; height: 110px; border-radius: 10px; overflow: hidden; background: #0E2238;">
                            <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=400&q=80" alt="Kamar Utama" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>

                    </div>
                </div>
            </div>

            <!-- Legal Agreement & Disclaimers -->
            <div style="background: #FFFFFF; border-radius: 14px; border: 1px solid var(--border-color); padding: 24px; margin-bottom: 24px;">
                <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer; margin-bottom: 16px;">
                    <input type="checkbox" name="legal_consent" required style="width: 18px; height: 18px; margin-top: 3px; accent-color: #00B488;">
                    <span style="font-size: 0.88rem; color: #334155; line-height: 1.5;">
                        Saya menyatakan data yang diisi adalah benar, berstatus sah secara hukum, dan saya memiliki kewenangan penuh atau kuasa resmi untuk menitipkan pemasaran unit properti ini tanpa sengketa hak milik.
                    </span>
                </label>

                <div style="background: #EBF1F5; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; gap: 12px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0D9488" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span style="font-size: 0.8rem; color: #1E293B; line-height: 1.4;">
                        <strong>Bebas biaya pendaftaran.</strong> Komisi profesional standar (2–3%) hanya berlaku saat transaksi tuntas ditandatangani di hadapan PPAT/Notaris.
                    </span>
                </div>
            </div>

            <!-- Submit Action Toolbar -->
            <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                <button type="submit" class="btn-primary" style="padding: 14px 34px; font-size: 1rem; border-radius: 10px;" id="btn-submit-titip">
                    <span>Kirim Data Properti untuk Review</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
                <a href="https://wa.me/6281288992345?text=Halo%20Advisor%20Kumala,%20saya%20ingin%20bertanya%20seputar%20konsinyasi%20titip%20properti." target="_blank" class="btn-outline-navy" style="padding: 14px 24px; border-radius: 10px; font-size: 0.95rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span>Tanya Advisor via WA</span>
                </a>
            </div>

        </form>

        <!-- Right: Sidebar Information & Curation Guidelines -->
        <div style="position: sticky; top: 100px;">
            
            <!-- Card 1: Proses Konsinyasi Kami -->
            <div class="sidebar-guide-card" style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <h3 style="font-size: 1.25rem; color: var(--primary-navy);">Proses Konsinyasi Kami</h3>
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #E6FBF5; display: flex; align-items: center; justify-content: center; color: var(--accent-teal);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                </div>

                <div class="roadmap-item">
                    <span class="roadmap-num">1</span>
                    <div>
                        <strong class="roadmap-title">Submit Data Properti</strong>
                        <p class="roadmap-desc">Isi formulir ringkas spesifikasi unit dan unggah foto dokumentasi awal.</p>
                    </div>
                </div>

                <div class="roadmap-item">
                    <span class="roadmap-num">2</span>
                    <div>
                        <strong class="roadmap-title">Kurasi & Valuasi Objektif</strong>
                        <p class="roadmap-desc">Tim appraiser kami menganalisis Nilai Pasar Terkini (CMA) dan kelayakan legalitas BPN.</p>
                    </div>
                </div>

                <div class="roadmap-item">
                    <span class="roadmap-num">3</span>
                    <div>
                        <strong class="roadmap-title">Pemotretan & Listing Eksklusif</strong>
                        <p class="roadmap-desc">Produksi fotografi arsitektur, video tur, serta promosi terarah ke investor premium.</p>
                    </div>
                </div>

                <div class="roadmap-item">
                    <span class="roadmap-num">4</span>
                    <div>
                        <strong class="roadmap-title">Closing & Pendampingan Legal</strong>
                        <p class="roadmap-desc">Fasilitasi negosiasi, penerbitan PPJB, hingga penandatanganan AJB resmi PPAT.</p>
                    </div>
                </div>
            </div>

            <!-- Card 2: Standar Kurasi Kumala -->
            <div class="sidebar-guide-card" style="background: #FAF8F5; border-color: #E2D9CE;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px; color: #92400E;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 2 3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <strong style="font-size: 0.95rem;">Standar Kurasi Kumala</strong>
                </div>

                <p style="font-size: 0.82rem; color: #64748B; line-height: 1.5; margin-bottom: 16px;">
                    Demi menjaga kepercayaan jejaring investor kami, seluruh properti yang dititipkan akan diverifikasi terhadap:
                </p>

                <ul style="display: flex; flex-direction: column; gap: 10px; font-size: 0.82rem; color: #334155;">
                    <li style="display: flex; align-items: flex-start; gap: 8px;">
                        <span style="color: #059669; font-weight: 700;">✓</span>
                        <span>Sertifikat Hak Milik (SHM) / HGB murni tanpa blokir</span>
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 8px;">
                        <span style="color: #059669; font-weight: 700;">✓</span>
                        <span>Bebas sengketa waris atau jaminan sita lelang bank</span>
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 8px;">
                        <span style="color: #059669; font-weight: 700;">✓</span>
                        <span>Akses jalan sah dan peruntukan zonasi tata kota (PBG)</span>
                    </li>
                </ul>
            </div>

        </div>

    </div>

</div>
@endsection

@section('styles')
<style>
.titip-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #FFFFFF;
    border: 1px solid var(--border-color);
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--primary-navy);
    box-shadow: var(--shadow-sm);
}
.titip-hero-badge svg {
    color: var(--accent-teal);
}

.step-card {
    background: #FFFFFF;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    padding: 28px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
}

.step-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 22px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border-light);
}

.step-number {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #0E2238;
    color: #FFFFFF;
    font-weight: 800;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.step-title {
    font-size: 1.25rem;
    color: var(--primary-navy);
    line-height: 1.2;
}

.step-desc {
    font-size: 0.82rem;
    color: var(--text-muted);
    margin-top: 2px;
}

.step-badge-required {
    background: #E2E8F0;
    color: #475569;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 6px;
    letter-spacing: 0.5px;
}

.radio-card {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: #F8FAFC;
    border: 1px solid var(--border-color);
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--primary-navy);
    cursor: pointer;
    transition: var(--transition-fast);
}
.radio-card:hover {
    border-color: var(--accent-teal);
}

.btn-trx-tab {
    flex: 1;
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #475569;
    transition: var(--transition-fast);
}
.btn-trx-tab.active {
    background: #0E2238;
    color: #FFFFFF;
}

.category-chip {
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
.category-chip:hover {
    border-color: #94A3B8;
}
.category-chip.active {
    background: #0E2238;
    color: #FFFFFF;
    border-color: #0E2238;
}

.checkbox-feature-item {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #F8FAFC;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--primary-navy);
    cursor: pointer;
    transition: var(--transition-fast);
}
.checkbox-feature-item:hover {
    border-color: var(--accent-teal);
}
.checkbox-feature-item input {
    accent-color: #00B488;
}

.upload-dropzone {
    border: 2px dashed #CBD5E1;
    border-radius: 12px;
    padding: 36px 20px;
    text-align: center;
    background: #F8FAFC;
    cursor: pointer;
    transition: var(--transition-fast);
}
.upload-dropzone:hover {
    border-color: var(--accent-teal);
    background: #F0FDF4;
}

.sidebar-guide-card {
    background: #FFFFFF;
    border-radius: 16px;
    border: 1px solid var(--border-color);
    padding: 26px;
    box-shadow: var(--shadow-sm);
}

.roadmap-item {
    display: flex;
    gap: 14px;
    margin-bottom: 20px;
    position: relative;
}
.roadmap-item:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 30px;
    left: 13px;
    width: 2px;
    height: calc(100% - 10px);
    background: #E2E8F0;
}
.roadmap-num {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #0E2238;
    color: #FFFFFF;
    font-size: 0.8rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.roadmap-title {
    display: block;
    font-size: 0.95rem;
    color: var(--primary-navy);
    margin-bottom: 3px;
}
.roadmap-desc {
    font-size: 0.8rem;
    color: var(--text-muted);
    line-height: 1.45;
}

@media (max-width: 1024px) {
    div[style*="grid-template-columns: 1fr 380px"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection

@section('scripts')
<script>
function setTitipTrx(btn, val) {
    document.querySelectorAll('.btn-trx-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('titip-trx-input').value = val;
}

function selectCategory(btn, val) {
    document.querySelectorAll('.category-chip').forEach(b => b.classList.remove('active'));
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
        if (num >= 1000000000) {
            const m = (num / 1000000000).toLocaleString('id-ID');
            display.textContent = `Terbilang: Rp ${m} Miliar`;
        } else if (num >= 1000000) {
            const jt = (num / 1000000).toLocaleString('id-ID');
            display.textContent = `Terbilang: Rp ${jt} Juta`;
        } else {
            display.textContent = `Terbilang: Rp ${num.toLocaleString('id-ID')}`;
        }
    }
}

function updateWordCount(text) {
    const words = text.trim().split(/\s+/).filter(w => w.length > 0).length;
    document.getElementById('word-counter-display').textContent = `${words} / 800 kata`;
}

function handleFileSelect(input) {
    if (input.files && input.files.length > 0) {
        showToast(`${input.files.length} foto berhasil dipilih.`);
    }
}

function handleTitipSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('btn-submit-titip');
    btn.disabled = true;
    btn.innerHTML = '<span>Memverifikasi & Mengirim Data...</span>';

    const formData = new FormData(form);

    fetch('{{ route('titip-properti.submit') }}', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            let errorMsg = data.message || 'Terjadi kesalahan validasi data.';
            if (data.errors) {
                const firstErr = Object.values(data.errors)[0];
                if (firstErr && firstErr[0]) errorMsg = firstErr[0];
            }
            throw new Error(errorMsg);
        }
        return data;
    })
    .then(data => {
        showToast(data.message || 'Pengajuan konsinyasi properti Anda berhasil dikirim!');
        form.reset();
        btn.disabled = false;
        btn.innerHTML = `<span>Pengajuan Berhasil Dikirim ✓</span>`;
    })
    .catch(err => {
        showToast(err.message || 'Gagal mengirim pengajuan. Silakan periksa formulir.');
        btn.disabled = false;
        btn.innerHTML = `<span>Kirim Data Properti untuk Review</span><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>`;
    });
}
</script>
@endsection
