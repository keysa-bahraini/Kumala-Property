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
            <a href="{{ route('admin.export.properties') }}" class="btn-fav-tool" style="padding: 9px 16px; font-size: 0.82rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                <span>Export CSV/Excel</span>
            </a>
            <button type="button" class="btn-primary" onclick="openPropertyModal('create')" style="padding: 9px 18px; font-size: 0.82rem; border-radius: 8px; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>+ Tambah Properti Baru</span>
            </button>
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

    <!-- Batch Action Toolbar -->
    <form id="batchActionForm" action="{{ route('admin.properties.batch') }}" method="POST" style="display: none; background: #0E2238; color: #FFFFFF; border-radius: 10px; padding: 12px 20px; margin-bottom: 18px; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; box-shadow: var(--shadow-md);">
        @csrf
        <div style="font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <span style="background: #00B488; color: #FFFFFF; border-radius: 50%; width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;" id="selectedCount">0</span>
            <span>unit properti terpilih</span>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <select name="batch_action" style="padding: 7px 12px; border-radius: 6px; font-size: 0.82rem; background: #162E4A; color: #FFFFFF; border: 1px solid #334155;">
                <option value="publish">Publikasikan (Aktif Tayang)</option>
                <option value="unpublish">Arsipkan (Unpublish)</option>
                <option value="draft">Set ke Draft Kurasi</option>
                <option value="delete">Hapus Unit Terpilih</option>
            </select>
            <button type="submit" class="btn-teal" style="padding: 7px 16px; font-size: 0.82rem; border-radius: 6px;">Terapkan Aksi</button>
        </div>
    </form>

    <!-- Master Data Table Container -->
    <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid var(--border-color); color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <th style="padding: 14px 16px; width: 40px;"><input type="checkbox" id="selectAllProps" onchange="toggleSelectAll(this)" style="accent-color: #00B488; cursor: pointer;"></th>
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
                            <input type="checkbox" name="property_ids[]" form="batchActionForm" value="{{ $item['id'] }}" class="prop-checkbox" onchange="updateBatchToolbar()" style="accent-color: #00B488; cursor: pointer;">
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
                            @if(($item['status'] ?? 'ACTIVE') === 'ACTIVE')
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #E6FBF5; color: #059669; font-size: 0.72rem; font-weight: 700; padding: 4px 10px; border-radius: 6px;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #059669;"></span>
                                <span>Aktif Tayang</span>
                            </span>
                            @elseif(($item['status'] ?? '') === 'DRAFT')
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #FEF3C7; color: #D97706; font-size: 0.72rem; font-weight: 700; padding: 4px 10px; border-radius: 6px;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #D97706;"></span>
                                <span>Draft Kurasi</span>
                            </span>
                            @elseif(($item['status'] ?? '') === 'PENDING_REVIEW')
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #EFF6FF; color: #2563EB; font-size: 0.72rem; font-weight: 700; padding: 4px 10px; border-radius: 6px;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #2563EB;"></span>
                                <span>Menunggu Review</span>
                            </span>
                            @elseif(($item['status'] ?? '') === 'SOLD')
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #FEE2E2; color: #DC2626; font-size: 0.72rem; font-weight: 700; padding: 4px 10px; border-radius: 6px;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #DC2626;"></span>
                                <span>Terjual</span>
                            </span>
                            @elseif(($item['status'] ?? '') === 'RENTED')
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #F3E8FF; color: #7C3AED; font-size: 0.72rem; font-weight: 700; padding: 4px 10px; border-radius: 6px;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #7C3AED;"></span>
                                <span>Tersewa</span>
                            </span>
                            @else
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #F1F5F9; color: #64748B; font-size: 0.72rem; font-weight: 700; padding: 4px 10px; border-radius: 6px;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #64748B;"></span>
                                <span>{{ $item['status'] ?? 'Arsip' }}</span>
                            </span>
                            @endif
                        </td>

                        <td style="padding: 14px 16px; text-align: right;">
                            <div style="display: inline-flex; gap: 6px;">
                                <a href="{{ route('property.detail', $item['slug']) }}" target="_blank" class="btn-fav-tool" style="padding: 6px 10px; font-size: 0.75rem;" title="Lihat di Web">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                                </a>
                                <button type="button" class="btn-fav-tool" style="padding: 6px 10px; font-size: 0.75rem;" onclick='openPropertyModal("edit", @json($item))' title="Edit Unit Properti">
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
            <span>Menampilkan <strong>{{ $allProperties->firstItem() ?? 0 }} - {{ $allProperties->lastItem() ?? 0 }}</strong> dari <strong>{{ $allProperties->total() }}</strong> unit listing aktif</span>
            
            <div style="display: flex; gap: 6px;">
                @if($allProperties->onFirstPage())
                    <button type="button" class="btn-fav-tool" style="padding: 4px 10px; font-size: 0.75rem;" disabled>&lt; Sebelumnya</button>
                @else
                    <a href="{{ $allProperties->previousPageUrl() }}" class="btn-fav-tool" style="padding: 4px 10px; font-size: 0.75rem; text-decoration: none;">&lt; Sebelumnya</a>
                @endif

                @for($page = 1; $page <= $allProperties->lastPage(); $page++)
                    @if($page == $allProperties->currentPage())
                        <button type="button" class="btn-fav-tool" style="padding: 4px 10px; font-size: 0.75rem; background: var(--primary-navy); color: #FFFFFF; border-color: var(--primary-navy);">{{ $page }}</button>
                    @else
                        <a href="{{ $allProperties->url($page) }}" class="btn-fav-tool" style="padding: 4px 10px; font-size: 0.75rem; text-decoration: none;">{{ $page }}</a>
                    @endif
                @endfor

                @if($allProperties->hasMorePages())
                    <a href="{{ $allProperties->nextPageUrl() }}" class="btn-fav-tool" style="padding: 4px 10px; font-size: 0.75rem; text-decoration: none;">Selanjutnya &gt;</a>
                @else
                    <button type="button" class="btn-fav-tool" style="padding: 4px 10px; font-size: 0.75rem;" disabled>Selanjutnya &gt;</button>
                @endif
            </div>
        </div>
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
@endsection
