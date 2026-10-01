@extends('layouts.admin')

@section('title', 'Riwayat Terjual & Tersewa - Kumala Admin')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 6px; color: #00838F; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.6px; text-transform: uppercase; margin-bottom: 6px;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>ARSIP TRANSAKSI FINAL &amp; AUDIT PEMASARAN</span>
            </div>
            <h1 style="font-size: 1.75rem; color: #0E1E2E; margin: 0 0 4px 0; font-weight: 800; letter-spacing: -0.01em;">
                Riwayat Terjual &amp; Tersewa (Closing Archive)
            </h1>
            <p style="font-size: 0.85rem; color: #64748B; margin: 0; max-width: 760px; line-height: 1.45;">
                Laporan transaksi final, komisi, waktu pemasaran (days on market), dan evaluasi nilai transaksi real estat Kumala Property.
            </p>
        </div>

        <button type="button" class="btn-top-teal" onclick="openClosingModal()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="16"/><line x1="8" x2="16" y1="12" y2="12"/></svg>
            <span>+ Catat Transaksi Closing</span>
        </button>
    </div>

    <!-- Data Table Container -->
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.02);">
        
        <!-- Table Top Header -->
        <div style="padding: 18px 24px; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: #F1F5F9; color: #0E1E2E; display: flex; align-items: center; justify-content: center;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 0.95rem; font-weight: 800; color: #0E1E2E; margin: 0;">Data Transaksi Closing Terverifikasi</h3>
                    <p style="font-size: 0.76rem; color: #64748B; margin: 2px 0 0 0;">Menampilkan seluruh mutasi serah terima unit dan penyelesaian hak legalitas notaris.</p>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 8px; font-size: 0.78rem; color: #64748B;">
                <span>Total: <strong style="color: #0E1E2E;">{{ count($transactions) }} Arsip</strong></span>
            </div>
        </div>

        <!-- Table -->
        <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table style="width: 100%; min-width: 820px; border-collapse: collapse; text-align: left; font-size: 0.82rem;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; color: #475569; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.6px; text-transform: uppercase;">
                        <th style="padding: 12px 24px; width: 220px;">NO. TRANSAKSI &amp; TANGGAL</th>
                        <th style="padding: 12px 24px;">UNIT PROPERTI &amp; ID</th>
                        <th style="padding: 12px 24px; width: 240px;">JENIS TRANSAKSI</th>
                        <th style="padding: 12px 24px; text-align: right; width: 220px;">HARGA LISTING VS DEAL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                    @php
                        $isJual = stripos($tx['trx_type'] ?? '', 'JUAL') !== false;
                    @endphp
                    <tr class="tx-table-row">
                        <td style="padding: 16px 24px;">
                            <strong style="color: #0E1E2E; font-size: 0.88rem; display: block;">{{ $tx['id'] }}</strong>
                            <span style="font-size: 0.74rem; color: #64748B; display: inline-flex; align-items: center; gap: 4px; margin-top: 3px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                {{ $tx['date'] ?? date('d M Y') }}
                            </span>
                        </td>
                        <td style="padding: 16px 24px;">
                            <div style="display: flex; gap: 12px; align-items: center;">
                                <div style="width: 52px; height: 42px; border-radius: 6px; overflow: hidden; flex-shrink: 0; background: #0E1E2E;">
                                    <img src="{{ $tx['property_image'] ?? ($tx['image'] ?? 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=150&q=80') }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div>
                                    <strong style="color: #0E1E2E; font-size: 0.88rem; display: block;">{{ $tx['property_title'] ?? ($tx['title'] ?? 'Properti Closing') }}</strong>
                                    <span style="font-size: 0.74rem; color: #64748B; display: block;">{{ $tx['location'] ?? 'Jakarta Selatan' }}</span>
                                    <span style="color: #00838F; font-size: 0.72rem; font-weight: 700;">{{ $tx['property_id'] ?? '' }}</span>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 16px 24px;">
                            <span style="background: {{ $isJual ? '#E0F7FA' : '#EFF6FF' }}; color: {{ $isJual ? '#00838F' : '#2563EB' }}; padding: 4px 10px; border-radius: 14px; font-size: 0.72rem; font-weight: 800; display: inline-flex; align-items: center; gap: 5px;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ $isJual ? '#00838F' : '#2563EB' }};"></span>
                                {{ $tx['trx_type'] ?? 'TERJUAL' }}
                            </span>
                        </td>
                        <td style="padding: 16px 24px; text-align: right;">
                            <strong style="font-size: 0.92rem; color: #0E1E2E; display: block;">{{ $tx['deal_price'] ?? ($tx['price_deal'] ?? 'Rp 0') }}</strong>
                            <div style="font-size: 0.74rem; color: #64748B; margin-top: 2px;">
                                <span style="text-decoration: line-through;">{{ $tx['asking_price'] ?? ($tx['price_ask'] ?? '-') }}</span>
                                <strong style="color: #059669; margin-left: 4px;">{{ $tx['diff_pct'] ?? ($tx['diff_percent'] ?? 'Deal') }}</strong>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 24px; text-align: center; color: #64748B;">
                            Belum ada riwayat closing transaksi tersimpan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Catat Closing -->
