<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    use HasFactory;

    protected $table = 'catalogos';

    protected $fillable = [
        'user_id',
        'nome',
        'hash',
        'desconto_index',
        'sob_consulta',
        'mostrar_nome',
    ];

    protected $casts = [
        'desconto_index' => 'decimal:2',
        'sob_consulta' => 'boolean',
        'mostrar_nome' => 'boolean',
    ];

    /**
     * Owner of the catalog.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
