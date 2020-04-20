<?php

namespace App\Modules\Client\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User as User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class ClientController extends Controller
{

    public function __construct()
    {
    	auth()->setDefaultDriver('api');
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
            //'product_image_ids' => 'required',
            'last_name' => 'required',
            'update_image'=>'required',
            'email' => 'required|email',
            'street_no' => 'required',
            'state' =>'required',
            'zip_code' => 'required',
            'country' => 'required'
        
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
                if ($request->hasFile('uploadimage')) {
                    $image = $request->file('uploadimage');
                    $name = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/profileimages');
                    $image->move($destinationPath, $name);
                    $imagePath = $name;
                }else{
                    $imagePath = null;
                }
          }
          
           $already = User::where('id','!=',$userId)->where('email',$request->email)->get()->count();
           if(!$already){
           $user = User::find($userId);
               
                if($user) {
                    $user->name = $request->first_name;
                    $user->last_name = $request->last_name;
                    $user->street_no = $request->street_no;
                    $user->email = $request->email;
                    $user->state = $request->sate;
                    $user->country = $request->country;
                    $user->zip_code = $request->zip_code;
                    
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
    

}
