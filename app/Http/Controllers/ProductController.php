<?php

namespace App\Http\Controllers;

use App\Domain\Product\ProductService;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->getAvailableProducts();
        return response()->json($products, Response::HTTP_OK);
    }
}
