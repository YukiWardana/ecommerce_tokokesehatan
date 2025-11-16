<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shop;
use App\Models\User;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        // Get sellers
        $sellerJakarta = User::where('email', 'seller.jakarta@example.com')->first();
        $sellerBandung = User::where('email', 'seller.bandung@example.com')->first();
        $sellerSurabaya = User::where('email', 'seller.surabaya@example.com')->first();

        // Create shops
        Shop::create([
            'user_id' => $sellerJakarta->id,
            'shop_name' => 'Toko Medis Jakarta Pusat',
            'description' => 'Menyediakan berbagai alat kesehatan dan medis berkualitas di Jakarta',
            'location' => 'Jakarta',
            'phone' => '+62 821-1111-2222',
            'is_active' => true,
        ]);

        Shop::create([
            'user_id' => $sellerBandung->id,
            'shop_name' => 'Apotek Sehat Bandung',
            'description' => 'Apotek terpercaya dengan produk kesehatan lengkap di Bandung',
            'location' => 'Bandung',
            'phone' => '+62 822-3333-4444',
            'is_active' => true,
        ]);

        Shop::create([
            'user_id' => $sellerSurabaya->id,
            'shop_name' => 'Medical Store Surabaya',
            'description' => 'Toko alat medis profesional di Surabaya',
            'location' => 'Surabaya',
            'phone' => '+62 823-5555-6666',
            'is_active' => true,
        ]);
    }
}
