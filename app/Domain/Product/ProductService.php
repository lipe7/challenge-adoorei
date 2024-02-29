<?php

namespace App\Domain\Product;

use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAvailableProducts(): Collection
    {
        return $this->productRepository->allAvailable();
    }
}
