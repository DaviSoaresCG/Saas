<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class Tenant extends Authenticatable
{
    use Billable, Notifiable;

    protected $fillable = ['name', 'slug', 'email', 'password' , 'whatsapp'];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
