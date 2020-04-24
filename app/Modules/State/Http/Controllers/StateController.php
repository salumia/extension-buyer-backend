<?php

namespace App\Modules\State\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\State\Models\State as State;


class StateController extends Controller
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
    public function welcome()
    {
        return view("State::welcome");
    }
     public function getState($id, Request $request)
    {
        $state=State::where('country_id',$id)->orderBy('name','ASC')->get();
        $response = array('status'=> 200, 'state'=> $state);
        return response()->json($response);
    }
    
}
