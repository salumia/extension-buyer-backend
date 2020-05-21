<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User as User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Image;
class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
    	auth()->setDefaultDriver('api');
        $this->middleware('auth:api', ['except' => ['testing','login','registration','refresh']]);
    //   $this->middleware('cors');
    }



    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid Credentials'], 401);
        }
        
        return $this->respondWithToken($token);

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
      try {
                $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            // do something
            
            $error = $e->getMessage(); 
            return response()->json(['error' =>$e->getMessage()], 401);
        
        }
      $userData = auth()->user();
      if(!is_null($userData->image_path)){
          
          $userData->image_path =  url('/').'/images/profileimages/full_image/'.$userData->image_path;
          
          $userData->image_path = str_replace('server.php','public',$userData->image_path );
      } 
    
      return response()->json($userData);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
       
        
        try {
                $user = auth()->userOrFail();
            } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
                // do something
                
                $error = $e->getMessage(); 
                return response()->json(['error' =>$e->getMessage()], 401);
            
            }
            
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = Auth::user();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 6000,
            'user'=>$user
        ]);
    }

	public function registration(Request $request) 
    { 
        
        $validator = Validator::make($request->all(), [ 
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'login_type' => 'required'
        ]);
        
       	if ($validator->fails()) { 
				return response()->json(['error'=>$validator->errors()], 401);            
		}
		
        $input = $request->all(); 
         
        if($input['login_type'] == 'web'){
            $user=$request->all();
            
		  
		  $validator = Validator::make($request->all(), [ 
				'first_name' => 'required',
				'last_name' => 'required',
				'email' => 'required|email|unique:users',
				'password' =>'required'
				
			   
			]);
			if ($validator->fails()) { 
				return response()->json(['error'=>$validator->errors()], 401);            
			}
			
			$lastName=substr($user['last_name'], 0, 1);
			$uniqueId=rand(100,1000);
            $firstName=explode(" ",$user['first_name']);
			$uniqueUserName=$lastName.$firstName[0].$uniqueId;
			$pw = $request->password;
	
			$user =new User;
			$user->name = $request->first_name;
			$user->last_name = $request->last_name;
			$user->email = $request->email;
			$user->username=$uniqueUserName;
			$user->Password=Hash::make($pw);
			
			$user->save();

			return response()->json(['message'=>'Registration Successfully.'], 200);   
        
        }else if($input['login_type'] == 'google'){
            
            $already = User::where('email',$input['email'] )->get()->count();
            
            if($already > 0){
              
                $userId = User::where('email',$input['email'] )->first();
          
                $token = auth()->tokenById($userId->id);
                return $this->respondWithToken($token);
               
            }else{
                $user=$request->all();
                $pw = bcrypt('%s=adfh8sdf');
                $UserName=substr($user['last_name'], 0, 3);
    			$uniqueId=rand(100,1000);
                $lastName=explode(" ",$user['first_name']);
    			$uniqueUserName=$UserName.$lastName[0].$uniqueId;
	
		    	$user =new User;
    			$user->name = $request->first_name;
    			$user->last_name = $request->last_name;
    			$user->email = $request->email;
    			$user->unique_user_name=$uniqueUserName;
    			$user->Password=Hash::make($pw);
    			
    			$user->save();

                $token = auth()->tokenById($user->id);
                return $this->respondWithToken($token);
            } 
        }
    }
}