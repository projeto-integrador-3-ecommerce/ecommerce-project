<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'address_id',
    'status',
    'total',
])]
class Order extends Model
{
    public const STATUS_AWAITING_PAYMENT = 'to_pay';

    public const STATUS_PAYMENT_CONFIRMED = 'payment_confirmed';

    public const STATUS_SHIPPED = 'shipped';

    public const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';

    public const STATUS_DELIVERED = 'delivered';

    public const STATUS_CANCELED = 'canceled';

    public const STATUS_LABELS = [
        self::STATUS_AWAITING_PAYMENT => 'A pagar',
        self::STATUS_PAYMENT_CONFIRMED => 'Pagamento confirmado',
        self::STATUS_SHIPPED => 'Enviado',
        self::STATUS_OUT_FOR_DELIVERY => 'Em rota de entrega',
        self::STATUS_DELIVERED => 'Entregue',
        self::STATUS_CANCELED => 'Cancelado',
    ];

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? match ($this->status) {
            'pending' => self::STATUS_LABELS[self::STATUS_AWAITING_PAYMENT],
            'Realizado' => self::STATUS_LABELS[self::STATUS_PAYMENT_CONFIRMED],
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

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
