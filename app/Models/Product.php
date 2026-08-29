<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function category(){
        // um produto pertence a uma categoria
        return $this->belongsTo(Category::class);
    }

    public function cartItems(){
        // um produto pode estar em muitos itens do carrinho
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(){
        // um produto pode estar em muitos itens de pedido
        return $this->hasMany(OrderItem::class);
    }
}
