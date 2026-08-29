<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function products(){
        // uma categoria tem muitos produtos
        return $this->hasMany(Product::class);
    }
}
