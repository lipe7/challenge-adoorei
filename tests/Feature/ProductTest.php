<?php

namespace Tests\Feature;

use App\Domain\Product\Product;
use App\Domain\Product\ProductService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_can_list_available_products()
    {
        $response = $this->get('/api/products');

        $mockRepository = Mockery::mock('App\Domain\Product\ProductRepository');
        $mockRepository->shouldReceive('allAvailable')->andReturn(
            new Collection([
                new Product(['name' => 'Celular 1', 'price' => '1800.00', 'description' => 'Lorenzo Ipsulum']),
                new Product(['name' => 'Celular 2', 'price' => '3200.00', 'description' => 'Lorem ipsum dolor']),
            ])
        );

        $productService = new ProductService($mockRepository);

        $products = $productService->getAvailableProducts();

        $this->assertCount(2, $products);
        $this->assertEquals('Celular 1', $products[0]->name);
        $this->assertEquals('1800.00', $products[0]->price);
        $this->assertEquals('Lorenzo Ipsulum', $products[0]->description);

        $response->assertStatus(200);
    }
}
