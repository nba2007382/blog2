<?php

namespace app\admin\validate;

use think\Validate;

class UserValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'uname'=>'token|checkName',
        'province'=>'require',
        'city'=>'require',
        'county'=>'require'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'province'=>'省不能为空',
        'city'=>'市不能为空',
        'county'=>'县/区不能为空'
    ];
    //自定义规则
    protected function checkName($value){
        return in_array($value,["草你","死","强奸","操你妈","草"])?"违规昵称，请重新输入":true;
    }
}
