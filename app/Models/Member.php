<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPasswordNotification;

class Member extends Authenticatable
{
    protected $table = 'members';
    protected $fillable = ['phone','name','password','avatar','pay_time','uuid','remark'];
    protected $hidden = ['password','remember_token'];


}
