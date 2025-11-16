<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $shopJakarta = Shop::where('location', 'Jakarta')->first();
        $shopBandung = Shop::where('location', 'Bandung')->first();
        $shopSurabaya = Shop::where('location', 'Surabaya')->first();

        // Products from Jakarta Shop
        Product::create([
            'shop_id' => $shopJakarta->id,
            'category_id' => 1,
            'name' => 'Termometer Digital',
            'description' => 'Termometer digital akurat dengan layar LCD, hasil cepat dalam 10 detik',
            'price' => 75000,
            'stock' => 100,
            'image' => 'products/thermometer.jpg',
        ]);

        Product::create([
            'shop_id' => $shopJakarta->id,
            'category_id' => 1,
            'name' => 'Tensimeter Digital',
            'description' => 'Alat ukur tekanan darah otomatis dengan memori 60 pengukuran',
            'price' => 350000,
            'stock' => 50,
            'image' => 'products/tensimeter.jpg',
        ]);

        Product::create([
            'shop_id' => $shopJakarta->id,
            'category_id' => 3,
            'name' => 'Pulse Oximeter',
            'description' => 'Alat ukur saturasi oksigen dan detak jantung, cocok untuk monitoring COVID-19',
            'price' => 250000,
            'stock' => 75,
            'image' => 'products/oximeter.jpg',
        ]);

        Product::create([
            'shop_id' => $shopJakarta->id,
            'category_id' => 4,
            'name' => 'Kotak P3K Lengkap',
            'description' => 'Kotak P3K dengan isi lengkap untuk pertolongan pertama di rumah atau kantor',
            'price' => 150000,
            'stock' => 60,
            'image' => 'products/firstaid.jpg',
        ]);

        // Products from Bandung Shop
        Product::create([
            'shop_id' => $shopBandung->id,
            'category_id' => 5,
            'name' => 'Vitamin C 1000mg',
            'description' => 'Suplemen vitamin C untuk meningkatkan daya tahan tubuh, isi 30 tablet',
            'price' => 85000,
            'stock' => 200,
            'image' => 'products/vitamin-c.jpg',
        ]);

        Product::create([
            'shop_id' => $shopBandung->id,
            'category_id' => 5,
            'name' => 'Multivitamin Keluarga',
            'description' => 'Multivitamin lengkap untuk seluruh keluarga, isi 60 kapsul',
            'price' => 120000,
            'stock' => 150,
            'image' => 'products/multivitamin.jpg',
        ]);

        Product::create([
            'shop_id' => $shopBandung->id,
            'category_id' => 6,
            'name' => 'Nebulizer Portable',
            'description' => 'Alat terapi uap untuk gangguan pernapasan, portable dan mudah digunakan',
            'price' => 450000,
            'stock' => 30,
            'image' => 'products/nebulizer.jpg',
        ]);

        Product::create([
            'shop_id' => $shopBandung->id,
            'category_id' => 4,
            'name' => 'Masker Medis 3 Ply',
            'description' => 'Masker medis 3 lapis, isi 50 pcs per box',
            'price' => 45000,
            'stock' => 500,
            'image' => 'products/mask.jpg',
        ]);

        // Products from Surabaya Shop
        Product::create([
            'shop_id' => $shopSurabaya->id,
            'category_id' => 2,
            'name' => 'Gunting Bedah Stainless',
            'description' => 'Gunting bedah stainless steel berkualitas tinggi untuk prosedur medis',
            'price' => 180000,
            'stock' => 40,
            'image' => 'products/scissors.jpg',
        ]);

        Product::create([
            'shop_id' => $shopSurabaya->id,
            'category_id' => 2,
            'name' => 'Pinset Medis Set',
            'description' => 'Set pinset medis berbagai ukuran, stainless steel',
            'price' => 95000,
            'stock' => 80,
            'image' => 'products/forceps.jpg',
        ]);

        Product::create([
            'shop_id' => $shopSurabaya->id,
            'category_id' => 6,
            'name' => 'Timbangan Badan Digital',
            'description' => 'Timbangan digital akurat hingga 180kg dengan layar LCD besar',
            'price' => 200000,
            'stock' => 45,
            'image' => 'products/scale.jpg',
        ]);

        Product::create([
            'shop_id' => $shopSurabaya->id,
            'category_id' => 1,
            'name' => 'Stetoskop Profesional',
            'description' => 'Stetoskop profesional untuk dokter dan perawat, akustik jernih',
            'price' => 320000,
            'stock' => 35,
            'image' => 'products/stethoscope.jpg',
        ]);

        // Admin products (no shop_id)
        Product::create([
            'shop_id' => null,
            'category_id' => 4,
            'name' => 'Hand Sanitizer 500ml',
            'description' => 'Hand sanitizer dengan alkohol 70%, membunuh 99.9% kuman',
            'price' => 35000,
            'stock' => 300,
            'image' => 'products/sanitizer.jpg',
        ]);

        Product::create([
            'shop_id' => null,
            'category_id' => 6,
            'name' => 'Alat Cek Gula Darah',
            'description' => 'Alat cek gula darah digital dengan strip test, akurat dan cepat',
            'price' => 280000,
            'stock' => 55,
            'image' => 'products/glucose-meter.jpg',
        ]);
    }
}