<div id="closingModal" style="display: none; position: fixed; inset: 0; background: rgba(14, 30, 46, 0.6); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #FFFFFF; border-radius: 12px; width: 100%; max-width: 500px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #F1F5F9; padding-bottom: 12px;">
            <strong style="font-size: 1.1rem; color: #0E1E2E;">Catat Transaksi Closing Baru</strong>
            <button type="button" onclick="closeClosingModal()" style="background: none; border: none; cursor: pointer; color: #64748B; display: flex; align-items: center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('admin.transactions.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">ID Properti Terkait *</label>
                <input type="text" name="property_id" placeholder="Contoh: #KP-8821" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Judul Properti *</label>
                <input type="text" name="title" placeholder="Contoh: Rumah Mewah Kebayoran Baru" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Tipe Transaksi *</label>
                    <select name="trx_type" style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
                        <option value="TERJUAL – JUAL BELI">Jual Beli (Terjual)</option>
                        <option value="TERSEWA – SEWA 1 TAHUN">Sewa 1 Tahun (Tersewa)</option>
                        <option value="TERSEWA – SEWA 2 TAHUN">Sewa 2 Tahun (Tersewa)</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Lokasi Unit *</label>
                    <input type="text" name="location" placeholder="Jakarta Selatan" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Harga Deal Final *</label>
                    <input type="text" name="price_deal" placeholder="Rp 4.800.000.000" required style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Harga Penawaran Awal</label>
                    <input type="text" name="price_ask" placeholder="Rp 5.000.000.000" style="width: 100%; padding: 8px 12px; border: 1px solid #CBD5E1; border-radius: 6px;">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" onclick="closeClosingModal()" class="btn-top-outline">Batal</button>
                <button type="submit" class="btn-top-teal">Simpan Closing</button>
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
    cursor: pointer;
    transition: background 0.15s;
}
.btn-top-teal:hover {
    background: #0097A7;
}

.btn-top-outline {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    color: #0E1E2E;
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
}
.btn-top-outline:hover {
    background: #F8FAFC;
    border-color: #94A3B8;
}

.tx-table-row {
    border-bottom: 1px solid #F1F5F9;
    transition: background 0.15s;
}
.tx-table-row:hover {
    background: #F8FAFC;
}
</style>
@endsection

@section('scripts')
<script>
function openClosingModal() {
    const modal = document.getElementById('closingModal');
    if (modal) modal.style.display = 'flex';
}

function closeClosingModal() {
    const modal = document.getElementById('closingModal');
    if (modal) modal.style.display = 'none';
}
</script>
@endsection
