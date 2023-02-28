<?php

namespace app\common\model;
use think\model\concern\SoftDelete;
use think\Model;

class Blogtype extends Model
{
    protected $pk = 'tid';
    protected $table = 'blogtype';
    use SoftDelete;
    protected $deleteTime = 'delete_time';
}
