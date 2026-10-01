@extends('layouts.admin')

@section('title', 'Manajemen Properti - Kumala Admin')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- Breadcrumbs & Status Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div style="font-size: 0.76rem; font-weight: 700; color: #64748B; letter-spacing: 0.5px; text-transform: uppercase;">
            <span>KATALOG MASTER</span> &gt; <span style="color: #00838F;">INVENTORY PROPERTI</span> <span style="color: #CBD5E1; margin: 0 4px;">&bull;</span> <span style="color: #94A3B8; font-weight: 500;">LIVE SYNCED</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <div style="display: inline-flex; align-items: center; gap: 6px; background: #FFFFFF; border: 1px solid #E2E8F0; padding: 4px 10px; border-radius: 20px; font-size: 0.74rem; font-weight: 600; color: #0E1E2E;">
                <span style="width: 7px; height: 7px; border-radius: 50%; background: #059669; display: inline-block;"></span>
                <span>Total Portofolio: {{ count($allProperties) }} Unit</span>
            </div>
            <div style="display: inline-flex; align-items: center; gap: 6px; background: #FFFFFF; border: 1px solid #E2E8F0; padding: 4px 10px; border-radius: 20px; font-size: 0.74rem; font-weight: 600; color: #64748B;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/></svg>
                <span>Auto-Syndication Aktif</span>
            </div>
        </div>
    </div>

    <!-- Header & Action Toolbar -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 6px; background: #E0F7FA; color: #00838F; padding: 3px 10px; border-radius: 4px; font-size: 0.7rem; font-weight: 800; letter-spacing: 0.5px; margin-bottom: 6px; text-transform: uppercase;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><line x1="9" x2="9" y1="22" y2="22"/></svg>
                <span>LISTING &amp; INVENTORY HUB</span>
            </div>
            <h1 style="font-size: 1.75rem; color: #0E1E2E; margin: 0 0 4px 0; font-weight: 800; letter-spacing: -0.01em;">
                Manajemen Properti
            </h1>
            <p style="font-size: 0.85rem; color: #64748B; margin: 0; max-width: 680px; line-height: 1.45;">
                Kelola master inventaris listing properti terkurasi Kumala Realty. Setiap penambahan atau perubahan status langsung tersinkronkan ke katalog web publik.
            </p>
        </div>

        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            <button type="button" class="btn-top-teal" onclick="openAddPropertyModal()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="16"/><line x1="8" x2="16" y1="12" y2="12"/></svg>
                <span>+ Tambah Properti Baru</span>
            </button>
            <a href="{{ route('explore') }}" target="_blank" class="btn-top-outline">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                <span>Lihat Katalog Publik</span>
            </a>
        </div>
    </div>

    <!-- 4 Inventory Stat Cards -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;" id="prop-stats-grid">
        @php
            $activeCount = count(array_filter($allProperties, fn($p) => ($p['status'] ?? 'ACTIVE') === 'ACTIVE'));
            $soldCount = count(array_filter($allProperties, fn($p) => in_array($p['status'] ?? '', ['SOLD', 'RENTED'])));
        @endphp
        <!-- Card 1: Aktif Tayang -->
        <div class="prop-stat-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <span class="prop-stat-label">AKTIF TAYANG</span>
                <span style="width: 7px; height: 7px; border-radius: 50%; background: #00838F;"></span>
            </div>
            <div class="prop-stat-val">{{ $activeCount }}</div>
            <div style="font-size: 0.74rem; color: #059669; font-weight: 700; margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                <span>Live di Website</span>
            </div>
        </div>

        <!-- Card 2: Terjual / Tersewa -->
        <div class="prop-stat-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <span class="prop-stat-label">TERJUAL / TERSEWA</span>
                <span style="width: 7px; height: 7px; border-radius: 50%; background: #059669;"></span>
            </div>
            <div class="prop-stat-val">{{ $soldCount }}</div>
            <div style="font-size: 0.74rem; color: #64748B; margin-top: 4px;">
                Arsip Transaksi Sukses
            </div>
        </div>

        <!-- Card 3: Total Unit Master -->
        <div class="prop-stat-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <span class="prop-stat-label">TOTAL INVENTARIS</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#00838F" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <div class="prop-stat-val">{{ count($allProperties) }}</div>
            <div style="font-size: 0.74rem; color: #64748B; margin-top: 4px;">
                Unit Terkurasi Resmi
            </div>
        </div>

        <!-- Card 4: Nilai Portofolio (Dark Navy Card) -->
        <div class="prop-stat-card-dark">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <span style="font-size: 0.68rem; font-weight: 700; color: #94A3B8; letter-spacing: 0.6px; text-transform: uppercase;">SLA KURASI DATA</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#67E8F9" stroke-width="2"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
            </div>
            <div style="font-size: 1.55rem; font-weight: 800; color: #FFFFFF; line-height: 1;">100% SHM</div>
            <div style="font-size: 0.74rem; color: #67E8F9; margin-top: 6px; font-weight: 600;">
                Terverifikasi Notaris
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <form action="{{ route('admin.properties') }}" method="GET" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap; flex: 1; margin: 0;">
            <div style="position: relative; flex: 1; min-width: 220px;">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama properti, ID unit, atau lokasi..." 
                       style="width: 100%; padding: 8px 12px 8px 34px; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.82rem; outline: none;">
                <span style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94A3B8; display: flex;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </span>
            </div>

            <div style="display: flex; gap: 8px; align-items: center;">
                <select name="status" onchange="this.form.submit()" style="padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.82rem; background: #fff; cursor: pointer;">
                    <option value="Semua" {{ ($status ?? 'Semua') === 'Semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="ACTIVE" {{ ($status ?? '') === 'ACTIVE' ? 'selected' : '' }}>Aktif Tayang</option>
                    <option value="SOLD" {{ ($status ?? '') === 'SOLD' ? 'selected' : '' }}>Terjual (Sold)</option>
                    <option value="RENTED" {{ ($status ?? '') === 'RENTED' ? 'selected' : '' }}>Tersewa (Rented)</option>
                    <option value="INACTIVE" {{ ($status ?? '') === 'INACTIVE' ? 'selected' : '' }}>Ditangguhkan</option>
                </select>

                <select name="type" onchange="this.form.submit()" style="padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.82rem; background: #fff; cursor: pointer;">
                    <option value="Semua" {{ ($type ?? 'Semua') === 'Semua' ? 'selected' : '' }}>Semua Tipe Transaksi</option>
                    <option value="Jual" {{ strtolower($type ?? '') === 'jual' ? 'selected' : '' }}>Hanya Jual</option>
                    <option value="Sewa" {{ strtolower($type ?? '') === 'sewa' ? 'selected' : '' }}>Hanya Sewa</option>
                </select>

                <button type="submit" class="btn-top-teal" style="padding: 8px 14px; font-size: 0.8rem;">Filter</button>
                @if(!empty($search) || ($status ?? 'Semua') !== 'Semua' || ($type ?? 'Semua') !== 'Semua')
                    <a href="{{ route('admin.properties') }}" class="btn-top-outline" style="padding: 8px 12px; font-size: 0.8rem; color: #DC2626; border-color: #FCA5A5;">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Master Data Table -->
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.02);">
        <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table style="width: 100%; min-width: 960px; border-collapse: collapse; text-align: left; font-size: 0.82rem;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; color: #475569; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.6px; text-transform: uppercase;">
                        <th style="padding: 12px 16px; width: 140px;">INFO ID</th>
                        <th style="padding: 12px 16px;">JUDUL PROPERTI &amp; LOKASI</th>
                        <th style="padding: 12px 16px; width: 150px;">KATEGORI &amp; TRANSAKSI</th>
                        <th style="padding: 12px 16px; width: 160px;">HARGA PENAWARAN</th>
                        <th style="padding: 12px 16px; width: 140px;">STATUS LISTING</th>
                        <th style="padding: 12px 16px; width: 150px; text-align: right;">TINDAKAN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allProperties as $prop)
                    @php
                        $status = $prop['status'] ?? 'ACTIVE';
                        $statusBadgeColor = '#00838F';
                        $statusBadgeBg = '#E0F7FA';
                        if ($status === 'SOLD') {
                            $statusBadgeColor = '#DC2626';
                            $statusBadgeBg = '#FEE2E2';
                        } elseif ($status === 'RENTED') {
                            $statusBadgeColor = '#0284C7';
                            $statusBadgeBg = '#E0F2FE';
                        }
                    @endphp
                    <tr class="prop-table-row">
                        <td style="padding: 14px 16px;">
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <div style="width: 52px; height: 40px; border-radius: 6px; overflow: hidden; flex-shrink: 0; background: #0E1E2E;">
                                    <img src="{{ $prop['image_main'] ?? 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=200&q=80' }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div>
                                    <strong style="color: #00838F; display: block; font-size: 0.78rem;">{{ $prop['id'] }}</strong>
                                    <span style="background: #F1F5F9; color: #475569; font-size: 0.65rem; font-weight: 700; padding: 1px 5px; border-radius: 3px;">
                                        {{ $prop['legal_status'] ?? 'SHM' }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 14px 16px;">
                            <strong style="color: #0E1E2E; font-size: 0.88rem; display: block;">{{ $prop['title'] }}</strong>
                            <div style="font-size: 0.74rem; color: #64748B; margin-top: 3px; display: flex; align-items: center; gap: 4px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span>{{ $prop['location_area'] ?? '' }}, {{ $prop['location_city'] ?? '' }}</span>
                            </div>
                        </td>
                        <td style="padding: 14px 16px;">
                            <span class="badge-type-pill" style="background: #E0F7FA; color: #00838F; display: inline-block; margin-bottom: 2px;">
                                {{ $prop['category'] ?? 'Rumah' }}
                            </span>
                            <div style="font-size: 0.72rem; font-weight: 700; color: #475569;">
                                {{ strtoupper($prop['transaction_type'] ?? 'Jual') }}
                            </div>
                        </td>
                        <td style="padding: 14px 16px;">
                            <strong style="font-size: 0.88rem; color: #0E1E2E; display: block;">{{ $prop['price_formatted'] ?? $prop['price_short'] }}</strong>
                            <span style="font-size: 0.72rem; color: #00838F; font-weight: 600;">{{ $prop['price_notes'] ?? 'Legalitas Terjamin' }}</span>
                        </td>
                        <td style="padding: 14px 16px;">
                            <select onchange="changePropertyStatus('{{ $prop['id'] }}', this.value)" 
                                    style="padding: 4px 8px; border-radius: 6px; font-size: 0.74rem; font-weight: 700; border: 1px solid #CBD5E1; background: {{ $statusBadgeBg }}; color: {{ $statusBadgeColor }}; cursor: pointer;">
                                <option value="ACTIVE" {{ $status === 'ACTIVE' ? 'selected' : '' }}>Aktif Tayang</option>
                                <option value="SOLD" {{ $status === 'SOLD' ? 'selected' : '' }}>Terjual (Sold)</option>
                                <option value="RENTED" {{ $status === 'RENTED' ? 'selected' : '' }}>Tersewa (Rented)</option>
                                <option value="INACTIVE" {{ $status === 'INACTIVE' ? 'selected' : '' }}>Ditangguhkan</option>
                            </select>
                        </td>
                        <td style="padding: 14px 16px; text-align: right;">
                            <div style="display: inline-flex; gap: 6px; align-items: center;">
                                <a href="{{ route('property.detail', $prop['slug']) }}" target="_blank" class="btn-icon-subtle" title="Buka Halaman Publik">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                                </a>
                                <form action="{{ route('admin.properties.delete', rawurlencode($prop['id'])) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus properti {{ $prop['id'] }} dari katalog master?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon-subtle" title="Hapus Properti" style="color: #DC2626;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 24px; text-align: center; color: #64748B;">
                            Belum ada properti dalam database.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Tambah Properti Baru -->
<div id="addPropModal" style="display: none; position: fixed; inset: 0; background: rgba(14, 30, 46, 0.6); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #FFFFFF; border-radius: 12px; width: 100%; max-width: 620px; max-height: 90vh; overflow-y: auto; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #F1F5F9; padding-bottom: 12px;">
            <strong style="font-size: 1.1rem; color: #0E1E2E;">Tambah Properti Baru ke Master Katalog</strong>
            <button type="button" onclick="closeAddPropertyModal()" style="background: none; border: none; cursor: pointer; color: #64748B; display: flex; align-items: center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('admin.properties.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Judul Properti *</label>
                <input type="text" name="title" placeholder="Contoh: Villa Tropical Modern Sunset Uluwatu" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Kategori *</label>
                    <select name="category" style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
                        <option>Rumah Mewah</option>
                        <option>Villa Eksklusif</option>
                        <option>Apartemen Penthouse</option>
                        <option>Ruko &amp; Komersial</option>
                        <option>Tanah Kavling</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Tipe Transaksi *</label>
                    <select name="transaction_type" style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
                        <option value="Jual">Jual</option>
                        <option value="Sewa">Sewa</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Harga (Angka Penuh) *</label>
                    <input type="number" name="price" placeholder="Contoh: 8500000000" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Legalitas Sertifikat</label>
                    <input type="text" name="legal_status" value="SHM (Hak Milik)" style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Kota / Wilayah *</label>
                    <input type="text" name="location_city" placeholder="Contoh: Jakarta Selatan atau Bali" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Area / Kawasan *</label>
                    <input type="text" name="location_area" placeholder="Contoh: Pondok Indah atau Uluwatu" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 0.72rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Luas Tanah (m²)</label>
                    <input type="number" name="land_size" value="300" style="width: 100%; padding: 6px 10px; border: 1px solid #CBD5E1; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.72rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Luas Bangunan (m²)</label>
                    <input type="number" name="building_size" value="250" style="width: 100%; padding: 6px 10px; border: 1px solid #CBD5E1; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.72rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Kamar Tidur</label>
                    <input type="number" name="bedrooms" value="4" style="width: 100%; padding: 6px 10px; border: 1px solid #CBD5E1; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.72rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Kamar Mandi</label>
                    <input type="number" name="bathrooms" value="4" style="width: 100%; padding: 6px 10px; border: 1px solid #CBD5E1; border-radius: 6px;">
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">URL Foto Utama (Resolusi Tinggi)</label>
                <input type="url" name="image_main" placeholder="https://images.unsplash.com/..." style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Deskripsi Properti</label>
                <textarea name="description" rows="3" placeholder="Tuliskan spesifikasi unggulan, suasana hunian, dan daya tarik investasi..." style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" onclick="closeAddPropertyModal()" class="btn-top-outline">Batal</button>
                <button type="submit" class="btn-top-teal">Terbitkan ke Katalog</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
.btn-top-teal {
    background: #00838F;
    color: #FFFFFF;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 0.82rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    cursor: pointer;
    transition: background 0.15s;
}
.btn-top-teal:hover {
    background: #0097A7;
    color: #FFFFFF;
}

.btn-top-outline {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    color: #0E1E2E;
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 0.82rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.15s;
}
.btn-top-outline:hover {
    background: #F8FAFC;
    border-color: #94A3B8;
}

.prop-stat-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 16px 18px;
}
.prop-stat-card-dark {
    background: #0E1E2E;
    border-radius: 10px;
    padding: 16px 18px;
}
.prop-stat-label {
    font-size: 0.68rem;
    font-weight: 800;
    color: #64748B;
    letter-spacing: 0.6px;
}
.prop-stat-val {
    font-size: 1.55rem;
    font-weight: 800;
    color: #0E1E2E;
    line-height: 1;
}

.prop-table-row {
    border-bottom: 1px solid #F1F5F9;
    transition: background 0.15s;
}
.prop-table-row:hover {
    background: #F8FAFC;
}

.badge-type-pill {
    font-size: 0.72rem;
    font-weight: 700;
    padding: 2px 7px;
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

@media (max-width: 900px) {
    #prop-stats-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}
@media (max-width: 640px) {
    #prop-stats-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection

@section('scripts')
<script>
function changePropertyStatus(id, newStatus) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}';

    fetch('/admin/properti/' + encodeURIComponent(id) + '/status', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(res => {
        showToast(`Status listing ${id} berhasil diubah menjadi ${newStatus}.`);
        setTimeout(() => window.location.reload(), 800);
    })
    .catch(err => {
        console.error(err);
        showToast('Gagal mengubah status properti.');
    });
}

function openAddPropertyModal() {
    const modal = document.getElementById('addPropModal');
    if (modal) modal.style.display = 'flex';
}

function closeAddPropertyModal() {
    const modal = document.getElementById('addPropModal');
    if (modal) modal.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    if (window.location.search.includes('action=add')) {
        openAddPropertyModal();
    }
});
</script>
@endsection
