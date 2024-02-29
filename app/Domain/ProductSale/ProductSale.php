<?php

namespace App\Domain\ProductSale;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    use HasFactory;

    protected $table = 'products_sales';
    protected $primaryKey = 'product_sale_id';

    protected $fillable = [
        'sale_id',
        'product_id',
        'amount'
    ];
}
