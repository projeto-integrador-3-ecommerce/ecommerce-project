<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

// dando permissão ao laravel para preencher os campos da tabela categories
#[Fillable([
    'name',
    'description',
    'image',
])]
//
class Category extends Model
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

    public function products()
    {
        // uma categoria tem muitos produtos
        return $this->hasMany(Product::class);
    }
}
