<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;

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
        $data = $request->validated();
        $order = $this->service->updateStatus($id, $data['status']);
        return new OrderResource($order);
    }
}
