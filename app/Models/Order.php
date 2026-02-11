<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable =  [
        'customer_name',
        'phone_number',
        'total_amount',
        'status'
    ];
}
