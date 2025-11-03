<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Enum\OrderStatus;

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
                'total_amount' => 0,
                'status' => OrderStatus::PENDING->value,
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

    public function update($id, array $data)
    {
        $order = $this->getOrderOrFail($id);

        if ($order->status !== OrderStatus::PENDING) {
            throw new \Exception("Somente pedidos pendentes podem ser editados.");
        }

        return DB::transaction(function () use ($order, $data) {
            $this->restockProducts($order);
            $order->products()->detach();
            $total = 0;
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
            return $order->fresh(['products']);
        });
    }

    public function cancel($id)
    {
        $order = $this->getOrderOrFail($id);

        if ($order->status === OrderStatus::CANCELLED) {
            throw new \Exception("Pedido já está cancelado.");
        }

        if ($order->status === OrderStatus::COMPLETED) {
            throw new \Exception("Não é possível cancelar um pedido já finalizado.");
        }

        $order->update(['status' => OrderStatus::CANCELLED->value]);
        $this->restockProducts($order);

        return $order;
    }

    public function complete($id)
    {
        $order = $this->getOrderOrFail($id);

        if ($order->status !== OrderStatus::PENDING) {
            throw new \Exception("Somente pedidos pendentes podem ser finalizados.");
        }

        $order->update(['status' => OrderStatus::COMPLETED->value]);
        return $order;
    }

    private function restockProducts(Order $order)
    {
        foreach ($order->products as $product) {
            $product->increment('stock', $product->pivot->quantity);
        }
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
