@extends('layouts.admin')

@section('title', 'Admin Dashboard - Kumala Property')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- 1. Operational Hero Banner -->
    <div style="background: linear-gradient(135deg, #0C1E32 0%, #102A45 100%); border-radius: 14px; padding: 26px 30px; color: #FFFFFF; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; box-shadow: 0 4px 20px rgba(12, 30, 50, 0.08); border: 1px solid rgba(255, 255, 255, 0.08);">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                <span style="background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255,255,255,0.15); color: #E2E8F0; font-size: 0.68rem; font-weight: 700; letter-spacing: 0.8px; padding: 4px 10px; border-radius: 20px; text-transform: uppercase;">
                    RINGKASAN OPERASIONAL
                </span>
                <span style="font-size: 0.78rem; color: #94A3B8; display: inline-flex; align-items: center; gap: 5px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    {{ date('l, d F Y') }}
                </span>
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: #FFFFFF; margin: 4px 0 6px 0; letter-spacing: -0.02em;">
                Selamat datang Admin!
            </h1>
            <p style="font-size: 0.88rem; color: #CBD5E1; margin: 0; line-height: 1.5;">
                Ada {{ $metrics['pending_review'] ?? 0 }} pengajuan listing baru dan {{ $metrics['new_inquiries'] ?? 0 }} pesan inquiry yang siap diverifikasi &amp; ditindaklanjuti.
            </p>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="button" class="btn-banner-action" onclick="showToast('Mengekspor Laporan Operasional Harian (.CSV)...')">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                <span>Ekspor Laporan Harian</span>
            </button>
            <a href="{{ route('admin.properties', ['action' => 'add']) }}" class="btn-banner-primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="16"/><line x1="8" x2="16" y1="12" y2="12"/></svg>
                <span>+ Input Properti Baru</span>
            </a>
        </div>
    </div>

    <!-- 2. KPI Metrics Grid (4 Cards) -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px;" id="metrics-grid">
        
        <!-- Metric 1: Properti Aktif Tayang -->
        <div class="kpi-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                <span class="kpi-label">Properti Aktif Tayang</span>
                <div class="kpi-icon-box" style="background: #E0F7FA; color: #00838F;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><line x1="9" x2="9" y1="22" y2="22"/><line x1="9" x2="9" y1="6" y2="6"/><line x1="15" x2="15" y1="6" y2="6"/><line x1="9" x2="9" y1="10" y2="10"/><line x1="15" x2="15" y1="10" y2="10"/><line x1="9" x2="9" y1="14" y2="14"/><line x1="15" x2="15" y1="14" y2="14"/><line x1="9" x2="9" y1="18" y2="18"/><line x1="15" x2="15" y1="18" y2="18"/></svg>
                </div>
            </div>
            <div class="kpi-value-row">
                <span class="kpi-number">{{ $metrics['active_units'] ?? count($properties) }}</span>
                <span class="kpi-unit">Unit Aktif</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.76rem; color: #64748B; margin-top: 10px; margin-bottom: 8px;">
                <span style="color: #059669; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    Katalog Terbit
                </span>
                <span>{{ $metrics['active_jual'] ?? 6 }} Jual &bull; {{ $metrics['active_sewa'] ?? 3 }} Sewa</span>
            </div>
            <!-- Dual progress bar -->
            <div style="height: 5px; background: #E2E8F0; border-radius: 4px; display: flex; overflow: hidden;">
                <div style="width: 65%; background: #00838F;"></div>
                <div style="width: 35%; background: #67E8F9;"></div>
            </div>
        </div>

        <!-- Metric 2: Menunggu Review -->
        <div class="kpi-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                <span class="kpi-label">Menunggu Review</span>
                <div class="kpi-icon-box" style="background: #FEF3C7; color: #D97706;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/></svg>
                </div>
            </div>
            <div class="kpi-value-row">
                <span class="kpi-number">{{ $metrics['pending_review'] ?? 0 }}</span>
                <span class="kpi-unit">Pengajuan</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.76rem; margin-top: 14px;">
                <span style="display: inline-flex; align-items: center; gap: 6px; color: #475569; font-weight: 500;">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #D97706; display: inline-block;"></span>
                    Titip Properti
                </span>
                <a href="{{ route('admin.submissions') }}" style="color: #00838F; font-weight: 700; text-decoration: none;">Tinjau Antrean &rarr;</a>
            </div>
        </div>

        <!-- Metric 3: Inquiry Baru Masuk -->
        <div class="kpi-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                <span class="kpi-label">Inquiry Baru Masuk</span>
                <div class="kpi-icon-box" style="background: #E0F7FA; color: #00838F;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
            </div>
            <div class="kpi-value-row">
                <span class="kpi-number">{{ $metrics['new_inquiries'] ?? 0 }}</span>
                <span class="kpi-unit">Prospek Klien</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.76rem; margin-top: 14px;">
                <span style="background: #E0F7FA; color: #00838F; padding: 2px 8px; border-radius: 4px; font-weight: 700; font-size: 0.72rem;">SLA &lt; 15 Mnt</span>
                <a href="{{ route('admin.inquiries') }}" style="color: #00838F; font-weight: 700; text-decoration: none;">Buka Leads &rarr;</a>
            </div>
        </div>

        <!-- Metric 4: Closing Bulan Ini -->
        <div class="kpi-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                <span class="kpi-label">Closing Transaksi</span>
                <div class="kpi-icon-box" style="background: #E6FBF5; color: #059669;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                </div>
            </div>
            <div class="kpi-value-row">
                <span class="kpi-number">{{ $metrics['closing_month_units'] ?? 0 }}</span>
                <span class="kpi-unit" style="font-size: 0.78rem; max-width: 100px; line-height: 1.2;">Unit Selesai</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.76rem; color: #64748B; margin-top: 10px; margin-bottom: 8px;">
                <strong style="color: #0F172A; font-size: 0.82rem;">{{ $metrics['closing_month_val'] ?? 'Rp 18.4 Miliar' }}</strong>
                <a href="{{ route('admin.transactions') }}" style="color: #059669; font-weight: 700; text-decoration: none;">Lihat Arsip</a>
            </div>
            <!-- Progress bar -->
            <div style="height: 5px; background: #E2E8F0; border-radius: 4px; overflow: hidden;">
                <div style="width: 85%; height: 100%; background: #0E1E2E; border-radius: 4px;"></div>
            </div>
        </div>

    </div>

    <!-- 3. Priority Actions Section ("Perlu Ditindaklanjuti Segera") -->
    <div class="priority-actions-card">
        @php
            $countAll = count($priorityActions);
            $countListing = count(array_filter($priorityActions, fn($a) => ($a['category'] ?? '') === 'listing'));
            $countInquiry = count(array_filter($priorityActions, fn($a) => ($a['category'] ?? '') === 'inquiry'));
            $countClosing = count(array_filter($priorityActions, fn($a) => ($a['category'] ?? '') === 'closing'));
        @endphp
        <div class="priority-actions-header">
            <div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #EF4444; display: inline-block;"></span>
                    <h2 style="font-size: 1.15rem; color: #0E1E2E; font-weight: 800; margin: 0;">Perlu Ditindaklanjuti Segera</h2>
                </div>
                <p style="font-size: 0.8rem; color: #64748B; margin: 4px 0 0 16px;">
                    Daftar item prioritas tinggi yang membutuhkan keputusan atau interaksi admin hari ini.
                </p>
            </div>

            <!-- Tabs Filter -->
            <div class="inbox-tabs-wrapper">
                <button type="button" class="inbox-tab-btn active" onclick="filterActionTabs('all', this)">Semua ({{ $countAll }})</button>
                <button type="button" class="inbox-tab-btn" onclick="filterActionTabs('listing', this)">Listing ({{ $countListing }})</button>
                <button type="button" class="inbox-tab-btn" onclick="filterActionTabs('inquiry', this)">Inquiry ({{ $countInquiry }})</button>
                <button type="button" class="inbox-tab-btn" onclick="filterActionTabs('closing', this)">Closing ({{ $countClosing }})</button>
            </div>
        </div>

        <!-- Action Items List -->
        <div class="action-items-container">
            @forelse($priorityActions as $item)
            @php
                $cat = $item['category'] ?? 'listing';
                $tagClass = $item['tag_class'] ?? ($cat === 'inquiry' ? 'badge-peach' : ($cat === 'closing' ? 'badge-teal' : 'badge-cyan'));
                $detailRaw = $item['detail'] ?? $item['price_label'] ?? '';
                $detailParts = explode('|', $detailRaw);
            @endphp
            <div class="action-item-card" data-category="{{ $cat }}">
                <div class="action-item-main">
                    <img src="{{ $item['image'] ?? 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=300&q=80' }}" alt="{{ $item['title'] }}" class="action-item-thumb">
                    <div class="action-item-info">
                        <div class="action-item-header-meta">
                            <span class="badge-tag-custom {{ $tagClass }}">{{ $item['tag_badge'] ?? strtoupper($cat) }}</span>
                            <span class="action-item-time">• {{ $item['time_ago'] ?? 'Baru saja' }}</span>
                        </div>
                        <h3 class="action-item-title">{{ $item['title'] }}</h3>
                        <div class="action-item-meta-row">
                            <div class="action-meta-user">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                <span>{{ $item['owner'] ?? $item['owner_label'] ?? 'Klien Terverifikasi' }}</span>
                            </div>
                            @if(count($detailParts) > 1)
                                <div class="action-meta-tags-wrap">
                                    <span class="action-meta-price">{{ trim($detailParts[0]) }}</span>
                                    <span class="action-meta-bullet">•</span>
                                    <span class="action-meta-loc">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                        <span>{{ trim(str_ireplace('Lokasi:', '', $detailParts[1])) }}</span>
                                    </span>
                                </div>
                            @else
                                <div class="action-meta-tags-wrap">
                                    <span class="action-meta-price">{{ $detailRaw }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="action-item-actions">
                    @if(!empty($item['btn_url_1']) || !empty($item['btn1_url']))
                        <a href="{{ $item['btn_url_1'] ?? $item['btn1_url'] ?? '#' }}" class="btn-action-outline">{{ $item['btn_action_1'] ?? $item['btn1_text'] ?? 'Buka Detail' }}</a>
                    @else
                        <button type="button" class="btn-action-outline" onclick="showToast('Memproses {{ $item['id'] }}...')">{{ $item['btn_action_1'] ?? 'Tindak Lanjut' }}</button>
                    @endif

                    @if(!empty($item['btn_url_2']) || !empty($item['btn2_url']))
                        <a href="{{ $item['btn_url_2'] ?? $item['btn2_url'] ?? '#' }}" target="{{ str_contains($item['btn_url_2'] ?? '', 'wa.me') ? '_blank' : '_self' }}" class="{{ str_contains($item['btn_url_2'] ?? '', 'wa.me') ? 'btn-action-teal' : 'btn-action-dark' }}">
                            @if(str_contains($item['btn_url_2'] ?? '', 'wa.me'))
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            @endif
                            <span>{{ $item['btn_action_2'] ?? $item['btn2_text'] ?? 'Proses' }}</span>
                        </a>
                    @else
                        <button type="button" class="btn-action-dark" onclick="showToast('Membuka kurasi {{ $item['id'] }}...')">Proses</button>
                    @endif
                </div>
            </div>
            @empty
            <div style="padding: 24px; text-align: center; color: #64748B;">
                Semua tugas operasional telah selesai ditindaklanjuti.
            </div>
            @endforelse
        </div>
    </div>

    <!-- 4. Stacked Full-Width Section 1: AUDIT TRAIL -->
    <div style="background: #FFFFFF; border: 1px solid var(--admin-border-subtle); border-radius: 14px; padding: 22px 26px; box-shadow: 0 1px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
            <div>
                <span style="font-size: 0.68rem; font-weight: 800; color: #64748B; letter-spacing: 0.8px; text-transform: uppercase;">AUDIT TRAIL</span>
                <h3 style="font-size: 1.15rem; color: #0E1E2E; font-weight: 800; margin: 2px 0 0 0;">Aktivitas Terkini</h3>
            </div>
            <button type="button" style="background: none; border: none; color: #64748B; cursor: pointer; padding: 4px;" title="Refresh Log" onclick="window.location.reload()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
            </button>
        </div>

        <!-- Activity Timeline List -->
        <div style="display: flex; flex-direction: column; gap: 14px;">
            @forelse($auditLogs as $log)
            <div style="display: flex; gap: 12px; align-items: flex-start;">
                <div style="width: 28px; height: 28px; border-radius: 50%; background: #E0F7FA; color: #00838F; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                </div>
                <div style="font-size: 0.82rem; line-height: 1.4;">
                    <span style="color: #0F172A;">
                        <strong>{{ $log['user'] ?? 'Sistem' }}</strong>: {{ $log['action'] }}
                    </span>
                    <div style="font-size: 0.72rem; color: #94A3B8; margin-top: 2px;">{{ $log['timestamp'] ?? 'Hari ini' }} &bull; Status: {{ $log['status'] ?? 'Recorded' }}</div>
                </div>
            </div>
            @empty
            <div style="padding: 12px; color: #64748B; font-size: 0.8rem;">
                Belum ada catatan aktivitas baru.
            </div>
            @endforelse
        </div>
    </div>

    <!-- 5. Stacked Full-Width Section 2: PORTOFOLIO TAYANG -->
    @php
        $totalActiveUnits = count(array_filter($properties, fn($p) => ($p['status'] ?? 'ACTIVE') === 'ACTIVE'));
        if ($totalActiveUnits === 0) $totalActiveUnits = count($properties);
        
        $countRumah = count(array_filter($properties, fn($p) => stripos($p['category'] ?? '', 'Rumah') !== false));
        $countRuko = count(array_filter($properties, fn($p) => stripos($p['category'] ?? '', 'Ruko') !== false || stripos($p['category'] ?? '', 'Komersial') !== false));
        $countApt = count(array_filter($properties, fn($p) => stripos($p['category'] ?? '', 'Apartemen') !== false || stripos($p['category'] ?? '', 'Penthouse') !== false));
        $countVilla = count(array_filter($properties, fn($p) => stripos($p['category'] ?? '', 'Villa') !== false));
        
        $pctRumah = $totalActiveUnits > 0 ? round(($countRumah / $totalActiveUnits) * 100) : 50;
        $pctRuko = $totalActiveUnits > 0 ? round(($countRuko / $totalActiveUnits) * 100) : 20;
        $pctApt = $totalActiveUnits > 0 ? round(($countApt / $totalActiveUnits) * 100) : 15;
        $pctVilla = $totalActiveUnits > 0 ? round(($countVilla / $totalActiveUnits) * 100) : 15;
    @endphp
    <div style="background: #FFFFFF; border: 1px solid var(--admin-border-subtle); border-radius: 14px; padding: 22px 26px; box-shadow: 0 1px 4px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: #E0F7FA; color: #00838F; display: flex; align-items: center; justify-content: center;">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 10 10h-10z"/></svg>
                </div>
                <div>
                    <span style="font-size: 0.68rem; font-weight: 800; color: #64748B; letter-spacing: 0.8px; text-transform: uppercase;">PORTOFOLIO TAYANG</span>
                    <h3 style="font-size: 1.15rem; color: #0E1E2E; font-weight: 800; margin: 2px 0 0 0;">Distribusi Tipe Properti</h3>
                </div>
            </div>
            <div style="font-size: 0.82rem; color: #64748B;">
                Total Aktif: <strong style="color: #0E1E2E; font-size: 1rem;">{{ $totalActiveUnits }} Unit</strong>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 36px; flex-wrap: wrap;" id="portfolio-dist-row">
            
            <!-- Donut Visual SVG -->
            <div style="position: relative; width: 124px; height: 124px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                <svg width="124" height="124" viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="48" fill="none" stroke="#E2E8F0" stroke-width="16"/>
                    <circle cx="60" cy="60" r="48" fill="none" stroke="#00838F" stroke-width="16"
                            stroke-dasharray="301.59" stroke-dashoffset="{{ 301.59 * (1 - ($pctRumah / 100)) }}" transform="rotate(-90 60 60)"/>
                    <circle cx="60" cy="60" r="48" fill="none" stroke="#0C1E32" stroke-width="16"
                            stroke-dasharray="301.59" stroke-dashoffset="{{ 301.59 * (1 - (($pctRumah + $pctRuko) / 100)) }}" transform="rotate(-90 60 60)"/>
                </svg>
                <div style="position: absolute; text-align: center;">
                    <span style="font-size: 1.35rem; font-weight: 800; color: #0E1E2E; display: block; line-height: 1;">{{ $totalActiveUnits }}</span>
                    <span style="font-size: 0.64rem; font-weight: 700; color: #94A3B8; letter-spacing: 0.8px;">UNIT</span>
                </div>
            </div>

            <!-- 4 Horizontal Items Grid -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; flex: 1; min-width: 320px;" id="portfolio-cols-grid">
                
                <!-- Col 1 -->
                <div>
                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.78rem; margin-bottom: 4px;">
                        <span style="display: inline-flex; align-items: center; gap: 6px; color: #0F172A; font-weight: 600;">
                            <span style="width: 8px; height: 8px; background: #0C1E32; border-radius: 2px; display: inline-block;"></span>
                            Rumah Tapak
                        </span>
                        <strong style="color: #0E1E2E; font-size: 0.88rem;">{{ $countRumah }} Unit</strong>
                    </div>
                    <div style="font-size: 0.72rem; color: #64748B; text-align: right; margin-bottom: 4px;">{{ $pctRumah }}%</div>
                    <div style="height: 4px; background: #E2E8F0; border-radius: 2px; overflow: hidden;">
                        <div style="width: {{ $pctRumah }}%; height: 100%; background: #0C1E32;"></div>
                    </div>
                </div>

                <!-- Col 2 -->
                <div>
                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.78rem; margin-bottom: 4px;">
                        <span style="display: inline-flex; align-items: center; gap: 6px; color: #0F172A; font-weight: 600;">
                            <span style="width: 8px; height: 8px; background: #00838F; border-radius: 2px; display: inline-block;"></span>
                            Ruko &amp; Komersial
                        </span>
                        <strong style="color: #0E1E2E; font-size: 0.88rem;">{{ $countRuko }} Unit</strong>
                    </div>
                    <div style="font-size: 0.72rem; color: #64748B; text-align: right; margin-bottom: 4px;">{{ $pctRuko }}%</div>
                    <div style="height: 4px; background: #E2E8F0; border-radius: 2px; overflow: hidden;">
                        <div style="width: {{ $pctRuko }}%; height: 100%; background: #00838F;"></div>
                    </div>
                </div>

                <!-- Col 3 -->
                <div>
                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.78rem; margin-bottom: 4px;">
                        <span style="display: inline-flex; align-items: center; gap: 6px; color: #0F172A; font-weight: 600;">
                            <span style="width: 8px; height: 8px; background: #67E8F9; border-radius: 2px; display: inline-block;"></span>
                            Apartemen &amp; Penthouse
                        </span>
                        <strong style="color: #0E1E2E; font-size: 0.88rem;">{{ $countApt }} Unit</strong>
                    </div>
                    <div style="font-size: 0.72rem; color: #64748B; text-align: right; margin-bottom: 4px;">{{ $pctApt }}%</div>
                    <div style="height: 4px; background: #E2E8F0; border-radius: 2px; overflow: hidden;">
                        <div style="width: {{ $pctApt }}%; height: 100%; background: #67E8F9;"></div>
                    </div>
                </div>

                <!-- Col 4 -->
                <div>
                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.78rem; margin-bottom: 4px;">
                        <span style="display: inline-flex; align-items: center; gap: 6px; color: #0F172A; font-weight: 600;">
                            <span style="width: 8px; height: 8px; background: #A5F3FC; border-radius: 2px; display: inline-block;"></span>
                            Villa Tropis
                        </span>
                        <strong style="color: #0E1E2E; font-size: 0.88rem;">{{ $countVilla }} Unit</strong>
                    </div>
                    <div style="font-size: 0.72rem; color: #64748B; text-align: right; margin-bottom: 4px;">{{ $pctVilla }}%</div>
                    <div style="height: 4px; background: #E2E8F0; border-radius: 2px; overflow: hidden;">
                        <div style="width: {{ $pctVilla }}%; height: 100%; background: #A5F3FC;"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@section('styles')
<style>
/* Hero Banner Buttons */
.btn-banner-action {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.18);
    color: #FFFFFF;
    padding: 9px 16px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-banner-action:hover {
    background: rgba(255, 255, 255, 0.15);
}
.btn-banner-primary {
    background: #00838F;
    color: #FFFFFF;
    border: none;
    padding: 9px 16px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-banner-primary:hover {
    background: #0097A7;
    color: #FFFFFF;
}

/* KPI Cards */
.kpi-card {
    background: #FFFFFF;
    border: 1px solid var(--admin-border-subtle);
    border-radius: 12px;
    padding: 18px 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.02);
}
.kpi-label {
    font-size: 0.78rem;
    font-weight: 600;
    color: #64748B;
}
.kpi-icon-box {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.kpi-value-row {
    display: flex;
    align-items: baseline;
    gap: 6px;
    margin-top: 4px;
}
.kpi-number {
    font-size: 2.1rem;
    font-weight: 800;
    color: #0E1E2E;
    line-height: 1;
    letter-spacing: -0.02em;
}
.kpi-unit {
    font-size: 0.86rem;
    font-weight: 600;
    color: #64748B;
}

/* Priority Actions Card & Header */
.priority-actions-card {
    background: #FFFFFF;
    border: 1px solid var(--admin-border-subtle);
    border-radius: 14px;
    padding: 24px 28px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.02);
}
.priority-actions-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 20px;
}

