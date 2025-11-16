<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class MedicalShopSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@medicalshop.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '1234567890',
        ]);

        // Create sample customer
        User::create([
            'name' => 'John Doe',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '0987654321',
            'address' => '123 Main St, City',
        ]);

        // Create categories
        $categories = [
            ['name' => 'Diagnostic Equipment', 'description' => 'Medical diagnostic tools and equipment'],
            ['name' => 'Surgical Instruments', 'description' => 'Professional surgical tools'],
            ['name' => 'Patient Monitoring', 'description' => 'Patient monitoring devices'],
            ['name' => 'First Aid', 'description' => 'First aid supplies and kits'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create sample products (prices in Rupiah)
        $products = [
            ['category_id' => 1, 'name' => 'Digital Thermometer', 'description' => 'Accurate digital thermometer', 'price' => 150000, 'stock' => 50],
            ['category_id' => 1, 'name' => 'Blood Pressure Monitor', 'description' => 'Automatic BP monitor', 'price' => 450000, 'stock' => 30],
            ['category_id' => 2, 'name' => 'Surgical Scissors', 'description' => 'Stainless steel surgical scissors', 'price' => 250000, 'stock' => 20],
            ['category_id' => 3, 'name' => 'Pulse Oximeter', 'description' => 'Fingertip pulse oximeter', 'price' => 350000, 'stock' => 40],
            ['category_id' => 4, 'name' => 'First Aid Kit', 'description' => 'Complete first aid kit', 'price' => 300000, 'stock' => 60],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
