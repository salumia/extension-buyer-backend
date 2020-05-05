<?php

namespace App\Modules\Product\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Modules\Product\Models\Product as Product;

use App\Modules\Product\Models\Categorie as Category;
use App\Modules\Product\Models\Product_type as ProductType;
use App\Modules\Product\Models\Product_image as ProductImage;
use App\Modules\Product\Models\Temp_product_image as TempProductImage;
use App\Modules\Product\Models\Userbase as Userbase;
use App\User as User;
use App\Offer as Offer;
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
            'currency'=>'required',
            "total_users"=> "required|min:0",
            "publish_date"=> "required|date_format:d/m/Y",
            //"negotiate"=> "required|min:0|max:1",
            "website"=> "required|url"
            //:
        ]);
        
        if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
        }
        
        
        $cat_id=$request->cat_id;
        if(is_array($cat_id)){
           if(count($cat_id)>0){
               array_walk($cat_id, function (&$value, $key) {
                   $value="#$value#";
                });
                $catIds = join(",",$cat_id);
           }
           
        }else{
           $error = array('cat_id'=>["invalide formate."]);
            return response()->json(['error'=>$error], 200);
        }
      
        
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
        $product->visibilty = $request->visibilty;
        $product->negotiate = 0;
        $product->is_sold = 0;
        $product->currency = $request->currency;
        $product->website = $postbody->website;
        $product->save();
        $product_id=$product->id;
        
        $userbase=$request->userbase;
        if($userbase){
            foreach($userbase as $user){
                $country_id= $user['country_id'];
                $user= $user['user'];
                
                $userbase= new Userbase;
                $userbase->country_id=$country_id;
                $userbase->product_id=$product_id;
                $userbase->users=$user;
                $userbase->save();
            }
        }
        
        
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

                $url =  url('/').'/images/productmedia/'.$name;
                $complete_path[] =str_replace("server.php","public",$url);
                
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
            
            $active = array();
            $products=DB::table('products')->select( 'id','product_name as name','visibilty','product_type as type','status')->where('user_id', '=', $userId)->whereIn('status', [0, 1, 5])->get();
            
            $products1 = (array) $products->toArray();
            if(count($products1)>0){
              foreach($products1 as $key => $product){
                $product = (array) $product;
                $productType=$product['type'];
                $product_id=$product['id'];
                
                $product_type = ProductType::select('type')->where('id','=',$productType)->first();
                $product['type']=$product_type->type;
                
                $received_offer=DB::table('products')->where('user_id', '=', $userId)->count();
                
                $total_offer=DB::table('offers')->where('product_id', '=', $product_id)->count();
                $total_amount = 0;
                if($total_offer != 0){
                    $total_amount=DB::table('offers')->where('product_id', '=', $product_id)->sum('offered_amount');
                    $total_amount = $total_amount/$total_offer;
                    
                }
                 $product['received_offer'] = $received_offer;
                 $product['avg_offers'] = $total_amount;
                
                $image=DB::table('product_images')->select( 'id','image_path','type')->where('product_id', '=', $product_id)->get();
                foreach($image as $img){
                    $name=$img->image_path;
                    $url =  url('/').'/images/productmedia/'.$name;
                    $img->image_path =str_replace("server.php","public",$url);
                }
                $images = (array)  $image->toArray();
                $product['images'] = $images;
                $active[] = $product;
              }
            }
            
