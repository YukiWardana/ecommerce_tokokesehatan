<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Get users
        $budi = User::where('email', 'budi@example.com')->first();
        $siti = User::where('email', 'siti@example.com')->first();
        $andi = User::where('email', 'andi@example.com')->first();
        $dewi = User::where('email', 'dewi@example.com')->first();

        // Get products
        $termometer = Product::where('name', 'Termometer Digital')->first();
        $tensimeter = Product::where('name', 'Tensimeter Digital')->first();
        $oximeter = Product::where('name', 'Pulse Oximeter')->first();
        $vitaminC = Product::where('name', 'Vitamin C 1000mg')->first();
        $masker = Product::where('name', 'Masker Medis 3 Ply')->first();
        $nebulizer = Product::where('name', 'Nebulizer Portable')->first();

        // Order 1: Budi (Jakarta) - COD from Jakarta shop
        $order1 = Order::create([
            'user_id' => $budi->id,
            'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'total_amount' => 625000,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'status' => 'processing',
            'shipping_address' => 'Jl. Gatot Subroto No. 99, Jakarta Selatan',
            'phone' => '+62 811-1234-5678',
            'created_at' => now()->subDays(2),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $termometer->id,
            'quantity' => 2,
            'price' => $termometer->price,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $oximeter->id,
            'quantity' => 1,
            'price' => $oximeter->price,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $tensimeter->id,
            'quantity' => 1,
            'price' => $tensimeter->price,
        ]);

        // Order 2: Siti (Jakarta) - Credit Card
        $order2 = Order::create([
            'user_id' => $siti->id,
            'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'total_amount' => 250000,
            'payment_method' => 'credit_card',
            'payment_status' => 'paid',
            'status' => 'shipped',
            'shipping_address' => 'Jl. Kuningan Raya No. 77, Jakarta',
            'phone' => '+62 812-9876-5432',
            'created_at' => now()->subDays(5),
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $oximeter->id,
            'quantity' => 1,
            'price' => $oximeter->price,
        ]);

        // Order 3: Andi (Bandung) - COD from Bandung shop
        $order3 = Order::create([
            'user_id' => $andi->id,
            'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'total_amount' => 250000,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'status' => 'pending',
            'shipping_address' => 'Jl. Dago No. 55, Bandung',
            'phone' => '+62 813-2468-1357',
            'created_at' => now()->subDays(1),
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $vitaminC->id,
            'quantity' => 2,
            'price' => $vitaminC->price,
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $masker->id,
            'quantity' => 2,
            'price' => $masker->price,
        ]);

        // Order 4: Dewi (Surabaya) - Digital Wallet
        $order4 = Order::create([
            'user_id' => $dewi->id,
            'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'total_amount' => 450000,
            'payment_method' => 'digital_wallet',
            'payment_status' => 'paid',
            'status' => 'delivered',
            'shipping_address' => 'Jl. Basuki Rahmat No. 111, Surabaya',
            'phone' => '+62 814-1357-2468',
            'created_at' => now()->subDays(10),
        ]);

        OrderItem::create([
            'order_id' => $order4->id,
            'product_id' => $nebulizer->id,
            'quantity' => 1,
            'price' => $nebulizer->price,
        ]);

        // Order 5: Budi (Jakarta) - Debit Card (completed)
        $order5 = Order::create([
            'user_id' => $budi->id,
            'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'total_amount' => 150000,
            'payment_method' => 'debit_card',
            'payment_status' => 'paid',
            'status' => 'delivered',
            'shipping_address' => 'Jl. Gatot Subroto No. 99, Jakarta Selatan',
            'phone' => '+62 811-1234-5678',
            'created_at' => now()->subDays(15),
        ]);

        OrderItem::create([
            'order_id' => $order5->id,
            'product_id' => $termometer->id,
            'quantity' => 2,
            'price' => $termometer->price,
        ]);
    }
}
