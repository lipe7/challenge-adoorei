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
}
