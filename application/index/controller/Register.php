<?php

namespace app\index\controller;

use app\common\model\Users;use think\Controller;use think\Request;

class Register extends Controller
{
    public function register(){
        return view('index@register/register');
    }
    //判断注册帐号是否存在
    public function getAccountData(Request $request){
        $account=$request->post('account');
        $data=Users::where('account',$account)->find();
        if ($data){
            return ['code'=>0,'msg'=>'帐号已存在!'];
        }
        else{
            return ['code'=>1,'msg'=>'帐号可用'];
        }
    }
    //处理注册信息
    public function getRegisterData(Request $request){
        $uname=$request->post('uname');
        $account=$request->post('account');
        $password=$request->post('password');
        $email=$request->post('email');
        $captcha=$request->post('captcha');
         if (strtolower($captcha)!= session('captcha')) {
            return ['code' => 0, 'msg' => '验证码错误!'];
        }
        $res=Users::create([
            'uname'=>$uname,
            'account'=>$account,
            'password'=>$password,
            'email'=>$email,
            'birthdate'=>date('Y-m-d H:i:s',time()),
            'pic'=>"static/index/images/common/默认头像女.jpg"
]);
         $user=Users::where('account',$account)->find();
        if ($res){
            session('user',$user);
            return json(['code'=>1,'msg'=>'注册成功!']);
        }else{
            return ['code'=>0,'msg'=>'注册失败!'];
        }
    }
}
