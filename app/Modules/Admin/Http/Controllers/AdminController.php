<?php

namespace App\Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

/*    public function getUser()
    {
        echo "hrere"; die();
        return view("Admin::user.users");
    }*/

    

}

