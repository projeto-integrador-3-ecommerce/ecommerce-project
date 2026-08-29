<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public function order(){
        // um pagamento pertence a um pedido
        return $this->belongsTo(Order::class);
    }
}
