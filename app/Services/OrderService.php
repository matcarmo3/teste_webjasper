<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(private OrderRepository $repository) {}

    public function list($limit = 10)
    {
        return $this->repository->paginate($limit);
    }

    public function find($id)
    {
        $order = $this->getOrderOrFail($id);
        return $order;
    }

    public function create(array $data)
    {
        $userId = auth()->id();

        return DB::transaction(function () use ($data, $userId) {
            $total = 0;
            $order = $this->repository->create([
                'user_id' => $userId,
                'total_amount' => 0, // Atualizamos depois
                'status' => 'pending'
            ]);

            foreach ($data['products'] as $item) {
                $product = Product::findOrFail($item['id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Estoque insuficiente para o produto {$product->name}");
                }

                $product->decrement('stock', $item['quantity']);

                $order->products()->attach($product->id, [
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);

                $total += $product->price * $item['quantity'];
            }

            $order->update(['total_amount' => $total]);

            return $order;
        });
    }

    public function updateStatus($id, string $status)
    {
        $order = $this->getOrderOrFail($id);
        $order->update(['status' => $status]);

        return $order;
    }

    private function getOrderOrFail($id)
    {
        $order = $this->repository->find($id);
        if (!$order) {
            throw new \Exception("Pedido não encontrado.");
        }
        if( $order->user_id !== auth()->id()) {
            throw new \Exception("Você não pode acessar este pedido.");
        }
        return $order;
    }

}
