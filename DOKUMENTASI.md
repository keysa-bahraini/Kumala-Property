# Dokumentasi Proyek Kumala Property (Midnight Coastal Edition)

Selamat datang di dokumentasi resmi **Kumala Property Management System & Public Portal**. Platform ini adalah aplikasi web real estate mewah (*luxury real estate atelier*) yang dibangun menggunakan framework **Laravel 12** dengan tema desain khusus **Midnight Coastal**.

---

## 1. Ikhtisar Sistem & Visi Produk

**Kumala Property** dirancang untuk menghubungkan pemilik properti bernilai tinggi (*high-net-worth vendors*) dengan calon pembeli dan investor eksklusif. Platform ini mengutamakan kurasi arsitektural yang ketat, transparansi legalitas 100% bersertifikat BPN/Notaris, serta pengalaman antarmuka pengguna yang elegan, cepat, dan responsif.

### Pilar Utama Platform:
* **Digitalisasi Konsinyasi ("Titip Properti")**: Formulir pengajuan multi-tahap (5 langkah) dengan standar SLA peninjauan < 4 jam.
* **Eksplorasi Properti Presisi**: Pencarian multi-parameter berdasarkan jenis transaksi, tipe unit, lokasi strategis, dan rentang harga.
* **Concierge & Lead Management**: Fitur inquiry instan untuk pengajuan minat beli/sewa, penjadwalan survei lokasi privat, dan direct WhatsApp concierge.
* **Favorite Sync**: Penyimpanan koleksi unit favorit tanpa login menggunakan integrasi `localStorage` peramban.

---

## 2. Spesifikasi Desain & Palet Warna (Midnight Coastal)

Desain antarmuka Kumala Property mengadopsi estetika mewah bergaya atelier modern dengan token warna berikut:

| Token Warna | Nilai HEX | Penggunaan Utama |
| :--- | :--- | :--- |
| **Primary Navy** | `#0E2238` / `#162E4A` | Warna dominan navbar, footer, kartu dark, dan teks heading. |
| **Accent Teal / Emerald** | `#00B488` / `#0D9488` | Tombol CTA utama, status aktif, WhatsApp connect, dan highlight ikon. |
| **Light Coastal** | `#F4F6F9` / `#EBF1F5` | Latar belakang halaman (*body background*) dan kontainer kartu sekunder. |
| **Text Primary Dark** | `#0E2238` / `#1E293B` | Warna teks judul dan paragraf utama. |
| **Border & Divider** | `#E2E8F0` / `#CBD5E1` | Garis pemisah halus, border formulir, dan kontur kartu. |

* **Tipografi**:
  * *Headings*: **Outfit** & **Plus Jakarta Sans** (Google Fonts)
  * *Body & UI Controls*: **Inter** (Google Fonts)

---

## 3. Rincian Implementasi Halaman Frontend Publik

Saat ini, telah selesai diimplementasikan 4 halaman utama sisi pengguna (*Public Portal*) yang dapat diakses langsung:

### 3.1 Halaman 1: Landing Page / Beranda Utama (`/`)
* **File View**: `resources/views/pages/home.blade.php`
* **Fitur Utama**:
  * **Hero Banner Eksklusif**: Latar belakang gradien *Midnight Coastal* dengan radial aura dan trust badges (100% Notaris, 15% lolos kurasi, Rp 2.4T+ transaksi, SLA < 4 jam).
  * **Quick Search Bar**: Formulir pencarian cepat 4 parameter di hero section yang mengarahkan pengguna ke halaman eksplorasi.
  * **Koleksi Unggulan Pilihan Kurator**: Grid kartu properti beresolusi tinggi dengan indikator legalitas, status sewa/jual, spesifikasi ruang, harga, dan tombol simpan favorit.
  * **Banner Callout Konsinyasi**: Ajakan penitipan properti terintegrasi tautan langsung ke WhatsApp dan form konsinyasi.

### 3.2 Halaman 2: Eksplorasi Properti / Katalog & Filter (`/explore`)
* **File View**: `resources/views/pages/explore.blade.php`
* **Fitur Utama**:
  * **Multi-Parameter Filter Box**: Filter dinamis meliputi Jenis Transaksi (Semua, Jual, Sewa), Kategori Properti (Rumah Mewah, Villa, Penthouse, Komersial), Wilayah Strategis, dan Rentang Harga.
  * **Status Katalog Dinamis**: Menampilkan jumlah properti yang cocok dengan filter yang diterapkan secara real-time.
  * **Filter Active Chips & Sorting Toolbar**: Tag filter yang aktif dapat dihapus per item atau reset sekaligus, disertai dropdown pengurutan harga/rekomendasi dan switcher view.
  * **Card Konsultasi Off-Market**: Banner pemesanan konsultasi privat via WhatsApp untuk unit-unit yang tidak dipublikasikan ke umum.

### 3.3 Halaman 3: Detail Properti (`/properti/{slug}`)
* **File View**: `resources/views/pages/detail.blade.php`
* **Unit Referensi**: *Villa Cliffside Horizon Uluwatu* (`#KP-8821`)
* **Fitur Utama**:
  * **Interactive Media Gallery Grid**: Tampilan 5 galeri foto resolusi tinggi dengan overlay tombol jelajah 18 foto & video tour.
  * **Key Specs Bar**: 5 metrik utama (Kamar Tidur ensuite, Kamar Mandi, Luas Tanah 850m², Luas Bangunan 620m², Status Furnishing).
  * **Download Center**: Tombol unduh gratis *E-Brosur & Factsheet Properti (PDF)* lengkap dengan denah dan rekam legalitas.
  * **Spesifikasi Arsitektural & Teknis**: Tabel rincian arah hadap, daya listrik + genset, sumber air, garasi, dan tahun pembangunan.
  * **Fasilitas Unggulan & Peta Aksesibilitas**: Icon grid fasilitas dan kartu jarak tempuh estimasi ke destinasi krusial (Pantai Suluban, Savaya, Bandara).
  * **Sticky Concierge Inquiry Form (Sidebar)**: Formulir pemesanan survei lokasi privat dengan opsi tab *Minat Beli* vs *Minat Sewa*, skema pembayaran, dan direct WhatsApp concierge.
  * **Rekomendasi Properti Serupa**: Menampilkan listing alternatif di wilayah sekitar.

