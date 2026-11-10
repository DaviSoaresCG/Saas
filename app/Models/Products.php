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

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

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
     * Returns the next zero-based order index for a new product image.
     */
    public function nextImageOrder(): int
    {
        $maxOrdem = $this->productImages()->max('ordem');

        return is_null($maxOrdem) ? 0 : ((int) $maxOrdem + 1);
    }

    /**
     * Synchronizes products.path (foto_url) with the first ordered gallery image.
     */
    public function syncCover(): void
    {
        $first = $this->productImages()->orderBy('ordem')->first();
        $this->update(['path' => $first?->path]);
    }

    /**
     * Parse a monetary value (e.g. "1.234,56" or "1234.56") into a float, or null if invalid.
     */
    public static function parseMonetaryValue(mixed $value): ?float
    {
        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        if (!is_string($value)) {
            return null;
        }

        $trimmed = trim($value);
        if ($trimmed === '') {
            return null;
        }

        // Formato BR com vírgula decimal (ex: "1.234,56" ou "1234,56")
        if (str_contains($trimmed, ',')) {
            if (!preg_match('/^(?:\d{1,3}(?:\.\d{3})+|\d+),\d{1,2}$/', $trimmed)) {
                return null;
            }
            $normalized = str_replace(['.', ','], ['', '.'], $trimmed);
            return (float) $normalized;
        }

        // Formato numérico padrão (ex: "1234.56" ou "1234")
        if (preg_match('/^\d+(?:\.\d{1,2})?$/', $trimmed)) {
            return (float) $trimmed;
        }

        return null;
    }

    /**
     * Format preco_base as money.
     */
    protected function precoBase(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => self::parseMonetaryValue($value) ?? (is_numeric($value) ? $value : str_replace(['.', ','], ['', '.'], $value)),
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
     * Backward compatibility mapping: path -> foto_url (with fallback).
     */
    protected function path(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $foto = $attributes['foto_url'] ?? null;
                if (!empty($foto)) {
                    return $foto;
                }
                return asset('img/noimage-original.png');
            },
            set: fn ($value) => ['foto_url' => $value]
        );
    }

    public function peso(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => is_numeric($value) ? $value : (empty($value) ? null : str_replace(['.', ','], ['', '.'], $value)),
            get: fn($value) => (float)$value > 0 ? number_format((float)$value, 2, ',', '.') . 'g' : 'Sem Peso',
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

