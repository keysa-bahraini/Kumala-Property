@extends('layouts.admin')

@section('title', 'Manajemen Listing Masuk (Titip Properti) - Kumala Admin')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px; min-width: 0; width: 100%; max-width: 100%;">

    <!-- Top Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;" class="submissions-header">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; flex-wrap: wrap;">
                <span style="background: #E0F7FA; color: #00838F; padding: 3px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: 800; letter-spacing: 0.5px; text-transform: uppercase; white-space: nowrap;">
                    SLA GATEKEEPER VERIFICATION
                </span>
                <span style="font-size: 0.76rem; color: #64748B; white-space: nowrap;">SLA Tinjau: &lt; 4 Jam</span>
            </div>
            <h1 style="font-size: 1.75rem; color: #0E1E2E; margin: 0 0 4px 0; font-weight: 800; letter-spacing: -0.01em;">
                Manajemen Listing Masuk (Titip Properti)
            </h1>
            <p style="font-size: 0.85rem; color: #64748B; margin: 0; max-width: 720px; line-height: 1.45;">
                Verifikasi kelengkapan berkas, data fisik, dan legalitas pengajuan pemilik unit sebelum diterbitkan ke katalog publik.
            </p>
        </div>

        <!-- Priority Queue Badge Card -->
        <div style="background: #EAF4F9; border: 1px solid #D6E8F2; border-radius: 10px; padding: 10px 18px; display: flex; align-items: center; gap: 14px;" class="sub-priority-card">
            <div>
                <span style="display: block; font-size: 0.68rem; font-weight: 800; color: #64748B; letter-spacing: 0.6px; text-transform: uppercase; white-space: nowrap;">ANTRIAN PRIORITAS</span>
                <strong style="font-size: 1.15rem; color: #0E1E2E; white-space: nowrap;" id="pending-count-text">{{ $queueCounts['pending'] ?? 0 }} Menunggu</strong>
            </div>
            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(0, 131, 143, 0.12); color: #00838F; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="m9 14 2 2 4-4"/></svg>
            </div>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="sub-tabs-wrapper">
        <a href="{{ route('admin.submissions') }}" class="sub-tab-btn {{ (!request()->filled('status') || request('status') === 'active' || request('status') === 'all') ? 'active' : '' }}" style="text-decoration: none;">
            Semua Antrean Aktif <span id="badge-all" style="background: rgba(255,255,255,0.2); padding: 1px 6px; border-radius: 10px; margin-left: 4px;">{{ $queueCounts['all'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.submissions', ['status' => 'pending']) }}" class="sub-tab-btn {{ request('status') === 'pending' ? 'active' : '' }}" style="text-decoration: none;">
            <span style="width: 6px; height: 6px; border-radius: 50%; background: #D97706; display: inline-block; margin-right: 6px;"></span>
            Menunggu Review <span id="badge-pending" style="background: #FEF3C7; color: #D97706; padding: 1px 6px; border-radius: 10px; margin-left: 4px;">{{ $queueCounts['pending'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.submissions', ['status' => 'revision']) }}" class="sub-tab-btn {{ request('status') === 'revision' ? 'active' : '' }}" style="text-decoration: none;">
            Perlu Revisi Pemilik <span id="badge-revision" style="background: #F1F5F9; color: #64748B; padding: 1px 6px; border-radius: 10px; margin-left: 4px;">{{ $queueCounts['revision'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.submissions', ['status' => 'approved']) }}" class="sub-tab-btn {{ request('status') === 'approved' ? 'active' : '' }}" style="text-decoration: none;">
            Disetujui &amp; Diterbitkan <span id="badge-approved" style="background: #E6FBF5; color: #059669; padding: 1px 6px; border-radius: 10px; margin-left: 4px;">{{ $queueCounts['approved'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.submissions', ['status' => 'rejected']) }}" class="sub-tab-btn {{ request('status') === 'rejected' ? 'active' : '' }}" style="text-decoration: none;">
            Ditolak <span id="badge-rejected" style="background: #F1F5F9; color: #64748B; padding: 1px 6px; border-radius: 10px; margin-left: 4px;">{{ $queueCounts['rejected'] ?? 0 }}</span>
        </a>
    </div>

    <!-- Antrian Horizontal Cards Row -->
    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-size: 0.74rem; flex-wrap: wrap; gap: 6px;">
            <div style="font-weight: 800; color: #64748B; letter-spacing: 0.6px; text-transform: uppercase;">
                ANTRIAN VERIFIKASI MASUK <span id="queue-stored-text" style="font-weight: 500; color: #94A3B8; text-transform: none; margin-left: 6px;">{{ count($submissions) }} Berkas Tersimpan</span>
            </div>
            <span style="color: #64748B; font-size: 0.72rem;">Klik kartu untuk meninjau detail berkas di bawah</span>
        </div>

        <div class="submissions-queue-grid" id="queue-grid">
            @forelse($submissions as $index => $sub)
            @php
                $statusPillClass = 'queue-pill-yellow';
                $statusText = 'Review';
                if (($sub['status'] ?? '') === 'approved') {
                    $statusPillClass = 'queue-pill-green';
                    $statusText = 'Disetujui';
                } elseif (($sub['status'] ?? '') === 'rejected') {
                    $statusPillClass = 'queue-pill-gray';
                    $statusText = 'Ditolak';
                } elseif (($sub['status'] ?? '') === 'revision') {
                    $statusPillClass = 'queue-pill-red';
                    $statusText = 'Revisi';
                }
            @endphp
            <div class="queue-card {{ $index === 0 ? 'active' : '' }}" 
                 data-status="{{ $sub['status'] ?? 'pending' }}" 
                 data-sub-id="{{ $sub['id'] }}"
                 onclick="selectSubmissionById('{{ $sub['id'] }}', this)">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <strong style="color: #0E1E2E; font-size: 0.78rem;">{{ $sub['id'] }}</strong>
                    <span class="{{ $statusPillClass }}">&bull; {{ $statusText }}</span>
                </div>
                <h4 style="font-size: 0.88rem; font-weight: 800; color: #0E1E2E; margin: 0 0 2px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ $sub['title'] }}
                </h4>
                <div style="font-size: 0.74rem; color: #64748B; margin-bottom: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ $sub['location'] }}
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.72rem; color: #64748B; padding-top: 6px; border-top: 1px solid #F1F5F9;">
                    <span style="font-weight: 600; color: #334155;">{{ $sub['owner'] }}</span>
                    <strong style="color: #0E1E2E;">{{ $sub['time_ago'] ?? 'Baru saja' }}</strong>
                </div>
            </div>
            @empty
            <div style="grid-column: 1 / -1; padding: 24px; text-align: center; background: #fff; border-radius: 8px; border: 1px dashed #CBD5E1; color: #64748B; font-size: 0.85rem;">
                Belum ada pengajuan titip properti yang masuk. Formulir di halaman publik dapat digunakan untuk menguji integrasi.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Main Inspection Deck -->
    @php
        $initSub = $selectedSubmission ?? ($submissions[0] ?? null);
    @endphp
    @if($initSub)
    <div id="deck-wrapper" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 24px 28px; box-shadow: 0 1px 4px rgba(0,0,0,0.02); display: flex; flex-direction: column; gap: 20px;">
        
        <!-- Deck Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 14px; padding-bottom: 16px; border-bottom: 1px solid #F1F5F9;" class="deck-header-row">
            <div style="flex: 1; min-width: 260px;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; flex-wrap: wrap;">
                    <span id="deck-id" style="background: #0E1E2E; color: #FFFFFF; font-size: 0.72rem; font-weight: 800; padding: 3px 8px; border-radius: 4px; white-space: nowrap; display: inline-block;">{{ $initSub['id'] ?? '' }}</span>
                    <span id="deck-trx-type" style="background: #E0F2FE; color: #0284C7; font-size: 0.72rem; font-weight: 800; padding: 3px 8px; border-radius: 4px; text-transform: uppercase; white-space: nowrap; display: inline-block;">
                        {{ $initSub['category'] ?? 'RUMAH TINGGAL' }} ({{ strtoupper($initSub['trx_type'] ?? 'JUAL') }})
                    </span>
                    <span id="deck-time" style="font-size: 0.76rem; color: #64748B; white-space: nowrap;">&bull; Terdaftar: {{ $initSub['time_ago'] ?? 'Baru saja' }}</span>
                </div>
                <h2 id="deck-title" style="font-size: 1.45rem; font-weight: 800; color: #0E1E2E; margin: 0 0 6px 0; line-height: 1.25;">
                    {{ $initSub['title'] ?? '' }}
                </h2>
                <div id="deck-location" style="font-size: 0.8rem; color: #64748B; line-height: 1.4;">
                    {{ $initSub['address'] ?? ($initSub['location'] ?? '') }}
                </div>
            </div>

            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <span id="deck-priority-pill" style="background: #E0F7FA; color: #00838F; font-size: 0.74rem; font-weight: 800; padding: 4px 10px; border-radius: 6px; display: inline-flex; align-items: center; gap: 5px; white-space: nowrap;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
                    <span>{{ $initSub['priority'] ?? 'P1 - Hot Asset' }}</span>
                </span>
                <span id="deck-status-pill" style="background: #FEF3C7; color: #D97706; font-size: 0.74rem; font-weight: 800; padding: 4px 10px; border-radius: 6px; white-space: nowrap;">
                    &bull; {{ ucfirst($initSub['status'] ?? 'pending') }}
                </span>
            </div>
        </div>

        <!-- 2 Section Grid: Data Pemilik & Spesifikasi -->
        <div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 18px;" id="deck-data-grid">
            
            <!-- Left Card: Data Pemilik -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 16px 18px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 6px;">
                    <div style="font-size: 0.76rem; font-weight: 800; color: #0E1E2E; display: flex; align-items: center; gap: 6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>Data Pemilik</span>
                    </div>
                    <span id="deck-ownership" style="background: #E0F2FE; color: #0284C7; font-size: 0.68rem; font-weight: 700; padding: 2px 6px; border-radius: 4px; white-space: nowrap;">{{ $initSub['ownership'] ?? 'Pemilik Langsung' }}</span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px; font-size: 0.8rem;">
                    <div>
                        <span style="font-size: 0.7rem; color: #64748B; display: block;">Nama Lengkap</span>
                        <strong id="deck-owner-name" style="color: #0E1E2E; font-size: 0.88rem;">{{ $initSub['owner'] ?? '' }}</strong>
                    </div>

                    <div>
                        <span style="font-size: 0.7rem; color: #64748B; display: block;">Kontak WhatsApp</span>
                        <div style="display: flex; align-items: center; gap: 8px; margin-top: 3px; flex-wrap: wrap;">
                            <strong id="deck-owner-phone" style="color: #0E1E2E; font-size: 0.86rem; white-space: nowrap;">{{ $initSub['phone'] ?? '' }}</strong>
                            <a id="deck-owner-wa-link" href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $initSub['phone'] ?? '') }}" target="_blank" style="background: #00838F; color: #fff; padding: 3px 8px; border-radius: 4px; font-size: 0.72rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                <span>Chat WA</span>
                            </a>
                        </div>
                    </div>

                    <div>
                        <span style="font-size: 0.7rem; color: #64748B; display: block;">Email</span>
                        <span id="deck-owner-email" style="color: #0E1E2E; font-size: 0.82rem; word-break: break-all;">{{ $initSub['email'] ?? '' }}</span>
                    </div>
                </div>
            </div>

            <!-- Right Card: Spesifikasi & Nilai Pasar -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 16px 18px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 6px;">
                    <div style="font-size: 0.76rem; font-weight: 800; color: #0E1E2E; display: flex; align-items: center; gap: 6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        <span>Spesifikasi &amp; Nilai Pasar</span>
                    </div>
                    <span style="color: #64748B; font-size: 0.72rem; white-space: nowrap;">Estimasi Valuasi: <strong id="deck-valuation" style="color: #0E1E2E;">{{ $initSub['valuation_range'] ?? 'Rp 3M - 3,5M' }}</strong></span>
                </div>

                <div class="deck-spec-grid">
                    <div class="deck-spec-col">
                        <span style="font-size: 0.7rem; color: #64748B; display: block;">Harga Pengajuan</span>
                        <strong id="deck-price" style="color: #0E1E2E; font-size: 0.95rem; display: block; margin-top: 2px;">{{ $initSub['price'] }}</strong>
                        <span id="deck-legal" style="color: #00838F; font-size: 0.72rem; font-weight: 600;">{{ $initSub['legal'] ?? 'SHM' }}</span>
                    </div>

                    <div class="deck-spec-col">
                        <span style="font-size: 0.7rem; color: #64748B; display: block;">Luas Tanah / Bangunan</span>
                        <strong id="deck-land" style="color: #0E1E2E; font-size: 0.95rem; display: block; margin-top: 2px;">LT {{ $initSub['land_size'] ?? 0 }} m²</strong>
                        <span id="deck-building" style="color: #64748B; font-size: 0.72rem;">LB {{ $initSub['building_size'] ?? 0 }} m²</span>
                    </div>

                    <div class="deck-spec-col">
                        <span style="font-size: 0.7rem; color: #64748B; display: block;">Kamar Tidur / Mandi</span>
                        <strong id="deck-rooms" style="color: #0E1E2E; font-size: 0.95rem; display: block; margin-top: 2px;">{{ $initSub['bedrooms'] ?? '3 KT' }} / {{ $initSub['bathrooms'] ?? '2 KM' }}</strong>
                        <span id="deck-electricity" style="color: #64748B; font-size: 0.72rem;">Listrik {{ $initSub['electricity'] ?? '3500 VA' }}</span>
                    </div>

                    <div class="deck-spec-col">
                        <span style="font-size: 0.7rem; color: #64748B; display: block;">Tipe Transaksi</span>
                        <strong id="deck-trx" style="color: #0E1E2E; font-size: 0.95rem; display: block; margin-top: 2px;">{{ $initSub['trx_type'] ?? 'Jual' }}</strong>
                        <span id="deck-category" style="color: #64748B; font-size: 0.72rem;">{{ $initSub['category'] ?? 'Properti' }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Foto & Dokumen Legalitas -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 6px;">
                <div style="font-size: 0.76rem; font-weight: 800; color: #0E1E2E; display: flex; align-items: center; gap: 6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    <span>Foto &amp; Dokumen Lampiran Pengajuan</span>
                </div>
                <span style="font-size: 0.72rem; color: #94A3B8;">Tinjau berkas visual resolusi asli</span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 10px;" id="photos-row">
                @php
                    $photos = $initSub['photos'] ?? [];
                    if (empty($photos)) {
                        $photos = ['https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=400&q=80'];
                    }
                @endphp
                @foreach($photos as $pIndex => $pUrl)
                <div class="berkas-photo-thumb">
                    <img src="{{ $pUrl }}" alt="Dokumen {{ $pIndex + 1 }}">
                    <span class="berkas-photo-label">Foto {{ $pIndex + 1 }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Checklist Kurasi Standar & Catatan Kurator -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;" id="deck-bottom-grid">
            
            <!-- Checklist Card -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 14px 18px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 6px;">
                    <strong style="color: #0E1E2E; font-size: 0.78rem;">Checklist Verifikasi Kurator</strong>
                    <span style="background: #E0F2FE; color: #0284C7; font-size: 0.7rem; font-weight: 700; padding: 2px 7px; border-radius: 4px;">Standar Kumala</span>
                </div>

                <div class="deck-checklist-grid">
                    <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                        <input type="checkbox" checked style="accent-color: #00838F;">
                        <span style="color: #0E1E2E; font-weight: 600;">Identitas &amp; kontak valid</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                        <input type="checkbox" checked style="accent-color: #00838F;">
                        <span style="color: #0E1E2E; font-weight: 600;">Lokasi Maps akurat</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                        <input type="checkbox" checked style="accent-color: #00838F;">
                        <span style="color: #0E1E2E; font-weight: 600;">Legalitas sesuai kepemilikan</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                        <input type="checkbox" checked style="accent-color: #00838F;">
                        <span style="color: #0E1E2E; font-weight: 600;">Foto layak publikasi</span>
                    </label>
                </div>
            </div>

            <!-- Catatan Tim Kurator -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 14px 18px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 6px;">
                    <strong style="color: #0E1E2E; font-size: 0.78rem;">Catatan Kurator / Pemohon</strong>
                    <span style="font-size: 0.7rem; color: #94A3B8;">Tinjauan Internal</span>
                </div>
                <div id="deck-notes" style="font-size: 0.76rem; color: #475569; line-height: 1.45; background: #FFFFFF; border: 1px solid #E2E8F0; padding: 8px 10px; border-radius: 6px; min-height: 52px;">
                    {{ $initSub['notes'] ?? 'Pengajuan online via portal Titip Properti Kumala. Siap diverifikasi.' }}
                </div>
            </div>

        </div>

        <!-- Action Footer Buttons -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 1px solid #F1F5F9; flex-wrap: wrap; gap: 10px;" class="deck-footer-actions">
            <div style="display: {{ ($initSub['status'] ?? '') === 'approved' ? 'none' : 'flex' }}; gap: 8px;" class="deck-footer-secondary" id="deck-secondary-actions">
                <button type="button" class="btn-deck-reject" id="btn-action-reject" onclick="handleRejectSubmission()">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    <span>Tolak Pengajuan</span>
                </button>
                <button type="button" class="btn-deck-outline" id="btn-action-revision" onclick="handleRevisionSubmission()">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    <span>Minta Revisi Berkas</span>
                </button>
            </div>

            <div style="display: flex; gap: 8px;" class="deck-footer-primary">
                @if(($initSub['status'] ?? '') === 'approved')
                <button type="button" class="btn-deck-approve" id="btn-action-approve" disabled style="background: #059669; border-color: #059669; cursor: default;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Telah Tayang di Web Publik</span>
                </button>
                @else
                <button type="button" class="btn-deck-approve" id="btn-action-approve" onclick="handleApproveSubmission()">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Setujui &amp; Publikasikan ke Web</span>
                </button>
                @endif
            </div>
        </div>

    </div>
    @endif

    <!-- Deck Empty State when no submissions are left -->
    <div id="deck-empty-state" style="{{ $initSub ? 'display: none;' : '' }} background: #FFFFFF; border: 1px dashed #CBD5E1; border-radius: 12px; padding: 48px 24px; text-align: center; color: #64748B;">
        <div style="width: 48px; height: 48px; border-radius: 50%; background: #E6FBF5; color: #059669; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px auto;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h3 style="font-size: 1.1rem; color: #0E1E2E; font-weight: 700; margin: 0 0 6px 0;">Semua Berkas Telah Diverifikasi</h3>
        <p style="font-size: 0.85rem; margin: 0;">Tidak ada pengajuan yang sedang menunggu tindakan di antrean ini.</p>
    </div>

</div>
@endsection

@section('styles')
<style>
.sub-tabs-wrapper {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    padding-bottom: 4px;
    scrollbar-width: none;
    width: 100%;
}
.sub-tabs-wrapper::-webkit-scrollbar {
    display: none;
}

.sub-tab-btn {
    padding: 7px 14px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    font-size: 0.78rem;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    white-space: nowrap;
    flex-shrink: 0;
    transition: all 0.15s;
}
.sub-tab-btn:hover {
    border-color: #CBD5E1;
    color: #0E1E2E;
}
.sub-tab-btn.active {
    background: #0E1E2E;
    color: #FFFFFF;
    border-color: #0E1E2E;
    font-weight: 700;
}

.submissions-queue-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 14px;
    width: 100%;
}

.queue-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 12px 14px;
    cursor: pointer;
    transition: all 0.15s;
    box-sizing: border-box;
}
.queue-card:hover {
    border-color: #CBD5E1;
}
.queue-card.active {
    border-color: #00838F;
    box-shadow: 0 0 0 2px rgba(0, 131, 143, 0.2);
}

.queue-pill-yellow {
    background: #FEF3C7;
    color: #D97706;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 4px;
    white-space: nowrap;
}
.queue-pill-green {
    background: #E6FBF5;
    color: #059669;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 4px;
    white-space: nowrap;
}
.queue-pill-red {
    background: #FEE2E2;
    color: #DC2626;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 4px;
    white-space: nowrap;
}
.queue-pill-gray {
    background: #F1F5F9;
    color: #64748B;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 4px;
    white-space: nowrap;
}

.deck-spec-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    font-size: 0.78rem;
    width: 100%;
}
.deck-spec-col {
    min-width: 0;
}
.deck-spec-col strong {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
}

.deck-checklist-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    font-size: 0.78rem;
}

.berkas-photo-thumb {
    position: relative;
    border-radius: 6px;
    overflow: hidden;
    height: 64px;
    background: #0E1E2E;
}
.berkas-photo-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.berkas-photo-label {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(14, 30, 46, 0.85);
    color: #FFFFFF;
    font-size: 0.62rem;
    font-weight: 700;
    padding: 2px 4px;
    text-align: center;
}

.btn-deck-reject {
    background: #FFFFFF;
    border: 1px solid #FCA5A5;
    color: #DC2626;
    height: 38px;
    padding: 0 14px;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
    box-sizing: border-box;
    white-space: nowrap;
    transition: all 0.15s;
}
.btn-deck-reject:hover {
    background: #FEF2F2;
    border-color: #F87171;
}

.btn-deck-outline {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    color: #0E1E2E;
    height: 38px;
    padding: 0 14px;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
    box-sizing: border-box;
    white-space: nowrap;
    transition: all 0.15s;
}
.btn-deck-outline:hover {
    background: #F8FAFC;
    border-color: #94A3B8;
}

.btn-deck-approve {
    background: #00838F;
    border: 1px solid #00838F;
    color: #FFFFFF;
    height: 38px;
    padding: 0 16px;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
    box-sizing: border-box;
    white-space: nowrap;
    transition: background 0.15s;
}
.btn-deck-approve:hover {
    background: #0097A7;
    border-color: #0097A7;
}

/* Tablet & Intermediate Screens (<= 1024px) */
@media (max-width: 1024px) {
    #deck-data-grid {
        grid-template-columns: 1fr !important;
    }
    #photos-row {
        grid-template-columns: repeat(3, 1fr) !important;
    }
    #deck-bottom-grid {
        grid-template-columns: 1fr !important;
    }
}

/* Phablet & Tablet (<= 768px) */
@media (max-width: 768px) {
    .deck-spec-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 12px 14px;
    }
}

/* Mobile Screens (<= 640px) */
@media (max-width: 640px) {
    #deck-wrapper {
        padding: 16px 14px !important;
        border-radius: 10px;
    }
    .submissions-queue-grid {
        grid-template-columns: 1fr !important;
    }
    .sub-priority-card {
        width: 100%;
        justify-content: space-between;
    }
    #photos-row {
        grid-template-columns: repeat(2, 1fr) !important;
    }
    .deck-footer-actions {
        flex-direction: column;
        align-items: stretch !important;
        gap: 10px;
    }
    .deck-footer-secondary {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        width: 100%;
    }
    .deck-footer-primary {
        width: 100%;
    }
    .btn-deck-reject,
    .btn-deck-outline,
    .btn-deck-approve {
        width: 100%;
        justify-content: center;
        text-align: center;
        height: 42px;
    }
}

/* Very Small Mobile (<= 420px) */
@media (max-width: 420px) {
    .deck-spec-grid {
        grid-template-columns: 1fr !important;
    }
    .deck-checklist-grid {
        grid-template-columns: 1fr !important;
    }
    .deck-footer-secondary {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection

@section('scripts')
<script>
const submissionsData = @json($submissions ?? []);
let activeSubData = @json($initSub ?? null);

function selectSubmissionById(id, el) {
    const found = submissionsData.find(s => String(s.id) === String(id));
    if (found) {
        selectSubmission(found, el);
    }
}

function selectSubmission(sub, el) {
    activeSubData = sub;
    
    // Highlight card
    document.querySelectorAll('.queue-card').forEach(c => c.classList.remove('active'));
    if (el) el.classList.add('active');

    // Update Deck
    const deckId = document.getElementById('deck-id');
    if (deckId) deckId.innerText = sub.id || '';
    
    const deckTrx = document.getElementById('deck-trx-type');
    if (deckTrx) deckTrx.innerText = (sub.category || 'PROPERTI') + ' (' + (sub.trx_type || 'JUAL').toUpperCase() + ')';
    
    const deckTime = document.getElementById('deck-time');
    if (deckTime) deckTime.innerText = '• Terdaftar: ' + (sub.time_ago || 'Baru saja');
    
    const deckTitle = document.getElementById('deck-title');
    if (deckTitle) deckTitle.innerText = sub.title || '';
    
    const deckLoc = document.getElementById('deck-location');
    if (deckLoc) deckLoc.innerText = sub.address || sub.location || '';
    
    const deckOwner = document.getElementById('deck-owner-name');
    if (deckOwner) deckOwner.innerText = sub.owner || '';
    
    const deckPhone = document.getElementById('deck-owner-phone');
    if (deckPhone) deckPhone.innerText = sub.phone || '';
    
    const deckEmail = document.getElementById('deck-owner-email');
    if (deckEmail) deckEmail.innerText = sub.email || '';
    
    const phoneClean = String(sub.phone || '').replace(/[^0-9]/g, '');
    const waLink = document.getElementById('deck-owner-wa-link');
    if (waLink) waLink.href = 'https://wa.me/' + phoneClean;

    const deckPrice = document.getElementById('deck-price');
    if (deckPrice) deckPrice.innerText = sub.price || 'Rp 0';
    
    const deckLegal = document.getElementById('deck-legal');
    if (deckLegal) deckLegal.innerText = sub.legal || 'SHM';
    
    const deckLand = document.getElementById('deck-land');
    if (deckLand) deckLand.innerText = 'LT ' + (sub.land_size || 0) + ' m²';
    
    const deckBldg = document.getElementById('deck-building');
    if (deckBldg) deckBldg.innerText = 'LB ' + (sub.building_size || 0) + ' m²';
    
    const deckRooms = document.getElementById('deck-rooms');
    if (deckRooms) deckRooms.innerText = (sub.bedrooms || '3 KT') + ' / ' + (sub.bathrooms || '2 KM');
    
    const deckElec = document.getElementById('deck-electricity');
    if (deckElec) deckElec.innerText = 'Listrik ' + (sub.electricity || '3500 VA');
    
    const deckTrxSub = document.getElementById('deck-trx');
    if (deckTrxSub) deckTrxSub.innerText = sub.trx_type || 'Jual';
    
    const deckCat = document.getElementById('deck-category');
    if (deckCat) deckCat.innerText = sub.category || 'Rumah Mewah';
    
    const deckNotes = document.getElementById('deck-notes');
    if (deckNotes) deckNotes.innerText = sub.notes || 'Pengajuan via portal Titip Properti.';

    const deckVal = document.getElementById('deck-valuation');
    if (deckVal) deckVal.innerText = sub.valuation_range || 'Dalam Estimasi';
    
    // Update status badge
    const statusPill = document.getElementById('deck-status-pill');
    if (statusPill) {
        statusPill.innerText = '• ' + (sub.status ? sub.status.toUpperCase() : 'PENDING');
        if (sub.status === 'approved') {
            statusPill.style.background = '#E6FBF5';
            statusPill.style.color = '#059669';
        } else if (sub.status === 'rejected') {
            statusPill.style.background = '#FEE2E2';
            statusPill.style.color = '#DC2626';
        } else if (sub.status === 'revision') {
            statusPill.style.background = '#F1F5F9';
            statusPill.style.color = '#64748B';
        } else {
            statusPill.style.background = '#FEF3C7';
            statusPill.style.color = '#D97706';
        }
    }

    // Update photos row
    const photosRow = document.getElementById('photos-row');
    if (photosRow) {
        const photoList = Array.isArray(sub.photos) && sub.photos.length > 0 
            ? sub.photos 
            : ['https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=400&q=80'];
        let photosHtml = '';
        photoList.forEach((url, i) => {
            photosHtml += `
                <div class="berkas-photo-thumb">
                    <img src="${url}" alt="Dokumen ${i+1}">
                    <span class="berkas-photo-label">Foto ${i+1}</span>
                </div>
            `;
        });
        photosRow.innerHTML = photosHtml;
    }

    // Update action buttons state
    const btnApprove = document.getElementById('btn-action-approve');
    const secActions = document.getElementById('deck-secondary-actions');
    if (btnApprove) {
        if (sub.status === 'approved') {
            btnApprove.disabled = true;
            btnApprove.style.background = '#059669';
            btnApprove.style.borderColor = '#059669';
            btnApprove.style.cursor = 'default';
            btnApprove.innerHTML = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg><span>Telah Tayang di Web Publik</span>`;
            if (secActions) secActions.style.display = 'none';
        } else {
            btnApprove.disabled = false;
            btnApprove.style.background = '#00838F';
            btnApprove.style.borderColor = '#00838F';
            btnApprove.style.cursor = 'pointer';
            btnApprove.innerHTML = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg><span>Setujui &amp; Publikasikan ke Web</span>`;
            if (secActions) secActions.style.display = 'flex';
        }
    }
}

function handleApproveSubmission() {
    if (!activeSubData || !activeSubData.id) {
        showToast('Pilih berkas pengajuan terlebih dahulu.');
        return;
    }

    const approvedId = String(activeSubData.id);
    const btn = document.getElementById('btn-action-approve');
    btn.disabled = true;
    btn.innerText = 'Menerbitkan Listing...';

    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}';

    fetch('/admin/listing-masuk/' + encodeURIComponent(approvedId) + '/approve', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            notes: 'Disetujui dan diverifikasi oleh Tim Kurator Kumala Property.'
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(`Sukses! Pengajuan ${approvedId} telah disetujui & otomatis tayang di katalog publik!`);

            // 1. Update queue count badges
            if (data.pending_count !== undefined) {
                const pendText = document.getElementById('pending-count-text');
                if (pendText) pendText.innerText = data.pending_count + ' Menunggu';
                const badgePend = document.getElementById('badge-pending');
                if (badgePend) badgePend.innerText = data.pending_count;
            }
            if (data.active_count !== undefined) {
                const badgeAll = document.getElementById('badge-all');
                if (badgeAll) badgeAll.innerText = data.active_count;
            }
            if (data.approved_count !== undefined) {
                const badgeApp = document.getElementById('badge-approved');
                if (badgeApp) badgeApp.innerText = data.approved_count;
            }

            // 2. Remove the approved card from active queue
            const cardEl = document.querySelector(`.queue-card[data-sub-id="${approvedId}"]`);
            if (cardEl) {
                cardEl.remove();
            }

            // 3. Update local submissionsData array
            const removeIdx = submissionsData.findIndex(s => String(s.id) === approvedId);
            if (removeIdx !== -1) {
                submissionsData.splice(removeIdx, 1);
            }

            // 4. Update stored count text
            const storedText = document.getElementById('queue-stored-text');
            if (storedText) {
                storedText.innerText = submissionsData.length + ' Berkas Tersimpan';
            }

            // 5. Select next item in queue or show empty state
            const remainingCards = document.querySelectorAll('.queue-card');
            if (remainingCards.length > 0 && submissionsData.length > 0) {
                const nextCard = remainingCards[0];
                const nextId = nextCard.getAttribute('data-sub-id');
                const nextSub = submissionsData.find(s => String(s.id) === String(nextId)) || submissionsData[0];
                selectSubmission(nextSub, nextCard);
            } else {
                activeSubData = null;
                const deckWrapper = document.getElementById('deck-wrapper');
                if (deckWrapper) deckWrapper.style.display = 'none';
                const emptyState = document.getElementById('deck-empty-state');
                if (emptyState) emptyState.style.display = 'block';

                const queueGrid = document.getElementById('queue-grid');
                if (queueGrid) {
                    queueGrid.innerHTML = `
                        <div id="queue-empty-msg" style="grid-column: 1 / -1; padding: 24px; text-align: center; background: #fff; border-radius: 8px; border: 1px dashed #CBD5E1; color: #64748B; font-size: 0.85rem;">
                            Semua pengajuan telah diverifikasi. Tidak ada berkas yang menunggu review di antrean ini.
                        </div>
                    `;
                }
            }
        } else {
            showToast('Gagal memproses persetujuan berkas.');
            btn.disabled = false;
            btn.innerHTML = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg><span>Setujui &amp; Publikasikan ke Web</span>`;
        }
    })
    .catch(err => {
        console.error(err);
        showToast('Terjadi kesalahan jaringan.');
        btn.disabled = false;
        btn.innerHTML = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg><span>Setujui &amp; Publikasikan ke Web</span>`;
    });
}

function handleRejectSubmission() {
    if (!activeSubData || !activeSubData.id) {
        showToast('Pilih berkas pengajuan terlebih dahulu.');
        return;
    }

    const reason = prompt('Masukkan alasan penolakan pengajuan ' + activeSubData.id + ':', 'Berkas ditolak setelah pemeriksaan spesifikasi fisik dan kelengkapan dokumen.');
    if (reason === null) return; // Batal jika tekan cancel

    updateStatusDirect(activeSubData.id, 'rejected', reason.trim() || 'Berkas ditolak oleh kurator.');
}

function handleRevisionSubmission() {
    if (!activeSubData || !activeSubData.id) return;
    const notes = prompt('Masukkan catatan revisi untuk pemilik properti:', 'Mohon unggah foto sertifikat BPN dengan resolusi lebih tajam.');
    if (notes === null) return;

    updateStatusDirect(activeSubData.id, 'revision', notes);
}

function updateStatusDirect(id, status, notes) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}';

    fetch('/admin/listing-masuk/' + encodeURIComponent(id) + '/status', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            status: status,
            notes: notes
        })
    })
    .then(res => res.json())
    .then(data => {
        showToast(`Status pengajuan ${id} berhasil diperbarui menjadi ${status.toUpperCase()}.`);
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    })
    .catch(err => {
        console.error(err);
        showToast('Terjadi kesalahan.');
    });
}
</script>
@endsection
