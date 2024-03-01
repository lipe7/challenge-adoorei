<?php

namespace App\Domain\Sale;

interface SaleRepository
{
    public function save(Sale $sale): Sale;
}
