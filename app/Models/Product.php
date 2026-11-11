<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model; // dando permissão ao laravel para preencher os campos da tabela products
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'category_id',
    'name',
    'description',
    'price',
    'stock',
    'color',
    'size',
    'material',
    'image',
])]

class Product extends Model
{
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'supabase:')) {
            $projectUrl = config('services.supabase.url');
            $bucket = config('services.supabase.bucket');

            if (! $projectUrl || ! $bucket) {
                throw new \RuntimeException('Supabase Storage is not configured.');
            }

            $path = substr($this->image, strlen('supabase:'));
            $encodedPath = implode('/', array_map('rawurlencode', explode('/', $path)));

            return rtrim($projectUrl, '/').'/storage/v1/object/public/'.rawurlencode($bucket).'/'.$encodedPath;
        }

        return Storage::disk('public')->url($this->image);
    }

    public function category()
    {
        // um produto pertence a uma categoria
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        // um produto pode estar em muitos itens do carrinho
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        // um produto pode estar em muitos itens de pedido
        return $this->hasMany(OrderItem::class);
    }
}