### 3.4 Halaman 4: Titip Properti / Konsinyasi Resmi (`/titip-properti`)
* **File View**: `resources/views/pages/titip-properti.blade.php`
* **Fitur Utama**:
  * **Multi-Step Form Wizard (5 Tahap)**:
    1. *Step 01 - Data Pemilik Properti*: Nama pemilik/kuasa hukum, nomor WhatsApp (+62), email aktif, dan status kepemilikan.
    2. *Step 02 - Detail & Status Properti*: Jenis transaksi, kategori aset, judul properti, wilayah operasional, alamat lengkap, dan nilai penawaran dengan generator teks terbilang rupiah dinamis (*"Terbilang: Rp 15 Miliar"*).
    3. *Step 03 - Spesifikasi Teknis*: Luas tanah, luas bangunan, jumlah KT/KM, dan checklist kelengkapan dokumen/fasilitas.
    4. *Step 04 - Deskripsi & Nilai Tambah*: Textarea narasi keunikan arsitektur dengan live counter kata (*0 / 800 kata*).
    5. *Step 05 - Dokumentasi Foto & Denah*: Drag & drop uploader area dengan pratinjau berkas terpilih dan penanda *FOTO UTAMA*.
  * **Pernyataan Legalitas & Komisi**: Checkbox pernyataan sah tanpa sengketa dan transparansi komisi profesional 2–3%.
  * **Sidebar Panduan**: 4 alur kerja konsinyasi dan standar kurasi Kumala Property.

---

## 4. Struktur Direktori Proyek

```text
c:\xampp\htdocs\Kumala Property/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Controller.php
│   │       └── PropertyController.php       # Controller utama rute publik
│   ├── Models/
│   │   └── User.php
│   └── Services/
│       └── PropertyDataService.php          # Layanan penyedia data katalog properti
├── bootstrap/
├── config/
├── database/
├── image/                                   # Folder aset mockup desain (13 screenshot)
├── public/
│   ├── css/
│   │   └── style.css                        # Master stylesheet sistem Midnight Coastal
│   ├── index.php
│   └── robots.txt
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php                # Master template layout (Navbar & Footer)
│   │   └── pages/
│   │       ├── home.blade.php               # Halaman 1: Beranda
│   │       ├── explore.blade.php            # Halaman 2: Eksplorasi
│   │       ├── detail.blade.php             # Halaman 3: Detail Properti
│   │       └── titip-properti.blade.php     # Halaman 4: Titip Properti
├── routes/
│   └── web.php                              # Konfigurasi rute web publik
├── storage/
├── tests/
│   └── Feature/
│       └── ExampleTest.php                  # Pengujian otomatis HTTP status 200 OK
├── .git/info/exclude                         # Konfigurasi proteksi lokal (.env & .gitignore)
├── DOKUMENTASI.md                           # Dokumentasi teknis bahasa Indonesia
├── prd.md                                   # Product Requirement Document (PRD)
└── README.md
```

---

## 5. Panduan Instalasi & Menjalankan Aplikasi

### Kebutuhan Sistem:
* **PHP**: Versi >= 8.2 (ekstensi pdo, mbstring, curl, openssl aktif)
* **Composer**: Versi >= 2.0
* **Node.js & NPM**: Versi >= 18.0

### Langkah Menjalankan:
1. Pastikan dependensi PHP dan Node telah terpasang:
   ```bash
   composer install
   npm install
   ```
2. Pastikan file `.env` telah memiliki application key:
   ```bash
   php artisan key:generate
   ```
3. Jalankan server lokal Laravel:
   ```bash
   php artisan serve
   ```
4. Buka peramban pada alamat:
   * **Beranda**: `http://127.0.0.1:8000/`
   * **Explore**: `http://127.0.0.1:8000/explore`
   * **Detail Properti**: `http://127.0.0.1:8000/properti/villa-cliffside-horizon-uluwatu`
   * **Titip Properti**: `http://127.0.0.1:8000/titip-properti`

---

## 6. Pengujian Otomatis (*Automated Testing*)

Proyek ini telah dilengkapi dengan unit dan feature tests untuk memvalidasi kelancaran setiap halaman:
```bash
php artisan test
```

**Hasil Pengujian:**
```text
PASS  Tests\Unit\ExampleTest
✓ that true is true

PASS  Tests\Feature\ExampleTest
✓ home page returns successful response (200 OK)
✓ explore page returns successful response (200 OK)
✓ detail page returns successful response (200 OK)
✓ titip properti page returns successful response (200 OK)

Tests:    5 passed (13 assertions)
Status:   ALL PASS
```

---

## 7. Kebijakan Keamanan Repositori Git

Untuk menjaga kerahasiaan kredensial dan konfigurasi sistem:
* File `.env` (berisi database password, app key, dan token privat) serta file `.gitignore` dilindungi secara lokal melalui file `.git/info/exclude`.
* Kedua file tersebut **tidak pernah diunggah (*never tracked / committed*) ke repositori GitHub**.
