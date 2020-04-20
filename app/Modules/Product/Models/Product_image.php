<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;

class Product_image extends Model
{
    
      protected $fillable = [
        "user_id","image_path","type"
    ];
    
}
