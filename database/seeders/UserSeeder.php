<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@medicalshop.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+62 812-3456-7890',
            'address' => 'Jl. Sudirman No. 1, Jakarta Pusat',
        ]);

        // Sellers
        User::create([
            'name' => 'Toko Medis Jakarta',
            'email' => 'seller.jakarta@example.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'phone' => '+62 821-1111-2222',
            'address' => 'Jl. Thamrin No. 45, Jakarta Pusat',
        ]);

        User::create([
            'name' => 'Apotek Sehat Bandung',
            'email' => 'seller.bandung@example.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'phone' => '+62 822-3333-4444',
            'address' => 'Jl. Asia Afrika No. 88, Bandung',
        ]);

        User::create([
            'name' => 'Medical Store Surabaya',
            'email' => 'seller.surabaya@example.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'phone' => '+62 823-5555-6666',
            'address' => 'Jl. Pemuda No. 123, Surabaya',
        ]);

        // Customers - Jakarta
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+62 811-1234-5678',
            'address' => 'Jl. Gatot Subroto No. 99, Jakarta Selatan',
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+62 812-9876-5432',
            'address' => 'Jl. Kuningan Raya No. 77, Jakarta',
        ]);

        // Customers - Bandung
        User::create([
            'name' => 'Andi Wijaya',
            'email' => 'andi@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+62 813-2468-1357',
            'address' => 'Jl. Dago No. 55, Bandung',
        ]);

        // Customers - Surabaya
        User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+62 814-1357-2468',
            'address' => 'Jl. Basuki Rahmat No. 111, Surabaya',
        ]);

        // Customer tanpa address lengkap
        User::create([
            'name' => 'Rudi Hartono',
            'email' => 'rudi@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+62 815-9999-8888',
            'address' => null,
        ]);
    }
}