/* Inbox Tabs */
.inbox-tabs-wrapper {
    display: flex;
    background: #F1F5F9;
    border-radius: 8px;
    padding: 3px;
    gap: 3px;
}
.inbox-tab-btn {
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 600;
    color: #64748B;
    background: transparent;
    border: none;
    cursor: pointer;
    transition: all 0.15s;
    white-space: nowrap;
}
.inbox-tab-btn:hover {
    color: #0E1E2E;
}
.inbox-tab-btn.active {
    background: #0E1E2E;
    color: #FFFFFF;
    font-weight: 700;
}

/* Action Items */
.action-items-container {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.action-item-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    transition: all 0.2s ease;
}
.action-item-card:hover {
    border-color: #CBD5E1;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
}
.action-item-main {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    flex: 1;
    min-width: 0;
}
.action-item-thumb {
    width: 64px;
    height: 64px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
    border: 1px solid #E2E8F0;
    align-self: flex-start;
}
.action-item-thumb-placeholder {
    width: 64px;
    height: 64px;
    border-radius: 8px;
    background: #F1F5F9;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 1px solid #E2E8F0;
    align-self: flex-start;
}
.action-item-info {
    flex: 1;
    min-width: 0;
}
.action-item-header-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 5px;
    flex-wrap: wrap;
}
.action-item-time {
    font-size: 0.72rem;
    color: #64748B;
    white-space: nowrap;
}
.action-item-title {
    font-size: 0.92rem;
    font-weight: 700;
    color: #0E1E2E;
    margin: 0 0 6px 0;
    line-height: 1.35;
    word-break: break-word;
}
.action-item-meta-row {
    display: flex;
    flex-direction: column;
    gap: 5px;
    font-size: 0.78rem;
}
.action-meta-user {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #64748B;
    font-size: 0.76rem;
    font-weight: 500;
    word-break: break-word;
}
.action-meta-tags-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
    font-size: 0.78rem;
}
.action-meta-price {
    color: #0E1E2E;
    font-size: 0.82rem;
    font-weight: 700;
    word-break: break-word;
}
.action-meta-bullet {
    color: #CBD5E1;
    font-size: 0.72rem;
}
.action-meta-loc {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: #00838F;
    font-weight: 600;
    font-size: 0.74rem;
    background: #E0F7FA;
    padding: 1px 7px;
    border-radius: 4px;
    border: 1px solid #B2EBF2;
    word-break: break-word;
}
.action-meta-budget {
    color: #00838F;
    font-size: 0.82rem;
    font-weight: 700;
    white-space: nowrap;
}
.action-meta-ready {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #059669;
    font-weight: 600;
    font-size: 0.76rem;
    white-space: nowrap;
}
.action-meta-notaris {
    color: #64748B;
    font-size: 0.76rem;
    white-space: nowrap;
}
.action-meta-warning {
    color: #DC2626;
    font-size: 0.76rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-weight: 500;
    line-height: 1.3;
}

