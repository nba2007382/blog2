<?php

namespace app\common\model;

use think\Model;

class Comments extends Model
{
    protected $pk='cid';
    protected $table='comments';
    //关联用户表
    public function userComments(){
        return $this->belongsTo('users','uid','uid');
    }

}
