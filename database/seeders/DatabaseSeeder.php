<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create test user
        User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create sample products
        $products = [
            [
                'name' => 'Laptop Pro 15"',
                'description' => 'High-performance laptop for professionals',
                'price' => 1299.99,
                'stock_quantity' => 25,
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Wireless Mouse',
                'description' => 'Ergonomic wireless mouse with precision tracking',
                'price' => 29.99,
                'stock_quantity' => 8,
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Mechanical Keyboard',
                'description' => 'RGB backlit mechanical keyboard',
                'price' => 89.99,
                'stock_quantity' => 50,
                'low_stock_threshold' => 15,
            ],
            [
                'name' => 'USB-C Hub',
                'description' => '7-in-1 USB-C multiport adapter',
                'price' => 49.99,
                'stock_quantity' => 5,
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Noise-Canceling Headphones',
                'description' => 'Premium wireless headphones with active noise cancellation',
                'price' => 249.99,
                'stock_quantity' => 30,
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Portable SSD 1TB',
                'description' => 'Ultra-fast portable solid state drive',
                'price' => 129.99,
                'stock_quantity' => 15,
                'low_stock_threshold' => 8,
            ],
            [
                'name' => '27" 4K Monitor',
                'description' => 'Professional grade 4K IPS display',
                'price' => 449.99,
                'stock_quantity' => 12,
                'low_stock_threshold' => 5,
            ],
            [
                'name' => 'Webcam HD',
                'description' => '1080p webcam with auto-focus',
                'price' => 69.99,
                'stock_quantity' => 20,
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Desk Lamp LED',
                'description' => 'Adjustable LED desk lamp with touch controls',
                'price' => 39.99,
                'stock_quantity' => 40,
                'low_stock_threshold' => 12,
            ],
            [
                'name' => 'Smartphone Stand',
                'description' => 'Adjustable aluminum phone holder',
                'price' => 19.99,
                'stock_quantity' => 7,
                'low_stock_threshold' => 10,
            ],
            [
                'name' => 'Gaming Chair',
                'description' => 'Ergonomic gaming chair with lumbar support',
                'price' => 299.99,
                'stock_quantity' => 3,
                'low_stock_threshold' => 5,
            ],
            [
                'name' => 'Cable Management Kit',
                'description' => 'Complete cable organization solution',
                'price' => 24.99,
                'stock_quantity' => 60,
                'low_stock_threshold' => 15,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}