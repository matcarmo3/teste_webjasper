<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $products = Product::all();
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => 0,
            'status' => 'pending',
        ]);

        $totalAmount = 0;
        
        foreach ($products as $product) {
            $quantity = rand(1, 3);
            $price = $product->price;
            $order->products()->attach($product->id, [
                'quantity' => $quantity,
                'price' => $price,
            ]);
            $totalAmount += $price * $quantity;
        }

        $order->update(['total_amount' => $totalAmount]);
    }
}
