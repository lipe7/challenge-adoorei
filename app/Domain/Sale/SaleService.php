<?php

namespace App\Domain\Sale;

use App\Domain\Product\ProductRepository;
use App\Http\Requests\CreateSaleRequest;
use Exception;
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
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'an unexpected error occurred: ' . $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
}
