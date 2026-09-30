@extends('layouts.app')

@section('title', 'Tentang Kami & Kontak - Kumala Property')

@section('content')
<div class="container" style="padding-top: 24px; padding-bottom: 90px;">

    <!-- Breadcrumbs -->
    <div class="breadcrumbs-bar" style="margin-bottom: 24px;">
        <ul class="breadcrumbs-list">
            <li class="breadcrumbs-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumbs-separator">&gt;</li>
            <li class="breadcrumbs-item active">Tentang Kami & Kontak</li>
        </ul>
    </div>

    <!-- Hero Narrative Header -->
    <div style="margin-bottom: 48px; max-width: 860px;">
        <span class="badge-tag badge-tag-teal" style="margin-bottom: 14px;">ATELIER REAL ESTATE INDEPENDEN</span>
        <h1 style="font-size: clamp(2.2rem, 4vw, 3.1rem); color: var(--primary-navy); margin-bottom: 18px; line-height: 1.2; font-weight: 800;">
            Kurasi Presisi untuk Koleksi Aset Bernilai Abadi
        </h1>
        <p style="font-size: 1.1rem; color: var(--text-muted); line-height: 1.7;">
            Kumala Property lahir dari keyakinan mendasar bahwa transaksi properti prestisius bukan sekadar urusan nominal penawaran, melainkan tentang apresiasi terhadap integritas arsitektural, transparansi legalitas tanpa celah, serta komitmen mutlak terhadap privasi klien terhormat.
        </p>
    </div>

    <!-- Key Metrics Highlight Bar -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 60px;">
        <div class="about-metric-card">
            <span class="about-metric-number">15%</span>
            <strong class="about-metric-title">Tingkat Lolos Kurasi</strong>
            <p class="about-metric-desc">Dari ratusan pengajuan bulanan, hanya aset dengan standar kualitas tertinggi yang kami publikasikan.</p>
        </div>

        <div class="about-metric-card">
            <span class="about-metric-number">100%</span>
            <strong class="about-metric-title">Verifikasi Notarial BPN</strong>
            <p class="about-metric-desc">Seluruh sertifikat SHM/HGB dan izin PBG diaudit tuntas oleh notaris rekanan independen.</p>
        </div>

        <div class="about-metric-card">
            <span class="about-metric-number">Rp 2.4T+</span>
            <strong class="about-metric-title">Portofolio Terfasilitasi</strong>
            <p class="about-metric-desc">Akumulasi nilai transaksi properti residensial dan komersial sejak pendirian firma kami.</p>
        </div>

        <div class="about-metric-card">
            <span class="about-metric-number">&lt; 4 Jam</span>
            <strong class="about-metric-title">SLA Verifikasi Konsinyasi</strong>
            <p class="about-metric-desc">Kecepatan tim kurator kami dalam merespons dan melakukan valuasi awal pengajuan unit.</p>
        </div>
    </div>

    <!-- Tiga Pilar Filosofi Kami -->
    <div style="margin-bottom: 70px;">
        <div style="text-align: center; max-width: 680px; margin: 0 auto 40px;">
            <span class="badge-tag badge-tag-navy" style="margin-bottom: 10px;">PRINSIP OPERASIONAL</span>
            <h2 style="font-size: 2rem; color: var(--primary-navy); margin-bottom: 10px;">Tiga Pilar Layanan Kumala</h2>
            <p style="font-size: 0.95rem; color: var(--text-muted); line-height: 1.6;">
                Landasan etika kerja yang membedakan kami dalam mendampingi pemilik maupun investor properti mewah.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
            <div class="pilar-card">
                <div class="pilar-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h3 style="font-size: 1.25rem; color: var(--primary-navy); margin-bottom: 10px;">1. Transparansi Tanpa Kompromi</h3>
                <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.6;">
                    Tidak ada harga titipan atau manipulasi margin. Pembeli dan penjual mendapatkan keterbukaan valuasi objektif berbasis Comparative Market Analysis (CMA).
                </p>
            </div>

            <div class="pilar-card">
                <div class="pilar-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                </div>
                <h3 style="font-size: 1.25rem; color: var(--primary-navy); margin-bottom: 10px;">2. Kurasi Estetika & Keandalan Struktur</h3>
                <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.6;">
                    Setiap unit melalui inspeksi fisik mendalam terhadap material bangunan, pencahayaan alami, zonasi lingkungan sekitar, dan potensi apresiasi investasi jangka panjang.
                </p>
            </div>

            <div class="pilar-card">
                <div class="pilar-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <h3 style="font-size: 1.25rem; color: var(--primary-navy); margin-bottom: 10px;">3. Pendampingan Privat & Obyektif</h3>
                <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.6;">
                    Private Client Concierge kami tidak bertindak sebagai sales agresif, melainkan penasihat aset yang berdedikasi menjaga kepentingan hukum dan kenyamanan Anda hingga penandatanganan AJB.
                </p>
            </div>
        </div>
    </div>

    <!-- Enam Keunggulan Kurasi Kumala Grid -->
    <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 16px; padding: 40px; margin-bottom: 70px; box-shadow: var(--shadow-sm);">
        <div style="margin-bottom: 30px;">
            <span class="badge-tag badge-tag-teal" style="margin-bottom: 10px;">STANDAR INDUSTRI</span>
            <h2 style="font-size: 1.8rem; color: var(--primary-navy);">Enam Keunggulan Standar Kurasi Kami</h2>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
            <div class="keunggulan-item">
                <div class="keunggulan-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 1.05rem; color: var(--primary-navy); margin-bottom: 4px;">Kurasi Arsitektural Ketat</strong>
                    <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;">Menyaring unit dengan tata ruang fungsional, pencahayaan alami optimal, dan material bernilai tinggi.</p>
                </div>
            </div>

            <div class="keunggulan-item">
                <div class="keunggulan-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 1.05rem; color: var(--primary-navy); margin-bottom: 4px;">Audit BPN & Legalitas Penuh</strong>
                    <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;">Pemeriksaan riwayat tanah resmi untuk memastikan bebas sita, sengketa waris, ataupun blokir perbankan.</p>
                </div>
            </div>

            <div class="keunggulan-item">
                <div class="keunggulan-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 1.05rem; color: var(--primary-navy); margin-bottom: 4px;">Privasi Klien Terlindungi (NDA)</strong>
                    <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;">Kerahasiaan identitas pemilik dan pembeli kelas atas dijamin dengan perjanjian kerahasiaan resmi.</p>
                </div>
            </div>

            <div class="keunggulan-item">
                <div class="keunggulan-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 1.05rem; color: var(--primary-navy); margin-bottom: 4px;">Bebas Biaya Di Muka</strong>
                    <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;">Pendaftaran konsinyasi dan valuasi gratis. Komisi hanya dibayarkan saat transaksi tuntas di PPAT.</p>
                </div>
            </div>

            <div class="keunggulan-item">
                <div class="keunggulan-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 1.05rem; color: var(--primary-navy); margin-bottom: 4px;">Pendampingan Notaris Lengkap</strong>
                    <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;">Mulai dari penyusunan draft PPJB, validasi pajak PPh/BPHTB, hingga serah terima kunci.</p>
                </div>
            </div>

            <div class="keunggulan-item">
                <div class="keunggulan-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 1.05rem; color: var(--primary-navy); margin-bottom: 4px;">Jejaring Portofolio Off-Market</strong>
                    <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;">Akses ke properti eksklusif yang dipasarkan secara tertutup atas permintaan khusus pemilik.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Kantor Operasional & Form Kontak (2 Kolom) -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: flex-start;">
        
        <!-- Kolom Kiri: Kantor & Kontak Info -->
        <div>
            <span class="badge-tag badge-tag-navy" style="margin-bottom: 12px;">PRESENSI REGIONAL</span>
            <h2 style="font-size: 1.8rem; color: var(--primary-navy); margin-bottom: 18px;">Kantor Operasional Kami</h2>
            <p style="font-size: 0.95rem; color: var(--text-muted); line-height: 1.6; margin-bottom: 24px;">
                Kami menyambut kehadiran Anda di kantor kami untuk berdiskusi langsung mengenai portofolio investasi ataupun rencana konsinyasi aset Anda.
            </p>

            <!-- Jakarta Office Card -->
            <div class="office-box" style="margin-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                    <div class="office-badge">KANTOR PUSAT</div>
                    <strong style="font-size: 1.1rem; color: var(--primary-navy);">Jakarta Selatan</strong>
                </div>
                <p style="font-size: 0.88rem; color: #475569; line-height: 1.5; margin-bottom: 12px;">
                    Kumala Tower Lt. 18, Jl. TB Simatupang No. 88, Cilandak, Jakarta Selatan, DKI Jakarta 12430
                </p>
                <div style="display: flex; gap: 16px; font-size: 0.82rem; color: var(--text-muted);">
                    <span>Senin - Jumat: 08.30 - 18.00 WIB</span>
                    <span>•</span>
                    <span style="color: var(--accent-teal); font-weight: 600;">Area Parkir Eksekutif</span>
                </div>
            </div>

            <!-- Bali Office Card -->
            <div class="office-box" style="margin-bottom: 24px;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                    <div class="office-badge">PERWAKILAN REGIONAL</div>
                    <strong style="font-size: 1.1rem; color: var(--primary-navy);">Bali & Nusa Tenggara</strong>
                </div>
                <p style="font-size: 0.88rem; color: #475569; line-height: 1.5; margin-bottom: 12px;">
                    Sunset Boulevard Suites Kav. 12, Jl. Sunset Road No. 42, Kuta, Kabupaten Badung, Bali 80361
                </p>
                <div style="display: flex; gap: 16px; font-size: 0.82rem; color: var(--text-muted);">
                    <span>Senin - Sabtu: 09.00 - 17.00 WITA</span>
                    <span>•</span>
                    <span style="color: var(--accent-teal); font-weight: 600;">Layanan Antar-Jemput Survei</span>
                </div>
            </div>

            <!-- Direct Contact Items -->
            <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; display: flex; flex-direction: column; gap: 14px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #E6FBF5; display: flex; align-items: center; justify-content: center; color: var(--accent-teal);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">HOTLINE WHATSAPP CLIENT CARE</span>
                        <strong style="color: var(--primary-navy); font-size: 0.95rem;">+62 812-8899-2345 (Tersedia 24/7)</strong>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #EBF1F5; display: flex; align-items: center; justify-content: center; color: var(--primary-navy);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">EMAIL KORESPONDENSI RESMI</span>
                        <strong style="color: var(--primary-navy); font-size: 0.95rem;">concierge@kumalaproperty.id</strong>
                    </div>
                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Form Kirim Pesan Konsultasi -->
        <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 16px; padding: 32px; box-shadow: var(--shadow-sm);">
            <div style="margin-bottom: 20px;">
                <h3 style="font-size: 1.35rem; color: var(--primary-navy); margin-bottom: 6px;">Kirim Pesan atau Jadwalkan Konsultasi</h3>
                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;">
                    Silakan isi formulir di bawah ini. Tim Private Client Concierge kami akan merespons dalam waktu kurang dari 15 menit pada jam operasional.
                </p>
            </div>

            <form id="contact-form" onsubmit="handleContactSubmit(event)">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label class="form-label-field">Nama Lengkap *</label>
                    <input type="text" name="name" class="form-input-field" placeholder="Contoh: Bpk. Bambang Sutanto" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                    <div>
                        <label class="form-label-field">Nomor WhatsApp *</label>
                        <input type="tel" name="phone" class="form-input-field" placeholder="+62 812-xxxx-xxxx" required>
                    </div>
                    <div>
                        <label class="form-label-field">Email Aktif *</label>
                        <input type="email" name="email" class="form-input-field" placeholder="bambang@perusahaan.com" required>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label class="form-label-field">Topik Keperluan *</label>
                    <select name="subject" class="form-input-field" required>
                        <option>Konsultasi Pembelian Aset / Private Tour</option>
                        <option>Konsultasi Sewa Jangka Panjang / Korporat</option>
                        <option>Konsinyasi Titip Jual Properti</option>
                        <option>Permohonan Akses Portofolio Off-Market</option>
                        <option>Kemitraan Notaris & Rekanan Hukum</option>
                    </select>
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="form-label-field">Pesan atau Kriteria Unit yang Dicari *</label>
                    <textarea name="message" rows="4" class="form-input-field" placeholder="Jelaskan kebutuhan Anda, wilayah yang diminati, rentang anggaran, atau jadwal yang nyaman untuk kami hubungi..." required></textarea>
                </div>

                <div style="display: flex; align-items: center; gap: 8px; font-size: 0.78rem; color: #64748B; margin-bottom: 20px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span>Data Anda dijaga secara konfidensial dan tidak akan diteruskan ke pihak ketiga.</span>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; border-radius: 8px; padding: 13px; font-size: 0.95rem;" id="btn-submit-contact">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    <span>Kirim Pesan Konsultasi</span>
                </button>
            </form>
        </div>

    </div>

