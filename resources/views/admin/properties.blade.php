@extends('layouts.admin')

@section('title', 'Manajemen Properti - Kumala Admin')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px; min-width: 0; width: 100%; max-width: 100%;">

    <!-- Breadcrumbs & Status Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div style="font-size: 0.76rem; font-weight: 700; color: #64748B; letter-spacing: 0.5px; text-transform: uppercase;">
            <span>KATALOG MASTER</span> &gt; <span style="color: #00838F;">INVENTORY PROPERTI</span> <span style="color: #CBD5E1; margin: 0 4px;">&bull;</span> <span style="color: #94A3B8; font-weight: 500;">LIVE SYNCED</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
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

        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;" class="prop-header-actions">
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
            $propsArray = is_array($allProperties) ? $allProperties : $allProperties->items();
            $activeCount = count(array_filter($propsArray, fn($p) => ($p['status'] ?? 'ACTIVE') === 'ACTIVE'));
            $soldCount = count(array_filter($propsArray, fn($p) => in_array($p['status'] ?? '', ['SOLD', 'RENTED'])));
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
    <div class="prop-filter-toolbar">
        <form action="{{ route('admin.properties') }}" method="GET" class="prop-filter-form">
            <div class="prop-search-box">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama properti, ID unit, atau lokasi...">
                <span class="prop-search-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </span>
            </div>

            <div class="prop-filter-controls">
                <div class="prop-select-group">
                    <select name="status" onchange="this.form.submit()" class="prop-filter-select">
                        <option value="Semua" {{ ($status ?? 'Semua') === 'Semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="ACTIVE" {{ ($status ?? '') === 'ACTIVE' ? 'selected' : '' }}>Aktif Tayang</option>
                        <option value="SOLD" {{ ($status ?? '') === 'SOLD' ? 'selected' : '' }}>Terjual (Sold)</option>
                        <option value="RENTED" {{ ($status ?? '') === 'RENTED' ? 'selected' : '' }}>Tersewa (Rented)</option>
                        <option value="INACTIVE" {{ ($status ?? '') === 'INACTIVE' ? 'selected' : '' }}>Ditangguhkan</option>
                    </select>

                    <select name="type" onchange="this.form.submit()" class="prop-filter-select">
                        <option value="Semua" {{ ($type ?? 'Semua') === 'Semua' ? 'selected' : '' }}>Semua Tipe Transaksi</option>
                        <option value="Jual" {{ strtolower($type ?? '') === 'jual' ? 'selected' : '' }}>Hanya Jual</option>
                        <option value="Sewa" {{ strtolower($type ?? '') === 'sewa' ? 'selected' : '' }}>Hanya Sewa</option>
                    </select>
                </div>

                <div class="prop-action-buttons">
                    <button type="submit" class="btn-top-teal">Filter</button>
                    @if(!empty($search) || ($status ?? 'Semua') !== 'Semua' || ($type ?? 'Semua') !== 'Semua')
                        <a href="{{ route('admin.properties') }}" class="btn-top-outline btn-reset-filter">Reset</a>
                    @endif
                </div>
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

        <!-- Table Pagination Footer -->
        @if(is_a($allProperties, 'Illuminate\Pagination\LengthAwarePaginator') && $allProperties->total() > 0)
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 20px; background: #F8FAFC; border-top: 1px solid #E2E8F0; font-size: 0.8rem; color: #64748B; flex-wrap: wrap; gap: 12px;">
            <span>Menampilkan <strong>{{ $allProperties->firstItem() ?? 0 }} - {{ $allProperties->lastItem() ?? 0 }}</strong> dari <strong>{{ $allProperties->total() }}</strong> unit listing</span>
            
            <div style="display: flex; gap: 6px;">
                @if($allProperties->onFirstPage())
                    <button type="button" class="btn-top-outline" style="padding: 4px 10px; font-size: 0.75rem;" disabled>&lt; Sebelumnya</button>
                @else
                    <a href="{{ $allProperties->previousPageUrl() }}" class="btn-top-outline" style="padding: 4px 10px; font-size: 0.75rem; text-decoration: none;">&lt; Sebelumnya</a>
                @endif

                @for($page = 1; $page <= $allProperties->lastPage(); $page++)
                    @if($page == $allProperties->currentPage())
                        <span class="btn-top-teal" style="padding: 4px 10px; font-size: 0.75rem;">{{ $page }}</span>
                    @else
                        <a href="{{ $allProperties->url($page) }}" class="btn-top-outline" style="padding: 4px 10px; font-size: 0.75rem; text-decoration: none;">{{ $page }}</a>
                    @endif
                @endfor

                @if($allProperties->hasMorePages())
                    <a href="{{ $allProperties->nextPageUrl() }}" class="btn-top-outline" style="padding: 4px 10px; font-size: 0.75rem; text-decoration: none;">Selanjutnya &gt;</a>
                @else
                    <button type="button" class="btn-top-outline" style="padding: 4px 10px; font-size: 0.75rem;" disabled>Selanjutnya &gt;</button>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Modal Formulir Properti (Tambah & Edit) -->
    <div id="modalPropertyForm" style="display: none; position: fixed; inset: 0; background: rgba(14, 34, 56, 0.7); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #FFFFFF; border-radius: 14px; max-width: 680px; width: 100%; padding: 28px; box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                <div>
                    <h3 id="propModalTitle" style="font-size: 1.25rem; color: var(--primary-navy); font-weight: 700;">Tambah Properti Baru</h3>
                    <p id="propModalSubtitle" style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">Lengkapi data spesifikasi dan kurasi properti.</p>
                </div>
                <button type="button" onclick="closePropertyModal()" style="background: none; border: none; font-size: 1.4rem; cursor: pointer; color: #94A3B8;">&times;</button>
            </div>

            <form id="propMainForm" action="{{ route('admin.properties.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 16px;">
                @csrf
                <input type="hidden" name="id" id="propInputId" value="">

                <!-- Section 1: Data Pokok -->
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Judul Properti *</label>
                    <input type="text" name="title" id="propInputTitle" required placeholder="Contoh: Villa Cliffside Horizon Uluwatu" style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Kategori Induk *</label>
                        <select name="category" id="propInputCategory" required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                            @if(isset($categoriesList) && count($categoriesList) > 0)
                                @foreach($categoriesList as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            @else
                                <option value="Rumah Tapak Mewah">Rumah Tapak Mewah</option>
                                <option value="Villa Mewah & Tropis">Villa Mewah & Tropis</option>
                                <option value="Ruko & Komersial">Ruko & Komersial</option>
                                <option value="Apartemen & Penthouse">Apartemen & Penthouse</option>
                                <option value="Tanah & Kavling">Tanah & Kavling</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Tipe Transaksi *</label>
                        <select name="transaction_type" id="propInputTrxType" required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                            <option value="Jual">Jual</option>
                            <option value="Sewa">Sewa</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Harga Penawaran *</label>
                        <input type="text" name="price" id="propInputPrice" required placeholder="Contoh: Rp 42.000.000.000" style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Catatan Harga</label>
                        <input type="text" name="price_notes" id="propInputPriceNotes" placeholder="Pajak & Biaya Notaris Dibagi Proposional" style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                    </div>
                </div>

                <!-- Section 2: Lokasi & Legalitas -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Kota / Wilayah *</label>
                        <select name="location_city" id="propInputCity" required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                            @if(isset($regionsList) && count($regionsList) > 0)
                                @foreach($regionsList as $reg)
                                    <option value="{{ $reg->city ?: $reg->name }}">{{ $reg->name }}</option>
                                @endforeach
                            @else
                                <option value="Jakarta Selatan">Jakarta Selatan</option>
                                <option value="Bali">Bali</option>
                                <option value="Tangerang Selatan">Tangerang Selatan (BSD City)</option>
                                <option value="Surabaya">Surabaya</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Area / Kawasan</label>
                        <input type="text" name="location_area" id="propInputArea" placeholder="Contoh: Uluwatu, Badung" style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Alamat Lengkap *</label>
                    <input type="text" name="location_address" id="propInputAddress" required placeholder="Jl. Pantai Suluban No. 8, Pecatu" style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Sertifikat / Legalitas *</label>
                        <select name="legal_status" id="propInputLegal" required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                            <option value="SHM (Hak Milik)">SHM (Hak Milik)</option>
                            <option value="HGB (Hak Guna Bangunan)">HGB (Hak Guna Bangunan)</option>
                            <option value="Strata Title">Strata Title</option>
                            <option value="Girik / Lainnya">Girik / Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Status Listing</label>
                        <select name="status" id="propInputStatus" style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                            <option value="ACTIVE">Aktif Tayang</option>
                            <option value="DRAFT">Draft Kurasi</option>
                            <option value="PENDING_REVIEW">Menunggu Review</option>
                            <option value="SOLD">Terjual</option>
                            <option value="RENTED">Tersewa</option>
                            <option value="ARCHIVED">Arsip</option>
                        </select>
                    </div>
                </div>

                <!-- Section 3: Spesifikasi Teknis -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
                    <div>
                        <label style="display: block; font-size: 0.72rem; font-weight: 700; color: #475569; margin-bottom: 2px;">KT</label>
                        <input type="number" name="bedrooms" id="propInputBedrooms" value="3" style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.72rem; font-weight: 700; color: #475569; margin-bottom: 2px;">KM</label>
                        <input type="number" name="bathrooms" id="propInputBathrooms" value="2" style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.72rem; font-weight: 700; color: #475569; margin-bottom: 2px;">LT (m²)</label>
                        <input type="number" name="land_size" id="propInputLand" value="200" style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.72rem; font-weight: 700; color: #475569; margin-bottom: 2px;">LB (m²)</label>
                        <input type="number" name="building_size" id="propInputBuilding" value="180" style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
                    <div>
                        <label style="display: block; font-size: 0.72rem; font-weight: 700; color: #475569; margin-bottom: 2px;">Jml Lantai</label>
                        <input type="number" name="floors" id="propInputFloors" value="1" style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.72rem; font-weight: 700; color: #475569; margin-bottom: 2px;">Listrik</label>
                        <input type="text" name="electricity" id="propInputElectricity" value="3500 VA" style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.72rem; font-weight: 700; color: #475569; margin-bottom: 2px;">Arah Hadap</label>
                        <input type="text" name="orientation" id="propInputOrientation" value="Utara" style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                    </div>
                </div>

                <!-- Section 4: Foto & Media -->
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Foto Utama (URL atau Unggah File)</label>
                    <input type="text" name="image_main" id="propInputImage" placeholder="https://images.unsplash.com/photo-..." style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC; margin-bottom: 6px;">
                    <input type="file" name="photo_file" accept="image/*" style="font-size: 0.82rem; color: #64748B;">
                </div>

                <!-- Section 5: Deskripsi & Fitur -->
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Deskripsi Narasi *</label>
                    <textarea name="description" id="propInputDesc" rows="3" required placeholder="Jelaskan konsep arsitektur, lanskap, dan keunggulan investasi..." style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC; font-family: inherit;"></textarea>
                </div>

                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Fasilitas & Fitur (Pisahkan dengan koma)</label>
                    <input type="text" name="features" id="propInputFeatures" placeholder="Kolam Renang Privat, Fully Furnished, Carport 2 Mobil, Ocean View" style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                </div>

                <!-- Modal Actions -->
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px; border-top: 1px solid var(--border-color); padding-top: 14px;">
                    <button type="button" class="btn-fav-tool" onclick="closePropertyModal()">Batal</button>
                    <button type="submit" id="propSubmitBtn" class="btn-primary">Simpan & Publikasikan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function toggleSelectAll(master) {
        const checkboxes = document.querySelectorAll('.prop-checkbox');
        checkboxes.forEach(cb => cb.checked = master.checked);
        updateBatchToolbar();
    }

    function updateBatchToolbar() {
        const checked = document.querySelectorAll('.prop-checkbox:checked');
        const count = checked.length;
        const toolbar = document.getElementById('batchActionForm');
        const countSpan = document.getElementById('selectedCount');
        
        if (count > 0) {
            toolbar.style.display = 'flex';
            countSpan.textContent = count;
        } else {
            toolbar.style.display = 'none';
            const master = document.getElementById('selectAllProps');
            if (master) master.checked = false;
        }
    }

    function openPropertyModal(mode, data = null) {
        const modal = document.getElementById('modalPropertyForm');
        const form = document.getElementById('propMainForm');
        const title = document.getElementById('propModalTitle');
        const subtitle = document.getElementById('propModalSubtitle');
        const submitBtn = document.getElementById('propSubmitBtn');

        if (mode === 'create') {
            title.textContent = 'Tambah Properti Baru';
            subtitle.textContent = 'Lengkapi data spesifikasi dan kurasi properti.';
            submitBtn.textContent = 'Simpan & Publikasikan';
            form.action = "{{ route('admin.properties.store') }}";
            form.reset();
            document.getElementById('propInputId').value = '';
            document.getElementById('propInputStatus').value = 'ACTIVE';
        } else if (mode === 'edit' && data) {
            title.textContent = 'Edit Properti ' + (data.id || '');
            subtitle.textContent = 'Perbarui data spesifikasi, harga penawaran, atau status unit.';
            submitBtn.textContent = 'Simpan Perubahan';
            const cleanId = encodeURIComponent(data.id);
            form.action = "/admin/properti/" + cleanId + "/update";
            
            document.getElementById('propInputId').value = data.id || '';
            document.getElementById('propInputTitle').value = data.title || '';
            document.getElementById('propInputCategory').value = data.category || 'Rumah Tapak Mewah';
            document.getElementById('propInputTrxType').value = data.transaction_type || 'Jual';
            document.getElementById('propInputPrice').value = data.price_formatted || (data.price ? 'Rp ' + data.price : '');
            document.getElementById('propInputPriceNotes').value = data.price_notes || '';
            document.getElementById('propInputCity').value = data.location_city || 'Jakarta Selatan';
            document.getElementById('propInputArea').value = data.location_area || '';
            document.getElementById('propInputAddress').value = data.location_address || '';
            document.getElementById('propInputLegal').value = data.legal_status || 'SHM (Hak Milik)';
            document.getElementById('propInputStatus').value = data.status || 'ACTIVE';
            document.getElementById('propInputBedrooms').value = data.bedrooms || 3;
            document.getElementById('propInputBathrooms').value = data.bathrooms || 2;
            document.getElementById('propInputLand').value = data.land_size || 0;
            document.getElementById('propInputBuilding').value = data.building_size || 0;
            document.getElementById('propInputImage').value = data.image_main || '';
            document.getElementById('propInputDesc').value = data.description || '';
        }

        modal.style.display = 'flex';
    }

    function closePropertyModal() {
        document.getElementById('modalPropertyForm').style.display = 'none';
    }
    </script>
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

            <div class="prop-modal-grid-2">
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

            <div class="prop-modal-grid-2">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Harga (Angka Penuh) *</label>
                    <input type="number" name="price" placeholder="Contoh: 8500000000" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Legalitas Sertifikat</label>
                    <input type="text" name="legal_status" value="SHM (Hak Milik)" style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
                </div>
            </div>

            <div class="prop-modal-grid-2">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Kota / Wilayah *</label>
                    <input type="text" name="location_city" placeholder="Contoh: Jakarta Selatan atau Bali" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Area / Kawasan *</label>
                    <input type="text" name="location_area" placeholder="Contoh: Pondok Indah atau Uluwatu" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
                </div>
            </div>

            <div class="prop-modal-grid-4">
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

/* Filter & Search Toolbar */
.prop-filter-toolbar {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 16px 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    box-sizing: border-box;
    width: 100%;
    max-width: 100%;
    overflow: hidden;
}

.prop-filter-form {
    display: flex;
    gap: 12px;
    align-items: center;
    width: 100%;
    margin: 0;
    box-sizing: border-box;
}

.prop-search-box {
    position: relative;
    flex: 1 1 auto;
    min-width: 220px;
    max-width: 100%;
    box-sizing: border-box;
}

.prop-search-box input {
    width: 100%;
    box-sizing: border-box;
    height: 42px;
    padding: 0 14px 0 38px;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    font-size: 0.84rem;
    color: #0E1E2E;
    outline: none;
    transition: all 0.2s ease;
    background: #FFFFFF;
    display: block;
}

.prop-search-box input:focus {
    border-color: #00838F;
    box-shadow: 0 0 0 3px rgba(0, 131, 143, 0.12);
}

.prop-search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94A3B8;
    display: flex;
    pointer-events: none;
}

.prop-filter-controls {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-shrink: 0;
    box-sizing: border-box;
}

.prop-select-group {
    display: flex;
    gap: 8px;
    align-items: center;
    box-sizing: border-box;
}

.prop-filter-select {
    height: 42px;
    padding: 0 12px;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    font-size: 0.82rem;
    color: #1E293B;
    background: #FFFFFF;
    cursor: pointer;
    outline: none;
    transition: all 0.2s ease;
    box-sizing: border-box;
    min-width: 140px;
}

.prop-filter-select:focus {
    border-color: #00838F;
    box-shadow: 0 0 0 3px rgba(0, 131, 143, 0.12);
}

.prop-action-buttons {
    display: flex;
    gap: 8px;
    align-items: center;
    box-sizing: border-box;
}

.prop-action-buttons .btn-top-teal,
.prop-action-buttons .btn-top-outline {
    height: 42px;
    box-sizing: border-box;
    white-space: nowrap;
}

/* Modal Responsive Grids */
.prop-modal-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 12px;
}

.prop-modal-grid-4 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-bottom: 12px;
}

