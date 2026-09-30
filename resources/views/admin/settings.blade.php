@extends('layouts.admin')

@section('title', 'Pengaturan Sistem & Master Data - Kumala Admin')

@section('content')
<div>

    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                <span class="badge-tag badge-tag-navy" style="font-size: 0.72rem;">SYSTEM CONFIGURATION</span>
                <span style="font-size: 0.78rem; color: var(--text-muted);">Kendali Parameter Bisnis & Master Data Taksonomi</span>
            </div>
            <h1 style="font-size: 1.65rem; color: var(--primary-navy); margin-bottom: 4px; font-weight: 800;">
                Pengaturan Sistem & Master Data
            </h1>
            <p style="font-size: 0.88rem; color: var(--text-muted);">
                Konfigurasi parameter global, taksonomi kategori, wilayah jangkauan, dan kendali otomatisasi sistem Kumala Property.
            </p>
        </div>

        <button type="button" class="btn-teal" style="padding: 10px 20px; font-size: 0.85rem; border-radius: 8px;" onclick="showToast('Seluruh konfigurasi sistem berhasil disimpan & disinkronkan!')">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Simpan Seluruh Pengaturan</span>
        </button>
    </div>

    <!-- Configuration Section Tabs -->
    <div style="display: flex; gap: 8px; margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; flex-wrap: wrap;">
        <button type="button" class="settings-tab-btn active" onclick="switchSettingsTab('tab-kategori', this)">Kategori & Tipe Properti</button>
        <button type="button" class="settings-tab-btn" onclick="switchSettingsTab('tab-wilayah', this)">Wilayah Operasional</button>
        <button type="button" class="settings-tab-btn" onclick="switchSettingsTab('tab-profil', this)">Profil Kantor & Kontak Resmi</button>
        <button type="button" class="settings-tab-btn" onclick="switchSettingsTab('tab-parameter', this)">Parameter Bisnis & Notifikasi</button>
    </div>

    <!-- Tab 1: Kategori & Tipe Properti -->
    <div id="tab-kategori" class="settings-pane active">
        <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; padding: 26px; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                <div>
                    <h3 style="font-size: 1.15rem; color: var(--primary-navy); font-weight: 700;">Taksonomi Kategori Induk</h3>
                    <p style="font-size: 0.8rem; color: var(--text-muted);">Atur kategori portofolio dan tag pencarian publik.</p>
                </div>
                <button type="button" class="btn-primary" style="padding: 8px 14px; font-size: 0.8rem; border-radius: 6px;" onclick="showToast('Membuka modal tambah kategori baru...')">
                    + Tambah Kategori
                </button>
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($categories as $cat)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; background: #F8FAFC; border-radius: 8px; border: 1px solid var(--border-light); flex-wrap: wrap; gap: 12px;">
                    <div>
                        <strong style="color: var(--primary-navy); font-size: 0.95rem; display: block; margin-bottom: 2px;">{{ $cat['name'] }}</strong>
                        <span style="font-size: 0.78rem; color: var(--text-muted);">Sub-tipe: {{ $cat['subtypes'] }}</span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 16px;">
                        <span style="font-size: 0.8rem; color: #475569; font-weight: 600;">{{ $cat['count'] }} Unit Aktif</span>
                        <label style="display: flex; align-items: center; gap: 6px; font-size: 0.78rem; color: #059669; cursor: pointer;">
                            <input type="checkbox" checked style="accent-color: #00B488;">
                            <span>Aktif</span>
                        </label>
                        <button type="button" class="btn-fav-tool" style="padding: 4px 8px; font-size: 0.75rem;" onclick="showToast('Edit kategori {{ $cat['name'] }}')">Edit</button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Tab 2: Wilayah Operasional -->
    <div id="tab-wilayah" class="settings-pane" style="display: none;">
        <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; padding: 26px; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                <div>
                    <h3 style="font-size: 1.15rem; color: var(--primary-navy); font-weight: 700;">Wilayah & Kota Jangkauan Layanan</h3>
                    <p style="font-size: 0.8rem; color: var(--text-muted);">Wilayah yang tampil pada filter pencarian publik dan form konsinyasi.</p>
                </div>
                <button type="button" class="btn-primary" style="padding: 8px 14px; font-size: 0.8rem; border-radius: 6px;" onclick="showToast('Membuka modal tambah wilayah operasional...')">
                    + Tambah Wilayah
                </button>
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($regions as $reg)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; background: #F8FAFC; border-radius: 8px; border: 1px solid var(--border-light);">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent-teal)" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <strong style="color: var(--primary-navy); font-size: 0.92rem;">{{ $reg['name'] }}</strong>
                    </div>

                    <div style="display: flex; align-items: center; gap: 16px;">
                        <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $reg['listings'] }} Listing Terdaftar</span>
                        <label style="display: flex; align-items: center; gap: 6px; font-size: 0.78rem; cursor: pointer;">
                            <input type="checkbox" {{ $reg['active'] ? 'checked' : '' }} style="accent-color: #00B488;">
                            <span style="color: {{ $reg['active'] ? '#059669' : '#94A3B8' }}; font-weight: 600;">{{ $reg['active'] ? 'Aktif' : 'Non-aktif' }}</span>
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Tab 3: Profil Kantor & Kontak Resmi -->
    <div id="tab-profil" class="settings-pane" style="display: none;">
        <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; padding: 26px; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
            <h3 style="font-size: 1.15rem; color: var(--primary-navy); font-weight: 700; margin-bottom: 18px;">Informasi Kontak & Jam Kerja Kantor</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                <div>
                    <label class="form-label-field">WhatsApp Hotline Client Care (Primary) *</label>
                    <input type="text" class="form-input-field" value="+62 812-8899-2345">
                </div>

                <div>
                    <label class="form-label-field">Email Resmi Korespondensi *</label>
                    <input type="email" class="form-input-field" value="concierge@kumalaproperty.id">
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <label class="form-label-field">Alamat Kantor Pusat Jakarta</label>
                <input type="text" class="form-input-field" value="Kumala Tower Lt. 18, Jl. TB Simatupang No. 88, Cilandak, Jakarta Selatan 12430">
            </div>

            <div style="margin-bottom: 18px;">
                <label class="form-label-field">Alamat Kantor Perwakilan Regional Bali</label>
                <input type="text" class="form-input-field" value="Sunset Boulevard Suites Kav. 12, Jl. Sunset Road No. 42, Kuta, Badung, Bali 80361">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
                <div>
                    <label class="form-label-field">Tautan Instagram Resmi</label>
                    <input type="text" class="form-input-field" value="https://instagram.com/kumalaproperty.luxury">
                </div>

                <div>
                    <label class="form-label-field">Tautan Saluran YouTube Virtual Tour</label>
                    <input type="text" class="form-input-field" value="https://youtube.com/@KumalaPropertyTours">
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 4: Parameter Bisnis & Notifikasi -->
    <div id="tab-parameter" class="settings-pane" style="display: none;">
        <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; padding: 26px; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
            <h3 style="font-size: 1.15rem; color: var(--primary-navy); font-weight: 700; margin-bottom: 18px;">Otomatisasi Sistem & Ketentuan Komisi</h3>

            <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px;">
                <label style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; background: #F8FAFC; border-radius: 8px; border: 1px solid var(--border-light); cursor: pointer;">
                    <div>
                        <strong style="color: var(--primary-navy); font-size: 0.9rem; display: block;">Notifikasi Email Pengajuan Titip Properti Otomatis</strong>
                        <span style="font-size: 0.78rem; color: var(--text-muted);">Kirimkan ringkasan berkas ke kurator segera setelah form online disubmit.</span>
                    </div>
                    <input type="checkbox" checked style="width: 20px; height: 20px; accent-color: #00B488;">
                </label>

                <label style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; background: #F8FAFC; border-radius: 8px; border: 1px solid var(--border-light); cursor: pointer;">
                    <div>
                        <strong style="color: var(--primary-navy); font-size: 0.9rem; display: block;">Forwarding WhatsApp Instan untuk Inquiry VIP</strong>
                        <span style="font-size: 0.78rem; color: var(--text-muted);">Teruskan formulir minat beli langsung ke WhatsApp on-duty Private Concierge.</span>
                    </div>
                    <input type="checkbox" checked style="width: 20px; height: 20px; accent-color: #00B488;">
                </label>

                <label style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; background: #F8FAFC; border-radius: 8px; border: 1px solid var(--border-light); cursor: pointer;">
                    <div>
                        <strong style="color: var(--primary-navy); font-size: 0.9rem; display: block;">Watermark Otomatis Logo Kumala pada Foto Listing</strong>
                        <span style="font-size: 0.78rem; color: var(--text-muted);">Menerapkan perlindungan hak cipta gambar untuk mencegah duplikasi pihak ketiga.</span>
                    </div>
                    <input type="checkbox" checked style="width: 20px; height: 20px; accent-color: #00B488;">
                </label>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; padding-top: 18px; border-top: 1px solid var(--border-light);">
                <div>
                    <label class="form-label-field">Tarif Standar Komisi Penjualan (%)</label>
                    <input type="number" step="0.1" class="form-input-field" value="2.5">
                </div>

                <div>
                    <label class="form-label-field">Tarif Standar Komisi Sewa (%)</label>
                    <input type="number" step="0.1" class="form-input-field" value="5.0">
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('styles')
<style>
.settings-tab-btn {
    padding: 8px 18px;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #64748B;
    background: transparent;
    border: none;
    cursor: pointer;
    transition: var(--transition-fast);
}
.settings-tab-btn:hover {
    color: var(--primary-navy);
}
.settings-tab-btn.active {
    background: var(--primary-navy);
    color: #FFFFFF;
}

@media (max-width: 768px) {
    div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection

@section('scripts')
<script>
function switchSettingsTab(tabId, btn) {
    document.querySelectorAll('.settings-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    document.querySelectorAll('.settings-pane').forEach(p => p.style.display = 'none');
    document.getElementById(tabId).style.display = 'block';
}
</script>
@endsection
