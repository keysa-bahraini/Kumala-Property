<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            [
                'name' => 'DKI Jakarta - Jakarta Selatan',
                'city' => 'Jakarta Selatan',
                'area' => 'Kemang, Senopati, Pondok Indah',
                'is_active' => true,
            ],
            [
                'name' => 'Bali - Badung (Canggu, Uluwatu, Seminyak)',
                'city' => 'Bali',
                'area' => 'Uluwatu, Canggu, Badung',
                'is_active' => true,
            ],
            [
                'name' => 'Banten - Tangerang Selatan (BSD City)',
                'city' => 'Tangerang Selatan',
                'area' => 'BSD City, Alam Sutera, Bintaro',
                'is_active' => true,
            ],
            [
                'name' => 'DKI Jakarta - Jakarta Pusat (Menteng)',
                'city' => 'Jakarta Pusat',
                'area' => 'Menteng',
                'is_active' => true,
            ],
            [
                'name' => 'Jawa Timur - Surabaya Barat',
                'city' => 'Surabaya',
                'area' => 'CitraLand, Surabaya Barat',
                'is_active' => true,
            ],
            [
                'name' => 'Jawa Barat - Bandung Utara',
                'city' => 'Bandung',
                'area' => 'Dago Pakar, Bandung Utara',
                'is_active' => false,
            ],
        ];

        foreach ($regions as $reg) {
            Region::updateOrCreate(['name' => $reg['name']], $reg);
        }
    }
}
