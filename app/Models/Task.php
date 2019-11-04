<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPasswordNotification;

class Task extends Authenticatable
{
    protected $table = 'tasks';
    protected $fillable = ['id','name','task_id'];
    protected $hidden = ['password','remember_token'];
	 #定义隐藏的字段
    // protected $hidden = [];
    
    /******时间管理******/
    #定义是否默认维护时间，默认是true.改为false，则以下时间相关设定无效
    public $timestamps = false;
    #定义数据行创建时间和修改时间的字段名称。默认created_at,updated_at,没有设null
    const CREATED_AT = 'created';
    const UPDATED_AT = null;
	#把ORM查询的数据自动转换。例如把int转boolean，时间戳转时间，json转成数组等。
    protected $casts = [
        'created'   => 'date:Y-m-d',
        'updated'   => 'datetime:Y-m-d H:i',
        'jsonData'  => 'array',
        'intSwitch' => 'boolean'
    ];

}
