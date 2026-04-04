<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductClick extends Model
{
    use BelongsToTenant;

    protected $fillable = ['user_id', 'product_id', 'clicks'];

    protected function casts(): array
    {
        return [
            'clicks' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    /**
     * Incrementa contagem quando o catálogo público exibe o detalhe do produto.
     */
    public static function recordProductView(Products $product): void
    {
        $row = static::firstOrCreate(
            ['product_id' => $product->id],
            ['clicks' => 0]
        );
        $row->increment('clicks');
    }
}
