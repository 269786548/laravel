<?php
namespace App\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

trait DbAffair{

    //开启事务
    public function startAffair(){
        DB::beginTransaction();
    }
    //提交事务
    public function commitAffair(){
        DB::commit();
    }
    //回滚
    public function rollBackAffair(){
        DB::rollBack();
    }

}