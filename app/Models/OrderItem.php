<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'order_id',
    'product_id',
    'quantity',
])]
class OrderItem extends Model
{
    public function order()
    {
        // um item pertence a um pedido
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        // um item pertence a um produto
        return $this->belongsTo(Product::class);
    }
}
