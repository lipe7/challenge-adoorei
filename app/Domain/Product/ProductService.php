<?php

namespace App\Domain\Product;

use App\Exceptions\AdooreiException;
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
        try {
            return $this->productRepository->allAvailable();
        } catch (AdooreiException $ex) {
            throw new AdooreiException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }
}
