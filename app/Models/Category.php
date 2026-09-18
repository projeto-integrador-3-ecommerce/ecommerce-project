<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

// dando permissão ao laravel para preencher os campos da tabela categories
#[Fillable([
    'name'
])]
#
class Category extends Model
{
    public function products(){
        // uma categoria tem muitos produtos
        return $this->hasMany(Product::class);
    }
}
