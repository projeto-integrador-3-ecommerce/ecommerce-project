<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public function user(){
        // 1 carrinho pertence a 1 usuário
        return $this->belongsTo(User::class);
    }

    public function cartItems(){
        // cada carrinho tem muitos itens
        return $this->hasMany(CartItem::class);
    }
}
