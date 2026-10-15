@extends('layouts.admin')

@section('title', 'Manajemen Data Inquiry - Kumala Admin')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px; min-width: 0; width: 100%; max-width: 100%;">

    <!-- Header Section -->
    <div>
        <div style="display: inline-flex; align-items: center; gap: 6px; color: #00838F; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.6px; text-transform: uppercase; margin-bottom: 6px;">
            <span>LEAD PIPELINE &bull; PROSPEK PROPERTI</span> <span style="color: #CBD5E1;">&bull;</span> <span>Real-Time Ingestion</span>
        </div>
        <h1 style="font-size: 1.75rem; color: #0E1E2E; margin: 0 0 4px 0; font-weight: 800; letter-spacing: -0.01em;">
            Manajemen Data Inquiry (Leads Properti)
        </h1>
        <p style="font-size: 0.85rem; color: #64748B; margin: 0; max-width: 760px; line-height: 1.45;">
            Kelola pertanyaan, permintaan konsultasi, dan janji temu survei calon pembeli/penyewa secara terpusat langsung dari formulir web publik.
        </p>
    </div>

    <!-- Filter Tabs -->
    <div class="inquiry-tabs-wrapper">
        <button type="button" class="inquiry-tab-btn active" onclick="filterInquiries('all', this)">
            Semua Inquiry <span class="tab-badge">{{ $stageCounts['all'] ?? count($inquiries) }}</span>
        </button>
        <button type="button" class="inquiry-tab-btn" onclick="filterInquiries('baru', this)">
            <span style="width: 6px; height: 6px; border-radius: 50%; background: #059669; display: inline-block; margin-right: 6px;"></span>
            Inquiry Baru <span class="tab-badge" style="background: #E6FBF5; color: #059669;">{{ $stageCounts['new'] ?? 0 }}</span>
        </button>
        <button type="button" class="inquiry-tab-btn" onclick="filterInquiries('follow-up', this)">
            Sedang Diproses <span class="tab-badge">{{ $stageCounts['follow_up'] ?? 0 }}</span>
        </button>
        <button type="button" class="inquiry-tab-btn" onclick="filterInquiries('survei', this)">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 5px;"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
            Jadwal Survei <span class="tab-badge" style="background: #E0F2FE; color: #0284C7;">{{ $stageCounts['survey'] ?? 0 }}</span>
        </button>
        <button type="button" class="inquiry-tab-btn" onclick="filterInquiries('closing', this)">
            Closing / Selesai <span class="tab-badge">{{ $stageCounts['closing'] ?? 0 }}</span>
        </button>
    </div>

    <!-- Subheader: Daftar Prospek Masuk & Sortir -->
    <div class="inquiry-subheader">
        <div>
            <strong style="color: #0E1E2E; font-size: 0.88rem;">Daftar Prospek Masuk</strong>
            <span style="margin-left: 8px; color: #64748B;">Total {{ count($inquiries) }} data prospek aktif</span>
        </div>
        <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
            <span style="color: #64748B;">Prioritas:</span>
            <span class="inquiry-sla-pill">
                SLA Responsif &lt; 15 Menit
            </span>
        </div>
    </div>

    <!-- Lead Cards Container -->
    <div style="display: flex; flex-direction: column; gap: 16px;" id="leads-container">
        @forelse($inquiries as $inq)
        @php
            $initials = '';
            $parts = explode(' ', trim($inq['client_name'] ?? 'Klien'));
            if (count($parts) >= 2) {
                $initials = strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
            } else {
                $initials = strtoupper(substr($parts[0], 0, 2));
            }
            $cleanPhone = preg_replace('/[^0-9]/', '', $inq['phone'] ?? '');
            if (substr($cleanPhone, 0, 1) === '0') {
                $cleanPhone = '62' . substr($cleanPhone, 1);
            }
            $currentStage = strtolower($inq['stage'] ?? 'baru');
        @endphp
        <div class="lead-card" data-stage="{{ $currentStage }}" data-inq-id="{{ $inq['id'] }}">
            <!-- Header Row -->
            <div class="lead-header-row">
                <div class="lead-client-info">
                    <div class="lead-avatar" style="background: #E0F7FA; color: #00838F;">{{ $initials }}</div>
                    <div class="lead-client-meta">
                        <div class="lead-name-row">
                            <strong class="lead-client-name">{{ $inq['client_name'] }}</strong>
                            <span class="lead-tag-pill">{{ $inq['buyer_type'] ?? 'Calon Pembeli' }}</span>
                            <span class="badge-wa-ready">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                WA Ready
                            </span>
                        </div>
                        <div class="lead-contact-row">
                            <span class="lead-contact-phone">{{ $inq['phone'] }}</span>
                            <span class="lead-dot">&bull;</span>
                            <span class="lead-contact-email">{{ $inq['email'] ?? 'klien@kumalaproperty.id' }}</span>
                            <span class="lead-dot">&bull;</span>
                            <span class="lead-contact-source">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" x2="22" y1="12" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1 4-10z"/></svg>
                                Web Detail
                            </span>
                        </div>
                    </div>
                </div>

                <div class="lead-status-group">
                    <div class="lead-time-wrap">
                        <span class="lead-time-ago">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #059669; display: inline-block;"></span>
                            {{ $inq['time_ago'] ?? 'Baru saja' }}
                        </span>
                        <span class="lead-sla-badge">{{ $inq['sla_badge'] ?? 'SLA Hijau (< 15 mnt)' }}</span>
                    </div>
                    <select class="lead-status-select" 
                            style="background: {{ $currentStage === 'baru' ? '#00838F' : ($currentStage === 'closing' ? '#059669' : '#0E1E2E') }}; color: #FFFFFF;"
                            onchange="updateLeadStage('{{ $inq['id'] }}', this.value, this)">
                        <option value="Baru" {{ $currentStage === 'baru' ? 'selected' : '' }}>Inquiry Baru</option>
                        <option value="Follow-Up" {{ $currentStage === 'follow-up' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="Survei" {{ $currentStage === 'survei' ? 'selected' : '' }}>Jadwal Survei</option>
                        <option value="Closing" {{ $currentStage === 'closing' ? 'selected' : '' }}>Closing</option>
                    </select>
                </div>
            </div>

            <!-- Middle Box: Properti Diminati & Pesan Klien -->
            <div class="lead-inner-box">
                <!-- Properti Diminati -->
                <div class="lead-inner-col prop-col">
                    <span style="font-size: 0.68rem; font-weight: 800; color: #64748B; letter-spacing: 0.6px; text-transform: uppercase; display: block; margin-bottom: 6px;">PROPERTI DIMINATI</span>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <div style="width: 48px; height: 38px; border-radius: 6px; background: #0E1E2E; color: #38BDF8; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 800; flex-shrink: 0;">
                            UNIT
                        </div>
                        <div style="min-width: 0;">
                            <strong style="font-size: 0.85rem; color: #0E1E2E; display: block; line-height: 1.25; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $inq['property_title'] ?? 'Listing Terpilih' }}</strong>
                            <div style="font-size: 0.72rem; color: #64748B; margin-top: 3px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                <span>{{ $inq['property_id'] ?? '#KP-8821' }}</span>
                                <strong style="color: #00838F;">{{ $inq['property_price'] ?? 'Rp 15 Miliar' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pesan Klien -->
                <div class="lead-inner-col quote-col">
                    <span style="font-size: 0.68rem; font-weight: 800; color: #64748B; letter-spacing: 0.6px; text-transform: uppercase; display: flex; align-items: center; gap: 5px; margin-bottom: 6px;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span>CATATAN / PERMINTAAN KLIEN</span>
                    </span>
                    <p style="font-size: 0.78rem; color: #334155; font-style: italic; margin: 0; line-height: 1.45;">
                        "{{ $inq['quote'] ?? 'Menanyakan ketersediaan jadwal survei lokasi dan legalitas unit.' }}"
                    </p>
                </div>
            </div>

            <!-- Footer Action Row -->
            <div class="lead-card-footer">
                <div style="display: flex; align-items: center; gap: 6px; color: #64748B; font-size: 0.74rem;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#00838F" stroke-width="2"><polyline points="9 10 4 15 9 20"/><path d="M20 4v7a4 4 0 0 1-4 4H4"/></svg>
                    <span><strong>Penanganan:</strong> {{ $inq['assigned_to'] ?? 'Private Client Concierge' }} <span style="color: #94A3B8;">({{ $inq['id'] }})</span></span>
                </div>

                <div class="lead-footer-buttons">
                    <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($inq['client_name']) }},%20kami%20dari%20Private%20Client%20Concierge%20Kumala%20Property%20ingin%20menindaklanjuti%20permohonan%20Anda%20untuk%20unit%20{{ urlencode($inq['property_title'] ?? 'properti') }}." target="_blank" class="btn-lead-action btn-wa">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span>WhatsApp Klien</span>
                    </a>
                    <button type="button" class="btn-lead-action btn-schedule" onclick="openScheduleModal('{{ $inq['client_name'] }}', '{{ $inq['property_title'] ?? 'Unit Properti' }}')">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                        <span>Atur Jadwal Survei</span>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div style="padding: 30px; text-align: center; background: #fff; border-radius: 12px; border: 1px dashed #CBD5E1; color: #64748B;">
            Belum ada leads inquiry masuk. Anda dapat mengirim minat properti dari halaman detail properti untuk menguji alur secara instan.
        </div>
        @endforelse
    </div>

    <!-- 2 Bottom Widgets: Jadwal Survei & Tingkat Konversi Lead -->
    <div style="display: grid; grid-template-columns: 1.1fr 1fr; gap: 18px;" id="inquiry-bottom-grid">
        
        <!-- Widget 1: Jadwal Survei Hari Ini -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px 22px; box-shadow: 0 1px 4px rgba(0,0,0,0.02); display: flex; flex-direction: column;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 28px; height: 28px; border-radius: 6px; background: #E0F7FA; color: #00838F; display: flex; align-items: center; justify-content: center;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    </div>
                    <div>
                        <strong style="color: #0E1E2E; font-size: 0.95rem; display: block;">Jadwal Survei Lapangan</strong>
                        <span style="font-size: 0.72rem; color: #64748B;">{{ count($surveysToday) }} Kunjungan Terjadwal</span>
                    </div>
                </div>
                <button type="button" class="btn-lead-action" onclick="openScheduleModal('', '')" style="font-size: 0.72rem; padding: 3px 8px;">
                    + Tambah Jadwal
                </button>
            </div>

            <!-- Schedule Items -->
            <div style="display: flex; flex-direction: column; gap: 12px; flex: 1;">
                @forelse($surveysToday as $srv)
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 12px 14px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <span style="font-size: 0.78rem; font-weight: 800; color: #00838F;">{{ $srv['time'] ?? '14:00 WIB' }}</span>
                        <span style="background: #E6FBF5; color: #059669; font-size: 0.68rem; font-weight: 700; padding: 2px 6px; border-radius: 4px;">{{ $srv['status'] ?? 'Terkonfirmasi' }}</span>
                    </div>
                    <strong style="color: #0E1E2E; font-size: 0.86rem; display: block;">{{ $srv['property'] ?? 'Unit Properti' }}</strong>
                    <div style="font-size: 0.74rem; color: #64748B; margin-top: 3px;">
                        Klien: {{ $srv['client'] ?? 'Klien VIP' }} <br>
                        Pendamping: {{ $srv['agent'] ?? 'Tim Concierge Kumala' }}
                    </div>
                </div>
                @empty
                <div style="padding: 16px; text-align: center; color: #94A3B8; font-size: 0.78rem;">
                    Belum ada jadwal survei hari ini.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Widget 2: Tingkat Konversi Lead -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px 22px; box-shadow: 0 1px 4px rgba(0,0,0,0.02); display: flex; flex-direction: column;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <strong style="color: #0E1E2E; font-size: 0.95rem;">Tingkat Konversi Lead Pipeline</strong>
                <span style="font-size: 0.72rem; color: #64748B;">Bulan Berjalan</span>
            </div>

            <!-- Conversion Progress Bars -->
            <div style="display: flex; flex-direction: column; gap: 14px; flex: 1;">
                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.78rem; margin-bottom: 4px;">
                        <span style="color: #0E1E2E; font-weight: 600;">Inquiry Masuk ke Respon Pertama</span>
                        <strong style="color: #0E1E2E;">98% (SLA &lt; 15m)</strong>
                    </div>
                    <div style="height: 6px; background: #E2E8F0; border-radius: 3px; overflow: hidden;">
                        <div style="width: 98%; height: 100%; background: #00838F; border-radius: 3px;"></div>
                    </div>
                </div>

                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.78rem; margin-bottom: 4px;">
                        <span style="color: #0E1E2E; font-weight: 600;">Respon ke Survei Lokasi Fisik</span>
                        <strong style="color: #0E1E2E;">64% Terjadwal</strong>
                    </div>
                    <div style="height: 6px; background: #E2E8F0; border-radius: 3px; overflow: hidden;">
                        <div style="width: 64%; height: 100%; background: #0E1E2E; border-radius: 3px;"></div>
                    </div>
                </div>

                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.78rem; margin-bottom: 4px;">
                        <span style="color: #0E1E2E; font-weight: 600;">Survei ke Notaris &amp; Closing</span>
                        <strong style="color: #0E1E2E;">32% Deal Selesai</strong>
                    </div>
                    <div style="height: 6px; background: #E2E8F0; border-radius: 3px; overflow: hidden;">
                        <div style="width: 32%; height: 100%; background: #059669; border-radius: 3px;"></div>
                    </div>
                </div>

                <!-- Tip Box -->
                <div style="background: #EAF4F9; border: 1px solid #D6E8F2; border-radius: 8px; padding: 12px 14px; margin-top: 6px;">
                    <div style="font-size: 0.74rem; font-weight: 800; color: #00838F; display: flex; align-items: center; gap: 5px; margin-bottom: 3px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                        <span>Pedoman Penanganan Prospek:</span>
                    </div>
                    <p style="font-size: 0.74rem; color: #475569; margin: 0; line-height: 1.45;">
                        Setiap inquiry yang masuk langsung tercatat di dasbor. Segera hubungi calon pembeli melalui tautan WhatsApp resmi agar peluang deal maksimal.
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Modal Jadwal Survei -->
<div id="surveyModal" style="display: none; position: fixed; inset: 0; background: rgba(14, 30, 46, 0.6); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #FFFFFF; border-radius: 12px; width: 100%; max-width: 460px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <strong style="font-size: 1.05rem; color: #0E1E2E;">Jadwalkan Survei Lokasi Fisik</strong>
            <button type="button" onclick="closeScheduleModal()" style="background: none; border: none; cursor: pointer; color: #64748B; display: flex; align-items: center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('admin.inquiries.survey') }}" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Nama Klien</label>
                <input type="text" name="client" id="modal-client-name" class="form-input-field" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Properti Tujuan</label>
                <input type="text" name="property" id="modal-property-title" class="form-input-field" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Waktu Kunjungan</label>
                    <input type="text" name="time" placeholder="Contoh: 14:00 WIB" class="form-input-field" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Pendamping (Agent)</label>
                    <input type="text" name="agent" value="Tim Concierge Kumala" class="form-input-field" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 18px;">
                <button type="button" onclick="closeScheduleModal()" class="btn-deck-outline" style="height: 36px; padding: 0 14px; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.8rem; cursor: pointer; background: #fff;">Batal</button>
                <button type="submit" class="btn-deck-approve" style="height: 36px; padding: 0 16px; background: #00838F; color: #fff; border: none; border-radius: 6px; font-size: 0.8rem; font-weight: 700; cursor: pointer;">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
.inquiry-tabs-wrapper {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    padding-bottom: 4px;
    scrollbar-width: none;
    width: 100%;
}
.inquiry-tabs-wrapper::-webkit-scrollbar {
    display: none;
}

.inquiry-tab-btn {
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
.inquiry-tab-btn:hover {
    border-color: #CBD5E1;
    color: #0E1E2E;
}
.inquiry-tab-btn.active {
    background: #0E1E2E;
    color: #FFFFFF;
    border-color: #0E1E2E;
    font-weight: 700;
}
.tab-badge {
    background: rgba(0,0,0,0.06);
    padding: 1px 6px;
    border-radius: 10px;
    margin-left: 6px;
    font-size: 0.72rem;
}
.inquiry-tab-btn.active .tab-badge {
    background: rgba(255,255,255,0.2);
    color: #FFFFFF;
}

.inquiry-subheader {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.78rem;
    color: #64748B;
    margin-top: 4px;
    flex-wrap: wrap;
    gap: 8px;
}
.inquiry-sla-pill {
    color: #00838F;
    background: #E0F7FA;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.74rem;
    font-weight: 700;
    display: inline-block;
    white-space: nowrap;
}

/* Lead Card */
.lead-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 18px 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.02);
    box-sizing: border-box;
    width: 100%;
}

.lead-header-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 14px;
    margin-bottom: 14px;
    width: 100%;
}
.lead-client-info {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    flex: 1;
    min-width: 0;
}
.lead-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    font-size: 0.84rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 2px;
}
.lead-client-meta {
    flex: 1;
    min-width: 0;
}
.lead-name-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 4px;
}
.lead-client-name {
    font-size: 1rem;
    font-weight: 800;
    color: #0E1E2E;
    white-space: nowrap;
}
.lead-tag-pill {
    font-size: 0.68rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 4px;
    background: #E0F7FA;
    color: #00838F;
    white-space: nowrap;
}
.badge-wa-ready {
    font-size: 0.72rem;
    color: #059669;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}
.lead-contact-row {
    font-size: 0.76rem;
    color: #64748B;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 4px 8px;
    line-height: 1.4;
}
.lead-contact-phone {
    color: #0E1E2E;
    font-weight: 600;
    white-space: nowrap;
}
.lead-contact-email {
    white-space: nowrap;
}
.lead-contact-source {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}
.lead-dot {
    color: #CBD5E1;
}

.lead-status-group {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}
.lead-time-wrap {
    text-align: right;
}
.lead-time-ago {
    font-size: 0.74rem;
    color: #059669;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
}
.lead-sla-badge {
    font-size: 0.7rem;
    color: #64748B;
    display: block;
    white-space: nowrap;
}
.lead-status-select {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 700;
    border: none;
    outline: none;
    cursor: pointer;
    box-sizing: border-box;
    white-space: nowrap;
}

/* Inner Box */
.lead-inner-box {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 12px 16px;
    display: flex;
    align-items: stretch;
    gap: 16px;
}
.lead-inner-col.prop-col {
    flex: 1;
    min-width: 220px;
    padding-right: 16px;
    border-right: 1px solid #E2E8F0;
}
.lead-inner-col.quote-col {
    flex: 1.5;
    min-width: 240px;
}

/* Footer Action */
.lead-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 14px;
    font-size: 0.76rem;
    flex-wrap: wrap;
    gap: 10px;
}
.lead-footer-buttons {
    display: flex;
    gap: 8px;
    align-items: center;
}
.btn-lead-action {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    color: #0E1E2E;
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.15s;
    box-sizing: border-box;
    white-space: nowrap;
}
.btn-lead-action:hover {
    background: #F8FAFC;
    border-color: #94A3B8;
}
.btn-lead-action.btn-wa {
    border-color: #00838F;
    color: #00838F;
    background: #F0FDFA;
}
.btn-lead-action.btn-wa:hover {
    background: #CCFBF1;
}

