<?php

namespace App\Http\Controllers;

use App\Domain\Sale\SaleService;
use App\Http\Requests\CreateSaleRequest;

class SaleController extends Controller
{
    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function createSale(CreateSaleRequest $request)
    {
        return $this->saleService->createSale($request);
    }
}
