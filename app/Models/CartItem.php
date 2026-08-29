<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    public function cart(){
        // cada item do carrinho pertence a um carrinho
        return $this->belongsTo(Cart::class);
    }

    public function product(){
        // cada item do carrinho pertence a um produto
        return $this->belongsTo(Product::class);
    }
}
