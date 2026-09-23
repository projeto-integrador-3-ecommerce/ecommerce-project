<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model; // dando permissão ao laravel para preencher os campos da tabela products

#[Fillable([
    'category_id',
    'name',
    'description',
    'price',
    'stock',
    'color',
    'size',
    'material',
    'image',
])]

class Product extends Model
{
    public function category()
    {
        // um produto pertence a uma categoria
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        // um produto pode estar em muitos itens do carrinho
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        // um produto pode estar em muitos itens de pedido
        return $this->hasMany(OrderItem::class);
    }
}
