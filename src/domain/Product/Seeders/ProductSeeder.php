<?php

namespace Domain\Product\Seeders;

use Domain\Product\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::factory(500)->create();

        $customProducts = [
            [
                'title' => 'Apple iPhone 15',
                'slug' => 'apple-iphone-15',
                'stock' => 50,
                'price' => 1299.99,
            ],
            [
                'title' => 'Samsung Galaxy S23',
                'slug' => 'samsung-galaxy-s23',
                'stock' => 40,
                'price' => 999.99,
            ],
            [
                'title' => 'Sony WH-1000XM5 Headphones',
                'slug' => 'sony-wh-1000xm5-headphones',
                'stock' => 30,
                'price' => 399.99,
            ],
            [
                'title' => 'MacBook Pro 16-inch',
                'slug' => 'macbook-pro-16-inch',
                'stock' => 20,
                'price' => 2499.99,
            ],
            [
                'title' => 'Dell XPS 13 Laptop',
                'slug' => 'dell-xps-13-laptop',
                'stock' => 25,
                'price' => 1199.99,
            ],
        ];

        foreach ($customProducts as $product) {
            Product::create([
                'slug' => $product['slug'],
                'title' => $product['title'],
                'stock' => $product['stock'],
                'price' => $product['price'],
            ]);

        }
    }
}
