<?php

namespace App\Domain\Sale;

use Illuminate\Pagination\LengthAwarePaginator;

interface SaleRepository
{
    public function save(Sale $sale): Sale;
    public function listSales($filters): LengthAwarePaginator;
    public function showSale($sale_id): array;
    public function findOrFail(int $sale_id): ?Sale;
    public function cancelSale($sale): void;
}
