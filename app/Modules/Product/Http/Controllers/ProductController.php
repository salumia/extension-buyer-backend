<?php

namespace App\Modules\Product\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Modules\Product\Models\Product as Product;

use App\Modules\Product\Models\Categorie as Category;
use App\Modules\Product\Models\Product_type as ProductType;
use App\Modules\Product\Models\Product_image as ProductImage;
use App\Modules\Product\Models\Temp_product_image as TempProductImage;
use App\User as User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');
        auth()->setDefaultDriver('api');
        
    }

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategoriesAndProductType()
    {
          try {
                $user = auth()->userOrFail();
            } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
                // do something
                
                $error = $e->getMessage(); 
                return response()->json(['error' =>$e->getMessage()], 401);
            
            }
       $categories =  Category::orderBy('category_name','ASC')->get();
       
       $productTypes =  ProductType::orderBy('display_type_name','ASC')->get();
       
       $response = array('status'=> 200, 'categories'=> $categories, 'product_type'=> $productTypes);
       return response()->json($response);

        
    }
    
    
   
    
    public function createProduct(Request $request)
    {
        
          try {
                $user = auth()->userOrFail();
            } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
                // do something
                
                $error = $e->getMessage(); 
                return response()->json(['error' =>$e->getMessage()], 401);
            
            }
            
        $postbody = $request->json()->all();
        
    
          $validator = Validator::make($postbody, [ 
            'cat_id' => 'required',
            //'product_image_ids' => 'required',
            'product_type_id' => 'required|min:1',
            'product_name'=>'required',
            'price'=>'required',
            'description'=>'required',
            //'currency'=>'required',
            "total_users"=> "required|min:0",
            "publish_date"=> "required|date|date_format:Y-m-d",
            "negotiate"=> "required|min:0|max:1",
            "website"=> "required|url"
            //:
        ]);
        
        if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
        }
        
        
        $catIds = join(",",$postbody['cat_id']);
        
        $userId = auth()->user()->id;
        $postbody = (object) $postbody;
        
        $product =new Product;
        $product->product_name = $postbody->product_name;
        $product->product_type= $postbody->product_type_id;
        $product->user_id = $userId;
        $product->cat_id = $catIds;
        $product->description = $postbody->description;
        $product->total_users = $postbody->total_users;
        $product->product_created_date = $postbody->publish_date;
        $product->status = 0;
        $product->price = $postbody->price;
        $product->service_fee =  20; //$postbody->service_fee;
        $product->visibilty = 0;
        $product->negotiate = $postbody->negotiate;
        $product->is_sold = 0;
        $product->currency = $request->currency;
        $product->website = $postbody->website;

        $product->save();
        $product_id=$product->id;
        if(isset($postbody->product_image_ids)){
            //dd($postbody->product_image_ids);
            foreach($postbody->product_image_ids as $value){
                $value = (int) $value;
                $TempProductImage = TempProductImage::find($value);
                $ProductImage = new ProductImage;
                
                $ProductImage->product_id = $product_id;
                $ProductImage->image_path = $TempProductImage->image_path;
                $ProductImage->type =  $TempProductImage->type;
                $ProductImage->save();
            }
        }
        $response = array('status'=> 200, 'message'=>"Product is created successfully.");
        return response()->json($response);
      
    }
    
    
    public function uploadProductMedia($type,Request $request){

        $validator = Validator::make($request->all(), [ 
            'uploadimage.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $postbody = $request->all();
        
        if($request->hasFile('uploadimage')){
            foreach($request->file('uploadimage') as $image) {
                $path = $image->getClientOriginalName();
                $random=rand(1,100);
                $name = time().'-'.$random.'-'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images/productmedia');
                $image->move($destinationPath, $name);
                $imagePath = $name;
                
                $complete_path[]='trigvent.com/sell-admin/public/images/productmedia/'.$name;
                $postbody = (object) $postbody;
                $ProductImage = new TempProductImage();
                $ProductImage->type = $type;
                $ProductImage->image_path= $imagePath;
                $ProductImage->save();
                $type =$ProductImage->type;
                $ids[]=$ProductImage->id;
            }

        } 
        $response = array('status'=> 200, 'message'=>"Image uploaded successfully.",'image_id'=>$ids,'type'=>$type,'image_path'=>$complete_path);
            return response()->json($response);       
    } 
  
    public function productListing(){
        
        try {
                $user = auth()->userOrFail();
            } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
                // do something
                
                $error = $e->getMessage(); 
                return response()->json(['error' =>$e->getMessage()], 401);
            
            }
            
            $userId = auth()->user()->id;

            $product = DB::table('products')
            ->leftJoin('product_images', 'products.id', '=', 'product_images.product_id')
            ->select('products.id', 'products.cat_id', 'products.user_id', 'products.product_name','products.total_users', 'products.price', 'products.product_created_date', 'product_images.product_id', 'product_images.image_path', 'product_images.type')
            ->where('products.user_id', '=', $userId)
            ->get();
            
            $response = array('status'=> 200, 'message'=>"all product list.",'productListing'=>$product);
            return response()->json($response); 
    }
    
}
