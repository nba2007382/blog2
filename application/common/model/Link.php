<?php

namespace app\common\model;
use think\model\concern\SoftDelete;
use think\Model;

class Link extends Model
{
    protected $pk = 'lid';
    protected $table = 'link';
    use SoftDelete;
    protected $deleteTime = 'delete_time';
}
