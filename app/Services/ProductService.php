<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    public function __construct(private ProductRepository $repository) {}

    public function list($limit = 10)
    {
        return $this->repository->paginate($limit);
    }

    public function find($id)
    {
        $product = $this->getProductOrFail($id);
        return $product;
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        $product = $this->getProductOrFail($id);
        return $this->repository->update($product, $data);
    }

    public function delete($id)
    {

        $product = $this->getProductOrFail($id);
        if ($product->orders()->exists()) {
            throw new \Exception("Produto já está presente em um pedido.");
        }

        return $this->repository->delete($product);
    }

    private function getProductOrFail($id)
    {
        $product = $this->repository->find($id);
        if (!$product) {
            throw new \Exception("Produto não encontrado.");
        }
        return $product;
    }
}
