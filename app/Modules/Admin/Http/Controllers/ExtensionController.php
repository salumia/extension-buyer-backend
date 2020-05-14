<?php

namespace App\Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User as User;
use App\Modules\Admin\Models\Category as Category;
use App\Modules\Product\Models\Product as Product;
use App\Modules\Admin\Models\Admin as Admin;
use App\Modules\Product\Models\Product_type as ProductType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Session;

class ExtensionController extends Controller
{
    public $successStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (isset($_GET['extension'])){
            
          $extensions = Product::Where('product_name', 'like', '%' . $_GET['extension'] . '%')->orderBy('id', 'DESC')->get();
        }else{
            $extensions=array();
            $extension=Product::all();
            $extension = (array) $extension->toArray();
            foreach ($extension as $ext) {
                $extension = (array) $ext;
                if($extension){
                    $productType=$extension['product_type'];
                    $categoryIdString=$extension['cat_id'];
                    $cateString=str_replace("#","",$categoryIdString);
                    $cateId=explode(",",$cateString);
                    foreach($cateId as $value){
                       $cate[]=(int)$value;
                    }

                    $product_type = ProductType::select('type')->where('id','=',$productType)->first();
                    $extension['type']=$product_type['type'];
                    $userId=$extension['user_id'];
                    $received_offer=DB::table('products')->where('user_id', '=', $userId)->count();
                    $extension['received_offer']=$received_offer; 

                    $product_seller = User::select('name')->where('id','=',$userId)->first();
                    $extension['seller']=$product_seller['name'];
                    
                }
                $extensions[] = $extension;
                
            }
            //dd($extensions);
        } 
        return view('Admin::extension.extensions',compact('extensions'));
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
        $product = Product::select( 'id','user_id','currency','product_name','product_type as type','visibilty','total_users','cat_id','product_created_date','website','price','negotiate','description','updated_at as updated_date')->where('id', '=', $id)->first();
        $product= (array) $product->toArray();
        //dd($product);
        $productCategories=$product['cat_id'];
        
        $cateString=str_replace("#","",$productCategories);
        $cateId=explode(",",$cateString);
        foreach($cateId as $key=>$value){
           $cateId[$key]=(int)$value;
        }
        $categories=Category::select('category_name')->whereIn('id', $cateId)->get();
        $categories = (array) $categories->toArray(); 
        foreach($categories as $category){
            $category=(array) $category;
            $cateName[] = $category['category_name'];
        }
        $categoriesName=implode(", ",$cateName);
        
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
            ->leftJoin('countries', 'userbases.country_id', '=', 'countries.id')->where('product_id','=',$id)
            ->get();
            
        $bannerImages=DB::table('product_images')->select( 'id','image_path')->where('product_id', '=', $id)->where('type', '=', 'banner')->get();
        
            foreach($bannerImages as $img){
                $name=$img->image_path;
                $url =  url('/').'/laravelcode/public/images/productmedia/'.$name;
                $img->image_path =$url;
            }
            
                
        $statImages=DB::table('product_images')->select( 'id','image_path','type')->where('product_id', '=', $id)->where('type', '=', 'stats')->get();
        
            foreach($statImages as $img){
                $name=$img->image_path;
                $url =  url('/').'/laravelcode/public/images/productmedia/'.$name;
                $img->image_path =$url;
            }
               
        $bannerImages = (array) $bannerImages->toArray(); 
        $statImages = (array) $statImages->toArray(); 
        $product['seller'] = $seller; 
        $product['userbase'] = $userBases;
        $product['banners']=$bannerImages;
        $product['statistics']=$statImages;
        $product['categories']=$categoriesName;
       // dd($product);
        return view('Admin::extension.view_extension',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    public function extensionStatus(Request $request, $id){
            $product=Product::find($id);
            $product->status=$request['status'];
            $product->update();
              
        $response = array('status'=> 200, 'message'=> 'Status Updated Successfully');
       return response()->json($response);
    }
    
    
    
    Public function extensionStatusReject($id, Request $request){
        
        $validator = Validator::make($request->all(), [ 
            'status' => 'required',
            'reason' => 'required'
        ]);
        
        if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
        }
        $product=Product::find($id);
        $product->status=$request['status'];
        $product->reject_reason=$request['reason'];
        $product->update();
        $response = array('status'=> 200, 'message'=> 'Status Updated Successfully');
       return response()->json($response);

    }
    
    public function validateUserEmailCheck(Request $request)
      {
          
        $email=$request['email'];
        $already = User::where('email',$email)->get()->count();
        $admin = Admin::where('email',$email)->get()->count();
         if($already or $admin){
          echo json_encode(false);
         }else{
          echo json_encode(true);
         }
         die;
      }
    
    public function validateAdminEmailCheck(Request $request)
      {
        $email=$request['email'];
        $user =User::where('email',$email)->get()->count();
        $admin = Admin::where('email',$email)->get()->count();
         if($admin or $user){
          echo json_encode(false);
         }else{
          echo json_encode(true);
         }
         die;
      }  
      
      
      
}
