<?php

namespace App\Modules\Chat\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Chat\Models\Chat as Chat;
use App\User as User;
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
    /*  try {
                $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            // do something
            
            $error = $e->getMessage(); 
            return response()->json(['error' =>$e->getMessage()], 401);
        }*/

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

        $details = array();

        $chats = Chat::where('product_id','=',$productId)->where('sender_id','=',$userId)->orWhere('receiver_id','=',$userId)->get();
        
        $chats =(array) $chats->toarray();
        
        if(count($chats)>0){
            foreach ($chats as $chat) {
                $chat = (array) $chat;
                
                $senderId=$chat['sender_id'];
                $receiverId= $chat['receiver_id'];
                $SenderDetail=User::select('id','image_path as image','name')->where('id','=',$senderId)->first();
                if($SenderDetail){
                    $SenderDetail =(array) $SenderDetail->toarray();
                    $name=$SenderDetail['image'];
                    if($name){
                        $destination_path=public_path('/images/profileimages/full_image/');
                        $destination = str_replace("laravelcode/public/","",$destination_path);
                        if (file_exists($destination.$name)) {
                            $url = url('/').'/images/profileimages/full_image/'.$name;
                            $SenderDetail['image']=$url;
                         }else{
                            $SenderDetail['image']=''; 
                         }
                    }
                    $chat['sender_detail']=$SenderDetail;
                }
                $ReciverDetail=User::select('id','image_path as image','name')->where('id','=',$receiverId)->first();
                if($ReciverDetail){
                    
                    $ReciverDetail =(array) $ReciverDetail->toarray();
                    $image=$ReciverDetail['image'];
                    if($name){
                        $destination_path=public_path('/images/profileimages/full_image/');
                        $destination = str_replace("laravelcode/public/","",$destination_path);
                        if (file_exists($destination.$image)) {
                            $url = url('/').'/images/profileimages/full_image/'.$image;
                            $ReciverDetail['image']=$url;
                         }else{
                            $ReciverDetail['image']=''; 
                         }
                    }
                    $chat['receiver_detail']=$ReciverDetail;
                }
                
                unset($chat['updated_at']);
                $details[] = $chat;
            }
            
        }
       
        $response = array('status'=> 200, 'message'=>"Chat Details",'chat'=>$details);
        return response()->json($response);
    }   




    
    
}
