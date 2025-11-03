<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    public function __construct(private ProductService $service) {}

    public function index()
    {
        return ProductResource::collection($this->service->list());
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $product = $this->service->create($data);
        return new ProductResource($product);
    }

    public function show($id)
    {
        $product = $this->service->find($id);
        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $data = $request->validated();
        $product = $this->service->update($id, $data);
        return new ProductResource($product);
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(null, 204);
    }
}
