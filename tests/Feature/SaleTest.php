<?php

namespace Tests\Feature;

use Database\Factories\ProductFactory;
use Database\Factories\SaleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    // /** @test */
    public function test_create_sale_with_invalid_data()
    {
        $response = $this->postJson('/api/sales', []);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors'
            ])
            ->assertJsonValidationErrors(['products']);
    }

    /** @test */
    public function it_returns_sales_json_structure()
    {
        $response = $this->get('/api/sales?order_by=sales.sale_id&order_by_type=desc&per_page=5');

        $response->assertJsonStructure([
            'current_page',
            'data',
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total'
        ]);
    }

    public function test_can_show_sale()
    {
        $sale = SaleFactory::new()->create();

        $url = '/api/sales/' . $sale->sale_id;
        $response = $this->get($url);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'sale_id',
            'amount',
            'products' => [
                '*' => [
                    'product_id',
                    'nome',
                    'price',
                    'amount'
                ]
            ]
        ]);
    }

    public function test_can_cancel_sale()
    {
        $sale = SaleFactory::new()->create();
        $url = '/api/sales/' . $sale->sale_id . '/cancel';

        $response = $this->delete($url);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('sales', ['sale_id' => $sale->sale_id]);
    }

    public function testAddProductToSale()
    {
        $sale = SaleFactory::new()->create();

        $product = ProductFactory::new()->create();

        $data = [
            'product_id' => $product->product_id,
            'amount' => 1,
        ];

        $url = '/api/sales/' . $sale->sale_id . '/product';
        $response = $this->post($url, $data);
        $response->assertStatus(201);

        $this->assertDatabaseHas('products_sales', [
            'sale_id' => $sale->sale_id,
            'product_id' => $product->product_id,
            'amount' => 1,
        ]);
    }
}
