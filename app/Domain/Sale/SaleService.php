<?php

namespace App\Domain\Sale;

use App\Domain\Product\ProductRepository;
use App\Exceptions\AdooreiException;
use App\Http\Requests\AddProductToSaleRequest;
use App\Http\Requests\CreateSaleRequest;
use App\Http\Requests\ListRequest;
use Illuminate\Http\Response;

class SaleService
{
    protected $saleRepository;
    protected $productRepository;

    public function __construct(
        SaleRepository $saleRepository,
        ProductRepository $productRepository
    ){
        $this->saleRepository = $saleRepository;
        $this->productRepository = $productRepository;
    }

    public function createSale(CreateSaleRequest $request)
    {
        try {
            $products = $request['products'] ?? null;

            $sale = new Sale();
            $products = $request->input('products', []);
            $this->saleRepository->save($sale);

            if (!empty($products)) {
                $this->addProductsToSale($sale, $products);
            }

            return response()->json([
                'success' => 'true',
                'sale_id' => $sale->sale_id
            ], Response::HTTP_CREATED);
        } catch (AdooreiException $ex) {
            throw new AdooreiException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    protected function addProductsToSale(Sale $sale, array $products)
    {
        foreach ($products as $productData) {
            $productId = $productData['product_id'];
            $product = $this->productRepository->findOrFail($productId);
            if ($product) {
                $amount = $productData['amount'];

                $sale->products()->attach($productId, ['amount' => $amount]);
            }
        }
    }

    public function listSales(ListRequest $filters)
    {
        try {
            return $this->saleRepository->listSales($filters);
        } catch (AdooreiException $ex) {
            throw new AdooreiException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    public function showSale($sale_id)
    {
        try {
            return $this->saleRepository->showSale($sale_id);
        } catch (AdooreiException $ex) {
            throw new AdooreiException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    public function cancelSale($sale_id)
    {
        try {
            $sale = $this->saleRepository->findOrFail($sale_id);
            $this->saleRepository->cancelSale($sale);

            return response()->json([
                'success' => 'true',
                'sale_id' => 'sale canceled'
            ], Response::HTTP_OK);
        } catch (AdooreiException $ex) {
            throw new AdooreiException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    public function addProductToSale(AddProductToSaleRequest $request)
    {
        try {

            $productId = $request['product_id'] ?? null;
            $amount = $request['amount'] ?? null;
            $saleId = $request->route('sale_id') ?? null;

            $sale = $this->saleRepository->findOrFail($saleId);
            $existingProduct = $this->saleRepository->productExistsInSale($saleId, $productId);
            if ($existingProduct) {
                $this->saleRepository->updateProductAmount($saleId, $productId, $amount);
            } else {
                $this->saleRepository->addProductToSale($saleId, $productId, $amount);
            }

            return response()->json([
                'success' => 'true',
                'sale_id' => $sale->sale_id
            ], Response::HTTP_CREATED);
        } catch (AdooreiException $ex) {
            throw new AdooreiException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }
}
