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
use App\Modules\Country\Models\Country as Country;
use App\User as User;
use App\Offer as Offer;

use App\Product_access_request as Productaccess;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Image;

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
    
    
     public function createThumbnailForProductImage($imageName)
    {            
     
        $destination = public_path('/images/productmedia/100x100/'.$imageName);
        $path = str_replace("laravelcode/public/","",$destination);
        $savePath200 =  $path;

        $destination = public_path('/images/productmedia/250x250/'.$imageName);
        $path = str_replace("laravelcode/public/","",$destination);
        $savePath250 =  $path;

        $destination = public_path('/images/productmedia/500x500/'.$imageName);
        $path = str_replace("laravelcode/public/","",$destination);

        $savePath500 = $path;

        $destination = public_path('/images/productmedia/temp/'.$imageName);
        $tempPath = str_replace("laravelcode/public/","",$destination);
        

        $img = Image::make($tempPath);
        $img->backup();
        $img->resize(100, 100 /*,function ($constraint) {
        $constraint->aspectRatio();
        } */);
        $img->save($savePath200);
        $img->reset();
        
        $img = Image::make($tempPath);
        $img->backup();
        $img->resize(250, 250 /*,function ($constraint) {
        $constraint->aspectRatio();
        } */);
        $img->save($savePath250);
        $img->reset();

        $img = Image::make($tempPath);
        $img->backup();
        $img->resize(500, 500 /*,function ($constraint) {
        $constraint->aspectRatio();
        } */);
        $img->save($savePath500);
        $img->reset();

        $fullImagePath = public_path('/images/productmedia/full_image/'.$imageName);
        $fullImagePath = str_replace("laravelcode/public/","",$fullImagePath);

        rename($tempPath, $fullImagePath);
        return true;
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
            "visibilty"=>"required",
            "store_url"=>"required",
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
        if($request->visibilty==0 or $request->visibilty==1){
            $product->unique_key=str_random(6);
        }
        $product->negotiate = 0;
        $product->is_sold = 0;
        $product->store_url = $request->store_url;
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
                $this->createThumbnailForProductImage($TempProductImage->image_path);
                
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
                $name = time().'-'.$random.'.'.$image->getClientOriginalExtension();
                $destination_path=public_path('/images/productmedia/temp/');
                $destinationPath=str_replace("laravelcode/public/","",$destination_path);
                $image->move($destinationPath, $name);
                $imagePath = $name;
                $url =  url('/').'/images/productmedia/temp/'.$name;
                $complete_path[] =$url;
                
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
  
    public function productListing(Request $request){
        try {
                $user = auth()->userOrFail();
            } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
                // do something
                
                $error = $e->getMessage(); 
                return response()->json(['error' =>$e->getMessage()], 401);
            
            }
            
            $userId = auth()->user()->id;
            
            $active = array();
            $products=DB::table('products')->select( 'id','product_name as name','visibilty','unique_key','product_type as type','status')->where('user_id', '=', $userId)->whereIn('status', [0, 1, 5])->get();
            
            $products1 = (array) $products->toArray();
           // echo "<pre>";print_r($products1);die;
            if(count($products1)>0){
                $sellerName=DB::table('users')->select('name','username')->where('id','=',$userId)->first();
                
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
                 $product['received_offer'] = $total_offer;
                 $product['avg_offers'] = $total_amount;
                 $product['sellerName']=$sellerName->name;
                 $product['username']=$sellerName->username;
                $image_active=DB::table('product_images')->select( 'id','image_path','type')->where('product_id', '=', $product_id)->get();
                foreach($image_active as $img_active){
                    $imgName=$img_active->image_path;
                    $url =  url('/').'/images/productmedia/full_image/'.$imgName;
                    $img_active->image_path =$url;
                }
                $image_actives = (array)  $image_active->toArray();
                $product['images'] = $image_actives;
                $active[] = $product;
              }
            }
            
