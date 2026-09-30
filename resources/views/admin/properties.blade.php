@extends('layouts.admin')

@section('title', 'Manajemen Properti - Kumala Admin')

@section('content')
<div>

    <!-- Header & Action Toolbar -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                <span class="badge-tag badge-tag-navy" style="font-size: 0.72rem;">INVENTORY HUB</span>
                <span style="font-size: 0.78rem; color: var(--text-muted);">Sistem Katalog & Portofolio Aset</span>
            </div>
            <h1 style="font-size: 1.65rem; color: var(--primary-navy); margin-bottom: 4px; font-weight: 800;">
                Manajemen Properti
            </h1>
            <p style="font-size: 0.88rem; color: var(--text-muted);">
                Kelola 148 unit listing aktif, draft kurasi, dan arsip portofolio Kumala Property.
            </p>
        </div>

        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <button type="button" class="btn-fav-tool" onclick="showToast('Mengekspor data inventaris ke format .CSV...')" style="padding: 9px 16px; font-size: 0.82rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                <span>Export CSV/Excel</span>
            </button>
            <a href="{{ route('titip-properti') }}" target="_blank" class="btn-primary" style="padding: 9px 18px; font-size: 0.82rem; border-radius: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>+ Tambah Properti Baru</span>
            </a>
        </div>
    </div>

    <!-- Inventory Metric Summary Bar (4 Cards) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="kpi-card" style="padding: 16px 20px;">
            <span class="kpi-label" style="font-size: 0.72rem;">Aktif Tayang</span>
            <div style="font-size: 1.6rem; font-weight: 800; color: var(--primary-navy); margin-top: 4px;">{{ $metrics['total_active'] }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748B;">Unit</span></div>
            <span style="font-size: 0.75rem; color: #059669; font-weight: 600;">✓ Publikasi Terverifikasi</span>
        </div>

        <div class="kpi-card" style="padding: 16px 20px;">
            <span class="kpi-label" style="font-size: 0.72rem;">Draft & Menunggu Review</span>
            <div style="font-size: 1.6rem; font-weight: 800; color: #D97706; margin-top: 4px;">{{ $metrics['draft_review'] }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748B;">Berkas</span></div>
            <span style="font-size: 0.75rem; color: #D97706; font-weight: 600;">Dalam Proses Kurasi</span>
        </div>

        <div class="kpi-card" style="padding: 16px 20px;">
            <span class="kpi-label" style="font-size: 0.72rem;">Total Leads Masuk</span>
            <div style="font-size: 1.6rem; font-weight: 800; color: var(--accent-teal-dark); margin-top: 4px;">{{ $metrics['leads_total'] }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748B;">Inquiry</span></div>
            <span style="font-size: 0.75rem; color: var(--text-muted);">Tingkat konversi rata-rata 24%</span>
        </div>

        <div class="kpi-card" style="padding: 16px 20px;">
            <span class="kpi-label" style="font-size: 0.72rem;">Nilai Portofolio Terdaftar</span>
            <div style="font-size: 1.6rem; font-weight: 800; color: var(--primary-navy); margin-top: 4px;">{{ $metrics['portfolio_val'] }}</div>
            <span style="font-size: 0.75rem; color: var(--text-muted);">Valuasi akumulasi aktif</span>
        </div>
    </div>

    <!-- Data Filter Bar -->
    <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; box-shadow: var(--shadow-sm);">
        <form action="{{ route('admin.properties') }}" method="GET" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto; gap: 12px; align-items: center;">
            
            <div style="position: relative;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama unit, alamat, atau ID #KP-xxx..." style="width: 100%; padding: 9px 12px 9px 34px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2" style="position: absolute; left: 11px; top: 50%; transform: translateY(-50%);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>

            <select name="status" style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                <option value="Semua">Semua Status</option>
                <option value="Aktif" {{ $status === 'Aktif' ? 'selected' : '' }}>Aktif Tayang</option>
                <option value="Draft" {{ $status === 'Draft' ? 'selected' : '' }}>Draft Kurasi</option>
                <option value="Review" {{ $status === 'Review' ? 'selected' : '' }}>Menunggu Review</option>
            </select>

            <select name="type" style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                <option value="Semua">Semua Tipe</option>
                <option value="Villa" {{ $type === 'Villa' ? 'selected' : '' }}>Villa Mewah</option>
                <option value="Rumah" {{ $type === 'Rumah' ? 'selected' : '' }}>Rumah Tinggal</option>
                <option value="Penthouse" {{ $type === 'Penthouse' ? 'selected' : '' }}>Penthouse</option>
                <option value="Komersial" {{ $type === 'Komersial' ? 'selected' : '' }}>Komersial</option>
            </select>

            <select name="region" style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                <option value="Semua">Semua Wilayah</option>
                <option value="Bali" {{ $region === 'Bali' ? 'selected' : '' }}>Bali</option>
                <option value="Jakarta" {{ $region === 'Jakarta' ? 'selected' : '' }}>Jakarta Selatan</option>
                <option value="Tangerang" {{ $region === 'Tangerang' ? 'selected' : '' }}>BSD / Tangerang</option>
                <option value="Surabaya" {{ $region === 'Surabaya' ? 'selected' : '' }}>Surabaya</option>
            </select>

            <button type="submit" class="btn-primary" style="padding: 9px 16px; font-size: 0.82rem; border-radius: 6px; white-space: nowrap;">
                <span>Terapkan</span>
            </button>

            <a href="{{ route('admin.properties') }}" class="btn-fav-tool" style="padding: 9px 12px; font-size: 0.82rem; border-radius: 6px;" title="Reset Filter">
                Reset
            </a>
        </form>
    </div>

    <!-- Master Data Table Container -->
    <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid var(--border-color); color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <th style="padding: 14px 16px; width: 40px;"><input type="checkbox" style="accent-color: #00B488;"></th>
                        <th style="padding: 14px 16px;">Unit & ID</th>
                        <th style="padding: 14px 16px;">Kategori & Transaksi</th>
                        <th style="padding: 14px 16px;">Harga Penawaran</th>
                        <th style="padding: 14px 16px;">Spesifikasi</th>
                        <th style="padding: 14px 16px;">Status Listing</th>
                        <th style="padding: 14px 16px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allProperties as $item)
                    <tr style="border-bottom: 1px solid var(--border-light); transition: var(--transition-fast);" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 14px 16px;">
                            <input type="checkbox" style="accent-color: #00B488;">
                        </td>

                        <td style="padding: 14px 16px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <img src="{{ $item['image_main'] }}" alt="{{ $item['title'] }}" style="width: 60px; height: 44px; border-radius: 6px; object-fit: cover; flex-shrink: 0; background: #0E2238;">
                                <div>
                                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
                                        <strong style="color: var(--primary-navy); font-size: 0.9rem;">{{ $item['title'] }}</strong>
                                        <span style="font-size: 0.68rem; font-weight: 700; background: #E2E8F0; color: #475569; padding: 2px 6px; border-radius: 4px;">{{ $item['id'] }}</span>
                                    </div>
                                    <span style="color: var(--text-muted); font-size: 0.78rem;">{{ $item['location_address'] }}</span>
                                </div>
                            </div>
                        </td>

                        <td style="padding: 14px 16px;">
                            <span class="badge-tag {{ $item['transaction_type'] === 'Jual' ? 'badge-tag-navy' : 'badge-tag-teal' }}" style="font-size: 0.7rem; padding: 2px 8px;">
                                {{ $item['transaction_type'] === 'Jual' ? 'DIJUAL' : 'DISEWAKAN' }}
                            </span>
                            <span style="display: block; font-size: 0.75rem; color: #475569; margin-top: 4px;">{{ $item['legal_status'] }}</span>
                        </td>

                        <td style="padding: 14px 16px;">
                            <strong style="color: var(--primary-navy); font-size: 0.95rem; display: block;">{{ $item['price_formatted'] }}</strong>
                            <span style="font-size: 0.72rem; color: #059669; font-weight: 600;">{{ $item['price_notes'] }}</span>
                        </td>

                        <td style="padding: 14px 16px;">
                            <div style="color: #475569; font-size: 0.78rem; line-height: 1.4;">
                                <span>{{ $item['bedrooms'] }} KT • {{ $item['bathrooms'] }} KM</span><br>
                                <span style="color: var(--text-muted);">LT {{ $item['land_size'] }}m² / LB {{ $item['building_size'] }}m²</span>
                            </div>
                        </td>

                        <td style="padding: 14px 16px;">
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #E6FBF5; color: #059669; font-size: 0.72rem; font-weight: 700; padding: 4px 10px; border-radius: 6px;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #059669;"></span>
                                <span>Aktif Tayang</span>
                            </span>
                        </td>

                        <td style="padding: 14px 16px; text-align: right;">
                            <div style="display: inline-flex; gap: 6px;">
                                <a href="{{ route('property.detail', $item['slug']) }}" target="_blank" class="btn-fav-tool" style="padding: 6px 10px; font-size: 0.75rem;" title="Lihat di Web">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                                </a>
                                <button type="button" class="btn-fav-tool" style="padding: 6px 10px; font-size: 0.75rem;" onclick="showToast('Membuka formulir edit {{ $item['id'] }}...')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Table Pagination Footer -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 20px; background: #F8FAFC; border-top: 1px solid var(--border-color); font-size: 0.8rem; color: var(--text-muted); flex-wrap: wrap; gap: 12px;">
            <span>Menampilkan <strong>1 - 6</strong> dari <strong>112</strong> unit listing aktif</span>
            
            <div style="display: flex; gap: 6px;">
                <button type="button" class="btn-fav-tool" style="padding: 4px 10px; font-size: 0.75rem;" disabled>&lt; Sebelumnya</button>
                <button type="button" class="btn-fav-tool" style="padding: 4px 10px; font-size: 0.75rem; background: var(--primary-navy); color: #FFFFFF; border-color: var(--primary-navy);">1</button>
                <button type="button" class="btn-fav-tool" style="padding: 4px 10px; font-size: 0.75rem;">2</button>
                <button type="button" class="btn-fav-tool" style="padding: 4px 10px; font-size: 0.75rem;">3</button>
                <button type="button" class="btn-fav-tool" style="padding: 4px 10px; font-size: 0.75rem;">Selanjutnya &gt;</button>
            </div>
        </div>
    </div>

</div>
@endsection
