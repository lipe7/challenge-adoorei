<?php

namespace App\Domain\Sale;

use App\Domain\ProductSale\ProductSale;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'sale_id';
    protected $fillable = ['amount'];

    public function products()
    {
        return $this->hasMany(ProductSale::class, 'sale_id', 'sale_id');
    }
}