/*----------------------status-Progress----------------------------------------*/

            $in_progress=array();
            $products2=DB::table('products')->select( 'id','product_name as name','visibilty','unique_key','product_type as type','status')
                                            ->where('user_id', '=', $userId)
                                            ->where('status', '=',2)
                                            ->get();
            $product_in = (array) $products2->toArray();
            
           if(count($product_in)>0){
             
             foreach ($product_in as $key => $product) {
                $product_in = (array) $product;
                $product_id=$product_in['id'];
                $productType=$product_in['type'];
                
                $product_type = ProductType::select('type')->where('id','=',$productType)->first();
                $product_in['type']=$product_type->type;
                
                $offers=DB::table('offers')->select('buyer_id')->where('product_id','=',$product_id)->get();
                $recived_offer=DB::table('offers')->where('product_id', '=',$product_id)->count();
                
                if(count($offers)>0){
                    foreach($offers as $offer){
                        $offer = (array) $offer;
                        $offer_details=DB::table('offers')->select('buyer_id','offered_amount','counter_offer')
                                                            ->where('buyer_id', '=', $offer['buyer_id'])
                                                            ->where('product_id','=',$product_id)
                                                            ->where('awarded', '=',1)->first();
                                                            
                        if($offer_details){
                           
                            $buyerName=DB::table('users')->select('name','username')->where('id','=',$offer_details->buyer_id)->first();
                            $product_in['buyer_id']=$offer_details->buyer_id;
                            $product_in['buyer_name']=$buyerName->name;
                            $product_in['username']=$buyerName->username;
                            $product_in['counter_offer']=$offer_details->counter_offer;
                            $product_in['sold_amount'] = $offer_details->offered_amount;
                             
                        }
                    }
                }
                $product_in['received_offers'] = $recived_offer;
                
                $image=DB::table('product_images')->select( 'id','image_path','type')->where('product_id', '=', $product_id)->get();
                foreach($image as $img){
                    $name=$img->image_path;
                    $url =  url('/').'/images/productmedia/full_image/'.$name;
                    $img->image_path =$url;
                }
                $images = (array)  $image->toArray();
                $product_in['images'] = $images;
                $in_progress[] = $product_in;      
             }
            }
 /*------------------------------status-sold--------------------------------------------*/
            $sold=array();
            $products3=DB::table('products')->select( 'id','product_name as name','visibilty','unique_key','product_type as type','status')
                                            ->where('user_id', '=', $userId)
                                            ->whereIn('status', [3,4])
                                            ->get();
            $product_sold = (array) $products3->toArray(); 
            if(count($product_sold)>0){
                
                 foreach ($product_sold as $key => $product) {
                    $product_sold = (array) $product;
                    $product_id=$product_sold['id'];
                    
                    $productType=$product_sold['type'];
                    $product_type = ProductType::select('type')->where('id','=',$productType)->first();
                    $product_sold['type']=$product_type->type;
                    
                    $offers=DB::table('offers')->select('buyer_id')->where('product_id','=',$product_id)->get();
                    $recived_offer=DB::table('offers')->where('product_id', '=', $product_id)->count();
                    
                    if(count($offers)>0){
                        foreach($offers as $offer){
                            $offer = (array) $offer;
                            
                            
                            $offer_details=DB::table('offers')->select('buyer_id','offered_amount','counter_offer')
                                                            ->where('buyer_id', '=', $offer['buyer_id'])
                                                            ->where('product_id','=',$product_id)
                                                            ->where('awarded', '=',1)
                                                            ->first();
                            
                            if($offer_details){
                                $buyerName=DB::table('users')->select('name','username')->where('id','=',$offer_details->buyer_id)->first();
                                
                                $product_sold['buyer_id']=$offer_details->buyer_id;
                                $product_sold['buyer_name']=$buyerName->name;
                                $product_sold['username']=$buyerName->username; 
                                $product_sold['offer_counter'] =$offer_details->counter_offer;
                                $product_sold['sold_offer'] = $offer_details->offered_amount;
                            }                                
                        }
                        
                    }
                    $product_sold['recived_offer'] = $recived_offer;
                    $image=DB::table('product_images')->select( 'id','image_path','type')->where('product_id', '=', $product_id)->get();
                    foreach($image as $img){
                        $name=$img->image_path;
                        $url =  url('/').'/images/productmedia/full_image/'.$name;
                        $img->image_path =$url;
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
        
        $product = Product::select( 'id','user_id','product_name','product_type as type','status','currency','total_users','visibilty','unique_key','store_url','product_created_date','website','price','negotiate','description','updated_at as updated_date')
                            ->where('id', '=', $id)->first();
        $product= (array) $product->toArray();

        $productType=$product['type'];
        $product_type = ProductType::select('type')->where('id','=',$productType)->first();  
        $product['type']=$product_type->type;
        $total_listings=DB::table('products')->where('user_id', '=', $product['user_id'])->where('status','!=','0')->count();
        $sold_listings=DB::table('products')->where('user_id', '=', $product['user_id'])->whereIn('status', [3,4])->count();
        $seller = DB::table('users as t1')->select('t1.id as id','t1.image_path as image','t2.name as country', 't2.sortname as country_code', 't1.name  as name','t1.username as username', 't1.created_at as created_date')
            ->leftJoin('countries as t2', 't1.country_id', '=', 't2.id')->where('t1.id','=', $product['user_id'])
            ->first();
            $seller = (array) $seller;
        $country_code = strtolower($seller['country_code']);
        $seller['country_code']=$country_code;
        $name=$seller['image'];
        
        if($name){
            $destination_path=public_path('/images/productmedia/full_image/');
            $destination = str_replace("laravelcode/public/","",$destination_path);
            if (file_exists($destination.$name)) {
                $url = url('/').'/images/productmedia/full_image/'.$name;
                $seller['image']=$url;
             }else{
                $seller['image']=''; 
             }
        }
        
        $seller['total_listings'] = $total_listings;
        $seller['sold_listings'] = $sold_listings;
         
        $userBases = DB::table('userbases')->select('users','name as country_name')
            ->leftJoin('countries', 'userbases.country_id', '=', 'countries.id')->where('product_id', '=', $id)
            ->get();
            
        $bannerImages=DB::table('product_images')->select( 'id','image_path')->where('product_id', '=', $id)->where('type', '=', 'banner')->get();
        
            foreach($bannerImages as $img){
                $name=$img->image_path;
                $url =  url('/').'/images/productmedia/full_image/'.$name;
                $img->image_path =$url;
            }
            
                
        $statImages=DB::table('product_images')->select( 'id','image_path','type')->where('product_id', '=', $id)->where('type', '=', 'stats')->get();
        
            foreach($statImages as $img){
                $name=$img->image_path;
                $url =  url('/').'/images/productmedia/full_image/'.$name;
                $img->image_path =$url;
            }
               
        $bannerImages = (array) $bannerImages->toArray(); 
        $statImages = (array) $statImages->toArray(); 
        
        $user = false;
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
          
        
        }
        
        
        if($user and $product['user_id'] == auth()->user()->id){
            
            $offers = DB::table('offers')
                        ->select('offers.id as offer_id','offers.counter_offer','offers.awarded','users.name as first_name', 'users.last_name','users.username', 'users.image_path','users.country_id as country_code', 'users.country_id', 'users.state_id',  'users.city_id','offers.offered_amount')
                        ->leftJoin('users', 'offers.buyer_id', '=', 'users.id')
                        ->where('offers.product_id','=',$id )
                        ->get();
                        
            foreach($offers as $offer){
                $country_id=$offer->country_code;
                $country= Country::select('sortname')->where('id','=',$country_id)->first();
                $offer->country_code=$country->sortname;
                
                $name=$offer->image_path;
                $url =  url('/').'/images/profileimages/full_image/'.$name;
                $offer->image_path =$url;
            }
            $product['received_offers'] = $offers; 
            
            $requestAccess = DB::table('product_access_requests')
                         ->select('product_access_requests.id as request_id','product_access_requests.status','users.name as first_name', 'users.last_name','users.username', 'users.image_path','users.country_id as country_code', 'users.country_id')
                        ->leftJoin('users', 'product_access_requests.sender_id', '=', 'users.id')
                        ->where('product_access_requests.product_id','=',$id )
                        ->get();
            
            foreach($requestAccess as $request){
                $country_id=$request->country_code;
                $country= Country::select('sortname')->where('id','=',$country_id)->first();
                $request->country_code=$country->sortname;
                
                $name=$request->image_path;
                $url =  url('/').'/images/profileimages/full_image/'.$name;
                $request->image_path =$url;
            }
            $product['access_request'] = $requestAccess;
             
            
        }else if($user){
            
           
            $userIdAsBuyer = auth()->user()->id;
            $my_offer=array();
            
            $buyer = DB::table('users as t1')->select('t1.id as id','t1.name  as first_name','t1.name  as last_name','t1.username as username','t1.image_path as image','t2.name as country', 't2.sortname as country_code')
                                            ->leftJoin('countries as t2', 't1.country_id', '=', 't2.id')
                                            ->where('t1.id','=', $userIdAsBuyer)
                                            ->first();
            $buyer = (array) $buyer;
            $name=$buyer['image'];
            if($name){
                $destination_path=public_path('/images/profileimages/full_image/');
                $destination = str_replace("laravelcode/public/","",$destination_path);
                if (file_exists($destination.$name)) {
                    $url = url('/').'/images/profileimages/full_image/'.$name;
                    $buyer['image']=$url;
                 }else{
                    $buyer['image']=$name; 
                 }
            }
            $my_offer['buyer-details']=$buyer;
            
            $sendOffer = Offer::select('id as offer_id','counter_offer', 'offered_amount', 'awarded', 'created_at')->where([ ['buyer_id','=',$userIdAsBuyer],[ 'product_id', '=' , $id ] ])->first();
            if($sendOffer){
                $sendOffer = (array) $sendOffer->toArray();
                $my_offer['buyer-details']['counter_offer']=$sendOffer['counter_offer'];
                $my_offer['buyer-details']['offer-amount']=$sendOffer['offered_amount'];
                $my_offer['buyer-details']['offer_id']=$sendOffer['offer_id'];
                $my_offer['buyer-details']['awarded']=$sendOffer['awarded'];
                $my_offer['buyer-details']['created_at']=$sendOffer['created_at'];
                 
                $received_offer=DB::table('products')->where('user_id', '=', $userIdAsBuyer)->count();
                
                $offer=DB::table('offers')->select('offered_amount')->where('product_id', '=', $id)->first();
                
                $asking_price=$offer->offered_amount;
                $total_offer=DB::table('offers')->where('product_id', '=', $id)->count();
                $total_amount = 0;
                if($total_offer != 0){
                    $total_amount=DB::table('offers')->where('product_id', '=', $id)->sum('offered_amount');
                    $total_amount = $total_amount/$total_offer;
                }
                $my_offer['total_offer'] = $total_offer;
                $my_offer['avg_offers'] = $total_amount;
                $my_offer['asking_price']=$asking_price;
                 
                
                $product['send_offers'] = $my_offer; 
            }
            
            $buyerRequest = Productaccess::where([ ['sender_id','=',$userIdAsBuyer],[ 'product_id', '=' , $id ],['status','=',1] ])->first();
            if($buyerRequest){
                $product['access']=1;
            }else{
                $product['access']=0;
            }
        }
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
                $folderName = ['full_image/','100x100/','250x250/','500x500/'];
                
                for ($x = 0; $x <= 3; $x++) {
                    $tempFolderName = $folderName[$x];
                    $destination_path=public_path('/images/productmedia/'.$tempFolderName);
                    $destination = str_replace("laravelcode/public/","",$destination_path);
                    $destinationPath =str_replace("laravelcode/public/","",$destination);
                    if (file_exists($destinationPath.$imageName)) {
                        unlink($destinationPath.$imageName);
                     }
                }
                 
            }
            $img->delete();
        }
        
        $product=Product::find($id);
        $product->delete();
        $response = array('status'=> 200, 'message'=>"Product Delete Successfully.");
        return response()->json($response);
        
    }
    
    public function getProductRawDetails($id,Request $request){
        
        $product = Product::select( 'id as product_id','user_id','product_name','product_type','visibilty','unique_key','store_url','cat_id as category','total_users','currency','product_created_date as publish_date','website','price','negotiate','description')->where('id', '=', $id)->first();
        if($product){
            $product= (array) $product->toArray();
            $productId=$product['product_id'];
            $category=$product['category'];
            $userId= $product['user_id'];
            $user =User::select('name','username')->where('id','=',$userId)->first();
            $product['username']=$user->username;
            $cateString=str_replace("#","",$category);
            $cateId=explode(",",$cateString);
            foreach($cateId as $key=>$value){
               $cateId[$key]=(int)$value;
            }
            
            $product['category']=$cateId;
            $userbase=DB::table('userbases')->select('id','country_id','users as user')->where('product_id','=',$productId)->get();
            
            $userBase = (array) $userbase->toArray(); 
            $product['userbase'] = $userBase;
            $bannerImage=DB::table('product_images')->select( 'id','image_path')->where('type', '=', 'banner')->where('product_id', '=', $productId)->get();
            foreach($bannerImage as $img){
                $name=$img->image_path;
                $url =  url('/').'/images/productmedia/full_image/'.$name;
                $img->image_path =$url;
            }
            $bannerImage = (array)  $bannerImage->toArray();
            $product['bannerImages'] = $bannerImage;
            $statImage=DB::table('product_images')->select( 'id','image_path')->where('type','=','stats')->where('product_id', '=', $productId)->get();
                foreach($statImage as $img){
                    $name=$img->image_path;
                    $url =  url('/').'/images/productmedia/full_image/'.$name;
                    $img->image_path =$url;
                }
            $statImage = (array)  $statImage->toArray();
            $product['statsImages'] = $statImage;
            
        }
        $response = array('status'=> 200, 'message'=>"Product Edit Details.",'product'=>$product);
        return response()->json($response);
        
    }
    
    public function updateProductRawDetails($id,Request $request){

        try {
                $user = auth()->userOrFail();
            } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
                // do something
                
                $error = $e->getMessage(); 
                return response()->json(['error' =>$e->getMessage()], 401);
            
            }
        
        $validator = Validator::make($request->all(), [ 
            'cat_id' => 'required',
            'product_type_id' => 'required|min:1',
            'product_name' => 'required',
            'total_users' => 'required',
            'publish_date' => 'required|date_format:d/m/Y',
            'website' => 'required|url',
            'currency'=>'required',
            'price' => 'required|max:20',
            'negotiate' =>'required|max:20',
            "store_url"=>"required",
            'visibilty' =>'required',
            'description' => 'required'
        ]);
        if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
        }
        
        $userId = auth()->user()->id;
        
        $cat_id=$request->cat_id;
        if(is_array($cat_id)){
           if(count($cat_id)>0){
               array_walk($cat_id, function (&$value, $key) {
                   $value="#$value#";
                });
                $catIds = join(",",$cat_id);
           }
           
        }
        
        $product=Product::find($id);
        $product->product_name=$request->product_name;
        $product->product_type= $request->product_type_id;
        $product->user_id = $userId;
        $product->cat_id = $catIds;
        $product->total_users=$request->total_users;
        $product->visibilty = $request->visibilty;
        $product->product_created_date=$request->publish_date;
        $product->website=$request->website;
        $product->currency = $request->currency;
        $product->price=$request->price;
        $product->negotiate=$request->negotiate;
        $product->description=$request->description;
        //$product->status = $request->status;
        $product->store_url=$request->store_url;
        /*$product->service_fee =  20;
        $product->is_sold = 0;*/
        $product->update();
        $userbase=$request->userbase;
        
        $userbase_data=Userbase::where('product_id','=',$id)->get();
        foreach ($userbase_data as $data){
            $data->delete();
        }
        if($userbase){
            foreach($userbase as $user){
                if($user){
                    $country_id= $user['country_id'];
                    $user= $user['user'];
                    $userbase= new Userbase;
                    $userbase->country_id=$country_id;
                    $userbase->product_id=$id;
                    $userbase->users=$user;
                    $userbase->save();
                }
            }
        }
        
        if($request->deleted_image_ids){
            
            foreach($request->deleted_image_ids as $delete_id){
                $id = (int) $delete_id;
                $ProductImage = ProductImage::find($id);
                if($ProductImage != null){
                    $imageName=$ProductImage->image_path;
                    $destination_path=public_path('/images/productmedia/full_image/');
                    $destination = str_replace("laravelcode/public/","",$destination_path);
                    if (file_exists($destination.$imageName)) {
                        unlink($destination.$imageName);
                     }
                   $ProductImage->delete();  
                }
            }
        }

        if(isset($request->product_image_ids)){
            foreach($request->product_image_ids as $image_id){
                $image_id = (int) $image_id;
                $TempProductImage = TempProductImage::find($image_id);
                if($TempProductImage != null){
                    $ProductImage = new ProductImage;
                    $ProductImage->product_id = $id;
                    $ProductImage->image_path = $TempProductImage->image_path;
                    $ProductImage->type =  $TempProductImage->type;
                    $ProductImage->save(); 
                    $this->createThumbnailForProductImage($TempProductImage->image_path);
                }
                
            }
        }
        
        $response = array('status'=> 200, 'message'=>"Product updated Successfully.");
        return response()->json($response);
    }
    
    
    public function submitOffer(Request $request)
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
            'product_id' => 'required|numeric',
            'offer_amount' => 'required'
        ]);
        
        if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
        }
        
        $postbody = (object) $postbody;
        $userId = auth()->user()->id;
        $my_offer=array();
        
        $buyer = DB::table('users as t1')->select('t1.id as id','t1.image_path as image','t2.name as country', 't2.sortname as country_code', 't1.name  as name','t1.username as username')
            ->leftJoin('countries as t2', 't1.country_id', '=', 't2.id')->where('t1.id','=', $userId)
            ->first();
        $buyer = (array) $buyer;
        $name=$buyer['image'];
        if($name){
            $destination_path=public_path('/images/profileimages/full_image/');
            $destination = str_replace("laravelcode/public/","",$destination_path);
            if (file_exists($destination.$name)) {
                $url = url('/').'/images/profileimages/full_image/'.$name;
                $buyer['image']=$url;
             }else{
                $buyer['image']=''; 
             }
        }
        $my_offer['buyer-details']=$buyer;
        $ownProductOffer=DB::table('products')->where('user_id', '=', $userId)
                                                ->where('id', '=', $postbody->product_id)
                                                ->count();
        if($ownProductOffer == 0){
                $alreadyOffered = DB::table('offers')->where('buyer_id', '=', $userId)
                                                    ->where('product_id', '=', $postbody->product_id)
                                                    ->count();
                if($alreadyOffered == 0){
                    $offer =new Offer;
                    $offer->product_id  = $postbody->product_id;
                    $offer->buyer_id = $userId;
                    $offer->awarded = 0;
                    $offer->offered_amount = $postbody->offer_amount;
                    $offer->save();
                    
                    $my_offer['buyer-details']['offered_amount']=$postbody->offer_amount;
                    $received_offer=DB::table('products')->where('user_id', '=', $userId)->count();
    
                    $offer=DB::table('offers')->select('offered_amount')
                                                ->where('product_id', '=', $postbody->product_id)
                                                ->first();
                    $asking_price=$offer->offered_amount;
                    
                    $total_offer=DB::table('offers')->where('product_id', '=', $postbody->product_id)->count();
                    $total_amount = 0;
                    if($total_offer != 0){
                        $total_amount=DB::table('offers')->where('product_id', '=', $postbody->product_id)->sum('offered_amount');
                        $total_amount = $total_amount/$total_offer;
                    }
                     $my_offer['total_offer'] = $total_offer;
                     $my_offer['avg_offers'] = $total_amount;
                     $my_offer['asking_price']=$asking_price;
                    
                    $response = array('status'=> true, 'message'=>"Offer submitted successfully.",'my_offer'=>$my_offer);
                    return response()->json($response);
                }else{

                    $alreadyOffered = DB::table('offers')->where('buyer_id', '=', $userId)
                                                            ->where('product_id', '=', $postbody->product_id)
                                                            ->first();
                   
                    $offer=Offer::find($alreadyOffered->id);
                    $offer->offered_amount = $postbody->offer_amount;
                    $offer->update();
                    
                    $my_offer['buyer-details']['offered_amount']=$postbody->offer_amount;
                    $received_offer=DB::table('products')->where('user_id', '=', $userId)->count();
                    
                    $offer=DB::table('offers')->select('offered_amount')->where('product_id', '=', $postbody->product_id)->first();
                    $asking_price=$offer->offered_amount;
                    $total_offer=DB::table('offers')->where('product_id', '=', $postbody->product_id)->count();
                    $total_amount = 0;
                    if($total_offer != 0){
                        $total_amount=DB::table('offers')->where('product_id', '=', $postbody->product_id)->sum('offered_amount');
                        $total_amount = $total_amount/$total_offer;
                    }
                     $my_offer['total_offer'] = $total_offer;
                     $my_offer['avg_offers'] = $total_amount;
                     $my_offer['asking_price']=$asking_price;
                    
                    $response = array('status'=> true, 'message'=>"Offer submitted successfully.",'my_offer'=>$my_offer);
                    return response()->json($response);
                }
        }else{
            $response = array('status'=> false, 'message'=>"Invalid product id. User can not send offer for its own product");
            return response()->json($response);  
        }
      
    }
    
    public function saveBuyerMessage(Request $request)
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
            'product_id' => 'required|numeric',
            'message' => 'required'
        ]);
        
        if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
        }
        
        $postbody = (object) $postbody;
        $userId = auth()->user()->id;
        $ownProductOffer=DB::table('products')->where('user_id', '=', $userId)
                                                ->where('id', '=', $postbody->product_id)
                                                ->count();
        
        if($ownProductOffer == 0 && isset($postbody->grant_access) &&  $postbody->grant_access == 1){
                
                $alreadyOffered = DB::table('product_access_requests')->where('sender_id', '=', $userId)
                                                                        ->where('product_id', '=', $postbody->product_id)
                                                                        ->count();
                if($alreadyOffered == 0){
                    
                    $accessRequest =new Productaccess;
                    $accessRequest->product_id  = $postbody->product_id;
                    $accessRequest->sender_id = $userId;
                    $accessRequest->status = 0;
               
                    $accessRequest->save();
                   
                    $response = array('status'=> true, 'message'=>"Product access request sent successfully.");
                    return response()->json($response);
                }else{
                        $response = array('status'=> false, 'message'=>"Request is already sent.");
                        return response()->json($response); 
                }
        }else{
            $response = array('status'=> false, 'message'=>"Invalid product id. User can not send request for its own product");
            return response()->json($response);  
        }
      
    }
    
    
    public function getAllProducts()
    {
    
        $allProducts = array();
    
        $products=DB::table('products')->select( 'products.id as product_id','products.product_name','product_types.display_type_name','product_types.type', 'products.price',  'products.visibilty', 'products.total_users')
                        ->leftJoin('product_types', 'products.product_type', '=', 'product_types.id')
                        ->where('products.status','=','1')->get();
        
        $products1 = (array) $products->toArray();
        
        if(count($products1)>0){
          foreach($products1 as $key => $product){
            $product = (array) $product;
        
            $product_id=$product['product_id'];
            
            $image=DB::table('product_images')->select( 'id','image_path','type')
                        ->where('product_id', '=', $product_id)
                        ->where('type', '=', 'banner')
                        ->limit(1)->get();
                        
                        
            foreach($image as $img){
                $name=$img->image_path;
                $url =  url('/').'/images/productmedia/full_image/'.$name;
                $img->image_path =$url;
            }
            $images = (array)  $image->toArray();
            $product['images'] = $images;
            $allProducts[] = $product;
          }
        }
        
        $response = array('status'=> 200, 'message'=>"all product list.",'productListing'=>$allProducts);
        return response()->json($response); 
    }
    
    public function getProductTypeListings(){
  
         $productTypes=ProductType::select('id','type')->get();
         $productTypes = (array) $productTypes->toArray();
         foreach($productTypes as $key => $Type){
             
             $productType = (array) $Type;
             $typeID=$productType['id'];
             $productTypeName = $productType['type'];
             $products = Product::select('id','product_name','total_users','cat_id')
                                    ->where('status','=','1')
                                    ->Where('product_type','=',$typeID)
                                    ->limit(4)
                                    ->get();
                                    
             $products = (array) $products->toArray();
             
             foreach($products as $keyInner => $product){
        
                $category=$product['cat_id'];
                $cateString=str_replace("#","",$category);
                $cateId=explode(",",$cateString);
                foreach($cateId as $keyCat=>$value){
                  $cateId[$keyCat]=(int)$value;
                }
                $categories=Category::select('id','category_name')->whereIn('id',$cateId)->get();
                $categories = (array) $categories->toArray();
              
                unset($products[$keyInner]['cat_id']);
                 $products[$keyInner]['categories'] =$categories;
                
             }
             
             $productTypes[$key]['listing']=$products;
         
            unset($productTypes[$key]['id']);
         }
     
        $response = array('status'=> 200, 'message'=>"top 4 active list.",'productTypeListing'=>$productTypes);
        return response()->json($response);
    }
    
    
    public function getLatestListings(){
        $active=array();
        $products = Product::select('id','product_name','cat_id','product_type as type','status')
                            ->orderBy('id', 'desc')
                            ->where('status','=','1')
                            ->limit(6)
                            ->get();
                          
         $products = (array) $products->toArray();
            
        foreach($products as $key => $product){
            $product_id=$product['id'];
            
            $Type=$product['type'];
            $productType=ProductType::select('type')->where('id','=',$Type)->first();
            $products[$key]['type']=$productType->type;
            
            $Image=DB::table('product_images')->select('image_path')
                                            ->where('product_id', '=', $product_id)
                                            ->where('type', '=', 'banner')
                                            ->first();
            if($Image){
                $name=$Image->image_path;
                $url =  url('/').'/images/productmedia/full_image/'.$name;
            }
            $products[$key]['Image'] =$url;
            
            $category=$product['cat_id'];
            $cateString=str_replace("#","",$category);
            $cateId=explode(",",$cateString);
            foreach($cateId as $keyCat=>$value){
              $cateId[$keyCat]=(int)$value;
            }
            $categories=Category::select('id','category_name')->whereIn('id',$cateId)->get();
            $categories = (array) $categories->toArray();
          
            unset($products[$key]['cat_id']);
            $products[$key]['categories'] =$categories;
        }
        
        $active['listings'] = $products;
        $total_buyers=User::all()->count();
        $products=Product::select('id')->where('status','=','3')->get();
        if(count($products)>0){
            foreach($products as $product){
                $sold_listing_cost = Offer::where('product_id','=',$product->id)->sum('offered_amount');
            }
            $active['stats']['sold_listing_cost'] = $sold_listing_cost;
        }
        
        $sold_listing=count($products);

        $active['stats']['sold_listing']=$sold_listing;
        
        $active['stats']['total_buyers']=$total_buyers;
        
        $response = array('status'=> 200, 'message'=>"get Latest Listings.",'latestListings'=>$active);
        return response()->json($response);

    }
    
    
    public function getBuyerListings(Request $request){
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            // do something
            
            $error = $e->getMessage(); 
            return response()->json(['error' =>$e->getMessage()], 401);
        
        }
        $userId = auth()->user()->id;

        
        /*------------------------------Acive Buyer Listing------------------------------------*/    

        $active = array();
        
        $products = DB::table('products')
                        ->select('products.id as id', 'products.user_id', 'products.product_name as name','products.product_type as type','offers.offered_amount','offers.created_at')
                        ->rightJoin('offers', 'offers.product_id', '=', 'products.id')
                        ->where('offers.buyer_id','=',$userId)
                        ->where('products.status','=','1' )
                        //->where('offers.awarded','=','0')
                        ->get();
                     
        $products = (array) $products->toArray();

        if(count($products)>0){
            
            foreach($products as $key => $product){
                
                $product = (array) $product;
                $productType=$product['type'];
                $product_id=$product['id'];
                $productUserId=$product['user_id'];
                
                $product_type = ProductType::select('type')->where('id','=',$productType)->first();
                $total_offer=DB::table('offers')->where('product_id', '=',$product_id)->count();
                $total_amount = 0;
                if($total_offer != 0){
                    $total_amount=DB::table('offers')->where('product_id', '=',$product_id)->sum('offered_amount');
                    
                    $total_amount = $total_amount/$total_offer;
                    
                }
                $image=DB::table('product_images')->select( 'id','image_path','type')->where('product_id', '=', $product_id)->get();
                foreach($image as $img){
                    $name=$img->image_path;
                    $url =  url('/').'/images/productmedia/full_image/'.$name;
                    $img->image_path =$url;
                }
                $images = (array)  $image->toArray();
                
                $seller=User::select('id as seller_id','name as seller_name','username as seller_username')->where('id','=',$productUserId)->first();
                $seller = (array)  $seller->toArray();
                
                $product['image'] = $images;
                $product['type']=$product_type->type;
                $product['avg_offers'] = $total_amount;
                $product['my_offer'] = $product['offered_amount'];
                $product['seller']=$seller;
                $product['bid_placed'] = $product['created_at'];
                unset($product['created_at']);
                unset($product['offered_amount']);
                $active[] = $product;
            }
            
        }    
        
        
        /*------------------------In-progress Buyer Listing----------------------------------*/
        $in_progress=array();
        $products1 = DB::table('products')
                        ->select('products.id as id','products.user_id', 'products.product_name as name','products.product_type as type','offers.offered_amount','offers.created_at')
                        ->rightJoin('offers', 'offers.product_id', '=', 'products.id')
                        ->where('offers.buyer_id','=',$userId)
                        ->where('products.status','=', 2 )
                        ->where('offers.awarded','=', 1)
                        ->get();

        $products1 = (array) $products1->toArray();
        if(count($products1)>0){
            foreach($products1 as $key => $product1){
                
                $product1 = (array) $product1;
                $productType1=$product1['type'];
                $product_id1=$product1['id'];
                $productUserId1=$product1['user_id'];
                
                $product_type1 = ProductType::select('type')->where('id','=',$productType1)->first();
                 
                $total_offer1=DB::table('offers')->where('product_id', '=', $product_id1)->count();
                $total_amount1 = 0;
                if($total_offer1 != 0){
                    $total_amount1=DB::table('offers')->where('product_id', '=', $product_id1)->sum('offered_amount');
                    $total_amount1 = $total_amount1/$total_offer1;
                    
                }
                $image1=DB::table('product_images')->select( 'id','image_path','type')->where('product_id', '=', $product_id1)->get();
                foreach($image1 as $img){
                    $name=$img->image_path;
                    $url =  url('/').'/images/productmedia/full_image/'.$name;
                    $img->image_path =$url;
                }
                $images = (array)  $image1->toArray();
                
                $seller1=User::select('id as seller_id','name as seller_name','username as seller_username')->where('id','=',$productUserId1)->first();
                $seller1 = (array)  $seller1->toArray();
                
                $product1['image'] = $images;
                $product1['type']=$product_type1->type;
                $product1['avg_offers'] = $total_amount1;
                $product1['my_offer'] = $product1['offered_amount'];
                $product1['seller']=$seller1;
                $product1['bid_placed'] = $product1['created_at'];
                unset($product1['created_at']);
                unset($product1['offered_amount']);
                $in_progress[] = $product1;
            }
            
        }        
         
         /*-------------------------------------------Purchased buyer Listing--------------------------*/
         
         $purchased = array();
         $products2 = DB::table('products')
                        ->select('products.id as id','products.user_id', 'products.product_name as name','products.product_type as type','offers.offered_amount','offers.created_at')
                        ->rightJoin('offers', 'offers.product_id', '=', 'products.id')
                        ->where('offers.buyer_id','=',$userId)
                        ->whereIn('products.status',[3,4])
                        ->where('offers.awarded','=','1')
                        ->get();

        $products2 = (array) $products2->toArray();
        
        if(count($products2)>0){
            
            foreach($products2 as $key => $product2){
                
                $product2 = (array) $product2;
                $productType2=$product2['type'];
                $product_id2=$product2['id'];
                $productUserId2=$product2['user_id'];
                
                $product_type2 = ProductType::select('type')->where('id','=',$productType2)->first();
                 
                $my_offer2=DB::table('offers')->where('buyer_id', '=', $userId)->where('product_id', '=', $product_id2)->count();
                
                $total_offer2=DB::table('offers')->where('product_id', '=', $product_id2)->count();
                $total_amount2 = 0;
                if($total_offer2 != 0){
                    $total_amount2=DB::table('offers')->where('product_id', '=', $product_id2)->sum('offered_amount');
                    $total_amount2 = $total_amount2/$total_offer2;
                    
                }
                $image=DB::table('product_images')->select( 'id','image_path','type')->where('product_id', '=', $product_id2)->get();
                foreach($image as $img){
                    $name=$img->image_path;
                    $url =  url('/').'/images/productmedia/full_image/'.$name;
                    $img->image_path =$url;
                }
                $images = (array)  $image->toArray();
                
                $seller2=User::select('id as seller_id','name as seller_name','username as seller_username')->where('id','=',$productUserId2)->first();
                $seller2 = (array)  $seller2->toArray();
                
                $product2['image'] = $images;
                $product2['type']=$product_type2->type;
                $product2['avg_offers'] = $total_amount2;
                $product2['my_offer'] = $product2['offered_amount'];
                $product2['seller']=$seller2;
                $product2['bid_placed'] = $product2['created_at'];
                unset($product2['created_at']);
                unset($product2['offered_amount']);
                 
                $purchased[] = $product2;
               
            }
        } 
        $buyerProduct['active']  = $active;
        $buyerProduct['in_progress']  = $in_progress;  
        $buyerProduct['purchased']  = $purchased;  
        $response = array('status'=> 200, 'message'=>" ",'buyerProduct'=>$buyerProduct);
        return response()->json($response); 
    }
    
    public function purchaseSucceed($id,Request $request){
        
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            // do something
            
            $error = $e->getMessage(); 
            return response()->json(['error' =>$e->getMessage()], 401);
        
        }
        $buyerId = auth()->user()->id;
        
        
        $product=Product::find($id);
        
        $product->status=3;
        $product->save();
        $offers=Offer::where('buyer_id','=',$buyerId)->where('product_id','=',$id)->first();
        $offers->awarded=1;
        $offers->update();
        
        $response = array('status'=> 200, 'message'=>" Product purchase Succeeded.");
        return response()->json($response);
        
        
    }
    
    
    public function offerAccept($id, Request $reuest){
        
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            // do something
            
            $error = $e->getMessage(); 
            return response()->json(['error' =>$e->getMessage()], 401);
        
        }
        $sellerId = auth()->user()->id;
        
        $offerProductId=offer::select('product_id')->where('id','=',$id)->first();
        $product=Product::where('id','=',$offerProductId->product_id)->first();
        $product->status=2;
        $product->update();
        
        $offers=Offer::where('id','=',$id)->first();
        $offers->awarded = 1;
        $offers->update();
        $response = array('status'=> 200, 'message'=>"Offer Accepted");
        return response()->json($response);
    }
    
    public function offerReject($id, Request $reuest){
        
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            // do something
            
            $error = $e->getMessage(); 
            return response()->json(['error' =>$e->getMessage()], 401);
        
        }
        $sellerId = auth()->user()->id;
        
        $offerProductId=offer::select('product_id')->where('id','=',$id)->first();
        $product=Product::where('id','=',$offerProductId->product_id)->first();
        $product->status=2;
        $product->update();
        
        $offers=Offer::where('id','=',$id)->first();
        $offers->awarded = 2;
        $offers->update();
        $response = array('status'=> 200, 'message'=>"Offer Rejected");
        return response()->json($response);
    }
    
    public function requestAccept($id, Request $reuest){
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            // do something
            
            $error = $e->getMessage(); 
            return response()->json(['error' =>$e->getMessage()], 401);
        
        }
        $sellerId = auth()->user()->id;
        
        $Productaccess=Productaccess::where('id','=',$id)->first();
        $Productaccess->status = 1;
        $Productaccess->update();
        $response = array('status'=> 200, 'message'=>"Request Accepted");
        return response()->json($response);
    }
    
    public function requestReject($id, Request $reuest){
        
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            // do something
            
            $error = $e->getMessage(); 
            return response()->json(['error' =>$e->getMessage()], 401);
        
        }
        $sellerId = auth()->user()->id;
        
        $Productaccess=Productaccess::where('id','=',$id)->first();
        $Productaccess->status = 2;
        $Productaccess->update();
        $response = array('status'=> 200, 'message'=>"Request Rejected");
        return response()->json($response);
    }
    
    
    public function offerCounter(Request $request){
        try {
                $user = auth()->userOrFail();
            } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
                // do something
                
                $error = $e->getMessage(); 
                return response()->json(['error' =>$e->getMessage()], 401);
            }
            
        $postbody = $request->json()->all();
        
        $validator = Validator::make($postbody, [ 
            'offer_id' => 'required|numeric',
            'counter_offer' => 'required'
        ]);
        
        if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
        }
        $postbody = (object) $postbody;

        $offer =Offer::where('id','=',$postbody->offer_id)->first();
        $offer->counter_offer=$postbody->counter_offer;
        $offer->update();
        
        $response = array('status'=> 200, 'message'=>"Counter offer save Sucessfully");
        return response()->json($response);
        
    }
    

}
