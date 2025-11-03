<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    public function paginate($limit = 10)
    {
        return Order::with('products')->paginate($limit);
    }

    public function find($id)
    {
        return Order::with('products')->find($id);
    }

    public function create(array $data)
    {
        return Order::create($data);
    }

    public function update(Order $order, array $data)
    {
        $order->update($data);
        return $order;
    }

    public function delete(Order $order)
    {
        return $order->delete(); // Não será usado diretamente, só alteraremos status
    }
}
