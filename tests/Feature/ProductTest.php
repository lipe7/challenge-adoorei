<?php

namespace Tests\Feature;

use App\Domain\Product\Product;
use App\Domain\Product\ProductService;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_available_products()
    {
        ProductFactory::new()->create();

        $response = $this->get('/api/products');

        $response->assertStatus(200);
    }
}
