<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements CanResetPassword, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'slug',
        'whatsapp',
        'password',
        'nome_loja',
        'store_name',
        'theme_name',
        'documento',
        'tipo_cliente',
        'plano_expira_em',
        'status',
        'api_token',
        'need_change_password',
        'modal_carrinho'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'plano_expira_em' => 'datetime',
            'need_change_password' => 'boolean',
            'modal_carrinho' => 'boolean',
        ];
    }

    /**
     * Verifies if the store is active based on its client type and billing.
     */
    public function isLojaAtiva(): bool
    {
        if ($this->status === 'suspended') {
            return false;
        }

        // Validação para clientes ERP
        if ($this->tipo_cliente === 'erp') {
            return $this->status === 'active';
        }

        // Validação para clientes Diretos (Pix)
        return $this->plano_expira_em && $this->plano_expira_em->isFuture();
    }

    /**
     * Backward compatibility mapping: store_name -> nome_loja.
     */
    protected function storeName(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['nome_loja'] ?? null,
            set: fn ($value) => ['nome_loja' => $value]
        );
    }

    /**
     * User's catalogs.
     */
    public function catalogos()
    {
        return $this->hasMany(Catalogo::class);
    }
}