/*----------------------status-Progress----------------------------------------*/

            $in_progress=array();
            $products2=DB::table('products')->select( 'id','product_name as name','visibilty','product_type as type','status')->where('user_id', '=', $userId)->where('status', '=',2)->get();
            $product_in = (array) $products2->toArray();
           if(count($product_in)>0){
                
            $buyerName=DB::table('users')->select('name')->where('id','=',$userId)->first();
            $recived_offer=DB::table('products')->where('user_id', '=', $userId)->count();
            $sold_offer=DB::table('products')->where('user_id', '=', $userId)->where('status', '=',2)->count();
             
             foreach ($product_in as $key => $product) {
                $product_in = (array) $product;
                $product_id=$product_in['id'];
                $productType=$product_in['type'];
                
                $product_type = ProductType::select('type')->where('id','=',$productType)->first();
                $product_in['type']=$product_type->type;
                
                $offers=DB::table('offers')->select('buyer_id')->where('product_id','=',$product_id)->get();
                foreach($offers as $offer){
                    $offers = (array) $offer;
                    $buyer_id=$offers['buyer_id'];
                }
                
                $product_in['buyer_id']=$buyer_id;
                
                $product_in['buyer_name']=$buyerName->name;
                $product_in['received_offers'] = $recived_offer;
                $product_in['sold_amount'] = $sold_offer;
                $image=DB::table('product_images')->select( 'id','image_path','type')->where('product_id', '=', $product_id)->get();
                foreach($image as $img){
                    $name=$img->image_path;
                    $url =  url('/').'/images/productmedia/'.$name;
                    $img->image_path =str_replace("server.php","public",$url);
                }
                $images = (array)  $image->toArray();
                $product_in['images'] = $images;
                $in_progress[] = $product_in;      
             }
            }
 /*------------------------------status-sold--------------------------------------------*/
            $sold=array();
            $products3=DB::table('products')->select( 'id','product_name as name','visibilty','product_type as type','status')->where('user_id', '=', $userId)->whereIn('status', [3,4])->get();
            $product_sold = (array) $products3->toArray(); 
            if(count($product_sold)>0){
                $buyerName=DB::table('users')->select('name')->where('id','=',$userId)->first(); 
                $recived_offer=DB::table('products')->where('user_id', '=', $userId)->count();
                $sold_offer=DB::table('products')->where('user_id', '=', $userId)->where('status', '=',3 or 'status','=',4)->count();
                
                 foreach ($product_sold as $key => $product) {
                    $product_sold = (array) $product;
                    $product_id=$product_sold['id'];
                    
                    $productType=$product_sold['type'];
                    $product_type = ProductType::select('type')->where('id','=',$productType)->first();
                    $product_sold['type']=$product_type->type;
                    
                    $offers=DB::table('offers')->select('buyer_id')->where('product_id','=',$product_id)->get();
                    foreach($offers as $offer){
                        $offers = (array) $offer;
                        if($offers['buyer_id']){
                            $buyer_id=$offers['buyer_id'];
                        }
                    }
                    $product_sold['buyer_id']=$buyer_id;
                    $product_sold['buyer_name']=$buyerName->name;
                    $product_sold['recived_offer'] = $recived_offer;
                    $product_sold['sold_offer'] = $sold_offer;
                    $image=DB::table('product_images')->select( 'id','image_path','type')->where('product_id', '=', $product_id)->get();
                    foreach($image as $img){
                        $name=$img->image_path;
                        $url =  url('/').'/images/productmedia/'.$name;
                        $img->image_path =str_replace("server.php","public",$url);
                    }
                    $images = (array)  $image->toArray();
                    $product_sold['images'] = $images;
                    $sold[] = $product_sold;   
                }
            }
            $allProducts['in_progess']  = $in_progress;  
            $allProducts['sold']  = $sold;  
            $allProducts['active']  = $active;   
                
            $response = array('status'=> 200, 'message'=>"all product list.",'productListing'=>$allProducts);
            return response()->json($response); 
    }
    
    
    public function productView($id, Request $request){
        
        $product = Product::select( 'id','user_id','product_name','product_type as type','total_users','product_created_date','website','price','negotiate','description','updated_at as updated_date')->where('id', '=', $id)->first();
        $product= (array) $product->toArray();

        $productType=$product['type'];
        $product_type = ProductType::select('type')->where('id','=',$productType)->first();  
        $product['type']=$product_type->type;
        $total_listings=DB::table('products')->where('user_id', '=', $product['user_id'])->count();
        $sold_listings=DB::table('products')->where('user_id', '=', $product['user_id'])->whereIn('status', [3,4])->count();
        $seller = DB::table('users as t1')->select('t1.id as id','t1.image_path as image','t2.name as country', 't1.name  as name', 't1.created_at as created_date')
            ->leftJoin('countries as t2', 't1.country_id', '=', 't2.id')->where('t1.id','=', $product['user_id'])
            ->first();
            $seller = (array) $seller;
            
        $name=$seller['image'];
        $url = url('/').'/images/upload/'.$name;
        $seller['image']=str_replace("server.php","public",$url);;
        $seller['total_listings'] = $total_listings;
        $seller['sold_listings'] = $sold_listings;
         
        $userBases = DB::table('userbases')->select('users','name as country_name')
            ->leftJoin('countries', 'userbases.country_id', '=', 'countries.id')
            ->get();
            
        $bannerImages=DB::table('product_images')->select( 'id','image_path')->where('product_id', '=', $id)->where('type', '=', 'banner')->get();
            foreach($bannerImages as $img){
                $name=$img->image_path;
                $url =  url('/').'/images/productmedia/'.$name;
                $img->image_path =str_replace("server.php","public",$url);
            }
                
        $statImages=DB::table('product_images')->select( 'id','image_path','type')->where('product_id', '=', $id)->where('type', '=', 'stats')->get();
            foreach($statImages as $img){
                $name=$img->image_path;
                $url =  url('/').'/images/productmedia/'.$name;
                $img->image_path =str_replace("server.php","public",$url);
            }
               
        $bannerImages = (array) $bannerImages->toArray(); 
        $statImages = (array) $statImages->toArray(); 
        $product['seller'] = $seller; 
        $product['userbase'] = $userBases;
        $product['banners']=$bannerImages;
        $product['statistics']=$statImages;

        unset($product['user_id']);
        
        $response = array('status'=> 200, 'message'=>"Product Details.",'product'=>$product);
        return response()->json($response);
        
    }
    
    
    public function productDelete($id,Request $request){
        
        $product_image=ProductImage::where('product_id','=',$id)->get();
        foreach($product_image as $img){
            $imageName=$img->image_path;
            if($imageName){
                $destination = public_path('/images/productmedia/');
                $destinationPath =str_replace("laravelcode/public/","",$destination);
                if (file_exists($destinationPath.$imageName)) {
                    unlink($destinationPath.$imageName);
                 }
            }
            $img->delete();
        }
        
        $product=Product::find($id);
        $product->delete();
        $response = array('status'=> 200, 'message'=>"Product Delete Successfully.");
        return response()->json($response);
        
    }
    
    public function productUpdate($id,Request $request){
        $validator = Validator::make($request->all(), [ 
            'product_name' => 'required',
            'total_users' => 'required',
            'product_created_date' => 'required|date_format:d/m/Y',
            'website' => 'required',
            'price' => 'required|max:20',
            'negotiate' =>'required|max:20',
            'description' => 'required|max:20'
        ]);
        if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
        }
   
        $product=Product::find($id);
        $product->product_name=$request->product_name;
        $product->total_users=$request->total_users;
        $product->product_created_date=$request->product_created_date;
        $product->website=$request->website;
        $product->price=$request->price;
        $product->negotiate=$request->negotiate;
        $product->description=$request->description;
        $product->update();
        /*if($product->update){
            $uploadimage=$request->file('uploadimage');
            if(count($uploadimage)>0){
                foreach($uploadimage as $image){
                    $path = $image->getClientOriginalName();
                    $random=rand(1,100);
                    $name = time().'-'.$random.'-'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/productmedia');
                    $image->move($destinationPath, $name);
                    $imagePath = $name;
                }
                
            }
        }*/
        
        $response = array('status'=> 200, 'message'=>"Product updated Successfully.");
        return response()->json($response);
        
        
    }
    
    
}
