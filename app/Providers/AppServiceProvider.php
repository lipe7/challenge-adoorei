<?php

namespace App\Providers;

use App\Domain\Product\EloquentProductRepository;
use App\Domain\Product\ProductRepository;
use App\Domain\Sale\EloquentSaleRepository;
use App\Domain\Sale\SaleRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(ProductRepository::class, EloquentProductRepository::class);
        $this->app->bind(SaleRepository::class, EloquentSaleRepository::class);
    }
}
