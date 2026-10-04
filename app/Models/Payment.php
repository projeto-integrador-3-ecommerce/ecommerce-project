<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'status',
        'method',
        'preference_id',
        'checkout_url',
        'external_payment_id',
        'status_detail',
    ];

    public function order()
    {
        // um pagamento pertence a um pedido
        return $this->belongsTo(Order::class);
    }
}
