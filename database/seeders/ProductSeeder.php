<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Celular Sansung',
                'price' => 2999.10,
                'stock' => 50,
                'category' => 'Eletrônicos'
            ],
            [
                'name' => 'Celular Motorola',
                'price' => 3599.84,
                'stock' => 25,
                'category' => 'Eletrônicos'
            ],
            [
                'name' => 'Camiseta',
                'price' => 29.41,
                'stock' => 100,
                'category' => 'Vestuário'
            ],
            [
                'name' => 'Tênis Nike',
                'price' => 299.33,
                'stock' => 30,
                'category' => 'Vestuário'
            ],
            [
                'name' => 'Celular Apple',
                'price' => 4236.18,
                'stock' => 20,
                'category' => 'Eletrônicos'
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
