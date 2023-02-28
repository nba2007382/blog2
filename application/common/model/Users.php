<?php

namespace app\common\model;
use think\model\concern\SoftDelete;
use think\Model;

class Users extends Model
{
    protected $pk = 'uid';
    protected $table = 'users';
    use SoftDelete;
    protected $deleteTime = 'delete_time';
}
