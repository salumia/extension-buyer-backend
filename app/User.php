<?php

namespace App;
/*
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
*/


use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Modules\Country\Models\Country;
use App\Modules\City\Models\City;
use App\Modules\State\Models\State;


class User extends Authenticatable implements JWTSubject
{

//class User extends Authenticatable

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name','email', 'password','image_path','address_line','phone_no','city','state','country','zip_code',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


        public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function getcountry() {
        return $this->belongsTo(Country::class,'country_id')->select(array('id','name'));
    }
     public function getstate() {
        return $this->belongsTo(State::class,'state_id')->select(array('id','name','country_id'));
    }
     public function getcity() {
        return $this->belongsTo(city::class,'city_id')->select(array('id','name','state_id'));
    }
   
}
