<?php

namespace App\Modules\Chat\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Chat\Models\Chat as Chat;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
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

    public function StoreChat(Request $request){
    	try {
                $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            // do something
            
            $error = $e->getMessage(); 
            return response()->json(['error' =>$e->getMessage()], 401);
        }

        $validator = Validator::make($request->all(), [ 
            'message' => 'required',
            'product_id' => 'required',
            'sender_id' => 'required',
            'receiver_id' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 200);     
        }
        $chat= new Chat;
        $chat->message=$request->message;
        $chat->product_id=$request->product_id;
        $chat->sender_id=$request->sender_id;
        $chat->receiver_id=$request->receiver_id;
        $chat->save();

        $response = array('status'=> 200, 'message'=>"Chat Save Sucessfully");
        return response()->json($response);

    }

    public function ChatDetails(Request $request){

    	try {
                $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            // do something
            
            $error = $e->getMessage(); 
            return response()->json(['error' =>$e->getMessage()], 401);
        }
        $userId = auth()->user()->id;
        $productId=$request->product_id;

        //$chat = Chat::where('product_id','=',$productId)->where('sender_id','=',$userId)->orWhere('receiver_id','=',$userId)->get();

        $chats = DB::table('users as t1')->select('t1.id as id','t1.image_path as image','t1.name','t2.id','t2.product_id','t2.sender_id','t2.receiver_id','t2.created_at')
            ->leftJoin('chats as t2', 't1.id', '=', 't2.receiver_id')->where('t2.product_id','=',$productId )->where('t2.sender_id','=',$userId )->orwhere('t2.receiver_id','=',$userId)
            ->get();

         foreach ($chats as $chat) {
         	$name=$chat->image;
         	if($name){
	            $destination_path=public_path('/images/profileImage/full_image/');
	            $destination = str_replace("laravelcode/public/","",$destination_path);
	            if (file_exists($destination.$name)) {
	                $url = url('/').'/images/profileImage/full_image/'.$name;
	                $chat->image=$url;
	             }else{
	                $chat->image=''; 
	             }
	        }
         }
        $response = array('status'=> 200, 'message'=>"Chat Details",'chat'=>$chats);
        return response()->json($response);
	}	




	
    
}