/* Tablet & Intermediate Screens (<= 1024px) */
@media (max-width: 1024px) {
    #prop-stats-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
    .prop-filter-form {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }
    .prop-search-box {
        width: 100%;
        min-width: 0;
    }
    .prop-filter-controls {
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 8px;
    }
    .prop-select-group {
        display: contents;
    }
    .prop-filter-select {
        width: 100%;
        min-width: 0;
    }
    .prop-action-buttons {
        display: flex;
        gap: 8px;
    }
}

/* Mobile Screens (<= 640px) */
@media (max-width: 640px) {
    .prop-header-actions {
        width: 100%;
    }
    .prop-header-actions button,
    .prop-header-actions a {
        flex: 1;
        justify-content: center;
        text-align: center;
    }
    #prop-stats-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 10px;
    }
    .prop-stat-card,
    .prop-stat-card-dark {
        padding: 12px 14px;
    }
    .prop-stat-val {
        font-size: 1.3rem;
    }
    .prop-filter-toolbar {
        padding: 12px 14px;
        border-radius: 10px;
    }
    .prop-filter-controls {
        display: flex;
        flex-direction: column;
        gap: 8px;
        width: 100%;
    }
    .prop-select-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        width: 100%;
    }
    .prop-filter-select {
        width: 100%;
        min-width: 0;
        font-size: 0.78rem;
        padding: 0 8px;
    }
    .prop-action-buttons {
        width: 100%;
        display: grid;
        grid-template-columns: 1fr;
        gap: 8px;
    }
    .prop-action-buttons .btn-top-teal,
    .prop-action-buttons .btn-top-outline {
        width: 100%;
        justify-content: center;
        text-align: center;
    }
    .prop-modal-grid-2 {
        grid-template-columns: 1fr;
    }
    .prop-modal-grid-4 {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Very Small Mobile (<= 420px) */
@media (max-width: 420px) {
    #prop-stats-grid {
        grid-template-columns: 1fr !important;
    }
    .prop-select-group {
        grid-template-columns: 1fr;
    }
    .prop-modal-grid-4 {
        grid-template-columns: 1fr;
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
