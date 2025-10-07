<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
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
}
