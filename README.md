# Kumala Property (Midnight Coastal Edition)

> **Luxury Real Estate Atelier & Integrated Property Management Platform**  
> Dibangun menggunakan **Laravel 12**, **PHP 8.2**, dan **Vanilla CSS** berstandar desain *Midnight Coastal*.

---

## 📌 Ringkasan Proyek

**Kumala Property** adalah platform properti mewah terpadu di Indonesia yang dirancang untuk menghubungkan pemilik properti bernilai tinggi (*HNWIs vendors*) dengan pembeli dan investor eksklusif. Platform ini mengutamakan kurasi arsitektural yang ketat, transparansi legalitas 100% bersertifikat BPN/Notaris, serta responsivitas tinggi (< 4 jam peninjauan konsinyasi dan < 15 menit respon inquiry).

---

## 🚀 Fitur Frontend Pengguna (Public Portal) yang Telah Tersedia

1. **[Beranda / Landing Page](resources/views/pages/home.blade.php)** (`/`):
   - Hero banner interaktif dengan radial glow dan 4 trust badges.
   - Quick Search Multi-Parameter (Transaksi, Kategori, Wilayah Strategis).
   - Koleksi unggulan pilihan kurator dengan integrasi simpan favorit (*localStorage*).
   - Callout konsinyasi resmi terintegrasi WhatsApp.

2. **[Eksplorasi Properti](resources/views/pages/explore.blade.php)** (`/explore`):
   - Kotak filter multi-parameter lengkap (Jenis Transaksi, Kategori Unit, Lokasi, Rentang Harga).
   - Indikator status katalog dinamis dan active filter chips.
   - Sorting dropdown & display switcher.
   - Card konsultasi properti *off-market*.

3. **[Detail Properti](resources/views/pages/detail.blade.php)** (`/properti/{slug}`):
   - Media gallery grid resolusi tinggi.
   - Key specs bar (KT, KM, LT, LB, status furnishing).
   - Spesifikasi arsitektural teknis & estimasi waktu akses landmark.
   - Sticky concierge inquiry form (tersambung ke CRM Leads Admin).

4. **[Titip Properti / Konsinyasi](resources/views/pages/titip-properti.blade.php)** (`/titip-properti`):
   - Wizard multi-tahap (Step 01 - 05): Data Pemilik, Detail Properti, Spek Teknis, Deskripsi, dan Drag & Drop Foto Uploader.
   - Pengiriman form otomatis masuk ke antrean verifikasi Admin (`/admin/listing-masuk`) dan seketika terbit di katalog publik (`/explore`).

5. **[Properti Favorit](resources/views/pages/favorites.blade.php)** (`/favorit`):
   - Grid kartu favorit dengan sinkronisasi *localStorage*.
   - Filter transaksi Jual/Sewa, modal komparasi multi-unit, dan hotline WhatsApp concierge.

6. **[Tentang Kami & Kontak](resources/views/pages/about.blade.php)** (`/tentang-kami`):
   - Profil kuratorial, 6 pilar keunggulan, kantor operasional Jakarta & Bali, serta form kontak langsung.

---

## 🛡️ Fitur Admin Suite (Internal Backoffice Portal)

Portal admin yang bersih, modern, dan **100% bebas emoji** (menggunakan SVG Icons arsitektural):

* **Halaman 7: Login Admin** (`/admin/login`): Kredensial default: `admin@kumalaproperty.id` / password: `admin` (atau `password123`).
* **Halaman 8: Dashboard Operasional** (`/admin/dashboard`): Matriks portofolio, Priority Action SLA alert dinamis, distribusi tipe aset, dan audit log.
* **Halaman 9: Master Inventory Properti** (`/admin/properti`): Filter status (ACTIVE, SOLD, RENTED, INACTIVE), pencarian keyword, penambahan unit modal, dan aksi delete aman.
* **Halaman 10: Gatekeeper Listing Masuk** (`/admin/listing-masuk`): Antrean pengajuan dari halaman Titip Properti dengan kartu inspeksi lengkap, checklist verifikasi, tombol WhatsApp pemilik, serta persetujuan kuratorial.
* **Halaman 11: Riwayat Closing & Transaksi** (`/admin/riwayat-transaksi`): Arsip transaksi jual beli dan sewa yang telah selesai tanpa label pihak ketiga.
* **Halaman 12: Lead Pipeline CRM** (`/admin/inquiry`): Manajemen calon pembeli/penyewa (Baru -> Follow-up -> Survei -> Closing) dan penjadwalan survei lokasi harian.
* **Halaman 13: Pengaturan Master Sistem** (`/admin/pengaturan`): Konfigurasi kontak hotline WhatsApp resmi, alamat kantor pusat & cabang, rate komisi, dan audit trail.

---

## 🗄️ Arsitektur Data (*KumalaDataHub*)

Platform ini menggunakan sistem data store lokal terpadu:
* **Engine**: [`app/Services/KumalaDataHub.php`](app/Services/KumalaDataHub.php)
* **Master Store**: [`storage/app/kumala_store.json`](storage/app/kumala_store.json)
* Menghubungkan secara instan semua aksi di frontend publik ke admin portal tanpa memerlukan migrasi database SQL eksternal.

---

## 💻 Panduan Menjalankan Proyek (Untuk Rekan Tim)

```bash
# 1. Pastikan dependensi telah terinstal
composer install
npm install

# 2. Salin .env dan generate Application Key (jika belum ada)
cp .env.example .env
php artisan key:generate

# 3. Jalankan server lokal
php artisan serve
```

Akses aplikasi pada peramban melalui:
* Public Portal: `http://127.0.0.1:8000`
* Admin Portal: `http://127.0.0.1:8000/admin` (Email: `admin@kumalaproperty.id`, Password: `admin`)

