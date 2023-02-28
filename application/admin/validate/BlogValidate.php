<?php

namespace app\admin\validate;

use think\Validate;

class BlogValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'img'=>'require|token',
        'intro'=>'require',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'img.require'=>'图片必须上传',
        'intro'=>'博文不能为空'
    ];
}
