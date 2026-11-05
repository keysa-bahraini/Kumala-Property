@extends('layouts.admin')

@section('title', 'Riwayat Terjual & Tersewa - Kumala Admin')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 6px; color: #00838F; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.6px; text-transform: uppercase; margin-bottom: 6px;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>CLOSING ARCHIVE &bull; ARSIP TRANSAKSI FINAL</span>
            </div>
            <h1 style="font-size: 1.75rem; color: #0E1E2E; margin: 0 0 4px 0; font-weight: 800; letter-spacing: -0.01em;">
                Riwayat Terjual &amp; Tersewa (Closing Archive)
            </h1>
            <p style="font-size: 0.85rem; color: #64748B; margin: 0; max-width: 760px; line-height: 1.45;">
                Laporan transaksi final, komisi, waktu pemasaran (days on market), dan evaluasi nilai transaksi real estat Kumala Property.
            </p>
        </div>

        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            <a href="{{ route('admin.export.transactions') }}" target="_blank" class="btn-top-outline">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                <span>Cetak Rekap PDF</span>
            </a>
            <button type="button" class="btn-top-teal" onclick="openClosingModal()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="16"/><line x1="8" x2="16" y1="12" y2="12"/></svg>
                <span>+ Catat Transaksi Closing</span>
            </button>
        </div>
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
                        <th style="padding: 12px 24px; width: 200px;">NO. TRANSAKSI &amp; TANGGAL</th>
                        <th style="padding: 12px 24px;">UNIT PROPERTI &amp; ID</th>
                        <th style="padding: 12px 24px; width: 220px;">JENIS TRANSAKSI</th>
                        <th style="padding: 12px 24px; text-align: right; width: 200px;">HARGA LISTING VS DEAL</th>
                        <th style="padding: 12px 24px; text-align: center; width: 120px;">AKSI</th>
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
                        <td style="padding: 16px 24px; text-align: center;">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                                @php
                                    $editTxData = [
                                        'id' => $tx['id'],
                                        'property_id' => $tx['property_id'] ?? '',
                                        'property_title' => $tx['property_title'] ?? ($tx['title'] ?? ''),
                                        'trx_type' => $tx['trx_type'] ?? 'TERJUAL - JUAL BELI',
                                        'deal_price' => $tx['deal_price'] ?? ($tx['price_deal'] ?? ''),
                                        'parties' => $tx['parties'] ?? '',
                                        'notary' => $tx['notary'] ?? '',
                                    ];
                                    $publicPropUrl = !empty($tx['property_slug']) 
                                        ? route('property.detail', $tx['property_slug']) 
                                        : (!empty($tx['property_id']) ? route('explore', ['q' => $tx['property_id']]) : route('explore'));
                                @endphp
                                <button type="button" class="btn-icon-subtle" title="Edit Transaksi" data-tx="{{ json_encode($editTxData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}" onclick="openClosingModalFromBtn(this)">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </button>
                                <a href="{{ $publicPropUrl }}" target="_blank" rel="noopener noreferrer" class="btn-icon-subtle" title="Buka Halaman Publik">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                                </a>
                                @if(auth()->user() && auth()->user()->role === 'super_admin')
                                <form action="{{ route('admin.transactions.delete', rawurlencode($tx['id'])) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi closing {{ $tx['id'] }}? Tindakan ini tidak dapat dibatalkan dan status unit properti akan dikembalikan.')" style="margin: 0; display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon-subtle" title="Hapus Transaksi" style="color: #DC2626;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 24px; text-align: center; color: #64748B;">
                            Belum ada riwayat closing transaksi tersimpan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Input Closing Transaksi -->
    <div id="modalClosing" style="display: none; position: fixed; inset: 0; background: rgba(14, 34, 56, 0.7); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #FFFFFF; border-radius: 14px; max-width: 520px; width: 100%; padding: 28px; box-shadow: var(--shadow-lg); border: 1px solid var(--border-color);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 id="modalClosingTitle" style="font-size: 1.25rem; color: var(--primary-navy); font-weight: 700;">Catat Closing Transaksi</h3>
                <button type="button" onclick="closeClosingModal()" style="background: none; border: none; font-size: 1.3rem; cursor: pointer; color: #94A3B8;">&times;</button>
            </div>
            <form id="formClosing" action="{{ route('admin.transactions.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 14px;">
                @csrf
                <input type="hidden" name="_method" id="closingFormMethod" value="POST">
                <input type="hidden" name="property_id" id="closingPropertyIdHidden" value="" disabled>

                <div id="closingModalErrorBox" style="{{ $errors->any() ? 'display: block;' : 'display: none;' }} background: #FEF2F2; border: 1px solid #F87171; border-radius: 8px; padding: 10px 14px; margin-bottom: 4px;">
                    <div style="font-size: 0.8rem; font-weight: 700; color: #991B1B; margin-bottom: 2px;">Periksa kembali isian:</div>
                    <ul id="closingModalErrorList" style="margin: 0; padding-left: 18px; font-size: 0.78rem; color: #B91C1C;">
                        @if($errors->any())
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <div id="closingPropSelectGroup">
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Pilih Properti Aktif</label>
                    <select name="property_id" id="closingPropertySelect" required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                        @if(!empty($activeProperties))
                            @foreach($activeProperties as $prop)
                                <option value="{{ $prop->id }}">{{ $prop->id }} - {{ $prop->title }} ({{ $prop->price_formatted }})</option>
                            @endforeach
                        @else
                            <option value="#KP-8821">#KP-8821 - Villa Cliffside Horizon Uluwatu</option>
                        @endif
                    </select>
                </div>
                <div id="closingPropDisplayGroup" style="display: none;">
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Unit Properti Terkait</label>
                    <input type="text" id="closingPropertyDisplay" readonly style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F1F5F9; color: #475569;">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Jenis Transaksi</label>
                        <select name="trx_type" id="closingTrxType" required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; background: #F8FAFC;">
                            <option value="TERJUAL - JUAL BELI">TERJUAL - JUAL BELI</option>
                            <option value="TERSEWA - 2 TAHUN">TERSEWA - 2 TAHUN</option>
                            <option value="TERSEWA - 1 TAHUN">TERSEWA - 1 TAHUN</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Harga Deal Final</label>
                        <input type="text" name="deal_price" id="closingDealPrice" placeholder="Contoh: Rp 40.000.000.000" required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem;">
                    </div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Pihak Transaksi</label>
                    <input type="text" name="parties" id="closingParties" placeholder="Penjual: Bpk. Tanu / Pembeli: Konsorsium Artha" required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Notaris Rekanan</label>
                    <input type="text" name="notary" id="closingNotary" value="Dra. Sri Wahyuni, S.H., M.Kn." required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem;">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="button" class="btn-fav-tool" onclick="closeClosingModal()">Batal</button>
                    <button type="submit" id="btnSubmitClosing" class="btn-teal">Simpan & Bukukan Closing</button>
                </div>
            </form>
        </div>
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
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
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
    text-decoration: none;
}
.btn-icon-subtle:hover {
    background: #F1F5F9;
    color: #00838F;
}
</style>
@endsection

