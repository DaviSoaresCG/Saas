<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Atributo extends Model
{
    use BelongsToTenant;

    protected $fillable = ['user_id', 'nome'];

    public function products()
    {
        return $this->belongsToMany(Products::class, 'atributo_product', 'atributo_id', 'product_id');
    }
}
