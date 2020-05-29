<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;


use Closure;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        //  $data = $request->session()->all();
        // print_r($data );
        // echo "here"; die();
        
       if ($request->session()->has('adminSessionData')) {
             return $next($request);
        }else{
             return redirect('/login');
        }

    }

    public function guard()
    {
    return Auth::guard('api');
    }
}