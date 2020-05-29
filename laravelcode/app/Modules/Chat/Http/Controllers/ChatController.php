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
        $other_party=$request->other_party;
        

        $details = array();
        
        if($other_party){
                            
            $chats = DB::select(DB::raw('SELECT * FROM ( SELECT * FROM `chats` WHERE product_id = '.$productId.' AND 
                            (( sender_id = '.$userId.' AND receiver_id = '.$other_party.' ) OR 
                            ( sender_id = '.$other_party.' AND receiver_id = '.$userId.' )) 
                            ORDER BY id DESC LIMIT 100 ) chats ORDER BY id ASC'));
            
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
            
        }else{
            
            $chats = DB::select(DB::raw('SELECT * FROM ( SELECT * FROM `chats` WHERE product_id = '.$productId.' AND 
            (sender_id = '.$userId.' OR receiver_id = '.$userId.') ORDER BY id DESC LIMIT 100 ) 
            chats ORDER BY id ASC'));
    
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
            
        }

        $response = array('status'=> 200, 'message'=>"Chat Details",'chat'=>$details);
        return response()->json($response);
	}	

    public function loadMore(Request $request){
        
        try {
                $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            // do something
            
            $error = $e->getMessage(); 
            return response()->json(['error' =>$e->getMessage()], 401);
        }
        
        
        $validator = Validator::make($request->all(), [ 
            'product_id' => 'required',
            'reference_id' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 200);     
        }
        
        $userId = auth()->user()->id;
        $productId=$request->product_id;
        $other_party=$request->other_party;
        $reference_id=$request->reference_id;
        
        $details = array();
        
        if($other_party){
            
            $chats = DB::select(DB::raw('SELECT * FROM ( SELECT * FROM `chats` WHERE product_id = '.$productId.' and id < '.$reference_id.'  AND 
                        (( sender_id = '.$userId.' AND receiver_id = '.$other_party.' )  OR ( sender_id = '.$other_party.' 
                        AND receiver_id = '.$userId.' )) ORDER BY id DESC LIMIT 100) chats ORDER BY id ASC'));
            
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
        }   
        else{
            
            $chats = DB::select(DB::raw('SELECT * FROM ( SELECT * FROM `chats` WHERE product_id = '.$productId.' and id < '.$reference_id.' AND 
                                    (sender_id = '.$userId.' OR receiver_id = '.$userId.') ORDER BY id DESC LIMIT 100 ) chats ORDER BY id ASC'));
            
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
        }
        $response = array('status'=> 200, 'message'=>"Previous Chat Details",'chat'=>$details);
        return response()->json($response);   
    }


	
	
	
	
    
}
