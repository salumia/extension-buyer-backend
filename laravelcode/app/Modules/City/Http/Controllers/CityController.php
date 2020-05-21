<?php

namespace App\Modules\City\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\City\Models\City as City;
class CityController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("City::welcome");
    }
    
    
    public function getCity($id, Request $request)
    {
        $city=City::where('state_id',$id)->orderBy('name','ASC')->get();
        $response = array('status'=> 200, 'city'=> $city);
        return response()->json($response);
    }
}
