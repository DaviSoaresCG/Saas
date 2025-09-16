<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;

class Tenant extends Model
{
    use Billable;

    protected $fillable = ['name', 'slug', 'stripe_customer_id', 'whatsapp'];
}
