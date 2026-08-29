<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public function user(){
        // um endereço pertence a um usuário
        return $this->belongsTo(User::class);
    }
}
