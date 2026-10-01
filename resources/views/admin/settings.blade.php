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

        <button type="button" class="btn-teal" style="padding: 10px 20px; font-size: 0.85rem; border-radius: 8px; cursor: pointer;" onclick="document.getElementById('settingsMainForm').submit()">
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

    <form id="settingsMainForm" action="{{ route('admin.settings.update') }}" method="POST">
        @csrf

        <!-- Tab 1: Kategori & Tipe Properti -->
        <div id="tab-kategori" class="settings-pane active">
            <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; padding: 26px; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                    <div>
                        <h3 style="font-size: 1.15rem; color: var(--primary-navy); font-weight: 700;">Taksonomi Kategori Induk</h3>
                        <p style="font-size: 0.8rem; color: var(--text-muted);">Atur kategori portofolio dan tag pencarian publik.</p>
                    </div>
                    <button type="button" class="btn-primary" style="padding: 8px 14px; font-size: 0.8rem; border-radius: 6px;" onclick="openCategoryModal()">
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
                                <input type="checkbox" {{ $cat['active'] ? 'checked' : '' }} onchange="toggleCategoryActive('{{ $cat['name'] }}', this)" style="accent-color: #00B488;">
                                <span>{{ $cat['active'] ? 'Aktif' : 'Non-aktif' }}</span>
                            </label>
                            <button type="button" class="btn-fav-tool" style="padding: 4px 8px; font-size: 0.75rem;" onclick="showToast('Kategori {{ $cat['name'] }} aktif.')">Info</button>
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
                    <button type="button" class="btn-primary" style="padding: 8px 14px; font-size: 0.8rem; border-radius: 6px;" onclick="openRegionModal()">
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
                                <input type="checkbox" {{ $reg['active'] ? 'checked' : '' }} onchange="toggleRegionActive('{{ $reg['name'] }}', this)" style="accent-color: #00B488;">
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
                        <input type="text" name="hotline_admin" class="form-input-field" value="{{ $settings['hotline_admin'] ?? '+62 812-8899-2345' }}">
                    </div>

                    <div>
                        <label class="form-label-field">Email Resmi Korespondensi *</label>
                        <input type="email" name="email_business" class="form-input-field" value="{{ $settings['email_business'] ?? 'concierge@kumalaproperty.id' }}">
                    </div>
                </div>

                <div style="margin-bottom: 18px;">
                    <label class="form-label-field">Alamat Kantor Pusat Jakarta</label>
                    <input type="text" name="hq_address" class="form-input-field" value="{{ $settings['hq_address'] ?? 'Kumala Tower Lt. 18, Jl. TB Simatupang No. 88, Cilandak, Jakarta Selatan 12430' }}">
                </div>

                <div style="margin-bottom: 18px;">
                    <label class="form-label-field">Alamat Kantor Perwakilan Regional Bali</label>
                    <input type="text" name="branch_bali_address" class="form-input-field" value="{{ $settings['branch_bali_address'] ?? 'Sunset Boulevard Suites Kav. 12, Jl. Sunset Road No. 42, Kuta, Badung, Bali 80361' }}">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
                    <div>
                        <label class="form-label-field">Tautan Instagram Resmi</label>
                        <input type="text" name="instagram_handle" class="form-input-field" value="{{ $settings['instagram_handle'] ?? 'https://instagram.com/kumalaproperty.luxury' }}">
                    </div>

                    <div>
                        <label class="form-label-field">Tautan Saluran YouTube Virtual Tour</label>
                        <input type="text" name="youtube_channel" class="form-input-field" value="{{ $settings['youtube_channel'] ?? 'https://youtube.com/@KumalaPropertyTours' }}">
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
                        <input type="checkbox" name="notify_new_submission" value="1" {{ ($settings['notify_new_submission'] ?? '1') == '1' ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: #00B488;">
                    </label>

                    <label style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; background: #F8FAFC; border-radius: 8px; border: 1px solid var(--border-light); cursor: pointer;">
                        <div>
                            <strong style="color: var(--primary-navy); font-size: 0.9rem; display: block;">Forwarding WhatsApp Instan untuk Inquiry VIP</strong>
                            <span style="font-size: 0.78rem; color: var(--text-muted);">Teruskan formulir minat beli langsung ke WhatsApp on-duty Private Concierge.</span>
                        </div>
                        <input type="checkbox" name="notify_instant_whatsapp" value="1" {{ ($settings['notify_instant_whatsapp'] ?? '1') == '1' ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: #00B488;">
                    </label>

                    <label style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; background: #F8FAFC; border-radius: 8px; border: 1px solid var(--border-light); cursor: pointer;">
                        <div>
                            <strong style="color: var(--primary-navy); font-size: 0.9rem; display: block;">Watermark Otomatis Logo Kumala pada Foto Listing</strong>
                            <span style="font-size: 0.78rem; color: var(--text-muted);">Menerapkan perlindungan hak cipta gambar untuk mencegah duplikasi pihak ketiga.</span>
                        </div>
                        <input type="checkbox" name="auto_watermark" value="1" {{ ($settings['auto_watermark'] ?? '1') == '1' ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: #00B488;">
                    </label>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; padding-top: 18px; border-top: 1px solid var(--border-light);">
                    <div>
                        <label class="form-label-field">Tarif Standar Komisi Penjualan (%)</label>
                        <input type="number" step="0.1" name="standard_commission_pct" class="form-input-field" value="{{ $settings['standard_commission_pct'] ?? '2.5' }}">
                    </div>

                    <div>
                        <label class="form-label-field">Tarif Standar Komisi Sewa (%)</label>
                        <input type="number" step="0.1" name="standard_commission_rent_pct" class="form-input-field" value="{{ $settings['standard_commission_rent_pct'] ?? '5.0' }}">
                    </div>
                </div>
            </div>
        </div>

    </form>

    <!-- Modal Tambah Kategori -->
    <div id="modalCategory" style="display: none; position: fixed; inset: 0; background: rgba(14, 34, 56, 0.7); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #FFFFFF; border-radius: 12px; max-width: 440px; width: 100%; padding: 24px; box-shadow: var(--shadow-lg);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 1.15rem; color: var(--primary-navy); font-weight: 700;">Tambah Kategori Baru</h3>
                <button type="button" onclick="closeCategoryModal()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #94A3B8;">&times;</button>
            </div>
            <form action="{{ route('admin.settings.category.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 12px;">
                @csrf
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Nama Kategori Induk *</label>
                    <input type="text" name="name" required placeholder="Contoh: Penthouse Eksklusif" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Sub-tipe Deskriptif</label>
                    <input type="text" name="subtypes" placeholder="Contoh: Sky Villa, Duplex, Luxury Tower" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem;">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 8px;">
                    <button type="button" class="btn-fav-tool" onclick="closeCategoryModal()">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Wilayah -->
    <div id="modalRegion" style="display: none; position: fixed; inset: 0; background: rgba(14, 34, 56, 0.7); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #FFFFFF; border-radius: 12px; max-width: 440px; width: 100%; padding: 24px; box-shadow: var(--shadow-lg);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 1.15rem; color: var(--primary-navy); font-weight: 700;">Tambah Wilayah Baru</h3>
                <button type="button" onclick="closeRegionModal()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #94A3B8;">&times;</button>
            </div>
            <form action="{{ route('admin.settings.region.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 12px;">
                @csrf
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Nama Wilayah Lengkap *</label>
                    <input type="text" name="name" required placeholder="Contoh: DI Yogyakarta - Sleman" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Kota / Daerah *</label>
                    <input type="text" name="city" required placeholder="Contoh: Yogyakarta" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Kawasan Cakupan</label>
                    <input type="text" name="area" placeholder="Contoh: Kaliurang, Palagan, Condongcatur" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem;">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 8px;">
                    <button type="button" class="btn-fav-tool" onclick="closeRegionModal()">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Wilayah</button>
                </div>
            </form>
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

function openCategoryModal() {
    document.getElementById('modalCategory').style.display = 'flex';
}
function closeCategoryModal() {
    document.getElementById('modalCategory').style.display = 'none';
}

function openRegionModal() {
    document.getElementById('modalRegion').style.display = 'flex';
}
function closeRegionModal() {
    document.getElementById('modalRegion').style.display = 'none';
}

function toggleCategoryActive(name, checkbox) {
    fetch("{{ route('admin.settings.category.toggle') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: JSON.stringify({ name: name })
    })
    .then(r => r.json())
    .then(data => {
        showToast("Status kategori " + name + " diperbarui.");
    })
    .catch(() => {
        checkbox.checked = !checkbox.checked;
        showToast("Gagal memperbarui status kategori.");
    });
}

function toggleRegionActive(name, checkbox) {
    fetch("{{ route('admin.settings.region.toggle') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: JSON.stringify({ name: name })
    })
    .then(r => r.json())
    .then(data => {
        showToast("Status wilayah " + name + " diperbarui.");
    })
    .catch(() => {
        checkbox.checked = !checkbox.checked;
        showToast("Gagal memperbarui status wilayah.");
    });
}
</script>
@endsection
