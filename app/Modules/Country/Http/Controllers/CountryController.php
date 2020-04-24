<?php

namespace App\Modules\Country\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Country\Models\Country as Country;

class CountryController extends Controller
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
        return view("Country::welcome");
    }
    
    public function getAllCountry()
    {
        $country =  Country::orderBy('name','ASC')->get();
        $response = array('status'=> 200, 'country'=> $country);
        return response()->json($response);

    }
}
