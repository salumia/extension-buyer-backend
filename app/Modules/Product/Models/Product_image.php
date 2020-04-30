<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;

class Product_image extends Model
{
    
      protected $fillable = [
        "product_id","image_path","type"
    ];
    
}
