<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'user_id',
        'nome',
        'foto_path',
    ];

    public function products()
    {
        return $this->belongsToMany(Products::class, 'grupo_product', 'grupo_id', 'product_id');
    }

    /**
     * URL pública da foto do grupo se existir.
     */
    protected function fotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->foto_path ? asset('storage/' . $this->foto_path) : null
        );
    }
}
