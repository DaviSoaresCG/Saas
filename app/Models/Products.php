<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Products extends Model
{
    use BelongsToTenant;
    
    protected $fillable = ['name', 'description', 'value', 'user_id', 'path'];
}
