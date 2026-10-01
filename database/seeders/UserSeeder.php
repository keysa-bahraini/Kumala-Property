<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Aditya Pratama',
                'email' => 'admin@kumalaproperty.com',
                'password' => Hash::make('@adminkumala'),
                'role' => 'super_admin',
                'avatar' => 'AP',
            ],
            [
                'name' => 'Farhan Kamil',
                'email' => 'farhan@kumalaproperty.com',
                'password' => Hash::make('@adminkumala'),
                'role' => 'curator',
                'avatar' => 'FK',
            ],
            [
                'name' => 'Gusti Ayu',
                'email' => 'gusti@kumalaproperty.com',
                'password' => Hash::make('@adminkumala'),
                'role' => 'sales_advisor',
                'avatar' => 'GA',
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                $u
            );
        }
    }
}
