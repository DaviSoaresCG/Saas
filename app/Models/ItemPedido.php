<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ItemPedido extends Model
{
    protected $fillable = ['pedido_id', 'product_id', 'value', 'quantidade'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function product()
    {
        return $this->belongsTo(Products::class);
    }

    protected function value(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => str_replace(['.', ','], '.', '.'),

            get: fn ($value) => number_format($value, 2, ',', '.')
        );
    }
}
