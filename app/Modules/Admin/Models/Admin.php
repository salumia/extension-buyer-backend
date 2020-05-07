<?php

namespace App\Modules\Admin\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\AdminResetPasswordNotification;

class Admin extends Authenticatable
{
	 use Notifiable;
   /* protected $table = 'admins';*/

   public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
