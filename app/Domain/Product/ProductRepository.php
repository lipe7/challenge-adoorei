<?php

namespace App\Domain\Product;

use Illuminate\Database\Eloquent\Collection;

interface ProductRepository
{
    public function allAvailable(): Collection;
    public function findOrFail(int $id): ?Product;
}
