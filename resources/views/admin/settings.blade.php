@extends('layouts.admin')

@section('title', 'Pengaturan Sistem & Master Data - Kumala Admin')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Header Section -->
    <div>
        <div style="display: inline-flex; align-items: center; gap: 6px; color: #00838F; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.6px; text-transform: uppercase; margin-bottom: 6px;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
            <span>KONFIGURASI INTI PORTAL</span>
        </div>
        <h1 style="font-size: 1.75rem; color: #0E1E2E; margin: 0 0 4px 0; font-weight: 800; letter-spacing: -0.01em;">
            Pengaturan Sistem &amp; Master Data
        </h1>
        <p style="font-size: 0.85rem; color: #64748B; margin: 0; max-width: 780px; line-height: 1.45;">
            Kelola kategori properti, wilayah operasional, kontak resmi kantor, dan pengaturan integrasi publik secara terpusat untuk menjaga konsistensi ekosistem Kumala Realty.
        </p>
    </div>

    <!-- Navigation Tabs -->
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <button type="button" class="settings-tab-btn active">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            <span>Kategori &amp; Tipe Properti</span>
        </button>
        <button type="button" class="settings-tab-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/></svg>
            <span>Wilayah &amp; Kota Operasional</span>
        </button>
        <button type="button" class="settings-tab-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            <span>Profil Kantor &amp; Kontak Resmi</span>
        </button>
        <button type="button" class="settings-tab-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="18" y1="20" y2="10"/><line x1="12" x2="12" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="14"/></svg>
            <span>Parameter Bisnis &amp; Komisi</span>
        </button>
    </div>

    <!-- Section 1: Master Kategori & Sub-tipe Properti -->
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 22px 24px; box-shadow: 0 1px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
            <div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 26px; height: 26px; border-radius: 6px; background: #E0F7FA; color: #00838F; display: flex; align-items: center; justify-content: center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    </div>
                    <h3 style="font-size: 1.05rem; font-weight: 800; color: #0E1E2E; margin: 0;">Master Kategori &amp; Sub-tipe Properti</h3>
                </div>
                <p style="font-size: 0.78rem; color: #64748B; margin: 4px 0 0 34px;">
                    Ditampilkan langsung pada formulir penambahan unit, syndication feed, dan filter pencarian publik.
                </p>
            </div>

            <button type="button" class="btn-cyan-outline" onclick="showToast('Membuka modal tambah kategori baru...')">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>Tambah Kategori / Tipe Baru</span>
            </button>
        </div>

        <!-- Table -->
        <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table style="width: 100%; min-width: 740px; border-collapse: collapse; text-align: left; font-size: 0.82rem;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; color: #475569; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.6px; text-transform: uppercase;">
                        <th style="padding: 12px 16px; width: 260px;">KATEGORI INDUK</th>
                        <th style="padding: 12px 16px;">SUB-TIPE TERDAFTAR</th>
                        <th style="padding: 12px 16px; width: 180px;">LISTING AKTIF</th>
                        <th style="padding: 12px 16px; width: 100px; text-align: center;">TINDAKAN</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1 -->
                    <tr class="settings-table-row">
                        <td style="padding: 14px 16px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 28px; height: 28px; border-radius: 6px; background: #E0F2FE; color: #0369A1; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                                </div>
                                <div>
                                    <strong style="color: #0E1E2E; font-size: 0.88rem; display: block;">Rumah Tinggal</strong>
                                    <span style="font-size: 0.72rem; color: #64748B;">Sektor Residensial Primer</span>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 14px 16px;">
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <span class="subtipe-pill">Minimalis</span>
                                <span class="subtipe-pill">Mewah</span>
                                <span class="subtipe-pill">Cluster</span>
                                <span class="subtipe-pill">Townhouse</span>
                            </div>
                        </td>
                        <td style="padding: 14px 16px;">
                            <strong style="color: #0E1E2E; font-size: 0.88rem; display: block;">84 Unit</strong>
                            <span style="font-size: 0.72rem; color: #00838F; font-weight: 600;">62% Total Portofolio</span>
                        </td>
                        <td style="padding: 14px 16px; text-align: center;">
                            <div style="display: inline-flex; gap: 8px;">
                                <button type="button" class="btn-icon-subtle" title="Edit" onclick="showToast('Edit kategori Rumah Tinggal...')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                </button>
                                <button type="button" class="btn-icon-subtle" title="Tambah Subtipe" onclick="showToast('Tambah sub-tipe Rumah Tinggal...')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2 -->
                    <tr class="settings-table-row">
                        <td style="padding: 14px 16px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 28px; height: 28px; border-radius: 6px; background: #FEF3C7; color: #B45309; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 7h20"/></svg>
                                </div>
                                <div>
                                    <strong style="color: #0E1E2E; font-size: 0.88rem; display: block;">Ruko &amp; Komersial</strong>
                                    <span style="font-size: 0.72rem; color: #64748B;">Sektor Retail &amp; Workspace</span>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 14px 16px;">
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <span class="subtipe-pill">Ruko 2 Lantai</span>
                                <span class="subtipe-pill">Ruko 3 Lantai</span>
                                <span class="subtipe-pill">Gedung Usaha</span>
                            </div>
                        </td>
                        <td style="padding: 14px 16px;">
                            <strong style="color: #0E1E2E; font-size: 0.88rem; display: block;">32 Unit</strong>
                            <span style="font-size: 0.72rem; color: #00838F; font-weight: 600;">24% Total Portofolio</span>
                        </td>
                        <td style="padding: 14px 16px; text-align: center;">
                            <div style="display: inline-flex; gap: 8px;">
                                <button type="button" class="btn-icon-subtle" title="Edit" onclick="showToast('Edit kategori Ruko & Komersial...')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                </button>
                                <button type="button" class="btn-icon-subtle" title="Tambah Subtipe" onclick="showToast('Tambah sub-tipe...')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 3 -->
                    <tr class="settings-table-row">
                        <td style="padding: 14px 16px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 28px; height: 28px; border-radius: 6px; background: #EDE9FE; color: #6D28D9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><line x1="9" y1="22" x2="9" y2="22.01"/><line x1="15" y1="22" x2="15" y2="22.01"/><line x1="9" y1="6" x2="9" y2="6.01"/><line x1="15" y1="6" x2="15" y2="6.01"/><line x1="9" y1="10" x2="9" y2="10.01"/><line x1="15" y1="10" x2="15" y2="10.01"/><line x1="9" y1="14" x2="9" y2="14.01"/><line x1="15" y1="14" x2="15" y2="14.01"/><line x1="9" y1="18" x2="9" y2="18.01"/><line x1="15" y1="18" x2="15" y2="18.01"/></svg>
                                </div>
                                <div>
                                    <strong style="color: #0E1E2E; font-size: 0.88rem; display: block;">Apartemen &amp; Kondominium</strong>
                                    <span style="font-size: 0.72rem; color: #64748B;">Hunian Vertikal Urban</span>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 14px 16px;">
                            <div style="display: gap: 6px; flex-wrap: wrap;">
                                <span class="subtipe-pill">Studio</span>
                                <span class="subtipe-pill">1BR</span>
                                <span class="subtipe-pill">2BR</span>
                                <span class="subtipe-pill">Penthouse</span>
                            </div>
                        </td>
                        <td style="padding: 14px 16px;">
                            <strong style="color: #0E1E2E; font-size: 0.88rem; display: block;">18 Unit</strong>
                            <span style="font-size: 0.72rem; color: #00838F; font-weight: 600;">13% Total Portofolio</span>
                        </td>
                        <td style="padding: 14px 16px; text-align: center;">
                            <div style="display: inline-flex; gap: 8px;">
                                <button type="button" class="btn-icon-subtle" title="Edit" onclick="showToast('Edit kategori Apartemen...')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                </button>
                                <button type="button" class="btn-icon-subtle" title="Tambah Subtipe" onclick="showToast('Tambah sub-tipe...')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 4 -->
                    <tr class="settings-table-row">
                        <td style="padding: 14px 16px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 28px; height: 28px; border-radius: 6px; background: #DCFCE7; color: #15803D; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg>
                                </div>
                                <div>
                                    <strong style="color: #0E1E2E; font-size: 0.88rem; display: block;">Tanah &amp; Kavling</strong>
                                    <span style="font-size: 0.72rem; color: #64748B;">Lahan Terbuka &amp; Plot Strategis</span>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 14px 16px;">
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <span class="subtipe-pill">Komersial</span>
                                <span class="subtipe-pill">Residensial</span>
                                <span class="subtipe-pill">Industri</span>
                            </div>
                        </td>
                        <td style="padding: 14px 16px;">
                            <strong style="color: #0E1E2E; font-size: 0.88rem; display: block;">14 Unit</strong>
                            <span style="font-size: 0.72rem; color: #00838F; font-weight: 600;">11% Total Portofolio</span>
                        </td>
                        <td style="padding: 14px 16px; text-align: center;">
                            <div style="display: inline-flex; gap: 8px;">
                                <button type="button" class="btn-icon-subtle" title="Edit" onclick="showToast('Edit kategori Tanah & Kavling...')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                </button>
                                <button type="button" class="btn-icon-subtle" title="Tambah Subtipe" onclick="showToast('Tambah sub-tipe...')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Section 1 Footer -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px; padding-top: 14px; border-top: 1px solid #F1F5F9; font-size: 0.76rem; flex-wrap: wrap; gap: 10px;">
            <div style="color: #64748B; display: flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                <span>Perubahan terakhir disimpan oleh Admin Siti pada <strong>14 Mar 2025, 10:15 WIB</strong></span>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="button" class="btn-settings-cancel" onclick="showToast('Perubahan dibatalkan.')">Batal</button>
                <button type="button" class="btn-settings-save" onclick="showToast('Sukses! Seluruh konfigurasi master disimpan ke database.')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    <span>Simpan Seluruh Konfigurasi Master</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Section 2: Wilayah Operasional -->
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 22px 24px; box-shadow: 0 1px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
            <div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 26px; height: 26px; border-radius: 6px; background: #E0F7FA; color: #00838F; display: flex; align-items: center; justify-content: center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/></svg>
                    </div>
                    <h3 style="font-size: 1.05rem; font-weight: 800; color: #0E1E2E; margin: 0;">Wilayah Operasional</h3>
                </div>
                <p style="font-size: 0.78rem; color: #64748B; margin: 4px 0 0 34px;">
                    Pilihan dropdown wilayah pada bilah pencarian publik dan geolokasi agen listing.
                </p>
            </div>

            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 0.74rem; font-weight: 700; color: #00838F; background: #E0F7FA; padding: 3px 8px; border-radius: 4px;">4 Area</span>
                <button type="button" class="btn-cyan-outline" onclick="showToast('Membuka form tambah wilayah operasional...')">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    <span>Tambah Wilayah Baru</span>
                </button>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;" id="region-grid">
            <!-- Region 1 -->
            <div class="region-box">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <strong style="color: #0E1E2E; font-size: 0.88rem;">Jakarta Selatan</strong>
                    <span class="region-active-pill">● Aktif</span>
                </div>
                <p style="font-size: 0.74rem; color: #64748B; margin: 0;">
                    Kebayoran Baru, Pondok Indah, Cilandak, Kemang, Senopati
                </p>
            </div>

            <!-- Region 2 -->
            <div class="region-box">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <strong style="color: #0E1E2E; font-size: 0.88rem;">Tangerang Selatan</strong>
                    <span class="region-active-pill">● Aktif</span>
                </div>
                <p style="font-size: 0.74rem; color: #64748B; margin: 0;">
                    BSD City, Bintaro Jaya, Alam Sutera, Serpong, Pamulang
                </p>
            </div>

            <!-- Region 3 -->
            <div class="region-box">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <strong style="color: #0E1E2E; font-size: 0.88rem;">Jakarta Barat</strong>
                    <span class="region-active-pill">● Aktif</span>
                </div>
                <p style="font-size: 0.74rem; color: #64748B; margin: 0;">
                    Puri Indah, Kebon Jeruk, Kedoya, Tomang
                </p>
            </div>

            <!-- Region 4 -->
            <div class="region-box">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <strong style="color: #0E1E2E; font-size: 0.88rem;">Bandung &amp; Sekitarnya</strong>
                    <span class="region-active-pill">● Aktif</span>
                </div>
                <p style="font-size: 0.74rem; color: #64748B; margin: 0;">
                    Dago Pakar, Buah Batu, Setiabudi, Sukajadi
                </p>
            </div>
        </div>
    </div>

    <!-- Section 3: Info Kontak Resmi Kantor (Tersambung Langsung ke Web Publik) -->
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 22px 24px; box-shadow: 0 1px 4px rgba(0,0,0,0.02);">
        <form action="{{ route('admin.settings.save') }}" method="POST">
            @csrf
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 26px; height: 26px; border-radius: 6px; background: #E0F7FA; color: #00838F; display: flex; align-items: center; justify-content: center;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <h3 style="font-size: 1.05rem; font-weight: 800; color: #0E1E2E; margin: 0;">Info Kontak Resmi Kantor</h3>
                    </div>
                    <p style="font-size: 0.78rem; color: #64748B; margin: 4px 0 0 34px;">
                        Perubahan di sini langsung tersinkronisasi ke seluruh portal publik (header navbar, footer, tombol WhatsApp properti, dan detail concierge).
                    </p>
                </div>

                <button type="submit" class="btn-deck-approve" style="background: #00838F; color: #fff; padding: 7px 18px; border: none; border-radius: 6px; font-size: 0.8rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Simpan &amp; Sinkronkan Kontak</span>
                </button>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;" id="contact-grid">
                
                <!-- WhatsApp -->
                <div class="contact-box" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 12px 14px;">
                    <span class="contact-box-label" style="font-size: 0.72rem; font-weight: 700; color: #475569; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#00838F" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        Nomor WhatsApp Hotline Admin
                    </span>
                    <input type="text" name="hotline_wa" value="{{ $settings['hotline_wa'] ?? '+62 812-8888-2900' }}" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px; font-weight: 700; color: #0E1E2E; font-size: 0.9rem;">
                    <span style="font-size: 0.72rem; color: #94A3B8; display: block; margin-top: 4px;">Penerima permohonan minat beli, survei, dan notifikasi konsinyasi baru.</span>
                </div>

                <!-- Email CS -->
                <div class="contact-box" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 12px 14px;">
                    <span class="contact-box-label" style="font-size: 0.72rem; font-weight: 700; color: #475569; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#00838F" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        Email Resmi Customer Care
                    </span>
                    <input type="email" name="email_cs" value="{{ $settings['email_cs'] ?? 'cs@kumalaproperty.co.id' }}" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px; font-weight: 700; color: #0E1E2E; font-size: 0.9rem;">
                    <span style="font-size: 0.72rem; color: #94A3B8; display: block; margin-top: 4px;">Email publik untuk pertanyaan umum klien portal.</span>
                </div>

                <!-- Email Bisnis -->
                <div class="contact-box" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 12px 14px;">
                    <span class="contact-box-label" style="font-size: 0.72rem; font-weight: 700; color: #475569; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#00838F" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        Email Korespondensi Bisnis
                    </span>
                    <input type="email" name="email_business" value="{{ $settings['email_business'] ?? 'info@kumalaproperty.co.id' }}" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px; font-weight: 700; color: #0E1E2E; font-size: 0.9rem;">
                    <span style="font-size: 0.72rem; color: #94A3B8; display: block; margin-top: 4px;">Penerima penawaran kemitraan notaris &amp; korporat.</span>
                </div>

                <!-- Jam Kerja -->
                <div class="contact-box" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 12px 14px;">
                    <span class="contact-box-label" style="font-size: 0.72rem; font-weight: 700; color: #475569; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#00838F" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Jam Operasional Kantor
                    </span>
                    <input type="text" name="office_hours" value="{{ $settings['office_hours'] ?? 'Senin – Sabtu: 08:30 – 18:00 WIB' }}" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px; font-weight: 700; color: #0E1E2E; font-size: 0.9rem;">
                    <span style="font-size: 0.72rem; color: #94A3B8; display: block; margin-top: 4px;">Tampil di footer dan balasan concierge.</span>
                </div>

                <!-- Alamat Kantor (Full Span) -->
                <div class="contact-box" style="grid-column: 1 / -1; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 12px 14px;">
                    <span class="contact-box-label" style="font-size: 0.72rem; font-weight: 700; color: #475569; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#00838F" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Alamat Kantor Pusat
                    </span>
                    <input type="text" name="address" value="{{ $settings['address'] ?? 'Kumala Tower Lt. 5, Jl. TB Simatupang No. 18, Jakarta Selatan 12430' }}" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px; font-weight: 700; color: #0E1E2E; font-size: 0.9rem;">
                </div>

            </div>
        </form>

        <!-- Social Media Badges -->
        <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid #F1F5F9;">
            <span style="font-size: 0.72rem; font-weight: 700; color: #64748B; display: block; margin-bottom: 8px;">Tautan Media Sosial Resmi</span>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;" id="social-grid">
                <div class="social-pill-box">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#00838F" stroke-width="2"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                    <div>
                        <span style="font-size: 0.68rem; color: #94A3B8; display: block;">Instagram</span>
                        <strong style="font-size: 0.78rem; color: #0E1E2E;">{{ $settings['social_instagram'] ?? '@kumala.realty' }}</strong>
                    </div>
                </div>
                <div class="social-pill-box">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#00838F" stroke-width="2"><path d="m22 8-6 4 6 4V8Z"/><rect width="14" height="12" x="2" y="6" rx="2"/></svg>
                    <div>
                        <span style="font-size: 0.68rem; color: #94A3B8; display: block;">YouTube Virtual Tour</span>
                        <strong style="font-size: 0.78rem; color: #0E1E2E;">{{ $settings['social_youtube'] ?? 'Kumala Property Official' }}</strong>
                    </div>
                </div>
                <div class="social-pill-box">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#00838F" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    <div>
                        <span style="font-size: 0.68rem; color: #94A3B8; display: block;">Facebook Page</span>
                        <strong style="font-size: 0.78rem; color: #0E1E2E;">{{ $settings['social_facebook'] ?? 'Kumala Realty Indonesia' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Wilayah Operasional -->
        <div id="tab-wilayah" class="settings-pane" style="display: none;">
            <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; padding: 26px; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                    <div>
                        <h3 style="font-size: 1.15rem; color: var(--primary-navy); font-weight: 700;">Wilayah & Kota Jangkauan Layanan</h3>
                        <p style="font-size: 0.8rem; color: var(--text-muted);">Wilayah yang tampil pada filter pencarian publik dan form konsinyasi.</p>
                    </div>
                    <button type="button" class="btn-primary" style="padding: 8px 14px; font-size: 0.8rem; border-radius: 6px;" onclick="openRegionModal()">
                        + Tambah Wilayah
                    </button>
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach($regions as $reg)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; background: #F8FAFC; border-radius: 8px; border: 1px solid var(--border-light);">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent-teal)" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <strong style="color: var(--primary-navy); font-size: 0.92rem;">{{ $reg['name'] }}</strong>
                        </div>

                        <div style="display: flex; align-items: center; gap: 16px;">
                            <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $reg['listings'] }} Listing Terdaftar</span>
                            <label style="display: flex; align-items: center; gap: 6px; font-size: 0.78rem; cursor: pointer;">
                                <input type="checkbox" {{ $reg['active'] ? 'checked' : '' }} onchange="toggleRegionActive('{{ $reg['name'] }}', this)" style="accent-color: #00B488;">
                                <span style="color: {{ $reg['active'] ? '#059669' : '#94A3B8' }}; font-weight: 600;">{{ $reg['active'] ? 'Aktif' : 'Non-aktif' }}</span>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Tab 3: Profil Kantor & Kontak Resmi -->
        <div id="tab-profil" class="settings-pane" style="display: none;">
            <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; padding: 26px; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
                <h3 style="font-size: 1.15rem; color: var(--primary-navy); font-weight: 700; margin-bottom: 18px;">Informasi Kontak & Jam Kerja Kantor</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                    <div>
                        <label class="form-label-field">WhatsApp Hotline Client Care (Primary) *</label>
                        <input type="text" name="hotline_admin" class="form-input-field" value="{{ $settings['hotline_admin'] ?? '+62 812-8899-2345' }}">
                    </div>

                    <div>
                        <label class="form-label-field">Email Resmi Korespondensi *</label>
                        <input type="email" name="email_business" class="form-input-field" value="{{ $settings['email_business'] ?? 'concierge@kumalaproperty.id' }}">
                    </div>
                </div>

                <div style="margin-bottom: 18px;">
                    <label class="form-label-field">Alamat Kantor Pusat Jakarta</label>
                    <input type="text" name="hq_address" class="form-input-field" value="{{ $settings['hq_address'] ?? 'Kumala Tower Lt. 18, Jl. TB Simatupang No. 88, Cilandak, Jakarta Selatan 12430' }}">
                </div>

                <div style="margin-bottom: 18px;">
                    <label class="form-label-field">Alamat Kantor Perwakilan Regional Bali</label>
                    <input type="text" name="branch_bali_address" class="form-input-field" value="{{ $settings['branch_bali_address'] ?? 'Sunset Boulevard Suites Kav. 12, Jl. Sunset Road No. 42, Kuta, Badung, Bali 80361' }}">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
                    <div>
                        <label class="form-label-field">Tautan Instagram Resmi</label>
                        <input type="text" name="instagram_handle" class="form-input-field" value="{{ $settings['instagram_handle'] ?? 'https://instagram.com/kumalaproperty.luxury' }}">
                    </div>

                    <div>
                        <label class="form-label-field">Tautan Saluran YouTube Virtual Tour</label>
                        <input type="text" name="youtube_channel" class="form-input-field" value="{{ $settings['youtube_channel'] ?? 'https://youtube.com/@KumalaPropertyTours' }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 4: Parameter Bisnis & Notifikasi -->
        <div id="tab-parameter" class="settings-pane" style="display: none;">
            <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; padding: 26px; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
                <h3 style="font-size: 1.15rem; color: var(--primary-navy); font-weight: 700; margin-bottom: 18px;">Otomatisasi Sistem & Ketentuan Komisi</h3>

                <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px;">
                    <label style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; background: #F8FAFC; border-radius: 8px; border: 1px solid var(--border-light); cursor: pointer;">
                        <div>
                            <strong style="color: var(--primary-navy); font-size: 0.9rem; display: block;">Notifikasi Email Pengajuan Titip Properti Otomatis</strong>
                            <span style="font-size: 0.78rem; color: var(--text-muted);">Kirimkan ringkasan berkas ke kurator segera setelah form online disubmit.</span>
                        </div>
                        <input type="checkbox" name="notify_new_submission" value="1" {{ ($settings['notify_new_submission'] ?? '1') == '1' ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: #00B488;">
                    </label>

                    <label style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; background: #F8FAFC; border-radius: 8px; border: 1px solid var(--border-light); cursor: pointer;">
                        <div>
                            <strong style="color: var(--primary-navy); font-size: 0.9rem; display: block;">Forwarding WhatsApp Instan untuk Inquiry VIP</strong>
                            <span style="font-size: 0.78rem; color: var(--text-muted);">Teruskan formulir minat beli langsung ke WhatsApp on-duty Private Concierge.</span>
                        </div>
                        <input type="checkbox" name="notify_instant_whatsapp" value="1" {{ ($settings['notify_instant_whatsapp'] ?? '1') == '1' ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: #00B488;">
                    </label>

                    <label style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; background: #F8FAFC; border-radius: 8px; border: 1px solid var(--border-light); cursor: pointer;">
                        <div>
                            <strong style="color: var(--primary-navy); font-size: 0.9rem; display: block;">Watermark Otomatis Logo Kumala pada Foto Listing</strong>
                            <span style="font-size: 0.78rem; color: var(--text-muted);">Menerapkan perlindungan hak cipta gambar untuk mencegah duplikasi pihak ketiga.</span>
                        </div>
                        <input type="checkbox" name="auto_watermark" value="1" {{ ($settings['auto_watermark'] ?? '1') == '1' ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: #00B488;">
                    </label>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; padding-top: 18px; border-top: 1px solid var(--border-light);">
                    <div>
                        <label class="form-label-field">Tarif Standar Komisi Penjualan (%)</label>
                        <input type="number" step="0.1" name="standard_commission_pct" class="form-input-field" value="{{ $settings['standard_commission_pct'] ?? '2.5' }}">
                    </div>

                    <div>
                        <label class="form-label-field">Tarif Standar Komisi Sewa (%)</label>
                        <input type="number" step="0.1" name="standard_commission_rent_pct" class="form-input-field" value="{{ $settings['standard_commission_rent_pct'] ?? '5.0' }}">
                    </div>
                </div>
            </div>
        </div>

    </form>

    <!-- Modal Tambah Kategori -->
    <div id="modalCategory" style="display: none; position: fixed; inset: 0; background: rgba(14, 34, 56, 0.7); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #FFFFFF; border-radius: 12px; max-width: 440px; width: 100%; padding: 24px; box-shadow: var(--shadow-lg);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 1.15rem; color: var(--primary-navy); font-weight: 700;">Tambah Kategori Baru</h3>
                <button type="button" onclick="closeCategoryModal()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #94A3B8;">&times;</button>
            </div>
            <form action="{{ route('admin.settings.category.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 12px;">
                @csrf
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Nama Kategori Induk *</label>
                    <input type="text" name="name" required placeholder="Contoh: Penthouse Eksklusif" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Sub-tipe Deskriptif</label>
                    <input type="text" name="subtypes" placeholder="Contoh: Sky Villa, Duplex, Luxury Tower" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem;">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 8px;">
                    <button type="button" class="btn-fav-tool" onclick="closeCategoryModal()">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Section 4: Notifikasi Admin & Kendali Sistem -->
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 22px 24px; box-shadow: 0 1px 4px rgba(0,0,0,0.02);">
        <div style="margin-bottom: 16px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 26px; height: 26px; border-radius: 6px; background: #E0F7FA; color: #00838F; display: flex; align-items: center; justify-content: center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                </div>
                <h3 style="font-size: 1.05rem; font-weight: 800; color: #0E1E2E; margin: 0;">Notifikasi Admin &amp; Kendali Sistem</h3>
            </div>
            <p style="font-size: 0.78rem; color: #64748B; margin: 4px 0 0 34px;">
                Kendali pengiriman sinyal instan sistem.
            </p>
        </div>

        <div style="display: flex; flex-direction: column; gap: 14px;">
            <!-- Toggle 1 -->
            <div class="toggle-row">
                <div>
                    <strong style="color: #0E1E2E; font-size: 0.84rem; display: block;">Notifikasi Form "Titip Properti"</strong>
                    <span style="font-size: 0.74rem; color: #64748B;">Kirim email rangkuman otomatis kepada lead admin saat ada pemilik mendaftarkan aset.</span>
                </div>
                <label class="switch">
                    <input type="checkbox" checked onchange="showToast('Pengaturan notifikasi diperbarui.')">
                    <span class="slider"></span>
                </label>
            </div>

            <!-- Toggle 2 -->
            <div class="toggle-row">
                <div>
                    <strong style="color: #0E1E2E; font-size: 0.84rem; display: block;">WhatsApp Instan Inquiry</strong>
                    <span style="font-size: 0.74rem; color: #64748B;">Teruskan pesan klien baru ke WhatsApp hotline admin secara real-time.</span>
                </div>
                <label class="switch">
                    <input type="checkbox" checked onchange="showToast('Pengaturan WhatsApp instan diperbarui.')">
                    <span class="slider"></span>
                </label>
            </div>

            <!-- Toggle 3 -->
            <div class="toggle-row">
                <div>
                    <strong style="color: #0E1E2E; font-size: 0.84rem; display: block;">Watermark Otomatis Foto Listing</strong>
                    <span style="font-size: 0.74rem; color: #64748B;">Terapkan logo Kumala Property pada setiap unggahan gambar properti baru.</span>
                </div>
                <label class="switch">
                    <input type="checkbox" checked onchange="showToast('Pengaturan watermark diperbarui.')">
                    <span class="slider"></span>
                </label>
            </div>
        </div>
    </div>

    <!-- Section 5: Photo Banner Headquarters Command -->
    <div style="position: relative; border-radius: 12px; overflow: hidden; height: 160px; box-shadow: 0 4px 14px rgba(0,0,0,0.06);">
        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80" style="width: 100%; height: 100%; object-fit: cover;">
        <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(14, 30, 46, 0.4) 0%, rgba(14, 30, 46, 0.85) 100%); display: flex; justify-content: space-between; align-items: flex-end; padding: 20px 24px; color: #FFFFFF;">
            <div>
                <h4 style="font-size: 1.15rem; font-weight: 800; color: #FFFFFF; margin: 0;">Headquarters Command</h4>
                <span style="font-size: 0.78rem; color: #CBD5E1;">TB Simatupang Business Center</span>
            </div>
            <div style="font-size: 0.76rem; color: #CBD5E1;">
                Server Time: <strong style="color: #FFFFFF;">WIB (UTC +07:00)</strong>
            </div>
        </div>
    </div>

</div>
@endsection

@section('styles')
<style>
.settings-tab-btn {
    padding: 8px 16px;
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.15s;
}
.settings-tab-btn:hover {
    border-color: #94A3B8;
    color: #0E1E2E;
}
.settings-tab-btn.active {
    background: #0E1E2E;
    color: #FFFFFF;
    border-color: #0E1E2E;
    font-weight: 700;
}

.btn-cyan-outline {
    background: #E0F7FA;
    border: 1px solid #B2EBF2;
    color: #00838F;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.76rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.15s;
}
.btn-cyan-outline:hover {
    background: #B2EBF2;
}

.settings-table-row {
    border-bottom: 1px solid #F1F5F9;
    transition: background 0.15s;
}
.settings-table-row:hover {
    background: #F8FAFC;
}

.subtipe-pill {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    color: #334155;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 4px;
}

.btn-icon-subtle {
    background: none;
    border: none;
    color: #64748B;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s;
}
.btn-icon-subtle:hover {
    background: #F1F5F9;
    color: #00838F;
}

.btn-settings-cancel {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    color: #475569;
    padding: 7px 14px;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
}
.btn-settings-cancel:hover {
    background: #F8FAFC;
    color: #0E1E2E;
}

.btn-settings-save {
    background: #00838F;
    border: none;
    color: #FFFFFF;
    padding: 7px 16px;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: background 0.15s;
}
.btn-settings-save:hover {
    background: #0097A7;
}

.region-box {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 12px 16px;
}
.region-active-pill {
    background: #E0F7FA;
    color: #00838F;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 4px;
}

.contact-box {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 12px 14px;
}
.contact-box-label {
    font-size: 0.7rem;
    font-weight: 700;
    color: #64748B;
    display: flex;
    align-items: center;
    gap: 5px;
}

.social-pill-box {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 10px 12px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.toggle-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 14px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
}

/* Switch */
.switch {
    position: relative;
    display: inline-block;
    width: 38px;
    height: 22px;
    flex-shrink: 0;
}
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #CBD5E1;
    transition: .3s;
    border-radius: 22px;
}
.slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
}
input:checked + .slider {
    background-color: #00838F;
}
input:checked + .slider:before {
    transform: translateX(16px);
}

@media (max-width: 900px) {
    #region-grid, #contact-grid, #social-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
