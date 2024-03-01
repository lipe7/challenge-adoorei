<?php

namespace App\Domain\Product;

use App\Domain\Sale\Sale;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'name',
        'price',
        'description',
        'available'
    ];

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'products_sales', 'product_id', 'sale_id')
                    ->withPivot('product_sale_id');
    }
}
