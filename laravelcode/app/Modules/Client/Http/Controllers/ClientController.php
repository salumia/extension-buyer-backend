<?php

namespace App\Modules\Client\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User as User;
use App\Modules\Product\Models\Product as Product;
use App\Modules\Country\Models\Country as Country;
use App\Modules\State\Models\State as State;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Classes\AutoResponder;
use Illuminate\Support\Facades\DB;
use Image;

class ClientController extends Controller
{

    public function __construct()
    {
       
    	auth()->setDefaultDriver('api');
    	 header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');
    	
    }
    
     public function updateProfile(Request $request)
    {
        
          try {
                $user = auth()->userOrFail();
            } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
                // do something
                
                $error = $e->getMessage(); 
                return response()->json(['error' =>$e->getMessage()], 401);
            
            }
            
           
    
          $validator = Validator::make($request->all(), [ 
            'first_name' => 'required',
          //  'password' => 'required|min:6|max:20',
            'last_name' => 'required',
            'update_image'=>'required|numeric',
            'email' => 'required|email',
            'address_line' => 'required',
            'country_id' => 'required|max:20',
            'state_id' =>'required|max:20',
            'zip_code' => 'required|max:20',
            'phone_no' => 'required',
            'city_id' => 'required|max:20'
        
        ]);
        
       	if ($validator->fails()) { 
				return response()->json(['error'=>$validator->errors()], 401);            
		}
		
            
          $userId = auth()->user()->id;
          $user = User::find($userId);
          
          $imagePath = '';
          
          $updateImage = false;
          
          if($request->update_image == 1){
                $updateImage = true;
                /*if ($request->hasFile('uploadimage')) {
                    
                    $validator = Validator::make($request->all(), [ 
                        'uploadimage' => 'image|mimes:jpeg,png,jpg,svg|max:2048'
                    ]);
                    
                    if ($validator->fails()) { 
                    	return response()->json(['error'=>$validator->errors()], 401);            
                    }
            
                    $image = $request->file('uploadimage');
                    $name = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/profileimages');
                    $image->move($destinationPath, $name);
                    $imagePath = $name;
                }else{
                    $imagePath = null;
                }*/
                if($request->uploadimage != ""){
                    $imagePath = $request->uploadimage;
                } else {
                    $imagePath = null;
                }
          }
          
