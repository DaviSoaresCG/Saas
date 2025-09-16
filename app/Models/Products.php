<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\BelongsToTenant\BelongsToTenant;

class Products extends Model
{
    use BelongsToTenant;
    
    protected $fillable = ['name', 'description', 'value', 'tenant_id'];
}
