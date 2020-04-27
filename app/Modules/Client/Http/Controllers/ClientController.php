<?php

namespace App\Modules\Client\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User as User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Classes\AutoResponder;

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
            'city_id' => 'required|max:20',
            'status' =>'required',
        
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
                    $user->state_id = $request->state_id;
                    $user->country_id = $request->country_id;
                    $user->zip_code = $request->zip_code;
                    $user->phone_no = $request->phone_no;
                    $user->city_id = $request->city_id;
                    $user->status =$request->status;
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
            $destinationPath = public_path('/images/upload/');
            move_uploaded_file($temp_name, $destinationPath.$name);
            $response = array('status'=> 200, 'message'=>"Image uploaded successfully.",'image_name'=>$name,'path'=>$destinationPath);
            return response()->json($response);
        }else{
            $response = array('status'=> 200, 'message'=>"Image not uploaded.");
            return response()->json($response);
        }
        
    }

}
