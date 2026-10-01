<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Rumah Tapak Mewah',
                'slug' => 'rumah-tapak-mewah',
                'subtypes' => 'Heritage, Colonial, Modern Minimalis',
                'is_active' => true,
            ],
            [
                'name' => 'Villa Mewah & Tropis',
                'slug' => 'villa-mewah-tropis',
                'subtypes' => 'Cliffside, Beachfront, Ricefield View',
                'is_active' => true,
            ],
            [
                'name' => 'Ruko & Komersial',
                'slug' => 'ruko-komersial',
                'subtypes' => 'Shophouse, Butik, Kantor Representatif',
                'is_active' => true,
            ],
            [
                'name' => 'Apartemen & Penthouse',
                'slug' => 'apartemen-penthouse',
                'subtypes' => 'Sky Villa, Penthouse SCBD, High-rise',
                'is_active' => true,
            ],
            [
                'name' => 'Tanah & Kavling',
                'slug' => 'tanah-kavling',
                'subtypes' => 'Plot Residensial, Komersial, Lahan Terbuka',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
