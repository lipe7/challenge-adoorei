<?php

namespace App\Http\Controllers;

use App\Domain\Sale\SaleService;
use App\Http\Requests\CreateSaleRequest;
use App\Http\Requests\ListRequest;

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

    public function list(ListRequest $request)
    {
        return $this->saleService->listSales($request);
    }

    public function show($sale_id)
    {
        return $this->saleService->showSale($sale_id);
    }

    public function cancel($sale_id)
    {
        return $this->saleService->cancelSale($sale_id);
    }
}