@section('scripts')
<script>
function openClosingModalFromBtn(btn) {
    try {
        const raw = btn.getAttribute('data-tx');
        const data = typeof raw === 'string' ? JSON.parse(raw) : raw;
        openClosingModal('edit', data);
    } catch (e) {
        console.error('Failed to parse tx data:', e);
    }
}

function openClosingModal(mode = 'create', data = null) {
    const modal = document.getElementById('modalClosing');
    const form = document.getElementById('formClosing');
    const title = document.getElementById('modalClosingTitle');
    const submitBtn = document.getElementById('btnSubmitClosing');
    const formMethod = document.getElementById('closingFormMethod');
    const selectGroup = document.getElementById('closingPropSelectGroup');
    const displayGroup = document.getElementById('closingPropDisplayGroup');
    const propSelect = document.getElementById('closingPropertySelect');
    const propDisplay = document.getElementById('closingPropertyDisplay');
    const propIdHidden = document.getElementById('closingPropertyIdHidden');
    const trxType = document.getElementById('closingTrxType');
    const dealPrice = document.getElementById('closingDealPrice');
    const parties = document.getElementById('closingParties');
    const notary = document.getElementById('closingNotary');
    const errorBox = document.getElementById('closingModalErrorBox');
    const errorList = document.getElementById('closingModalErrorList');

    if (!modal) return;

    if (errorBox) errorBox.style.display = 'none';
    if (errorList) errorList.innerHTML = '';

    if (mode === 'create') {
        if (title) title.textContent = 'Catat Closing Transaksi';
        if (submitBtn) {
            submitBtn.textContent = 'Simpan & Bukukan Closing';
            submitBtn.disabled = false;
        }
        if (formMethod) formMethod.value = 'POST';
        if (form) {
            form.action = "{{ route('admin.transactions.store') }}";
            form.reset();
        }
        if (selectGroup) selectGroup.style.display = 'block';
        if (displayGroup) displayGroup.style.display = 'none';
        if (propSelect) {
            propSelect.disabled = false;
            propSelect.required = true;
        }
        if (propIdHidden) {
            propIdHidden.disabled = true;
            propIdHidden.value = '';
        }
    } else if (mode === 'edit' && data) {
        if (title) title.textContent = 'Edit Closing ' + (data.id || '');
        if (submitBtn) {
            submitBtn.textContent = 'Simpan Perubahan';
            submitBtn.disabled = false;
        }
        if (formMethod) formMethod.value = 'PUT';
        if (form) {
            form.action = "/admin/riwayat-transaksi/" + encodeURIComponent(data.id) + "/update";
        }
        if (selectGroup) selectGroup.style.display = 'none';
        if (displayGroup) displayGroup.style.display = 'block';
        if (propSelect) {
            propSelect.disabled = true;
            propSelect.required = false;
        }
        if (propIdHidden) {
            propIdHidden.disabled = false;
            propIdHidden.value = data.property_id || '';
        }
        if (propDisplay) propDisplay.value = (data.property_id ? data.property_id + ' - ' : '') + (data.property_title || '');
        if (trxType) trxType.value = data.trx_type || 'TERJUAL - JUAL BELI';
        if (dealPrice) dealPrice.value = data.deal_price || '';
        if (parties) parties.value = data.parties || '';
        if (notary) notary.value = data.notary || 'Dra. Sri Wahyuni, S.H., M.Kn.';
    }

    modal.style.display = 'flex';
}

