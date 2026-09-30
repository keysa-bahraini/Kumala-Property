<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test Page 1: Landing Page (Beranda)
     */
    public function test_home_page_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('KUMALA PROPERTY');
        $response->assertSee('Temukan Hunian Eksklusif');
    }

    /**
     * Test Page 2: Explore Properti
     */
    public function test_explore_page_returns_successful_response(): void
    {
        $response = $this->get('/explore');
        $response->assertStatus(200);
        $response->assertSee('Eksplorasi Properti');
        $response->assertSee('Menampilkan');
    }

    /**
     * Test Page 3: Detail Properti
     */
    public function test_detail_page_returns_successful_response(): void
    {
        $response = $this->get('/properti/villa-cliffside-horizon-uluwatu');
        $response->assertStatus(200);
        $response->assertSee('Villa Cliffside Horizon Uluwatu');
        $response->assertSee('Tertarik dengan Properti Ini?');
    }

    /**
     * Test Page 4: Titip Properti
     */
    public function test_titip_properti_page_returns_successful_response(): void
    {
        $response = $this->get('/titip-properti');
        $response->assertStatus(200);
        $response->assertSee('Titipkan Properti Anda');
        $response->assertSee('Data Pemilik Properti');
    }

    /**
     * Test Page 5: Properti Favorit Saya
     */
    public function test_favorites_page_returns_successful_response(): void
    {
        $response = $this->get('/favorit');
        $response->assertStatus(200);
        $response->assertSee('Properti Favorit Saya');
        $response->assertSee('Bandingkan Spek');
    }

    /**
     * Test Page 6: Tentang Kami & Kontak
     */
    public function test_about_page_returns_successful_response(): void
    {
        $response = $this->get('/tentang-kami');
        $response->assertStatus(200);
        $response->assertSee('Kurasi Presisi untuk Koleksi Aset Bernilai Abadi');
        $response->assertSee('Tiga Pilar Layanan Kumala');
    }

    /**
     * Test Page 7: Login Admin
     */
    public function test_admin_login_page_returns_successful_response(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('Masuk ke Portal Internal');
        $response->assertSee('admin@kumalaproperty.com');
    }

    /**
     * Test Page 8: Admin Dashboard
     */
    public function test_admin_dashboard_page_returns_successful_response(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('KUMALA ADMIN');
        $response->assertSee('Perlu Ditindaklanjuti Segera');
    }

    /**
     * Test Page 9: Manajemen Properti (Master)
     */
    public function test_admin_properties_page_returns_successful_response(): void
    {
        $response = $this->get('/admin/properti');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Properti');
        $response->assertSee('INVENTORY HUB');
    }

    /**
     * Test Page 10: Manajemen Listing Masuk (Gatekeeper)
     */
    public function test_admin_submissions_page_returns_successful_response(): void
    {
        $response = $this->get('/admin/listing-masuk');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Listing Masuk');
        $response->assertSee('SLA GATEKEEPER VERIFICATION');
    }

    /**
     * Test Page 11: Riwayat Terjual & Tersewa (Closing Archive)
     */
    public function test_admin_transactions_page_returns_successful_response(): void
    {
        $response = $this->get('/admin/riwayat-transaksi');
        $response->assertStatus(200);
        $response->assertSee('Riwayat Terjual & Tersewa');
        $response->assertSee('CLOSING ARCHIVE');
    }

    /**
     * Test Page 12: Manajemen Data Inquiry (CRM)
     */
    public function test_admin_inquiries_page_returns_successful_response(): void
    {
        $response = $this->get('/admin/inquiry');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Data Inquiry');
        $response->assertSee('LEAD PIPELINE', false);
    }

    /**
     * Test Page 13: Pengaturan Sistem & Master Data
     */
    public function test_admin_settings_page_returns_successful_response(): void
    {
        $response = $this->get('/admin/pengaturan');
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Sistem & Master Data');
        $response->assertSee('SYSTEM CONFIGURATION', false);
    }
}
