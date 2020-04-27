<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\User;
use App\PasswordReset;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
    	
        $request->validate([
            'email' => 'required|string|email',
        ]);
        
        $user = User::where('email', $request->email)->first();

      /*  dd($user);*/

		if (!$user){
            $response = array('status'=>'false', 'message'=>'We can\'t find a user with that e-mail address.');
            return response()->json($response);
		}		
		$passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => str_random(60)
             ]
        );
        $resetLink =  "<a target='_blank' href='trigvent.com/extensionbuyer/reset-password/".$passwordReset['token']."'> Click Here to reset password</a>";
        $to = $user['email'];
        $subject = 'Reset password';
        $headers = "From: extensionbuyer@gmail.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $message ="<p><strong>Hey " .$user['name'] . "</strong></p>";
        $message .="We received a request to reset your password  ".$resetLink."\r\n";
        mail($to, $subject, $message, $headers);
        $response = array('status'=>'true', 'message'=>'We have e-mailed your password reset link!');
        return response()->json($response);
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->first();
        if (!$passwordReset)
            $response = array('status'=> 200, 'message'=>'Token is invalid.');
            return response()->json($response);
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            $response = array('status'=> 200, 'message'=>'This password reset token is invalid.');
            return response()->json($response);
        }
        return response()->json($passwordReset);
    }

    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        
        $request->validate([
            'newPassword' => 'required|string	',
            'resetPasswordToken' => 'required|string'
        ]);
        $passwordReset = PasswordReset::where([
            ['token', $request->resetPasswordToken]
        ])->first();
        
        if (!$passwordReset){
            $response = array('status'=> 200, 'message'=>'This password reset token is invalid.');
            return response()->json($response);
        }    
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user){
    		$response = array('status'=> 200, 'message'=>'We can\'t find a user with that e-mail address.');
            return response()->json($response);
		}
        $user->password = bcrypt($request->newPassword);
        $user->save();
        $passwordReset->delete();
        $response = array('status'=> 200, 'message'=>'password changed successfully.');
            return response()->json($response);
    }
}