           $already = User::where('id','!=',$userId)->where('email',$request->email)->get()->count();
           if(!$already){
           $user = User::find($userId);
               
                if($user) {
                    $user->name = $request->first_name;
                    $user->last_name = $request->last_name;
                    $user->address_line = $request->address_line;
                    $user->email = $request->email;
                    $user->country_id = $request->country_id;
                    $user->state_id = $request->state_id;
                    $user->zip_code = $request->zip_code;
                    $user->phone_no = $request->phone_no;
                    $user->city_id = $request->city_id;
                   // $user->password = Hash::make($request->password);
                    
                    if($updateImage){
                        $user->image_path = $imagePath;
                    }
                    $user->email = $request->email;
                    $user->save();
                }
    
            $response = array('status'=> 200, 'message'=>"Profile updated successfully.");
            return response()->json($response);
           }else{
				return response()->json(['error'=>['email'=>'This email is already taken.']], 401);            
           }
       
           
    }
    
    
     public function checkEmailUpdate(Request $request)
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
            'email' => 'required|email'
        ]);
        
       	if ($validator->fails()) { 
				return response()->json(['error'=>$validator->errors()], 401);            
		}
		
            
          $userId = auth()->user()->id;
    
           $already = User::where('id','!=',$userId)->where('email',$postbody['email'])->get()->count();
           if(!$already){
           $user = User::find($userId);
               
                if($user) {
                    $user->email = $postbody['email'];
                    $user->save();
                }
    
            $response = array('status'=> 200, 'message'=>true);
            return response()->json($response);
           }else{
                 $response = array('status'=> 200, 'message'=>false);
                 return response()->json($response);
           }
       
           
    }

    public function changePassword(Request $request){


      try {
                $user = auth()->userOrFail();
            } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
                // do something
                
                $error = $e->getMessage(); 
                return response()->json(['error' =>$e->getMessage()], 401);
            
            }
          
        //$postbody = $request->all();  
        $postbody = $request->json()->all();
        $validator = Validator::make($postbody, [ 
            'password' => 'required'
        ]);
        
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        } 
        $userId = auth()->user()->id;
        $user = User::find($userId);
        if($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            $response = array('status'=> 200, 'message'=>true);
            return response()->json($response);
        }else{
            $response = array('status'=> 200, 'message'=>false);
            return response()->json($response);
       }

    }


    public function uploadImage(Request $request) 
    {
        $image;
        $temp_name;
        $data=$request->all();
        if($data){
            $images = $_FILES["demo"]["name"];
        
            foreach($images as $img){
                $image=$img;
            }
            $temp_names=$_FILES['demo']['tmp_name'];
            foreach($temp_names as $temp){
                $temp_name=$temp;
            }
            $imageFileType = pathinfo($image,PATHINFO_EXTENSION);
            $name = time().'.'.$imageFileType;
            $destination=public_path('/images/profileimages/full_image/');
            $destinationPath = str_replace("laravelcode/public/","",$destination);
            
            move_uploaded_file($temp_name, $destinationPath.$name);
            
            $url =  url('/').'/images/profileimages/full_image/'.$name;
            $complete_path =$url;

            $this->createThumbnailForProfileImage($name);
            
            $response = array('status'=> 200, 'message'=>"Image uploaded successfully.",'image_name'=>$name,'path'=>$complete_path);
            return response()->json($response);
        }else{
            $response = array('status'=> 200, 'message'=>"Image not uploaded.");
            return response()->json($response);
        }
        
    }

    public function createThumbnailForProfileImage($imageName)
    {            
      //  $path = public_path('images/productmedia/'.$imageName);
        $destination = public_path('/images/profileimages/100x100/'.$imageName);
        $path = str_replace("laravelcode/public/","",$destination);
        
        $savePath100 = $path;

        $destination = public_path('/images/profileimages/250x250/'.$imageName);
        $path = str_replace("laravelcode/public/","",$destination);
        $savePath250 =  $path;

        $destination = public_path('/images/profileimages/500x500/'.$imageName);
        $path = str_replace("laravelcode/public/","",$destination);

        $savePath500 = $path;

        $destination = public_path('/images/profileimages/full_image/'.$imageName);
        $path = str_replace("laravelcode/public/","",$destination);
        

        $img = Image::make($path);
        $img->backup();
        $img->resize(100, 100 /*,function ($constraint) {
        $constraint->aspectRatio();
        } */);
        $img->save($savePath100);
        $img->reset();

        $img = Image::make($path);
        $img->backup();
        $img->resize(250, 250 /*,function ($constraint) {
        $constraint->aspectRatio();
        } */);
        $img->save($savePath250);
        $img->reset();

        $img = Image::make($path);
        $img->backup();
        $img->resize(500, 500 /*,function ($constraint) {
        $constraint->aspectRatio();
        } */);
        $img->save($savePath500);
        $img->reset();

        return true;
    }
    
    public function getPublicProfile(Request $request)
    {
        $username=$request['username'];
        $user=User::select('id','name','last_name','username','image_path','country_id as country','state_id as state','created_at as joinDate')->where('username','=',$username)->first();
        if($user){
            $user= (array) $user->toArray();
            $userId=$user['id'];
            $country_id=$user['country'];
            $country=Country::select('name')->where('id','=',$country_id)->first();
            $user['country']=$country->name;
            $state_id=$user['state'];
            $state=State::select('name')->where('id','=',$state_id)->first();
            $user['state']=$state->name;
            $imageName=$user['image_path'];
            if($imageName){
                $url =  url('/').'/images/profileimages/full_image/'.$imageName;
                $user['image_path'] =$url;
            }
            $total_listing =Product::where('user_id','=',$userId)->count();
            $sold_Listing=Product::where('user_id', '=', $userId)->where('status', '=',3)->orWhere('status','=',4)->count();
            
            $user['total_listing']=$total_listing;
            $user['sold_Listing']=$sold_Listing;
            unset($user['id']);
        }
        $response = array('status'=> 200, 'message'=>"User Details.",'user'=>$user);
        return response()->json($response);
        
    }

}
