<?php

namespace app\index\controller;

use app\common\model\Users;use think\Controller;use think\Db;use think\Request;

class Login extends Controller
{
    public function login(){
        return view('index@login/login');
    }
    //处理登录数据
    public function getLoginData(Request $request){
         $data = $request->post();
        if (strtolower($data['captcha'] )!= session('captcha')) {
            return ['code' => 0, 'msg' => '验证码错误!'];
        }
        $user=Users::where('account',$data['account'])->find();
         if (!$user) {
            return ['code' => 0, 'msg' => '帐号错误!'];
        }
         if (md5($data['password']) != md5($user['password'])) {
            return ['code' => 0, 'msg' => '密码错误!'];
        }
         $updata=Db::name('users')->where('account',$data['account'])->update([
             'last_login_ip' => $_SERVER['REMOTE_ADDR'],
            'login_times' => $user['login_times'] + 1,
            'last_login_time'=>date('Y-m-d H:i:s')
]);
          session('user', $user);
        return ['code'=>1,'msg'=>'登录成功!'];
    }
}
