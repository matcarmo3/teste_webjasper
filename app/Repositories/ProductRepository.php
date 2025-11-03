<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function paginate($limit = 10)
    {
        return Product::paginate($limit);
    }

    public function find($id)
    {
        return Product::find($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data)
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product)
    {
        return $product->delete();
    }
}
