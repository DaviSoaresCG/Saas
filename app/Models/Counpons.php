<?php

namespace App\Models;

use App\CounponStatus;
use Illuminate\Database\Eloquent\Model;

class Counpons extends Model
{

    protected $fillable = [
        'code',
        'description',
        'active'
    ];

    protected $casts = [
        'active' => CounponStatus::class,
    ];
}
