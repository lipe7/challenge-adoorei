<?php

namespace App\Domain\Sale;

use Illuminate\Pagination\LengthAwarePaginator;

class EloquentSaleRepository implements SaleRepository
{
    public function save(Sale $sale): Sale
    {
        $sale->save();
        return $sale;
    }

    public function listSales($filters): LengthAwarePaginator
    {
        $fields = '*';
        $perPage = $filters['per_page'] ?? 10;
        $orderBy = $filters['order_by'] ?? 'sales.sale_id';
        $orderByType = $filters['order_by_type'] ?? 'asc';

        $listQuery = Sale::with('products')
            ->select([
                'sales.sale_id',
            ])
            ->selectSub(function ($query) {
                $query->selectRaw('SUM(products.price * products_sales.amount)')
                    ->from('products_sales')
                    ->join('products', 'products_sales.product_id', '=', 'products.product_id')
                    ->whereColumn('products_sales.sale_id', 'sales.sale_id');
            }, 'product_total_price')
            ->groupBy('sales.sale_id');

        $sales = $listQuery->orderby($orderBy, $orderByType)->orderby($orderBy, $orderByType)->groupBy('sales.sale_id')->paginate($perPage, [$fields]);

        $sales->getCollection()->transform(function ($sale) {
            $formattedProducts = $sale->products->map(function ($product) {
                return [
                    'product_id' => $product->product_id,
                    'nome' => $product->name,
                    'price' => $product->price,
                    'amount' => $product->pivot->amount,
                ];
            });

            $formattedSale = [
                'sales_id' => $sale->sale_id,
                'amount' => $sale->product_total_price,
                'products' => $formattedProducts,
            ];

            return $formattedSale;
        });

        return $sales;
    }

    public function showSale($sale_id): array
    {
        $sale = Sale::with('products')
            ->select([
                'sales.sale_id',
            ])
            ->selectSub(function ($query) {
                $query->selectRaw('SUM(products.price * products_sales.amount)')
                    ->from('products_sales')
                    ->join('products', 'products_sales.product_id', '=', 'products.product_id')
                    ->whereColumn('products_sales.sale_id', 'sales.sale_id');
            }, 'product_total_price')
            ->where('sales.sale_id', $sale_id)
            ->groupBy('sales.sale_id')
            ->findOrFail($sale_id);

        $formattedProducts = $sale->products->map(function ($product) {
            return [
                'product_id' => $product->product_id,
                'nome' => $product->name,
                'price' => $product->price,
                'amount' => $product->pivot->amount,
            ];
        });

        $formattedSale = [
            'sale_id' => $sale->sale_id,
            'amount' => $sale->product_total_price,
            'products' => $formattedProducts,
        ];

        return $formattedSale;
    }

    public function findOrFail(int $sale_id): ?Sale
    {
        return Sale::findOrFail($sale_id);
    }

    public function cancelSale($sale): void
    {
        $sale->products()->detach();
        $sale->delete();
    }

    public function productExistsInSale(int $sale_id, int $product_id): bool
    {
        $sale = Sale::findOrFail($sale_id);
        $products = $sale->products;

        return $products->contains('product_id', $product_id);
    }

    public function addProductToSale(int $sale_id, int $product_id, int $amount): void
    {
        $sale = Sale::findOrFail($sale_id);
        $sale->products()->attach($product_id, ['amount' => $amount]);
    }

    public function updateProductAmount(int $sale_id, int $product_id, int $amount): void
    {
        $sale = Sale::findOrFail($sale_id);
        $sale->products()->updateExistingPivot($product_id, ['amount' => $amount]);
    }
}
