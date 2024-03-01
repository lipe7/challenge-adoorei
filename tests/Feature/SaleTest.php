<?php

namespace Tests\Feature;

use App\Domain\Product\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SaleTest extends TestCase
{
    /** @test */
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
}
