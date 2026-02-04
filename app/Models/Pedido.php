<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use BelongsToTenant;

    protected $fillable = ['user_id', 'total'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function iten_pedido()
    {
        return $this->hasMany(ItemPedido::class);
    }

    protected function total(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => str_replace(['.', ','], '.', '.'),

            get: fn ($value) => number_format($value, 2, ',', '.')
        );
    }
}
