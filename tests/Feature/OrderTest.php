<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Enum\OrderStatus;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_create_order_and_decrement_stock()
    {
        $product1 = Product::factory()->create(['stock' => 5, 'price' => 10]);
        $product2 = Product::factory()->create(['stock' => 3, 'price' => 20]);

        $payload = [
            'products' => [
                ['id' => $product1->id, 'quantity' => 2],
                ['id' => $product2->id, 'quantity' => 1],
            ]
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/orders', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'total_amount' => "40.00",
                     'status' => 'pending',
                 ]);

        $this->assertDatabaseHas('orders', ['user_id' => $this->user->id, 'total_amount' => 40]);
        $this->assertDatabaseHas('order_product', ['product_id' => $product1->id, 'quantity' => 2]);
        $this->assertDatabaseHas('order_product', ['product_id' => $product2->id, 'quantity' => 1]);

        $this->assertEquals(3, $product1->fresh()->stock);
        $this->assertEquals(2, $product2->fresh()->stock);
    }

    public function test_cannot_create_order_with_insufficient_stock()
    {
        $product = Product::factory()->create(['stock' => 1, 'price' => 10]);

        $payload = [
            'products' => [
                ['id' => $product->id, 'quantity' => 5],
            ]
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/orders', $payload);

        $response->assertStatus(500)
                 ->assertJsonFragment([
                     'message' => "Estoque insuficiente para o produto {$product->name}"
                 ]);

        $this->assertEquals(1, $product->fresh()->stock);
    }

    public function test_user_can_update_own_order_status()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);

        $payload = ['status' => 'cancelled'];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->putJson("/api/orders/{$order->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'cancelled']);

        $this->assertEquals(OrderStatus::CANCELLED, $order->fresh()->status);
    }

    public function test_user_cannot_update_other_users_order()
    {
        $otherUser = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $otherUser->id]);

        $payload = ['status' => 'cancelled'];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->putJson("/api/orders/{$order->id}", $payload);

        $response->assertStatus(500)
                 ->assertJsonFragment(['message' => 'Você não pode acessar este pedido.']);
    }

    public function test_can_list_orders_with_pagination()
    {
        Order::factory()->count(15)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('/api/orders');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data',
                     'meta',
                     'links'
                 ]);

        $this->assertCount(10, $response->json('data'));
    }
}
