<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $service){}

    public function index()
    {
        return OrderResource::collection($this->service->list());
    }

    public function store(StoreOrderRequest $request)
    {
        $order = $this->service->create($request->validated());
        return new OrderResource($order);
    }

    public function show($id)
    {
        $order = $this->service->find($id);
        return new OrderResource($order);
    }

    public function update(UpdateOrderRequest $request, $id)
    {
        $order = $this->service->update($id, $request->validated());
        return new OrderResource($order);
    }

    public function cancel($id)
    {
        $order = $this->service->cancel($id);
        return response()->json([
            'success' => true,
            'message' => 'Pedido cancelado com sucesso.',
            'data' => new OrderResource($order)
        ]);
    }

    public function complete($id)
    {
        $order = $this->service->complete($id);
        return response()->json([
            'success' => true,
            'message' => 'Pedido finalizado com sucesso.',
            'data' => new OrderResource($order)
        ]);
    }
}
