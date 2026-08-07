<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'nome',
        'description',
        'preco_base',
        'peso',
        'user_id',
        'foto_url',
        'status',
        'path',
        'erp_id',
        'sku',
        'estoque',
        'slug'
    ];

    protected static function booted()
    {
        static::saving(function ($product) {
            $user = app()->bound(User::class) ? app(User::class) : auth()->user();
            if ($user && $user->tipo_cliente === 'erp' && $product->exists) {
                // If it is an API request (started by ERP sync), allow modifications.
                // Otherwise, prevent modifications to critical fields (nome, preco_base, estoque).
                if (request()->is('api/*') || request()->is('webhook/*')) {
                    return;
                }

                if ($product->isDirty(['nome', 'preco_base', 'estoque'])) {
                    abort(403, "Clientes integrados ao ERP não podem alterar preço, estoque ou nome dos produtos pelo painel.");
                }
            }
        });
    }

    public function productClicks()
    {
        return $this->hasMany(ProductClick::class, 'product_id');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id')->orderBy('ordem');
    }

    public function atributos()
    {
        return $this->belongsToMany(Atributo::class, 'atributo_product', 'product_id', 'atributo_id');
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_product', 'product_id', 'grupo_id');
    }

    /**
     * Format preco_base as money.
     */
    protected function precoBase(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => is_numeric($value) ? $value : str_replace(['.', ','], ['', '.'], $value),
            get: fn ($value) => number_format($value, 2, ',', '.')
        );
    }

    /**
     * Backward compatibility mapping: name -> nome.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['nome'] ?? null,
            set: fn ($value) => ['nome' => $value]
        );
    }

    /**
     * Backward compatibility mapping: path -> foto_url.
     */
    protected function path(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['foto_url'] ?? null,
            set: fn ($value) => ['foto_url' => $value]
        );
    }

    /**
     * Backward compatibility mapping: value -> preco_base (formatted).
     */
    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->preco_base,
            set: fn ($value) => ['preco_base' => is_numeric($value) ? $value : str_replace(['.', ','], ['', '.'], $value)]
        );
    }

    /**
     * Get the discounted price of the product if a catalog session is active.
     */
    public function getValorComDescontoAttribute()
    {
        $desconto = session('desconto_index');
        if ($desconto > 0) {
            $base = (float) str_replace(['.', ','], ['', '.'], $this->value);
            return number_format($base * (1 - $desconto / 100), 2, ',', '.');
        }
        return null;
    }
}

