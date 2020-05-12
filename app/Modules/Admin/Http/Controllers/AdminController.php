<?php

namespace App\Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Admin\Models\Admin as Admin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Session;
class AdminController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }



    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("Admin::welcome");
    }

    public function dashboard()
    {
        return view("Admin::dashboard");
    }
    
    public function adminProfile()
    {

        $admin = \Auth::guard('admin')->user();
        $adminData['id'] =$admin->id;
        $adminData['name'] = $admin->name;
        $adminData['email'] = $admin->email; 
        return view('Admin::profile',compact('adminData'));
    }

    public function editView($id)
    {
        $admin = Admin::find($id);

        return view('Admin::editprofile',compact('admin'));
    }
    public function updateProfile($id, Request $request)
    {
    
        $email = $request->input('email');

        $already = Admin::where('id','!=',$id)->where('email',$email)->get()->count();
       if(!$already){
       $admin = Admin::find($id);
            if($admin) {
                $admin->email = $email;
                $admin->save();
            }

        $name=$admin->name;    
        $adminData['id'] =$id;
        $adminData['email'] = $email;
        $adminData['name']=$name;
        session(['adminSessionData' => $adminData]);
        return redirect('admin/editProfile/'.$id)->with('success','Updated successfully.');
       }else{
            return redirect('admin/editProfile/'.$id)->with('error','This email id is already exist'); 
       }
    }

    public function changePasswordView()
    {
         return view('Admin::changepassword');
    }

    public function changePasswordStore($id, Request $request)
    {
        $password = $request->input('password');
        $admin = Admin::find($id);
        if($admin) {
            $admin->password = Hash::make($password);
            $admin->save();
        }
        return redirect('admin/changePassword')->with('success','Password changed successfully.');
    }

    

}

