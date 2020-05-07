<?php

namespace App\Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User as User;
use App\Modules\Admin\Models\Category as Category;
use App\Modules\Product\Models\Product as Product;
use App\Modules\Product\Models\Product_type as ProductType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Session;

class ProductListingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products=array();
        $product=Product::all();
        $product = (array) $product->toArray();
        foreach ($product as $pro) {
             $product = (array) $pro;
                if($product){
                    $productType=$product['product_type'];
                    $product_type = ProductType::select('type')->where('id','=',$productType)->first();
                    $product['type']=$product_type['type'];

                    $userId=$product['user_id'];
                    $received_offer=DB::table('products')->where('user_id', '=', $userId)->count();
                    $product['received_offer']=$received_offer; 

                    $product_seller = User::select('name')->where('id','=',$userId)->first();
                    $product['seller']=$product_seller['name'];
                }
                $products[] = $product;
            }  
        return view('Admin::product.product',compact('products'));  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product=Product::find($id);
        return view('Admin::product.edit_product',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
