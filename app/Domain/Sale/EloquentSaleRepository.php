<?php

namespace App\Domain\Sale;

class EloquentSaleRepository implements SaleRepository
{
    public function save(Sale $sale): Sale
    {
        $sale->save();
        return $sale;
    }
}
