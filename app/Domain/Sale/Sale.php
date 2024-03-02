<?php

namespace App\Domain\Sale;

use App\Domain\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';
    protected $primaryKey = 'sale_id';
    protected $fillable = [];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_sales', 'sale_id', 'product_id')
                    ->withPivot('product_sale_id', 'amount');
    }
}
