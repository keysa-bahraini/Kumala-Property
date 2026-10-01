@extends('layouts.admin')

@section('title', 'Admin Dashboard - Kumala Property')

@section('content')
<div>

    <!-- Operational Summary Banner -->
    <div style="background: linear-gradient(135deg, #0E2238 0%, #162E4A 100%); border-radius: 14px; padding: 28px 32px; color: #FFFFFF; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; margin-bottom: 28px; box-shadow: var(--shadow-sm); border: 1px solid rgba(255, 255, 255, 0.08);">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <span class="badge-tag badge-tag-teal" style="font-size: 0.72rem;">SISTEM OPERASIONAL AKTIF</span>
                <span style="font-size: 0.78rem; color: #94A3B8;">Pembaruan Terakhir: {{ date('d M Y, H:i') }} WIB</span>
            </div>
            <h1 style="font-size: 1.75rem; color: #FFFFFF; margin-bottom: 6px; font-weight: 800;">
                Selamat Datang Kembali, {{ session('admin_user.name', 'Aditya Pratama') }}
            </h1>
            <p style="font-size: 0.9rem; color: #CBD5E1; max-width: 680px; line-height: 1.5;">
                Pantau antrean kurasi listing masuk, SLA respon prospek investor VIP, serta progres audit transaksi closing notarial hari ini.
            </p>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('admin.export.daily') }}" class="btn-outline-white" style="padding: 10px 18px; font-size: 0.85rem; border-radius: 8px; background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.2); text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                <span>Ekspor Laporan Harian</span>
            </a>
            <a href="{{ route('titip-properti') }}" target="_blank" class="btn-teal" style="padding: 10px 18px; font-size: 0.85rem; border-radius: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>+ Input Properti Baru</span>
            </a>
        </div>
    </div>

    <!-- KPI Metrics Grid (4 Cards) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 32px;">
        
        <!-- Metric 1: Properti Aktif Tayang -->
        <div class="kpi-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                <span class="kpi-label">Properti Aktif Tayang</span>
                <div class="kpi-icon-box" style="background: #EBF1F5; color: var(--primary-navy);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
            </div>
            <div class="kpi-value">{{ $metrics['active_units'] }} <span style="font-size: 1rem; font-weight: 500; color: #64748B;">Unit</span></div>
            <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-muted); margin-top: 8px;">
                <span>{{ $metrics['active_jual'] }} Jual • {{ $metrics['active_sewa'] }} Sewa</span>
                <strong style="color: #059669;">{{ $metrics['active_growth'] }}</strong>
            </div>
        </div>

        <!-- Metric 2: Menunggu Review Kurasi -->
        <div class="kpi-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                <span class="kpi-label">Menunggu Review Kurasi</span>
                <div class="kpi-icon-box" style="background: #FEF3C7; color: #D97706;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
            </div>
            <div class="kpi-value" style="color: #D97706;">{{ $metrics['pending_review'] }} <span style="font-size: 1rem; font-weight: 500; color: #64748B;">Berkas</span></div>
            <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-muted); margin-top: 8px;">
                <span>Target SLA &lt; 4 Jam</span>
                <span style="color: #D97706; font-weight: 600;">Perlu Tindakan Segera</span>
            </div>
        </div>

        <!-- Metric 3: Inquiry Baru Masuk -->
        <div class="kpi-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                <span class="kpi-label">Inquiry Baru Masuk</span>
                <div class="kpi-icon-box" style="background: #E6FBF5; color: var(--accent-teal);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
            </div>
            <div class="kpi-value" style="color: var(--accent-teal-dark);">{{ $metrics['new_inquiries'] }} <span style="font-size: 1rem; font-weight: 500; color: #64748B;">Prospek</span></div>
            <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-muted); margin-top: 8px;">
                <span>Avg. SLA Respons Tim</span>
                <strong style="color: #059669;">{{ $metrics['avg_sla_minutes'] }} Menit (Cepat)</strong>
            </div>
        </div>

        <!-- Metric 4: Closing Bulan Ini -->
        <div class="kpi-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                <span class="kpi-label">Closing Bulan Ini</span>
                <div class="kpi-icon-box" style="background: #F1F5F9; color: var(--primary-navy);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                </div>
            </div>
            <div class="kpi-value">{{ $metrics['closing_month_units'] }} <span style="font-size: 1rem; font-weight: 500; color: #64748B;">Deal</span></div>
            <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-muted); margin-top: 8px;">
                <span>{{ $metrics['closing_month_val'] }}</span>
                <strong style="color: var(--primary-navy);">{{ $metrics['closing_target_pct'] }}% Target</strong>
            </div>
        </div>

    </div>

    <!-- Priority Action Inbox ("Perlu Ditindaklanjuti Segera") -->
    <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 14px; padding: 26px 30px; margin-bottom: 32px; box-shadow: var(--shadow-sm);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 20px;">
            <div>
                <span class="badge-tag badge-tag-teal" style="font-size: 0.72rem; margin-bottom: 4px;">ANTREAN KERJA PRIORITAS</span>
                <h2 style="font-size: 1.35rem; color: var(--primary-navy); font-weight: 700;">Perlu Ditindaklanjuti Segera</h2>
            </div>

            <!-- Inbox Tabs -->
            <div style="display: flex; background: #F1F5F9; border-radius: 8px; padding: 4px; gap: 4px;">
                <button type="button" class="inbox-filter-tab active" data-type="all" onclick="filterInbox('all', this)">Semua ({{ count($priorityActions) }})</button>
                <button type="button" class="inbox-filter-tab" data-type="listing" onclick="filterInbox('listing', this)">Listing ({{ collect($priorityActions)->where('type', 'listing')->count() }})</button>
                <button type="button" class="inbox-filter-tab" data-type="inquiry" onclick="filterInbox('inquiry', this)">Inquiry ({{ collect($priorityActions)->where('type', 'inquiry')->count() }})</button>
                <button type="button" class="inbox-filter-tab" data-type="closing" onclick="filterInbox('closing', this)">Closing ({{ collect($priorityActions)->where('type', 'closing')->count() }})</button>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 14px;" id="inbox-items-container">
            @foreach($priorityActions as $item)
            <div class="inbox-card-row" data-inbox-type="{{ $item['type'] }}">
                <div style="flex: 1; min-width: 280px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; flex-wrap: wrap;">
                        <span class="badge-tag {{ $item['type'] === 'listing' ? 'badge-tag-navy' : ($item['type'] === 'inquiry' ? 'badge-tag-teal' : 'badge-tag-white') }}">
                            {{ $item['id'] }}
                        </span>
                        <span style="font-size: 0.75rem; color: #64748B;">{{ $item['time_ago'] }}</span>
                        <span class="badge-tag" style="background: {{ $item['sla_status'] === 'urgent' ? '#E6FBF5' : ($item['sla_status'] === 'warning' ? '#FEF3C7' : '#F1F5F9') }}; color: {{ $item['sla_status'] === 'urgent' ? '#059669' : ($item['sla_status'] === 'warning' ? '#D97706' : '#475569') }}; font-size: 0.72rem;">
                            {{ $item['sla_badge'] }}
                        </span>
                    </div>

                    <strong style="display: block; font-size: 1rem; color: var(--primary-navy); margin-bottom: 4px;">{{ $item['title'] }}</strong>
                    <p style="font-size: 0.85rem; color: #64748B; line-height: 1.45; margin-bottom: 6px;">{{ $item['detail'] }}</p>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                        <span>Kontak Terkait: <strong>{{ $item['owner'] }}</strong> ({{ $item['phone'] }})</span>
                    </div>
                </div>

                <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                    <a href="{{ $item['url_1'] ?? '#' }}" target="_blank" class="btn-outline-navy" style="text-decoration: none; display: inline-flex; align-items: center; padding: 8px 14px; font-size: 0.82rem; border-radius: 6px;">
                        {{ $item['btn_action_1'] }}
                    </a>
                    <a href="{{ $item['url_2'] ?? '#' }}" class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; padding: 8px 14px; font-size: 0.82rem; border-radius: 6px;">
                        {{ $item['btn_action_2'] }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- 2 Bottom Widgets: Audit Trail & Portfolio Distribution -->
    <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 24px;">
        
        <!-- Real-time Audit Trail Log -->
        <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 14px; padding: 24px; box-shadow: var(--shadow-sm);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                <div>
                    <h3 style="font-size: 1.15rem; color: var(--primary-navy); font-weight: 700;">Audit Trail Aktivitas Sistem</h3>
                    <p style="font-size: 0.78rem; color: var(--text-muted);">Pencatatan waktu nyata untuk setiap modifikasi data & transaksi.</p>
                </div>
                <span style="font-size: 0.72rem; color: #059669; font-weight: 700; background: #E6FBF5; padding: 4px 8px; border-radius: 4px;">Live Logger Active</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($auditLogs as $log)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: #F8FAFC; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.82rem;">
                    <div>
                        <strong style="color: var(--primary-navy); display: block;">{{ $log['action'] }}</strong>
                        <span style="color: var(--text-muted); font-size: 0.75rem;">Oleh: {{ $log['user'] }}</span>
                    </div>
                    <div style="text-align: right;">
                        <span class="badge-tag badge-tag-teal" style="font-size: 0.7rem; padding: 2px 6px;">{{ $log['status'] }}</span>
                        <span style="display: block; font-size: 0.72rem; color: #94A3B8; margin-top: 2px;">{{ $log['timestamp'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Portfolio Distribution Breakdown -->
        <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 14px; padding: 24px; box-shadow: var(--shadow-sm);">
            <div style="margin-bottom: 18px;">
                <h3 style="font-size: 1.15rem; color: var(--primary-navy); font-weight: 700;">Distribusi Portofolio Aset</h3>
                <p style="font-size: 0.78rem; color: var(--text-muted);">Porsi kategori dari 148 total unit aktif tayang.</p>
            </div>

            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.82rem; margin-bottom: 4px;">
                        <strong style="color: var(--primary-navy);">Rumah Tapak Mewah / Villa</strong>
                        <span style="font-weight: 700; color: var(--primary-navy);">60% (88 Unit)</span>
                    </div>
                    <div style="height: 8px; background: #E2E8F0; border-radius: 4px; overflow: hidden;">
                        <div style="width: 60%; height: 100%; background: var(--primary-navy); border-radius: 4px;"></div>
                    </div>
                </div>

                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.82rem; margin-bottom: 4px;">
                        <strong style="color: var(--primary-navy);">Ruko & Ruang Komersial</strong>
                        <span style="font-weight: 700; color: var(--primary-navy);">22% (33 Unit)</span>
                    </div>
                    <div style="height: 8px; background: #E2E8F0; border-radius: 4px; overflow: hidden;">
                        <div style="width: 22%; height: 100%; background: var(--accent-teal); border-radius: 4px;"></div>
                    </div>
                </div>

                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.82rem; margin-bottom: 4px;">
                        <strong style="color: var(--primary-navy);">Penthouse & Apartemen</strong>
                        <span style="font-weight: 700; color: var(--primary-navy);">12% (18 Unit)</span>
                    </div>
                    <div style="height: 8px; background: #E2E8F0; border-radius: 4px; overflow: hidden;">
                        <div style="width: 12%; height: 100%; background: #3B82F6; border-radius: 4px;"></div>
                    </div>
                </div>

                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.82rem; margin-bottom: 4px;">
                        <strong style="color: var(--primary-navy);">Tanah Kavling Strategis</strong>
                        <span style="font-weight: 700; color: var(--primary-navy);">6% (9 Unit)</span>
                    </div>
                    <div style="height: 8px; background: #E2E8F0; border-radius: 4px; overflow: hidden;">
                        <div style="width: 6%; height: 100%; background: #D97706; border-radius: 4px;"></div>
                    </div>
                </div>
            </div>

            <div style="margin-top: 20px; padding-top: 14px; border-top: 1px solid var(--border-light); font-size: 0.78rem; color: var(--text-muted); text-align: center;">
                Total Nilai Portofolio Terdaftar: <strong style="color: var(--primary-navy);">Rp 482 Miliar</strong>
            </div>
        </div>

    </div>

</div>
@endsection

@section('styles')
<style>
.kpi-card {
    background: #FFFFFF;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 20px;
    box-shadow: var(--shadow-sm);
}
.kpi-label {
    font-size: 0.78rem;
    font-weight: 700;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.kpi-value {
    font-family: var(--font-heading);
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary-navy);
    line-height: 1.15;
    letter-spacing: -0.02em;
}
.kpi-icon-box {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.inbox-filter-tab {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748B;
    background: transparent;
    border: none;
    cursor: pointer;
    transition: var(--transition-fast);
}
.inbox-filter-tab:hover {
    color: var(--primary-navy);
}
.inbox-filter-tab.active {
    background: #FFFFFF;
    color: var(--primary-navy);
    font-weight: 700;
    box-shadow: var(--shadow-sm);
}

.inbox-card-row {
    background: #F8FAFC;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    transition: var(--transition-fast);
}
.inbox-card-row:hover {
    border-color: #CBD5E1;
    background: #FFFFFF;
}

@media (max-width: 992px) {
    div[style*="grid-template-columns: 1.4fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection

@section('scripts')
<script>
function filterInbox(type, btn) {
    document.querySelectorAll('.inbox-filter-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const rows = document.querySelectorAll('.inbox-card-row');
    rows.forEach(row => {
        const itemType = row.getAttribute('data-inbox-type');
        if (type === 'all' || itemType === type) {
            row.style.display = 'flex';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endsection
