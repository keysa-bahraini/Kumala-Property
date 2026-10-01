@extends('layouts.admin')

@section('title', 'Riwayat Terjual & Tersewa - Kumala Admin')

@section('content')
<div>

    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                <span class="badge-tag badge-tag-navy" style="font-size: 0.72rem;">CLOSING ARCHIVE</span>
                <span style="font-size: 0.78rem; color: var(--text-muted);">Audit & Rekapitulasi Transaksi Notarial</span>
            </div>
            <h1 style="font-size: 1.65rem; color: var(--primary-navy); margin-bottom: 4px; font-weight: 800;">
                Riwayat Terjual & Tersewa
            </h1>
            <p style="font-size: 0.88rem; color: var(--text-muted);">
                Arsip rekapitulasi transaksi final, komparasi harga asking vs closing, audit legalitas AJB, dan rekonsiliasi komisi notarial.
            </p>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="button" class="btn-teal" style="padding: 10px 18px; font-size: 0.85rem; border-radius: 8px;" onclick="openClosingModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>+ Catat Closing Transaksi</span>
            </button>
            <a href="{{ route('admin.export.transactions') }}" target="_blank" class="btn-primary" style="padding: 10px 18px; font-size: 0.85rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                <span>Ekspor Laporan Closing (PDF)</span>
            </a>
        </div>
    </div>

    <!-- 4 Summary Metrics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="kpi-card" style="padding: 18px 20px;">
            <span class="kpi-label">Volume Transaksi (YTD)</span>
            <div style="font-size: 1.7rem; font-weight: 800; color: var(--primary-navy); margin-top: 4px;">{{ $metrics['total_volume'] }}</div>
            <span style="font-size: 0.75rem; color: #059669; font-weight: 600;">Akumulasi nilai transaksi sukses</span>
        </div>

        <div class="kpi-card" style="padding: 18px 20px;">
            <span class="kpi-label">Total Unit Selesai</span>
            <div style="font-size: 1.7rem; font-weight: 800; color: var(--primary-navy); margin-top: 4px;">{{ $metrics['total_units'] }} <span style="font-size: 0.9rem; font-weight: 500; color: #64748B;">Unit</span></div>
            <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $metrics['total_jual'] }} Terjual • {{ $metrics['total_sewa'] }} Tersewa</span>
        </div>

        <div class="kpi-card" style="padding: 18px 20px;">
            <span class="kpi-label">Rata-rata Waktu Pemasaran</span>
            <div style="font-size: 1.7rem; font-weight: 800; color: var(--accent-teal-dark); margin-top: 4px;">{{ $metrics['avg_dom'] }}</div>
            <span style="font-size: 0.75rem; color: #059669; font-weight: 600;">14% lebih cepat dari rata-rata industri</span>
        </div>

        <div class="kpi-card" style="padding: 18px 20px;">
            <span class="kpi-label">Akumulasi Komisi Tertagih</span>
            <div style="font-size: 1.7rem; font-weight: 800; color: #059669; margin-top: 4px;">{{ $metrics['commission_total'] }}</div>
            <span style="font-size: 0.75rem; color: var(--text-muted);">Rekonsiliasi notaris 100% tuntas</span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; padding: 14px 20px; margin-bottom: 24px; box-shadow: var(--shadow-sm); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
        <div style="display: flex; gap: 12px; flex: 1; max-width: 600px;">
            <div style="position: relative; flex: 1;">
                <input type="text" placeholder="Cari no. closing #CLS-xxx, nama unit, atau pembeli..." style="width: 100%; padding: 8px 12px 8px 34px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2" style="position: absolute; left: 11px; top: 50%; transform: translateY(-50%);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>

            <select style="padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                <option>Tahun 2025 (YTD)</option>
                <option>Tahun 2024</option>
                <option>Q1 2025</option>
            </select>

            <select style="padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                <option>Semua Transaksi</option>
                <option>Jual Beli</option>
                <option>Sewa</option>
            </select>
        </div>

        <span style="font-size: 0.8rem; color: var(--text-muted);">Menampilkan <strong>4</strong> arsip transaksi audit</span>
    </div>

    <!-- Closing Transactions Table -->
    <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-sm);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid var(--border-color); color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <th style="padding: 14px 16px;">No. Transaksi</th>
                        <th style="padding: 14px 16px;">Unit Properti</th>
                        <th style="padding: 14px 16px;">Pihak Transaksi</th>
                        <th style="padding: 14px 16px;">Jenis</th>
                        <th style="padding: 14px 16px;">Asking vs Deal</th>
                        <th style="padding: 14px 16px;">Komisi & Notaris</th>
                        <th style="padding: 14px 16px;">Status</th>
                        <th style="padding: 14px 16px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $t)
                    <tr style="border-bottom: 1px solid var(--border-light);" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 14px 16px;">
                            <strong style="color: var(--primary-navy); display: block;">{{ $t['id'] }}</strong>
                            <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $t['date'] }}</span>
                        </td>

                        <td style="padding: 14px 16px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <img src="{{ $t['property_image'] }}" alt="{{ $t['property_title'] }}" style="width: 50px; height: 38px; border-radius: 6px; object-fit: cover; background: #0E2238;">
                                <div>
                                    <strong style="color: var(--primary-navy); font-size: 0.88rem; display: block;">{{ $t['property_title'] }}</strong>
                                    <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $t['property_id'] }} • {{ $t['location'] }}</span>
                                </div>
                            </div>
                        </td>

                        <td style="padding: 14px 16px;">
                            <span style="font-size: 0.8rem; color: #475569; line-height: 1.4; display: block;">{{ $t['parties'] }}</span>
                        </td>

                        <td style="padding: 14px 16px;">
                            <span class="badge-tag {{ str_contains($t['trx_type'], 'TERJUAL') ? 'badge-tag-navy' : 'badge-tag-teal' }}" style="font-size: 0.68rem; padding: 3px 8px;">
                                {{ $t['trx_type'] }}
                            </span>
                        </td>

                        <td style="padding: 14px 16px;">
                            <strong style="color: var(--primary-navy); font-size: 0.92rem; display: block;">{{ $t['deal_price'] }}</strong>
                            <span style="font-size: 0.72rem; color: var(--text-muted);">Asking: {{ $t['asking_price'] }} <strong style="color: #D97706;">({{ $t['diff_pct'] }})</strong></span>
                        </td>

                        <td style="padding: 14px 16px;">
                            <strong style="color: #059669; font-size: 0.82rem; display: block;">{{ $t['commission_val'] }}</strong>
                            <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $t['notary'] }}</span>
                        </td>

                        <td style="padding: 14px 16px;">
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #E6FBF5; color: #059669; font-size: 0.72rem; font-weight: 700; padding: 4px 10px; border-radius: 6px;">
                                <span>✓ {{ $t['status_doc'] }}</span>
                            </span>
                        </td>

                        <td style="padding: 14px 16px; text-align: right;">
                            <button type="button" class="btn-fav-tool" style="padding: 6px 10px; font-size: 0.75rem;" onclick="showToast('Mencetak berkas akta resmi {{ $t['id'] }}...')">
                                Cetak Akta
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Input Closing Transaksi -->
    <div id="modalClosing" style="display: none; position: fixed; inset: 0; background: rgba(14, 34, 56, 0.7); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #FFFFFF; border-radius: 14px; max-width: 520px; width: 100%; padding: 28px; box-shadow: var(--shadow-lg); border: 1px solid var(--border-color);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-size: 1.25rem; color: var(--primary-navy); font-weight: 700;">Catat Closing Transaksi</h3>
                <button type="button" onclick="closeClosingModal()" style="background: none; border: none; font-size: 1.3rem; cursor: pointer; color: #94A3B8;">&times;</button>
            </div>
            <form action="{{ route('admin.transactions.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 14px;">
                @csrf
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Pilih Properti Aktif</label>
                    <select name="property_id" required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                        @if(!empty($activeProperties))
                            @foreach($activeProperties as $prop)
                                <option value="{{ $prop->id }}">{{ $prop->id }} - {{ $prop->title }} ({{ $prop->price_formatted }})</option>
                            @endforeach
                        @else
                            <option value="#KP-8821">#KP-8821 - Villa Cliffside Horizon Uluwatu</option>
                        @endif
                    </select>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Jenis Transaksi</label>
                        <select name="trx_type" required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                            <option value="TERJUAL - JUAL BELI">TERJUAL - JUAL BELI</option>
                            <option value="TERSEWA - 2 TAHUN">TERSEWA - 2 TAHUN</option>
                            <option value="TERSEWA - 1 TAHUN">TERSEWA - 1 TAHUN</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Harga Deal Final</label>
                        <input type="text" name="deal_price" placeholder="Contoh: Rp 40.000.000.000" required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem;">
                    </div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Pihak Transaksi</label>
                    <input type="text" name="parties" placeholder="Penjual: Bpk. Tanu / Pembeli: Konsorsium Artha" required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Notaris Rekanan</label>
                    <input type="text" name="notary" value="Dra. Sri Wahyuni, S.H., M.Kn." required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem;">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="button" class="btn-fav-tool" onclick="closeClosingModal()">Batal</button>
                    <button type="submit" class="btn-teal">Simpan & Bukukan Closing</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openClosingModal() {
        document.getElementById('modalClosing').style.display = 'flex';
    }
    function closeClosingModal() {
        document.getElementById('modalClosing').style.display = 'none';
    }
    </script>
</div>
@endsection
