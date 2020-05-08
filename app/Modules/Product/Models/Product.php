<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use App\User as User;
use App\Modules\Product\Models\Product_type as ProductType;

class Product extends Model
{
    protected $fillable = [
        "cat_id","user_id","product_name","description","total_users","product_created_date","product_type","status","price","service_fee","visibilty",
        "is_sold","negotiate","currency","website"
    ];

    public function getProductType() {
        return $this->belongsTo(ProductType::class,'product_type')->select(array('id','type'));
    }
    public function getUser() {
        return $this->belongsTo(User::class,'user_id')->select(array('id','name'));
    }
}
