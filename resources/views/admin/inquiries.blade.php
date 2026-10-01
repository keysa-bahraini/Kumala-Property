@extends('layouts.admin')

@section('title', 'Manajemen Data Inquiry (CRM) - Kumala Admin')

@section('content')
<div>

    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                <span class="badge-tag badge-tag-teal" style="font-size: 0.72rem;">LEAD PIPELINE & CRM</span>
                <span style="font-size: 0.78rem; color: var(--text-muted);">Manajemen Prospek Klien HNWIs & Alokasi Concierge</span>
            </div>
            <h1 style="font-size: 1.65rem; color: var(--primary-navy); margin-bottom: 4px; font-weight: 800;">
                Manajemen Data Inquiry
            </h1>
            <p style="font-size: 0.88rem; color: var(--text-muted);">
                Pusat alokasi prospek klien VIP, jadwal survei lokasi harian, dan pemantauan SLA respon tim concierge.
            </p>
        </div>

        <button type="button" class="btn-primary" style="padding: 10px 18px; font-size: 0.85rem; border-radius: 8px;" onclick="openManualLeadPrompt()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>+ Input Lead Manual</span>
        </button>
    </div>

    <!-- Pipeline Stage Tabs -->
    <div style="display: flex; gap: 10px; margin-bottom: 24px; flex-wrap: wrap;">
        <button type="button" class="queue-tab-btn" onclick="filterInquiryStage('all', this)">
            <span>Semua Inquiry</span>
            <span class="queue-count">{{ $stageCounts['all'] }}</span>
        </button>
        <button type="button" class="queue-tab-btn active" onclick="filterInquiryStage('new', this)">
            <span>Inquiry Baru</span>
            <span class="queue-count" style="background: var(--accent-teal); color: #FFFFFF;">{{ $stageCounts['new'] }}</span>
        </button>
        <button type="button" class="queue-tab-btn" onclick="filterInquiryStage('follow_up', this)">
            <span>Sedang Diproses</span>
            <span class="queue-count">{{ $stageCounts['follow_up'] }}</span>
        </button>
        <button type="button" class="queue-tab-btn" onclick="filterInquiryStage('survey', this)">
            <span>Jadwal Survei</span>
            <span class="queue-count" style="background: var(--primary-navy); color: #FFFFFF;">{{ $stageCounts['survey'] }}</span>
        </button>
        <button type="button" class="queue-tab-btn" onclick="filterInquiryStage('closing', this)">
            <span>Closing / Selesai</span>
            <span class="queue-count">{{ $stageCounts['closing'] }}</span>
        </button>
    </div>

    <!-- 2 Columns Layout: Pipeline Feed (Left) & Survey Schedule + Funnel (Right) -->
    <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px; align-items: flex-start;">
        
        <!-- Left: Inquiry Cards Feed -->
        <div style="display: flex; flex-direction: column; gap: 16px;">
            @foreach($inquiries as $inq)
            <div class="inquiry-card" data-stage="{{ $inq['stage'] }}" style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; padding: 22px; box-shadow: var(--shadow-sm);">
                
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <span class="badge-tag badge-tag-navy" style="font-size: 0.72rem; padding: 2px 6px;">{{ $inq['id'] }}</span>
                            <span class="badge-tag" style="background: #E2E8F0; color: #0E2238; font-size: 0.72rem;">{{ $inq['buyer_type'] }}</span>
                            <span class="badge-tag" style="background: #E6FBF5; color: #059669; font-size: 0.72rem;">{{ $inq['sla_badge'] }}</span>
                        </div>
                        <strong style="font-size: 1.15rem; color: var(--primary-navy); display: block;">{{ $inq['client_name'] }}</strong>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $inq['phone'] }} • {{ $inq['email'] }}</span>
                    </div>

                    <div style="text-align: right;">
                        <span style="display: block; font-size: 0.72rem; color: #64748B; text-transform: uppercase;">Ditangani Oleh</span>
                        <strong style="font-size: 0.85rem; color: var(--primary-navy);">{{ $inq['assigned_to'] }}</strong>
                    </div>
                </div>

                <!-- Unit of Interest Box -->
                <div style="background: #F8FAFC; border: 1px solid var(--border-light); border-radius: 8px; padding: 12px 16px; margin-bottom: 14px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Unit Properti yang Diminati</span>
                        <strong style="display: block; font-size: 0.92rem; color: var(--primary-navy);">{{ $inq['property_title'] }}</strong>
                    </div>
                    <span style="font-family: var(--font-heading); font-size: 1.05rem; font-weight: 800; color: var(--primary-navy);">{{ $inq['property_price'] }}</span>
                </div>

                <!-- Client Message Quote -->
                <div style="background: #FAF8F5; border-left: 3px solid #D97706; padding: 10px 14px; border-radius: 4px; font-size: 0.85rem; color: #475569; line-height: 1.5; margin-bottom: 18px; font-style: italic;">
                    "{{ $inq['quote'] }}"
                </div>

                <!-- Action Controls -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 14px; border-top: 1px solid var(--border-light); flex-wrap: wrap; gap: 10px;">
                    <span style="font-size: 0.75rem; color: #94A3B8;">Diterima: {{ $inq['time_ago'] }}</span>

                    <div style="display: flex; gap: 8px;">
                        <a href="https://wa.me/6281288992345" target="_blank" class="btn-teal" style="padding: 7px 14px; font-size: 0.8rem; border-radius: 6px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            <span>Hubungi Klien via WA</span>
                        </a>
                        <button type="button" class="btn-outline-navy" style="padding: 7px 14px; font-size: 0.8rem; border-radius: 6px;" onclick="scheduleSurveyPrompt('{{ $inq['id'] }}', '{{ $inq['client_name'] }}', '{{ $inq['property_id'] }}', '{{ $inq['property_title'] }}')">
                            Jadwalkan Survei
                        </button>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

        <!-- Right: Schedule & Funnel Widgets -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            <!-- Widget 1: Jadwal Survei Hari Ini -->
            <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 14px; padding: 22px; box-shadow: var(--shadow-sm);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div>
                        <h3 style="font-size: 1.1rem; color: var(--primary-navy); font-weight: 700;">Jadwal Survei Hari Ini</h3>
                        <span style="font-size: 0.75rem; color: var(--text-muted);">{{ date('l, d F Y') }}</span>
                    </div>
                    <span style="font-size: 0.72rem; color: var(--accent-teal); font-weight: 700; background: #E6FBF5; padding: 4px 8px; border-radius: 4px;">3 Kunjungan</span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach($surveysToday as $s)
                    <div style="padding: 12px; background: #F8FAFC; border-radius: 8px; border: 1px solid var(--border-light);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                            <strong style="color: var(--primary-navy); font-size: 0.85rem;">{{ $s['time'] }}</strong>
                            <span style="font-size: 0.7rem; font-weight: 700; color: #059669; background: #E6FBF5; padding: 2px 6px; border-radius: 4px;">{{ $s['status'] }}</span>
                        </div>
                        <div style="font-size: 0.82rem; color: var(--primary-navy); font-weight: 600; margin-bottom: 2px;">{{ $s['property'] }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Klien: {{ $s['client'] }} • Pendamping: {{ $s['agent'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Widget 2: Tingkat Konversi Lead Analytics Funnel -->
            <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 14px; padding: 22px; box-shadow: var(--shadow-sm);">
                <div style="margin-bottom: 16px;">
                    <h3 style="font-size: 1.1rem; color: var(--primary-navy); font-weight: 700;">Tingkat Konversi Lead Funnel</h3>
                    <p style="font-size: 0.75rem; color: var(--text-muted);">Performa alur konversi inquiry bulan berjalan.</p>
                </div>

                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 4px;">
                            <span style="color: var(--text-muted);">Inquiry Masuk ke Respon (&lt; 15 mnt)</span>
                            <strong style="color: var(--primary-navy);">{{ $funnelMetrics['resp_pct'] ?? 96 }}% ({{ $funnelMetrics['resp_ratio'] ?? '38/40' }})</strong>
                        </div>
                        <div style="height: 8px; background: #E2E8F0; border-radius: 4px; overflow: hidden;">
                            <div style="width: {{ $funnelMetrics['resp_pct'] ?? 96 }}%; height: 100%; background: #059669; border-radius: 4px;"></div>
                        </div>
                    </div>

                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 4px;">
                            <span style="color: var(--text-muted);">Respon ke Jadwal Survei Fisik</span>
                            <strong style="color: var(--primary-navy);">{{ $funnelMetrics['survey_pct'] ?? 48 }}% ({{ $funnelMetrics['survey_ratio'] ?? '18/38' }})</strong>
                        </div>
                        <div style="height: 8px; background: #E2E8F0; border-radius: 4px; overflow: hidden;">
                            <div style="width: {{ $funnelMetrics['survey_pct'] ?? 48 }}%; height: 100%; background: var(--accent-teal); border-radius: 4px;"></div>
                        </div>
                    </div>

                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 4px;">
                            <span style="color: var(--text-muted);">Survei Fisik ke Closing Transaksi</span>
                            <strong style="color: var(--primary-navy);">{{ $funnelMetrics['closing_pct'] ?? 28 }}% ({{ $funnelMetrics['closing_ratio'] ?? '5/18' }})</strong>
                        </div>
                        <div style="height: 8px; background: #E2E8F0; border-radius: 4px; overflow: hidden;">
                            <div style="width: {{ $funnelMetrics['closing_pct'] ?? 28 }}%; height: 100%; background: var(--primary-navy); border-radius: 4px;"></div>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 18px; padding-top: 12px; border-top: 1px solid var(--border-light); font-size: 0.75rem; color: #64748B; text-align: center;">
                    Standar industri broker mewah: rata-rata 18–22% konversi akhir.
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
const csrfToken = '{{ csrf_token() }}';

function filterInquiryStage(stage, btn) {
    document.querySelectorAll('.queue-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const cards = document.querySelectorAll('.inquiry-card');
    cards.forEach(card => {
        const cardStage = card.getAttribute('data-stage').toLowerCase();
        if (stage === 'all') {
            card.style.display = 'block';
        } else if (stage === 'new' && (cardStage.includes('baru') || cardStage === 'new')) {
            card.style.display = 'block';
        } else if (stage === 'follow_up' && (cardStage.includes('follow') || cardStage.includes('proses'))) {
            card.style.display = 'block';
        } else if (stage === 'survey' && cardStage.includes('survei')) {
            card.style.display = 'block';
        } else if (stage === 'closing' && cardStage.includes('closing')) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

async function scheduleSurveyPrompt(inqId, clientName, propId, propTitle) {
    const sDate = prompt(`Tanggal Survei untuk ${clientName} (YYYY-MM-DD):`, '{{ date('Y-m-d', strtotime('+1 day')) }}');
    if (!sDate) return;
    const sTime = prompt('Waktu Survei (contoh: 10:30 WIB):', '10:30 WIB');
    if (!sTime) return;
    try {
        const res = await fetch(`/admin/inquiry/${encodeURIComponent(inqId)}/survey`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ _token: csrfToken, survey_date: sDate, survey_time: sTime, property_id: propId, property_title: propTitle, client_name: clientName })
        });
        const data = await res.json();
        if (data.success) {
            showToast(data.message);
            setTimeout(() => window.location.reload(), 1200);
        }
    } catch (e) {
        showToast('Gagal menjadwalkan survei');
    }
}

async function openManualLeadPrompt() {
    const name = prompt('Nama Klien HNWIs:');
    if (!name) return;
    const phone = prompt('Nomor WhatsApp Klien:');
    if (!phone) return;
    const prop = prompt('Unit Properti yang Diminati:', 'Villa Cliffside Horizon Uluwatu');
    if (!prop) return;
    try {
        const res = await fetch('/admin/inquiry/manual', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ _token: csrfToken, client_name: name, client_phone: phone, property_title: prop })
        });
        const data = await res.json();
        if (data.success) {
            showToast(data.message);
            setTimeout(() => window.location.reload(), 1200);
        }
    } catch (e) {
        showToast('Gagal menambahkan lead baru');
    }
}
</script>
@endsection
