<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false; // nonaktifkan penggunaan created_at dan updated_at

    protected $fillable = [
        'order_number',
        'customer_name',
        'product',
        'quantity',
        'price',
    ];
}
