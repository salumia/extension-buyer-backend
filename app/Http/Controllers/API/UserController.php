<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User; 
use Illuminate\Support\Facades\Auth; 

class UserController extends Controller
{

    public function validateUserEmailAdd(Request $request)
      {
        $email=$request['email'];
        $already = User::where('email',$email)->get()->count();
         if($already){
          echo json_encode(false);
         }else{
          echo json_encode(true);
         }
         die;
      }

    /*public function validateUserEmailEdit($id, Request $request)
      {
      	$data=$request->all();
      	dd($data);
          $email = $request->input('email');
          $already = User::where('id','!=',$id)->where('email',$email)->get()->count();
         if($already){
          echo json_encode(false);
         }else{
          echo json_encode(true);
         }
         die;
    } */
}