/* Responsive Media Queries */
@media (max-width: 900px) {
    #inquiry-bottom-grid {
        grid-template-columns: 1fr !important;
    }
}

@media (max-width: 768px) {
    .lead-inner-box {
        flex-direction: column !important;
        gap: 12px !important;
        padding: 12px 14px !important;
    }
    .lead-inner-col.prop-col {
        border-right: none !important;
        border-bottom: 1px solid #E2E8F0 !important;
        padding-right: 0 !important;
        padding-bottom: 12px !important;
        min-width: 0 !important;
        width: 100% !important;
    }
    .lead-inner-col.quote-col {
        min-width: 0 !important;
        width: 100% !important;
    }
}

@media (max-width: 640px) {
    .lead-card {
        padding: 14px 14px !important;
    }
    .lead-header-row {
        flex-direction: column !important;
        gap: 10px !important;
    }
    .lead-status-group {
        width: 100% !important;
        justify-content: space-between !important;
        padding-top: 10px !important;
        border-top: 1px dashed #E2E8F0 !important;
    }
    .lead-time-wrap {
        text-align: left !important;
    }
    .lead-card-footer {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 10px !important;
    }
    .lead-footer-buttons {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 8px !important;
        width: 100% !important;
    }
    .btn-lead-action {
        width: 100% !important;
        height: 40px !important;
    }
}

