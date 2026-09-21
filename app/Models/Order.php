<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'user_id',
    'address_id',
    'status',
    'total',
])]
class Order extends Model
{
    public function user()
    {
        // um pedido pertence a um usuário
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        // um pedido tem muitos itens
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        // um pedido tem 1 pagamento
        return $this->hasOne(Payment::class);
    }

    // um pedido pertence a um endereço
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