</div>
@endsection

@section('styles')
<style>
.about-metric-card {
    background: #FFFFFF;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 24px 20px;
    box-shadow: var(--shadow-sm);
}
.about-metric-number {
    display: block;
    font-family: var(--font-heading);
    font-size: 2.2rem;
    font-weight: 800;
    color: var(--primary-navy);
    line-height: 1.1;
    margin-bottom: 8px;
    letter-spacing: -0.02em;
}
.about-metric-title {
    display: block;
    font-size: 0.95rem;
    color: var(--primary-navy);
    margin-bottom: 6px;
}
.about-metric-desc {
    font-size: 0.8rem;
    color: var(--text-muted);
    line-height: 1.5;
}

.pilar-card {
    background: #FFFFFF;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 30px;
    box-shadow: var(--shadow-sm);
}
.pilar-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: #E6FBF5;
    color: var(--accent-teal);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
}

.keunggulan-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px;
    background: #F8FAFC;
    border-radius: 10px;
    border: 1px solid var(--border-light);
}
.keunggulan-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #E6FBF5;
    color: var(--accent-teal);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.office-box {
    background: #FFFFFF;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 20px;
    box-shadow: var(--shadow-sm);
}
.office-badge {
    background: #E2E8F0;
    color: var(--primary-navy);
    font-size: 0.7rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 4px;
    letter-spacing: 0.5px;
}

@media (max-width: 900px) {
    div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection

@section('scripts')
<script>
function handleContactSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-submit-contact');
    btn.disabled = true;
    btn.innerHTML = '<span>Mengirim pesan...</span>';

    setTimeout(() => {
        showToast('Pesan konsultasi Anda berhasil dikirim! Private Client Concierge kami akan menghubungi Anda segera (SLA < 15 menit).');
        document.getElementById('contact-form').reset();
        btn.disabled = false;
        btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg><span>Pesan Berhasil Terkirim ✓</span>`;
    }, 800);
}
</script>
@endsection
