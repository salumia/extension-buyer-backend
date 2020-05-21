<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_access_request extends Model
{
     protected $fillable = [
        'sender_id', 'product_id', 'status',
    ];
}
