<?php

namespace App\Domain\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class EloquentProductRepository implements ProductRepository
{
    public function allAvailable(): Collection
    {
        return Product::where('available', true)
            ->select('name', 'price', 'description')
            ->get();
    }
}