@media (max-width: 420px) {
    .lead-footer-buttons {
        grid-template-columns: 1fr !important;
    }
    .lead-contact-row .lead-dot {
        display: none !important;
    }
}
</style>
@endsection

@section('scripts')
<script>
function filterInquiries(stage, btn) {
    document.querySelectorAll('.inquiry-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const cards = document.querySelectorAll('.lead-card');
    cards.forEach(card => {
        const cardStage = card.getAttribute('data-stage');
        if (stage === 'all' || cardStage === stage) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function updateLeadStage(id, stage, selectEl) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}';

    fetch('/admin/inquiry/' + encodeURIComponent(id) + '/stage', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ stage: stage })
    })
    .then(res => res.json())
    .then(data => {
        showToast(`Tahapan lead ${id} berhasil diperbarui menjadi ${stage}.`);
        if (selectEl) {
            if (stage === 'Baru') selectEl.style.background = '#00838F';
            else if (stage === 'Closing') selectEl.style.background = '#059669';
            else selectEl.style.background = '#0E1E2E';
            
            const card = selectEl.closest('.lead-card');
            if (card) card.setAttribute('data-stage', stage.toLowerCase());
        }
    })
    .catch(err => {
        console.error(err);
        showToast('Gagal memperbarui status lead.');
    });
}

function openScheduleModal(clientName, propertyTitle) {
    const modal = document.getElementById('surveyModal');
    if (modal) {
        modal.style.display = 'flex';
        document.getElementById('modal-client-name').value = clientName || '';
        document.getElementById('modal-property-title').value = propertyTitle || '';
    }
}

function closeScheduleModal() {
    const modal = document.getElementById('surveyModal');
    if (modal) modal.style.display = 'none';
}
</script>
@endsection