function closeClosingModal() {
    const modal = document.getElementById('modalClosing');
    if (modal) modal.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formClosing');
    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('btnSubmitClosing');
        const origBtnText = submitBtn ? submitBtn.textContent : 'Simpan';
        const errorBox = document.getElementById('closingModalErrorBox');
        const errorList = document.getElementById('closingModalErrorList');

        if (errorBox) errorBox.style.display = 'none';
        if (errorList) errorList.innerHTML = '';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
        }

        try {
            const formData = new FormData(form);
            const token = document.querySelector('input[name="_token"]')?.value || '';

            const res = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: formData
            });

            const data = await res.json().catch(() => ({}));

            if (res.ok && data.success) {
                if (typeof showToast === 'function') {
                    showToast(data.message || 'Transaksi berhasil disimpan!');
                }
                closeClosingModal();
                setTimeout(() => {
                    window.location.reload();
                }, 600);
            } else {
                let errors = [];
                if (data.errors) {
                    for (const k in data.errors) {
                        errors = errors.concat(data.errors[k]);
                    }
                } else if (data.message) {
                    errors.push(data.message);
                } else {
                    errors.push('Terjadi kesalahan saat menyimpan transaksi. Silakan periksa isian.');
                }

                if (errorList) {
                    errorList.innerHTML = errors.map(err => '<li>' + err + '</li>').join('');
                }
                if (errorBox) {
                    errorBox.style.display = 'block';
                }
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = origBtnText;
                }
            }
        } catch (err) {
            console.error(err);
            if (errorList) {
                errorList.innerHTML = '<li>Gagal mengirim formulir. Periksa koneksi jaringan.</li>';
            }
            if (errorBox) {
                errorBox.style.display = 'block';
            }
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = origBtnText;
            }
        }
    });

    @if($errors->any())
        const modal = document.getElementById('modalClosing');
        if (modal) modal.style.display = 'flex';
    @endif
});
</script>
@endsection