/* Tag Pills */
.badge-tag-custom {
    font-size: 0.66rem;
    font-weight: 800;
    letter-spacing: 0.4px;
    padding: 3px 8px;
    border-radius: 4px;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    line-height: 1;
    white-space: nowrap;
}
.badge-cyan { background: #E0F7FA; color: #00838F; }
.badge-peach { background: #FEE2E2; color: #DC2626; }
.badge-teal { background: #E0F2FE; color: #0284C7; }
.badge-orange { background: #FFEDD5; color: #C2410C; }

/* Action Buttons */
.action-item-actions {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-shrink: 0;
}
.btn-action-outline {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    color: #0E1E2E;
    height: 36px;
    padding: 0 14px;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none;
    cursor: pointer;
    box-sizing: border-box;
    white-space: nowrap;
    transition: all 0.15s;
}
.btn-action-outline:hover {
    background: #F8FAFC;
    border-color: #94A3B8;
}
.btn-action-dark {
    background: #0E1E2E;
    color: #FFFFFF;
    border: 1px solid #0E1E2E;
    height: 36px;
    padding: 0 14px;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none;
    cursor: pointer;
    box-sizing: border-box;
    white-space: nowrap;
    transition: background 0.15s;
}
.btn-action-dark:hover {
    background: #192F47;
    border-color: #192F47;
    color: #FFFFFF;
}
.btn-action-teal {
    background: #00695C;
    color: #FFFFFF;
    border: 1px solid #00695C;
    height: 36px;
    padding: 0 14px;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none;
    cursor: pointer;
    box-sizing: border-box;
    white-space: nowrap;
    transition: background 0.15s;
}
.btn-action-teal:hover {
    background: #00796B;
    border-color: #00796B;
    color: #FFFFFF;
}

.btn-log-all {
    width: 100%;
    height: 40px;
    padding: 0 16px;
    background: #EFF6FF;
    border: 1px solid #DBEAFE;
    border-radius: 8px;
    color: #0E1E2E;
    font-size: 0.82rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
    box-sizing: border-box;
    transition: background 0.15s;
}
.btn-log-all:hover {
    background: #DBEAFE;
}

@media (max-width: 1200px) {
    #metrics-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
    #portfolio-cols-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}
@media (max-width: 860px) {
    .priority-actions-card {
        padding: 18px 16px !important;
        border-radius: 12px !important;
    }
    .priority-actions-header {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 12px !important;
    }
    .inbox-tabs-wrapper {
        width: 100% !important;
        overflow-x: auto !important;
        flex-wrap: nowrap !important;
        -webkit-overflow-scrolling: touch !important;
        scrollbar-width: none !important;
        padding: 4px !important;
    }
    .inbox-tabs-wrapper::-webkit-scrollbar {
        display: none !important;
    }
    .inbox-tab-btn {
        flex: 1 0 auto !important;
        text-align: center !important;
        padding: 7px 12px !important;
        font-size: 0.76rem !important;
        white-space: nowrap !important;
    }
    .action-item-card {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 12px !important;
        padding: 14px 14px !important;
    }
    .action-item-main {
        width: 100% !important;
        align-items: flex-start !important;
        gap: 12px !important;
    }
    .action-item-actions {
        width: 100% !important;
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 8px !important;
        padding-top: 10px !important;
        border-top: 1px solid #F1F5F9 !important;
    }
    .action-item-actions .btn-action-outline,
    .action-item-actions .btn-action-dark,
    .action-item-actions .btn-action-teal {
        width: 100% !important;
        height: 38px !important;
        min-height: 38px !important;
        padding: 0 8px !important;
        font-size: 0.78rem !important;
        font-weight: 700 !important;
        text-align: center !important;
        justify-content: center !important;
    }
}
@media (max-width: 640px) {
    #metrics-grid {
        grid-template-columns: 1fr !important;
    }
    #portfolio-cols-grid {
        grid-template-columns: 1fr !important;
        min-width: 0 !important;
    }
}
@media (max-width: 480px) {
    .priority-actions-card {
        padding: 14px 12px !important;
    }
    .action-item-thumb,
    .action-item-thumb-placeholder {
        width: 56px !important;
        height: 56px !important;
    }
    .action-item-title {
        font-size: 0.88rem !important;
    }
    .action-meta-price {
        font-size: 0.80rem !important;
    }
    .action-item-actions .btn-action-outline,
    .action-item-actions .btn-action-dark,
    .action-item-actions .btn-action-teal {
        font-size: 0.74rem !important;
        padding: 0 4px !important;
    }
}
</style>
@endsection

@section('scripts')
<script>
function filterActionTabs(cat, btn) {
    document.querySelectorAll('.inbox-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const items = document.querySelectorAll('.action-item-card');
    items.forEach(item => {
        if (cat === 'all') {
            item.style.display = 'flex';
        } else {
            const itemCat = item.getAttribute('data-category');
            item.style.display = (itemCat === cat) ? 'flex' : 'none';
        }
    });
}
</script>
@endsection
