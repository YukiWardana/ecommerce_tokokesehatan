<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Alat Diagnostik',
                'description' => 'Peralatan untuk diagnosis medis seperti termometer, tensimeter, dan alat cek kesehatan lainnya'
            ],
            [
                'name' => 'Alat Bedah',
                'description' => 'Instrumen bedah profesional untuk prosedur medis'
            ],
            [
                'name' => 'Monitoring Pasien',
                'description' => 'Perangkat untuk memantau kondisi vital pasien'
            ],
            [
                'name' => 'Pertolongan Pertama',
                'description' => 'Perlengkapan P3K dan pertolongan pertama'
            ],
            [
                'name' => 'Obat-obatan',
                'description' => 'Obat-obatan umum dan vitamin'
            ],
            [
                'name' => 'Alat Kesehatan Rumah',
                'description' => 'Peralatan kesehatan untuk penggunaan di rumah'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
