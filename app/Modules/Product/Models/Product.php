<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        "cat_id","user_id","product_name","description","total_users","product_created_date","product_type","status","price","service_fee","visibilty",
        "is_sold","negotiate","currency","website"
    ];
}
