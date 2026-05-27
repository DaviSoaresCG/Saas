<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'path', 'ordem'];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
