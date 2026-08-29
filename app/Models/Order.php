<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function user(){
        // um pedido pertence a um usuário
        return $this->belongsTo(User::class);
    }

    public function orderItems(){
        // um pedido tem muitos itens
        return $this->hasMany(OrderItem::class);
    }

    public function payment(){
        // um pedido tem 1 pagamento
        return $this->hasOne(Payment::class);
    }
}
