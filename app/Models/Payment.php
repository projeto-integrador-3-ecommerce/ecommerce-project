<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'status',
        'method',
    ];

    public function order()
    {
        // um pagamento pertence a um pedido
        return $this->belongsTo(Order::class);
    }
}
