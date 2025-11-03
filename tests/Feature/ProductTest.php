<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_products_returns_paginated_data()
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    ['id', 'name', 'price', 'stock', 'category']
                ],
                'meta',
                'links'
            ]);
    }

    public function test_create_product_successfully()
    {
        $payload = [
            'name' => 'Mouse Gamer',
            'price' => 99.90,
            'stock' => 10,
            'category' => 'Periféricos'
        ];

        $response = $this->postJson('/api/products', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Mouse Gamer']);

        $this->assertDatabaseHas('products', ['name' => 'Mouse Gamer']);
    }

    public function test_update_product()
    {
        $product = Product::factory()->create();

        $payload = ['name' => 'Novo Nome'];

        $response = $this->putJson("/api/products/{$product->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Novo Nome']);
    }

    public function test_delete_product_without_orders()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_delete_product_fails_if_has_orders()
    {
        $product = Product::factory()->create();
        $order = Order::factory()->create();

        $order->products()->attach($product->id, [
            'quantity' => 1,
            'price' => $product->price
        ]);

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(500)
            ->assertJsonFragment([
                'message' => 'Produto já está presente em um pedido.'
            ]);
    }
}
