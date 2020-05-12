<?php

/*namespace App\Http\Controllers\Auth;*/
namespace App\Modules\Admin\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Modules\Admin\Models\Admin as Admin;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function showLoginForm()
    {
        return view("Admin::auth.login");
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

/*    public function logout(Request $request)
    {
        $this->guard('admin')->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('admin/login');
    }*/


    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

   

    protected function guard()
    {
        return Auth::guard('admin');

    }

    protected function authenticated(Request $request, Admin $user){
        $admin = \Auth::guard('admin')->user();
        $adminData['id'] =$admin->id;
        $adminData['name'] = $admin->name;
        $adminData['email'] = $admin->email; 
        session(['adminSessionData' => $adminData]);
        return redirect('admin/dashboard');
    }
    
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect('admin/login');
    }


}
