<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'user_id',
    'cep',
    'street',
    'neighborhood',
    'city',
    'state',
    'country',
    'number',
    'complement',
])]
class Address extends Model
{
    // um endereço pertence a um usuário
    public function user()
    {
        // um endereço pertence a um usuário
        return $this->belongsTo(User::class);
    }

    // varios pedidos podem ter o mesmo endereço
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
